<?php

namespace app\controllers;

use app\models\Basket;
use app\models\ImageUpload;
use Yii;
use app\models\Team;
use app\models\TeamSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * TeamController implements the CRUD actions for Team model.
 */
class TeamController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Team models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/index', 'message'=>"Вы не зарегистрированы"]);
        }
        $searchModel = new TeamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->user->identity->id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Team model.
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
     * Creates a new Team model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Team();
        if (Yii::$app->request->isPost) {
            $model->user_id = Yii::$app->user->identity->id;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionSetImage($id){
        $model = new ImageUpload;

        if (Yii::$app->request->isPost) {
            $team = Team::findOne($id);
            $file = UploadedFile::getInstance($model, 'image');

            if ($team->saveImage($model->UploadedFile($file, $model->image))) {
                return $this->redirect(['view', 'id'=>$id]);
            }
        }
        return $this->render('image', ['model' =>$model]);
    }

    public function actionSetBasket($id) {
        $team = Team::findOne($id);

        $count = Team::find()->count()/4;

        $selected = $team->basket;

        $baskets = ArrayHelper::map(Basket::find()->all(), 'number', 'number');

        if (Yii::$app->request->isPost) {
            if ($team->saveBasket(Basket::find()->where(['number'=>Yii::$app->request->post('basket')])->one()->id)) {
                return $this->redirect(['view', 'id'=>$id]);
            }
        }

        return $this->render('basket', [
            'selected' =>$selected,
            'baskets' =>$baskets
        ]);




    }

    /**
     * Updates an existing Team model.
     * If update is successful, the browser will be redirected to the 'view' page.
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
     * Deletes an existing Team model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Team model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Team the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Team::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
