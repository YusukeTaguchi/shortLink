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
                            <div class="fs-4 fw-semibold">26 <span class="fs-6 fw-normal">(-12.4%)</span></div>
                            <div>Today's clicks</div>
                        </div>
                        </div>
                        <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas class="chart" id="card-chart1" height="70" style="display: block; box-sizing: border-box; height: 70px; width: 207px;" width="207"></canvas>
                        <div class="chartjs-tooltip" style="opacity: 0; left: 195.667px; top: 133.713px;"><table style="margin: 0px;"><thead class="chartjs-tooltip-header"><tr class="chartjs-tooltip-header-item" style="border-width: 0px;"><th style="border-width: 0px;">May</th></tr></thead><tbody class="chartjs-tooltip-body"><tr class="chartjs-tooltip-body-item"><td style="border-width: 0px;"><span style="background: rgb(50, 31, 219); border-color: rgba(255, 255, 255, 0.55); border-width: 2px; margin-right: 10px; height: 10px; width: 10px; display: inline-block;"></span>My First dataset: 51</td></tr></tbody></table></div></div>
                    </div>
                    </div>
                    <!-- /.col-->
                    <div class="col-sm-6 col-lg-3">
                    <div class="card mb-4 text-white bg-info">
                        <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">6.200 <span class="fs-6 fw-normal">(40.9%)</span></div>
                            <div>This month's clicks</div>
                        </div>
                        </div>
                        <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas class="chart" id="card-chart2" height="70" style="display: block; box-sizing: border-box; height: 70px; width: 207px;" width="207"></canvas>
                        </div>
                    </div>
                    </div>
                    <!-- /.col-->
                    <div class="col-sm-6 col-lg-3">
                    <div class="card mb-4 text-white bg-warning">
                        <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">2.49 <span class="fs-6 fw-normal">(84.7%)</span></div>
                                <div>Today's links</div>
                            </div>
                            
                        </div>
                        <div class="c-chart-wrapper mt-3" style="height:70px;">
                        <canvas class="chart" id="card-chart3" height="70" style="display: block; box-sizing: border-box; height: 70px; width: 239px;" width="239"></canvas>
                        </div>
                    </div>
                    </div>
                    <!-- /.col-->
                    <div class="col-sm-6 col-lg-3">
                    <div class="card mb-4 text-white bg-danger">
                        <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">44.000 <span class="fs-6 fw-normal">(-23.6%)</span></div>
                            <div>This month's links</div>
                        </div>
                        </div>
                        <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas class="chart" id="card-chart4" height="70" style="display: block; box-sizing: border-box; height: 70px; width: 207px;" width="207"></canvas>
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
                <!-- /.card.mb-4-->
            
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>

    <script>
        function getDaysInMonth(month, year) {
            return new Date(year, month, 0).getDate();
        }

        function formatDate(date) {
            let day = date.getDate();
            let month = date.getMonth() + 1;
            return `${day < 10 ? '0' + day : day}/${month < 10 ? '0' + month : month}`;
        }

        let currentDate = new Date();
        let daysInMonth = getDaysInMonth(currentDate.getMonth() + 1, currentDate.getFullYear());
        let dates = Array.from({ length: daysInMonth }, (_, i) => formatDate(new Date(currentDate.getFullYear(), currentDate.getMonth(), i + 1)));

        let pageClicks = Array.from({ length: daysInMonth }, () => Math.floor(Math.random() * 100));
        var ctx = document.getElementById('main-chart').getContext('2d');
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Clicks',
                    data: pageClicks,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
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
                    legend: false
                }
            }
        });
    </script>
    <script>
        function formatDate(date) {
            let day = date.getDate();
            let month = date.getMonth() + 1;
            return `${day < 10 ? '0' + day : day}/${month < 10 ? '0' + month : month}`;
        }

        let startDate = new Date();
        let dates01 = Array.from({ length: 7 }, (_, i) => {
            let date = new Date(startDate);
            date.setDate(date.getDate() - i);
            return formatDate(date);
        }).reverse();

        let pageClicks01 = Array.from({ length: 7 }, () => Math.floor(Math.random() * 100));

        var ctx1 = document.getElementById('card-chart1').getContext('2d');
        var cardChart1 = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: dates01,
                datasets: [{
                    label: 'Clicks',
                    data: pageClicks01,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                maintainAspectRatio:false,scales:{x:{grid:{display:false,drawBorder:false},ticks:{display:false}},y:{min:-9,max:39,display:false,grid:{display:false},ticks:{display:false}},
                plugins: {
                    legend: false
                }
            }
        });
    </script>
@endpush

