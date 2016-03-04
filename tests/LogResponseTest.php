<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogResponseTest extends TestCase
{

    protected $mailStatisticClass;
    protected $mailStatistics;
    protected $logResponseClass;
    protected $projectClass;
    protected $projects;

    public function setUp()
    {
        parent::setUp();

        $this->mailStatisticClass = \BitsOfLove\MailStats\Entities\MailStatistic::class;
        $this->mailStatistics = $this->app->make($this->mailStatisticClass);

        $this->projectClass = \BitsOfLove\MailStats\Entities\Project::class;
        $this->projects = $this->app->make($this->projectClass);

        $this->logResponseClass = \BitsOfLove\MailStats\LogResponse::class;
    }

    protected function createProject()
    {
        $data = [
            "name" => "testing_bitsoflove",
            "human_name" => "Only a test Project",
        ];

        return $this->projects->create($data);
    }

    /** @test */
    public function logresponse_returns_correct_logresponse_instance()
    {
        $project = $this->createProject();

        $data = [
            'recipient' => 'some@email.to',
            'tag' => $project->name,
            'status' => "delivered",
            'service_id' => 'insertserviceidhere'
        ];

        $request = new \Illuminate\Http\Request([], $data);
        $logResponse = \BitsOfLove\MailStats\LogResponse::create($request);

        $this->assertInstanceOf($this->logResponseClass, $logResponse);
    }

    /**
     * @test
     * @expectedException BitsOfLove\MailStats\Exceptions\ProjectNotSupported
     */
    public function logresponse_with_non_existing_project_in_tag_throws_exception()
    {
        $data = [
            'recipient' => 'some@email.to',
            'tag' => "testing",
            'status' => "delivered",
            'service_id' => 'insertserviceidhere'
        ];

        $request = new \Illuminate\Http\Request([], $data);
        \BitsOfLove\MailStats\LogResponse::create($request);
    }
}
