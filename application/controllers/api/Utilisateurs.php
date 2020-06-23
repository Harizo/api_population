<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/PHPMailer/PHPMailerAutoload.php';

class Utilisateurs extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('utilisateurs_model', 'UserManager');

        $this->load->model('region_model', 'RegionManager');
        $this->load->model('privilege_groupe_model', 'Privilege_groupeManager');
    }
    public function index_get() {

        set_time_limit(0);
        ini_set ('memory_limit', '2048M');
        //find by id
        $id = $this->get('id');
        $enabled = $this->get('enabled');
        $tab_groupe = $this->get('tab_groupe');

        //authentification
        $email = $this->get('email');
        $pwd = sha1($this->get('pwd'));
        $site = $this->get('site');
        //authentification

        // Methode get et à la fois post
        $id_utilisateur = $this->get('id_utilisateur');
        $confirm_pwd = $this->get('conf_pwd');

        //get par email
        $fndmail = $this->get('courriel');
        $fndmdp = $this->get('mdp');

        //REINIT PWD 
        $courriel = $this->get('courriel_reset');
        $reinitpwd = sha1($this->get('reinitpwd'));
        $reinitpwdtoken = $this->get('reinitpwdtoken');


        $email_connection = $this->get('email_connection');
        $test_connection = $this->get('test_connection');


        if ($id) 
        {
            $user = $this->UserManager->findById($id);
            if ($user) 
            {
                $data['id'] = $user->id;
                $data['nom'] = $user->nom;
                $data['prenom'] = $user->prenom;
                $data['raison_sociale'] = $user->raison_sociale;
                $data['token'] = $user->token;
                $data['email'] = $user->email;
                $data['enabled'] = $user->enabled;      
                //$data['roles'] = unserialize($user->roles);

                $data['groupes'] = unserialize($user->roles);

                $data['roles'] =array();

                foreach ($data['groupes'] as $k => $v) 
                {
                    $tmp = $this->Privilege_groupeManager->findBygroupe($v);

                    $privileges = unserialize($tmp[0]->privileges) ;

                    $data['roles'] = array_unique(array_merge($data['roles'], $privileges)) ;
                }
            }  
            else 
            {
                $data = array();
            }                               
        } 
        else 
        {
            if ($enabled == 1) 
            {
				// Récupération par actif ou inactif
                $nbr = 0 ;
                $user = $this->UserManager->findAllByEnabled(0);
                if ($user) 
                {
                    foreach ($user as $key => $value) 
                    {
                        $nbr++ ;
                    }
                }               
                $data = $nbr;
            } 
            else 
            {
				if ($tab_groupe) //recuperation des acces menu et sous menu 
                {
                    $data = array();
                    $tab = explode(",",$tab_groupe);
                    foreach ($tab as $k => $v) 
                    {
                        $tmp = $this->Privilege_groupeManager->findBygroupe($v);

                        $privileges = unserialize($tmp[0]->privileges) ;

                        $data = array_unique(array_merge($data, $privileges)) ;
                    }
                }
                else
                {
                    
                    
                    if ($email && $pwd) 
                    {
                        $value = $this->UserManager->sign_in($email, $pwd);
                        if ($value) 
                        {
                            $data = array();
                            $data['etat_connexion'] = $value[0]->etat_connexion;
                            $data['id'] = $value[0]->id;
                            $data['nom'] = $value[0]->nom;
                            $data['prenom'] = $value[0]->prenom;
                            $data['token'] = $value[0]->token;
                            $data['email'] = $value[0]->email;
                            $data['enabled'] = $value[0]->enabled;         
                            $data['default_password'] = $value[0]->default_password;         
                           // $data['roles'] = unserialize($value[0]->roles);

                            $data['groupes'] = unserialize($value[0]->roles);

                            $data['roles'] =array();

                            foreach ($data['groupes'] as $k => $v) 
                            {
                                $tmp = $this->Privilege_groupeManager->findBygroupe($v);

                                $privileges = unserialize($tmp[0]->privileges) ;

                                $data['roles'] = array_unique(array_merge($data['roles'], $privileges)) ;
                            }
                        }
                        else
                        {
                            $data = array();
                        }
                    }
                    else if ($confirm_pwd && $id_utilisateur) 
                    {
                        $data = array(
                            'password' => sha1($confirm_pwd),
                            'default_password' => 0,
                        );
                        $value = $this->UserManager->first_login($data,$id_utilisateur);
                        if ($value) {
                            $data = array();
                            $data['id'] = $value->id;
                            $data['nom'] = $value->nom;
                            $data['prenom'] = $value->prenom;
                            $data['token'] = $value->token;
                            $data['email'] = $value->email;
                            $data['enabled'] = $value->enabled;         
                            $data['default_password'] = $value->default_password;         
                           // $data['roles'] = unserialize($value->roles);

                            $data['groupes'] = unserialize($value->roles);

                            $data['roles'] =array();

                            foreach ($data['groupes'] as $k => $v) 
                            {
                                $tmp = $this->Privilege_groupeManager->findBygroupe($v);

                                $privileges = unserialize($tmp[0]->privileges) ;

                                $data['roles'] = array_unique(array_merge($data['roles'], $privileges)) ;
                            }

                        }else{
                            $data = array();
                        }
                    }
                    else if ($fndmail) 
                    {
                        $data = $this->UserManager->findByMail($fndmail);
                        if (!$data)
                            $data = array();
                    }
                    else if ($fndmdp) 
                    {
                        $data = $this->UserManager->findByPassword($fndmdp);
                        if (!$data)
                            $data = array();
                    }

                    //else if ($courriel && $reinitpwd && $reinitpwdtoken) {
                    else if ($reinitpwd && $reinitpwdtoken) {
                        $data = $this->UserManager->reinitpwd($reinitpwd, $reinitpwdtoken);
                        if (!$data)
                            $data = array();
                    }
                    else
                    {
                        if ($test_connection == 1 && $email_connection) 
                        {
                            $data = $this->UserManager->test_connection($email_connection);
                        }
                        else
                        {
                            $usr = $this->UserManager->findAll();
                            if ($usr) 
                            {
                                foreach ($usr as $key => $value)  
                                {
                                    $data[$key]['etat_connexion'] = $value->etat_connexion;
                                    $data[$key]['id'] = $value->id;
                                    $data[$key]['nom'] = $value->nom;
                                    $data[$key]['prenom'] = $value->prenom;
                                    $data[$key]['password'] = $value->password;
                                    $data[$key]['default_password'] = $value->default_password;
                                    $data[$key]['token'] = $value->token;
                                    $data[$key]['email'] = $value->email;
                                    $data[$key]['enabled'] = $value->enabled;                  
                                    $data[$key]['groupes'] = unserialize($value->roles);

                                    $data[$key]['roles'] =array();

                                    foreach ($data[$key]['groupes'] as $k => $v) 
                                    {
                                        $tmp = $this->Privilege_groupeManager->findBygroupe($v);

                                        $privileges = unserialize($tmp[0]->privileges) ;

                                        $data[$key]['roles'] = array_unique(array_merge($data[$key]['roles'], $privileges)) ;
                                    }

                                    $data[$key]['id_region'] = $value->id_region;
                                    $data[$key]['id_district'] = $value->id_district;
                                    $data[$key]['id_commune'] = $value->id_commune;
                                    $data[$key]['id_fokontany'] = $value->id_fokontany;
                                    $data[$key]['id_intervention'] = $value->id_intervention;
                                    $data[$key]['piece_identite'] = $value->piece_identite;
                                    $data[$key]['adresse'] = $value->adresse;
                                    $data[$key]['fonction'] = $value->fonction;
                                    $data[$key]['telephone'] = $value->telephone;
                                    $data[$key]['raison_sociale'] = $value->raison_sociale;
                                    $data[$key]['adresse_hote'] = $value->adresse_hote;
                                    $data[$key]['nom_responsable'] = $value->nom_responsable;
                                    $data[$key]['fonction_responsable'] = $value->fonction_responsable;
                                    $data[$key]['email_hote'] = $value->email_hote;
                                    $data[$key]['telephone_hote'] = $value->telephone_hote;
                                    $data[$key]['description_hote'] = $value->description_hote;
                                }
                            } 
                            else 
                            {
                                $data = array();
                            }
                        }
                    }
                    
                    
                }
            }                             
        }



        //status success + data
        if ($data) {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get data success',
            ], REST_Controller::HTTP_OK);
        } else  {
            $this->response([
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }
	// Sauvegarde des dnnées dans la table utilisateur
    public function index_post() {           
        $id = $this->post('id') ;
        $deconnexion = $this->post('deconnexion') ;
        $gestion_utilisateur = intval($this->post('gestion_utilisateur')) ;
        $supprimer = $this->post('supprimer') ;
        $profil = $this->post('profil') ;
		// Menu gestion utlisateur : ajout utlisateur ou mise à jour utlisateur
        if ($gestion_utilisateur == 1) 
        {
			// $supprimer =0 : veut dire ajout ou mise à jour
            if ($supprimer == 0) 
            {
				$envoyer_mail_creation_user=false;
				$message_retour="Data insert success";
				$id_region=null;
				$tmp = $this->post('id_region');
				if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
					$id_region=$tmp;
				}
				$id_district=null;
				$tmp = $this->post('id_district');
				if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
					$id_district=$tmp;
				}
				$id_commune=null;
				$tmp = $this->post('id_commune');
				if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
					$id_commune=$tmp;
				}
				$id_fokontany=null;
				$tmp = $this->post('id_fokontany');
				if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
					$id_fokontany=$tmp;
				}
				$id_intervention=null;
				$tmp = $this->post('id_intervention');
				if(isset($tmp) && $tmp !="" && intval($tmp) >0) {
					$id_intervention=$tmp;
				}
                $getrole = $this->post('roles');
				if(intval($this->post('default_password'))==0) {  
					// C'est-à-dire mot de passe par défaut déjà modifié
					// Mise à jour d'un utlisateur
					$data = array(
						'nom' => $this->post('nom'),
						'prenom' => $this->post('prenom'),         
						'email' => $this->post('email'),                 
						'enabled' => $this->post('enabled'),
                        'etat_connexion' => $this->post('etat_connexion'),
						'roles' => serialize($getrole),
						'id_region' => $id_region,
						'id_district' => $id_district,
						'id_commune' => $id_commune,
						'id_fokontany' => $id_fokontany,
						'id_intervention' => $id_intervention,	
						'piece_identite' => $this->post('piece_identite'),
						'adresse' => $this->post('adresse'),
						'fonction' => $this->post('fonction'),
						'telephone' => $this->post('telephone'),
						'raison_sociale' => $this->post('raison_sociale'),
						'adresse_hote' => $this->post('adresse_hote'),
						'nom_responsable' => $this->post('nom_responsable'),
						'fonction_responsable' => $this->post('fonction_responsable'),
						'email_hote' => $this->post('email_hote'),
						'telephone_hote' => $this->post('telephone_hote'),
						'description_hote' => $this->post('description_hote'),
					);
				} 
                else 
                {
                    $tkn = bin2hex(openssl_random_pseudo_bytes(32)) ;
					// Mot de passe par défaut : création d'un utlisateur
					$data = array(
						'nom' => $this->post('nom'),
						'prenom' => $this->post('prenom'),         
						'password' => sha1($this->post('password')),
						'default_password' => $this->post('default_password'),
                        'etat_connexion' => $this->post('etat_connexion'),
						'token' => $tkn,
						'email' => $this->post('email'),                 
						'enabled' => $this->post('enabled'),
						'roles' => serialize($getrole),
						'id_region' => $id_region,
						'id_district' => $id_district,
						'id_commune' => $id_commune,
						'id_fokontany' => $id_fokontany,
						'id_intervention' => $id_intervention,					  
						'piece_identite' => $this->post('piece_identite'),
						'adresse' => $this->post('adresse'),
						'fonction' => $this->post('fonction'),
						'telephone' => $this->post('telephone'),
						'raison_sociale' => $this->post('raison_sociale'),
						'adresse_hote' => $this->post('adresse_hote'),
						'nom_responsable' => $this->post('nom_responsable'),
						'fonction_responsable' => $this->post('fonction_responsable'),
						'email_hote' => $this->post('email_hote'),
						'telephone_hote' => $this->post('telephone_hote'),
						'description_hote' => $this->post('description_hote'),
					);	
					$envoyer_mail_creation_user=true;	
				}	
				if(intval($id) >0) {
					$dataId = $this->UserManager->update($id, $data);   
				} else {
					$dataId = $this->UserManager->add($data);  
				}	
				// Nouvel utilisateur : envoi mail vers l'utilisateur du compte crée
				if($envoyer_mail_creation_user==true) {
					$sender = "registrebeneficiaire@gmail.com";
					$mdpsender = "Registre2020";
					// DEBUT ENVOI MAIL SIGNALANT LA CREATION D'UTILISATEUR
					$nom = $this->post('nom');
					$prenom = $this->post('prenom');
                    
                    //LIEN DE L'APPLICATION

                    $adresse_serveur = "http://registrebeneficiaires.mg" ;
                    $base_url_serveur = $adresse_serveur."/2019/population/api/index.php/api" ;

                    /*$adresse_serveur = "http://localhost:3000" ;
                    $base_url_serveur = "http://localhost"."/2019/population/api/index.php/api" ;*/

                    $adr_email = $this->post('email') ;

                    $data["connexion"] = $base_url_serveur . "/mail?actif=4&courriel=" . $adr_email . "&token=" . $tkn;//activation compte par email


                    //FIN LIEN DE L'APPLICATION

					$data["nom"] = $this->post('nom');
					$data["prenom"] =$this->post('prenom');
					$data["password"] =$this->post('password');
					$adresse_mail =$this->post('email');
					$data["email"] =$this->post('email');
					$data["piece_identite"] =$this->post('piece_identite');
					$data["adresse"] =$this->post('adresse');
					$data["fonction"] =$this->post('fonction');
					$data["telephone"] =$this->post('telephone');
					$data["raison_sociale"] =$this->post('raison_sociale');
					$data["description_hote"] =$this->post('description_hote');
					$data["adresse_hote"] =$this->post('adresse_hote');
					$data["nom_responsable"] =$this->post('nom_responsable');
					$nom_responsable =$this->post('nom_responsable');
					$data["fonction_responsable"] =$this->post('fonction_responsable');
					$data["email_hote"] =$this->post('email_hote');
					$email_hote =$this->post('email_hote');
					$data["telephone_hote"] =$this->post('telephone_hote');
					$sujet = "OUVERTURE DE COMPTE UTILISATEUR : application WEB du MINISTERE DE LA POPULATION, DE LA PROTECTION SOCIALE ET DE LA PROMOTION DE LA FEMME";
					$corps = $this->load->view('mail/activation.php', $data, true);
					$mail = new PHPMailer;
					$mail->isSMTP();
					$mail->Host = 'smtp.gmail.com';
					$mail->SMTPAuth = true;
					$mail->Username = $sender;
					$mail->Password = $mdpsender;
					$mail->From = "registrebeneficiaire@gmail.com"; // adresse mail de l’expéditeur
					$mail->FromName = "Ministère de la population Malagasy"; // nom de l’expéditeur	
					$mail->addReplyTo('registrebeneficiaire@gmail.com', 'Ministère de la population');
					$mail->SMTPSecure = 'tls';
					$mail->Port = 587;
					$mail->SMTPOptions = array(
						'ssl' => array(
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true
						)
					);
					$mail->setFrom($sender,"Ministère de la population Malagasy");
					$mail->addAddress($adresse_mail,$prenom." ".$nom);
					$mail->AddCC($email_hote,$nom_responsable);
					$mail->isHTML(true);
					$mail->Subject = $sujet;
					$mail->Body = $corps;
					if (!$mail->send()) {
						$message_retour = "ERREUR";
					} else {
						$message_retour = "OK";
					}	
					// FIN ENVOI MAIL SIGNALANT LA CREATION D'UTILISATEUR					
				}
                if(!is_null($dataId))  {
                    $this->response([
                        'status' => TRUE,
                        'response' => $dataId,
                        'message' => $message_retour
                            ], REST_Controller::HTTP_OK);
                } else  {
                    $this->response([
                        'status' => TRUE,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_OK);
                }
            } 
            else 
            {
			// suppression d'un utlisateur
				$dataId = $this->UserManager->delete($id); 
                if(!is_null($dataId))  {
                    $this->response([
                        'status' => TRUE,
                        'response' => $dataId,
                        'message' => 'Delete data success'
                            ], REST_Controller::HTTP_OK);
                } else  {
                    $this->response([
                        'status' => TRUE,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_OK);
                }
            }            
        } 
        else 
        {       
            if ($profil == 1) 
            {
                $data = array(
                    'nom' => $this->post('nom'),
                    'prenom' => $this->post('prenom'),
                    'email' => $this->post('email'),
                    'password' => sha1($this->post('password'))
          
                );

                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_OK);
                }

                $dataId = $this->UserManager->update_profil($id, $data);
                if (!is_null($dataId)) {
                    $this->response([
                        'status' => TRUE,
                        'response' => $dataId
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No request found 2'
                            ], REST_Controller::HTTP_OK);
                }
            }
            else
            {
                if ($deconnexion == 1) //update etat connexion en 0
                {
                    $mail = $this->post('email') ;
                    $token = $this->post('token') ;
                    $deconnexion_login = $this->post('deconnexion_login') ;
                    
                  
                    if ($deconnexion_login == 1) 
                    {
                        if (!$mail) 
                        {
                            $this->response([
                                'status' => FALSE,
                                'response' => "Data not found",
                                'message' => 'No request found'
                                    ], REST_Controller::HTTP_OK);
                        }
                        $dataId = $this->UserManager->sign_out_login($mail);
                        if (!is_null($dataId)) {
                            $this->response([
                                'status' => TRUE,
                                'response' => $dataId
                                    ], REST_Controller::HTTP_OK);
                        } else {
                            $this->response([
                                'status' => FALSE,
                               'message' => 'No request found'
                                    ], REST_Controller::HTTP_OK);
                        }
                    } 
                    else
                    {
                        
                        if (!$mail || !$token) 
                        {
                            $this->response([
                                'status' => FALSE,
                                'response' => "Data not found",
                                'message' => 'No request found'
                                    ], REST_Controller::HTTP_OK);
                        }
                        $dataId = $this->UserManager->sign_out($mail, $token);
                        if (!is_null($dataId)) {
                            $this->response([
                                'status' => TRUE,
                                'response' => $dataId
                                    ], REST_Controller::HTTP_OK);
                        } else {
                            $this->response([
                                'status' => FALSE,
                               'message' => 'No request found'
                                    ], REST_Controller::HTTP_OK);
                        }
                    }
                } 
                 else
                {
                    $getrole = array("USER");
                    $data = array(
                        'nom' => $this->post('nom'),
                        'prenom' => $this->post('prenom'),
                        'email' => $this->post('email'),
                        'password' => sha1($this->post('password')),
                        'enabled' => 0,
                        'token' => bin2hex(openssl_random_pseudo_bytes(32)),
                        'roles' => serialize($getrole)
                    );
                    if (!$data) {
                        $this->response([
                            'status' => FALSE,
                            'response' => 0,
                            'message' => 'No request found'
                                ], REST_Controller::HTTP_OK);
                    }
                    $dataId = $this->UserManager->add($data);
                    if (!is_null($dataId)) {
                        $this->response([
                            'status' => TRUE,
                            'response' => $dataId
                                ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => FALSE,
                           'message' => 'No request found'
                                ], REST_Controller::HTTP_OK);
                    } 
                }
            }
                         
        }       
    }
} 

/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>