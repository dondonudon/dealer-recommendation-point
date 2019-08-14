@extends('dashboard.layout')

@section('page title','Overview')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-dark">
                            <h5 class="card-title">Filter Chart</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="dateRange">Tanggal Input</label>
                                <input type="text" class="form-control form-control-sm" id="dateRange">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Sales Prospect</h5>
                        </div>
                        <div class="card-body">
                            <div id="sp_chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Booking GR</h5>
                        </div>
                        <div class="card-body">
                            <div id="gr_chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>BP Estimation</h5>
                        </div>
                        <div class="card-body">
                            <div id="bp_chart"></div>
                        </div>
                    </div>
                </div>
            </div>
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

        let iStartDate = moment().startOf('month').format('YYYY-MM-DD');
        let iEndDate = moment().format('YYYY-MM-DD');
        const iRange = $('#dateRange');
        iRange.daterangepicker({
            startDate: moment().startOf('month').format('DD-MM-YYYY'),
            endDate: moment().format('DD-MM-YYYY'),
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
        iRange.on('apply.daterangepicker', function(ev, picker) {
            iStartDate = picker.startDate.format('YYYY-MM-DD');
            iEndDate = picker.endDate.format('YYYY-MM-DD');
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
                data: [30,40,45,50,49]
            }],
            xaxis: {
                categories: ['Total Data Estimasi', 'Belum Follow UP', 'FU: HIGH', 'FU: Medium', 'FU: Low'],
            }
        };

        const spChart = new ApexCharts(document.querySelector("#sp_chart"), options);
        spChart.render();

        const grChart = new ApexCharts(document.querySelector("#gr_chart"), options);
        grChart.render();

        const bpChart = new ApexCharts(document.querySelector("#bp_chart"), options);
        bpChart.render();

        function reloadChart(startDate,endDate) {
            $.ajax({
                url: '{{ url('overview/list') }}',
                method: 'post',
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                success: function (response) {
                    // console.log(response);
                    let data = JSON.parse(response);
                    spChart.updateSeries([{
                        data: data.sales_prospect
                    }]);

                    grChart.updateSeries([{
                        data: data.booking_gr
                    }]);

                    bpChart.updateSeries([{
                        data: data.bp_estimation
                    }]);
                }
            })
        }

        $(document).ready(function () {
            reloadChart(iStartDate, iEndDate);
        })
    </script>
@endsection
