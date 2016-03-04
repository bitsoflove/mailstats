<?php

namespace BitsOfLove\MailStats;

use BitsOfLove\MailStats\Events\EmailAddedToQueue;
use BitsOfLove\MailStats\Exceptions\EmailDataMissmatchException;
use BitsOfLove\MailStats\Exceptions\ProjectNotSupported;
use BitsOfLove\MailStats\Entities\Project;
use BitsOfLove\MailStats\ValueObjects\Emails\From;
use BitsOfLove\MailStats\ValueObjects\Emails\To;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Bogardo\Mailgun\Mailgun;
use Mailgun\Connection\Exceptions\MissingRequiredParameters;

class SendMail
{
    protected $mailgun;

    /**
     * @var To
     */
    protected $to;

    /**
     * @var From
     */
    protected $from;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $view;

    /**
     * @var array
     */
    protected $viewData;

    /**
     * @var array
     */
    protected $tags;

    /**
     * @var Model
     */
    protected $project;

    /**
     * @param To $to
     * @param From $from
     * @param string $subject
     * @param string $view
     * @param array $viewData
     * @param Model $project
     * @param array $tags
     * @param array $options
     */
    public function __construct(
        To $to,
        From $from,
        $subject,
        $view,
        array $viewData = [],
        Model $project,
        array $tags = [],
        array $options = []
    ) {
        $this->mailgun = app('mailgun');

        $options += [
            'view_namespace' => "mail-stats"
        ];

        $this->to = $to;
        $this->from = $from;
        $this->subject = $subject;
        $this->setView($view, $options['view_namespace']);
        $this->viewData = $viewData;
        $this->project = $project;
        $this->tags = $tags;
    }

    /**
     * @return To
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return From
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Set the view that should be used to send the email
     *
     * @param string $view
     * @param null|string $namespace
     * @todo: add possibility to change the namespace from outside the class
     */
    public function setView($view, $namespace = null)
    {
        // no namespace was given for the email views
        if (is_null($namespace) || empty($namespace)) {
            $this->view = $view;
            return;
        }

        // detect if the namespace is all ready included in the view name
        $pattern = "^$namespace::";

        if (!preg_match("/$pattern/", $view)) {
            $this->view = "{$namespace}::{$view}";
        } else {
            $this->view = $view;
        }
    }

    /**
     * @return array
     */
    public function getViewData()
    {
        return $this->viewData;
    }

    /**
     * @return Model
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }


    /**
     * Create a SendMail instance from a given request
     *
     * @param Request $request
     * @return SendMail
     */
    public static function create(Request $request)
    {
        // ensure the project is available via the request
        $project = self::fetchProjectFromRequest($request);

        // add missing information to the request
        // create a collection to work with
        $data = self::injectExtraInfoInRequest($project, $request->request->all());

        // validate the data
        self::validate($data);

        // init the options
        $options = [];

        $from = new From(
            $data['from']['email'], $data['from']['name']
        );

        $to = new To(
            $data['to']['email'], $data['to']['name']
        );

        $tags[] = $project->name;
        $subject = $data['subject'];

        $view = $data['messageData']['view'];
        $viewData = $data['messageData']['variables'];

        // if the namespace for the
        if (isset($data['messageData']['view_namespace'])) {
            $options['view_namespace'] = $data['messageData']['view_namespace'];
        }

        return new static($to, $from, $subject, $view, $viewData, $project, $tags, $options);
    }

    /**
     * Inject extra information given on the project if it does not exist in the request data
     *
     * @param Project $project
     * @param array $data
     * @return Collection
     */
    public static function injectExtraInfoInRequest(Project $project, $data)
    {
        // replace missing recipient information with the defaults
        if (!isset($data['to']['name'])) {
            $data['to']['name'] = $project->recipient_name;
        }

        if (!isset($data['to']['email'])) {
            $data['to']['email'] = $project->recipient_email;
        }

        // replace missing sender information with the defaults
        if (!isset($data['from']['name'])) {
            $data['from']['name'] = $project->sender_name;
        }

        if (!isset($data['from']['email'])) {
            $data['from']['email'] = $project->sender_email;
        }

        // return a new collection
        return collect($data);
    }

    /**
     * Send the actual
     *
     * @return JsonResponse
     */
    public function send()
    {
        try {
            $res = $this->mailgun->send($this->view, $this->viewData,
                function ($message) {
                    $message->from($this->from->getEmail(), $this->from->getName());
                    $message->to($this->to->getEmail(), $this->to->getName())
                        ->subject($this->subject);
                    foreach ($this->tags as $tag) {
                        $message->tag($tag);
                    }
                });

            // cleanup the result for later use
            self::cleanupResults($res);

            // trigger the required events
            event(new EmailAddedToQueue($this, $res));
        } catch (MissingRequiredParameters $e) {
            return false;
        }

        return true;
    }

    /**
     * Validate the given collection to ensure the required keys are provided
     *
     * @param Collection $data
     * @return \Illuminate\Support\MessageBag
     * @throws ValidationException
     */
    public static function validate(Collection $data)
    {
        $validator = \Validator::make($data->toArray(), [
            'to.name' => "required",
            'to.email' => "required|email",
            'from.email' => "email",
            'project' => "required",
            'subject' => "required",
            'messageData.view' => "required",
            'messageData.variables' => 'required|array'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Retrieve a project from a given request
     *
     * @param Request $request
     * @return Project
     * @throws \Exception
     */
    private static function fetchProjectFromRequest(Request $request)
    {
        // retrieve the project from the request bag if it's available
        if ($project_name = $request->get('project', false)) {
            // load the project by its name
            $project = Project::where('name', $project_name)->first();

            if ($project instanceof Model) {
                return $project;
            }

            throw new ProjectNotSupported("The project: {$project_name} could not be found in our records.");
        }

        throw new ProjectNotSupported("Provide a project.");
    }

    /**
     * @param \stdClass $response
     */
    private static function cleanupResults(&$response)
    {
        // only keep what's necessary in the id key
        $response->http_response_body->id = cleanup_mailgun_message_id($response->http_response_body->id);
    }
}
