<h2 class="bd-title mt-5" id="analytics"><?= Yii::t('app', 'Estatísticas na página do link') ?></h2>
<p><?= Yii::t('app', 'A página /curto/{code} mostra todo o histórico por defeito. Podes selecionar os últimos 7, 30 ou 90 dias; os gráficos e as contagens do período usam apenas visitas com registos detalhados. A última visita registada considera todo o histórico disponível.') ?></p>
<ul>
    <li><?= Yii::t('app', 'Visitas por dia, hora e dia da semana; IPs distintos; navegadores; dispositivos e sistemas operativos.') ?></li>
    <li><?= Yii::t('app', 'Distribuição por país e rankings das dez cidades, regiões e origens com mais visitas.') ?></li>
    <li><?= Yii::t('app', 'Estado e tempo até à data de expiração, sem garantia de bloqueio ou remoção automática quando essa data passa.') ?></li>
</ul>
<p><?= Yii::t('app', 'IPs distintos são uma aproximação de visitantes únicos: várias pessoas podem partilhar um IP e uma pessoa pode usar vários IPs. Registos antigos ou falhas de gravação podem explicar diferenças face ao contador histórico.') ?></p>

<h3 class="bd-title mt-4" id="collected-data"><?= Yii::t('app', 'Dados recolhidos e origens') ?></h3>
<p><?= Yii::t('app', 'As novas visitas registam o IP, navegador e versão, sistema operativo, tipo de dispositivo e a localização disponível. A identificação de tablets depende do user agent enviado pelo navegador e não reclassifica os registos antigos.') ?></p>
<p><?= Yii::t('app', 'A origem guarda apenas o domínio do header Referer, sem credenciais, caminho, parâmetros ou fragmento. Os registos antigos com origem nula aparecem como Não recolhida; um header ausente ou inválido aparece como Direto / desconhecido. Referer e user agent podem estar ausentes ou ser alterados pelo cliente.') ?></p>
<p><?= Yii::t('app', 'A resolução do IP usa a configuração do pedido Yii. Instalações atrás de proxies devem confirmar a configuração de proxies de confiança antes de interpretar os dados. Não existe uma rotina automática de retenção ou limpeza de visitas neste repositório.') ?></p>

<h3 class="bd-title mt-4" id="geolocation"><?= Yii::t('app', 'Geolocalização') ?></h3>
<p><?= Yii::t('app', 'A localização é aproximada e obtida por HTTPS através de ipwho.is. As consultas enviam o IP público do visitante a esse fornecedor. IPs privados, reservados ou inválidos não são consultados; em localhost, é normal haver visitas sem país.') ?></p>
<p><?= Yii::t('app', 'O serviço externo documenta atualmente 1 000 consultas gratuitas por dia por IP do servidor, sem chave e com uso comercial permitido. Confirma as condições e os limites na documentação do fornecedor antes de usar o serviço.') ?> <a href="https://ipwhois.io/documentation" target="_blank" rel="noopener noreferrer"><?= Yii::t('app', 'Documentação ipwho.is') ?></a>.</p>
<p><?= Yii::t('app', 'O timeout é de 2 segundos. A cache por IP guarda respostas válidas durante 24 horas e falhas durante 5 minutos. A quota pode esgotar-se com muitos IPs distintos. Uma falha da API externa ou uma resposta inválida não impede a gravação dos restantes dados válidos da visita, que fica sem localização. Não há preenchimento retroativo da localização dos registos antigos.') ?></p>
<p><?= Yii::t('app', 'Ao apagar um link, os seus registos de visitas associados também são apagados. Quem aloja uma instalação deve proteger os backups e adequar a informação de privacidade e a retenção aos dados recolhidos e à consulta externa de IPs.') ?></p>
