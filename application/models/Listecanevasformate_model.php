<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listecanevasformate_model extends CI_Model {
    protected $table = 'liste_canevas_formtate';

    public function add($canevasformate) {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($canevasformate))
                 ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $canevasformate) {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($canevasformate))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($canevasformate) {
		// Affectation des valeurs
        return array(
            'resume'         => $canevasformate['resume'],
            'id_utilisateur' => $canevasformate['id_utilisateur'],
            'nom_fichier'    => $canevasformate['nom_fichier'],
            'repertoire'     => $canevasformate['repertoire'] ,
            'date_upload'    => $canevasformate['date_upload'],
        );
    }
    public function delete($id) {
		// Suppression d'un enregitrement
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
		// Selection de tous les enregitrements
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id', 'desc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
	public function getlescanevasformate() {
		// Selection de tous les recommandations
		$requete="select cf.id,cf.resume,cf.id_utilisateur,cf.nom_fichier,cf.repertoire,cf.date_upload,"
				."concat_ws(' ',u.nom,u.prenom) as nomutilisateur"
				." from liste_canevas_formtate as cf"
				." left outer join utilisateur as u on u.id=cf.id_utilisateur"
				." order by cf.date_upload";
      $query= $this->db->query($requete);
      return $query->result();
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
}
?>