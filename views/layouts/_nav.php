<?php

use yii\helpers\Url;

?>
<nav id="navbar-main" class="navbar navbar-main navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand mr-lg-5" href="<?= Yii::$app->homeUrl ?>">
            <img src="<?= Url::to('@web/img/logo.png', true) ?>">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
            <span class="fas fa-bars fa-lg"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbar_global">
            <div class="navbar-collapse-header">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="<?= Yii::$app->homeUrl ?>">
                            <img src="<?= Url::to('@web/img/logo.png', true) ?>">
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
                <li class="nav-item">
                    <a href="<?= Yii::$app->homeUrl ?>" class="nav-link <?= Yii::$app->controller->action->id == "index" ? 'active' : '' ?>" role="button">
                        <span class="nav-link-inner--text"><?= Yii::t('app', 'Início') ?></span>
                    </a>
                </li>
                <?php if (!Yii::$app->user->isGuest) { ?>
                    <li class="nav-item">
                        <a href="<?= Url::to(['links']) ?>" class="nav-link <?= Yii::$app->controller->action->id == "admin" ? 'active' : '' ?>" role="button">
                            <span class="nav-link-inner--text"><?= Yii::t('app', 'Links') ?></span>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a href="<?= Url::to(['about']) ?>" class="nav-link <?= Yii::$app->controller->action->id == "about" ? 'active' : '' ?>" role="button">
                        <span class="nav-link-inner--text"><?= Yii::t('app', 'Sobre') ?></span>
                    </a>
                </li>
            </ul>
            <br/>
            <ul class="navbar-nav align-items-lg-center ml-lg-auto">
                <li class="nav-item d-lg-block ml-lg-4">
                    <?php if (Yii::$app->user->isGuest) { ?>
                        <a href="<?= Url::to(['login']) ?>" class="btn btn-neutral">
                            <?= Yii::t('app', 'Entrar') ?>
                        </a>
                        <a href="<?= Url::to(['signup']) ?>" class="btn btn-primary">
                            <?= Yii::t('app', 'Registar') ?>
                        </a>
                    <?php } else { ?>
                    <li class="nav-item dropdown">
                        <a href="#" class="btn btn-neutral btn-icon" data-toggle="dropdown" href="#" role="button">
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
                </li>
            </ul>
        </div>
    </div>
</nav>