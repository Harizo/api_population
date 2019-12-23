<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suivi_menage_model extends CI_Model
{
    protected $table = 'suivi_menage';


 	// TABLE CONCERNEE DANS LA BDD : suivi_menage
	// Cette fonction ajoute un enregistrement dans la table
   public function add($suivi_menage)
    {
        $this->db->set($this->_set($suivi_menage))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
	// Cette fonction met à jour un enregistrement dans la table
    public function update($id, $suivi_menage)
    {
        $this->db->set($this->_set($suivi_menage))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
	// Cette fonction affecte les données  d'un enregistrement via controleur php avant d'être passée en paramètre
	// dans la fonction add ou fontion update
    public function _set($suivi_menage)
    {
        return array(
            'id_menage'         => $suivi_menage['id_menage'],
            'id_suivi_menage_entete'      => $suivi_menage['id_suivi_menage_entete'],                      
        );
    }
	// Cette fonction permet de supprimer un enregitrement dans la table
    public function delete($id)
    {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }
	// Cette fonction récupère toutes les données dans la table
    public function findAll()
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
	// Cette fonction récupère les enregistrement dans la table par id_programme
    public function findAllByProgramme($id_interventions)
    {
        $result =  $this->db->select('menage.id as id_menage,
                                        suivi_menage.id as id,
                                        menage.NomInscrire as NomInscrire,
                                        menage.PersonneInscription as PersonneInscription,
                                        menage.AgeInscrire as AgeInscrire,
                                        menage.Addresse as Addresse,
                                        menage.NumeroEnregistrement as NumeroEnregistrement
                                        ')
                        ->from($this->table)
                        ->join('menage', 'menage.id = suivi_menage.id_menage')
                    //    ->order_by('id')
                        ->like('id_intervention', $id_interventions)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                  
    }
	// Cette fonction récupère les enregistrement dans la table par id_menage
    public function findAllByMenage($id_menage)
    {
        
        $this->db->where("id_menage", $id_menage);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;  
    }
	// Cette fonction récupère un enregistrement dans la table par id = clé primaire de la table	
    public function findById($id)
    {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
	// Cette fonction récupère les enregistrement dans la table par id_programme et par id_menage	
    public function findAllByProgrammeAndMenage($id_interventions,$id_menage)
    {
		$requete="select sm.id,sm.id_menage,m.nom,m.prenom,m.date_naissance,m.profession,m.date_inscription,"
				."sm.id_intervention,sm.date_suivi,sm.montant,sm.observation,sm.id_type_transfert"
				." from suivi_menage as sm"
				." left outer join menage as m on m.id=sm.id_menage"
				." left outer join fokontany as v on v.id=m.id_fokontany"
                ." where sm.id_intervention=".$id_interventions
				." and sm.id_menage=".$id_menage;	
				$result = $this->db->query($requete)->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                  
    }
}
?>