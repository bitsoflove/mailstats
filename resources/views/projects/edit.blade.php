@extends('mail-stats::layouts.app')

@section('content')

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

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name"
                   value="@if(old('name')){{ old('name') }}@else{{ $project->name }}@endif" class="form-control">
            <p class="help-block">Only update this if you know what you're doing.</p>
        </div>

        <div class="form-group">
            <label for="human_name">Human readable name</label>
            <input type="text" name="human_name" id="human_name"
                   value="@if(old('human_name')){{ old('human_name') }}@else{{ $project->human_name }}@endif" class="form-control">
        </div>

        <fieldset title="Default recipient information">
            <legend>Default recipient information</legend>

            <div class="form-group">
                <label for="recipient_name">Default recipient name</label>
                <input type="text" name="recipient_name" id="recipient_name"
                       value="@if(old('recipient_name')){{ old('recipient_name') }}@else{{ $project->recipient_name }}@endif" class="form-control">
            </div>

            <div class="form-group">
                <label for="recipient_email">Default recipient e-mail address</label>
                <input type="text" name="recipient_email" id="recipient_email"
                       value="@if(old('recipient_email')){{ old('recipient_email') }}@else{{ $project->recipient_email }}@endif" class="form-control">
            </div>
        </fieldset>

        <fieldset title="Default sender information">
            <legend>Default sender information</legend>

            <div class="form-group">
                <label for="sender_name">Default sender name</label>
                <input type="text" name="sender_name" id="sender_name"
                       value="@if(old('sender_name')){{ old('sender_name') }}@else{{ $project->sender_name }}@endif" class="form-control">
            </div>

            <div class="form-group">
                <label for="sender_email">Default sender e-mail address</label>
                <input type="text" name="sender_email" id="sender_email"
                       value="@if(old('sender_email')){{ old('sender_email') }}@else{{ $project->sender_email }}@endif" class="form-control">
            </div>
        </fieldset>

        <fieldset title="Reply to information">
            <legend>Default reply to information</legend>

            <div class="form-group">
                <label for="reply_to_name">Default reply to name</label>
                <input type="text" name="reply_to_name" id="reply_to_name"
                       value="@if(old('reply_to_name')){{ old('reply_to_name') }}@else{{ $project->reply_to_name }}@endif" class="form-control">
            </div>

            <div class="form-group">
                <label for="reply_to_email">Default reply to e-mail address</label>
                <input type="text" name="reply_to_email" id="reply_to_email"
                       value="@if(old('reply_to_email')){{ old('reply_to_email') }}@else{{ $project->reply_to_email }}@endif" class="form-control">
            </div>
        </fieldset>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>

@endsection