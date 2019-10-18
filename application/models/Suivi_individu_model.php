<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suivi_individu_model extends CI_Model
{
    protected $table = 'suivi_individu';


    public function add($suivi_individu)
    {
        $this->db->set($this->_set($suivi_individu))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $suivi_individu)
    {
        $this->db->set($this->_set($suivi_individu))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($suivi_individu)
    {
       return array(
            'id_individu'       => $suivi_individu['id_individu'],
            'id_intervention'    => $suivi_individu['id_intervention'],                      
            'date_suivi'        => $suivi_individu['date_suivi'],                      
            'montant'           => $suivi_individu['montant'],                      
            'observation'       => $suivi_individu['observation'],                      
            'id_type_transfert' => $suivi_individu['id_type_transfert'],                      
        );
    }


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

    public function findAllByProgramme($id_interventions)
    {
        $result =  $this->db->select('menage.id as id_individu,
                                        suivi_individu.id as id,
                                        menage.NomInscrire as NomInscrire,
                                        menage.PersonneInscription as PersonneInscription,
                                        menage.AgeInscrire as AgeInscrire,
                                        menage.Addresse as Addresse,
                                        menage.NumeroEnregistrement as NumeroEnregistrement
                                        ')
                        ->from($this->table)
                        ->join('menage', 'menage.id = suivi_individu.id_individu')
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

    public function findAllByMenage($id_individu)
    {
        
        $this->db->where("id_individu", $id_individu);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;  
    }

    public function findById($id)
    {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
    public function findAllByProgrammeAndIndividu($id_interventions,$id_individu)
    {
		$requete="select sm.id,sm.id_individu,i.nom,i.prenom,i.date_naissance,"
				."sm.id_intervention,sm.date_suivi,sm.montant,sm.observation,sm.id_type_transfert,i.id_menage"
				." from suivi_individu as sm"
				." left outer join individu as i on i.id=sm.id_individu"
				." left outer join menage as m on m.id=i.id_menage"
				." left outer join fokontany as v on v.id=m.id_fokontany"
                ." where sm.id_intervention=".$id_interventions
				." and sm.id_individu=".$id_individu;	
				$result = $this->db->query($requete)->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                  
    }

}
