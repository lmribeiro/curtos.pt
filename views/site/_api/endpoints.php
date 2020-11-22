<h2 class="bd-title mt-5" id="endpoints"><?= Yii::t('app', 'Endpoints') ?></h2>

<h3 class="bd-title mt-4" id="create"><?= Yii::t('app', 'Criar Link') ?></h3>
<p class="bd-lead">
    <?= Yii::t('app', 'Criar um link curto para o determinado target longo.') ?>
</p>

<p class="mt-3"><b><?= Yii::t('app', 'Pedido') ?></b></p>
<figure class="highlight">
    <pre><code><span class="badge badge-success float-right">POST</span>Endpoint: /api/v1/create
Host: https://curtos.pt</code></pre>

</figure>

<p class="mt-3"><b><?= Yii::t('app', 'Payload') ?></b></p>
<figure class="highlight">
    <pre><code><span class="badge badge-secondary float-right">JSON</span>{
	"target": "https://curtos.pt",
	"expires_after": 60 //<?= Yii::t('app', 'Opcional') ?>

}</code></pre>

</figure>

<p class="mt-3"><b><?= Yii::t('app', 'Resposta') ?></b></p>
<figure class="highlight">
    <pre><code><span class="badge badge-secondary float-right">JSON</span>{
    "name": "Created",
    "message": "Short link created with success",
    "code": 201,
    "status": "OK",
    "data": {
        "code": "183adb",
        "target": "https://curtos.pt/",
        "short": "https://www.curtos.pt/183adb",
        "expires_after": "2020-07-02 23:53:46"
    }
}</code></pre>
</figure>

<h3 class="bd-title mt-5" id="delete"><?= Yii::t('app', 'Apagar Link') ?></h3>
<p class="bd-lead">
    <?= Yii::t('app', 'Apagar um link curto com determinado código.') ?>
</p>

<p class="mt-3"><b><?= Yii::t('app', 'Pedido') ?></b></p>
<figure class="highlight">
    <pre><code><span class="badge badge-danger float-right">DELETE</span>Endpoint: /api/v1/delete
Host: https://curtos.pt</code></pre>

</figure>

<p class="mt-3"><b><?= Yii::t('app', 'Payload') ?></b></p>
<figure class="highlight">
    <pre><code><span class="badge badge-secondary float-right">JSON</span>{
	"code": "183adb"
}</code></pre>

</figure>

<p class="mt-3"><b><?= Yii::t('app', 'Resposta') ?></b></p>
<figure class="highlight">
    <pre><code><span class="badge badge-secondary float-right">JSON</span>{
    "name": "OK",
    "message": "Short link deleted with success",
    "code": 200,
    "status": "OK",
    "data": []
}</code></pre>
</figure>


<h3 class="bd-title mt-5" id="stats"><?= Yii::t('app', 'Estatísticas') ?></h3>
<p class="bd-lead">
    <?= Yii::t('app', 'Obter estatísticas de um link curto com determinado código.') ?>
</p>

<p class="mt-3"><b><?= Yii::t('app', 'Pedido') ?></b></p>
<figure class="highlight">
    <pre><code><span class="badge badge-primary float-right">GET</span>Endpoint: /api/v1/stats?code={code}
Host: https://curtos.pt</code></pre>

</figure>

<p class="mt-3"><b><?= Yii::t('app', 'Resposta') ?></b></p>
<figure class="highlight">
    <pre><code><span class="badge badge-secondary float-right">JSON</span>{
    "name": "OK",
    "message": "Data retrieved with success",
    "code": 200,
    "status": "OK",
    "data": {
        "code": "183adb",
        "target": "https://curtos.pt",
        "short": "https://curtos.pt/183adb",
        "expires_after": "2021-02-21 23:05:12",
        "visits": 37,
        "byBrowser": {
            "Chrome": 12,
            "Firefox": 6,
            "Safari": 6,
            "Opera": 6,
            "Internet Explorer": 5,
            "Edge": 5,
            "Boots": 0
        },
        "byCountry": {
            "ES": {
                "name": "España",
                "count": 2
            },
            "FR": {
                "name": "France",
                "count": 1
            },
            "IT": {
                "name": "Italia",
                "count": 2
            },
            "PT": {
                "name": "Portugal",
                "count": 17
            },
            "US": {
                "name": "United States",
                "count": 1
            }
        }
    }
}</code></pre>
</figure>
