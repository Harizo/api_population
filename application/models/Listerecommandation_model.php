<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listerecommandation_model extends CI_Model {
    protected $table = 'liste_recommandations';

    public function add($typeavancement) {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($typeavancement))
                 ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $typeavancement) {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($typeavancement))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($typeavancement) {
		// Affectation des valeurs
        return array(
            'resume'         => $typeavancement['resume'],
            'url'            => $typeavancement['url'],
            'validation'     => $typeavancement['validation'],
            'utilisateur_id' => $typeavancement['utilisateur_id'],
            'site_id'        => $typeavancement['site_id'],
            'nom_fichier'    => $typeavancement['nom_fichier'],
            'repertoire'     => $typeavancement['repertoire'] ,
            'date_upload'    => $typeavancement['date_upload'],
            'fait'           => $typeavancement['fait']                   
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
	public function getlesrecommandations() {
		// Selection de tous les recommandations
		$requete="select r.id,r.resume,r.url,r.validation,r.utilisateur_id,r.site_id as site,r.nom_fichier,r.repertoire,r.date_upload,"
				."r.fait,concat_ws(' ',u.nom,u.prenom) as nomutilisateur"
				." from liste_recommandations as r"
				." left outer join utilisateur as u on u.id=r.utilisateur_id"
				." order by r.date_upload";
      $query= $this->db->query($requete);
      return $query->result();
	}
	public function getlesnonfait() {
		// Selection des recommandations non faits
		$valide =1;
		$fait =0;
        $result = $this->db->select('*')
                        ->from($this->table)
                        ->where("fait", $fait)
                        ->where("validation", $valide)
                        ->order_by('id', 'desc')
                        ->get()
                        ->result();
        if($result) {
            return count($result);
        }else{
            return '';
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
}
?>