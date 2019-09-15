if (Boolean(localStorage.getItem('sidebar-toggle-collapsed'))) {
    var body = document.getElementsByTagName('body')[0];
    body.className = body.className + ' sidebar-collapse';
}
$('.sidebar-toggle').click(function (event) {
    event.preventDefault();
    if (Boolean(localStorage.getItem('sidebar-toggle-collapsed'))) {
        localStorage.setItem('sidebar-toggle-collapsed', '');
    } else {
        localStorage.setItem('sidebar-toggle-collapsed', '1');
    }
});

$('.select2').select2({
    dropdownAutoWidth: true,
    width: 'auto'
});

$('.datepicker').datepicker({
    autoclose: true
});