<?php

namespace app\controllers;

use Yii;
use app\models\Trip;
use yii\web\Response;
use app\models\TripSearch;
use app\models\Service;
use app\models\ServiceType;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class TripController extends Controller
{
    /**
     * @return array[]
     */
    public function behaviors() : array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'confirm-service' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex() : string
    {
        $searchModel = new TripSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id) : string
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model' => $model,
            'users' => $model->getUsers(),
            'services' => $model->getServices(),
        ]);
    }

    /**
     * @return Response|string
     * @throws \yii\db\Exception
     */
    public function actionCreate() : Response|string
    {
        $model = new Trip();

        if ($model->load(Yii::$app->request->post())) {
            $model->participantIds = Yii::$app->request->post('Trip')['participantIds'] ?? [];
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     *
     * @return Response|string
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id) : Response|string
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->participantIds = Yii::$app->request->post('Trip')['participantIds'] ?? [];
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id) : Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $trip_id
     *
     * @return Response|string
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionAddService($trip_id) : Response|string
    {
        $trip = $this->findModel($trip_id);
        $model = new Service();
        $model->trip_id = $trip_id;
        $serviceTypes = ServiceType::find()->all();
        $users = $trip->users;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                // save services for users
                $selectedUsers = Yii::$app->request->post('users', []);
                foreach ($selectedUsers as $userId) {
                    Yii::$app->db->createCommand()->insert('service_user', [
                        'service_id' => $model->id,
                        'user_id' => $userId,
                    ])->execute();
                }
                
                // save attributes
                $attributes = Yii::$app->request->post('attributes', []);
                foreach ($attributes as $attrId => $value) {
                    if ($value !== '') {
                        Yii::$app->db->createCommand()->insert('service_attribute_value', [
                            'service_id' => $model->id,
                            'attribute_id' => $attrId,
                            'value' => $value,
                        ])->execute();
                    }
                }
                
                return $this->redirect(['view', 'id' => $trip_id]);
            }
        }

        return $this->render('add-service', [
            'model' => $model,
            'trip' => $trip,
            'serviceTypes' => $serviceTypes,
            'users' => $users,
        ]);
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws \yii\db\Exception
     */
    public function actionConfirmService($id) : Response
    {
        $service = Service::findOne($id);
        if ($service) {
            $service->is_confirmed = !$service->is_confirmed;
            $service->save();
        }
        
        return $this->redirect(['view', 'id' => $service->trip_id]);
    }

    /**
     * @param $id
     *
     * @return Trip|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id) : ?Trip
    {
        if (($model = Trip::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $service_type_id
     *
     * @return string
     */
    public function actionGetAttributes($service_type_id) : string
    {
        $attributes = ServiceAttribute::find()
                                      ->where(['service_type_id' => $service_type_id])
                                      ->all();

        return $this->renderPartial('_attributes', [
            'attributes' => $attributes,
        ]);
    }
}