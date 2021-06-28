<?php

use yii\helpers\Url;

?>
<footer class="footer">
    <div class="container">
        <div class="row ">
            <div class="col-sm-12 col-md-6">
                <div class="text-lg-left text-center">
                    <p>
                        &copy; 2020 - <?= date('Y') ?> Curtos.pt
                    </p>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="text-lg-right text-center">
                    <p>
                        <?= Yii::t('app', 'Url shortener gratuito e ') ?> 
                        <a class="text-info" href="https://github.com/lmribeiro/curtos.pt" rel="noreferrer" class="nav-link" target="_blank">
                            <?= Yii::t('app', 'Open Source') ?>.
                        </a> 
                        <span class="d-none d-lg-inline"> | </span>
                        <br class="d-lg-none" />
                        <a class="text-info" href="<?= Url::to(['/terms']) ?>" rel="noreferrer" class="nav-link">
                            <?= Yii::t('app', 'Termos de Uso') ?>.
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
