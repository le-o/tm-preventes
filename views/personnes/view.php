<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Personnes */

$this->title = $model->personne_id;
$this->params['breadcrumbs'][] = ['label' => 'Personnes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personnes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->personne_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->personne_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'personne_id',
            'nom',
            'prenom',
            'rue',
            'boite_postale',
            'npa',
            'localite',
            'email:email',
            'telephone',
        ],
    ]) ?>

</div>
