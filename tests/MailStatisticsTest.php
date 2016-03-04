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
     * @var
     */
    protected $categories;

    /**
     * @var string
     */
    protected $projectClass = \BitsOfLove\MailStats\Entities\Project::class;

    /**
     * @var string
     */
    protected $mailStatisticsClass = \BitsOfLove\MailStats\Entities\MailStatistic::class;

    /**
     * @var string
     */
    protected $categoryClass = \BitsOfLove\MailStats\Entities\Category::class;

    public function setUp()
    {
        parent::setUp();

        $this->projects = $this->app->make($this->projectClass);
        $this->mailStatistics = $this->app->make($this->mailStatisticsClass);
        $this->categories = $this->app->make($this->categoryClass);

        $this->project = $this->projects->create([
            'name' => 'testing',
            'human_name' => "Only for testing",
        ]);
    }

    public function createMailStatistic()
    {
        $category = $this->createCategory();
        return $this->mailStatistics->create([
            'recipient' => "to@email.com",
            'tag' => 'tag',
            'status' => 'queued', // default state
            'project_id' => $this->project->id,
            'category_id' => $category->id,
            'service_message_id' => "insertmessageidhere",
        ]);
    }

    public function createCategory()
    {
        return $this->categories->create([
            'name' => 'random',
        ]);
    }

    /** @test */
    public function mailstats_create_should_create_correct_mail_statistics_instance()
    {
        $statistic = $this->createMailStatistic();

        $this->assertInstanceOf($this->mailStatisticsClass, $statistic);
        $this->assertInstanceOf($this->categoryClass, $statistic->category);
    }

    /** @test */
    public function mailstats_project_should_return_fully_loaded_project_instance()
    {
        $statistic = $this->createMailStatistic();

        $this->assertInstanceOf($this->projectClass, $statistic->project);
    }

    /** @test */
    public function mailstat_create_without_a_given_category_should_not_throw_exception()
    {
        $statistic =  $this->mailStatistics->create([
            'recipient' => "to@email.com",
            'tag' => 'tag',
            'status' => 'queued', // default state
            'project_id' => $this->project->id,
            'service_message_id' => "insertmessageidhere",
        ]);

        $this->assertInstanceOf($this->mailStatisticsClass, $statistic);
        $this->assertNull($statistic->category);
    }
}
