<?php
/* @var $this yii\web\View */
/* @var $admin app\models\User */

use yii\helpers\Html;
use app\components\Shorter;

$shorter = new Shorter();

$resetLink = $shorter->getShortLink(
        Yii::$app->urlManager->createAbsoluteUrl([
            'site/verify-account',
            'key' => $user->auth_key
        ]), false, 1);

?>

<?= Yii::t('app', 'Olá'); ?> <?= Html::encode($user->username) ?>!
<br/>
<br/>
<?= Yii::t('app', 'Bem-vindo e obrigado por te registares!'); ?>
<br/>
<?= Yii::t('app', 'Usa o botão abaixo para validar a tua conta.'); ?>
<br/>
<br/>
<?= Html::a(Yii::t('app', 'Validar Conta'), $resetLink, ['class' => 'button']) ?>
<br/>
<br/>
<?= Yii::t('app', 'Se o botão não funcionar, usa o link abaixo.') ?>
<br/>
<br/>
<?= Html::a($resetLink, $resetLink) ?>
<br/>
<br/>
<?= Yii::t('app', 'Obrigado.') ?><br/>
<?= Yii::$app->name ?>
