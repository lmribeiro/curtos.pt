<h2 class="bd-title mt-5" id="endpoints"><?= Yii::t('app', 'Endpoints') ?></h2>

<h3 class="bd-title mt-4" id="create"><?= Yii::t('app', 'Criar link') ?></h3>
<figure class="highlight"><pre><code>POST /api/v1/create</code></pre></figure>
<div class="table-responsive">
<table class="table table-striped">
    <thead><tr><th scope="col"><?= Yii::t('app', 'Campo JSON') ?></th><th scope="col"><?= Yii::t('app', 'Obrigatório') ?></th><th scope="col"><?= Yii::t('app', 'Descrição') ?></th></tr></thead>
    <tbody>
        <tr><td>target</td><td><?= Yii::t('app', 'Sim') ?></td><td><?= Yii::t('app', 'String com um URL HTTPS completo e sintaticamente válido, com até 255 caracteres. Outros protocolos, valores de outros tipos e endereços inválidos são rejeitados.') ?></td></tr>
        <tr><td>expires_after</td><td><?= Yii::t('app', 'Não') ?></td><td><?= Yii::t('app', 'Intervalo em dias, não uma data. Quando omitido, usa 30 dias na configuração atual. Quando enviado, tem de ser um inteiro JSON positivo: null, zero, negativos, booleanos, texto numérico e números fracionários são rejeitados. A data calculada tem de caber na coluna TIMESTAMP atual, até 2038-01-19 03:14:07 UTC; não existe outra quota de dias.') ?></td></tr>
    </tbody>
</table>
</div>
<p><b><?= Yii::t('app', 'Exemplo cURL') ?></b></p>
<figure class="highlight"><pre><code>curl --include --request POST "$API_BASE/api/v1/create" \
  --header "Authorization: $API_KEY" \
  --header 'Content-Type: application/json' \
  --data '{"target":"https://example.com/","expires_after":60}'</code></pre></figure>
<p><?= Yii::t('app', 'Exemplo ilustrativo de resposta. O código e a data são gerados pelo serviço; a data usa o fuso horário configurado na aplicação.') ?></p>
<figure class="highlight"><pre><code>{
    "name": "Created",
    "message": "Short link created with success",
    "code": 201,
    "status": "OK",
    "data": {
        "code": "a1b2c3",
        "target": "https://example.com/",
        "short": "https://curtos.pt/a1b2c3",
        "expires_after": "2027-01-12 12:00:00"
    }
}</code></pre></figure>
<p><?= Yii::t('app', 'Se repetires este pedido, podes criar outro link para o mesmo destino. A data de expiração não implica remoção automática nem bloqueio do redirecionamento nesta versão.') ?></p>

<h3 class="bd-title mt-5" id="delete"><?= Yii::t('app', 'Apagar link') ?></h3>
<figure class="highlight"><pre><code>DELETE /api/v1/delete</code></pre></figure>
<p><?= Yii::t('app', 'Envia o campo obrigatório code como string no corpo JSON. Aceita 1 a 255 letras ASCII, algarismos ou underscores. Usa o código devolvido pela criação, não o URL completo. A eliminação é permanente e apaga também os registos de visitas associados ao link.') ?></p>
<figure class="highlight"><pre><code>curl --include --request DELETE "$API_BASE/api/v1/delete" \
  --header "Authorization: $API_KEY" \
  --header 'Content-Type: application/json' \
  --data '{"code":"a1b2c3"}'</code></pre></figure>
<figure class="highlight"><pre><code>{
    "name": "OK",
    "message": "Short link deleted with success",
    "code": 200,
    "status": "OK",
    "data": []
}</code></pre></figure>
<p><?= Yii::t('app', 'Uma nova tentativa após uma eliminação concluída devolve normalmente code 404, com message igual a Link not found.') ?></p>

<h3 class="bd-title mt-5" id="stats"><?= Yii::t('app', 'Consultar estatísticas') ?></h3>
<figure class="highlight"><pre><code>GET /api/v1/stats?code={code}</code></pre></figure>
<p><?= Yii::t('app', 'Envia o código obrigatório na query string, com 1 a 255 letras ASCII, algarismos ou underscores. Um código em falta ou inválido devolve code 400; um código válido mas inexistente devolve 404. Este pedido não cria uma visita ao link.') ?></p>
<figure class="highlight"><pre><code>curl --include "$API_BASE/api/v1/stats?code=a1b2c3" \
  --header "Authorization: $API_KEY"</code></pre></figure>
<figure class="highlight"><pre><code>{
    "name": "OK",
    "message": "Data retrieved with success",
    "code": 200,
    "status": "OK",
    "data": {
        "code": "a1b2c3",
        "target": "https://example.com/",
        "short": "https://curtos.pt/a1b2c3",
        "expires_after": "2027-01-12 12:00:00",
        "visits": 5,
        "byBrowser": {
            "Chrome": 2,
            "Firefox": 1,
            "Boots": 0
        },
        "byCountry": {
            "PT": {"name": "Portugal", "count": 2},
            "US": {"name": "United States", "count": 1}
        }
    }
}</code></pre></figure>

<p><?= Yii::t('app', 'visits é o contador histórico. byBrowser e byCountry usam os registos detalhados disponíveis, pelo que as somas podem ser inferiores ao contador. Boots é a designação histórica usada pela API para navegadores não reconhecidos e não comprova que todas essas visitas sejam bots.') ?></p>
<p><?= Yii::t('app', 'A API v1 devolve apenas code, target, short, expires_after, visits, byBrowser e byCountry. Os novos indicadores e os filtros de período da página do link não estão expostos neste endpoint; o parâmetro days dessa página não é um filtro da API v1.') ?></p>
