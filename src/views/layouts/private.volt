<body class="hold-transition {{ config.gazlab.skin is defined ? config.gazlab.skin : 'skin-purple' }} sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="{{ url() }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span
                    class="logo-mini">{{ config.gazlab.logo.sm is defined ? config.gazlab.logo.sm : '<b>GA</b>Z' }}</span>
                <!-- logo for regular state and mobile devices -->
                <span
                    class="logo-lg">{{ config.gazlab.logo.lg is defined ? config.gazlab.logo.lg : '<b>Gazlab</b>Admin' }}</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ userSession.avatar }}" class="user-image" alt="{{ userSession.username }}">
                                <span
                                    class="hidden-xs">{{ userSession.name is defined ? userSession.name : userSession.username }}</span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('users/profile') }}">Profile</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ url('session/signOut') }}">Sign Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- =============================================== -->

        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    {% for group, resource in resources %}
                    <?php if (!is_int($group)) { ?>
                    <li class="header"><?= strtoupper(\Phalcon\Text::humanize($group)) ?></li>
                    {% for child in resource %}
                    <li class="{{ router.getControllerName() === child.menu[0] ? 'active' : null }}">
                        <a href="{{ url(child.menu[0]) }}">
                            <i class="{{ child.menu['icon'] }}"></i> <span>{{ child.menu['name'] }}</span>
                        </a>
                    </li>
                    {% endfor %}
                    <?php } else { ?>
                    <li class="{{ router.getControllerName() === resource.menu[0] ? 'active' : null }}">
                        <a href="{{ url(resource.menu[0]) }}">
                            <i class="{{ resource.menu['icon'] }}"></i> <span>{{ resource.menu['name'] }}</span>
                        </a>
                    </li>
                    <?php } ?>
                    {% endfor %}
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    {{ currentResource.menu['name'] }}
                    {% if router.getActionName() is not 'index' %}
                    <small><?= ucwords(\Phalcon\Text::humanize(\Phalcon\Text::uncamelize($this->router->getActionName(), '-'))) ?></small>
                    {% endif %}
                </h1>
                <ol class="breadcrumb">
                    {{ breadcrumbs.output() }}
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                {{ flashSession.output() }}
                {{ content() }}
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer text-sm">
            {{ config.gazlab.footer is defined ? config.gazlab.footer : '<strong>Copyright &copy; 2019 Gazlab Admin.</strong> All rights reserved.' }}
        </footer>
    </div>

    {% do assets.addCss('../assets/adminlte/bower_components/select2/dist/css/select2.min.css') %}
    {% do assets.addCss('../assets/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') %}
    {% do assets.addInlineCss(view.getPartial(view.getLayoutsDir()~'_private.css')) %}

    {% do assets.addJs('../assets/adminlte/bower_components/select2/dist/js/select2.full.min.js') %}
    {% do assets.addJs('../assets/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') %}
    {% do assets.addInlineJs(view.getPartial(view.getLayoutsDir()~'_private.js')) %}