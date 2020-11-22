<?php

$this->title = Yii::$app->name . " | " . Yii::t('app', 'CLI');

?>
<div class="section  mt-5">
    <div class="container">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 mx-auto text-center mb-2">
                    <h1 class="display-3"><?= Yii::t('app', 'Command Line Interface') ?></h1>
                    <p class="lead"><?= Yii::t('app', 'Documentação da Command Line Interface para Curtos.pt') ?>.</p>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-9">
                <main class="col-12  bd-content" role="main">

                    <div>
                        <h2 class="bd-title" id="intro"><?= Yii::t('app', 'Visão Geral') ?></h2>
                        <p class="bd-lead">
                            <?= Yii::t('app', 'Pensada para developers, a curtos-cli é uma cli simples e prática que permite usar a API curtos.pt através da linha de comandos.') ?>
                        </p>

                        <h2 class="bd-title mt-2" id="repos"><?= Yii::t('app', 'Repositórios') ?></h2>
                        <p class="bd-lead">
                            GitHub: <a target="new"
                                       href="https://github.com/curtos-pt/cli">https://github.com/curtos-pt/cli</a><br/>
                            NPM: <a target="new" href="https://www.npmjs.com/package/curtos-cli">https://www.npmjs.com/package/curtos-cli</a><br/>
                        </p>


                        <h2 class="bd-title mt-2" id="version"><?= Yii::t('app', 'Versão') ?></h2>
                        <p class="bd-lead">
                            <b>1.0.0</b><br/>
                            <?= Yii::t('app', 'Data criação') ?>: 2020/07/11<br/>
                            <!--<?= Yii::t('app', 'Última atualização') ?>: 2020/07/11<br/>-->
                        </p>
                    </div>

                    <div class="mt-5">
                        <h2 class="bd-title mt-5" id="install"><?= Yii::t('app', 'Instalar') ?></h2>

                        <p class="bd-lead">
                            <?= Yii::t('app', 'Para instalar curtos-cli podes usar o npm.') ?>
                        </p>

                        <figure class="highlight">
    <pre><code><span class="badge badge-secondary float-right">BASH</span>INSTALL
    $ npm install -g curtos-cli</code></pre>
                        </figure>
                    </div>

                    <div class="mt-5">
                        <h2 class="bd-title mt-5" id="commands"><?= Yii::t('app', 'Comandos') ?></h2>

                        <h3 class="bd-title mt-4" id="create">curtos create</h3>
                        <p class="bd-lead">
                            <?= Yii::t('app', 'Devolve um link curto para o determinado target longo.') ?>
                        </p>

                        <figure class="highlight">
    <pre><code><span class="badge badge-secondary float-right">BASH</span>USAGE
  $ curtos create

OPTIONS
  -e, --expiresAfter=expiresAfter  time to expire in days
  -t, --target=target              target url</code></pre>
                        </figure>

                        <h3 class="bd-title mt-5" id="delete">curtos delete</h3>
                        <p class="bd-lead">
                            <?= Yii::t('app', 'Apagar um link curto com determinado código.') ?>
                        </p>

                        <p class="mt-3"><b><?= Yii::t('app', 'Payload') ?></b></p>
                        <figure class="highlight">
    <pre><code><span class="badge badge-secondary float-right">BASH</span>USAGE
  $ curtos delete

OPTIONS
  -c, --code=code  link's code</code></pre>
                        </figure>

                    </div>

                    <div class="mt-5">
                        <h2 class="bd-title mt-5" id="changelog"><?= Yii::t('app', 'Changelog') ?></h2>
                        <p class="bd-lead">2020/07/11</p>
                        <ul>
                            <li><?= Yii::t('app', 'Lançamento') ?></li>
                        </ul>
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
                                    <a class="side-link" href="#repos"><?= Yii::t('app', 'Repositórios') ?></a>
                                </li>
                                <li class="toc-entry toc-h3">
                                    <a class="side-link" href="#version"><?= Yii::t('app', 'Versão') ?></a>
                                </li>
                            </ul>
                        </li>

                        <li class="toc-entry toc-h2">
                            <a class="side-link" href="#install"><?= Yii::t('app', 'Instalar') ?></a>
                        </li>

                        <li class="toc-entry toc-h2">
                            <a href="#commands"><?= Yii::t('app', 'Comandos') ?></a>
                            <ul>
                                <li class="toc-entry toc-h3">
                                    <a class="side-link" href="#create"><?= Yii::t('app', 'Criar') ?></a>
                                </li>
                                <li class="toc-entry toc-h3">
                                    <a class="side-link" href="#delete"><?= Yii::t('app', 'Apagar') ?></a>
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
