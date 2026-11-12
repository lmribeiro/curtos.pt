<?php

namespace tests\unit\components;

use app\components\Stats;
use app\models\Link;
use app\models\LinkStats;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\DynamicModel;
use yii\db\Connection;
use yii\web\Application;

class StatsTest extends TestCase
{
    private $server;

    protected function setUp(): void
    {
        $this->server = $_SERVER;
        $_SERVER['REMOTE_ADDR'] = '8.8.8.8';
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 Chrome/100.0.0.0';

        new Application([
            'id' => 'stats-test',
            'basePath' => dirname(__DIR__, 3),
            'components' => [
                'db' => ['class' => Connection::class, 'dsn' => 'sqlite::memory:'],
                'cache' => ['class' => \yii\caching\ArrayCache::class],
                'request' => ['cookieValidationKey' => 'test-only'],
                'userAgent' => new DynamicModel([
                    'os' => 'linux', 'platform' => 'desktop', 'browserVersion' => '100.0',
                ]),
            ],
        ]);

        Yii::$app->db->createCommand()->createTable('link', ['id' => 'pk'])->execute();
        Yii::$app->db->createCommand()->insert('link', ['id' => 1])->execute();
        $columns = ['id' => 'pk', 'link_id' => 'integer'];
        foreach (['ip', 'os', 'platform', 'browser', 'browserVersion', 'city', 'region',
            'country_code', 'country_name', 'lat', 'lng'] as $column) {
            $columns[$column] = 'string';
        }
        $columns['created_at'] = 'timestamp DEFAULT CURRENT_TIMESTAMP';
        $columns['referrer'] = 'string';
        Yii::$app->db->createCommand()->createTable('link_stats', $columns)->execute();

        GeoStream::$response = false;
        GeoStream::$requests = 0;
        stream_wrapper_unregister('https');
        stream_wrapper_register('https', GeoStream::class);
    }

    protected function tearDown(): void
    {
        stream_wrapper_restore('https');
        $_SERVER = $this->server;
        Yii::$app->db->close();
        Yii::$app = null;
        parent::tearDown();
    }

    /** @dataProvider unavailableGeoProvider */
    public function testVisitIsSavedWithoutGeolocation($response)
    {
        GeoStream::$response = $response;

        $this->assertTrue((new Stats())->create(Link::findOne(1)));

        $stats = LinkStats::find()->one();
        $this->assertNotNull($stats);
        $this->assertSame('Chrome', $stats->browser);
        $this->assertSame('', $stats->country_code);
        $this->assertSame([], Link::findOne(1)->getDataByCountry());
        $this->assertSame(1, GeoStream::$requests);
        $this->assertSame(2, GeoStream::$options['http']['timeout']);
        $this->assertSame(0, GeoStream::$options['http']['follow_location']);
        $this->assertSame('https://ipwho.is/8.8.8.8', GeoStream::$url);
        $this->assertTrue((new Stats())->create(Link::findOne(1)));
        $this->assertSame(1, GeoStream::$requests);
    }

    public function unavailableGeoProvider()
    {
        return [
            'HTTP failure' => [false],
            'invalid payload' => ['<html>Forbidden</html>'],
            'unexpected scalar' => [json_encode('invalid')],
            'unsuccessful lookup' => [json_encode(['success' => false, 'message' => 'Reserved range'])],
            'rate limit' => [json_encode(['success' => false, 'message' => 'Rate limit exceeded'])],
            'invalid field type' => [json_encode(['success' => true, 'city' => ['invalid']])],
            'oversized field' => [json_encode(['success' => true, 'country_code' => 'invalid'])],
        ];
    }

    public function testVisitIsSavedWithGeolocation()
    {
        GeoStream::$response = json_encode([
            'success' => true,
            'country_code' => 'PT',
            'country' => 'Portugal',
            'city' => 'Lisbon',
            'region' => 'Lisbon',
            'latitude' => 38.72,
            'longitude' => -9.14,
        ]);

        $this->assertTrue((new Stats())->create(Link::findOne(1)));
        $stats = LinkStats::find()->one();
        $this->assertSame('PT', $stats->country_code);
        $this->assertSame('Lisbon', $stats->city);
        $this->assertSame('Lisbon', $stats->region);
        $this->assertSame('38.72', $stats->lat);
        $this->assertSame('-9.14', $stats->lng);
        $this->assertTrue((new Stats())->create(Link::findOne(1)));
        $this->assertSame(1, GeoStream::$requests);
        $this->assertSame(['PT' => ['name' => 'Portugal', 'count' => 2]], Link::findOne(1)->getDataByCountry());
    }

    /** @dataProvider privateIpProvider */
    public function testPrivateAddressesDoNotRequireGeolocation($ip)
    {
        $_SERVER['REMOTE_ADDR'] = $ip;

        $this->assertTrue((new Stats())->create(Link::findOne(1)));
        $this->assertSame(0, GeoStream::$requests);
        $this->assertSame(1, (int) LinkStats::find()->count());
    }

    public function privateIpProvider()
    {
        return [['127.0.0.1'], ['::1'], ['192.168.1.10']];
    }

    public function testSaveFailureIsReported()
    {
        GeoStream::$response = json_encode(['success' => false]);
        $link = new Link();
        $link->id = 999;

        $this->assertFalse((new Stats())->create($link));
        $this->assertSame(0, (int) LinkStats::find()->count());
    }

    /** @dataProvider referrerProvider */
    public function testOnlyReferrerDomainIsStored($referrer, $expected)
    {
        $_SERVER['HTTP_REFERER'] = $referrer;
        $this->assertTrue((new Stats())->create(Link::findOne(1)));
        $this->assertSame($expected, LinkStats::find()->one()->referrer);
    }

    public function referrerProvider()
    {
        return [
            ['https://WWW.Example.com/path?token=private#fragment', 'www.example.com'],
            ['https://user:password@example.com/private', 'example.com'],
            ['javascript:alert(1)', ''],
            ['https://invalid host/path', ''],
            ['', ''],
        ];
    }

    /** @dataProvider tabletProvider */
    public function testTabletDetection($agent, $expected)
    {
        $_SERVER['HTTP_USER_AGENT'] = $agent;
        Yii::$app->userAgent->platform = 'mobile';
        $this->assertTrue((new Stats())->create(Link::findOne(1)));
        $this->assertSame($expected, LinkStats::find()->one()->platform);
    }

    public function tabletProvider()
    {
        return [
            ['Mozilla/5.0 (iPad; CPU OS 15_0 like Mac OS X)', 'tablet'],
            ['Mozilla/5.0 (Linux; Android 12) Chrome/100.0 Safari/537.36', 'tablet'],
            ['Mozilla/5.0 (Linux; Android 12) Chrome/100.0 Mobile Safari/537.36', 'mobile'],
        ];
    }
}

class GeoStream
{
    public $context;
    public static $response;
    public static $requests;
    public static $options;
    public static $url;
    private $offset = 0;

    public function stream_open($path, $mode, $options, &$openedPath)
    {
        self::$requests++;
        self::$url = $path;
        self::$options = stream_context_get_options($this->context);
        return self::$response !== false;
    }

    public function stream_read($count)
    {
        $chunk = substr(self::$response, $this->offset, $count);
        $this->offset += strlen($chunk);
        return $chunk;
    }

    public function stream_eof()
    {
        return $this->offset >= strlen(self::$response);
    }

    public function stream_stat()
    {
        return [];
    }
}
