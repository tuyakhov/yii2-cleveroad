<?php

use yii\db\Schema;
use yii\db\Migration;

class m141022_173739_create_product_table extends Migration
{
    protected $table = 'product';

    public function up()
    {
        $this->createTable($this->table, [
            'id' => 'pk',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'image' => Schema::TYPE_STRING . ' NOT NULL',
            'price' => Schema::TYPE_MONEY . ' NOT NULL',
            'owner_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
        $this->addForeignKey('fk_product_owner_id', $this->table, 'owner_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_product_owner_id', $this->table);
        $this->dropTable($this->table);
    }
}
