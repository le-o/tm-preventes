<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TypeAbonnement */

$this->title = 'Créer un abonnement';
$this->params['breadcrumbs'][] = ['label' => 'Type Abonnements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-abonnement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
