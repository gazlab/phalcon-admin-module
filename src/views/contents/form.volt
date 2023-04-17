{% if box is defined and box is true %}
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ title }}</h3>
        <div class="box-tools">
            {% if acl.isAllowed(userSession.profile.name, router.getControllerName(), 'create') and
            router.getActionName() is 'update' %}
            <a title="Add New"
                href="{{ url([router.getModuleName(), router.getControllerName(), 'create']|join('/')) }}"
                class="btn btn-box-tool"><i class="fa fa-plus"></i></a>
            {% endif %}
        </div>
    </div>

    {% set attrs[0] = router.getRewriteUri() %}
    {% set attrs['class'] = attrs['class'] is defined ? attrs['class']~' form-horizontal': 'form-horizontal' %}
    <?= $this->tag->form($attrs) ?>
    <div class="box-body">
        {% endif %}
        {% for field in formFields %}
        <?php 
            switch (get_class($field)) {
                case 'Phalcon\Forms\Element\File':
                    $this->view->partial($this->config->application->viewsDir.'/contents/elementFile', ['element' => $field]);
                    break;
                default:
                ?>
        <div class="form-group">
            <label for="{{ field.getName() }}" class="col-sm-2 control-label">{{ field.getLabel() }}</label>

            <div class="col-sm-10">
                {{ field }}
            </div>
        </div>
        <?php
            }
        ?>
        {% endfor %}
        {% if box is defined and box is true %}
    </div>
    <div class="box-footer">
        {{ submit_button('Save', 'class':'btn btn-success pull-right', 'data-loading-text': 'Loading...') }}
    </div>
    </form>
</div>
{% endif %}

{% do assets.addJs('//cdn.ckeditor.com/4.21.0/full/ckeditor.js', false) %}

{% do assets.addInlineJs(view.getPartial(config.application.viewsDir~'/contents/form.js')) %}