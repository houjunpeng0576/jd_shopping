<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\RunoobTbl;

/**
 * RunoobTblSearch represents the model behind the search form of `frontend\models\RunoobTbl`.
 */
class RunoobTblSearch extends RunoobTbl
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['runoob_id'], 'integer'],
            [['runoob_title', 'runoob_author', 'submission_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = RunoobTbl::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'runoob_id' => $this->runoob_id,
            'submission_date' => $this->submission_date,
        ]);

        $query->andFilterWhere(['like', 'runoob_title', $this->runoob_title])
            ->andFilterWhere(['like', 'runoob_author', $this->runoob_author]);

        return $dataProvider;
    }
}
