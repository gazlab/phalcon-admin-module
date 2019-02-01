<div class="form-group row">
    <label for="{{ element[0] }}" class="col-sm-2 control-label">{{ element['label']|capitalize }}</label>
    <div class="col-sm-10">
        {% set field = element['tag'] %}
        <?= $this->tag->$field($element) ?>
    </div>
</div>