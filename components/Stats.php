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
        $stats->browser = $browser->getBrowserName(Yii::$app->getRequest()->getUserAgent());
        $stats->browserVersion = Yii::$app->userAgent->browserVersion;

        if ($geo = $this->getGeo($stats->ip)) {
            $stats->city = isset($geo["geoplugin_city"]) ? $geo["geoplugin_city"] : '';
            $stats->region = isset($geo['geoplugin_region']) ? $geo['geoplugin_region'] : '';
            $stats->country_code = isset($geo['geoplugin_countryCode']) ? $geo['geoplugin_countryCode'] : '';
            $stats->country_name = isset($geo['geoplugin_countryName']) ? $geo['geoplugin_countryName'] : '';
            $stats->lat = isset($geo['geoplugin_latitude']) ? $geo['geoplugin_latitude'] : '';
            $stats->lng = isset($geo['geoplugin_longitude']) ? $geo['geoplugin_longitude'] : '';
        }

        $stats->save();
        return true;
    }

    /**
     * @param $ip
     * @return mixed
     */
    protected function getGeo($ip) {
        return unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip"));
    }


}
