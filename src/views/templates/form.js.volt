$('.select2').select2({
dropdownAutoWidth: true,
width: 'auto'
});

$('.editor').each(function(e){
CKEDITOR.replace(this.id);
});
$('.date-range-picker').daterangepicker({
locale: {
format: 'YYYY/MM/DD'
}
});