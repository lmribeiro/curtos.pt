<?php

namespace tests\unit\api;

use app\modules\api\Module;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\ExitException;
use yii\db\Connection;
use yii\web\Application;
use yii\web\MethodNotAllowedHttpException;

class V1ControllerTest extends TestCase
{
    private $server;

    protected function setUp(): void
    {
        $this->server = $_SERVER;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        unset($_SERVER['HTTP_ORIGIN'], $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'], $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
        new ApiTestApplication([
            'id' => 'api-test',
            'basePath' => dirname(__DIR__, 3),
            'timeZone' => 'Europe/Lisbon',
            'modules' => ['api' => ['class' => Module::class]],
            'params' => ['expiresAfter' => 30],
            'components' => [
                'db' => ['class' => Connection::class, 'dsn' => 'sqlite::memory:'],
                'user' => ['identityClass' => \app\models\User::class, 'enableSession' => false],
                'request' => [
                    'cookieValidationKey' => 'test-only',
                    'scriptFile' => dirname(__DIR__, 3) . '/web/index.php',
                    'scriptUrl' => '/index.php',
                    'hostInfo' => 'http://localhost',
                    'baseUrl' => '',
                ],
            ],
        ]);
        Yii::$app->request->headers->set('Authorization', 'test-api-key');
        Yii::$app->request->headers->set('Accept', 'application/json');
        Yii::$app->db->createCommand('PRAGMA foreign_keys = ON')->execute();
        Yii::$app->db->createCommand()->createTable('user', [
            'id' => 'pk', 'auth_key' => 'string', 'status' => 'integer',
        ])->execute();
        Yii::$app->db->createCommand()->insert('user', ['id' => 1, 'auth_key' => 'test-api-key', 'status' => 1])->execute();
        Yii::$app->db->createCommand()->createTable('link', [
            'id' => 'pk', 'user_id' => 'integer', 'target' => 'string', 'short' => 'string',
            'expires_after' => 'string', 'visit_count' => 'integer DEFAULT 0',
            'created_at' => 'timestamp DEFAULT CURRENT_TIMESTAMP', 'updated_at' => 'string',
        ])->execute();
        Yii::$app->db->createCommand('CREATE TABLE link_stats (
            id INTEGER PRIMARY KEY, link_id INTEGER, browser TEXT, country_code TEXT, country_name TEXT,
            FOREIGN KEY(link_id) REFERENCES link(id) ON DELETE CASCADE
        )')->execute();
    }

    protected function tearDown(): void
    {
        Yii::$app->db->close();
        Yii::$app = null;
        $_SERVER = $this->server;
        parent::tearDown();
    }

    private function request($action, $body = '', $method = 'POST', array $query = [])
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        Yii::$app->request->setRawBody($body);
        Yii::$app->request->setQueryParams($query);
        Yii::$app->response->content = null;
        try {
            Yii::$app->runAction('api/v1/' . $action);
        } catch (ExitException $e) {
        }
        return json_decode(Yii::$app->response->content, true);
    }

    /** @dataProvider invalidCreateProvider */
    public function testInvalidCreationReturnsLegacyErrorEnvelopeWithoutWriting($body)
    {
        $response = $this->request('create', $body);
        $this->assertSame(200, Yii::$app->response->statusCode);
        $this->assertSame(400, $response['code']);
        $this->assertSame('Error', $response['status']);
        $this->assertSame([], $response['data']);
        $this->assertSame(0, (int) Yii::$app->db->createCommand('SELECT COUNT(*) FROM link')->queryScalar());
    }

    public function invalidCreateProvider()
    {
        $cases = [[''], ['{'], ['null'], ['[]'], ['[{}]'], ['"text"'], ['true'], ['{}'], ["{\"target\":\"\xff\"}"]];
        foreach ([null, [], (object) [], true, 123, '', 'https://', 'http://example.com/',
            'http://example.com/?https://other.example', 'https://invalid host/', 'https://example.com/' . str_repeat('a', 256)] as $target) {
            $cases[] = [json_encode(['target' => $target])];
        }
        foreach ([null, 0, -1, true, '60', 1.5, [], PHP_INT_MAX] as $days) {
            $cases[] = [json_encode(['target' => 'https://example.com/', 'expires_after' => $days])];
        }
        return $cases;
    }

    public function testCreationKeepsResponseAndDefaultExpiration()
    {
        $response = $this->request('create', '{"target":"https://example.com/"}');
        $this->assertSame(200, Yii::$app->response->statusCode);
        $this->assertSame(201, $response['code']);
        $this->assertSame('OK', $response['status']);
        $this->assertSame('https://example.com/', $response['data']['target']);
        $this->assertSame(1, preg_match('/^[a-f0-9]{6}$/', $response['data']['code']));
        $this->assertSame(date('Y-m-d', strtotime('+30 days')), substr($response['data']['expires_after'], 0, 10));
    }

    public function testExplicitExpirationIsAccepted()
    {
        $response = $this->request('create', '{"target":"https://example.com/?q=1#part","expires_after":60}');
        $this->assertSame(201, $response['code']);
        $this->assertSame(date('Y-m-d', strtotime('+60 days')), substr($response['data']['expires_after'], 0, 10));
    }

    /** @dataProvider invalidCodeProvider */
    public function testInvalidCodesAreRejectedForStatsAndDeletion($code)
    {
        $this->assertSame(400, $this->request('delete', json_encode(['code' => $code]), 'DELETE')['code']);
        $this->assertSame(400, $this->request('stats', '', 'GET', ['code' => $code])['code']);
    }

