<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectEntityTest extends TestCase
{
    use DatabaseTransactions;

    protected $projects;

    protected $projectClass = \BitsOfLove\MailStats\Entities\Project::class;

    protected $mailStatistics;

    protected $mailStatisticsClass = \BitsOfLove\MailStats\Entities\MailStatistic::class;

    public function setUp()
    {
        parent::setUp();

        $this->projects = $this->app->make($this->projectClass);
        $this->mailStatistics = $this->app->make($this->mailStatisticsClass);
    }

    public function createProject()
    {
        $data = [
            "name" => "testing_bitsoflove",
            "human_name" => "Only a test Project",
        ];

        return $this->projects->create($data);
    }

    public function createMailStatistic($project)
    {
        return $this->mailStatistics->create([
            'recipient' => "to@email.com",
            'tag' => 'tag',
            'status' => 'queued', // default state
            'project_id' => $project->id,
            'service_message_id' => "insertmessageidhere",
        ]);
    }

    /** @test */
    public function projects_create_should_return_correct_project_entity()
    {
        $project = $this->createProject();

        $this->assertInstanceOf($this->projectClass, $project);
        $this->assertTrue($project->id > 0);
        $this->assertTrue($project->name == "testing_bitsoflove");
        $this->assertTrue($project->human_name == "Only a test Project");
        return $project;
    }

    /** @test */
    public function projects_all_should_return_collection()
    {
        $projects = $this->projects->all();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $projects);
    }

    /** @test */
    public function projects_find_should_return_correct_project_entity()
    {
        $new = $this->createProject();
        $result = $this->projects->find($new->id);

        $this->assertInstanceOf($this->projectClass, $new);
        $this->assertInstanceOf($this->projectClass, $result);
        $this->assertArraySubset($result->toArray(), $new->toArray());
    }

    /** @test */
    public function project_mailstatistics_should_return_collection_of_mailstatistic_objects()
    {
        $project = $this->createProject();

        for ($i = 0; $i <= 5; $i++) {
            $this->createMailStatistic($project);
        }

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $project->mailStatistics);

        foreach ($project->mailStatistics as $mailStatistic) {
            $this->assertInstanceOf($this->mailStatisticsClass, $mailStatistic);
        }
    }
}