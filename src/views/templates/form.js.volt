$('.select2').select2({
    dropdownAutoWidth: true
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