<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?=base_url('/uiux/web/vendors/ecs/images/app_icons/'.$app_info->app_icon)?>">
    <title><?=$title?></title>
    <!-- Bootstrap core CSS -->
    <link href="<?=base_url('/uiux/web/vendors/bootstrap/dist/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('/uiux/web/vendors/Font-Awesome/css/font-awesome.min.css')?>" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?=base_url('uiux/web/vendors/AdminLTE/dist/css/AdminLTE.min.css')?>" rel="stylesheet" type="text/css" />
    <link href="<?=base_url('/uiux/web/vendors/ecs/css/credential.css')?>" rel="stylesheet">
    <?=$_styles?>
    <?=$_scripts?>

  </head>

  <body data-spy="scroll" data-offset="0" data-target="#navigation">

  <div><?=$band_name?></div>
		<div>
			<?=$content?>
		</div>
<br><br><br>
<nav class="navbar navbar-inverse navbar-fixed-bottom" role="navigation">
    <div class="navbar-text"><?=$footer?></div>
  </div>
</nav>
  </body>
</html>