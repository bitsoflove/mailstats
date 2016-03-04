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

        <fieldset title="Default recipient information">
            <legend>Default recipient information</legend>
            <div>
                <label for="recipient_name">Name</label>
                <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name') }}">
            </div>

            <div>
                <label for="recipient_email">Email</label>
                <input type="email" name="recipient_email" id="recipient_email" value="{{ old('recipient_email') }}">
            </div>
        </fieldset>

        <fieldset title="Default sender information">
            <legend>Default sender information</legend>
            <div>
                <label for="sender_name">Name</label>
                <input type="text" name="sender_name" id="sender_name" value="{{ old('sender_name') }}">
            </div>

            <div>
                <label for="sender_email">Email</label>
                <input type="email" name="sender_email" id="sender_email" value="{{ old('sender_email') }}">
            </div>
        </fieldset>

        <button type="submit">Save</button>
    </form>

@endsection