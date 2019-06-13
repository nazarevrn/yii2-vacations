<?php

namespace app\controllers;

use Yii;
// use yii\filters\AccessControl;
use yii\web\Controller;
// use yii\web\Response;
// use yii\filters\VerbFilter;
// use app\models\LoginForm;
use app\models\tblUsers;

class TestdbController extends Controller
{
    public function actionIndex()
    {
        $users = new tblUsers();
        $model = $users::find()->where(['id' => 1])->one();

        return $this->render('index', ['model' => $model]);
    }
}