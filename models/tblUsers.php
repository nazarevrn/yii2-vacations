<?php

namespace app\models;

use yii\db\ActiveRecord;;

class tblUsers extends ActiveRecord
{
    public static function tableName()
    {
        return '{{tblUsers}}';
    }
}