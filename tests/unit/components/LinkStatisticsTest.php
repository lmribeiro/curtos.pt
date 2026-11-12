<?php

namespace tests\unit\components;

use app\components\LinkStatistics;
use app\models\Link;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\console\Application;
use yii\db\Connection;

class LinkStatisticsTest extends TestCase
{
    protected function setUp(): void
    {
        new Application([
            'id' => 'link-statistics-test',
            'basePath' => dirname(__DIR__, 3),
            'language' => 'pt',
            'timeZone' => 'Europe/Lisbon',
            'components' => [
                'db' => ['class' => Connection::class, 'dsn' => 'sqlite::memory:'],
                'urlManager' => [
                    'baseUrl' => 'http://localhost:8383', 'scriptUrl' => '/index.php',
                    'enablePrettyUrl' => true, 'showScriptName' => false,
                ],
            ],
        ]);
        Yii::$app->db->createCommand()->createTable('link', ['id' => 'pk', 'short' => 'string'])->execute();
        Yii::$app->db->createCommand()->batchInsert('link', ['id'], [[1], [2], [3], [4]])->execute();
        Yii::$app->db->createCommand()->createTable('link_stats', [
            'id' => 'pk', 'link_id' => 'integer', 'ip' => 'string', 'created_at' => 'string',
            'browser' => 'string', 'platform' => 'string', 'os' => 'string', 'city' => 'string',
            'region' => 'string', 'country_code' => 'string', 'country_name' => 'string', 'referrer' => 'string',
        ])->execute();
        $today = date('Y-m-d') . ' 00:00:00';
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $old = date('Y-m-d', strtotime('-8 days')) . ' 00:00:00';
        $future = date('Y-m-d', strtotime('+1 day')) . ' 00:00:00';
        Yii::$app->db->createCommand()->batchInsert('link_stats', [
            'link_id', 'ip', 'created_at', 'browser', 'platform', 'os', 'city', 'region', 'country_code', 'country_name', 'referrer',
        ], [
            [1, '192.0.2.1', $yesterday . ' 10:00:00', 'Chrome', 'mobile', 'android', 'Lisbon', 'Lisbon', 'PT', 'Portugal', 'example.com'],
            [1, '192.0.2.1', $yesterday . ' 15:00:00', 'Firefox', 'mobile', 'ios', 'Porto', 'Porto', 'PT', 'Portugal', ''],
            [1, '192.0.2.2', $today, 'Chrome', 'desktop', 'windows', null, null, 'US', 'United States', null],
            [1, null, $today, 'unknown', 'unknown', null, null, null, null, null, ''],
            [1, '', $today, 'unknown', null, 'unknown', '', '', '', '', ''],
            [1, '192.0.2.3', $old, 'Safari', 'desktop', 'macos', 'Lisbon', 'Lisbon', 'PT', 'Portugal', 'archive.example'],
            [1, '192.0.2.4', $future, 'Chrome', 'desktop', 'windows', null, null, null, null, ''],
            [2, '192.0.2.5', $today, 'Chrome', 'desktop', 'windows', null, null, null, null, ''],
            [4, '192.0.2.6', $old, 'Chrome', 'desktop', 'windows', null, null, null, null, null],
            [1, '192.0.2.7', date('Y-m-d', strtotime('-400 days')) . ' 00:00:00', 'Chrome', 'desktop', 'windows', 'Lisbon', 'Lisbon', 'PT', 'Portugal', null],
        ])->execute();
    }

    protected function tearDown(): void
    {
        Yii::$app->db->close();
        Yii::$app = null;
        parent::tearDown();
    }

    public function testPeriodCountsAndDistinctIps()
    {
        $data = (new LinkStatistics())->summarize(Link::findOne(1), 7);
        $this->assertSame(5, $data['visits']);
        $this->assertSame(2, $data['uniqueIps']);
        $this->assertSame(date('Y-m-d') . ' 00:00:00', $data['lastVisit']);
        $this->assertCount(7, $data['daily']);
        $this->assertSame(0, $data['daily'][0]['count']);
        $this->assertSame(3, $data['daily'][6]['count']);
        $this->assertSame(5, array_sum(array_column($data['daily'], 'count')));
        $this->assertCount(24, $data['hours']);
        $this->assertSame(3, $data['hours'][0]['count']);
        $this->assertSame(1, $data['hours'][10]['count']);
        $this->assertSame(1, $data['hours'][15]['count']);
        $this->assertCount(7, $data['weekdays']);
        $this->assertSame(5, array_sum(array_column($data['weekdays'], 'count')));
    }

