$(document).ajaxStart(function () {
    Pace.restart()
});

$('.data-table').DataTable({
    serverSide: true,
    ajax: {
        url: '<?= $this->router->getRewriteUri() ?>',
        method: 'POST'
    },
    columns: <?= json_encode($columns) ?>
});