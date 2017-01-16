<?php ob_start(); ?>
<!doctype html>
<html class='no-js' lang='<?=$lang?>'>
<head>
<meta charset='utf-8'/>
<title><?=$title . $title_append?></title>
<?php if(isset($favicon)): ?><link rel='icon' href='<?=$this->url->asset($favicon)?>'/><?php endif; ?>
<?php foreach($stylesheets as $stylesheet): ?>
<link rel='stylesheet' type='text/css' href='<?=$this->url->asset($stylesheet)?>'/>
<link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<script>var __adobewebfontsappname__="dreamweaver"</script><script src="http://use.edgefonts.net/fascinate:n4:default.js" type="text/javascript"></script> 
<?php endforeach; ?>
<?php if(isset($style)): ?><style><?=$style?></style><?php endif; ?>
<script src='<?=$this->url->asset($modernizr)?>'></script>
</head>

<body>
<div id='adminbar'>
    <?php if(isset($adminbar)) echo $adminbar?>
    <?php $this->views->render('adminbar')?>
</div>

<div id='header'>
    <?php if(isset($header)) echo $header?>
    <?php $this->views->render('header')?>
</div>

<div id='wrapper'>

<div id='main' class='clearboth'>
  <?php if(isset($main)) echo $main?>
  <?php $this->views->render('main')?>
</div>

<div id='footer'>
    <?php if(isset($footer)) echo $footer?>
    <?php $this->views->render('footer')?>
</div>

</div><!--end wrapper-->

<?php if(isset($jquery)):?><script src='<?=$this->url->asset($jquery)?>'></script><?php endif; ?>

<?php if(isset($javascript_include)): foreach($javascript_include as $val): ?>
<script src='<?=$this->url->asset($val)?>'></script>
<?php endforeach; endif; ?>

<?php if(isset($google_analytics)): ?>
<script>
  var _gaq=[['_setAccount','<?=$google_analytics?>'],['_trackPageview']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
  s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
<?php endif; ?>


</body>
</html>
