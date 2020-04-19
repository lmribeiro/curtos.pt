<?php

namespace app\models;

use Yii;
use yii\base\Model;

class PasswordResetByUsername extends Model
{

    public $username;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string'],
            [
                'username',
                'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Yii::t('app', 'Utilizador não encontrado.')
            ]
        ];
    }

    /**
     * Sends an email with a link, for reset password.
     */
    public function sendEmail()
    {

        if (!$user = User::findOne([
                    'status' => User::STATUS_ACTIVE,
                    'username' => $this->username
                ])) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app->mailer->compose(['html' => 'ResetPassword',], ['user' => $user])
                        ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                        ->setTo($user->email)
                        ->setSubject(Yii::$app->name." | ".Yii::t('app', 'Recuperar Password'))
                        ->send();
    }

}
