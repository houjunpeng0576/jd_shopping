<?php
namespace common\models;
use common\models\BaseModel;
use Yii;

class  UserProfile extends BaseModel{
    public static function tableName()
    {
        return '{{%user_profile}}';
    }
}