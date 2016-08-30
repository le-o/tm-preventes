<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CommandeSearch */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="pull-right">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    Filtrer le résultat: 
    <?= Html::a('Tous', Url::to(['commande/index', 'CommandeSearch[date_commande]'=>''])); ?>
    
    <?php $year = date('Y');
    while ($year >= 2015) {
        echo '&nbsp;-&nbsp;'.Html::a($year, Url::to(['commande/index', 'CommandeSearch[date_commande]'=>$year]));
        $year--;
    } ?>

    <?php ActiveForm::end(); ?>

</div>
