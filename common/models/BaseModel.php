<?php
namespace common\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Exception;

class BaseModel extends ActiveRecord{
    const VALID = 1;
    const UN_VALID = 0;
}