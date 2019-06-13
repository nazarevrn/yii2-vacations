<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
/*
в этом классе я скрещаю жабу с гадюкой. пароли храним в солёном виде, для каждого пользователя - соль уникальна.
до специального коммента - идёт мой велосипед, дальше - родной функционал yii из мануала
TO-DO
по-хорошему, эту срань надо заменить на https://www.yiiframework.com/doc/api/2.0/yii-base-security#generatePasswordHash()-detail
*/
class User extends ActiveRecord implements IdentityInterface
{
    
    public static function tableName()
    {
        return '{{tblUsers}}';
    }



    public function load($user)
    {
        
        if (self::findByUsername($user['username'])) {
            //return true;
            //проверить пароль
            if( self::findByUsername($user['username'], $user['password']) ) {
                return true;
            }
        } else {
            return 'login wrong!';
        }
        
        
    }

    public function findByUsername($username) 
    {        
        if(self::find()->where(['username' => $username])->one()) {
            return true;
        }
        return false;

    }

    public static function hidePass($password)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        
        $randString = '0';
        for ($i = 0; $i < 10; $i++) {
            $randstring .= $characters[rand(0, strlen($characters) - 1)];
        }
        $sault = hash('sha512', $randString);
        return [
                'sault'         =>  $sault,
                'saultedPass'   =>  hash('sha512', $password . $sault) //ну да, просто. надо придумать что-то похитрее, но лень
                ];
    }

    public function checkPassword($username, $password)
    {
        $userInDb = self::find()->where(['username' => $username])->one();

        if ( $userInDb->password === hash('sha512', $password . $userInDb->sault)) {
            return true;
        }

    }

    //здесь и далее копипаста из мана https://www.yiiframework.com/doc/guide/2.0/ru/security-authentication
    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }



}
