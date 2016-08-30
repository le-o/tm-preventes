<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "commande".
 *
 * @property integer $commande_id
 * @property integer $fk_personne
 * @property integer $etat_commande
 * @property string $date_commande
 * @property string $message
 *
 * @property Personnes $fkPersonne
 * @property DetailCommande[] $detailCommandes
 */
class Commande extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'commande';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_personne', 'etat_commande', 'date_commande'], 'required'],
            [['fk_personne', 'etat_commande'], 'integer'],
            [['date_commande'], 'safe'],
            [['message'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'commande_id' => 'Commande #',
            'fk_personne' => 'Fk Personne',
            'fkPersonne' => 'Personne',
            'etat_commande' => 'Etat Commande',
            'date_commande' => 'Date Commande',
            'message' => 'Message',
        ];
    }
    
    /**
     * @return array options for etat_commande drop-down
     */
    public function optsEtat()
    {
        return array(
            '0' => 'Annulé',
            '1' => 'Commandé',
            '2' => 'En traitement',
            '3' => 'Finalisé',
            '4' => 'Archivé',
        );
    }
    
    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->date_commande = date('d.m.Y H:i:s', strtotime($this->date_commande));

        parent::afterFind();
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->date_commande = date('Y-m-d H:i:s', strtotime($this->date_commande));

            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @return int Sum of the abos
     */
    public function getTotalCommande() {
        $total = 0;
        foreach ($this->detailCommandes as $detail) {
            $total += $detail->montant;
        }
        return $total;
    }
    
    /**
     * 
     * @return array List of years
     */
    public static function getYearsList() {
        $years = (new Query())->select('DISTINCT YEAR(`date_commande`) as years')->from('{{%commande}}')->column();
        return array_combine($years, $years);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkPersonne()
    {
        return $this->hasOne(Personnes::className(), ['personne_id' => 'fk_personne']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetailCommandes()
    {
        return $this->hasMany(DetailCommande::className(), ['fk_commande' => 'commande_id']);
    }
    
    /**
     * Selon la date de la commande, on prend 15% ou 10% de rabais
     * @return string Nom du champ à prendre en compte
     */
    public function getMontantRabais($dateCompare = '')
    {
        $params = \app\models\Parametres::findOne(Yii::$app->params['dateFin15']);
        $date15 = date(strip_tags($params->valeur));
        
        $date = ($dateCompare == '') ? date('Y-m-d') : date('Y-m-d', strtotime($dateCompare));
        if (date('Y', strtotime($date)) < 2016) $montant = 'montant_10';
        elseif ($date <= date('Y-m-d', strtotime($date15))) $montant = 'montant_15';
        else $montant = 'montant_10';
        return $montant;
    }
}
