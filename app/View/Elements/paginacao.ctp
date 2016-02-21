<ul class="pagination hidden-xs hidden-sm">  

  <?php echo $this->MyPaginator->first('« Primeiro', array("tag"=>"li","url"=>$rota_paginacao,"currentClass"=>"active",'separator' => null)); ?>

  <?php echo $this->MyPaginator->prev('‹ Anterior', array("tag"=>"li","url"=>$rota_paginacao), null, array('class' => 'disabled')); ?>

  <?php echo $this->MyPaginator->numbers(array("modulus" => 6,"tag"=>"li","url"=>$rota_paginacao,"currentClass"=>"active",'separator' => null)); ?>

  <?php echo $this->MyPaginator->next('Próximo ›', array("tag"=>"li","url"=>$rota_paginacao), null, array('class' => 'disabled'));?>

  <?php echo $this->MyPaginator->last('Último »', array("tag"=>"li","url"=>$rota_paginacao,"currentClass"=>"active",'separator' => null)); ?>

</ul>  



<ul class="pagination hidden-md hidden-lg">  

  <?php echo $this->MyPaginator->first('«', array("tag"=>"li","url"=>$rota_paginacao,"currentClass"=>"active",'separator' => null)); ?>

  <?php echo $this->MyPaginator->prev('‹', array("tag"=>"li","url"=>$rota_paginacao), null, array('class' => 'disabled')); ?>

  <?php echo $this->MyPaginator->numbers(array("modulus" => 4,"tag"=>"li","url"=>$rota_paginacao,"currentClass"=>"active",'separator' => null)); ?>

  <?php echo $this->MyPaginator->next('›', array("tag"=>"li","url"=>$rota_paginacao), null, array('class' => 'disabled'));?>

  <?php echo $this->MyPaginator->last('»', array("tag"=>"li","url"=>$rota_paginacao,"currentClass"=>"active",'separator' => null)); ?>

</ul>     