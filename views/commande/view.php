<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Commande */

$this->title = 'Récapitulatif de commande';
?>
<style>
@media print
{    
    .no-print, .no-print * { display: none !important; }
    .col-sm-offset-7 { padding-left:370px; }
}
</style>
<div class="commande-view">
    
    <div class="no-print">
        <?php if (!Yii::$app->user->isGuest) { ?>
            <p>
                <?= Html::a('Editer', ['update', 'id' => $modelCommande->commande_id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Supprimer', ['delete', 'id' => $modelCommande->commande_id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Etes-vous sûr de vouloir supprimer cet élément?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        <?php } else {
            echo '<div class="alert alert-success">Merci pour votre commande.<br>Une confirmation de votre commande vous a été envoyée par e-mail.</div>';
        } ?>
    </div>

    <h1><?= Html::encode($this->title.' #'.$modelCommande->commande_id) ?></h1>
    
    <div class="row">
        <div class="col-sm-4">
            TéléMarécottes S.A.<br />
            Secrétariat<br />
            Place de la Télécabine 5<br />
            1923 Les Marécottes<br />
        </div>
    </div>
    <div class="row" style="padding-top:60px;">
        <div class="col-sm-offset-7">
            <?= $modelPersonne->prenom.' '.$modelPersonne->nom ?>
            <br />
            <?php if ($modelPersonne->boite_postale != '') {
                    echo $modelPersonne->boite_postale.'<br />';
                }
                echo $modelPersonne->rue; ?>
            <br />
            <?= $modelPersonne->npa.' '.$modelPersonne->localite ?>
            <br /><br />
            E <?= $modelPersonne->email ?>
            <br />
            T <?= $modelPersonne->telephone ?>
        </div>
    </div>
    <div class="row" style="padding-top:80px;">
        Abonnement<?= (count($modelsDetailCommande) > 1) ? 's' : '' ?> commandé<?= (count($modelsDetailCommande) > 1) ? 's' : '' ?> pour
    </div>
    <div class="row" style="padding-top:20px;">
        <table class="table table-striped table-bordered">
            <tr><th>Prénom Nom</th><th>Date naissance</th><th>Type abonnement</th><th>Prix</th></tr>
            <?php $total_rabais = 0;
            foreach ($modelsDetailCommande as $detail) {
                echo '<tr>';
                    echo '<td>'.$detail->prenom_carte.' '.$detail->nom_carte.'</td>';
                    echo '<td>'.$detail->date_naissance.'</td>';
                    $type = $detail->getFkTypeAbonnement()->one();
                    echo '<td>'.$type->nom.'</td>';
                    echo '<td>CHF '.$detail->montant.'</td>';
                    $total_rabais += $detail->montant;
                echo '</tr>';
            } ?>
            <tr><th colspan="3"><div class="pull-right">Prix total</div></th><th>CHF <?= $total_rabais ?>.00</th></tr>
        </table>
    </div>
    <div class="row" style="padding-top:20px;">
        <div class="col-sm-3"><strong>Votre message</strong>:</div>
        <div class="col-sm-7"><?= $modelCommande->message ?></div>
    </div>
    <div class="row" style="padding-top:60px;">
        <?php echo $texte->valeur; ?>
    </div>
    <div class="row" style="padding-top:60px;">
        Commande par Internet le <?= date('d.m.Y', strtotime($modelCommande->date_commande)) ?>
    </div>
</div>
