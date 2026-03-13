<script>
    function initializeDoughnutChart() {
        chartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.values,
                    backgroundColor: [
                        '#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b',
                        '#ef4444', '#ec4899', '#84cc16', '#f97316', '#6b7280'
                    ],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                ...commonOptions,
                cutout: '60%',
                plugins: {
                    ...commonOptions.plugins,
                    legend: {
                        ...commonOptions.plugins.legend,
                        position: 'bottom'
                    }
                }
            }
        });
    }
</script>
