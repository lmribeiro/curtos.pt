<?php

namespace app\components;

use Yii;
use yii\helpers\Url;
use app\models\Link;

class Shorter
{

    public $expiresAfter = 30;

    /**
     * Constructur
     */
    public function __construct()
    {
        
    }

    /**
     * Get short link for the given target
     * 
     * @param string $target Target link
     * @param int $time Time to the link expire (in days)
     * @return string The short link
     */
    public function getShortLink($target, $time = false)
    {
        if (!$time) {
            $time = $this->expiresAfter;
        }

        $model = new Link();
        $model->short = $this->getSlug();
        $model->target = $target;
        $model->expires_after = date('Y-m-d H:i:s', strtotime("+ ".$time." day"));

        if (!Yii::$app->user->isGuest) {
            $model->user_id = Yii::$app->user->identity->id;
        }

        if ($model->save()) {
            return Url::base(true)."/".$model->short;
        }
    }

    /**
     * Generate an unique slug
     * 
     * @return string An unique short slug
     */
    private function getSlug()
    {
        $link = substr(md5(uniqid(mt_rand(), true)), -6);
        while (Link::find()->where(['short' => $link])->exists()) {
            $link = substr(md5(uniqid(mt_rand(), true)), -6);
        }

        return $link;
    }

}
