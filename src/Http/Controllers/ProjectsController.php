<?php

namespace BitsOfLove\MailStats\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use BitsOfLove\MailStats\Entities\Project;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProjectsController extends Controller
{
    protected $projects;

    /**
     * ProjectsController constructor.
     * @param $projects
     */
    public function __construct(Project $projects)
    {
        $this->projects = $projects;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = $this->projects->paginate(15);

        return view('mail-stats::projects.index', compact(
            'projects'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mail-stats::projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        $project = $this->projects->create($request->request->all());

        return redirect()->route('projects.index')->with('status', 'New project created!');
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = $this->projects->find($id);

        return view('mail-stats::projects.edit', compact(
            'project'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProjectRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, $id)
    {
        $project = $this->projects->find($id);

        $project->update($request->request->all());

        return redirect()->route('projects.index')->with('status', 'Project updated!');
    }

    /**
     * Show a delete confirmation form.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $project = $this->projects->find($id);
        return view('mail-stats::projects.delete', compact(
            'project'
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = $this->projects->find($id);
        $project->delete();

        return redirect()->route('projects.index')->with('status', 'Project deleted!');
    }
}
