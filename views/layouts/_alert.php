<?php
use kartik\growl\Growl;
?>

<?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
    <?php if (in_array($type, ['success', 'danger', 'warning', 'info'])): ?>
        
        <?php
        echo Growl::widget([
            'type' => $type,
            'icon' => 'glyphicon glyphicon-ok-sign',
            //'title' => 'Note',
            'showSeparator' => true,
            'body' => $message
        ]);
        ?>                
    <?php endif ?>
<?php endforeach ?>
