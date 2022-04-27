<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class EmailController extends BaseController 
{// Pour que ce controller marche entièrement, le ficher config\Email.php doit être reconfiguré

    public function __construct() { // Cette fonction set à autoriser les set_value();
        helper(['url', 'form']); 
    }
 

    public function send() { // Cette fonction à pour but dans un premier temps de vérifier les données d'un formulaire
                             // (que chaque champ sois remplit et l'emaiil soit valide) puis d'envoyer un mail automatique

        $validation = $this->validate([
            'name' => 'required',
            'email' => 'required|valid_email',
            'subject' => 'required',
            'message' => 'required',
        ]);
        if (!$validation) {
        return view('mailTest', ['validation' =>$this->validator]);
        }
        else {
            if ($this->isOnline()) {
                $to = "come.bonal@gmail.com";
                $subject = $this->request->getVar('subject'); // récupère la variable $message avec la méthode POO
                $message = $this->message->getVar('message'); // selon moi la variable entre () est celle du name ou
                                                            // de la value d'un input du fichier html précédant

                $email = \Config\Services::email();

                $email->setTo($to);
                $email->setFrom($this->request->getVar('email'), $this->request->getVar('name'));
                $email->setSubject($subject);
                $email->setMessage($message);

                if ($email->send()) {
                    return redirect()->to('/contact')->with('Success', 'Email has been sent sucessfulyl')->withInput();
                }
                else {
                    return redirect()->to('/contact')->with('error', 'Email has not been sent')->withInput();
                }
            }
            else {
                return redirect()->to('/contact')->with('error', 'Check your internet Connection')->withInput();
            }
        }
    }
    public function isOnline($site = "https://youtube.com") { // vérifier que nous sommes connecter à internet
        if (@fopen($site, "r")) { // si l'url par défaut peut être ouverte, en "lecture seule" = "r"
            return true;
        }
        else {
            return false;
        }
    }

    public function envoieMail($identifiant_utilisateur) {
        // Cette fonction à pour but d'envoyer un mail à toutes les adresse mail classifié "admin' dans la bdd

        include "../app/Views/fonction-page-accueil.php";

        include "../app/Views/config-page-accueil.php";

        try {
        if ($this->isOnline()) {

        $connexion = GETPDO($config);

        $req_admin = $connexion->query("SELECT DISTINCT mail FROM authentification WHERE categorie_utilisateur = 'Administrateur'");
               
                $to = $req_admin->fetchAll();
                $subject = "Code de validation nouvelle inscription";
                $message = "Bonjour, " . $identifiant_utilisateur ." s'est récemment inscrit, veuillez lui fournir son code de vérification : " . $_SESSION['random'];
                $header = "Content-Type: text/plain; charset=utf-8\r\n"; // pour prendre en compte tous les caractères de toutes les langues
                
                    foreach ($to as $key) {
                        $email = \Config\Services::email();

                        $email->setTo($key['mail']);
                        $email->setFrom("come.bonal@gmail.com");
                        $email->setSubject($subject);
                        $email->setMessage($message);
                        $email->send();
                    
                    }
                
                 session_destroy();
                return redirect()->to("/Front/code_validation/");
            }
            else {
                return redirect()->to('/Front/inscription/')->with('error', 'Check your internet Connection')->withInput();
            }

        } catch (\Throwable $th) {
            echo "erreur : " . $th->getMessage();
        }         
            
    }
}
