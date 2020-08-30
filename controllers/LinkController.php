<?php

namespace app\controllers;

use app\components\Shorter;
use app\models\{Link, LinkSearch};
use Yii;
use yii\filters\{AccessControl, VerbFilter};
use yii\base\Action;
use yii\helpers\Url;
use yii\web\{BadRequestHttpException, Controller, NotFoundHttpException, Response};

/**
 * Class LinkController
 * @package app\controllers
 */
class LinkController extends Controller
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
                        'actions' => ['short'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'renew', 'short'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                    'delete' => ['get', 'post'],
                    'update' => ['post'],
                    'renew' => ['get', 'post'],
                    'short' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        try {
            return parent::beforeAction($action);
        } catch (BadRequestHttpException $e) {
            Yii::error($e->getMessage());
        }
    }

    /**
     * Short
     * Entering point for the short link
     *
     * @return string
     */
    public function actionShort()
    {
        $user = false;
        $request = Yii::$app->request;
        $shorter = new Shorter();

        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
        }

        $link = $shorter->getShortLink($request->post('target'), $user);
        return Url::base(true) . "/" . $link->short;
    }

    /**
     * Lists all Link models
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LinkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Link model.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Link model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Link the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Link::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new Link model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Link();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Link model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Link model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash(
            'success', Yii::t('app', 'Apagado com sucesso.')
        );
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Renew Link's expiration date
     *
     * @param int $id Link's ID
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionRenew($id)
    {
        $model = $this->findModel($id);
        $model->expires_after = date('Y-m-d H:i:s', strtotime($model->expires_after . " + 30 days"));
        $model->save();
        Yii::$app->getSession()->setFlash(
            'success', Yii::t('app', 'Data de expiração renovada com sucesso.')
        );
        return $this->redirect(Yii::$app->request->referrer);
    }

}
