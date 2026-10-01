document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | ICON
        |--------------------------------------------------------------------------
        */

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }


        /*
        |--------------------------------------------------------------------------
        | TAB PERIODE
        |--------------------------------------------------------------------------
        */

        const periodInput =
            document.getElementById(
                'reportPeriod'
            );

        const periodButtons =
            document.querySelectorAll(
                '.report-period-btn'
            );

        const periodFields =
            document.querySelectorAll(
                '.report-period-field'
            );


        function updatePeriod(period) {
            if (!periodInput) {
                return;
            }

            periodInput.value = period;


            periodButtons.forEach(
                function (button) {

                    button.classList.toggle(
                        'active',
                        button.dataset.period === period
                    );

                }
            );


            periodFields.forEach(
                function (field) {

                    const active =
                        field.dataset.periodField ===
                        period;

                    field.style.display =
                        active
                            ? 'flex'
                            : 'none';

                }
            );
        }


        periodButtons.forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        updatePeriod(
                            this.dataset.period
                        );

                    }
                );

            }
        );


        updatePeriod(
            periodInput
                ? periodInput.value
                : 'harian'
        );


        /*
        |--------------------------------------------------------------------------
        | DROPDOWN REKAP TENANT
        |--------------------------------------------------------------------------
        */

        const recapTenantSelect =
            document.getElementById(
                'recapTenantSelect'
            );

        const recapTenantForm =
            document.getElementById(
                'recapTenantForm'
            );


        if (
            recapTenantSelect &&
            recapTenantForm
        ) {
            recapTenantSelect.addEventListener(
                'change',
                function () {

                    recapTenantForm.submit();

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FORMAT RUPIAH
        |--------------------------------------------------------------------------
        */

        function formatRupiah(value) {
            return (
                'Rp ' +
                new Intl.NumberFormat(
                    'id-ID'
                ).format(value)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FORMAT SUMBU Y
        |--------------------------------------------------------------------------
        */

        function formatAxis(value) {

            if (value >= 1000000) {
                return (
                    'Rp ' +
                    (value / 1000000) +
                    ' jt'
                );
            }

            if (value >= 1000) {
                return (
                    'Rp ' +
                    (value / 1000) +
                    ' rb'
                );
            }

            return 'Rp ' + value;
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        function getChartData(sourceId) {

            const source =
                document.getElementById(
                    sourceId
                );

            if (!source) {
                return null;
            }

            try {

                return {

                    labels: JSON.parse(
                        source.dataset.labels ||
                        '[]'
                    ),

                    values: JSON.parse(
                        source.dataset.values ||
                        '[]'
                    )

                };

            } catch (error) {

                console.error(
                    'Data grafik tidak dapat dibaca:',
                    error
                );

                return null;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | BUAT GRAFIK
        |--------------------------------------------------------------------------
        */

        function createIncomeChart(
            canvasId,
            sourceId,
            type,
            period
        ) {

            const canvas =
                document.getElementById(
                    canvasId
                );

            if (
                !canvas ||
                typeof Chart === 'undefined'
            ) {
                return;
            }


            const chartData =
                getChartData(
                    sourceId
                );


            if (!chartData) {
                return;
            }


            const dataset = {

                label:
                    'Pendapatan',

                data:
                    chartData.values,

                borderColor:
                    '#19324d',

                backgroundColor:
                    type === 'bar'
                        ? '#19324d'
                        : '#19324d',

                borderWidth:
                    2

            };


            /*
            |--------------------------------------------------------------------------
            | BAR
            |--------------------------------------------------------------------------
            */

            if (type === 'bar') {

                dataset.borderRadius = 6;

                dataset.borderSkipped = false;

                dataset.maxBarThickness = 58;

                dataset.barPercentage = 0.55;

                dataset.categoryPercentage = 0.68;

            }


            /*
            |--------------------------------------------------------------------------
            | LINE
            |--------------------------------------------------------------------------
            */

            if (type === 'line') {

                dataset.tension = 0.3;

                dataset.fill = false;

                dataset.pointRadius = 3;

                dataset.pointHoverRadius = 5;

                dataset.pointBackgroundColor =
                    '#19324d';

            }


            /*
            |--------------------------------------------------------------------------
            | CHART
            |--------------------------------------------------------------------------
            */

            new Chart(
                canvas,
                {

                    type: type,


                    data: {

                        labels:
                            chartData.labels,

                        datasets: [
                            dataset
                        ]

                    },


                    options: {

                        responsive: true,

                        maintainAspectRatio: false,


                        interaction: {

                            intersect: false,

                            mode: 'index'

                        },


                        plugins: {

                            legend: {
                                display: false
                            },


                            tooltip: {

                                callbacks: {

                                    label:
                                        function (
                                            context
                                        ) {

                                            return formatRupiah(
                                                context.raw
                                            );

                                        }

                                }

                            }

                        },


                        scales: {

                            x: {

                                grid: {
                                    display: false
                                },


                                ticks: {

                                    maxRotation: 0,

                                    minRotation: 0,

                                    autoSkip: true,

                                    maxTicksLimit:
                                        period === 'bulanan'
                                            ? 16
                                            : 12

                                }

                            },


                            y: {

                                beginAtZero: true,


                                ticks: {

                                    callback:
                                        function (
                                            value
                                        ) {

                                            return formatAxis(
                                                value
                                            );

                                        }

                                }

                            }

                        }

                    }

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | GRAFIK HARIAN
        |--------------------------------------------------------------------------
        */

        createIncomeChart(
            'incomeChartDaily',
            'reportChartDailyData',
            'bar',
            'harian'
        );


        /*
        |--------------------------------------------------------------------------
        | GRAFIK MINGGUAN
        |--------------------------------------------------------------------------
        */

        createIncomeChart(
            'incomeChartWeekly',
            'reportChartWeeklyData',
            'line',
            'mingguan'
        );


        /*
        |--------------------------------------------------------------------------
        | GRAFIK BULANAN
        |--------------------------------------------------------------------------
        */

        createIncomeChart(
            'incomeChartMonthly',
            'reportChartMonthlyData',
            'line',
            'bulanan'
        );

    }
);