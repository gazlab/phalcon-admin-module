{% do assets.addCss('gazlab_assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') %}

{% do assets.addCss('gazlab_assets/bower_components/select2/dist/css/select2.min.css') %}

<body class="hold-transition skin-purple sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="{{ url(router.getModuleName()) }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">{{ config.gazlab.logo.sm }}</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">{{ config.gazlab.logo.lg }}</span>
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
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ userSession.avatar is not empty ? userSession.avatar : gravatar.getAvatar(userSession.username) }}"
                                    class="user-image" alt="User Image">
                                <span class="hidden-xs">{{ userSession.username }}</span>
                            </a>
                            <ul class="dropdown-menu" role="menu" style="width: auto;">
                                <li><a href="{{ url(router.getModuleName()~'/users/profile') }}"
                                        style="color: inherit;">Profile</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ url(router.getModuleName()~'/session/signOut') }}"
                                        style="color: inherit;">Sign
                                        Out</a></li>
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
                    {% if resource.menu['type'] is not defined %}
                    <?php if (!is_int($group)) { ?>
                    <li class="header">{{ group|upper }}</li>
                    {% for child in resource %}
                    <li>
                        <a href="{{ url(router.getModuleName()~'/'~child.menu[0]) }}">
                            <i class="{{ child.menu['icon'] }}"></i> <span>{{ child.menu['name']|capitalize }}</span>
                        </a>
                    </li>
                    {% endfor %}
                    <?php }else{ ?>
                    <li>
                        <a href="{{ url(router.getModuleName()~'/'~resource.menu[0]) }}">
                            <i class="{{ resource.menu['icon'] }}"></i>
                            <span>{{ resource.menu['name']|capitalize }}</span>
                        </a>
                    </li>
                    <?php } ?>
                    {% endif %}
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
                    {{ currentResource.menu['name']|capitalize }}
                    {% if currentResource.menu['description'] is defined %}
                    <small>{{ currentResource.menu['description'] }}</small>
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
            <div class="pull-right hidden-xs">
                <b>Version</b> 1.0
            </div>
            {{ config.gazlab.footer }}
        </footer>
    </div>
    <!-- ./wrapper -->

    {% do assets.addJs('gazlab_assets/bower_components/datatables.net/js/jquery.dataTables.min.js') %}
    {% do assets.addJs('gazlab_assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') %}

    {% do assets.addJs('gazlab_assets/bower_components/select2/dist/js/select2.full.min.js') %}

    {% do assets.addJs('gazlab_assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') %}
    {% do assets.addJs('gazlab_assets/bower_components/fastclick/lib/fastclick.js') %}

    {% do assets.addJs('//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js', false) %}

    {% do assets.addInlineJs(view.getPartial(view.getLayoutsDir()~'/private.js')) %}