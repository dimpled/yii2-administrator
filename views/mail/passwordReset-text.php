<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

//$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/administrator/recovery/reset', 'id'=>$user->id,'token' => $token]);
?>
Hello <?= $user->username ?>,

Follow the link below to reset your password:

<?= $user->getConfirmUrl('recover') ?>
