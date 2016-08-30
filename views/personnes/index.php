<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PersonnesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Personnes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personnes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Personnes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'personne_id',
            'nom',
            'prenom',
            'rue',
            'boite_postale',
            // 'npa',
            // 'localite',
            // 'email:email',
            // 'telephone',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
