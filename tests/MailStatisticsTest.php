<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MailStatisticsTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @var \BitsOfLove\MailStats\Entities\Project
     *   the projects repository
     */
    protected $projects;

    /**
     * @var \BitsOfLove\MailStats\Entities\Project
     *   a project entity for testing purposes
     */
    protected $project;

    /**
     * @var \BitsOfLove\MailStats\Entities\MailStatistic
     *   the mail statistics repository
     */
    protected $mailStatistics;

    /**
     * @var string
     */
    protected $projectClass = \BitsOfLove\MailStats\Entities\Project::class;

    /**
     * @var string
     */
    protected $mailStatisticsClass = \BitsOfLove\MailStats\Entities\MailStatistic::class;

    public function setUp()
    {
        parent::setUp();

        $this->projects = $this->app->make($this->projectClass);
        $this->mailStatistics = $this->app->make($this->mailStatisticsClass);

        $this->project = $this->projects->create([
            'name' => 'testing',
            'human_name' => "Only for testing",
        ]);
    }

    public function createMailStatistic()
    {
        return $this->mailStatistics->create([
            'recipient' => "to@email.com",
            'tag' => 'tag',
            'status' => 'queued', // default state
            'project_id' => $this->project->id,
            'service_message_id' => "insertmessageidhere",
        ]);
    }

    /** @test */
    public function mailstats_create_should_create_correct_mail_statistics_instance()
    {
        $statistic = $this->createMailStatistic();

        $this->assertInstanceOf($this->mailStatisticsClass, $statistic);
    }

    /** @test */
    public function mailstats_project_should_return_fully_loaded_project_instance()
    {
        $statistic = $this->createMailStatistic();

        $this->assertInstanceOf($this->projectClass, $statistic->project);
    }


}