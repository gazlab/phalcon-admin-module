<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url() }}">{{ config.gazlab.logo.lg }}</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            {{ content() }}
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="{{ url('session/signIn') }}" method="post">
                <div class="form-group has-feedback">
                    <input class="form-control" placeholder="Username" name="username" required autofocus>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <!-- <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> Remember Me
                            </label>
                        </div>
                    </div> -->
                    <!-- /.col -->
                    <div class="col-xs-4 col-xs-offset-8">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <!-- <div class="social-auth-links text-center">
                <p>- OR -</p>
                <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign
                    in using
                    Facebook</a>
                <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign
                    in using
                    Google+</a>
            </div>
            <!-- /.social-auth-links

            <a href="#">I forgot my password</a><br>
            <a href="register.html" class="text-center">Register a new membership</a> -->

        </div>
        <!-- /.login-box-body -->
    </div>

    {# do assets.addCss('../assets/adminlte/plugins/iCheck/square/blue.css') #}

    {# do assets.addJs('../assets/adminlte/plugins/iCheck/icheck.min.js') #}
    {# do assets.addInlineJs(view.getPartial(config.application.viewsDir~'session/_signIn.js')) #}