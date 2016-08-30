<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "type_abonnement".
 *
 * @property integer $type_abonnement_id
 * @property string $nom
 * @property integer $montant
 * @property integer $montant_10
 * @property integer $montant_15
 * @property integer $is_famille
 * @property integer $an_debut
 * @property integer $an_fin
 * @property integer $valide_debut
 * @property integer $valide_fin
 * @property integer $is_archive
 *
 * @property DetailCommande[] $detailCommandes
 */
class TypeAbonnement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type_abonnement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tri', 'nom', 'montant', 'montant_10', 'montant_15'], 'required'],
            [['montant', 'montant_10', 'montant_15', 'is_famille', 'an_debut', 'an_fin', 'valide_debut', 'valide_fin', 'is_archive'], 'integer'],
            [['nom'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_abonnement_id' => 'Abonnement ID',
            'tri' => 'Tri pour affichage',
            'nom' => 'Nom abonnement',
            'montant' => 'Montant',
            'montant_10' => 'Montant 10%',
            'montant_15' => 'Montant 15%',
            'is_famille' => 'Abo Famille',
            'an_debut' => 'Année min',
            'an_fin' => 'Année max',
            'valide_debut' => 'Début validité',
            'valide_fin' => 'Fin validité',
            'is_archive' => 'Archivé ?',
        ];
    }
    
    /**
     * @return array options for type drop-down
     */
    public function optsType()
    {   
        $types = self::find()->orderBy('tri')->all();
        $temp = array();
        foreach($types as $type) {
            $temp[$type['type_abonnement_id']]= $type->nom.' - CHF '.$type->montant_10;
        }
        return $temp;
    }
    
    /**
     * @return array options for type drop-down
     */
    public function optsTypeValide($modelDetail)
    {   
        $modelCommande = new \app\models\Commande();
        $montant = $modelCommande->getMontantRabais();
        
        if ($modelDetail->isNewRecord) $types = self::find()->where('is_archive = 0')->orderBy('tri')->all();
        else $types = self::find()->where(['OR', 'is_archive = 0', 'type_abonnement_id = '.$modelDetail->fk_type_abonnement])->orderBy('tri')->all();
        $temp = array();
        foreach($types as $type) {
            $temp[$type['type_abonnement_id']]= $type->nom.' - CHF '.$type->$montant;
        }
        return $temp;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetailCommandes()
    {
        return $this->hasMany(DetailCommande::className(), ['fk_type_abonnement' => 'type_abonnement_id']);
    }
}
