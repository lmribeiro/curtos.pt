<h5 class="bd-title mt-5" id="endpoints"><?= Yii::t('app', 'Endpoints') ?></h5>

<h6 class="bd-title mt-4" id="create"><?= Yii::t('app', 'Criar Link') ?></h6>
<p class="bd-lead">
    <?= Yii::t('app', 'Criar um link curto para o determinado target longo.') ?>
</p>

<p><b><?= Yii::t('app', 'Pedido') ?></b></p>
<figure class="highlight">
    <pre><code><span class="badge badge-success float-right">POST</span>Endpoint: /api/v1/create
Host: https://curtos.pt</code></pre>

</figure>

<p><b><?= Yii::t('app', 'Payload') ?></b></p>
<figure class="highlight">
    <pre><code><span class="badge badge-secondary float-right">JSON</span>{
	"target": "https://curtos.pt",
	"expires_after": 60 //<?= Yii::t('app', 'Opcional') ?>

}</code></pre>

</figure>

<p><b><?= Yii::t('app', 'Resposta') ?></b></p>
<figure class="highlight">
    <pre><code><span class="badge badge-secondary float-right">JSON</span>{
    "name": "OK",
    "message": "Short link created with success",
    "code": 200,
    "status": 200,
    "data": {
        "id": 110,
        "code": "3825d5",
        "target": "https://www.curtos.pt/",
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
    <pre><code><span class="badge badge-danger float-right">DELETE</span>Endpoint: /api/v1/delete
Host: https://curtos.pt</code></pre>

</figure>

<p><b><?= Yii::t('app', 'Payload') ?></b></p>
<figure class="highlight">
    <pre><code><span class="badge badge-secondary float-right">JSON</span>{
	"code": "18acbf"
}</code></pre>

</figure>

<p><b><?= Yii::t('app', 'Resposta') ?></b></p>
<figure class="highlight">
    <pre><code><span class="badge badge-secondary float-right">JSON</span>{
    "name": "OK",
    "message": "Short link deleted with success",
    "code": 200,
    "status": 200,
    "data": []
}</code></pre>
</figure>