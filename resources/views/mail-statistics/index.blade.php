@extends('mail-stats::layouts.app')

@section('content')

    @if($projects->count() > 1)
        <h3>Filter by project</h3>
        <ul class="list-group">
            @foreach($projects as $slug => $project)
                <li class="list-group-item"><a href="{{ route('mail-stats-per-project', [$slug]) }}">{{ $project }}</a>
                </li>
            @endforeach
        </ul>
    @endif

    <table class="table">
        <thead>
        <tr>
            <th>Recipient</th>
            <th>Status</th>
            <th>Project</th>
            <th>Message id</th>
            <th>Created</th>
        </tr>
        </thead>

        <tbody>
        @foreach($mailStatistics as $mailStatistic)
            <tr>
                <td>{{ $mailStatistic->recipient }}</td>
                <td>{{ $mailStatistic->status }}</td>
                <td><a href="{{ route('mail-stats-per-project',[
                    $mailStatistic->project->name
                ]) }}">{{ $mailStatistic->project->human_name }}</a></td>
                <td><a href="{{ route('mail-stats-per-message-id', [
                     $mailStatistic->project->name,
                     $mailStatistic->service_message_id
                ]) }}">{{ $mailStatistic->service_message_id }}</a></td>
                <td>{{ $mailStatistic->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {!! $mailStatistics->render() !!}

    @include("mail-stats::partials.status-overview")

@endsection