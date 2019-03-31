CKEDITOR.replaceClass = 'ckeditor';

$('form').on('submit', function () {
    $("input[type=submit]").button('loading');
});