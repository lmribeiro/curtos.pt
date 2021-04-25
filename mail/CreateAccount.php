<?php
/* @var $this yii\web\View */
/* @var $email String */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Shorter;

$shorter = new Shorter();

$link = $shorter->getShortLink(
        Yii::$app->urlManager->createAbsoluteUrl([
            'site/create-account',
            'email' => $email
        ]), false, 1);

$resetLink = Url::base(true)."/".$link->short;

?>

<?= Yii::t('app', 'Olá'); ?>!
<br/>
<br/>
<?= Yii::t('app', 'Bem-vindo e obrigado pelo interesse no curtos.pt!'); ?>
<br/>
<?= Yii::t('app', 'Usa o botão abaixo para criares a tua conta.'); ?>
<br/>
<br/>
<?= Html::a(Yii::t('app', 'Criar Conta'), $resetLink, ['class' => 'button']) ?>
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
