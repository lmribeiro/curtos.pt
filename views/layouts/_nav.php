<?php

use yii\helpers\Url;

?>
<nav id="navbar-main" class="navbar navbar-main navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand mr-lg-5" href="<?= Yii::$app->homeUrl ?>">
            <img src="<?= Url::to('@web/img/name.svg', true) ?>" style="height: 19px; margin-top: -6px;">
        </a>
        <button class="navbar-toggler" style="margin-right: -10px;" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
            <span class="fas fa-bars fa-lg"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbar_global">
            <div class="navbar-collapse-header">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="<?= Yii::$app->homeUrl ?>">
                            <img src="<?= Url::to('@web/img/name.svg', true) ?>" style="height: 18px;">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <ul class="navbar-nav align-items-lg-center">
                <li class="nav-item text-uppercase">
                    <a href="<?= Yii::$app->homeUrl ?>" class="nav-link <?= Yii::$app->controller->action->id == "index" ? 'active' : '' ?>" role="button">
                        <span class="nav-link-inner--text"><?= Yii::t('app', 'Início') ?></span>
                    </a>
                </li>
                <li class="nav-item text-uppercase">
                    <a href="<?= Url::to(['about']) ?>" class="nav-link <?= Yii::$app->controller->action->id == "about" ? 'active' : '' ?>" role="button">
                        <span class="nav-link-inner--text"><?= Yii::t('app', 'Sobre') ?></span>
                    </a>
                </li>
                <li class="nav-item text-uppercase">
                    <a href="<?= Url::to(['api-v1']) ?>" class="nav-link <?= Yii::$app->controller->action->id == "api-v1" ? 'active' : '' ?>" role="button">
                        <span class="nav-link-inner--text"><?= Yii::t('app', 'API') ?></span>
                    </a>
                </li>
                <?php if (!Yii::$app->user->isGuest) { ?>
                    <li class="nav-item text-uppercase">
                        <a href="<?= Url::to(['links']) ?>" class="nav-link <?= Yii::$app->controller->action->id == "links" ? 'active' : '' ?>" role="button">
                            <span class="nav-link-inner--text"><?= Yii::t('app', 'Links') ?></span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (Yii::$app->user->isGuest) { ?>
                    <a href="<?= Url::to(['login']) ?>" class="btn btn-neutral px-4 mb-3 mt-5 d-sm-none">
                        <?= Yii::t('app', 'Entar') ?>
                    </a>
                    <a href="<?= Url::to(['signup']) ?>" class="btn btn-primary d-sm-none">
                        <?= Yii::t('app', 'Registar') ?>
                    </a> 
                <?php } else { ?>
                    <div class="d-sm-none">
                        <p class="mt-5">
                            <span class="fas fa-user-circle fa-lg"></span>
                            <?= Yii::$app->user->identity->name ?? Yii::$app->user->identity->username ?>
                        </p>
                        <p>
                            <a href="<?= Url::to(['account']) ?>" class="mt-2">
                                <?= Yii::t('app', 'Conta') ?>
                            </a>
                        </p>
                        <p>
                            <a href="<?= Url::to(['logout']) ?>" class="mt-2">
                                <span class="nav-link-inner--text text-danger"><?= Yii::t('app', 'Sair') ?></span>
                                <span class="float-right text-danger"><i class="fas fa-sign-out-alt"></i></span>
                            </a>
                        </p>
                    </div>
                <?php } ?>
                    <div class="toggle-sm d-sm-flex d-lg-none">
                    <?php include __DIR__.'/_toggler.php'; ?>
                </div>
            </ul>
            <br/>

            <ul class="navbar-nav align-items-lg-center ml-lg-auto d-none d-lg-flex">
                <li>
                    <?php include __DIR__.'/_toggler.php'; ?>
                </li>
                <?php if (Yii::$app->user->isGuest) { ?>
                    <li class="nav-item d-lg-block ml-lg-4">
                        <a href="<?= Url::to(['login']) ?>" class="btn btn-neutral px-4">
                            <?= Yii::t('app', 'Entrar') ?>
                        </a>
                    </li>
                    <li class="nav-item d-lg-block ml-lg-4">
                        <a href="<?= Url::to(['signup']) ?>" class="btn btn-primary px-4">
                            <?= Yii::t('app', 'Registar') ?>
                        </a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item dropdown">
                        <a href="#" class="btn btn-md btn-neutral px-4 mb-1" style="min-width: 190px;" data-toggle="dropdown" href="#" role="button">
                            <span class="fas fa-user-circle fa-lg"></span>
                            <?= Yii::$app->user->identity->name ?? Yii::$app->user->identity->username ?>
                        </a>
                        <div class="dropdown-menu">
                            <a href="<?= Url::to(['account']) ?>" class="dropdown-item">
                                <?= Yii::t('app', 'Conta') ?>
                            </a>
                            <a href="<?= Url::to(['logout']) ?>" class="dropdown-item">
                                <span class="nav-link-inner--text text-danger"><?= Yii::t('app', 'Sair') ?></span>
                                <span class="float-right text-danger"><i class="fas fa-sign-out-alt"></i></span>
                            </a>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>