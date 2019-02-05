<div class="form-group row">
    <label for="{{ element[0] }}" class="col-sm-2 control-label">{{ element['label']|capitalize }}</label>
    <div class="col-sm-10">
        {% set field = element['tag'] %}
        {% if field is 'fileField' and hasValue(element[0]) %}
        {% set files = getValue(element[0])|json_decode(true) %}
        <div class="row pb-2">
            {% for file in files %}
            <div class="col-md-4">
                {{ image(file, 'class': 'img-fluid img-thumbnail') }}
            </div>
            {% endfor %}
        </div>
        {% endif %}
        <?= $this->tag->$field($element) ?>
    </div>
</div>