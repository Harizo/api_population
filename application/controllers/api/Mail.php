<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/PHPMailer/PHPMailerAutoload.php';

class Mail extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('utilisateurs_model', 'UserManager');
    }

    public function index_get() {


        $courriel = $to = $this->get('courriel');
        $token = $this->get('token');
        $actif = $this->get('actif');
        $dest = $this->get('dest');
        $assujettis = $this->get('assujettis');
        $assujettis_id = $this->get('assujettis_id');
        $date = $this->get('date');
        $sender = "ndrianaina.aime.bruno@gmail.com";
        $mdpsender = "finaritra";

        if ($actif == 0) {
			// Envoi mail code de confirmation
            $data['activer'] = base_url() . "/mail?actif=1&courriel=" . $courriel . "&token=" . $token;
            $sujet = 'Code de confirmation';
            $corps = $this->load->view('mail/activation.php', $data, true);
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $sender;
            $mail->Password = $mdpsender;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->setFrom($sender);
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $sujet;
            $mail->Body = $corps;
            if (!$mail->send()) {
                $data = 0;
            } else {
                $data = 1;
            }
        }

        if ($actif == 1) {
            $site = "http://localhost:3000";//$site = substr_replace(base_url(), "", -10);
			// Mise à jour adresse mail et token
            $val = $this->UserManager->update2($courriel, $token);
            if ($val == 1) {
                redirect($site . '/auth/login');
            } else {
                redirect($site . '/auth/login');
            }
        }
        if ($actif == 2) {
			// Mail de confirmation
            $data['confirmer'] = base_url() . "/mail?actif=3&token=" . $token;
            $sujet = 'Reinitialisation mot de passe';
            $corps = $this->load->view('mail/confirmation.php', $data, true);
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $sender;
            $mail->Password = $mdpsender;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->setFrom($sender);
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $sujet;
            $mail->Body = $corps;
            if (!$mail->send()) {
                $data = 0;
            } else {
                $data = 1;
            }
        }

        if ($actif == 3) {
			// Rédirection vers réinitialisation mot de pass
            $site = "http://localhost:3000"; //$site = substr_replace(base_url(), "", -10);
            redirect($site . '/auth/reset-password?token=' . $token);
        }


        if ($actif == 7) 
        {
			// envoi mail : INFORMATION SUR LE COMPTE D'UTILISATEUR
            $data['mdp'] = $this->get('teny_miafina');
            $data['email'] = $this->get('email');
            $sujet ="INFORMATION SUR LE COMPTE D'UTILISATEUR";
            $corps = $this->load->view('mail/compte.php', $data, true);
            $mail = new PHPMailer;
          
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $sender;
            $mail->Password = $mdpsender;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->setFrom($sender);
            $mail->addAddress($data['email']);
            $mail->isHTML(true);
            $mail->Subject = $sujet;
            $mail->AddEmbeddedImage("./assets/logo.png", "my-attach", "./assets/logo.png", "base64", "application/octet-stream");
         
            //$mail->Body = '<img alt="PHPMailer" src="cid:my-attach">';
            $mail->Body = $corps;
            if (!$mail->send()) {
                $data ;
            } else {
                $data ;
            }
            
        }
        //status success + data
        $this->response($data, REST_Controller::HTTP_OK);
    }

}

/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */