<?php
/* @var $this yii\web\View */
/* @var $user app\models\User */

use yii\helpers\Html;

?>

<?= Yii::t('app', 'Olá'); ?> <?= Html::encode($user->username) ?>!
<br/>
<br/>
<?= Yii::t('app', 'Bem-vindo e obrigado por te registares!'); ?>
<br/>
<?= Yii::t('app', 'Para mais, explora a API e a CLI do curtos.pt.'); ?>
<br/>
<br/>
<a href="https://curtos.pt/api-v1">https://curtos.pt/api</a>
<br/>
<a href="https://curtos.pt/cli">https://curtos.pt/cli</a>
<br/>
<br/>
<?= Yii::t('app', 'Obrigado.') ?><br/>
<?= Yii::$app->name ?>
