<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  {{ get_title() }}
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  {{ stylesheet_link('gazlab_assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}
  {{ stylesheet_link('gazlab_assets/bower_components/font-awesome/css/font-awesome.min.css') }}
  {{ stylesheet_link('gazlab_assets/bower_components/Ionicons/css/ionicons.min.css') }}
  {{ stylesheet_link('gazlab_assets/dist/css/AdminLTE.min.css') }}
  {{ assets.outputCss() }}
  {{ assets.outputInlineCss() }}

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
{{ content() }}

{{ javascript_include('gazlab_assets/bower_components/jquery/dist/jquery.min.js') }}
{{ javascript_include('gazlab_assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}
{{ assets.outputJs() }}
{{ assets.outputInlineJs() }}
</body>

</html>