document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById('gastosChart');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Alimentación', 'Transporte', 'Ocio', 'Salud', 'Vivienda', 'Otros'],
            datasets: [{
                data: [300, 150, 200, 180, 220, 100],
                backgroundColor: ['#4c6ef5', '#e74c3c', '#f1c40f', '#2ecc71', '#9b59b6', '#34495e']
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
