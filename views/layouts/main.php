<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'TéléMarécottes S.A.',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Accueil', 'url' => ['/site/index']],
            !Yii::$app->user->isGuest && Yii::$app->user->identity->username != 'CA' ?
                [
                    'label' => 'Commandes',
                    'url' => ['/commande'],
                ] : '',
            !Yii::$app->user->isGuest && Yii::$app->user->identity->username != 'CA' ?
                ['label' => 'Paramétrages',
                    'items' => [
                         ['label' => 'Gestion des abos', 'url' => ['/type-abonnement']],
                         ['label' => 'Gestion des codes', 'url' => ['/parametres']],
                    ],
                ] : '',
            Yii::$app->user->isGuest ?
                ['label' => 'Se connecter', 'url' => ['/site/login']] :
                [
                    'label' => 'Se déconnecter (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; TéléMarécottes S.A. <?= date('Y') ?></p>

        <p class="pull-right" style="width:250px;"><?= Yii::powered() ?> & <a href="http://www.d-web.ch" target="_blank">d-web.ch</a></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
