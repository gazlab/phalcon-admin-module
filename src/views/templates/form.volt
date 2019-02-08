{{ content() }}
{{ flashSession.output() }}
{% if card is defined and card is true %}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ title }}</h3>
    </div>
    <!-- /.card-header -->
    {% endif %}
    <?= $this->tag->form($params) ?>
    <div class="card-body">
        {% for row in formRows %}
        {{ partial(config.application.viewsDir ~ 'templates/formRow', ['element': row]) }}
        {% endfor %}
    </div>
    <div class="card-footer">
        {{ submit_button('Save', 'class':'btn btn-success')}}
    </div>
    </form>
    {% if card is defined and card is true %}
    <!-- /.card-body -->
</div>
{% endif %}

{# Datetime Range Picker #}
{% do assets.addCss('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css', false) %}
{% do assets.addJs('https://cdn.jsdelivr.net/momentjs/latest/moment.min.js', false) %}
{% do assets.addJs('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', false) %}

{# Select2 #}
{% do assets.addCss('gazlab_assets/plugins/select2/select2.min.css') %}
{% do assets.addJs('gazlab_assets/plugins/select2/select2.full.min.js') %}

{# CKEditor #}
{% do assets.addJs('https://cdn.ckeditor.com/4.11.2/full/ckeditor.js', false) %}

{% do assets.addInlineJs(view.getPartial(config.application.viewsDir ~ 'templates/form.js')) %}