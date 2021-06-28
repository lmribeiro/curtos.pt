<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = Yii::$app->name . " | " . Yii::t('app', 'Os teus links... curtos!');

?>
<div id="landing" class="section section-shaped mt-5">
    <div class="page-header">
        <div class="container shape-container d-flex align-items-center">
            <div class="col">
                <?php if (!Yii::$app->user->isGuest) { ?>
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-8 mx-auto text-center">
                        <h1 class="display-3"><?= Yii::t('app', 'Os teus links... curtos!') ?></h1>
                    </div>
                    <div class="col-lg-6 mt-lg-4">
                        <div id="target-div">
                            <input type="text" name="target" id="target"
                                   class="form-control form-control-lg shadow border-0 mt-4"
                                   placeholder="https://..."/>
                            <div class="col-lg-12 text-danger">
                                <p class="help-block visible-off"><?= Yii::t('app', 'Url inválido!') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col col-lg-1 d-none" id="copy">
                        <button type="button" onclick="javascript:app.copy()" data-toggle="tooltip"
                                data-placement="top" title="<?= Yii::t('app', 'Copiar') ?>"
                                class="btn btn-neutral btn-block btn-lg btn-icon shadow border-0 mb-sm-0">
                            <span class="btn-inner--icon"><i class="far fa-copy"></i></span>
                            <span class="btn-inner--text d-lg-none"><?= Yii::t('app', 'Copiar') ?></span>
                        </button>
                    </div>
                    <div class="col-sm-4 col-lg-2">
                        <div class="btn-wrapper">
                            <button type="button" onclick="javascript:app.short()"
                                    class="btn btn-neutral btn-block btn-lg btn-icon shadow border-0 mb-sm-0">
                                <span class="btn-inner--icon"><i class="fas fa-link"></i></span>
                                <span class="btn-inner--text"><?= Yii::t('app', 'Curto') ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else { ?>
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-8 mx-auto text-center">
                        <h1 class="display-3">
                            <?= Yii::t('app', 'Url shortener ') ?>
                        </h1>
                        <div class="col-sm-12 col-md-12">
                            <div class="text-lg-center text-center">
                                <p>
                                    <?= Yii::t('app', 'Gratuito e ') ?>
                                    <a class="text-info" href="https://github.com/lmribeiro/curtos.pt" rel="noreferrer" class="nav-link" target="_blank">
                                        <?= Yii::t('app', 'Open Source') ?>.
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mx-auto text-center mt-5">
                        <div id="carousel" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carousel" data-slide-to="0" class="active"></li>
                                <li data-target="#carousel" data-slide-to="1"></li>
                                <li data-target="#carousel" data-slide-to="2"></li>
                                <li data-target="#carousel" data-slide-to="3"></li>
                                <li data-target="#carousel" data-slide-to="4"></li>
                                <li data-target="#carousel" data-slide-to="5"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="<?= Url::to('@web/img/slide1.png', true) ?>" class="d-block w-100" alt="">
                                </div>
                                <div class="carousel-item">
                                    <img src="<?= Url::to('@web/img/slide5.png', true) ?>" class="d-block w-100" alt="">
                                </div>
                                <div class="carousel-item">
                                    <img src="<?= Url::to('@web/img/slide6.png', true) ?>" class="d-block w-100" alt="">
                                </div>
                                <div class="carousel-item">
                                    <img src="<?= Url::to('@web/img/slide2.png', true) ?>" class="d-block w-100" alt="">
                                </div>
                                <div class="carousel-item">
                                    <img src="<?= Url::to('@web/img/slide3.png', true) ?>" class="d-block w-100" alt="">
                                </div>
                                <div class="carousel-item">
                                    <img src="<?= Url::to('@web/img/slide4.png', true) ?>" class="d-block w-100" alt="">
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carousel" role="button"
                               data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carousel" role="button"
                               data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
