{% if card is defined and card is true %}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ title }}</h3>
        <div class="card-tools">
            <a href="{{ url([router.getModuleName(),router.getControllerName(),'create']|join('/')) }}" class="btn btn-tool btn-sm"
                title="Add">
                <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>
    <!-- /.card-header -->
    {% endif %}
    {{ form() }}
    <div class="card-body">
        {% for row in formRows %}
        {{ partial(config.application.viewsDir ~ 'templates/formRow', row) }}
        {% endfor %}
    </div>
    <div class="card-footer">
        {{ submit_button('Save', 'class':'btn btn-primary')}}
    </div>
    </form>
    {% if card is defined and card is true %}
    <!-- /.card-body -->
</div>
{% endif %}