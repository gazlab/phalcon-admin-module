<div class="form-group row">
    <label for="{{ element[0] }}" class="col-sm-2 control-label">{{ element['label']|capitalize }}</label>
    <div class="col-sm-10">
        {% set field = element['tag'] %}
        {% if field is 'fileField' and hasValue(element[0]) %}
        {% set files = getValue(element[0]) %}
        {% if files is not null %}
        <div class="row pb-2">
            <?php if (is_array($files)) { ?>
            {% for file in files %}
            <div class="col-md-4">
                <?php $mimeType = mime_content_type(BASE_PATH . '/public/' . $file); ?>
                <?php if (strpos($mimeType, 'image/') !== false) { ?>
                {{ image(file, 'class': 'img-fluid img-thumbnail') }}
                <?php } elseif (strpos($mimeType, 'video/') !== false) { ?>
                <video class="w-100" controls>
                    <source src="{{ url(file) }}" type="{{ mimeType }}">
                    Your browser does not support the video tag.
                </video>
                <?php } ?>
            </div>
            {% endfor %}
            <?php } else { ?>
            <div class="col">
                <?php $mimeType = mime_content_type(BASE_PATH . '/public/' . $files); ?>
                <?php if (strpos($mimeType, 'image/') !== false) { ?>
                {{ image(files, 'class': 'img-fluid img-thumbnail') }}
                <?php } elseif (strpos($mimeType, 'video/') !== false) { ?>
                <video class="w-100" controls>
                    <source src="{{ url(files) }}" type="{{ mimeType }}">
                    Your browser does not support the video tag.
                </video>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        {% endif %}
        {% endif %}
        <?= $this->tag->$field($element) ?>
        {{ element['help'] is defined ? '<small id="'~element[0]~'Help" class="form-text text-muted">'~element['help']~'</small>'
        : null }}
    </div>
</div>