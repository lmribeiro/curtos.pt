<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "link_stats".
 *
 * @property int $id
 * @property int|null $link_id
 * @property string|null $ip
 * @property string|null $os
 * @property string|null $platform
 * @property string|null $browser
 * @property string|null $browserVersion
 * @property string|null $city
 * @property string|null $region
 * @property string|null $country_code
 * @property string|null $country_name
 * @property string|null $lat
 * @property string|null $lng
 * @property string $created_at
 *
 * @property Link $link
 */
class LinkStats extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'link_stats';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['link_id'], 'integer'],
            [['created_at'], 'safe'],
            [['ip', 'os', 'platform', 'browser', 'browserVersion', 'lat', 'lng'], 'string', 'max' => 32],
            [['city', 'region', 'country_name'], 'string', 'max' => 255],
            [['country_code'], 'string', 'max' => 2],
            [['link_id'], 'exist', 'skipOnError' => true, 'targetClass' => Link::className(), 'targetAttribute' => ['link_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'link_id' => Yii::t('app', 'Link ID'),
            'ip' => Yii::t('app', 'Ip'),
            'os' => Yii::t('app', 'Os'),
            'platform' => Yii::t('app', 'Platform'),
            'browser' => Yii::t('app', 'Browser'),
            'browserVersion' => Yii::t('app', 'Browser Version'),
            'city' => Yii::t('app', 'City'),
            'region' => Yii::t('app', 'Region'),
            'country_code' => Yii::t('app', 'Country Code'),
            'country_name' => Yii::t('app', 'Country Name'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Link]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Link::className(), ['id' => 'link_id']);
    }
}
