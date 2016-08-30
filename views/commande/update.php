<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Commande */

$this->title = 'Edition commande: ' . ' ' . $modelCommande->commande_id;
$this->params['breadcrumbs'][] = ['label' => 'Commandes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelCommande->commande_id, 'url' => ['view', 'id' => $modelCommande->commande_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="commande-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'alerte' => $alerte,
        'modelPersonne' => $modelPersonne,
        'modelCommande' => $modelCommande,
        'modelTypeAbonnement' => $modelTypeAbonnement,
        'modelsDetailCommande' => $modelsDetailCommande
    ]) ?>

</div>
