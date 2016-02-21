<!DOCTYPE html>
<html lang="en">
<head>
    <?=$this->Html->charset(); ?>
    <meta name="viewport"    content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author"      content="Thomas Kanzig (thomaskanzig.com), Emerson Gomes (emersonpgomes@gmail.com)">
    
    <title><?=$title_for_layout;?></title>

    <?=$this->Html->meta('favicon.ico','/favicon.ico',array('type' => 'icon'));?>
    
    <link rel="stylesheet" media="screen" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
    <?=$this->Html->css('landing-pages/bootstrap.min');?>
    <?=$this->Html->css('landing-pages/font-awesome.min');?>

    <!-- Custom styles for our template -->
    <?=$this->Html->css('landing-pages/bootstrap-theme');?>
    <?=$this->Html->css('landing-pages/main');?>

    <!-- JavaScript libs are placed at the end of the document so the pages load faster -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>    

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="<?=$this->request->params['action'];?>">
    
    <?=$this->element('landing-pages-navbar'); ?>

    <?=$this->fetch('content'); ?>
    
    <?=$this->element('landing-pages-footer'); ?>
        
    <?=$this->Html->script('landing-pages/headroom.min');?>
    <?=$this->Html->script('landing-pages/jQuery.headroom.min');?>
    <?=$this->Html->script('landing-pages/template');?>
</body>
</html>