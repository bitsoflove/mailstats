@extends('layouts.app')

@section('contentheader_title')
    Mail log
@endsection
@section('contentheader_description')
    Display a list of mail log information
@endsection

@section('main-content')

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

    {!! $mailStatistics->links() !!}
@endsection