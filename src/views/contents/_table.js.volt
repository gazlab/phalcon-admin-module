var table_list_data = $('#list_data').DataTable({
    responsive: true,
    processing:true,
    serverSide: true,
    saveState: true,
    ajax: {
        url: '{{ router.getRewriteUri() }}',
        method: 'POST'
    },
    {% set dtColumns = [] %}
    {% for column in columns %}
        {% set attr = [] %}
        {% for option, value in column['dataTable']  %}
            {% switch option %}
                {% case 'render' %}
                    {% set value = 'function(data, type, row, meta){'~value~'}' %}
                    <?php array_push($attr, $option.':'.$value) ?>
                    {% break %}
                {% default %}
                    <?php
                        if (is_string($value)) {
                            array_push($attr, $option.':"'.$value.'"');
                        }elseif (is_bool($value)){
                            array_push($attr, $option.':'.(($value) ? 'true' : 'false'));
                        }else{
                            array_push($attr, $option.':'.$value);
                        }
                    ?>
            {% endswitch %}
        {% endfor %}
        <?php array_push($dtColumns, '{'.join(',',$attr).'}') ?>
    {% endfor %}
    columns: [{{ dtColumns|join(',') }}],
    mark: true,
    drawCallback: function () { 
        $('[data-toggle="popover"]').popover({
            html: true,
            placement: "left",
            delay: {
                hide: "100"
            }
        });
    }
});