document.addEventListener("DOMContentLoaded", function() {
    
    setTimeout(function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))

        popoverTriggerList.forEach(function (el) {
            if (el._popover) el._popover.dispose();
            var popover = new bootstrap.Popover(el, {
                trigger: 'hover',
                container: 'body',
                boundary: 'viewport',
                placement: 'top'
            });
            el._popover = popover;
        });
    }, 100);
});
