<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use scotthuangzl\googlechart\GoogleChart;

$this->title = 'Tableau de bord';
$this->params['breadcrumbs'][] = 'Tableau de bord';
?>
<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="pull-right">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    Filtrer le résultat: 
    <?= Html::a('Tous', Url::to(['index', 'dateref'=>''])); ?>
    
    <?php $year = date('Y');
    while ($year >= 2015) {
        echo '&nbsp;-&nbsp;'.Html::a($year, Url::to(['index', 'dateref'=>$year]));
        $year--;
    } ?>

    <?php ActiveForm::end(); ?>

</div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php echo GoogleChart::widget(array('visualization' => 'PieChart',
                'data' => $statsGenre,
                'options' => array('title' => 'Nombre abonnements par genre',
                    'height' => 400))); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?php echo GoogleChart::widget(array('visualization' => 'ColumnChart',
                'data' => $statsPrix,
                'options' => array('title' => 'Montant total par genre (CHF) | Montant total CHF '.$totalPrix,
                    'legend' => 'none',
                    'colors' => array('#109618')))); ?>
        </div>
        <div class="col-lg-6">
            <?php echo GoogleChart::widget(array('visualization' => 'ColumnChart',
                'data' => $statsType,
                'options' => array('title' => 'Nombre abonnements par type | Nombre total '.$totalNombre,
                    'legend' => 'none'))); ?>
        </div>
    </div>
</div>
