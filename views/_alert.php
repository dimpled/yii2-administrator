<?php
use yii\bootstrap\Alert;
?>
    <div class="row">
        <div class="col-xs-12">
        <h1><?= isset($title)?$title:null;?> </h1>
            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
                <?php if (in_array($type, ['success', 'danger', 'warning', 'info'])): ?>
                   <?= Alert::widget([
                        'options' => [
                            'class' => 'alert-'.$type,
                        ],
                        'body' =>  $message,
                    ]);?>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
