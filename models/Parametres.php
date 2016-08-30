<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "parametres".
 *
 * @property integer $parametre_id
 * @property integer $class_key
 * @property string $nom
 * @property string $valeur
 * @property string $info_special
 */
class Parametres extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parametres';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_key', 'nom', 'valeur'], 'required'],
            [['class_key'], 'integer'],
            [['valeur'], 'string'],
            [['nom'], 'string', 'max' => 50],
            [['info_special'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parametre_id' => 'Parametre ID',
            'class_key' => 'Code regroupement',
            'nom' => 'Nom',
            'valeur' => 'Valeur',
            'info_special' => 'Info Special',
        ];
    }
    
    /**
     * @return array options for class_key drop-down
     */
    public function optsRegroupement()
    {
        return array(
            '1' => 'Texte et email',
            '2' => 'Liste déroulante',
            '3' => 'Paramètres généraux',
        );
    }
}
