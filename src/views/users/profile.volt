{{ content() }}
{{ flashSession.output() }}
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                            src="{{ userSession.avatar is not null ? url(userSession.avatar) : gravatar.getAvatar(userSession.username) }}"
                            alt="{{ userSession.username }}">
                    </div>

                    <h3 class="profile-username text-center">
                        {{ userSession.name is defined ? userSession.name : userSession.username }}</h3>

                    <p class="text-muted text-center">{{ userSession.profile.name }}</p>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#change_password" data-toggle="tab">Change
                                Password</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="settings">
                            <form class="form-horizontal" action="{{ url('/session/profile') }}" method="post">
                                <input type='hidden' name='<?php echo $this->security->getTokenKey() ?>'
                                    value='<?php echo $this->security->getToken() ?>' />
                                <div class="form-group row">
                                    <label for="inputUsername" class="col-sm-2 col-form-label">Username</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="inputUsername" placeholder="Username"
                                            value="{{ userSession.username }}" required name="username">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputAvatar" class="col-sm-2 col-form-label">Avatar</label>
                                    <div class="col-sm-10 input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="inputAvatar"
                                                placeholder="Avatar" name="avatar">
                                            <label class="custom-file-label" for="inputAvatar">Choose file</label>
                                        </div>
                                        <!-- <div class="input-group-append">
                                            <span class="input-group-text" id="">Upload</span>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-save    "></i>
                                            Save</button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button class="btn btn-xs btn-danger"><i class="fas fa-trash    "></i> Delete
                                            Account</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="change_password">
                            <form class="form-horizontal" action="{{ url('/session/profile#change_password') }}" method="post">
                                <input type='hidden' name='<?php echo $this->security->getTokenKey() ?>'
                                    value='<?php echo $this->security->getToken() ?>' />
                                <div class="form-group row">
                                    <label for="inputOldPassword" class="col-sm-2 col-form-label">Old Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="inputOldPassword"
                                            placeholder="Old Password" required name="old_password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputNewPassword" class="col-sm-2 col-form-label">New Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="inputNewPassword"
                                            placeholder="New Password" required name="new_password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputConfirmPassword" class="col-sm-2 col-form-label">Confirmation
                                        Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="inputConfirmPassword"
                                            placeholder="Confirmation Password" required name="confirm_password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-warning"><i class="fas fa-save    "></i>
                                            Change</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->

{% do assets.addJs('/../assets/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') %}
{% do assets.addInlineJs('$(document).ready(function () {
    bsCustomFileInput.init();
  });') %}