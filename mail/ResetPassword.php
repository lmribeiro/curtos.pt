<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Shorter;

/* @var $this yii\web\View */
/* @var $admin app\models\User */

$shorter = new Shorter();

$link = $shorter->getShortLink(
        Yii::$app->urlManager->createAbsoluteUrl([
            'site/set-password',
            'token' => $user->password_reset_token
        ]), false, 1);

$resetLink = Url::base(true)."/".$link->short;

?>

<?= Yii::t('app', 'Olá'); ?> <?= Html::encode($user->name ? $user->name : $user->username) ?>,
<br/>
<br/>
<?= Yii::t('app', 'Recebemos um pedido de recuperação de password.'); ?>
<br/>
<?= Yii::t('app', 'Usa o botão abaixo para continuar.'); ?>
<br/>
<br/>
<?= Html::a(Yii::t('app', 'Recuperar Password'), $resetLink, ['class' => 'button']) ?>
<br/>
<br/>
<?= Yii::t('app', 'Se o botão não funcionar, usa o link curto abaixo.') ?>
<br/>
<br/>
<?= Html::a($resetLink, $resetLink) ?>
<br/>
<br/>
<?= Yii::t('app', 'Não fizeste este pedido?') ?>
<br/>
<?= Yii::t('app', 'Não precisas de fazer nada. A tua conta continua segura.') ?>
<br/>
<br/>
<?= Yii::t('app', 'Obrigado.') ?><br/>
<?= Yii::$app->name ?>
