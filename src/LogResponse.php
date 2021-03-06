<?php

namespace BitsOfLove\MailStats;

use BitsOfLove\MailStats\Entities\Category;
use BitsOfLove\MailStats\Exceptions\ProjectNotSupported;
use BitsOfLove\MailStats\Entities\MailStatistic;
use BitsOfLove\MailStats\Entities\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LogResponse
{

    /**
     * @var string
     */
    protected $recipient;

    /**
     * @var string
     */
    protected $tag;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var Project
     */
    protected $project;

    /**
     * @var Category
     */
    protected $category;

    /**
     * @var string
     */
    protected $service_id;

    /**
     * @param string $recipient
     * @param string $tag
     * @param string $status
     * @param Project $project
     * @param string $service_id
     * @param $category
     */
    public function __construct($recipient, $tag, $status, $project, $service_id, $category)
    {
        $this->recipient = $recipient;
        $this->tag = $tag;
        $this->status = $status;
        $this->project = $project;
        $this->category = $category;
        $this->service_id = $service_id;
    }

    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Create a new LogResponse object from a given Request
     *
     * @param Request $request
     * @return LogResponse
     */
    public static function create(Request $request)
    {
        $recipient = $request->get('recipient', 'unknown');
        $tag = self::fetchTagFromRequest($request);
        $status = $request->get('event', 'bad-request');
        $project = self::fetchProjectFromRequest($request);
        $service_id = self::fetchServiceMessageIdFromRequest($request);

        $category = self::determineCategoryByMessageId($service_id);

        return new self($recipient, $tag, $status, $project, $service_id, $category);
    }

    /**
     * Create the log entry with the provided information
     *
     * @return MailStatistic
     */
    public function log()
    {
        $log = MailStatistic::create([
            'recipient' => $this->recipient,
            'tag' => $this->tag,
            'status' => $this->status,
            'project_id' => $this->project->id,
            'service_message_id' => $this->service_id,
            'category_id' => !$this->category ? null : $this->category->id
        ]);

        return $log;
    }

    /**
     * Retrieve the message id from the Request
     *
     * @param Request $request
     * @return string
     */
    private static function fetchServiceMessageIdFromRequest(Request $request)
    {
        if ($message_id = $request->get('message-id', false)) {
            return self::cleanUpMessageId($message_id);
        } elseif ($message_id = $request->get('Message-Id', false)) {
            return self::cleanUpMessageId($message_id);
        }

        return "none";
    }

    /**
     * Retrieve the tag from the Request
     *
     * @param Request $request
     * @return mixed
     */
    private static function fetchTagFromRequest(Request $request)
    {
        $tag = $request->get('tag', false);

        if (!$tag) {
            $tag = $request->get('X-Mailgun-Tag', false);
        }

        return $tag;
    }

    /**
     * Remove <> from the message id to ensure equality
     *
     * @param string $message_id
     * @return string
     */
    private static function cleanUpMessageId($message_id)
    {
        return cleanup_mailgun_message_id($message_id);
    }

    /**
     * Determine the Project from a given Request
     *
     * @param Request $request
     * @return Project
     * @throws ProjectNotSupported
     */
    private static function fetchProjectFromRequest(Request $request)
    {
        $project_name = self::fetchTagFromRequest($request);

        // retrieve the project from the request bag if it's available
        // load the project by its name
        $project = Project::where('name', $project_name)->first();

        if ($project instanceof Model) {
            return $project;
        }

        throw new ProjectNotSupported("The project: {$project_name} could not be found in our records.");
    }


    /**
     * Determine the category of the given service_message_id
     *
     * @param $service_message_id
     * @return null|Category
     */
    private static function determineCategoryByMessageId($service_message_id)
    {
        $mail_statistic = MailStatistic::where('service_message_id', $service_message_id)->first();

        return is_null($mail_statistic) ? null : $mail_statistic->category;
    }
}
