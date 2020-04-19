<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class AccountForm extends Model
{

    public $user;
    public $name;
    public $username;
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'safe'],
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'validateUsername'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'validateEmail'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Nome'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
        ];
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Updates user.
     *
     * @return true|false
     */
    public function save()
    {
        if ($this->validate()) {
            $this->user->loadDefaultValues();

            $this->user->attributes = $this->attributes;
            if ($this->user->save()) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function validateUsername($attribute)
    {
        $user = User::findByUsername($this->username);
        if ($this->user->id === $user->id) {
            return true;
        }

        $this->addError($attribute, Yii::t('app', 'Username indisponível.'));
        return true;
    }

    public function validateEmail($attribute)
    {
        $user = User::findByEmail($this->email);
        if ($this->user->id === $user->id) {
            return true;
        }

        $this->addError($attribute, Yii::t('app', 'Email indisponível.'));
        return false;
    }

}