    public function invalidCodeProvider()
    {
        return [[null], [[]], [(object) []], [''], [' '], ['a/b'], ['https://example.com/a1b2c3'], [str_repeat('a', 256)]];
    }

    public function testNumericJsonCodeIsRejected()
    {
        $this->assertSame(400, $this->request('delete', '{"code":123}', 'DELETE')['code']);
    }

    public function testMissingStatsCodeIsRejected()
    {
        $this->assertSame(400, $this->request('stats', '', 'GET')['code']);
    }

    public function testValidUnknownCodesKeepNotFoundResponse()
    {
        $this->assertSame(404, $this->request('stats', '', 'GET', ['code' => 'a1b2c3'])['code']);
        $this->assertSame(404, $this->request('delete', '{"code":"a1b2c3"}', 'DELETE')['code']);
    }

    public function testStatsAndDeletionKeepTheirResponseContracts()
    {
        $created = $this->request('create', '{"target":"https://example.com/"}');
        $code = $created['data']['code'];
        $linkId = Yii::$app->db->createCommand('SELECT id FROM link WHERE short = :code', [':code' => $code])->queryScalar();
        Yii::$app->db->createCommand()->insert('link_stats', [
            'link_id' => $linkId, 'browser' => 'Chrome', 'country_code' => 'PT', 'country_name' => 'Portugal',
        ])->execute();
        $stats = $this->request('stats', '', 'GET', ['code' => $code]);
        $this->assertSame(200, $stats['code']);
        $this->assertSame(['code', 'target', 'short', 'expires_after', 'visits', 'byBrowser', 'byCountry'], array_keys($stats['data']));
        $deleted = $this->request('delete', json_encode(['code' => $code]), 'DELETE');
        $this->assertSame(200, $deleted['code']);
        $this->assertSame([], $deleted['data']);
        $this->assertSame(0, (int) Yii::$app->db->createCommand('SELECT COUNT(*) FROM link')->queryScalar());
        $this->assertSame(0, (int) Yii::$app->db->createCommand('SELECT COUNT(*) FROM link_stats')->queryScalar());
    }

    /** @dataProvider preflightProvider */
    public function testPreflightBypassesAuthenticationAndNegotiation($action, $method)
    {
        $_SERVER['HTTP_ORIGIN'] = 'https://client.example';
        $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] = $method;
        $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'] = 'authorization, content-type';
        Yii::$app->request->headers->set('Access-Control-Request-Method', $method);
        Yii::$app->request->headers->remove('Authorization');
        Yii::$app->request->headers->set('Accept', 'application/not-supported');
        $this->assertNull($this->request($action, '', 'OPTIONS'));
        $headers = Yii::$app->response->headers;
        $this->assertSame(200, Yii::$app->response->statusCode);
        $this->assertSame('*', $headers->get('Access-Control-Allow-Origin'));
        $this->assertContains($method, explode(', ', $headers->get('Access-Control-Allow-Methods')));
        $this->assertStringContainsString('authorization', strtolower($headers->get('Access-Control-Allow-Headers')));
        $this->assertStringContainsString('content-type', strtolower($headers->get('Access-Control-Allow-Headers')));
        $this->assertNull($headers->get('Access-Control-Allow-Credentials'));
        $this->assertSame(0, (int) Yii::$app->db->createCommand('SELECT COUNT(*) FROM link')->queryScalar());
    }

    public function preflightProvider()
    {
        return [['create', 'POST'], ['delete', 'DELETE'], ['stats', 'GET']];
    }

    public function testPlainOptionsDoesNotRequireAuthentication()
    {
        Yii::$app->request->headers->remove('Authorization');
        $this->assertNull($this->request('delete', '', 'OPTIONS'));
        $this->assertSame(200, Yii::$app->response->statusCode);
    }

    public function testUnsupportedPreflightMethodAndHeaderAreNotAdvertised()
    {
        $_SERVER['HTTP_ORIGIN'] = 'https://client.example';
        $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] = 'PUT';
        $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'] = 'authorization, x-unapproved-header';
        Yii::$app->request->headers->set('Access-Control-Request-Method', 'PUT');
        Yii::$app->request->headers->remove('Authorization');
        $this->request('delete', '', 'OPTIONS');
        $headers = Yii::$app->response->headers;
        $this->assertSame('DELETE, OPTIONS', $headers->get('Access-Control-Allow-Methods'));
        $this->assertStringNotContainsString('x-unapproved-header', strtolower($headers->get('Access-Control-Allow-Headers')));
    }

    public function testUnauthenticatedRequestKeepsErrorAndCorsHeaders()
    {
        $_SERVER['HTTP_ORIGIN'] = 'https://client.example';
        Yii::$app->request->headers->remove('Authorization');
        $response = $this->request('create', '{}');
        $this->assertSame(401, $response['code']);
        $this->assertSame(200, Yii::$app->response->statusCode);
        $this->assertSame('*', Yii::$app->response->headers->get('Access-Control-Allow-Origin'));
    }

    public function testUnsupportedVerbIsStillRejectedWithCorsHeaders()
    {
        $_SERVER['HTTP_ORIGIN'] = 'https://client.example';
        try {
            $this->request('create', '', 'GET');
            $this->fail('GET must not create links.');
        } catch (MethodNotAllowedHttpException $e) {
            $this->assertSame(405, $e->statusCode);
            $this->assertSame('*', Yii::$app->response->headers->get('Access-Control-Allow-Origin'));
        }
    }
}

class ApiTestApplication extends Application
{
    public function end($status = 0, $response = null)
    {
        throw new ExitException('', $status);
    }
}
