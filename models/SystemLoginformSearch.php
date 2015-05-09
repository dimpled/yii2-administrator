<?php

namespace dimple\administrator\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dimple\administrator\models\SystemLoginform;

/**
 * SystemLoginformSearch represents the model behind the search form about `app\modules\settings\models\SystemLoginform`.
 */
class SystemLoginformSearch extends SystemLoginform
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_id', 'time', 'success'], 'integer'],
            [['username', 'password', 'ip', 'user_agent'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = SystemLoginform::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['time'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
           // $query->where('username ==');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'log_id' => $this->log_id,
            'time' => $this->time,
            'success' => $this->success,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'user_agent', $this->user_agent]);

        return $dataProvider;
    }
}
