<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TypeAbonnementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Abonnements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-abonnement-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Créer un abonnement', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'tri',
            'nom',
            'montant',
            'montant_10',
            'montant_15',
            'valide_debut',
            'valide_fin',
            [
                'attribute' => 'is_famille',
                'value' => function ($data) {
                    return ($data->is_famille) ? 'Oui' : 'Non';
                }
            ],
            [
                'attribute' => 'is_archive',
                'value' => function ($data) {
                    return ($data->is_archive) ? 'Oui' : 'Non';
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
