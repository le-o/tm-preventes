<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Parametres */

$this->title = 'Création paramètres';
$this->params['breadcrumbs'][] = ['label' => 'Paramètres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parametres-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
