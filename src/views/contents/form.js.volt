CKEDITOR.replaceClass = 'ckeditor';

$('form').on('submit', function () {
    $("input[type=submit]").button('loading');
});

$('.date-picker').datepicker({
    autoclose: true,
    format: "yyyy-mm-dd"
});