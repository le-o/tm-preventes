<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Personnes */

$this->title = 'Update Personnes: ' . ' ' . $model->personne_id;
$this->params['breadcrumbs'][] = ['label' => 'Personnes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->personne_id, 'url' => ['view', 'id' => $model->personne_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="personnes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
