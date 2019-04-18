{{ flashSession.output() }}
{% if box is defined and box is true %}
<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{ title is defined ? title : 'List Data' }}</h3>
        <div class="box-tools">
            {% if acl.isAllowed(userSession.profile.name, router.getControllerName(), 'create') %}
            <a title="Add New"
                href="{{ url([router.getModuleName(), router.getControllerName(), 'create']|join('/')) }}"
                class="btn btn-box-tool"><i class="fa fa-plus"></i></a>
            {% endif %}
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive">
        {% endif %}
        <table id="list_data" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    {% for column in columns %}
                    <th>{{ column['header'] }}</th>
                    {% endfor %}
                </tr>
            </thead>
        </table>
        {% if box is defined and box is true %}
    </div>
    <!-- /.box-body -->
</div>
{% endif %}

{% do assets.addInlineJs(view.getPartial(config.application.viewsDir~'/contents/table.js')) %}