<?php 
use dimple\administrator\assets\AdministratorAssets;
AdministratorAssets::register($this);
?>
<?php $this->beginContent('@app/views/layouts/main.php') ?>
 <?= $content ?>
<?php $this->endContent() ?>