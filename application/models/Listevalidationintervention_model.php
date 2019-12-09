<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listevalidationintervention_model extends CI_Model {
    protected $table = 'liste_validation_intervention';
	// Table concerné : liste_validation_intervention
	// Insertion dans la table
    public function add($liste_validation_intervention)  {
        $this->db->set($this->_set($liste_validation_intervention))
							// ->set('date_reception', 'NOW()', 'Europe/Moscow')	décalage  heure de temps : la poisse
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)  {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
	// Mise à jour dans la table : par id (clé primaire)
     public function update($id, $liste_validation_intervention)  {
        $this->db->set($this->_set($liste_validation_intervention))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)  {
            return true;
        }else{
            return null;
        }                      
    }
	// Affectation des colonnes : passée en paramètres dans la fonction add ou update après
    public function _set($liste_validation_intervention) {
        return array(
            'id_utilisateur'            => $liste_validation_intervention['id_utilisateur'],
            'nom_fichier'               => $liste_validation_intervention['nom_fichier'],
            'repertoire'                => $liste_validation_intervention['repertoire'],
            'donnees_validees'          => $liste_validation_intervention['donnees_validees'],
            'date_reception'            => $liste_validation_intervention['date_reception'],
            'date_validation'           => $liste_validation_intervention['date_validation'],
            'id_utilisateur_validation' => $liste_validation_intervention['id_utilisateur_validation'],
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