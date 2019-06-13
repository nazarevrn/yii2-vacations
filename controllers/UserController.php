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
        if (!Yii::$app->user->identity) { //пользователь не аутентифицирован
            $model = new User();
            $postParams = Yii::$app->request->post();
            if ($postParams) {
                if ( $model->load($postParams['User']) ) {
                    //Yii::$app->user->login(true);
                    $identity = User::findOne(['username' => $postParams['User']['username']]);
                    Yii::$app->user->login($identity);
                    return $this->goHome();
                }
            }
            return $this->render('login', ['model' => $model]);
        }

    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}