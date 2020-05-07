<?php
/* @var $this yii\web\View */

$this->title = Yii::$app->name." | ".Yii::t('app', 'Sobre');

?>
<div class="section">
    <div class="container">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 mx-auto text-center mb-2">
                    <!--<span class="badge badge-primary badge-pill mb-3">&nbsp;</span>-->
                    <h3 class="display-3"><?= Yii::t('app', 'Sobre o Curtos.pt') ?></h3>
                    <p class="lead"><?= Yii::t('app', 'Url shortener gratuito e Open Source.') ?></p>
                </div>
            </div>
        </div>
        <div class="row mt-5 text-justify">
            <div class="col-12">
                <p>
                    <?= Yii::t('app', 'Curtos.pt é um Url shortener feito em PHP gratuito para todos os utilizadores. Curtos.pt também é uma aplicação de código aberto (Open Source), e está disponível no GitHub para quem o quiser alojar o seu próprio serviço.') ?>
                </p>
            </div>
        </div>
        <div class="row mt-5 mb-2">
            <div class="col">
                <div class="description">
                    <h5 class="title"><?= Yii::t('app', 'FAQS') ?></h5>
                </div>
            </div>
        </div>

        <div class="row text-justify">
            <div class="col-md-4 mb-3">
                <div class="info">
                    <h6><?= Yii::t('app', 'Como funciona?') ?></h6>
                    <p class="description opacity-8"><?= Yii::t('app', 'Basta inserir um url, a página inical, e selecionar o botão "Curto" para obter um link curto para o url inserido. Quando os utilizadores sseguem o link curto, são reencaminhados para o url origial.') ?></p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="info">
                    <h6><?= Yii::t('app', 'Preciso criar conta?') ?></h6>
                    <p class="description opacity-8"><?= Yii::t('app', 'Não é necessário criar conta para poder usar o Curtos.pt, no entanto utilizadores registados têm acesso a mais funcionalidades.') ?></p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="info">
                    <h6><?= Yii::t('app', 'O link curto expira?') ?></h6>
                    <p class="description opacity-8"><?= Yii::t('app', 'Normalmente um link curto expira em 30 dias. Uma vez expirado, o link é automáticamente apagado. Utilizadores registados podem prelongar a data de expiração.') ?></p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="info">
                    <h6><?= Yii::t('app', 'Que tecnologias usa?') ?></h6>
                    <p class="description opacity-8"><?= Yii::t('app', 'O Curtos.pt é desenvolvido em PHP e usa a framework Yii como base. Alem disso, também usar JQuery, Bootstrap e base de dados MySQL.') ?></p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="info">
                    <h6><?= Yii::t('app', 'Tem uma API?') ?></h6>
                    <p class="description opacity-8"><?= Yii::t('app', 'Sim, o Curtos.pt está disponivel tanto para utilizadores registados como utilizadores sem registo.') ?>
                        <a class="text-info" href="<?= \yii\helpers\Url::to(['api-v1']) ?>" class="nav-link">
                            <?= Yii::t('app', 'Ver Documentação') ?>.
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
