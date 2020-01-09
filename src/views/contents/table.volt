{% do assets.addCss('/../assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') %}
{% do assets.addCss('https://cdn.jsdelivr.net/npm/datatables.mark.js@2.0.1/dist/datatables.mark.min.css', false) %}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Listing Data</h3>
        <div class="card-tools">
            {% if acl.isAllowed(userSession.profile.name, dispatcher.getControllerName(), 'create') %}
            <a href="{{ url('/'~[dispatcher.getControllerName(), 'create']|join('/')) }}"
                class="btn btn-tool btn-default" type="button" title="Add"><i class="fas fa-plus"></i></a>
            {% endif %}
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive">
        <table id="example1" class="table table-bordered table-striped table-hover"></table>
    </div>
    <!-- /.card-body -->
</div>
{% do assets.addJs('/../assets/adminlte/plugins/datatables/jquery.dataTables.js') %}
{% do assets.addJs('/../assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js') %}
{% do assets.addJs('https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/jquery.mark.min.js', false) %}
{% do assets.addJs('https://cdn.jsdelivr.net/npm/datatables.mark.js@2.0.1/dist/datatables.mark.min.js', false) %}
{% do assets.addInlineJs(view.getPartial('contents/table.js', ['columns': columns])) %}