$('#list_data').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    stateSave: true,
    ajax: {
        url: '<?= $this->url->get($this->router->getRewriteUri()) ?>',
        method: 'POST'
    },
    <?php 
    $js = [];
    foreach ($columns as $column){
        $attr = [];
        foreach ($column as $key => $value){
            switch ($key) {
                case '0':
                    unset($column[$key]);
                    break;
                case 'render':
                    array_push($attr, $key.':'.$value);
                break;
                default:
                    if (is_string($value)) {
                        array_push($attr, $key.':"'.$value.'"');
                    }elseif (is_bool($value)){
                        array_push($attr, $key.':'.(($value) ? 'true' : 'false'));
                    }else{
                        array_push($attr, $key.':'.$value);
                    }
            }
        }
        array_push($js, '{'.join(',',$attr).'}');
    }
    ?>
    columns: [
        <?= join(',', $js) ?>
    ],
    drawCallback: function () {
        $('[data-toggle="popover"]').popover({
            html: true,
            placement: "left"
        });
    }
});