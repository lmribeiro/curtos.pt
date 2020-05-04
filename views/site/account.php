<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->name." | ".Yii::t('app', 'Conta');
$this->params['modals'][] = "accountDelete";
$this->params['modals'][] = "apiKey";

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
            <div class="">
                <div class="row">
                    <div class="col-xs-12 col-lg-5 mb-5">
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
                    <div class="col-xs-12 col-lg-5 offset-lg-2 mb-5">
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

            <div class="">
                <div class="row ">
                    <div class="col-xs-12 col-lg-12 mb-5 mt-5">
                        <h5 class="mb-5"><?= Yii::t('app', 'API') ?></h5>
                        <p>
                            <?= Yii::t('app', 'Além deste site, também pode usar a API para criar e apagar links curtos. Se não sabe como funciona uma API, não a use. NÃO partilhe essa chave no lado do cliente do seu site.') ?>
                            <?= Yii::t('app', 'Tem duvidas?') ?> <?= Html::tag('span', Html::a(Yii::t('app', 'Veja a documentação'), 'api-v1')); ?>.

                        </p>

                        <p class="mt-4">
                            <b><?= Yii::t('app', 'Chave') ?>:</b> 
                            <input id="api-key" class="text-success text-underline border-0" value="<?= Yii::$app->user->identity->auth_key ?>" />&nbsp;
                            <button  type="button" onclick="javascript:app.copyKey()" data-toggle="tooltip" data-placement="top" title="<?= Yii::t('app', 'Copiar') ?>"  class="btn btn-sm btn-link" >
                                <i class="far fa-copy fa-lg"></i>
                            </button>
                        </p>
                        <a href="#"  class="btn btn-primary btn-sm shadow border-0" data-toggle='modal' data-target='#apiKey_modal' >
                            <i class="fas fa-bolt"></i> <?= Yii::t('app', 'Regenerar Chave') ?>
                        </a>    
                    </div>

                </div>
            </div>
            <div class="">
                <div class="row ">
                    <div class="col-xs-12 col-lg-12 mb-5 mt-5">
                        <h5 class="mb-5 text-danger"><?= Yii::t('app', 'Apagar Conta') ?></h5>
                        <p class="text-danger"> 
                            <?= Yii::t('app', 'Esta ação vai apagar todos os seus dados e links registados.') ?>
                            <?= Yii::t('app', 'Depois de ativar esta opção a mesma não pode ser revertida nem os seus dados podem ser recuperados.') ?>
                            <?= Yii::t('app', 'Continue apenas se tem a certeza que é isso que quer fazer.') ?>

                        </p>
                        <p class="mt-5">
                            <?= Html::tag('span', Html::a('<i class="fas fa-trash"></i> '.Yii::t('app', 'Apagar'), '#', ['class' => 'btn btn-danger shadow border-0', 'data-toggle' => 'modal', 'data-target' => '#delete_modal'])); ?>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>