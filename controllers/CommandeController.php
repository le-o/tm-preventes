<?php

namespace app\controllers;

use Yii;
use app\models\Personnes;
use app\models\PersonnesSearch;
use app\models\Commande;
use app\models\CommandeSearch;
use app\models\DetailCommande;
use app\models\TypeAbonnement;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Model;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * CommandeController implements the CRUD actions for Commande model.
 */
class CommandeController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'view', 'closed', 'fillabo'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return (Yii::$app->user->identity->username == 'CA') ? false : true;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Commande models.
     * @return mixed
     */
    public function actionIndex()
    {
        $queryParams = Yii::$app->request->queryParams;
        if (!isset($queryParams['CommandeSearch'])) $queryParams['CommandeSearch']['date_commande'] = 2016;
        $searchModel = new CommandeSearch();
        $dataProvider = $searchModel->search($queryParams);
        
        $sommeCommande = 0;
        foreach ($dataProvider->getModels() as $data) {
            $sommeCommande += $data->getTotalCommande();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sommeCommande' => $sommeCommande,
        ]);
    }

    /**
     * Displays a single Commande model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $modelCommande = $this->findModel($id);
        $modelPersonne = $modelCommande->getFkPersonne()->one();
        $modelsDetailCommande = $modelCommande->getDetailCommandes()->all();
        
        $montant = $modelCommande->getMontantRabais($modelCommande->date_commande);
        if ($montant == 'montant_10') $texte = \app\models\Parametres::findOne(Yii::$app->params['texteFondCommande']);
        else $texte = \app\models\Parametres::findOne(Yii::$app->params['texteFondCommande15']);
        
        $this->layout = "main_1";
        return $this->render('view', [
            'modelCommande' => $modelCommande,
            'modelPersonne' => $modelPersonne,
            'modelsDetailCommande' => $modelsDetailCommande,
            'montant' => $montant,
            'texte' => $texte,
        ]);
    }

    /**
     * Creates a new Commande model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {   
        $this->layout = "main_1";
        
        if (Yii::$app->user->isGuest) {
            $ouverture = \app\models\Parametres::findOne(Yii::$app->params['dateOuverture']);
            $dateok = date(strip_tags($ouverture->valeur));
            $datefinok = date(strip_tags($ouverture->info_special));
            if (date('Y-m-d') < date('Y-m-d', strtotime($dateok)) || date('Y') < date('Y', strtotime($dateok)) || date('Y-m-d') > date('Y-m-d', strtotime($datefinok))) {
                return $this->render('closed');
            }
        }
        
        $alerte = '';
        $modelPersonne = new Personnes;
        $modelCommande = new Commande;
        $modelTypeAbonnement = new TypeAbonnement;
        $modelsDetailCommande = [new DetailCommande];
        if ($modelPersonne->load(Yii::$app->request->post())) {
            $modelsDetailCommande = Model::createMultiple(DetailCommande::classname());
            Model::loadMultiple($modelsDetailCommande, Yii::$app->request->post());
            
            $modelCommande->load(Yii::$app->request->post());

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsDetailCommande),
                    ActiveForm::validate($modelCommande),
                    ActiveForm::validate($modelPersonne)
                );
            }

            if (!DetailCommande::validateFamille($modelsDetailCommande)) {
                $alerte = 'Abonnement famille non valide (min. 3 pers - max. 2 adultes - même type)';
            } else {
                // valide 1a personne, le reste est construit par la suite
                $valid = $modelPersonne->validate();

                // trouve si la personne existe déjà dans la bdd
                $existe = Personnes::find()
                    ->where('nom LIKE \''.$modelPersonne->nom.'%\'')
                    ->andWhere('prenom LIKE \''.$modelPersonne->prenom.'%\'')
                    ->andWhere('email LIKE \''.$modelPersonne->email.'%\'')
                    ->one();
                $update = false;
                if (!empty($existe)) {
                    $existe->boite_postale = $modelPersonne->boite_postale;
                    $existe->rue = $modelPersonne->rue;
                    $existe->npa = $modelPersonne->npa;
                    $existe->localite = $modelPersonne->localite;
                    $existe->email = $modelPersonne->email;
                    $existe->telephone = $modelPersonne->telephone;
                    $modelPersonne->personne_id = $existe->personne_id;
                    $update = true;
                }
            
                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($update) {
                            $existe->update(false);
                            $flag = true;
                        } else $flag = $modelPersonne->save(false);

                        if ($flag) {
                            $modelCommande->fk_personne = $modelPersonne->personne_id;
                            $modelCommande->etat_commande = 1;
                            $modelCommande->date_commande = date('Y-m-d H:i:s');
                            $montantR = $modelCommande->getMontantRabais($modelCommande->date_commande);
                            if ($flag = $modelCommande->save(false)) {
                                foreach ($modelsDetailCommande as $modelDetail) {
                                    $modelDetail->montant = $modelDetail->fkTypeAbonnement->$montantR;
                                    
                                    $modelDetail->fk_commande = $modelCommande->commande_id;
                                    if (! ($flag = $modelDetail->save(false))) {
                                        $transaction->rollBack();
                                        break;
                                    }
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();

                            $contenu = \app\models\Parametres::findOne(Yii::$app->params['texteEmailAuto']);

                            // tout est ok, on envoi l'email
                            Yii::$app->mailer->compose()
                                ->setFrom('administration@telemarecottes.ch')
                                ->setTo($modelPersonne->email)
                                ->setSubject('Confirmation de commande')
                                ->setHtmlBody($contenu->valeur)
                                ->send();
                            return $this->redirect(['commande/view', 'id' => $modelCommande->commande_id]);
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }
            }
        }
        
        return $this->render('create', [
            'alerte' => $alerte,
            'modelPersonne' => $modelPersonne,
            'modelCommande' => $modelCommande,
            'modelTypeAbonnement' => $modelTypeAbonnement,
            'modelsDetailCommande' => (empty($modelsDetailCommande)) ? [new DetailCommande] : $modelsDetailCommande
        ]);
    }

    /**
     * Updates an existing Commande model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $alerte = '';
        $modelCommande = $this->findModel($id);
        $modelPersonne = Personnes::findOne($modelCommande->fk_personne);
        $modelTypeAbonnement = new TypeAbonnement;
        $modelsDetailCommande = $modelCommande->detailCommandes;
        
        if ($modelPersonne->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelsDetailCommande, 'detail_commande_id', 'detail_commande_id');
            $modelsDetailCommande = Model::createMultiple(DetailCommande::classname(), $modelsDetailCommande, 'detail_commande_id');
            Model::loadMultiple($modelsDetailCommande, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsDetailCommande, 'detail_commande_id', 'detail_commande_id')));
            
            $modelCommande->load(Yii::$app->request->post());

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsDetailCommande),
                    ActiveForm::validate($modelCommande),
                    ActiveForm::validate($modelPersonne)
                );
            }
            
            if (!DetailCommande::validateFamille($modelsDetailCommande)) {
                $alerte = 'Abonnement famille non valide (min. 3 pers - max. 2 adultes - même type)';
            } else {
                // valide 1a personne, le reste est construit par la suite
                $valid = $modelPersonne->validate();

                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $modelPersonne->save(false)) {
                            if ($flag = $modelCommande->save(false)) {
                                if (!empty($deletedIDs)) {
                                    DetailCommande::deleteAll(['detail_commande_id' => $deletedIDs]);
                                }
                                $montantR = $modelCommande->getMontantRabais($modelCommande->date_commande);
                                foreach ($modelsDetailCommande as $modelDetail) {
                                    $modelDetail->montant = $modelDetail->fkTypeAbonnement->$montantR;
                                    
                                    $modelDetail->fk_commande = $modelCommande->commande_id;
                                    $flag = $modelDetail->save(false) && $flag;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return $this->redirect(['commande/index']);
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }
            }
        }
        
        return $this->render('update', [
            'alerte' => $alerte,
            'modelPersonne' => $modelPersonne,
            'modelCommande' => $modelCommande,
            'modelTypeAbonnement' => $modelTypeAbonnement,
            'modelsDetailCommande' => $modelsDetailCommande
        ]);
    }
    
    /**
     * Creates a new Commande model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEnvrac()
    {   
        $alerte = '';
        $modelCommande = $this->prerequisEnVrac();
        $modelPersonne = Personnes::findOne($modelCommande->fk_personne);
        $modelTypeAbonnement = TypeAbonnement::find()->where('is_archive = 0')->all();
        
        if (!empty(Yii::$app->request->post())) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                foreach (Yii::$app->request->post() as $idDetail => $nombreAbo) {
                    $modelDetail = DetailCommande::findOne($idDetail);
                    if ($modelDetail != '') {
                        $modelDetail->nom_carte = $nombreAbo;
                        $modelDetail->save(false);
                    }
                }
                $transaction->commit();
                $alerte = 'Enregistrement effectué avec succès !';
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        
        $modelsDetailCommande = DetailCommande::find()->where('fk_commande = '.$modelCommande->commande_id)->all();
        
        return $this->render('envrac', [
            'alerte' => $alerte,
            'modelPersonne' => $modelPersonne,
            'modelCommande' => $modelCommande,
            'modelTypeAbonnement' => $modelTypeAbonnement,
            'modelsDetailCommande' => $modelsDetailCommande
        ]);
    }

    /**
     * Deletes an existing Commande model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $modelCommande = $this->findModel($id);
        $modelsDetailCommande = $modelCommande->detailCommandes;
        
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($modelsDetailCommande as $detail) {
                $detail->delete();
            }
            $modelCommande->delete();
            
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Commande model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Commande the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Commande::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Remplissage dynamique de la liste déroulante des abonnements
     * 
     * @param type $dateRef
     */
    public function actionFillabo($dateRef, $selector)
    {   
        $commande = new Commande;
        $montant = $commande->getMontantRabais();
        
        $options = '<option value="">Choisir un abonnement</option>';
        $types = TypeAbonnement::find()->where(['and', date('Y', strtotime($dateRef)).' BETWEEN an_debut AND an_fin', 'is_archive = 0'])->orderBy('tri')->all();
        if(count($types) > 0){
            foreach($types as $type){
                $options .= "<option value='".$type->type_abonnement_id."'>".$type->nom.' - CHF '.$type->$montant."</option>";
            }
        } else {
            $options .= "<option>-</option>";
        }
        
        $ref = explode('-', $selector);
        echo json_encode(['selector' => 'detailcommande-'.$ref[1].'-fk_type_abonnement', 'options' => $options]);
    }
    
    /**
     * Création des pré-requis annuel pour la génération en vrac des abonnements
     * vendu aux caisses
     * 
     * @return object Commande
     */
    private function prerequisEnVrac()
    {
        $modelCommande = Commande::find()->where('YEAR(date_commande) = '.date('Y').' AND fk_personne = '.Yii::$app->params['adresseTele'])->one();
        if ($modelCommande == '') {
            $modelCommande = new Commande;
            $modelCommande->fk_personne = Yii::$app->params['adresseTele'];
            $modelCommande->etat_commande = 1;
            $modelCommande->date_commande = date('Y-m-d H:i:s');
            $modelCommande->message = 'Commande générique pour insertion en vrac uniquement!';
            $modelCommande->save(false);
        }
        $modelTypeAbonnement = TypeAbonnement::find()->where('is_archive = 0')->all();
        $nbrDetail = DetailCommande::find()->where('fk_commande = '.$modelCommande->commande_id)->count();
        if (count($modelTypeAbonnement) != $nbrDetail) {
            foreach ($modelTypeAbonnement as $type) {
                $detail = DetailCommande::find()->where('fk_commande = '.$modelCommande->commande_id.' AND fk_type_abonnement = '.$type->type_abonnement_id)->one();
                if ($detail == '') {
                    $detail = new DetailCommande;
                    $detail->fk_commande = $modelCommande->commande_id;
                    $detail->fk_type_abonnement = $type->type_abonnement_id;
                    $detail->nom_carte = 0;
                    $detail->montant = $detail->fkTypeAbonnement->montant_10;
                    $detail->save(false);
                }
            }
        }
        return $modelCommande;
    }
    
}
