<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Commande */

$this->title = 'Création commande';
?>
<div class="commande-create">

    <?= $this->render('_form', [
        'alerte' => $alerte,
        'modelPersonne' => $modelPersonne,
        'modelCommande' => $modelCommande,
        'modelTypeAbonnement' => $modelTypeAbonnement,
        'modelsDetailCommande' => $modelsDetailCommande
    ]) ?>

</div>
