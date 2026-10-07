document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') lucide.createIcons();

    const dataElement = document.getElementById('tenantReportData');
    if (!dataElement || typeof Chart === 'undefined') return;

    const data = JSON.parse(dataElement.textContent);

    function rupiah(value) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
    }

    function createChart(id, source, type = 'line') {
        const canvas = document.getElementById(id);
        if (!canvas) return;

        const semuaData = [...source.offline, ...source.online].map(Number);
        const nilaiMaksimum = Math.max(...semuaData, 0);
        const dataKosong = nilaiMaksimum === 0;

        new Chart(canvas, {
            type: type,
            data: {
                labels: source.labels,
                datasets: [
                    {
                        label: 'Offline',
                        data: source.offline,
                        borderColor: '#193650',
                        backgroundColor: '#193650',
                        pointBackgroundColor: '#193650',
                        pointBorderColor: '#193650',
                        borderWidth: 2,
                        tension: 0.3
                    },
                    {
                        label: 'Online',
                        data: source.online,
                        borderColor: '#ff7a1a',
                        backgroundColor: '#ff7a1a',
                        pointBackgroundColor: '#ff7a1a',
                        pointBorderColor: '#ff7a1a',
                        borderWidth: 2,
                        tension: 0.3
                    }
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
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 10,
                            boxHeight: 10,
                            padding: 12,
                            color: '#536577',
                            font: {
                                size: 12
                            },
                            generateLabels: function (chart) {
                                const labels = Chart.defaults.plugins.legend.labels.generateLabels(chart);

                                labels.forEach(function (label, index) {
                                    const color = index === 0 ? '#193650' : '#ff7a1a';

                                    label.fillStyle = color;
                                    label.strokeStyle = color;
                                    label.lineWidth = 0;
                                    label.pointStyle = 'circle';
                                });

                                return labels;
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' + rupiah(context.raw);
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
                            color: '#667788',
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        suggestedMax: dataKosong ? 10000 : undefined,
                        ticks: {
                            stepSize: dataKosong ? 2000 : undefined,
                            precision: 0,
                            color: '#667788',
                            font: {
                                size: 11
                            },
                            callback: function (value) {
                                return rupiah(value);
                            }
                        },
                        grid: {
                            color: 'rgba(25, 54, 80, 0.08)'
                        }
                    }
                }
            }
        });
    }

    createChart('dailyChart', data.harian, 'bar');
    createChart('weeklyChart', data.mingguan, 'line');
    createChart('monthlyChart', data.bulanan, 'line');
});