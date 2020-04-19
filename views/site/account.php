<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->name." | ".Yii::t('app', 'Conta');
$this->params['modals'][] = "accountDelete";

?>
<div class="section">
    <div class="container">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <!--<span class="badge badge-primary badge-pill mb-3">&nbsp;</span>-->
                    <h3 class="display-3"><?= Yii::t('app', 'A tua conta') ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="mt-5">
            <div class="px-4">
                <div class="row">
                    <div class="col-xs-12 col-lg-6 px-lg-5 mb-5">
                        <h5 class="mb-5"><?= Yii::t('app', 'Dados pesssoais') ?></h5>
                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'account-form',
                                    'layout' => 'horizontal',
                                    'fieldConfig' => [
                                        'template' => "<div class=\"row\">{label}\n<div class=\"col-lg-12\">{input}</div></div>\n<div class=\"row\"><div class=\"col-lg-12 text-danger\">{error}</div></div>",
                                        'labelOptions' => ['class' => 'col-lg-12 control-label'],
                                    ],
                        ]);

                        ?>
                        <?=
                                $form->field($account, 'name')
                                ->textInput(['placeholder' => $account->getAttributeLabel('name'), 'class' => 'form-control shadow border-0'])

                        ?>
                        <?=
                                $form->field($account, 'username')
                                ->textInput(['placeholder' => $account->getAttributeLabel('username'), 'class' => 'form-control shadow border-0'])

                        ?>
                        <?=
                                $form->field($account, 'email')
                                ->textInput(['placeholder' => $account->getAttributeLabel('email'), 'class' => 'form-control shadow border-0'])

                        ?>

                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <?= Html::submitButton(Yii::t('app', 'Guardar'), ['class' => 'btn btn-primary shadow border-0 float-right', 'type' => 'submit', 'id' => 'account-btn']); ?>
                            </div>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                    <div class="col-xs-12 col-lg-6 px-lg-5 mb-5">
                        <h5 class="mb-5"><?= Yii::t('app', 'Alterar Password') ?></h5>
                        <?php
                        $form2 = ActiveForm::begin([
                                    'id' => 'password-form',
                                    'layout' => 'horizontal',
                                    'fieldConfig' => [
                                        'template' => "<div class=\"row\">{label}\n<div class=\"col-lg-12\">{input}</div></div>\n<div class=\"row\"><div class=\"col-lg-12 text-danger\">{error}</div></div>",
                                        'labelOptions' => ['class' => 'col-lg-12 control-label'],
                                    ],
                        ]);

                        ?>
                        <?=
                                $form2->field($password, 'password')
                                ->passwordInput(['placeholder' => $password->getAttributeLabel('password'), 'class' => 'form-control shadow border-0'])

                        ?>

                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <?= Html::submitButton(Yii::t('app', 'Guardar'), ['class' => 'btn btn-primary shadow border-0 float-right', 'type' => 'submit', 'id' => 'password-btn']); ?>
                            </div>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>

            <div class="px-5">
                <div class="row border-top">
                    <div class="col-xs-12 col-lg-6 text-center text-lg-left">
                        <h5 class="mb-5 mt-5"><?= Yii::t('app', 'Apagar Conta') ?></h5>
                    </div>
                    <div class="col-xs-12 col-lg-6 text-center text-lg-right">
                        <div class="btn-wrapper mb-5 mt-5">
                            <?= Html::tag('span', Html::a('<i class="fas fa-trash"></i> '.Yii::t('app', 'Apagar conta'), '#', ['class' => 'btn btn-danger shadow border-0', 'data-toggle' => 'modal', 'data-target' => '#delete_modal'])); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>