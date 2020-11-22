<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::$app->name . ' | ' . Yii::t('app', 'Alterar Password');

?>

<section class="section mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="">
                    <div class="text-muted text-center">
                        <h1 class="display-3"><?= Yii::t('app', 'Alterar Password') ?></h1>
                    </div>
                    <div class="card-body px-lg-5 py-lg-5">
                        <?php
                        $form = ActiveForm::begin([
                            'layout' => 'horizontal',
                            'fieldConfig' => [
                                'template' => "<div class=\"row\">{label}\n<div class=\"col-lg-12\">{input}</div></div>\n<div class=\"row\"><div class=\"col-lg-12 text-danger\">{error}</div></div>",
                                'labelOptions' => ['class' => 'col-lg-12 control-label'],
                            ],
                        ]);

                        ?>

                        <?= $form->field($model, 'password')->passwordInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('password'), 'class' => 'form-control shadow border-0']); ?>

                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <?= Html::submitButton(Yii::t('app', 'Guardar'), ['class' => 'btn btn-primary btn-block shadow border-0', 'name' => 'login-button']); ?>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
