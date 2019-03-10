<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url(router.getModuleName()) }}">{{ config.gazlab.logo.lg }}</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>
            {{ content() }}
            {{ form([router.getModuleName(), 'session', 'signIn']|join('/')) }}
            <div class="form-group has-feedback">
                <input class="form-control" placeholder="Username" name="username" autofocus>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4 col-xs-offset-8">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->