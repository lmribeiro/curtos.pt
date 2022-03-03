<?php

namespace app\controllers;

use app\components\Stats;
use app\models\{AccountForm,
    Link,
    LinkSearch,
    LoginForm,
    PasswordReset,
    PasswordResetByEmail,
    PasswordResetByUsername,
    SetPasswordForm,
    SignupForm,
    SignupCompleteForm,
    User
};
use Yii;
use yii\filters\{AccessControl, VerbFilter};
use yii\web\{Controller, Response};

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'about', 'api-v1', 'cli', 'login', 'signup', 'reset-password', 'set-password', 'create-account', 'error', 'terms', 'short', 'theme'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'about', 'api-v1', 'cli', 'signup', 'login', 'logout', 'links', 'error', 'account', 'delete-account', 'terms', 'short', 'renew-api-key', 'theme'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get', 'post'],
                    'register' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if ($action->id === "theme") {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * About
     *
     * @return Response|string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * User account
     *
     * @return Response|string
     */
    public function actionAccount()
    {
        $password = new SetPasswordForm();
        $password->setUser(Yii::$app->user->identity);
        $account = new AccountForm();
        $account->setUser(Yii::$app->user->identity);
        $account->attributes = Yii::$app->user->identity->attributes;

        $post = Yii::$app->request->post();

        if (isset($post['AccountForm'])) {
            $account->attributes = $post['AccountForm'];
            $account->save();
            Yii::$app->getSession()->setFlash(
                'success', Yii::t('app', 'Dados pessoais atualizados com sucesso.')
            );
            return $this->redirect(['account']);
        }
        if (isset($post['SetPasswordForm'])) {
            $password->attributes = $post['SetPasswordForm'];
            $password->resetPassword();
            Yii::$app->getSession()->setFlash(
                'success', Yii::t('app', 'Password alterada com sucesso.')
            );
            return $this->redirect(['account']);
        }

        return $this->render('account', [
            'account' => $account,
            'password' => $password
        ]);
    }

    /**
     * Delete user account
     *
     * @return Response|string
     */
    public function actionAccountDelete()
    {
        Yii::$app->user->identity->delete();
        Yii::$app->getSession()
            ->setFlash('success', Yii::t('app', 'Conta apagada com sucesso.'));
        return $this->actionLogout();
    }

    /**
     * Logout
     *
     * @return Response|string
     */
    public function actionLogout()
    {
        $theme = Yii::$app->session->get('theme') ? 'night' : '';
        Yii::$app->user->logout();

        Yii::$app->session->set('theme', $theme);
        return $this->goHome();
    }

    /**
     * API
     *
     * @return Response|string
     */
    public function actionApiV1()
    {
        return $this->render('api');
    }

    /**
     * CLI
     *
     * @return Response|string
     */
    public function actionCli()
    {
        return $this->render('cli');
    }

    /**
     * Site index
     *
     * @return yii\web\View
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Get user's links
     *
     * @return Response|string
     */
    public function actionLinks()
    {
        $searchModel = new LinkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 10;

        return $this->render('links', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * renew API access key
     *
     * @return Response|string
     */
    public function actionRenewApiKey()
    {
        $user = Yii::$app->user->identity;
        $user->generateAuthKey();
        $user->save();
        Yii::$app->getSession()
            ->setFlash('success', Yii::t('app', 'Chave regenerada com sucesso.'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Reset Password
     *
     * @return Response|string
     */
    public function actionResetPassword()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new PasswordReset();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (filter_var($model->emailusername, FILTER_VALIDATE_EMAIL)) {
                $byEmail = new PasswordResetByEmail();
                $byEmail->email = $model->emailusername;

                if ($byEmail->validate() && $byEmail->sendEmail()) {
                    Yii::$app->session
                        ->setFlash('success', Yii::t('app', 'Verifique o seu email para concluir o processo.'));

                    return $this->redirect(['login']);
                }
                $model->addError('emailusername', $byEmail->errors['email'][0]);
            } else {
                $byUsername = new PasswordResetByUsername();
                $byUsername->username = $model->emailusername;

                if ($byUsername->validate() && $byUsername->sendEmail()) {
                    Yii::$app->session
                        ->setFlash('success', Yii::t('app', 'Verifique o seu email para concluir o processo.'));

                    return $this->redirect(['login']);
                }
                $model->addError('emailusername', $byUsername->errors['username'][0]);
            }
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }

    /**
     * Set Password
     *
     * @param string $token reset token
     * @return Response|string
     */
    public function actionSetPassword($token = null)
    {
        $model = new SetPasswordForm();

        if (empty($token) || !is_string($token)) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Pedido inválido/expirado. Faça novo pedido.'));
            return $this->redirect(['login']);
        }

        if (!$user = User::findByPasswordResetToken($token)) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Pedido inválido/expirado. Faça novo pedido.'));
            return $this->redirect(['login']);
        }

        $model->setUser($user);

        if (
            $model->load(Yii::$app->request->post()) &&
            $model->validate() &&
            $model->resetPassword()
        ) {
            Yii::$app->session->setFlash(
                'success', Yii::t('app', 'Password alterada com sucesso')
            );

            return $this->redirect(['login']);
        }

        return $this->render('set-password', [
            'model' => $model,
        ]);
    }

    /**
     * Redirect user to target link
     *
     * @param string $id the short link code
     * @return Response|string
     */
    public function actionShort(string $id)
    {
        $link = Link::find()->where(['short' => $id])->one();
        $link->visit_count++;
        $link->save();

        // Add stats
        $stats = new Stats();
        $stats->create($link);

        return $this->redirect($link->target);
    }

    /**
     * Signup
     *
     * @return Response|string
     */
    public function actionSignup()
    {
        return $this->redirect(['/']);

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->signup()) {
                Yii::$app->getSession()
                    ->setFlash('success', Yii::t('app', 'Verifique o seu email para concluir o processo.'));

                return $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Site terms of use
     *
     * @return Response|string
     */
    public function actionTerms()
    {
        return $this->render('terms');
    }

    /**
     * Set/remove night mode
     *
     * @return boolean true
     */
    public function actionTheme()
    {
        $theme = Yii::$app->request->post('theme', false) == 'true' ? true : false;
        Yii::$app->session->set('theme', $theme);
        return true;
    }

    /**
     * Verify Account
     *
     * @param string $key user auth key
     * @return Response|string
     */
    public function actionCreateAccount($email)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupCompleteForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->signup()) {
                Yii::$app->getSession()
                    ->setFlash('success', Yii::t('app', 'Registo criado com sucesso.'));
                return $this->goHome();
            }
        }

        return $this->render('create-account', [
            'model' => $model,
            'email' => $email
        ]);
    }

}
