document.addEventListener("DOMContentLoaded", () => {
    const canvas = document.getElementById('gastosChart');
    if (!canvas || chartLabels.length === 0) return;
    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: chartLabels,
            datasets: [{
                data: chartTotals,
                backgroundColor: [
                    '#4c6ef5',
                    '#e74c3c',
                    '#f1c40f',
                    '#2ecc71',
                    '#9b59b6',
                    '#34495e'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
