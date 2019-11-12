<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menage_beneficiaire_model extends CI_Model {
    protected $table = 'menage_beneficiaire';

    public function add($menage_benef)  {
        $this->db->set($this->_set($menage_benef))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $menage_benef)   {
        $this->db->set($this->_set($menage_benef))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($menage_benef)  {
        return array(
            'id_menage'       => $menage_benef['id_menage'],
            'id_intervention' => $menage_benef['id_intervention'],                      
            'date_sortie' => $menage_benef['date_sortie'],                      
        );
    }
    public function delete($id)  {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll()  {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findAllByProgramme($id_interventions)   {
        $result =  $this->db->select('menage.id as id_menage,
                                        menage_benef.id as id,
                                        menage.NomInscrire as NomInscrire,
                                        menage.PersonneInscription as PersonneInscription,
                                        menage.AgeInscrire as AgeInscrire,
                                        menage.Addresse as Addresse,
                                        menage.NumeroEnregistrement as NumeroEnregistrement
                                        ')
                        ->from($this->table)
                        ->join('menage', 'menage.id = menage_benef.id_menage')
                        ->like('id_intervention', $id_interventions)
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                  
    }
    public function findAllByMenage($id_menage) {
        
        $this->db->where("id_menage", $id_menage);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;  
    }
    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
    public function findAllByProgrammeAndVillage($id_interventions,$id_fokontany)  {
		$requete="select mp.id,mp.id_menage,m.nom,m.prenom,m.date_naissance,m.cin,m.profession,m.date_inscription,mp.id_intervention"
				." from menage_beneficiaire as mp"
				." left outer join menage as m on m.id=mp.id_menage"
				." left outer join fokontany as v on v.id=m.id_fokontany"
                ." where mp.id_intervention like ".$id_interventions
				." and v.id=".$id_fokontany;	
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