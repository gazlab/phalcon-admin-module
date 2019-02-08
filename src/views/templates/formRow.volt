<div class="form-group row">
    <label for="{{ element[0] }}" class="col-sm-2 control-label">{{ element['label']|capitalize }}</label>
    <div class="col-sm-10">
        {% set field = element['tag'] %}
        {% if field is 'fileField' and hasValue(element[0]) %}
        {% set files = getValue(element[0]) %}
        {% if files is not null %}
        <div class="row pb-2">
            {% for file in files %}
            <div class="col-md-4">
                {{ image(file, 'class': 'img-fluid img-thumbnail') }}
            </div>
            {% endfor %}
        </div>
        {% endif %}
        {% endif %}
        <?= $this->tag->$field($element) ?>
        {{ element['help'] is defined ? '<small id="'~element[0]~'Help" class="form-text text-muted">'~element['help']~'</small>'
        : null }}
    </div>
</div>