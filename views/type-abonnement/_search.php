<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TypeAbonnementSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="type-abonnement-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'type_abonnement_id') ?>

    <?= $form->field($model, 'nom') ?>

    <?= $form->field($model, 'montant') ?>

    <?= $form->field($model, 'montant_10') ?>

    <?= $form->field($model, 'is_famille') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
