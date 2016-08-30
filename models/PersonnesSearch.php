<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Personnes;

/**
 * PersonnesSearch represents the model behind the search form about `app\models\Personnes`.
 */
class PersonnesSearch extends Personnes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personne_id'], 'integer'],
            [['nom', 'prenom', 'rue', 'boite_postale', 'npa', 'localite', 'email', 'telephone'], 'safe'],
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
        $query = Personnes::find();

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
            'personne_id' => $this->personne_id,
        ]);

        $query->andFilterWhere(['like', 'nom', $this->nom])
            ->andFilterWhere(['like', 'prenom', $this->prenom])
            ->andFilterWhere(['like', 'rue', $this->rue])
            ->andFilterWhere(['like', 'boite_postale', $this->boite_postale])
            ->andFilterWhere(['like', 'npa', $this->npa])
            ->andFilterWhere(['like', 'localite', $this->localite])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'telephone', $this->telephone]);

        return $dataProvider;
    }
}
