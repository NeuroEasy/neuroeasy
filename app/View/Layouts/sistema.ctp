<!DOCTYPE html>
<html lang="pt-br">
  
<head>
    <?=$this->Html->charset(); ?>

    <title><?=$title_for_layout;?></title>

    <?=$this->Html->meta('favicon.ico','/favicon.ico',array('type' => 'icon'));?>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    
    <?=$this->Html->css('sistema/bootstrap.min');?>
    
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    
    <?=$this->Html->css('sistema/font-awesome.min');?>
    
    <?=$this->Html->css('sistema/style');?>

    <?=$this->Html->script('sistema/jQuery-2.1.4.min');?>
    <?=$this->Html->script('sistema/bootstrap.min');?>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

<body>

<?=$this->element('sistema-navbar'); ?>
     
<?=$this->element('sistema-subnavbar'); ?>

<?=$this->Session->flash('top-page'); ?>

<?=$this->fetch('content'); ?>
       
<?=$this->element('sistema-footer'); ?>

</body>

</html>
