@extends('dashboard.layout')

@section('page title','BOOKING GR Performance Result')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg">

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="dateRange">Tanggal Booking</label>
                                        <input type="text" class="form-control form-control-sm" id="dateRange">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="chart"></div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const loading = '<i class="fas fa-spinner fa-pulse"></i>';

        let iStartDate = moment().startOf('week').format('YYYY-MM-DD');
        let iEndDate = moment().format('YYYY-MM-DD');
        const iRange = $('#dateRange');
        iRange.daterangepicker({
            startDate: moment().startOf('week').format('DD-MM-YYYY'),
            endDate: moment().format('DD-MM-YYYY'),
            locale: {
                format: 'DD-MM-YYYY'
            }
        });

        let options = {
            chart: {
                height: 250,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                }
            },
            series: [{
                data: [0,0,0,0,0]
            }],
            xaxis: {
                categories: ['Total Data Estimasi', 'Belum Follow UP', 'FU: BOOK', 'FU: RESCHEDULE', 'FU: CANCEL'],
            }
        };

        const chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        function updateChart() {
            $.ajax({
                url: '{{ url("booking-general-repair/performance-result/list") }}',
                method: 'post',
                data: {
                    start_date: iStartDate,
                    end_date: iEndDate
                },
                success: function (response) {
                    let data = JSON.parse(response);
                    chart.updateSeries([{
                        data: data
                    }]);
                }
            });
        }

        $(document).ready(function () {
            updateChart();
            iRange.on('apply.daterangepicker', function(ev, picker) {
                iStartDate = picker.startDate.format('YYYY-MM-DD');
                iEndDate = picker.endDate.format('YYYY-MM-DD');
                updateChart();
            });
        });
    </script>
@endsection
