<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utilisateurs_model extends CI_Model
{
    protected $table = 'utilisateur';

	// Table concernée : utilisateur
    public function add($utilisateurs) {
        // ajout utilisateur
        $this->db->set($this->_setGestionUtilisateur($utilisateurs))
                 ->set('date_creation', 'NOW()', false)
                 ->set('date_modification', 'NOW()', false)
                 ->insert($this->table);
			$id_utilisateur =	$this->db->insert_id(); 
		// Sauvegarde mot de passe par défaut au cas où mdp oublié	
        $this->db->set($this->_set_default_password($id_utilisateur,$utilisateurs['password']))
                 ->insert("mot_de_passe_par_defaut");
			
        if($this->db->affected_rows() === 1) {
            return $id_utilisateur;
        }else{
            return null;
        }                    
    }
    public function update($id, $utilisateurs) {
		// Mise à jour utilisateur par id(clé primaire)
        $this->db->set($this->_setUpdateUtilisateur($utilisateurs))
                 //->set('date_modification', 'NOW()', false)
                 ->where('id', (int) $id)
                 ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function update2($courriel,$token)  {
		// Mise à jour email et token (activation compte)
        $array = array('email' => $courriel, 'token' => $token);
        $this->db->set('enabled', 1)
                 ->where($array)
                 ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return 1;
        }else{
            return 0;
        }                      
    }
	// Mise à jour profil utilisateur
    public function update_profil($id, $utilisateurs)  {
        $this->db->set($this->_set_profil($utilisateurs))
                 ->where('id', (int) $id)
                 ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
	// Affectation des info profil
    public function _set_profil($utilisateurs)  {
        return array(
            'nom'                   =>      $utilisateurs['nom'],
            'prenom'                =>      $utilisateurs['prenom'],
            'email'                 =>      $utilisateurs['email'],
            'password'              =>      $utilisateurs['password'],
            'cin'                   =>      $utilisateurs['cin'],         
        );
    }
	// Réinitialisation mot de passe : si mot de passe oublié
    public function reinitpwd($courriel,$pwd,$token) {
        $this->db->set('password', $pwd)
                 ->where('email', $courriel)
                 ->where('token', $token)
                 ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return array("update ok");
        }else{
            return array();
        }                      
    }
	// Affectation des info utilisateur
    public function _set($utilisateurs)  {
        return array(
            'nom'                   =>      $utilisateurs['nom'],
            'prenom'                =>      $utilisateurs['prenom'],
            'email'                 =>      $utilisateurs['email'],
            'password'              =>      $utilisateurs['password'],
            'enabled'               =>      $utilisateurs['enabled'],
            'token'                 =>      $utilisateurs['token'],
            'roles'                 =>      $utilisateurs['roles'],            
        );
    }
	// Affectation des info via menu gestion utilisateur
    public function _setGestionUtilisateur($utilisateurs) {
        return array(
            'nom'                  => $utilisateurs['nom'],
            'prenom'               => $utilisateurs['prenom'],
            'email'                => $utilisateurs['email'],
            'password'             => $utilisateurs['password'],
            'default_password'     => $utilisateurs['default_password'],
            'token'                => $utilisateurs['token'],
            'enabled'              => $utilisateurs['enabled'],
            'roles'                => $utilisateurs['roles'],
            'id_region'            => $utilisateurs['id_region'],
            'id_district'          => $utilisateurs['id_district'],
            'id_commune'           => $utilisateurs['id_commune'],
            'id_fokontany'         => $utilisateurs['id_fokontany'],
            'id_intervention'      => $utilisateurs['id_intervention'],
            'piece_identite'       => $utilisateurs['piece_identite'],
            'adresse'              => $utilisateurs['adresse'],
            'fonction'             => $utilisateurs['fonction'],
            'telephone'            => $utilisateurs['telephone'],
            'raison_sociale'       => $utilisateurs['raison_sociale'],
            'adresse_hote'         => $utilisateurs['adresse_hote'],
            'nom_responsable'      => $utilisateurs['nom_responsable'],
            'fonction_responsable' => $utilisateurs['fonction_responsable'],
            'email_hote'           => $utilisateurs['email_hote'],
            'telephone_hote'       => $utilisateurs['telephone_hote'],
            'description_hote'     => $utilisateurs['description_hote'],
        );
    }
	// Affectation des info pour mettre à jour un utilisateur
    public function _setUpdateUtilisateur($utilisateurs) {
        return array(
            'nom'                  => $utilisateurs['nom'],
            'prenom'               => $utilisateurs['prenom'],
            'email'                => $utilisateurs['email'],
            'enabled'              => $utilisateurs['enabled'],
            'roles'                => $utilisateurs['roles'],
            'id_region'            => $utilisateurs['id_region'],
            'id_district'          => $utilisateurs['id_district'],
            'id_commune'           => $utilisateurs['id_commune'],
            'id_fokontany'         => $utilisateurs['id_fokontany'],
            'id_intervention'      => $utilisateurs['id_intervention'],
            'piece_identite'       => $utilisateurs['piece_identite'],
            'adresse'              => $utilisateurs['adresse'],
            'fonction'             => $utilisateurs['fonction'],
            'telephone'            => $utilisateurs['telephone'],
            'raison_sociale'       => $utilisateurs['raison_sociale'],
            'adresse_hote'         => $utilisateurs['adresse_hote'],
            'nom_responsable'      => $utilisateurs['nom_responsable'],
            'fonction_responsable' => $utilisateurs['fonction_responsable'],
            'email_hote'           => $utilisateurs['email_hote'],
            'telephone_hote'       => $utilisateurs['telephone_hote'],
            'description_hote'     => $utilisateurs['description_hote'],
        );
    }
	// Suppression d'un utilisateur
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }  
    }
	// Récupération de tous les enregistrements de la table utilisateur
    public function findAll()  {
        $result = $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id', 'desc')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                  
    }
    public function findAllByEnabled($enabled)  {
		// Selection par enabled
        $result = $this->db->select('*')
                        ->from($this->table)
                        ->where("enabled", $enabled)
                        ->order_by('id', 'desc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                  
    }
    public function findById($id) {
		// Selection par id
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }   
	public function findByIdtab($id) {
		// Selection par id : résultat dans un tableau
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('id', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findByMail($mail) {
		// Selection par mail
        $this->db->where("email", $mail);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
    public function findByPassword($mdp) {
		// Selection par mot de passe
        $this->db->where("password", sha1($mdp));
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
    public function sign_in($email, $pwd)   {
		// Selection par mail et password
        $result = $this->db->select('*')
                        ->from($this->table)
                        ->where("email", $email)
                        ->where("password", $pwd)
                        ->order_by('id', 'desc')
                        ->get()
                        ->result();
        if($result)  {
            return $result;
        }else{
            return null;
        }                  
    }
    public function _set_first_login($utilisateurs)  {
		// Affectation des valeurs lors de la première connexion
        return array(
            'password'         => $utilisateurs['password'],
            'default_password' => $utilisateurs['default_password'],
        );
    }
    public function first_login($data, $id_utilisateur)  {
		// Selection par id_utilisateur
        $this->db->set($this->_set_first_login($data))
                 ->where('id', (int) $id_utilisateur)
                 ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->findById($id_utilisateur);
        }else{
            return null;
        }                      
    }
    public function _set_default_password($id_utilisateur,$password)  {
		// Affectation mot de passe par défaut
        return array(
            'id_utilisateur' => $id_utilisateur,
            'password'       => $password,
        );
    }
}
?>