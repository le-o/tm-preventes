<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "personnes".
 *
 * @property integer $personne_id
 * @property string $nom
 * @property string $prenom
 * @property string $rue
 * @property string $boite_postale
 * @property string $npa
 * @property string $localite
 * @property string $email
 * @property string $telephone
 *
 * @property Commande[] $commandes
 */
class Personnes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'personnes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nom', 'prenom', 'rue', 'npa', 'localite', 'email', 'telephone'], 'required'],
            [['nom', 'prenom'], 'string', 'max' => 60],
            [['rue', 'localite', 'email'], 'string', 'max' => 100],
            [['boite_postale'], 'string', 'max' => 10],
            [['npa'], 'string', 'max' => 5],
            [['telephone'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'personne_id' => 'Personne ID',
            'nom' => 'Nom',
            'prenom' => 'Prenom',
            'rue' => 'Rue',
            'boite_postale' => 'Boite Postale',
            'npa' => 'Npa',
            'localite' => 'Localite',
            'email' => 'Email',
            'telephone' => 'Telephone',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommandes()
    {
        return $this->hasMany(Commande::className(), ['fk_personne' => 'personne_id']);
    }
}
