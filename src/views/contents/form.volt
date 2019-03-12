{{ flashSession.output() }}
{% if box is defined and box is true %}
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ title }}</h3>
    </div>

    {{ form(router.getRewriteUri(), 'class':"form-horizontal") }}
    <div class="box-body">
        {% endif %}
        {% for field in formFields %}
        <div class="form-group">
            <label for="{{ field.getName() }}" class="col-sm-2 control-label">{{ field.getLabel() }}</label>

            <div class="col-sm-10">
                {{ field }}
            </div>
        </div>
        {% endfor %}
        {% if box is defined and box is true %}
    </div>
    <div class="box-footer">
        {{ submit_button('Save', 'class':'btn btn-success pull-right') }}
    </div>
    </form>
</div>
{% endif %}

{% do assets.addInlineJs(view.getPartial(config.application.viewsDir~'/contents/table.js')) %}