<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TypeAbonnement;

/**
 * TypeAbonnementSearch represents the model behind the search form about `app\models\TypeAbonnement`.
 */
class TypeAbonnementSearch extends TypeAbonnement
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_abonnement_id', 'montant', 'montant_10', 'montant_15', 'is_famille', 'is_archive'], 'integer'],
            [['nom'], 'safe'],
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
        $query = TypeAbonnement::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'type_abonnement_id' => $this->type_abonnement_id,
            'montant' => $this->montant,
            'montant_10' => $this->montant_10,
            'montant_15' => $this->montant_15,
            'is_famille' => $this->is_famille,
            'is_archive' => $this->is_archive,
        ]);

        $query->andFilterWhere(['like', 'nom', $this->nom]);
        $query->orderBy('is_archive ASC, tri ASC');

        return $dataProvider;
    }
}
