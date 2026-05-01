document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".progress-bar-fill").forEach(bar => {
        const gasto = parseFloat(bar.dataset.amount);
        const presupuesto = parseFloat(bar.dataset.budget);

        const porcentaje = Math.min((gasto / presupuesto) * 100, 100);
        bar.style.width = porcentaje + "%";

        if (porcentaje < 60) {
            bar.style.background = "#4caf50";
        } else if (porcentaje < 90) {
            bar.style.background = "#ffc107";
        } else {
            bar.style.background = "#dc3545";
        }

    });
});