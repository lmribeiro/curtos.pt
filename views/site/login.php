<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->name.' | '.Yii::t('app', 'Entrar');

?>
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="">
                    <div class="text-muted text-center">
                        <h3 class="display-3">
                            <?= Yii::t('app', 'Entrar') ?>
                        </h3>
                    </div>
                    <div class="card-body px-lg-5 py-lg-5">
                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'login-form',
                                    'layout' => 'horizontal',
                                    'fieldConfig' => [
                                        'template' => "<div class=\"row\">{label}\n<div class=\"col-lg-12\">{input}</div></div>\n<div class=\"row\"><div class=\"col-lg-12 text-danger\">{error}</div></div>",
                                        'labelOptions' => ['class' => 'col-lg-12 control-label'],
                                    ],
                        ]);

                        ?>

                        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('username'), 'class' => 'form-control shadow border-0']) ?>

                        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password'), 'class' => 'form-control shadow border-0']) ?>

                        <?=
                        $form->field($model, 'rememberMe')->checkbox([
                            'template' => "<div class= \"custom-control custom-checkbox\">{input}\n <label class=\"custom-control-label\"{label}</label> </div>",
                            'class' => 'custom-control-input',
                        ]);

                        ?>

                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <?= Html::submitButton('Entrar', ['class' => 'btn btn-primary btn-block shadow', 'name' => 'login-button']) ?>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <a href="<?= Url::to(['reset-password']) ?>" class="text-light"><small><?= Yii::t('app', 'Recuperar password') ?></small></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>