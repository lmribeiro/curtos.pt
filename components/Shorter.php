<?php

namespace app\components;

use app\models\Link;
use Yii;

class Shorter
{

    public $expiresAfter;

    /**
     * Constructur
     */
    public function __construct()
    {
        $this->expiresAfter = Yii::$app->params['expiresAfter'];
    }

    /**
     * Get short link for the given target
     *
     * @param string $target Target link
     * @param $user
     * @param bool $time Time to the link expire (in days)
     * @return string The short link
     */
    public function getShortLink(string $target, $user, $time = false)
    {
        if (!$time) {
            $time = $this->expiresAfter;
        }

        $model = new Link();
        $model->short = $this->getSlug();
        $model->target = $target;
        $model->expires_after = date('Y-m-d H:i:s', strtotime("+ " . $time . " day"));

        if ($user) {
            $model->user_id = $user->id;
        }

        if ($model->save()) {
            return $model;
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
