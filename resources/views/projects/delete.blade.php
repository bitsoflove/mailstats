@extends('layouts.app')

@section('contentheader_title')
    Confirm deletion for {{ $project->human_name }}
@endsection
@section('contentheader_description')
@endsection

@section('main-content')

    <form action="{{ route('projects.destroy', [$project->id]) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('delete') }}

        <button type="submit" class="danger">Delete</button>
        <a href="{{ route('projects.index') }}" class="button">Cancel</a>

    </form>

@endsection