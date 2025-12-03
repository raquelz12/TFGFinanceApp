document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById('gastosChart');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Alimentación', 'Transporte', 'Ocio', 'Otros'],
            datasets: [{
                data: [300, 150, 200, 180],
                backgroundColor: ['#4c6ef5', '#e74c3c', '#f1c40f', '#2ecc71']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
