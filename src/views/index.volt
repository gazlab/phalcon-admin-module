<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{ get_title() }}
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    {{ stylesheet_link('../assets/adminlte/plugins/fontawesome-free/css/all.min.css') }}
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- pace-progress -->
    {{ stylesheet_link('../assets/adminlte/plugins/pace-progress/themes/black/pace-theme-flat-top.css') }}
    <!-- adminlte-->
    {{ stylesheet_link('../assets/adminlte/dist/css/adminlte.min.css') }}
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    {{ assets.outputInlineCss() }}
</head>

{{ content() }}

<!-- jQuery -->
{{ javascript_include('../assets/adminlte/plugins/jquery/jquery.min.js') }}
<!-- Bootstrap 4 -->
{{ javascript_include('../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}
<!-- pace-progress -->
{{ javascript_include('../assets/adminlte/plugins/pace-progress/pace.min.js') }}
<!-- AdminLTE App -->
{{ javascript_include('../assets/adminlte/dist/js/adminlte.min.js') }}
<!-- AdminLTE for demo purposes -->
{{ javascript_include('../assets/adminlte/dist/js/demo.js') }}
</body>

</html>