{% do assets.addCss('../assets/adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') %}
{% do assets.addCss('https://cdn.datatables.net/plug-ins/1.10.13/features/mark.js/datatables.mark.min.css', false) %}

<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{ title }}</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="list_data" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    {% for column in columns %}
                    <th>{{ column['header'] }}</th>
                    {% endfor %}
                </tr>
            </thead>
        </table>
    </div>
    <!-- /.box-body -->
</div>

{% do assets.addJs('../assets/adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js') %}
{% do assets.addJs('../assets/adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') %}
{% do assets.addJs('https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/jquery.mark.min.js', false) %}
{% do assets.addJs('https://cdn.datatables.net/plug-ins/1.10.19/features/mark.js/datatables.mark.min.js', false) %}
{% do assets.addInlineJs(view.getPartial(config.application.viewsDir~'contents/_table.js')) %}