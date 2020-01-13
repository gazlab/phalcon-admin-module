<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url() }}">{{ config.gazlab.title.lg is defined ? config.gazlab.title.lg : '<b>Gazlab</b> Admin' }}</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ content() ? content() : 'Sign in to start your session' }}</p>

                <form action="{{ url('/session/signIn') }}" method="post">
                    <input type='hidden' name='<?php echo $this->security->getTokenKey() ?>'
                        value='<?php echo $this->security->getToken() ?>' />
                    <div class="input-group mb-3">
                        <input class="form-control" placeholder="Username" name="username" autofocus required
                            autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 offset-8">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->