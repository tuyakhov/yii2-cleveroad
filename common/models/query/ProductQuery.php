<?php
namespace common\models\query;

use yii\db\ActiveQuery;

class ProductQuery extends ActiveQuery
{
    public function self()
    {
        $this->andWhere(['owner_id' => \Yii::$app->user->id]);
        return $this;
    }
}