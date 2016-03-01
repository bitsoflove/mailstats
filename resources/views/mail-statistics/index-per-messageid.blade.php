@extends('layouts.app')

@section('contentheader_title')
    Mail statistics for {{ $messageId }}
@endsection
@section('contentheader_description')
    Display a list of mail log information for {{ $messageId }}
@endsection

@section('main-content')

    <table>
        <thead>
        <tr>
            <th>Recipient</th>
            <th>Status</th>
            <th>Project</th>
            <th>Created</th>
        </tr>
        </thead>

        <tbody>
        @foreach($mailStatistics as $mailStatistic)
            <tr>
                <td>{{ $mailStatistic->recipient }}</td>
                <td>{{ $mailStatistic->status }}</td>
                <td><a href="{{ route('mail-stats-per-project', [
                  $mailStatistic->project->name
                ]) }}">{{ $mailStatistic->project->human_name }}</a></td>
                <td>{{ $mailStatistic->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection