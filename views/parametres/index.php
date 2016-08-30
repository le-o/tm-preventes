<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParametresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Paramètres';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parametres-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Créer un paramètres', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'class_key',
                'value' => function ($data) {
                    $etats = $data->optsRegroupement();
                    return $etats[$data->class_key];
                }
            ],
            'nom',
            [
                'attribute' => 'valeur',
                'format' => 'html',
            ],
            'info_special',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
