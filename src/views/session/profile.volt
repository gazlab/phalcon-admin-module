{{ content() }}
{{ flashSession.output() }}
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool"><i class="fa fa-history"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {{ form([router.getControllerName(), router.getActionName()]|join('/'), 'class': 'form-horizontal') }}
    <div class="box-body">
        <div class="form-group">
            <label for="avatar" class="col-sm-2 control-label">Avatar</label>

            <div class="col-sm-10">
                {{ userSession.avatar is not null ? image('../'~userSession.avatar, 'class': 'img-responsive img-thumbnail', 'alt': userSession.username) : image(gravatar.getAvatar(userSession.username), 'class': 'img-responsive img-thumbnail', 'alt': userSession.username) }}
                {{ file_field('avatar', 'class': 'form-control') }}
            </div>
        </div>
        <div class="form-group">
            <label for="username" class="col-sm-2 control-label">Username</label>

            <div class="col-sm-10">
                {{ text_field('username', 'value': userSession.username, 'class': 'form-control') }}
            </div>
        </div>
        <div class="form-group">
            <label for="old_password" class="col-sm-2 control-label">Old Password</label>

            <div class="col-sm-10">
                {{ password_field('old_password', null, 'class': 'form-control') }}
            </div>
        </div>
        <div class="form-group">
            <label for="new_password" class="col-sm-2 control-label">New Password</label>

            <div class="col-sm-10">
                {{ password_field('new_password', null, 'class': 'form-control') }}
            </div>
        </div>
        <div class="form-group">
            <label for="confirm_password" class="col-sm-2 control-label">Confirm Password</label>

            <div class="col-sm-10">
                {{ password_field('confirm_password', null, 'class': 'form-control') }}
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <a class="btn btn-danger btn-sm">
            <i class="fa fa-trash"></i>
            Delete Account</a>
        <button type="submit" class="btn btn-success pull-right">Save</button>
    </div>
    <!-- /.box-footer -->
    </form>
</div>