    public function testDistributionsAndMissingHistoricalFields()
    {
        $data = (new LinkStatistics())->summarize(Link::findOne(1), 7);
        $devices = array_column($data['devices'], 'count', 'name');
        $this->assertSame(2, $devices['Telemóvel']);
        $this->assertSame(2, $devices['Desconhecido']);
        $this->assertSame(1, $devices['Computador']);
        $systems = array_column($data['systems'], 'count', 'name');
        $this->assertSame(2, $systems['Desconhecido']);
        $this->assertSame(1, $systems['Android']);
        $countries = array_column($data['countries'], 'value', 'id');
        $this->assertSame(['PT' => 2, 'US' => 1], $countries);
        $sources = array_column($data['sources'], 'count', 'name');
        $this->assertSame(3, $sources['Direto / desconhecido']);
        $this->assertSame(1, $sources['Não recolhida']);
        $this->assertSame(1, $sources['example.com']);
        $cities = array_column($data['cities'], 'count', 'name');
        $this->assertSame(1, $cities['Lisbon / Portugal']);
        $this->assertSame(1, $cities['Desconhecido / United States']);
    }

    public function testLongerPeriodsAndInvalidPeriodFallback()
    {
        $stats = new LinkStatistics();
        $this->assertSame(6, $stats->summarize(Link::findOne(1), 30)['visits']);
        $this->assertCount(90, $stats->summarize(Link::findOne(1), 90)['daily']);
        $this->assertSame('all', $stats->summarize(Link::findOne(1), -1)['days']);
    }

    public function testFullHistoryIsDefaultAndIncludesVisitsOlderThanNinetyDays()
    {
        $data = (new LinkStatistics())->summarize(Link::findOne(1));
        $this->assertSame('all', $data['days']);
        $this->assertSame(7, $data['visits']);
        $this->assertSame(4, $data['uniqueIps']);
        $this->assertSame(date('Y-m-d', strtotime('-400 days')), $data['start']);
        $this->assertCount(401, $data['daily']);
        $this->assertSame(1, $data['daily'][0]['count']);
        $this->assertSame(7, array_sum(array_column($data['daily'], 'count')));
        $this->assertSame(7, array_sum(array_column($data['hours'], 'count')));
        $this->assertSame(7, array_sum(array_column($data['weekdays'], 'count')));
        $this->assertSame(4, array_column($data['countries'], 'value', 'id')['PT']);
        $link = Link::findOne(1);
        $link->short = 'demo';
        $html = (new \yii\web\View())->renderFile(Yii::$app->basePath . '/views/link/_partials/analytics.php', [
            'statistics' => $data, 'model' => $link,
        ]);
        $this->assertStringContainsString('value="all" selected', $html);
    }

    public function testEmptyFullHistoryHasZeroCountsAndOneDailyPoint()
    {
        $data = (new LinkStatistics())->summarize(Link::findOne(3), 'all');
        $this->assertSame(0, $data['visits']);
        $this->assertSame([], $data['browsers']);
        $this->assertCount(1, $data['daily']);
        $this->assertSame(0, $data['daily'][0]['count']);
    }

    public function testEmptyLinkHasCompleteZeroSeries()
    {
        $data = (new LinkStatistics())->summarize(Link::findOne(3), 7);
        $this->assertSame(0, $data['visits']);
        $this->assertSame(0, $data['uniqueIps']);
        $this->assertNull($data['lastVisit']);
        $this->assertSame([], $data['browsers']);
        $this->assertSame([], $data['countries']);
        $this->assertSame([], $data['sources']);
        $this->assertSame(0, array_sum(array_column($data['daily'], 'count')));
    }

    public function testLastVisitIncludesHistoryOutsideSelectedPeriod()
    {
        $data = (new LinkStatistics())->summarize(Link::findOne(4), 7);
        $this->assertSame(0, $data['visits']);
        $this->assertSame(date('Y-m-d', strtotime('-8 days')) . ' 00:00:00', $data['lastVisit']);
    }

    public function testViewEscapesDatabaseLabelsAndRendersSelectedPeriod()
    {
        Yii::$app->db->createCommand()->update('link_stats', ['referrer' => '<script>alert(1)</script>'], ['id' => 1])->execute();
        $link = Link::findOne(1);
        $link->short = 'demo';
        $statistics = (new LinkStatistics())->summarize($link, 7);
        $view = new \yii\web\View();
        $html = $view->renderFile(Yii::$app->basePath . '/views/link/_partials/analytics.php', [
            'statistics' => $statistics, 'model' => $link,
        ]);
        $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('value="7" selected', $html);
        $this->assertStringContainsString('id="daily-chart"', $html);
        $countryHtml = $view->renderFile(Yii::$app->basePath . '/views/link/_partials/byCountry.php', [
            'data' => [['id' => 'PT', 'name' => '</script><script>alert(1)</script>', 'value' => 1]],
        ]);
        $this->assertStringNotContainsString('</script><script>alert(1)</script>', $countryHtml);
        $this->assertStringContainsString('\\u003C', $countryHtml);
    }
}
