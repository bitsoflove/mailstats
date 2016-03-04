@extends('layouts.app')

@section('contentheader_title')
    Projects
@endsection
@section('contentheader_description')
    Display a list of projects
@endsection

@section('main-content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($projects as $project)
            <tr>
                <td>{{ $project->human_name }} ({{ $project->name }})</td>
                <td>
                    <ul>
                        <li class="list-group-item"><a href="{{ route('projects.edit', [
                            $project->id
                        ]) }}">edit</a></li>
                        <li class="list-group-item list-group-item-danger"><a href="{{ route('projects.delete', [
                            $project->id
                        ]) }}">delete</a></li>
                    </ul>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {!! $projects->links() !!}

@endsection