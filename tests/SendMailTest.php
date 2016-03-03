<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SendMailTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @var \BitsOfLove\MailStats\Entities\Project
     *   the project repository
     */
    protected $projects;

    /**
     * @var \BitsOfLove\MailStats\Entities\Project
     *   one specific created project for testing purposes
     */
    protected $project;

    protected $projectClass = \BitsOfLove\MailStats\Entities\Project::class;

    public function setUp()
    {
        parent::setUp();

        // init the
        $this->projects = $this->app->make($this->projectClass);

        // create a project for testing purposes
        $this->project = $this->projects->create([
            "name" => "testing",
            "human_name" => "Only a test Project",
        ]);
    }

    /**
     * @test
     * @expectedException BitsOfLove\MailStats\Exceptions\ProjectNotSupported
     */
    function non_existing_project_should_throw_an_exception()
    {
        $request = new \Illuminate\Http\Request([], [
            'project' => 'bitsoflove'
        ]);

        \BitsOfLove\MailStats\SendMail::create($request);
    }

    /**
     * @test
     */
    function correct_mail_config_should_return_json()
    {
        $request = new \Illuminate\Http\Request([], [
            'project' => 'testing',
            "to" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "from" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "subject" => "insert subject here",
            "messageData" => [
                "view" => "emails.test",
                "variables" => [
                    "service" => "mailgun"
                ]
            ]
        ]);

        $service = \BitsOfLove\MailStats\SendMail::create($request);

        $this->assertInstanceOf(\BitsOfLove\MailStats\SendMail::class, $service);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function missing_from_parameter_should_throw_exception(){
        $request = new \Illuminate\Http\Request([], [
            'project' => 'testing',
            "to" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "subject" => "insert subject here",
            "messageData" => [
                "view" => "emails.test",
                "variables" => [
                    "service" => "mailgun"
                ]
            ]
        ]);

        \BitsOfLove\MailStats\SendMail::create($request);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function missing_from_name_parameter_should_throw_exception(){
        $request = new \Illuminate\Http\Request([], [
            'project' => 'testing',
            "to" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "from" => [
                "email" => "stijn@bitsoflove.be"
            ],
            "subject" => "insert subject here",
            "messageData" => [
                "view" => "emails.test",
                "variables" => [
                    "service" => "mailgun"
                ]
            ]
        ]);

        \BitsOfLove\MailStats\SendMail::create($request);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function missing_from_email_parameter_should_throw_exception(){
        $request = new \Illuminate\Http\Request([], [
            'project' => 'testing',
            "to" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "from" => [
                "name" => "Stijn Tilleman",
            ],
            "subject" => "insert subject here",
            "messageData" => [
                "view" => "emails.test",
                "variables" => [
                    "service" => "mailgun"
                ]
            ]
        ]);

        \BitsOfLove\MailStats\SendMail::create($request);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function missing_to_parameter_should_throw_exception(){
        $request = new \Illuminate\Http\Request([], [
            'project' => 'testing',
            "from" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "subject" => "insert subject here",
            "messageData" => [
                "view" => "emails.test",
                "variables" => [
                    "service" => "mailgun"
                ]
            ]
        ]);

        \BitsOfLove\MailStats\SendMail::create($request);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function missing_to_name_parameter_should_throw_exception(){
        $request = new \Illuminate\Http\Request([], [
            'project' => 'testing',
            "to" => [
                "email" => "stijn@bitsoflove.be"
            ],
            "from" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "subject" => "insert subject here",
            "messageData" => [
                "view" => "emails.test",
                "variables" => [
                    "service" => "mailgun"
                ]
            ]
        ]);

        \BitsOfLove\MailStats\SendMail::create($request);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function missing_to_email_parameter_should_throw_exception(){
        $request = new \Illuminate\Http\Request([], [
            'project' => 'testing',
            "to" => [
                "name" => "Stijn Tilleman",
            ],
            "from" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "subject" => "insert subject here",
            "messageData" => [
                "view" => "emails.test",
                "variables" => [
                    "service" => "mailgun"
                ]
            ]
        ]);

        \BitsOfLove\MailStats\SendMail::create($request);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function missing_subject_parameter_should_throw_exception(){
        $request = new \Illuminate\Http\Request([], [
            'project' => 'testing',
            "to" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "from" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "messageData" => [
                "view" => "emails.test",
                "variables" => [
                    "service" => "mailgun"
                ]
            ]
        ]);

        \BitsOfLove\MailStats\SendMail::create($request);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function missing_messagedata_parameter_should_throw_exception(){
        $request = new \Illuminate\Http\Request([], [
            'project' => 'testing',
            "to" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "from" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "subject" => "insert subject here",
        ]);

        \BitsOfLove\MailStats\SendMail::create($request);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function missing_messagedata_view_parameter_should_throw_exception(){
        $request = new \Illuminate\Http\Request([], [
            'project' => 'testing',
            "to" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "from" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "subject" => "insert subject here",
            "messageData" => [
                "variables" => [
                    "service" => "mailgun"
                ]
            ]
        ]);

        \BitsOfLove\MailStats\SendMail::create($request);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function missing_messagedata_variables_parameter_should_throw_exception(){
        $request = new \Illuminate\Http\Request([], [
            'project' => 'testing',
            "to" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "from" => [
                "name" => "Stijn Tilleman",
                "email" => "stijn@bitsoflove.be"
            ],
            "subject" => "insert subject here",
            "messageData" => [
                "view" => "emails.test",
            ]
        ]);

        \BitsOfLove\MailStats\SendMail::create($request);
    }

}
