{% for content in contents %}
{{ partial(config.application.viewsDir ~ 'templates/' ~ content[0], content) }}
{% endfor %}