document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll("form").forEach(form => {

        form.addEventListener("submit", e => {
            const errorBox = form.querySelector(".form-error");
            let valid = true;
            let message = "";

            const inputs = form.querySelectorAll("[data-required]");

            inputs.forEach(input => {
                const value = input.value.trim();

                if (!value) {
                    valid = false;
                    message = "Todos los campos son obligatorios";
                }

                if (valid && input.dataset.minlength) {
                    if (value.length < input.dataset.minlength) {
                        valid = false;
                        message = "El texto es demasiado corto";
                    }
                }

                if (valid && input.dataset.maxlength) {
                    if (value.length > input.dataset.maxlength) {
                        valid = false;
                        message = "El texto es demasiado largo";
                    }
                }

                if (valid && input.dataset.type === "number") {
                    const numberValue = parseFloat(value);
                    if (isNaN(numberValue)) {
                        valid = false;
                        message = "El valor debe ser un número";
                    }
                }

                if (valid && input.dataset.positive !== undefined) {
                    const numberValue = parseFloat(value);

                    if (isNaN(numberValue) || numberValue <= 0) {
                        valid = false;
                        message = "El valor debe ser mayor que 0";
                    }
                }

                if (valid && input.dataset.future) {
                    const today = new Date().toISOString().split("T")[0];
                    if (value <= today) {
                        valid = false;
                        message = "La fecha debe ser posterior a hoy";
                    }
                }
            });

            if (!valid) {
                e.preventDefault();
                if (errorBox) {
                    errorBox.innerText = message;
                    errorBox.style.display = "block";
                }
            }
        });

    });

});
