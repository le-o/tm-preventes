<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TypeAbonnement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="type-abonnement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tri')->textInput(); ?>
    
    <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'montant')->textInput() ?>

    <?= $form->field($model, 'montant_10')->textInput() ?>

    <?= $form->field($model, 'montant_15')->textInput() ?>
    
    <?= $form->field($model, 'is_famille')->checkbox(); ?>

    <?= $form->field($model, 'an_debut')->textInput() ?>

    <?= $form->field($model, 'an_fin')->textInput() ?>

    <?= $form->field($model, 'valide_debut')->textInput() ?>

    <?= $form->field($model, 'valide_fin')->textInput() ?>
    
    <?= $form->field($model, 'is_archive')->checkbox(); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Créer' : 'Editer', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
