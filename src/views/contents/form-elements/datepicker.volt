<div class="input-group date">
    <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
    </div>
    {{ field }}
</div>

{% do assets.addInlineJs(view.getPartial(config.application.viewsDir~'contents/form-elements/_datepicker.js')) %}