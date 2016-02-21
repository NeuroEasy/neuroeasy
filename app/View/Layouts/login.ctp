<!DOCTYPE html>
<html lang="pt-br">
  
<head>
<?=$this->Html->charset(); ?>
<title>Login - Bootstrap Admin Template</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes"> 
    
<?=$this->Html->css('sistema/bootstrap.min');?>
<?=$this->Html->css('sistema/bootstrap-responsive.min');?>
<?=$this->Html->css('sistema/font-awesome');?>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">

<?=$this->Html->css('sistema/style');?>
<?=$this->Html->css('sistema/pages/signin');?>

</head>

<body>
  
<?=$this->element('login-navbar'); ?>

<?=$this->fetch('content'); ?>

<?=$this->Html->script('sistema/jQuery-2.1.4.min');?>
<?=$this->Html->script('sistema/bootstrap.min');?>
<?=$this->Html->script('sistema/signin');?>

</body>

</html>
