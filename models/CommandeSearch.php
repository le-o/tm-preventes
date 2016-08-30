<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Commande;

/**
 * CommandeSearch represents the model behind the search form about `app\models\Commande`.
 */
class CommandeSearch extends Commande
{
    public $fkPersonne;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['commande_id', 'fk_personne', 'etat_commande'], 'integer'],
            [['date_commande', 'message', 'fkPersonne'], 'safe'],
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
        $query = Commande::find()->where('fk_personne <> '.Yii::$app->params['adresseTele']);
        $query->joinWith(['fkPersonne']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['commande_id'=>SORT_DESC]],
            'pagination' => [
                'pagesize' => 100,
            ],
        ]);
        
        $dataProvider->sort->attributes['fkPersonne'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['personnes.nom' => SORT_ASC],
            'desc' => ['personnes.nom' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'commande_id' => $this->commande_id,
            'fk_personne' => $this->fk_personne,
            'etat_commande' => $this->etat_commande,
        ]);
        // Recherche par nom - prénom
        $query->andFilterWhere(['or', 
            ['like', 'personnes.nom', $this->fkPersonne],
            ['like', 'personnes.prenom', $this->fkPersonne],
        ]);
        $query->andFilterWhere(['YEAR(`date_commande`)' => $this->date_commande]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
