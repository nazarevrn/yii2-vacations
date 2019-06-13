<?php

namespace app\controllers;

use Yii;
// use yii\filters\AccessControl;
use yii\web\Controller;
// use yii\web\Response;
// use yii\filters\VerbFilter;
// use app\models\LoginForm;
use app\models\User;


class UserController extends Controller
{
    public function actionLogin()
    {
        /*
        $users = new tblUsers();
        $model = $users::find()->where(['id' => 1])->one();

        return $this->render('login', ['model' => $model]);
        
        $identity = Yii::$app->user->identity;
        return $this->render('login', ['model' => $identity]);
        */
        //$identity = User::findOne(['username' => $username]);
        if (!Yii::$app->user->identity) { //пользователь не аутентифицирован
            $model = new User();
            /*
            if ($model->load(Yii::$app->request->post())) {
                //return $this->goBack();
                return 'oh!';
            }
            */

            $postParams = Yii::$app->request->post();
            if ($postParams) {
                if ($model->load($postParams['User']) ) {
                    //Yii::$app->user->login(true);
                    $identity = User::findOne(['username' => $postParams['User']['username']]);
                    Yii::$app->user->login($identity);
                    return $this->goHome();
                }
            }
            //$model->load(Yii::$app->request->post());
            return $this->render('login', ['model' => $model]);

            /*
            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
            */
        }


    }
}