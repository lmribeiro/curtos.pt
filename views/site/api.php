<?php
$this->title = Yii::$app->name . " | " . Yii::t('app', 'API');

?>
<div class="section  mt-5">
    <div class="container">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 mx-auto text-center mb-2">
                    <h1 class="display-3"><?= Yii::t('app', 'API') ?></h1>
                    <p class="lead"><?= Yii::t('app', 'Documentação da API Curtos.pt') ?>.</p>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="row">
                <div class="col-9">
                    <main class="col-12  bd-content" role="main">

                        <div>
                            <?php include __DIR__ . '/_api/intro.php'; ?>
                        </div>

                        <div class="mt-5">
                            <?php include __DIR__ . '/_api/tests.php'; ?>
                        </div>

                        <div class="mt-5">
                            <?php include __DIR__ . '/_api/codes.php'; ?>
                        </div>

                        <div class="mt-5">
                            <?php include __DIR__ . '/_api/endpoints.php'; ?>
                        </div>

                        <div class="mt-5">
                            <?php include __DIR__ . '/_api/changelog.php'; ?>
                        </div>

                    </main>
                </div>
                <div class="col-3 mt-4">
                    <nav id="nav" aria-label="Secondary navigation" class="d-none d-xl-block col-12 bd-toc">
                        <ul class="section-nav">
                            <li class="toc-entry toc-h2">
                                <a href="#intro"><?= Yii::t('app', 'Visão Geral') ?></a>
                                <ul>
                                    <li class="toc-entry toc-h3">
                                        <a class="side-link" href="#auth"><?= Yii::t('app', 'Autenticação') ?></a>
                                    </li>
                                    <li class="toc-entry toc-h3">
                                        <a class="side-link" href="#encode"><?= Yii::t('app', 'Encoding') ?></a>
                                    </li>
                                    <li class="toc-entry toc-h3">
                                        <a class="side-link" href="#version"><?= Yii::t('app', 'Versão') ?></a>
                                    </li>
                                </ul>
                            </li>

                            <li class="toc-entry toc-h2">
                                <a class="side-link" href="#tests"><?= Yii::t('app', 'Testar') ?></a>
                            </li>

                            <li class="toc-entry toc-h2">
                                <a class="side-link" href="#codes"><?= Yii::t('app', 'Códigos de Resposta') ?></a>
                            </li>

                            <li class="toc-entry toc-h2">
                                <a href="#endpoints"><?= Yii::t('app', 'Endpoints') ?></a>
                                <ul>
                                    <li class="toc-entry toc-h3">
                                        <a class="side-link" href="#create"><?= Yii::t('app', 'Criar') ?></a>
                                    </li>
                                    <li class="toc-entry toc-h3">
                                        <a class="side-link" href="#delete"><?= Yii::t('app', 'Apagar') ?></a>
                                    </li>
                                    <li class="toc-entry toc-h3">
                                        <a class="side-link" href="#stats"><?= Yii::t('app', 'Estatísticas') ?></a>
                                    </li>
                                </ul>
                            </li>

                            <li class="toc-entry toc-h2">
                                <a class="side-link" href="#changelog"><?= Yii::t('app', 'Changelog') ?></a>
                            </li>

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(window).bind('scroll', function () {
            const navHeight = $(window).height() - 652;
            if ($(window).scrollTop() > navHeight) {
                $('#nav').addClass('fixed');
            } else {
                $('#nav').removeClass('fixed');
            }
        });
    });
</script>
