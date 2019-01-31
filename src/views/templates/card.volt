<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ title }}</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive">
        {% for content in contents %}
        {{ partial(config.application.viewsDir ~ 'templates/' ~ content[0], content) }}
        {% endfor %}
    </div>
    <!-- /.card-body -->
</div>