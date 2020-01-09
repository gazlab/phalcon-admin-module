{{ content() }}
{{ flashSession.output() }}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ title }}</h3>
        <div class="card-tools">
            {% if dispatcher.getActionName() is 'update' and acl.isAllowed(userSession.profile.name, dispatcher.getControllerName(), 'create') %}
            <a href="{{ url('/'~[dispatcher.getControllerName(), 'create']|join('/')) }}"
                class="btn btn-tool btn-default" type="button" title="Add"><i class="fas fa-plus"></i></a>
            {% endif %}
        </div>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" action="{{ router.getRewriteUri() }}" method="post">
        <input type='hidden' name='<?php echo $this->security->getTokenKey() ?>'
            value='<?php echo $this->security->getToken() ?>' />
        <div class="card-body">
            {% for element in fields %}
            <div class="form-group row">
                <label for="{{ element.getName() }}" class="col-sm-2 col-form-label">{{ element.getLabel() }}</label>
                <div class="col-sm-10">
                    {{ element }}
                </div>
            </div>
            {% endfor %}
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-success btn-lg float-right"><i class="fas fa-save fa-lg   "></i> Save
                {{ resource.menu['name'] }}</button>
            {% if dispatcher.getActionName() is 'update' %}
            <a class="btn btn-danger btn-sm" href="#"><i class="fas fa-trash fa-sm   "></i> Delete
                {{ resource.menu['name'] }}</a>
            {% endif %}
        </div>
        <!-- /.card-footer -->
    </form>
</div>

{% do assets.addCss('/../assets/adminlte/plugins/select2/css/select2.min.css') %}
{% do assets.addJs('/../assets/adminlte/plugins/select2/js/select2.full.min.js') %}
{% do assets.addInlineJs(view.getPartial('contents/form.js')) %}