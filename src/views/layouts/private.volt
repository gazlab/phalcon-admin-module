<body class="hold-transition sidebar-mini pace-primary">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ userSession.avatar is not null ? url(userSession.avatar) : gravatar.getAvatar(userSession.username) }}"
                            class="user-image img-circle elevation-2" alt="{{ userSession.username }}">
                        <span
                            class="d-none d-md-inline">{{ userSession.name is defined ? userSession.name : userSession.username }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <li><a href="{{ url('/users/profile') }}" class="dropdown-item">Profile</a></li>

                        <li class="dropdown-divider"></li>
                        <li><a href="{{ url('/session/signOut') }}" class="dropdown-item">Sign Out</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-navy elevation-4">
            <!-- Brand Logo -->
            <a href="{{ url() }}" class="brand-link navbar-purple">
                <img src="{{ url('/../assets/adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ config.gazlab.title.lg is defined ? config.gazlab.title.lg : 'Gazlab Admin' }}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-compact text-sm"
                        data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        {% for group, menu in resources %}
                        <?php if (!is_int($group)) { ?>
                        <li class="nav-header"><?= strtoupper(\Phalcon\Text::humanize($group)) ?></li>
                        {% for child in menu %}
                        <li class="nav-item">
                            <a href="{{ url('/'~child.menu[0]) }}"
                                class="nav-link {{ dispatcher.getControllerName() === child.menu[0] ? 'active' : null }}">
                                <i class="nav-icon {{ child.menu['icon'] }}"></i>
                                <p>{{ child.menu['name'] }}</p>
                            </a>
                        </li>
                        {% endfor %}
                        <?php } else { ?>
                        <li class="nav-item">
                            <a href="{{ url('/'~menu.menu[0]) }}"
                                class="nav-link {{ dispatcher.getControllerName() === menu.menu[0] ? 'active' : null }}">
                                <i class="nav-icon {{ menu.menu['icon'] }}"></i>
                                <p>{{ menu.menu['name'] }}</p>
                            </a>
                        </li>
                        <?php } ?>
                        {% endfor %}
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper text-sm">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>{{ resource.menu['name'] }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                {{ breadcrumbs.output() }}
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                {{ content() }}
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer text-sm">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.0.1
            </div>
            <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
            reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->