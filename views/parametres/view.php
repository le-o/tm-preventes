<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Parametres */

$this->title = $model->parametre_id;
$this->params['breadcrumbs'][] = ['label' => 'Paramètres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parametres-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editer', ['update', 'id' => $model->parametre_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Supprimer', ['delete', 'id' => $model->parametre_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Etes-vous sûr de vouloir supprimer cet élément?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'parametre_id',
            'class_key',
            'nom',
            'valeur',
            'info_special',
        ],
    ]) ?>

</div>
