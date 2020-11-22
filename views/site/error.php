<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use yii\helpers\Html;

$this->title = Yii::$app->name . " | " . $name;

?>
<div class="section mt-5">
    <div class="container">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <!--<span class="badge badge-primary badge-pill mb-3">&nbsp;</span>-->
                    <h1 class="display-3"><?= Yii::t('app', 'Erro!') ?></h1>
                    <p class="lead"><?= Html::encode($name) ?></p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="text-center">

                <div class="alert alert-danger">
                    <p><?= nl2br(Html::encode($message)) ?></p>
                </div>
                <p>
                    <?= Yii::t('app', 'Este ocorreu enquanto o servidor processava o teu pedido.') ?>
                    <br/>
                    <?= Yii::t('app', 'Por favor contacta-nos se achares que isso é um erro do servidor. Obrigado.') ?>
                </p>
            </div>
        </div>
    </div>
</div>
