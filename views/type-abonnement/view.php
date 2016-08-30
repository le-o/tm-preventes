<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TypeAbonnement */

$this->title = $model->type_abonnement_id;
$this->params['breadcrumbs'][] = ['label' => 'Type Abonnements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-abonnement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editer', ['update', 'id' => $model->type_abonnement_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Supprimer', ['delete', 'id' => $model->type_abonnement_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Etes-vous sûr de vouloir supprimer cet élément?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'type_abonnement_id',
            'tri',
            'nom',
            'montant',
            'montant_10',
            'montant_15',
            [
                'attribute' => 'is_famille',
                'value' => ($model->is_famille) ? 'Oui' : 'Non',
            ],
            'an_debut',
            'an_fin',
            'valide_debut',
            'valide_fin',
            [
                'attribute' => 'is_archive',
                'value' => ($model->is_archive) ? 'Oui' : 'Non',
            ],
        ],
    ]) ?>

</div>
