<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ title }}</h3>
        <div class="box-tools pull-right">
            {% if acl.isAllowed(userSession.profile.name, router.getControllerName(), 'create') and router.getActionName() is 'update' %}
            <a href="{{ url([router.getControllerName(), 'create']|join('/')) }}" class="btn btn-box-tool"
                title="Add"><i class="fa fa-plus"></i></a>
            {% endif %}
            {% if acl.isAllowed(userSession.profile.name, router.getControllerName(), 'history') and router.getActionName() is 'update' %}
            <a href="{{ url([router.getControllerName(), 'history', dispatcher.getParams()[0]]|join('/')) }}"
                class="btn btn-box-tool" title="History"><i class="fa fa-history"></i></a>
            {% endif %}
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {{ form(router.getRewriteUri(), 'class':'form-horizontal') }}
    <div class="box-body">
        {% for field in fields %}
        <div class="form-group">
            <label for="{{ field.getName() }}" class="col-sm-2 control-label">{{ field.getLabel() }}</label>
            
            <div class="col-sm-10">
                {{ field }}
            </div>
        </div>
        {% endfor %}
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        {% if acl.isAllowed(userSession.profile.name, router.getControllerName(), 'delete') and router.getActionName() is 'update' %}
        <button class="btn btn-danger btn-sm">Delete</button>
        {% endif %}

        <button type="submit" class="btn btn-success pull-right">Save</button>
    </div>
    <!-- /.box-footer -->
    </form>
</div>