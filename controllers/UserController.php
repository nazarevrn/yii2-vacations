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
        print '<pre>';
        print_r(Yii::$app->request->post());
        print '</pre>';
        die;
        */
        if (!Yii::$app->user->identity) { //пользователь не аутентифицирован
            
            $model = new User();
            $postParams = Yii::$app->request->post();
            
            if ($postParams) {
                if ( $model->login($postParams['User']) ) {
                    //Yii::$app->user->login(true);
                    $identity = User::findOne(['username' => $postParams['User']['username']]);
                    Yii::$app->user->login($identity);
                    return $this->goHome();
                }            
            }

            return $this->render('login', ['model' => $model]);

        }

    }

    public function actionRegister()
    {
        $model = new User();
        $postParams = Yii::$app->request->post();
        if ($postParams) {
            if ( $model->register($postParams['User']) ) {
                //Yii::$app->user->login(true);
                $identity = User::findOne(['username' => $postParams['User']['username']]);
                Yii::$app->user->login($identity);
                return $this->goHome();
            }
        }
        return $this->render('register', ['model' => $model]);
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