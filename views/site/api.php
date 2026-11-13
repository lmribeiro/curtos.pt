<?php

use app\assets\AppAsset;

$this->registerJsFile('@web/js/docs-navigation.js', ['depends' => [AppAsset::class]]);
$this->title = Yii::$app->name . " | " . Yii::t('app', 'API');

?>
<div class="section mt-5 documentation-page">
    <div class="container">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 mx-auto text-center mb-2">
                    <h1 class="display-3"><?= Yii::t('app', 'API') ?></h1>
                    <p class="lead"><?= Yii::t('app', 'Documentação da API Curtos.pt') ?>.</p>
                </div>
            </div>
        </div>
        <div class="row mt-5 docs-layout">
            <div class="col-12 col-lg-9 order-2 order-lg-1 docs-content">
                <main class="bd-content" role="main">

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
                        <?php include __DIR__ . '/_api/analytics.php'; ?>
                    </div>

                    <div class="mt-5">
                        <?php include __DIR__ . '/_api/changelog.php'; ?>
                    </div>

                </main>
            </div>
            <aside class="col-12 col-lg-3 order-1 order-lg-2 docs-sidebar">
                <?= $this->render('_docs/navigation', [
                    'label' => 'Índice da documentação da API',
                    'items' => [
                        ['intro', 'Visão geral', [
                            ['auth', 'Autenticação'],
                            ['encode', 'Formato dos pedidos'],
                            ['version', 'Versão e endereço base'],
                            ['examples', 'Exemplos cURL'],
                            ['validation', 'Validação e CORS'],
                            ['limits', 'Limitações'],
                        ]],
                        ['tests', 'Testar a integração'],
                        ['codes', 'Códigos e erros'],
                        ['endpoints', 'Endpoints', [
                            ['create', 'Criar link'],
                            ['delete', 'Apagar link'],
                            ['stats', 'Consultar estatísticas'],
                        ]],
                        ['analytics', 'Estatísticas na página', [
                            ['collected-data', 'Dados recolhidos'],
                            ['geolocation', 'Geolocalização'],
                        ]],
                        ['changelog', 'Changelog'],
                    ],
                ]) ?>
            </aside>
        </div>
    </div>
</div>
