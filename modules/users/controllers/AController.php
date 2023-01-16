<?php
namespace app\modules\users\controllers;

use app\classes\Utilities;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

use yii\easyii\components\Controller;

class AController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => User::find()
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new User();
        $model->role = 3;
        $model->scenario = USER::SCENARIO_ADMIN_EDIT;
        return $this->render('user-edit', ['model' => $model]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionSave()
    {
        $id = intval(Yii::$app->request->post('id'));

        if($id) {
            $model = User::findOne($id);

            if($model === null){
                $this->flash('error', 'такой модели не существует');
                return $this->redirect(['/admin/'.$this->module->id]);
            }
        } else {
            $model = new User();
        }

        $model->scenario = User::SCENARIO_ADMIN_EDIT;
        if ($model->load(Yii::$app->request->post())) {
            $model->photo = UploadedFile::getInstanceByName('User[photo]');
            if($model->validate())
            {
                if($model->photo)
                {
                    /** @var UploadedFile $photo */
                    $photo = $model->photo;
                    $ext = $photo->extension;
                    $path = Yii::getAlias('@app/web/images/users/');
                    $filename = Utilities::uniqueFileName($path, $ext, 'user_photo');

                    if($photo->saveAs($path.$filename)) {
                        $model->photo = Url::to('@web/images/users/') . $filename;
                        $oldAttributes = $model->oldAttributes;
                        $oldImage = $oldAttributes ? $oldAttributes['photo'] : '';
                        if(!empty($oldImage))
                        {
                            $oldpath = Yii::getAlias('@app/web/'.$oldImage);
                            if(file_exists($oldpath))
                                unlink($oldpath);
                        }
                    }
                    else
                    {
                        Yii::warning("Картинка пользователя не сохранилась");
                        $model->photo = $model->oldAttributes['photo'];
                    }
                } else {
                    $model->photo = $model->oldAttributes['photo'];
                }

                $model->save(false);
                $this->flash('success', 'Пользователь сохранен');
                return $this->redirect(['/admin/'.$this->module->id.'/a']);
            }
            $model->photo = $model->oldAttributes['photo'];

        }

        return $this->render('user-edit', [ 'model' => $model ]);
    }

    public function actionEdit($id)
    {
        $model = User::findOne($id);

        if($model === null){
            $this->flash('error', 'Not found');
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        $model->scenario = User::SCENARIO_ADMIN_EDIT;
        return $this->render('user-edit', [ 'model' => $model ]);
    }

    public function actionDelete($id)
    {
        if(($model = User::findOne($id))){
            Utilities::removeUser($model);
        } else {
            $this->error = "Такой пользователь не найден";
        }
        return $this->formatResponse('Пользователь удален');
    }
}