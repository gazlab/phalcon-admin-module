<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{ getTitle() }}
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    {{ assets.outputCss() }}
    <!-- Theme style -->
    {{ stylesheet_link('gazlab_assets/dist/css/adminlte.min.css') }}
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    {{ content() }}

    {{ javascript_include('gazlab_assets/plugins/jquery/jquery.min.js') }}
    {{ javascript_include('gazlab_assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}
    {{ assets.outputJs() }}
    {{ javascript_include('gazlab_assets/plugins/slimScroll/jquery.slimscroll.min.js') }}
    {{ javascript_include('gazlab_assets/plugins/fastclick/fastclick.js') }}
    {{ javascript_include('gazlab_assets/dist/js/adminlte.min.js') }}
    {{ assets.outputInlineJs() }}
</body>

</html>