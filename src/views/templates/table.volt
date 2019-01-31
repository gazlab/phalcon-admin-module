<table id="datatable" class="table table-bordered table-hover table-strip">
    <thead>
        <tr>
            {% for column in columns %}
            {{ partial(config.application.viewsDir ~ 'templates/column', column) }}
            {% endfor %}
        </tr>
    </thead>
</table>

{% do assets.addInlineJs(view.getPartial(config.application.viewsDir ~ 'templates/table.js')) %}