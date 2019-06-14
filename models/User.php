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



    public function login($user)
    {
        
        if ($this->findByUsername($user['username'])) {

            if( $this->checkPassword($user['username'], $user['password']) ) {
                return true;
            } else {
                return false;
            }
        } else {
            return false; //неверный логин
        }
        
        
    }

    public function findByUsername($username) 
    {        
        if($this->find()->where(['username' => $username])->one()) {
            return true;
        }
        return false;

    }

    public static function hidePass($password)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        
        $randString = '0';
        for ($i = 0; $i < 10; $i++) {
            $randString .= $characters[rand(0, strlen($characters) - 1)];
        }
        $sault = hash('sha512', $randString);
        return [
                'sault'         =>  $sault,
                'saultedPass'   =>  hash('sha512', $password . $sault) //ну да, просто. надо придумать что-то похитрее, но лень
                ];
    }

    public function checkPassword($username, $password)
    {
        $userInDb = $this->find()->where(['username' => $username])->one();

        if ( $userInDb->password === hash('sha512', $password . $userInDb->sault)) {
            return true;
        }

    }

    public function isUnique($user) {
        $login = $user['username'];
        $email = $user['email'];
        $userInDB = $this->find()->where(['username' => $login])->orWhere(['email' => $email])->one();
        if ($userInDB) {
            return false;
        }

        return true;

    }

    public function register($user)
    {   
        if ( !$this->isUnique($user) ) {
            return false;
        } else {
            $this->fullName = $user['fullName'];
            $this->username = $user['username'];
            $splitUsername = explode(' ', $this->fullName);
    
            $this->shortName = $splitUsername[0] . ' ' . mb_substr($splitUsername[1], 0, 1) . '. ' . mb_substr($splitUsername[2], 0, 1) . '.';
            $password = $user['password'];
            $this->email = $user['email'];
    
            $saultAndHiddenPass = $this->hidePass($password);
            $this->sault = $saultAndHiddenPass['sault'];
            $this->password = $saultAndHiddenPass['saultedPass'];
            $this->created = date('Y-m-d H:i:s');
            
            $this->save(false);
            return true;
        }


        /*
        $this->insert(false, [
            'userName' => $userName,
            'password' => $password,
            'sault'     => $sault,
            'email'     => $email,
            'shortName' => $shortName,
            'fullName'  => $fullName,
            

        ]);
        */

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
