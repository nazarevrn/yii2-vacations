<?php

use yii\db\Migration;
use yii\db\sqlite\Schema;

/**
 * Class m190613_075226_create_tblUsers
 */
class m190613_075226_create_tblUsers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tblUsers', [
            'id'        => $this->primaryKey(),
            'username'  => Schema::TYPE_STRING,
            'password'  => Schema::TYPE_STRING,
            'sault'     => Schema::TYPE_STRING,
            'email'     => Schema::TYPE_STRING,
            'shortName' => Schema::TYPE_STRING,
            'fullName'  => Schema::TYPE_STRING,
            'created'   => Schema::TYPE_DATETIME,
            'updated'   => Schema::TYPE_DATETIME
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tblUsers');

        //return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190613_075226_create_tblUsers cannot be reverted.\n";

        return false;
    }
    */
}
