@extends('layouts.app')

@section('contentheader_title')
    Create a project
@endsection
@section('contentheader_description')
    Create a new project to associate with the mailgun service
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

    <form action="{{ route('projects.store') }}" method="POST">
        {{ csrf_field() }}

        <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
        </div>

        <div>
            <label for="human_name">Human readable name</label>
            <input type="text" name="human_name" id="human_name" value="{{ old('human_name') }}">
        </div>

        <button type="submit">Save</button>
    </form>

@endsection