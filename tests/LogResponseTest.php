<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogResponseTest extends TestCase
{
    use DatabaseTransactions;

    protected $mailStatisticClass;
    protected $mailStatistics;
    protected $logResponseClass;
    protected $projectClass;
    protected $projects;
    protected $categoryClass;
    protected $categories;

    public function setUp()
    {
        parent::setUp();

        $this->mailStatisticClass = \BitsOfLove\MailStats\Entities\MailStatistic::class;
        $this->mailStatistics = $this->app->make($this->mailStatisticClass);

        $this->projectClass = \BitsOfLove\MailStats\Entities\Project::class;
        $this->projects = $this->app->make($this->projectClass);

        $this->categoryClass = \BitsOfLove\MailStats\Entities\Category::class;
        $this->categories = $this->app->make($this->categoryClass);

        $this->logResponseClass = \BitsOfLove\MailStats\LogResponse::class;
    }

    protected function createMailStatistic()
    {
        $project = $this->createProject();
        $category = $this->createCategory();

        $data = [
            'recipient' => "to@email.com",
            'tag' => 'tag',
            'status' => 'queued', // default state
            'project_id' => $project->id,
            'category_id' => $category->id,
            'service_message_id' => "testmessageid",
        ];

        return [
            $this->mailStatistics->create($data),
            $project,
            $category
        ];
    }

    protected function createProject()
    {
        $data = [
            "name" => "testing_bitsoflove",
            "human_name" => "Only a test Project",
        ];

        return $this->projects->create($data);
    }

    protected function createCategory()
    {
        $data = [
            'name' => 'mail'
        ];

        return $this->categories->create($data);
    }

    /** @test */
    public function logresponse_returns_correct_logresponse_instance()
    {
        $project = $this->createProject();
        $this->createCategory();

        $data = [
            'recipient' => 'some@email.to',
            'tag' => $project->name,
            'status' => "delivered",
            'message-id' => 'insertserviceidhere'
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
        $this->createCategory();

        $data = [
            'recipient' => 'some@email.to',
            'tag' => "testing",
            'status' => "delivered",
            'service_id' => 'insertserviceidhere'
        ];

        $request = new \Illuminate\Http\Request([], $data);
        \BitsOfLove\MailStats\LogResponse::create($request);
    }

    /** @test */
    public function logresponse_finds_correct_category_for_given_service_message_id()
    {
        list($mailStatistic, $project, $category) = $this->createMailStatistic();

        $data = [
            'recipient' => 'some@email.to',
            'tag' => $project->name,
            'status' => "delivered",
            'message-id' => $mailStatistic->service_message_id,
        ];

        $request = new \Illuminate\Http\Request([], $data);
        $logResponse = \BitsOfLove\MailStats\LogResponse::create($request);

        $this->assertInstanceOf($this->logResponseClass, $logResponse);
        $this->assertInstanceOf($this->categoryClass, $logResponse->getCategory());
    }

    /** @test */
    public function logresponse_finds_null_for_non_existing_message()
    {
        list($mailStatistic, $project, $category) = $this->createMailStatistic();

        $data = [
            'recipient' => 'some@email.to',
            'tag' => $project->name,
            'status' => "delivered",
            'message-id' => "some random thing",
        ];

        $request = new \Illuminate\Http\Request([], $data);
        $logResponse = \BitsOfLove\MailStats\LogResponse::create($request);

        $this->assertInstanceOf($this->logResponseClass, $logResponse);
        $this->assertNull($logResponse->getCategory());
    }
}
