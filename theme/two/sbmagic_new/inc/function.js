$(document).ready(function() {
    var equalize = function () {
        var disableOnMaxWidth = 0; // 767 for bootstrap

        var grouped = {};
        var elements = $('*[data-same-height]');

        elements.each(function () {
            var el = $(this);
            var id = el.attr('data-same-height');

            if (!grouped[id]) {
                grouped[id] = [];
            }

            grouped[id].push(el);
        });

        $.each(grouped, function (key) {
            var elements = $('*[data-same-height="' + key + '"]');

            elements.css('height', '');

            var winWidth = $(window).width();

            if (winWidth <= disableOnMaxWidth) {
                return;
            }

            var maxHeight = 0;

            elements.each(function () {
                var eleq = $(this);
                maxHeight = Math.max(eleq.height(), maxHeight);
            });

            elements.css('height', maxHeight + "px");
        });
    };

    var timeout = null;

    $(window).resize(function () {
        if (timeout) {
            clearTimeout(timeout);
            timeout = null;
        }

        timeout = setTimeout(equalize, 250);
    });
    equalize();
});