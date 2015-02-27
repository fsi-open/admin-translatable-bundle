define(['jquery'], function ($) {
    $('label.translatable').each(function (index, label) {
        $(label).append('<i class="glyphicon glyphicon-flag"></i>');
    });
});
