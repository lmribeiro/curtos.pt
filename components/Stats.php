<?php

namespace app\components;

use app\models\Link;
use app\models\LinkStats;
use Yii;

class Stats
{

    /**
     * Create link stats
     *
     * @param Link $link
     */
    public function create(Link $link)
    {
        $browser = new Browser();
        $stats = new LinkStats();

        $stats->link_id = $link->id;
        $stats->ip = Yii::$app->request->userIP;
        $stats->os = Yii::$app->userAgent->os;
        $stats->platform = Yii::$app->userAgent->platform;
        if (preg_match('/ipad|tablet|android(?!.*mobile)/i', Yii::$app->request->userAgent)) {
            $stats->platform = 'tablet';
        }
        $stats->browser = $browser->getBrowserName(Yii::$app->getRequest()->getUserAgent());
        $stats->browserVersion = Yii::$app->userAgent->browserVersion;
        $stats->referrer = '';
        $referrer = Yii::$app->request->referrer;
        if ($referrer && in_array(strtolower((string) parse_url($referrer, PHP_URL_SCHEME)), ['http', 'https'], true)) {
            $host = strtolower(rtrim((string) parse_url($referrer, PHP_URL_HOST), '.'));
            if (strlen($host) <= 253 && filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
                $stats->referrer = $host;
            }
        }

        $geo = $this->getGeo($stats->ip);
        $stats->city = isset($geo['city']) ? $geo['city'] : '';
        $stats->region = isset($geo['region']) ? $geo['region'] : '';
        $stats->country_code = isset($geo['country_code']) ? $geo['country_code'] : '';
        $stats->country_name = isset($geo['country']) ? $geo['country'] : '';
        $stats->lat = isset($geo['latitude']) ? (string) $geo['latitude'] : '';
        $stats->lng = isset($geo['longitude']) ? (string) $geo['longitude'] : '';

        if (!$stats->save()) {
            Yii::warning([
                'message' => 'Unable to save link statistics.',
                'attributes' => array_keys($stats->getErrors()),
            ], __METHOD__);
            return false;
        }

        return true;
    }

    /**
     * @param $ip
     * @return mixed
     */
    protected function getGeo($ip) {
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return false;
        }

        $key = ['ipwho.is', $ip];
        $geo = Yii::$app->cache->get($key);
        if ($geo !== false) {
            return $geo;
        }

        $context = stream_context_create(['http' => ['timeout' => 2, 'follow_location' => 0]]);
        $response = @file_get_contents('https://ipwho.is/' . rawurlencode($ip), false, $context);
        $geo = $response === false ? null : json_decode($response, true);
        if (!is_array($geo) || !isset($geo['success']) || $geo['success'] !== true) {
            $geo = [];
        }

        foreach (['city' => 255, 'region' => 255, 'country_code' => 2, 'country' => 255,
            'latitude' => 32, 'longitude' => 32] as $field => $maxLength) {
            if (isset($geo[$field]) && (!is_scalar($geo[$field]) || mb_strlen((string) $geo[$field], 'UTF-8') > $maxLength)) {
                $geo = [];
                break;
            }
        }

        Yii::$app->cache->set($key, $geo, $geo ? 86400 : 300);
        return $geo;
    }


}
