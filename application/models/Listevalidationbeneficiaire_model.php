<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listevalidationbeneficiaire_model extends CI_Model {
    protected $table = 'liste_validation_beneficiaire';
	// Table concerné : liste_validation_beneficiaire
	// Insertion dans la table
    public function add($liste_validation_beneficiaire)  {
        $this->db->set($this->_set($liste_validation_beneficiaire))
							// ->set('date_reception', 'NOW()', 'Europe/Moscow')	décalage  heure de temps : la poisse
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
	// Mise à jour dans la table : par id (clé primaire)
    public function update($id, $liste_validation_beneficiaire)  {
        $this->db->set($this->_set($liste_validation_beneficiaire))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
	// Affectation des colonnes : passée en paramètres dans la fonction add ou update après
    public function _set($liste_validation_beneficiaire) {
        return array(
            'id_utilisateur'            => $liste_validation_beneficiaire['id_utilisateur'],
            'nom_fichier'               => $liste_validation_beneficiaire['nom_fichier'],
            'repertoire'                => $liste_validation_beneficiaire['repertoire'],
            'donnees_validees'          => $liste_validation_beneficiaire['donnees_validees'],
            'date_reception'            => $liste_validation_beneficiaire['date_reception'],
            'date_validation'           => $liste_validation_beneficiaire['date_validation'],
            'id_utilisateur_validation' => $liste_validation_beneficiaire['id_utilisateur_validation'],
            'id_fokontany'              => $liste_validation_beneficiaire['id_fokontany'],
            'id_intervention'           => $liste_validation_beneficiaire['id_intervention'],
        );
    }
	// Suppression dans la table : par id (clé primaire)
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }  
    }
	// Récupération de tous les enregistrements de la table
    public function findAll() {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('date_reception')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
	// Récupération d'un enregistrement : par id
    public function findById($id) {
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
	// Récupération des enregistrements par colonne donnees_validees : si $donnees_validees =0 => pas encore importé dans BDD
	// sinon $donnees_validees =1 : fichier déjà importé dans la BDD
    public function findByValidation($donnees_validees) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("donnees_validees", $donnees_validees)
                        ->order_by('date_reception', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
	// Récupération des enregistrements par colonne donnees_validees et id_utilisateur : si $donnees_validees =0 => pas encore importé dans BDD
	// sinon $donnees_validees =1 : fichier déjà importé dans la BDD
    public function findByValidationAndUtilisateur($donnees_validees,$id_utilisateur) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("donnees_validees", $donnees_validees)
                        ->where("id_utilisateur", $id_utilisateur)
                        ->order_by('date_reception', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
	// Récupération d'un enregistrement par maximum date de reception et par id_utilisateur
    public function findByMaxDateReceptionAndUtilisateur($id_utilisateur) {
		$requete = "select max(date_reception) as date_reception from ".$this->table." where id_utilisateur=".$id_utilisateur;
		$result = $this->db->query($requete)->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
}
?>