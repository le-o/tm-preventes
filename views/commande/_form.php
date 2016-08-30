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

$this->registerJs(' 
    $(document).ready(function(){
        $(".dynamicform_wrapper").on("beforeInsert", function(e, item) {
            console.log("beforeInsert");
        });

        $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
            calculTotal();
            $("select[name*=\"[fk_type_abonnement]\"]").change(function() {
                calculTotal();
            });
        });

        $(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
            if (! confirm("Voulez-vous vraiment supprimer cet abonnement?")) {
                return false;
            }
            return true;
        });

        $(".dynamicform_wrapper").on("afterDelete", function(e) {
            calculTotal();
            $("select[name*=\"[fk_type_abonnement]\"]").change(function() {
                calculTotal();
            });
        });

        $(".dynamicform_wrapper").on("limitReached", function(e, item) {
            alert("Vous avez atteint la limite maximale d\'abonnement.");
        });
        $("select[name*=\"[fk_type_abonnement]\"]").change(function() {
            calculTotal();
        });
        calculTotal();
    });
    
    function calculTotal() {
        var montanttotal = 0;
        $("select[name*=\"[fk_type_abonnement]\"]").each(function() {
            var abo = $(this).find(":selected").text();
            var montant = abo.replace(/[\s-]+$/, \'\').split(/[\s-]/).pop();
            if (montant == \'abonnement\') montant = 0;
            montanttotal = montanttotal + parseInt(montant);
        });
        if (montanttotal == \'\') montanttotal = 0;
        $("#totalCommande").val(montanttotal);
    }',
\yii\web\View::POS_READY);
?>

<?php if ($alerte != '') {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-warning',
        ],
        'body' => $alerte,
    ]); 
} ?>

<div class="commande-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($modelPersonne, 'nom')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($modelPersonne, 'prenom')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($modelPersonne, 'boite_postale')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-8">
            <?= $form->field($modelPersonne, 'rue')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($modelPersonne, 'npa')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-8">
            <?= $form->field($modelPersonne, 'localite')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <?= $form->field($modelPersonne, 'email', [
                    'addon' => ['prepend' => ['content'=>'@']]
                ])->textInput(['maxlength' => true]); ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($modelPersonne, 'telephone')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-shopping-cart"></i> Ma commande</h4></div>
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 10, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelsDetailCommande[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'fk_commande',
                    'nom_carte',
                    'prenom_carte',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($modelsDetailCommande as $i => $modelDetail): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left"><i class="glyphicon glyphicon-credit-card"></i> Abonnement</h3>
                        <div class="pull-right">
                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $modelDetail->isNewRecord) {
                                echo Html::activeHiddenInput($modelDetail, "[{$i}]detail_commande_id");
                            }
                        ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <?= $form->field($modelDetail, "[{$i}]nom_carte")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $form->field($modelDetail, "[{$i}]prenom_carte")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $form->field($modelDetail, "[{$i}]date_naissance")->widget(DatePicker::classname(), [
                                    'options' => ['placeholder' => 'jj.mm.aaaa',
//                                        'onchange'=>'console.log("ok "+$(this).));',
                                        'onchange'=>'
                                             $.ajax({
                                                url: "'.Yii::$app->urlManager->createUrl(['commande/fillabo']).'",
                                                data: "dateRef="+$(this).val()+"&selector="+$(this).attr("id"),
                                                success: function (data) {
                                                    var result = $.parseJSON(data);
                                                    $("#"+result.selector).html(result.options);
                                                }
                                            }).error(function() {
                                                alert("Un problème est survenue dans l\'application.");
                                            });',
                                        ],
                                    'removeButton' => false,
                                    'pluginOptions' => [
                                        'autoclose'=>true,
                                        'format' => 'dd.mm.yyyy',
                                        'defaultViewDate' => ['year' => 1980]
                                    ]
                                ]); ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $form->field($modelDetail, "[{$i}]fk_type_abonnement", ['labelOptions' => [ 'class' => 'label-big' ]])->dropDownList($modelTypeAbonnement->optsTypeValide($modelDetail),['prompt'=>'Choisir un abonnement']) ?>
                            </div>
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <?php DynamicFormWidget::end(); ?>
            <div class="pull-right col-sm-3" style="min-width:220px;">
                <strong>Montant total à payer</strong>
                <div class="input-group">
                    <span class="input-group-addon">CHF</span>
                    <input id="totalCommande" type="text" class="form-control" aria-label="Montant total" style="text-align: right;">
                    <span class="input-group-addon">.00</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($modelCommande, 'message')->textArea(['maxlength' => true, 'rows' => '4']) ?>
        </div>
    </div>
    
    <?php if (!$modelDetail->isNewRecord) { ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($modelCommande, 'etat_commande')->dropDownList($modelCommande->optsEtat()) ?>
        </div>
    </div>
    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton($modelDetail->isNewRecord ? 'Envoyer la commande' : 'Modifier la commande', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
