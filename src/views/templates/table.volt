{{ content() }}
{{ flashSession.output() }}
{% if card is defined and card is true %}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ title }}</h3>
        <div class="card-tools">
            <a href="{{ url([router.getModuleName(),router.getControllerName(),'create']|join('/')) }}" class="btn btn-tool btn-sm"
                title="Add">
                <i class="fa fa-plus mr-1"></i>Add
            </a>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive">
        {% endif %}

        <table id="datatable" class="table table-hover table-striped">
            <thead>
                <tr>
                    {% for column in columns %}
                    {{ partial(config.application.viewsDir ~ 'templates/column', column) }}
                    {% endfor %}
                </tr>
            </thead>
        </table>

        {% if card is defined and card is true %}
    </div>
    <!-- /.card-body -->
</div>
{% endif %}

{% do assets.addInlineJs(view.getPartial(config.application.viewsDir ~ 'templates/table.js')) %}