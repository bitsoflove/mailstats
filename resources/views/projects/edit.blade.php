@extends('layouts.app')

@section('contentheader_title')
    Edit project: {{ $project->human_name }}
@endsection
@section('contentheader_description')
    Update {{ $project->human_name }}
@endsection

@section('main-content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('projects.update', [$project->id]) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('patch') }}

        <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="@if(old('name')){{ old('name') }}@else{{ $project->name }}@endif">
            <span class="danger">Only update this if you know what you're doing.</span>
        </div>

        <div>
            <label for="human_name">Human readable name</label>
            <input type="text" name="human_name" id="human_name" value="@if(old('human_name')){{ old('human_name') }}@else{{ $project->human_name }}@endif">
        </div>

        <button type="submit">Save</button>
    </form>

@endsection