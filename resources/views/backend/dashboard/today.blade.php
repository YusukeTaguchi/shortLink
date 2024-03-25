@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')
    <div class="row">
        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                <div class="card mb-4">
                    <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                        <h4 class="card-title mb-0">Traffic click count</h4>
                        </div>
                    
                    </div>
                    <div class="c-chart-wrapper" style="margin-top:40px;">
                        <canvas class="chart" id="main-chart" height="300" style="display: block; box-sizing: border-box; height: 300px; width: 1002px;" width="1002"></canvas>
                        <div class="chartjs-tooltip" style="opacity: 0;"><table style="margin: 0px;"></table></div></div>
                    </div>
                </div>
                <!-- /.card.mb-4-->

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-5">
                                <h4 class="card-title mb-0">
                                    TOP
                                </h4>
                            </div>
                            <!--col-->
                        </div>
                        <!--row-->

                        <div class="row mt-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="links-table" class="table" data-ajax_url="{{ route("admin.links.top") }}">
                                        <thead> 
                                            <tr>
                                                <th>{{ trans('labels.backend.access.links.table.id') }}</th>
                                                <th>{{ trans('labels.backend.access.links.table.title') }}</th>
                                                <th>{{ trans('labels.backend.access.links.table.thumbnail_image') }}</th>
                                                <th>{{ trans('labels.backend.access.links.table.fake') }}</th>
                                                <th>{{ trans('labels.backend.access.links.table.short_url') }}</th>
                                                <th>{{ trans('labels.backend.access.links.table.status') }}</th>
                                                <th>{{ trans('labels.backend.access.links.table.total_viewed') }}</th>
                                                <th>{{ trans('labels.backend.access.links.table.createdby') }}</th>
                                                <th>{{ trans('labels.backend.access.links.table.createdat') }}</th>
                                                <th>{{ trans('labels.general.actions') }}</th>
                                            </tr>
                                        </thead>

                                    </table>
                                </div>
                            </div>
                            <!--col-->
                        </div>
                        <!--row-->


                    </div>
                    <!--card-body-->
                </div>
            
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')


    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>

    <script>
        function formatDate(date) {
            let hour = date < 10 ? '0' + date : date;
            return hour;
        }

        let stats = <?php echo json_encode($stats); ?>;

        let linkStats = stats.link_stats ? stats.link_stats : [];
        let viewStats = stats.view_stats ? stats.view_stats : [];

        let hours = Array.from({ length: 25 }, (_, i) => i); // Tạo mảng giờ từ 0 đến 24

        let links = hours.map(hour => {
            let stat = linkStats.find(stat => stat.hour === hour);
            return stat ? stat.count : 0;
        });

        let views = hours.map(hour => {
            let stat = viewStats.find(stat => stat.hour === hour);
            return stat ? stat.count : 0;
        });

        var ctx = document.getElementById('main-chart').getContext('2d');
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: hours.map(hour => formatDate(hour)),
                datasets: [{
                    label: 'Links',
                    data: links,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                {
                    label: 'Views',
                    data: views,
                    fill: false,
                    borderColor: 'rgb(192, 75, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            boxWidth: 80,
                            fontColor: 'black'
                        }
                    }
                }
            }
        });
    </script>

    <script>
        FTX.Utils.documentReady(function() {
            FTX.Links.list.init();
        });
    </script>
@endpush

