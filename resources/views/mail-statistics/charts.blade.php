@extends('layouts.app')

@section('contentheader_title')
    Charts
@endsection
@section('contentheader_description')
    Charts?
@endsection

@section('main-content')
    <div id="last-interaction-per-mail-chart-wrapper" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
@endsection

@section('scripts')
    @parent
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script type="text/javascript">
        jQuery(function () {
            jQuery('#last-interaction-per-mail-chart-wrapper').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Last interaction status per email'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },
                series: [{
                    name: 'Status',
                    colorByPoint: true,
                    data: [
                            @foreach($lastStatuses as $status => $count)
                        {
                            name: '{{ $status }}',
                            y: {{ $count }}
                        },
                        @endforeach
                    ]
                }]
            });
        });
    </script>
@endsection