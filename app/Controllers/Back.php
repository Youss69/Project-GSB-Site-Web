<?php

namespace App\Controllers;

class Back extends BaseController
{
    public function Verif() {
        $session = session();

        include "../app/Views/fonction-page-accueil.php";
        include "../app/Views/config-page-accueil.php";
        $code_v = $_POST['codeV'];
        $bd = GETPDO($config);
        $code_a=  $bd->prepare('SELECT code FROM `authentification` WHERE `authentification`.`code` = ? AND 
        `authentification`.`identifiant` = ?');
        $code_a->execute(array($code_v, $_SESSION['identifiant']));
        $fetch = $code_a->fetch();
        if ($code_a->rowCount() == 1 && $fetch['code'] != 0) {
            $delete = $bd->prepare('UPDATE `authentification` SET `code` = "0" WHERE `authentification`.`code` = :code');
            $delete->bindValue(':code', $code_v);
            $delete->execute();
            $act = $bd->prepare('UPDATE `authentification` SET `Activation3` = "1" WHERE `authentification`.`identifiant` = ?');
            $act->execute(array($_SESSION['identifiant']));
            return redirect()->to("/Front/index");
        }
        else {
            return redirect()->to("/Front/inscription");
        }
    }
    function CompteNonActif() {
        echo "<script type=\"text/javascript\">
        window.alert ('Identifiant et/ou Mot de passe incorrect'); 
        window.location='../../index.php'; </script>";


    }
    public function Activation($idTableau) {
        include "../app/Views/fonction-page-accueil.php";
        include "../app/Views/config-page-accueil.php";
        $session = session();
        if ($_SESSION['activé'][$idTableau] == TRUE) {
        
        $insert = $_SESSION['id_bdd'][$idTableau];
        $bd = GETPDO($config);
        $activation=  $bd->prepare('UPDATE `authentification` SET `Activation3` = "1" WHERE `authentification`.`id` = :id');
        $activation->bindValue(':id', $insert);
        $activation->execute();
        return redirect()->to("/Front/droit");
        }
        else {
            $insert = $_SESSION['id_bdd'][$idTableau];
            $bd = GETPDO($config);
            $activation=  $bd->prepare('UPDATE `authentification` SET `Activation3` = "0" WHERE `authentification`.`id` = :id');
            $activation->bindValue(':id', $insert);
            $activation->execute();
            return redirect()->to("/Front/droit");
        }
        
    }


    public function Supprimer($idFicheFrais) {

        $session = session();
        $insert = $_SESSION['fichefrais'][$idFicheFrais];

        include "../app/Views/fonction-page-accueil.php";
        include "../app/Views/config-page-accueil.php";

        $bd = GETPDO($config);
        $suppresion=  $bd->prepare('DELETE FROM fichefrais WHERE `fichefrais`.`id` = :id');
        $suppresion->bindValue(':id', $insert);
        $suppresion->execute();
        return redirect()->to("/Front/noteDeFrais");
    }

    public function confirmationConnexion() {

        
        $session = session();
            include "../app/Views/fonction-page-accueil.php";
            include "../app/Views/config-page-accueil.php";
            $_SESSION['connecté'] = FALSE;


            # Première méthode 
            $var_recup = GETPDO($config);
            $var_recup_ex = $var_recup->prepare("SELECT `id`, `identifiant`, `motDePasse` FROM authentification");
            $var_recup_ex->execute();
            $données_verif = $var_recup_ex->fetchAll();

            # Deuxième méthode 
            $X = filter_var($_POST['identifiant-co'], FILTER_SANITIZE_STRING);
            $Y = filter_var($_POST['mdp-co'], FILTER_SANITIZE_STRING);
            $recup_user = $var_recup->prepare('SELECT * FROM authentification WHERE identifiant = ? AND motDePasse = ? ');
            $recup_user->execute(array($X, $Y)); 
            

            # Assimilation de données aux variables de sessions
            $_SESSION['idd'] = $X;
            
            $q = $var_recup->prepare('SELECT * FROM authentification WHERE identifiant = :identifiant');
            $q->bindValue('identifiant', $X);
            $q->execute();
            $res = $q->fetch();

            //var_dump($res);
           

            $recup_user2 = $var_recup->prepare('SELECT * FROM authentification WHERE `identifiant` = :identifiant');
            $recup_user2->bindValue('identifiant', $X);
            $recup_user2->execute();
            $ftch=$recup_user2->fetch();
            $_SESSION['categorie_utilisateur'] = $ftch['categorie_utilisateur'];


            if (!empty($_POST['identifiant-co'] && $_POST['mdp-co'])) {
                if($res) {
                    $passwordHash = $res['motDePasse'];
                
                foreach ($données_verif as $données_verif) {

                            if (password_verify($Y, $passwordHash)) {
                                $fetch_activation= $recup_user->fetch();

                                 $res['Activation3'] = isset($res['Activation3']) ? $res['Activation3'] 
                                 : 0;

                                if ($res['Activation3'] == 1) {
                                    $_SESSION['connecté'] = TRUE;
                                    return redirect()->to("/Front/FicheFrais2");
                                }
                                else {
                                    return redirect()->to("/Back/CompteNonActif");
                                   
                                }
                                
                                      
                            } 
                            else {
                                #header('location:page-inscription.php');
                                echo "<script type=\"text/javascript\">window.alert ('Identifiant et/ou Mot de passe incorrect'); 
                                window.location='../index.php'; </script>";
                                $_SESSION['connecté'] = FALSE;
                            } 
                }
            }
            else {
                echo "<script type=\"text/javascript\">window.alert ('Identifiant et/ou Mot de passe incorrect'); 
                window.location='../index.php'; </script>";
                $_SESSION['connecté'] = FALSE;
            }

            } 
            else {
                return view("confirmation-connexion.php");
                }
            }

    public function pageInscription() {
        $session = session();

            $nom_utilisateur = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
            $prenom_utilisateur = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
            $mail_utilisateur = filter_var($_POST['mail'], FILTER_SANITIZE_STRING);
            $identifiant_utilisateur = filter_var($_POST['identifiant'], FILTER_SANITIZE_STRING);
            $mdp_utilisateur = filter_var($_POST['mdp'], FILTER_SANITIZE_STRING);
            $categorie_utilisateur = filter_var($_POST['categorie_utilisateur'], FILTER_SANITIZE_STRING);
            $random = rand(100000,999999);

            // hachage du mot de passe
            $mdp_utilisateur = password_hash($mdp_utilisateur, PASSWORD_DEFAULT);

            include "../app/Views/fonction-page-accueil.php";

            include "../app/Views/config-page-accueil.php";
            $_SESSION['identifiant'] = $identifiant_utilisateur;
            /* $_SESSION['nom'] = $nom_utilisateur;
            $_SESSION['prenom'] = $prenom_utilisateur; */

            $connexion = GETPDO($config);
                if (!empty($identifiant_utilisateur) AND !empty($mdp_utilisateur
                AND !empty($nom_utilisateur) AND !empty($mail_utilisateur) AND !empty($prenom_utilisateur))){

                    //  méthode 1 pour vérifier dans la bdd qu'un utilisateur ne porte pas le même identifiant 

                    $recup_user = $connexion->prepare('SELECT * FROM authentification WHERE `identifiant` = :identifiant');
                    $recup_user->bindValue('identifiant', $identifiant_utilisateur);
                    $recup_user->execute();
                    if ($recup_user->rowCount() != 0) {
                        return redirect()->to("/Front/erreurIdentifiant");
                    }

                    //  méthode 2 pour vérifier dans la bdd qu'un utilisateur ne porte pas le même identifiant 

                    $recup_user = $connexion->query('SELECT * FROM authentification ');
                    $idUnique = $recup_user->fetchAll();
                    foreach ($idUnique as $i) {
                        if ($i['identifiant'] === $identifiant_utilisateur) {
                            return redirect()->to("/Front/erreurIdentifiant");
                        }
                        
                    }
                // Insertion au sein de la table authentification
                
                $insérer = $connexion->prepare('INSERT INTO authentification 
                (identifiant, motDePasse, nom, prenom, mail, categorie_utilisateur, code) VALUES (? , ? , ? , ? , ?, ?, ?)');

                $insérer->execute(array($identifiant_utilisateur, $mdp_utilisateur, $nom_utilisateur, 
                $prenom_utilisateur, $mail_utilisateur, $categorie_utilisateur, $random));

                // Insertion au sein de la table categorie_users

                $insérer2 = $connexion->prepare('INSERT INTO categorie_users 
                (categorie_utilisateur, id_authentification) VALUES (? , ?)');
                
                $req = $connexion->prepare("SELECT * FROM authentification WHERE identifiant=?");
                $req->bindValue('identifiant', $identifiant_utilisateur);
                $req->execute(array($identifiant_utilisateur));
                $id_bdd = $req->fetch();

                $insérer2->execute(array($categorie_utilisateur, $id_bdd['id']));

                return redirect()->to("/Front/code_validation/");
                }
                else {
                        return view("page-inscription.php");
                    }
                
    
            }
           
    public function frais() {

        $session = session();
        
        include_once ("../app/Views/fonction-frais.php");
        include_once ("../app/Views/config-frais.php");

        
            if (isset($_SESSION['idd'])) {

                if ($_SESSION['connecté'] == TRUE) {

                    $reponse = GETPDO($config);

                    $execution = $reponse->query('SELECT `id`, `identifiant` FROM authentification');

                    $execution->execute();

                    $fetch = $execution->fetchAll();

                    foreach($fetch as $fetch) {

                        if ($_SESSION['idd'] === $fetch['identifiant']) {
                            
                            $numéro_id = $fetch['id'];

                            
                    }

                    $nombre_km = isset($_POST['nbr_km']) ?$_POST['nbr_km'] : null;
                    $nombre_km = filter_var($nombre_km, FILTER_SANITIZE_STRING);

                    $coutkm = isset ($_POST['cout_km']) ?$_POST['cout_km'] : null;
                    $coutkm = filter_var($coutkm, FILTER_SANITIZE_STRING);

                    $restau = isset($_POST['Restauration']) ?$_POST['Restauration'] : null;
                    $restau = filter_var($restau, FILTER_SANITIZE_STRING);

                    $htl = isset ($_POST['hôtel']) ?$_POST['hôtel'] : null;
                    $htl = filter_var($htl, FILTER_SANITIZE_STRING);

                    $event = isset($_POST['Evènementiel']) ?$_POST['Evènementiel'] : null;
                    $event = filter_var($event, FILTER_SANITIZE_STRING);

                        $frais = GETPDO($config);
                        if (!empty($nombre_km) and !empty($restau) and !empty($htl) and !empty($event))
                        {

                            $insérer = $frais->prepare('INSERT INTO fichefrais (nbr_km, cout_km, restauration, hotel, evenementiel,
                            id_authentification	) VALUES (? , ? , ? , ? , ?, ?)');

                        $resql = $insérer->execute(array($nombre_km, $coutkm, $restau, $htl, $event, $numéro_id));
                         /*var_dump($insérer->errorInfo());

                        echo "Données retourné"; */
                        #header('Location: http://localhost:3000/app/Views/FicheFrais2.php');
                        return redirect()->to("/Front/noteDeFrais");
                        /*$data = array('user_idd' => $session->get("idd"), 'connected'=> $session->get("connecté"));
                        return view("FicheFrais2.php", $data); */
                      
                        #return view("noteDeFrais.php");
                        }

                        else {
                            return view("FicheFrais2.php");
                        }
                        
                    }
                }

                else {
                    echo "<script type=\"text/javascript\">window.alert ('Vous devez être connecté pour envoyez vos fiche de frais'); 
        window.location='/Front/FicheFrais2'; </script>";
                }

        }
    

    }


    public function fraisStatusUpdate() {
        include "../app/Views/fonction-page-accueil.php";
        include "../app/Views/config-page-accueil.php";
        $session = session();
        if ($_SESSION['connecté'] == TRUE) {
            $bd = GETPDO($config);
            $elementId = $this->request->getVar('element_id');
            $categorie_fiche = $this->request->getVar('categorie_fiche');
            $activation=  $bd->prepare(
                'UPDATE `fichefrais` SET `categorie_fiche` = :categorie_fiche WHERE `fichefrais`.`id` = :id');
            $activation->bindValue(':id', $elementId);
            $activation->bindValue(':categorie_fiche', $categorie_fiche);
            $activation->execute();
        }
        return redirect()->to("/Front/comptable"); 
    }

}