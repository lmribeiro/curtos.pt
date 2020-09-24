<?php
/* @var $this yii\web\View */

$this->title = Yii::$app->name." | ".Yii::t('app', 'Os teus links... curtos!');

?>
<div id="landing" class="section section-shaped mt-5">
    <div class="page-header">
        <div class="container shape-container d-flex align-items-center">
            <div class="col">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-8 mx-auto text-center">
                        <h3 class="display-3"><?= Yii::t('app', 'Os teus links... curtos!') ?></h3>
                    </div>
                    <div class="col-lg-6 mt-lg-4">
                        <div id="target-div">
                            <input type="text" name="target" id="target" class="form-control form-control-lg shadow border-0 mt-4" placeholder="https://..." />
                            <div class="col-lg-12 text-danger">
                                <p class="help-block visible-off"><?= Yii::t('app', 'Url inválido!') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col col-lg-1 d-none" id="copy">
                        <button  type="button" onclick="javascript:app.copy()" data-toggle="tooltip" data-placement="top" title="<?= Yii::t('app', 'Copiar') ?>"  class="btn btn-neutral btn-block btn-lg btn-icon shadow border-0 mb-sm-0" >
                            <span class="btn-inner--icon"><i class="far fa-copy"></i></span>
                            <span class="btn-inner--text d-lg-none"><?= Yii::t('app', 'Copiar') ?></span>
                        </button>
                    </div>
                    <div class="col-sm-4 col-lg-2">
                        <div class="btn-wrapper">
                            <button type="button" onclick="javascript:app.short()" class="btn btn-neutral btn-block btn-lg btn-icon shadow border-0 mb-sm-0" >
                                <span class="btn-inner--icon"><i class="fas fa-link"></i></span>
                                <span class="btn-inner--text"><?= Yii::t('app', 'Curto') ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
