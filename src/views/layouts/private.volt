<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ url() }}" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fa fa-user"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ url('session/profile') }}" class="dropdown-item">
                        Profile
                    </a>
                    <a href="{{ url('session/signOut') }}" class="dropdown-item">
                        Sign Out
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar elevation-4 sidebar-dark-danger">
        <!-- Brand Logo -->
        <a href="{{ url(router.getModuleName()) }}" class="brand-link bg-danger text-center">
            <span class="brand-text font-weight-light"><b>Imove</b> ADMIN</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ gravatar.getAvatar(userSession.name) }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ userSession.name }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                    {%- macro render_menus(resources) %}
                    {%- for resource in resources %}
                    <li class="nav-item {{ resource['childs'] is defined ? 'has-treeview' : null }}">
                        <a href="{{ resource['childs'] is defined ? '#' : url(router.getModuleName() ~ '/' ~ resource['controller_name']) }}"
                            class="nav-link">
                            <i class="{{ resource['icon'] }} nav-icon"></i>
                            <p>
                                {{ resource['name'] }}
                                {%- if resource['childs'] is defined %}
                                <i class="right fa fa-angle-left"></i>
                                {%- endif %}
                            </p>
                        </a>
                        {%- if resource['childs'] is defined %}
                        <ul class="nav nav-treeview">
                            {{ render_menus(resource['childs']) }}
                        </ul>
                        {%- endif %}
                    </li>
                    {%- endfor %}
                    {%- endmacro %}
                    {{ render_menus(resources) }}
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ resource.name }}</h1>
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

    <footer class="main-footer">
        {{ config.imove.footer }}
    </footer>
</div>