{% if card is defined and card is true %}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ title }}</h3>
    </div>
    <!-- /.card-header -->
    {% endif %}
    {{ form() }}
    <div class="card-body">
        {% for row in formRows %}
        {{ partial(config.application.viewsDir ~ 'templates/formRow', ['element': row]) }}
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