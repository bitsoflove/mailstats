@extends('layouts.app')

@section('contentheader_title')
    Mail statistics for {{ $project->human_name }}
@endsection
@section('contentheader_description')
    Display a list of mail log information for {{ $project->human_name }}
@endsection

@section('main-content')

    <a class="btn btn-default" href="{{ route('mail-stats-per-project-cart',[$project->name]) }}">Chart view</a>

    <table class="table">
        <thead>
        <tr>
            <th>Recipient</th>
            <th>Status</th>
            <th>Message id</th>
            <th>Created</th>
        </tr>
        </thead>

        <tbody>
        @foreach($mailStatistics as $mailStatistic)
            <tr>
                <td>{{ $mailStatistic->recipient }}</td>
                <td>{{ $mailStatistic->status }}</td>
                <td><a href="{{ route("mail-stats-per-message-id", [
                    $project->name, $mailStatistic->service_message_id
                ]) }}">{{ $mailStatistic->service_message_id }}</a></td>
                <td>{{ $mailStatistic->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {!! $mailStatistics->links() !!}

    @include("mail-stats::partials.status-overview")

@endsection