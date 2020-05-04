<h5 class="bd-title mt-5" id="tests"><?= Yii::t('app', 'Testar') ?></h5>
<p class="bd-lead">
    <?= Yii::t('app', 'A melhor maneira de testar o serviço é criar uma conta. Dessa forma, podes testar todas as funcionalidades e obter controle total dos dados.') ?>
</p>
<?php if (Yii::$app->user->isGuest) { ?>
    <p>
        <a class="btn btn-secondary" href="<?= \yii\helpers\Url::to(['signup']) ?>"><?= Yii::t('app', 'Criar conta') ?></a>
    </p>
<?php } else { ?>
    <p class="bd-lead">
        <?= Yii::t('app', 'Como já fizeste esse passo, podes passar para a documentação dos') ?> <a href="#endpoints" class="">Endpoints</a>.
    </p>
<?php } ?>
