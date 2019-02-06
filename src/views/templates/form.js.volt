$('.select2').select2({
dropdownAutoWidth: true,
width: 'auto'
});

ClassicEditor
.create(document.querySelector('.editor'), {
toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
})
.then(function (editor) {

})
.catch(function (error) {
console.error(error)
});

$('.date-range-picker').daterangepicker({
locale: {
format: 'YYYY/MM/DD'
}
});