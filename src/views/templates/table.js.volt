{% set datas = [] %}
{% for cols in columns %}
{% set data = [] %}
{% for key, value in cols %}
{% switch key %}
{% case '0' %}
<?php array_push($data, 'data: "'. $value . '"') ?>
{% break %}
{% case 'render' %}
<?php array_push($data, 'render: '. $value) ?>
{% break %}
{% default %}
<?php array_push($data, $key . ': '. (is_string($value) ? '"'.$value.'"' : (is_bool($value) ? ($value ? 'true' : 'false') : $value))) ?>
{% endswitch %}
{% endfor %}
<?php array_push($datas, '{' . join($data, ','). '}') ?>
{% endfor %}

$("#datatable").DataTable({
serverSide: true,
ajax: {
url: '{{ url(router.getRewriteUri()) }}',
method: 'POST'
},
columns: [
{{ datas|join(',') }}
],
drawCallback: function() {
$('[data-toggle="popover"]').popover({
trigger: 'focus',
html: true
});
}
});