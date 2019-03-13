<div class="form-group">
    <label for="{{ element.getName() }}" class="col-sm-2 control-label">{{ element.getLabel() }}</label>

    <div class="col-sm-10">
        {{ element.getUserOption('showFiles') ? element.getUserOption('showFiles') : null }}
        {{ element }}
    </div>
</div>