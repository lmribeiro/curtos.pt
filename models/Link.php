<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "link".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $target
 * @property string|null $short
 * @property int $visit_count
 * @property string|null $expires_after
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property User $user
 * @property LinkStats[] $linkStats
 */
class Link extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'visit_count'], 'integer'],
            [['target'], 'required'],
            [['expires_after', 'created_at', 'updated_at'], 'safe'],
            [['target', 'short'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'Utilizador'),
            'target' => Yii::t('app', 'Target'),
            'short' => Yii::t('app', 'Curto'),
            'visit_count' => Yii::t('app', 'Visitas'),
            'expires_after' => Yii::t('app', 'Expira em'),
            'created_at' => Yii::t('app', 'Criado em'),
            'updated_at' => Yii::t('app', 'Atualizado em'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return LinkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LinkQuery(get_called_class());
    }

    /**
     * Gets query for [[LinkStats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinkStats()
    {
        return $this->hasMany(LinkStats::className(), ['link_id' => 'id']);
    }

    public function getDataByBrowser()
    {
        $browsers = [];
        $browsers['Boots'] = 0;

        foreach ($this->linkStats as $stat) {
            if (in_array($stat->browser, ['Chrome', 'Edge', 'Firefox', 'Internet Explorer', 'Opera', 'Safari'])) {
                if (!isset($browsers[$stat->browser])) {
                    $browsers[$stat->browser] = 0;
                }
                $browsers[$stat->browser]++;
            } else {
                $browsers['Boots']++;
            }
        }
        arsort($browsers);
        return $browsers;
    }

    /**
     * Get data by country
     * @return array
     */
    public function getDataByCountry()
    {
        $countries = [];

        foreach ($this->linkStats as $stat) {
            if ($stat->country_code !== "") {
                if (!isset($countries[$stat->country_code])) {
                    $countries[$stat->country_code]['name'] = $stat->country_name;
                    $countries[$stat->country_code]['count'] = 0;
                }
                $countries[$stat->country_code]['count']++;
            }
        }
        ksort($countries);
        return $countries;
    }
}
