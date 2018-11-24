<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$this->title = '重置密码邮件';
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['manage/mail-change-password', 'token' => $token,'adminuser' => $adminuser,'timestamp' => $time]);
?>
<div class="password-reset">
    <p>尊敬的 <?= Html::encode($adminuser) ?>,您好：</p>

    <p>您的找回密码链接如下：</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

    <p>该链接5分钟内有效，请勿传递给别人！</p>

    <p>该邮件为系统自动发送，请勿回复！</p>
</div>
