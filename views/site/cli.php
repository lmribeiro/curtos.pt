<?php

use app\assets\AppAsset;

$this->registerJsFile('@web/js/docs-navigation.js', ['depends' => [AppAsset::class]]);

$this->title = Yii::$app->name . " | " . Yii::t('app', 'CLI');

?>
<div class="section mt-5 documentation-page">
    <div class="container">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 mx-auto text-center mb-2">
                    <h1 class="display-3"><?= Yii::t('app', 'Command Line Interface') ?></h1>
                    <p class="lead"><?= Yii::t('app', 'Documentação da Command Line Interface para Curtos.pt') ?>.</p>
                </div>
            </div>
        </div>
        <div class="row mt-5 docs-layout">
            <div class="col-12 col-lg-9 order-2 order-lg-1 docs-content">
                <main class="bd-content" role="main">

                    <div>
                        <h2 class="bd-title" id="intro"><?= Yii::t('app', 'Visão Geral') ?></h2>
                        <p class="bd-lead">
                            <?= Yii::t('app', 'Pensada para developers, a curtos-cli é uma cli simples e prática que permite usar a API curtos.pt através da linha de comandos.') ?>
                        </p>
                        <p><?= Yii::t('app', 'Os comandos abaixo são exemplos históricos. A API atual exige uma chave de uma conta ativa, e o registo público está desativado. Confirma no repositório da CLI as instruções de autenticação e configuração da versão que tens instalada; esta página não garante compatibilidade da versão antiga com a API atual.') ?></p>
                        <p><a href="<?= \yii\helpers\Url::to(['/api-v1']) ?>"><?= Yii::t('app', 'Consultar autenticação, exemplos cURL e tratamento de erros da API') ?></a></p>

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

            <aside class="col-12 col-lg-3 order-1 order-lg-2 docs-sidebar">
                <?= $this->render('_docs/navigation', [
                    'label' => 'Índice da documentação da CLI',
                    'items' => [
                        ['intro', 'Visão geral', [
                            ['repos', 'Repositórios'],
                            ['version', 'Versão'],
                        ]],
                        ['install', 'Instalar'],
                        ['commands', 'Comandos', [
                            ['create', 'curtos create'],
                            ['delete', 'curtos delete'],
                        ]],
                        ['changelog', 'Changelog'],
                    ],
                ]) ?>
            </aside>
        </div>
    </div>
</div>
