<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pie Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: white;
            font-family: Arial, sans-serif;
        }
        .chart-container {
            width: 350px;
            height: 400px;
            position: relative;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="chart-container">
    <canvas id="pieChart"></canvas>
</div>

<script>
    @if($data['complaintTypes']->count() > 0)
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const complaintsData = @json($data['complaintTypes']);

    // Calculate total for percentages
    const total = complaintsData.reduce((sum, item) => sum + item.count, 0);

    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: complaintsData.map(item => item.nature),
            datasets: [{
                data: complaintsData.map(item => item.count),
                backgroundColor: [
                    '#6366f1',
                    '#8b5cf6',
                    '#06b6d4',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#f97316',
                    '#84cc16',
                    '#ec4899',
                    '#14b8a6'
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    align: 'start',
                    labels: {
                        padding: 12,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 10,
                            weight: '500',
                            family: 'Arial'
                        },
                        boxWidth: 12,
                        boxHeight: 12,
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                const dataset = data.datasets[0];
                                const total = dataset.data.reduce((a, b) => a + b, 0);

                                return data.labels.map((label, i) => {
                                    const value = dataset.data[i];
                                    const percentage = ((value / total) * 100).toFixed(1);

                                    return {
                                        text: `${label}: ${value} (${percentage}%)`,
                                        fillStyle: dataset.backgroundColor[i],
                                        strokeStyle: '#ffffff',
                                        lineWidth: 2,
                                        hidden: false,
                                        index: i,
                                        pointStyle: 'circle'
                                    };
                                });
                            }
                            return [];
                        }
                    },
                    maxHeight: 150,
                    onClick: null
                },
                tooltip: {
                    enabled: false
                }
            },
            layout: {
                padding: {
                    bottom: 20,
                    top: 5,
                    left: 5,
                    right: 5
                }
            },
            cutout: '35%',
            radius: '75%'
        }
    });
    @else
    const ctx = document.getElementById('pieChart').getContext('2d');
    ctx.fillStyle = '#6b7280';
    ctx.font = '16px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('No data available', 175, 200);
    @endif
</script>
</body>
</html>
