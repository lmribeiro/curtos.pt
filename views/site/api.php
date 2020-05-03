<div class="section">
    <div class="container">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 mx-auto text-center mb-2">
                    <h3 class="display-3"><?= Yii::t('app', 'API') ?>
                    </h3>
                    <p class="lead"><?= Yii::t('app', 'Documentação da API Curtos.pt') ?>
                        .</p>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div class="row">
                    <div class="col-9">
                        <main class="col-12 py-md-3 pl-md-5 bd-content" role="main">

                            <div >
                                <?php include __DIR__.'/_api/intro.php'; ?>
                            </div>
                            
                            <div class="mt-5">
                                <?php include __DIR__.'/_api/tests.php'; ?>
                            </div>

                            <div class="mt-5">
                                <?php include __DIR__.'/_api/codes.php'; ?>
                            </div>
                            
                            <div class="mt-5">
                                <?php include __DIR__.'/_api/endpoints.php'; ?>
                            </div>
                            
                            <div class="mt-5">
                                <?php include __DIR__.'/_api/changelog.php'; ?>
                            </div>

                        </main>
                    </div>
                    <div class="col-3 mt-4">
                        <nav aria-label="Secondary navigation" class="d-none d-xl-block col-12 bd-toc" style="position: fixed;top: 350px;">
                            <ul class="section-nav">
                                <li class="toc-entry toc-h2">
                                    <a href="#intro"><?= Yii::t('app', 'Visão Geral') ?></a>
                                    <ul>
                                        <li class="toc-entry toc-h3">
                                            <a href="#auth"><?= Yii::t('app', 'Autenticação') ?></a>
                                        </li>
                                        <li class="toc-entry toc-h3">
                                            <a href="#encode"><?= Yii::t('app', 'Encoding') ?></a>
                                        </li>
                                        <li class="toc-entry toc-h3">
                                            <a href="#version"><?= Yii::t('app', 'Versão    ') ?></a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="toc-entry toc-h2">
                                    <a href="#tests"><?= Yii::t('app', 'Testar') ?></a>
                                </li>

                                <li class="toc-entry toc-h2">
                                    <a href="#codes"><?= Yii::t('app', 'Códigos de Resposta') ?></a>
                                </li>

                                <li class="toc-entry toc-h2">
                                    <a href="#endpoints"><?= Yii::t('app', 'Endpoints') ?></a>
                                    <ul>
                                        <li class="toc-entry toc-h3">
                                            <a href="#create"><?= Yii::t('app', 'Criar') ?></a>
                                        </li>
                                        <li class="toc-entry toc-h3">
                                            <a href="#delete"><?= Yii::t('app', 'Apagar') ?></a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="toc-entry toc-h2">
                                    <a href="#changelog"><?= Yii::t('app', 'Changelog') ?></a>
                                </li>

                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>