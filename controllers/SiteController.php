<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex($dateref = null)
    {
        if (\Yii::$app->user->isGuest) {
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->goBack();
            }
            return $this->render('login', [
                'model' => $model,
            ]);
        }
        
        if ($dateref === null) $dateref = date('Y');
        
        $regroupement = array('sénior', 'adulte', 'jeune', 'enfant', 'apprenti', 'semaine', 'piéton');
        
        $detailsCommande = \app\models\DetailCommande::find()->all();
        foreach ($detailsCommande as $detail) {
            if (date('Y', strtotime($detail->fkCommande->date_commande)) == $dateref || $dateref == '') {
                if (!isset($genres[$detail->getFkTypeAbonnement()->one()->nom])) $genres[$detail->getFkTypeAbonnement()->one()->nom] = 0;
                if (!isset($prix[$detail->getFkTypeAbonnement()->one()->nom])) $prix[$detail->getFkTypeAbonnement()->one()->nom] = 0;
                if (is_numeric($detail->nom_carte)) {
                    $genres[$detail->getFkTypeAbonnement()->one()->nom] += $detail->nom_carte;
                    $prix[$detail->getFkTypeAbonnement()->one()->nom] += $detail->getFkTypeAbonnement()->one()->montant_10 * $detail->nom_carte;
                } else {
                    $genres[$detail->getFkTypeAbonnement()->one()->nom]++;
                    $prix[$detail->getFkTypeAbonnement()->one()->nom] += $detail->getFkTypeAbonnement()->one()->montant_10;
                }

                foreach ($regroupement as $type) {
                    if (strstr(strtolower($detail->getFkTypeAbonnement()->one()->nom), $type) !== false) {
                        if (strstr(strtolower($detail->getFkTypeAbonnement()->one()->nom), 'famille') !== false) {
                            if (!isset($types['Famille '.$type])) $types['Famille '.$type] = 0;
                            if (is_numeric($detail->nom_carte)) {
                                $types['Famille '.$type] += $detail->nom_carte;
                            } else {
                                $types['Famille '.$type]++;
                            }
                        } else {
                            if (!isset($types[$type])) $types[$type] = 0;
                            if (is_numeric($detail->nom_carte)) {
                                $types[$type] += $detail->nom_carte;
                            } else {
                                $types[$type]++;
                            }
                        }
                    }
                }
            }
        }
        
        $statsGenre[] = array('Tâches', 'Nombre par genre');
        foreach ($genres as $key => $value) {
            $statsGenre[] = array($key, $value);
        }
        $statsType[] = array('Tâches', 'Nombre', array('role' => 'annotation'));
        $totalNombre = 0;
        foreach ($types as $key => $value) {
            $statsType[] = array($key, $value, $value);
            $totalNombre += $value;
        }
        $statsPrix[] = array('Tâches', 'CHF par genre', array('role' => 'annotation'));
        $totalPrix = 0;
        foreach ($prix as $key => $value) {
            $statsPrix[] = array($key, $value, $value);
            $totalPrix += $value;
        }
        
        return $this->render('index', [
            'statsGenre' => $statsGenre,
            'statsType' => $statsType,
            'totalNombre' => $totalNombre,
            'statsPrix' => $statsPrix,
            'totalPrix' => $totalPrix,
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
