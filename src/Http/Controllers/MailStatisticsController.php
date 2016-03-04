<?php

namespace BitsOfLove\MailStats\Http\Controllers;

use BitsOfLove\MailStats\Entities\MailStatistic;
use BitsOfLove\MailStats\Entities\Project;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailStatisticsController extends Controller
{
    /**
     * @var int
     *   the default number of items in a paginated overview
     */
    protected $perPage = 15;

    /**
     * @var MailStatistic
     */
    protected $mailStatistics;

    /**
     * @var Project
     */
    private $projects;

    /**
     * @param MailStatistic $mailLogs
     * @param Project $projects
     */
    public function __construct(MailStatistic $mailLogs, Project $projects)
    {
        $this->mailStatistics = $mailLogs;
        $this->projects = $projects;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mailStatistics = $this->mailStatistics->newestGrouped()->paginate($this->perPage);
        $projects = $this->projects->lists('human_name', 'name');

        return view('mail-stats::mail-statistics.index', compact(
            'mailStatistics',
            'projects'
        ));
    }

    /**
     * Show a test entry point
     *
     * @return \Illuminate\Http\Response
     */
    public function testEntryPoint()
    {
        // disable this function in production
        if (app()->environment('production')) {
            abort(404);
        }

        return view('mail-stats::mail-statistics.test-entry-point');
    }

    /**
     * Show a list of entries per project
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function indexPerProject(Project $project)
    {
        // @todo: add repo?
        $mailStatistics = $project->mailStatistics()->newestGrouped()->paginate($this->perPage);

        return view('mail-stats::mail-statistics.index-per-project', compact(
            'project',
            'mailStatistics'
        ));
    }

    /**
     * Show a list of entries associated with a given message id
     *
     * @param Project $project
     * @param string $messageId
     * @return \Illuminate\Http\Response
     */
    public function indexPerMessageId(Project $project, $messageId)
    {
        // retrieve the statistics associated with the given Project
        $mailStatistics = MailStatistic::service($messageId)->newest()->get();

        if ($mailStatistics->isEmpty()) {
            abort(404);
        }

        return view('mail-stats::mail-statistics.index-per-messageid', compact(
            'project',
            'messageId',
            'mailStatistics'
        ));
    }
}
