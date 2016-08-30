<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detail_commande".
 *
 * @property integer $detail_commande_id
 * @property integer $fk_commande
 * @property integer $fk_type_abonnement
 * @property string $nom_carte
 * @property string $prenom_carte
 * @property date $date_naissance
 * @property float $montant
 *
 * @property TypeAbonnement $fkTypeAbonnement
 * @property Commande $fkCommande
 */
class DetailCommande extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'detail_commande';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_commande', 'fk_type_abonnement', 'nom_carte', 'prenom_carte', 'date_naissance', 'montant'], 'required'],
            [['fk_commande', 'fk_type_abonnement'], 'integer'],
            [['nom_carte', 'prenom_carte'], 'string', 'max' => 60],
            
            // validation du type d'abonnement selon date de naissance
//            ['fk_type_abonnement', 'validateTypeAbo'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'detail_commande_id' => 'Detail Commande ID',
            'fk_commande' => 'Fk Commande',
            'fk_type_abonnement' => 'Type abonnement',
            'nom_carte' => 'Nom Carte',
            'prenom_carte' => 'Prénom Carte',
            'date_naissance' => 'Date de naissance',
            'montant' => 'Montant',
        ];
    }
    
    /**
     * Validation des abonnements familles
     */
    public static function validateFamille($modelsDetailCommande) {
        foreach ($modelsDetailCommande as $detail) {
            if ($detail->fkTypeAbonnement->is_famille) {
                if (!isset($comptage[$detail->fk_type_abonnement])) $comptage[$detail->fk_type_abonnement] = 0;
                $comptage[$detail->fk_type_abonnement]++;
            }
        }
        // au moins un abo famille
        if (isset($comptage)) {
            // moins de 3 abonnements commandés => pas famille
            if (count($modelsDetailCommande) < 3) return false;
        
            // si il y a qu'un type d'abo => pas famille
            if (count($comptage) < 2) return false;

            $nbrPremium = $nbrStandard = $nbrAdulte = $nbrEnfant = 0;
            foreach ($comptage as $cle => $nombre) {
                if (in_array($cle, yii::$app->params['typeAboPremium'])) $nbrPremium += $nombre;
                if (in_array($cle, yii::$app->params['typeAboStandart'])) $nbrStandard += $nombre;

                if (in_array($cle, yii::$app->params['typeAboAdulteFamille'])) $nbrAdulte += $nombre;
                if (in_array($cle, array_merge(yii::$app->params['typeAboEtudiantFamille'], yii::$app->params['typeAboJeuneFamille'], yii::$app->params['typeAboEnfantFamille']))) $nbrEnfant += $nombre;
            }

            // si pas 3 abo du même type => pas famille
            if ($nbrPremium < 3 && $nbrStandard < 3) return false;
            // si plus de 2 adultes ou étudiant => pas famille
            if ($nbrAdulte > 2) return false;
            // si pas enfants => pas famille
            if ($nbrEnfant < 1) return false;
        }
        
        return true;
    }
    
    /**
     * Validation du choix de l'abonnement selon date de naissance
     */
    public function validateTypeAbo()
    {
        $this->addError('fk_type_abonnement', 'Abonnement non disponible.');
        if (date('Y', strtotime($this->date_naissance)) < '1981') {
            $this->addError('fk_type_abonnement', 'Abonnement non disponible.');
        }
    }
    
    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->date_naissance = date('d.m.Y', strtotime($this->date_naissance));

        parent::afterFind();
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->date_naissance = date('Y-m-d', strtotime($this->date_naissance));

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkTypeAbonnement()
    {
        return $this->hasOne(TypeAbonnement::className(), ['type_abonnement_id' => 'fk_type_abonnement']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkCommande()
    {
        return $this->hasOne(Commande::className(), ['commande_id' => 'fk_commande']);
    }
}
