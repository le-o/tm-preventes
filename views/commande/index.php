<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CommandeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Commandes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="commande-index">

    <h1><?= Html::encode($this->title) ?> 
        <?= Html::a('Créer une commande', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('En vrac', ['envrac'], ['class' => 'btn btn-primary']) ?>
    </h1>

    <div>
    <?php
    // Renders a export dropdown menu
    echo ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'commande_id',
            [
                'label' => 'Nom',
                'value' => function ($data) {
                    return $data->fkPersonne->nom;
                }
            ],
            [
                'label' => 'Prénom',
                'value' => function ($data) {
                    return $data->fkPersonne->prenom;
                },
            ],
            [
                'label' => 'Rue',
                'value' => function ($data) {
                    return $data->fkPersonne->rue;
                },
            ],
            [
                'label' => 'Boite postale',
                'value' => function ($data) {
                    return $data->fkPersonne->boite_postale;
                },
            ],
            [
                'label' => 'NPA',
                'value' => function ($data) {
                    return $data->fkPersonne->npa;
                },
            ],
            [
                'label' => 'Localité',
                'value' => function ($data) {
                    return $data->fkPersonne->localite;
                },
            ],
            [
                'label' => 'Email',
                'value' => function ($data) {
                    return $data->fkPersonne->email;
                },
            ],
        ],
        'fontAwesome' => true,
        'target' => ExportMenu::TARGET_SELF,
        'showConfirmAlert' => false,
        'showColumnSelector' => false,
        'noExportColumns' => [5,6,7],
        'dropdownOptions' => [
            'class' => 'btn btn-default',
            'label' => 'Export tous',
        ],
        'exportConfig' => [
            ExportMenu::FORMAT_HTML => false,
            ExportMenu::FORMAT_TEXT => false,
            ExportMenu::FORMAT_PDF => false,
            ExportMenu::FORMAT_EXCEL_X => false,
        ]
    ]);
    ?>
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>

    <?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model, $index, $widget, $grid){
            switch ($model->etat_commande) {
                case 0: return ['class' => 'danger']; break;
                case 1: return ['class' => 'warning']; break;
                case 2: return ['class' => 'info']; break;
                case 3: return ['class' => 'success']; break;
                case 4: return ['class' => 'primary']; break;
                default: return [];
            }
          },
        'filterModel' => $searchModel,
        'showFooter'=>true,
        'export'=>false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'commande_id',
            [
                'attribute' => 'fkPersonne',
                'value' => function ($data) {
                    return $data->fkPersonne->nom.' '.$data->fkPersonne->prenom;
                }
            ],
            [
                'attribute' => 'etat_commande',
                'value' => function ($data) {
                    $etats = $data->optsEtat();
                    return $etats[$data->etat_commande];
                }
            ],
            'date_commande',
            [
                'label' => 'Message',
                'format' => 'raw',
                'value'=>function ($data) {
                    return (strlen($data->message) > 100) ? substr($data->message, 0, 100).'...' : $data->message;
                }
            ],
            [
                'label' => 'Nombre abo',
                'value' => function ($data) {
                    return count($data->detailCommandes);
                }
            ],
            [
                'label' => 'Montant CHF',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getTotalCommande().'.00';
                },
                'footer' => '<div class="pull-right"><strong>'.$sommeCommande.'.00</strong></div>',
                'contentOptions'=>['style'=>'text-align:right; min-width:80px;']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
