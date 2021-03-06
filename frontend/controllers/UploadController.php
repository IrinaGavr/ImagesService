<?php

namespace frontend\controllers;

use Yii;
use common\models\Upload;
use common\models\UploadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * UploadController implements the CRUD actions for Upload model.
 */
class UploadController extends Controller {

    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors() {
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
     * Lists all Upload models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new UploadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Upload model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Upload model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Upload();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('upload', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Upload model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
        }

        if ($model->upload()) {
            // file is uploaded successfully
            return;
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Upload model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Upload model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Upload the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Upload::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUpload() {
        $model = new Upload;
        if (Yii::$app->request->isPost) {

            $model->file = UploadedFile::getInstance($model, 'file');
            $model->load(\Yii::$app->request->post());

            if (!empty($model->file)) {
                $model->save();
            } else {
                Yii::$app->session->setFlash('danger', 'Загрузите файл!');
                return $this->redirect(['upload']);
            }
            return $this->redirect(['upload']);
        }

        return $this->render('upload', ['model' => $model]);
    }

    /**
     * Это отправка на клиенте (будем пихать в компонент Yii2)
     * @param type $model_id
     * @param type $model_name
     * @param type $file_path
     * @param type $desc
     */
//    public function sendFile($model_id, $model_name, $file_path, $desc = '') {
//        $file = file_get_contents($file_path);
//        $data = [
//            'file' => [
//                'ext' => pathinfo($file_path, PATHINFO_EXTENSION),
//                'data' => base64_encode($file)
//            ],
//            'Upload' => [
//                'model_id' => $model_id,
//                'model_name' => $model_name,
//                'desc' => $desc
//            ]
//        ];
//        $obj_to_send = [
//            'ajaxUpload' => json_encode($data)
//        ];
//
//        $curl = curl_init(); //инициализация сеанса
//        curl_setopt($curl, CURLOPT_URL, 'http://images-service.ru/upload/json'); //урл сайта к которому обращаемся
//        curl_setopt($curl, CURLOPT_HEADER, 1); //выводим заголовки
//        curl_setopt($curl, CURLOPT_POST, 1); //передача данных методом POST
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //теперь curl вернет нам ответ, а не выведет
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $obj_to_send);
//        $res = curl_exec($curl);
//        return $res;
//    }
//
//    public function actionTest() {
//        $pyt = \Yii::getAlias('@frontend/web/uploads/' . 'test.txt');
//        $resalt = $this->sendFile('ddd' . time(), 'dddiugd' . time(), $pyt);
//    }

    public function actionJson() {
        $data = \Yii::$app->request->post('ajaxUpload'); // вместо false тутдолжно быть получение POST
        $json = json_decode($data, true); // получаем массив из JSON
        $customUpload = new \common\models\CustomUpload;
        $customUpload->extension = $json['file']['ext'];
        $customUpload->baseName = time() . "tmp";
        $customUpload->fileData = base64_decode($json['file']['data']);

        $model = new Upload;
        $loadResult = $model->load($json);
        $model->file = $customUpload;
        if ($loadResult) {
            return ($model->save()) ? 'true' : json_encode($model->getErrors());
        }
        return 'false';
    }

    public function actionDownloadById($id) {
        $model = Upload::find()->andWhere(['id' => $id])->one();

        if ($model) {
            return 'http://images-service.ru' . $model->Image;
        }
        return FALSE;
    }

    public function actionDownloadByModel($id, $name) {
                    
        $model = Upload::find()->Where(['model_id' => $id])->andWhere(['model_name' => $name])->all();

        $resalt = [
            'status'=>false,
            'result'=>[]
        ];
        foreach ($model as $_model) {
            if ($_model instanceof Upload) {
                $resalt['result'][] = array('url' => 'http://images-service.ru' . $_model->Image, 'desc' => json_decode($_model->desc, true));
            }
        }

        if (!empty($resalt['result'])) {
            $resalt['status']=true;
        }
        return json_encode($resalt, JSON_PRETTY_PRINT);
    }

    public function actionTestik1($file_id = 8601) {
        $model = Upload::find()->andWhere(['id' => $file_id])->one();

        if ($model) {
            var_dump(\Yii::$app->ImageServiceComponent->getFileId(3));
        }
    }

    public function actionTestik2() {

        var_dump(json_decode(\Yii::$app->ImageServiceComponent->getModelVallue($model_id, $model_name), TRUE));
    }

    public function actionTestik3() {
        $pyt = \Yii::getAlias('@frontend/web/uploads/' . 'test.jpg');
        return $resalt = \Yii::$app->ImageServiceComponent->sendFile('ddd', 'dddiugd', $pyt, 'djdj');
    }

}
