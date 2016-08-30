<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TypeAbonnement */

$this->title = 'Update Type Abonnement: ' . ' ' . $model->type_abonnement_id;
$this->params['breadcrumbs'][] = ['label' => 'Type Abonnements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->type_abonnement_id, 'url' => ['view', 'id' => $model->type_abonnement_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="type-abonnement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
