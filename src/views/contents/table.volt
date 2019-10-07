<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{ title }}</h3>
        <div class="box-tools pull-right">
            {% if acl.isAllowed(userSession.profile.name, router.getControllerName(), 'create') and router.getActionName() is 'index' %}
            <a href="{{ url([router.getControllerName(), 'create']|join('/')) }}" class="btn btn-box-tool"
                title="Add"><i class="fa fa-plus"></i></a>
            {% endif %}
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive">
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

{% do assets.addInlineCss(view.getPartial(config.application.viewsDir~'contents/_table.css')) %}

{% do assets.addInlineJs(view.getPartial(config.application.viewsDir~'contents/_table.js')) %}