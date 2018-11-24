<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "runoob_tbl".
 *
 * @property int $runoob_id
 * @property string $runoob_title
 * @property string $runoob_author
 * @property string $submission_date
 */
class RunoobTbl extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'runoob_tbl';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['runoob_title', 'runoob_author'], 'required'],
            [['submission_date'], 'safe'],
            [['runoob_title'], 'string', 'max' => 100],
            [['runoob_author'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'runoob_id' => 'Runoob ID',
            'runoob_title' => 'Runoob Title',
            'runoob_author' => 'Runoob Author',
            'submission_date' => 'Submission Date',
        ];
    }
}
