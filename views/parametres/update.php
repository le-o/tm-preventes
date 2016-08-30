<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Parametres */

$this->title = 'Edition Paramètres: ' . ' ' . $model->parametre_id;
$this->params['breadcrumbs'][] = ['label' => 'Paramètres', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->parametre_id, 'url' => ['view', 'id' => $model->parametre_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="parametres-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
