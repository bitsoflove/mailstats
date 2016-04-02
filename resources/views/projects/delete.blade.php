@extends('mail-stats::layouts.app')

@section('content')

    <form action="{{ route('projects.destroy', [$project->id]) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('delete') }}

        <button type="submit" class="btn btn-danger">Delete</button>
        <a href="{{ route('projects.index') }}" class="btn btn-default">Cancel</a>

    </form>

@endsection