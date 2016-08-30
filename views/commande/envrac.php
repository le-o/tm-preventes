<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\helpers\Enum;
use kartik\depdrop\DepDrop;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\Commande */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if ($alerte != '') {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-success',
        ],
        'body' => $alerte,
    ]); 
} ?>

<div class="commande-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    
    <?php foreach ($modelsDetailCommande as $i => $detail): ?>
        <div class="row">
            <div class="col-sm-5">
                <label class="control-label" style="float:right;"><?= $detail->fkTypeAbonnement->nom ?></label>
            </div>
            <div class="col-sm-1">
                <?= Html::input('number', $detail->detail_commande_id, $detail->nom_carte, ['class'=>'form-control'])?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton('Enregister', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
