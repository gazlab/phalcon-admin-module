{% if box is defined and box is true %}
<div class="box">
    <div class="box-header">
        <h3 class="box-title">List Data</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        {% endif %}
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    {% for column in columns %}
                    <th>{{ column['header'] }}</th>
                    {% endfor %}
                </tr>
            </thead>
        </table>
        {% if box is defined and box is true %}
    </div>
    <!-- /.box-body -->
</div>
{% endif %}