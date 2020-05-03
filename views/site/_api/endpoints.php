<h5 class="bd-title mt-5" id="endpoints"><?= Yii::t('app', 'Endpoints') ?></h5>
<p class="bd-lead">
    Get started with Bootstrap, the world’s most popular framework for building responsive, mobile-first sites, with BootstrapCDN and a template starter page.
</p>

<h6 class="bd-title mt-5" id="create"><?= Yii::t('app', 'Criar Link') ?></h6>
<p class="bd-lead">
    <?= Yii::t('app', 'Criar um link curto para o determinado link longo.') ?>
</p>

<p><b><?= Yii::t('app', 'Pedido') ?></b></p>
<figure class="highlight">
    <pre><code>POST /api/v1/create
Host: https://curtos.pt</code></pre>

</figure>

<p><b><?= Yii::t('app', 'Payload') ?></b></p>
<figure class="highlight">
    <pre><code>{
	"target": "https://curtos.pt",
	"expires_after": 60 //<?= Yii::t('app', 'Opcional') ?>

}</code></pre>

</figure>

<p><b><?= Yii::t('app', 'Resposta') ?></b></p>
<figure class="highlight">
    <pre><code>{
    "name": "OK",
    "message": "Short link created with success",
    "code": 200,
    "status": 200,
    "data": {
        "id": 110,
        "code": "3825d5",
        "target": "https://www.linkya.xyz",
        "short": "http://localhost:8383/3825d5",
        "expires_after": "2020-07-02 23:53:46"
    }
}</code></pre>
</figure>

<h6 class="bd-title mt-5" id="delete"><?= Yii::t('app', 'Apagar Link') ?></h6>
<p class="bd-lead">
    <?= Yii::t('app', 'Apagar um link curto com determinado código.') ?>
</p>

<p><b><?= Yii::t('app', 'Pedido') ?></b></p>
<figure class="highlight">
    <pre><code>POST /api/v1/delete
Host: https://curtos.pt</code></pre>

</figure>

<p><b><?= Yii::t('app', 'Payload') ?></b></p>
<figure class="highlight">
    <pre><code>{
	"code": "18acbf"
}</code></pre>

</figure>

<p><b><?= Yii::t('app', 'Resposta') ?></b></p>
<figure class="highlight">
    <pre><code>{
    "name": "OK",
    "message": "Short link deleted with success",
    "code": 200,
    "status": 200,
    "data": []
}</code></pre>
</figure>