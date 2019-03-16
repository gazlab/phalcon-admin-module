{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Profile</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {{ form(router.getRewriteUri(), 'class': 'form-horizontal') }}
    <div class="box-body">
        <div class="form-group">
            <label for="username" class="col-sm-2 control-label">Username</label>

            <div class="col-sm-10">
                {{ text_field('username', 'value': userSession.username, 'class': 'form-control') }}
            </div>
        </div>
        <div class="form-group">
            <label for="old_password" class="col-sm-2 control-label">Old Password</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" name="old_password">
            </div>
        </div>
        <div class="form-group">
            <label for="new_password" class="col-sm-2 control-label">New Password</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" name="new_password">
            </div>
        </div>
        <div class="form-group">
            <label for="confirm_password" class="col-sm-2 control-label">Confirmation Password</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" name="confirm_password">
            </div>
        </div>
        <!-- <div class="form-group">
                <label for="avatar" class="col-sm-2 control-label">Photo</label>

                <div class="col-sm-10">
                    <input type="file" name="avatar">
                </div>
            </div> -->
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <button type="submit" class="btn btn-success pull-right">Save</button>
    </div>
    <!-- /.box-footer -->
    </form>
</div>