<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Personnes */

$this->title = 'Create Personnes';
$this->params['breadcrumbs'][] = ['label' => 'Personnes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personnes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
