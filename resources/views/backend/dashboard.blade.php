@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')
    <div class="row">
        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
            <div class="row">
                    <div class="col-sm-6 col-lg-3">
                    <div class="card mb-4 text-white bg-primary">
                        <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div style="font-size: 25px" class="fs-4 fw-semibold">{{$viewsToday}}</div>
                            <div>Today's clicks</div>
                        </div>
                        </div>
                    </div>
                    </div>
                    <!-- /.col-->
                    <div class="col-sm-6 col-lg-3">
                    <div class="card mb-4 text-white bg-info">
                        <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div style="font-size: 25px" class="fs-4 fw-semibold">{{$viewsThisMonth}}</div>
                            <div>This month's clicks</div>
                        </div>
                        </div>
                      
                    </div>
                    </div>
                    <!-- /.col-->
                    <div class="col-sm-6 col-lg-3">
                    <div class="card mb-4 text-white bg-warning">
                        <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div style="font-size: 25px" class="fs-4 fw-semibold">{{$linksToday}}</div>
                                <div>Today's links</div>
                            </div>
                            
                        </div>
                    </div>
                    </div>
                    <!-- /.col-->
                    <div class="col-sm-6 col-lg-3">
                    <div class="card mb-4 text-white bg-danger">
                        <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div style="font-size: 25px" class="fs-4 fw-semibold">{{$linksThisMonth}}</div>
                            <div>This month's links</div>
                        </div>
                        </div>
                    </div>
                    </div>
                    <!-- /.col-->
                </div>
                <!-- /.row-->

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
            
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')


    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>

    <script>
        let monthlyStats = <?php echo json_encode($monthlyStats); ?>;
        let months = Object.keys(monthlyStats); // Lấy danh sách các tháng

        let linkData = [];
        let viewData = [];

        // Lặp qua từng tháng và lấy dữ liệu cho click và view
        months.forEach(month => {
            let linkStats = monthlyStats[month].link_stats ? monthlyStats[month].link_stats : 0;
            let viewStats = monthlyStats[month].view_stats ? monthlyStats[month].view_stats : 0;


            linkData.push(linkStats);
            viewData.push(viewStats);
        });

        var ctx = document.getElementById('main-chart').getContext('2d');
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months, // Sử dụng danh sách các tháng làm nhãn trục x
                datasets: [{
                    label: 'Links',
                    data: linkData,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                {
                    label: 'Views',
                    data: viewData,
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
@endpush

