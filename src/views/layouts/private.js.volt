$(".select2").select2({
    dropdownAutoWidth: true
});

$('.date-picker').datepicker({
    autoclose: true,
    format: "yyyy-mm-dd",
    clearBtn: true
});

$('.datetime-range-picker').daterangepicker({
    timePicker: true,
    timePicker24Hour: true,
    drops: 'up',
    locale: {
        format: 'YYYY-MM-DD HH:mm'
    }
});