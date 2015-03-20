define(['jquery', 'bootstrap'], function ($) {
    $('label.translatable').each(function (index, label) {
        $(label).append(' <i class="glyphicon glyphicon-flag"></i>')
    });

    $('label.not_translated').each(function (index, label) {
        var $label = $(label);
        if (!$label.data('default-locale-value')) {
            return;
        }
        var $badge = $(' <span class="badge pull-right">' + $label.data('default-locale') + '</span>');
        var $content;
        if ($label.data('default-locale-url')) {
            $content = '<a href="' + $label.data('default-locale-url') + '" target="_blank">' + $label.data('default-locale-value') + '</a>';
        } else {
            $content = $label.data('default-locale-value');
        }

        $badge.appendTo($(label)).popover({
            container: 'body',
            content: $content,
            html: true,
            placement: 'auto top'
        });
        $badge.on('click', function (e) {
            e.preventDefault();
        });
    });
});
