<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Systeme_protection_social_model extends CI_Model
{    
    public function repartitionBeneficiaire_sexe_age($requete,$enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee)
    {
       $this->db->select("region.id as id_reg,region.nom as nom_region,district.id as id_dist,commune.id as id_com ");
        
       $this->db ->select("((select count(DISTINCT(individu_beneficiaire.id_individu)) from individu_beneficiaire inner join individu on individu.id= individu_beneficiaire.id_individu inner join intervention on intervention.id= individu_beneficiaire.id_intervention inner join fokontany on individu.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance >= '".$enfant."' and individu.sexe = 'F')) as nombre_enfant_individu_f",false);

       $this->db ->select("((select count(DISTINCT(individu_beneficiaire.id_individu)) from individu_beneficiaire inner join individu on individu.id= individu_beneficiaire.id_individu inner join intervention on intervention.id= individu_beneficiaire.id_intervention inner join fokontany on individu.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance >= '".$enfant."' and individu.sexe = 'H')) as nombre_enfant_individu_h",false);

       $this->db ->select("((select count(DISTINCT(individu_beneficiaire.id_individu)) from individu_beneficiaire inner join individu on individu.id= individu_beneficiaire.id_individu inner join intervention on intervention.id= individu_beneficiaire.id_intervention inner join fokontany on individu.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and individu.sexe = 'F')) as nombre_agescolaire_individu_f",false);

        $this->db ->select("((select count(DISTINCT(individu_beneficiaire.id_individu)) from individu_beneficiaire inner join individu on individu.id= individu_beneficiaire.id_individu inner join intervention on intervention.id= individu_beneficiaire.id_intervention inner join fokontany on individu.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and individu.sexe = 'H')) as nombre_agescolaire_individu_h",false); 

       $this->db ->select("((select count(DISTINCT(individu_beneficiaire.id_individu)) from individu_beneficiaire inner join individu on individu.id= individu_beneficiaire.id_individu inner join intervention on intervention.id= individu_beneficiaire.id_intervention inner join fokontany on individu.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and individu.sexe = 'F')) as nombre_agetravaille_individu_f",false);

       $this->db ->select("((select count(DISTINCT(individu_beneficiaire.id_individu)) from individu_beneficiaire inner join individu on individu.id= individu_beneficiaire.id_individu inner join intervention on intervention.id= individu_beneficiaire.id_intervention inner join fokontany on individu.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and individu.sexe = 'H')) as nombre_agetravaille_individu_h",false);

       $this->db ->select("((select count(DISTINCT(individu_beneficiaire.id_individu)) from individu_beneficiaire inner join individu on individu.id= individu_beneficiaire.id_individu inner join intervention on intervention.id= individu_beneficiaire.id_intervention inner join fokontany on individu.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance <= '".$agee."' and individu.sexe = 'F')) as nombre_agee_individu_f",false);

       $this->db ->select("((select count(DISTINCT(individu_beneficiaire.id_individu)) from individu_beneficiaire inner join individu on individu.id= individu_beneficiaire.id_individu inner join intervention on intervention.id= individu_beneficiaire.id_intervention inner join fokontany on individu.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance <= '".$agee."' and individu.sexe = 'H')) as nombre_agee_individu_h",false);

       $this->db ->select("((select count(DISTINCT(menage_beneficiaire.id_menage)) from menage_beneficiaire inner join menage on menage.id=menage_beneficiaire.id_menage inner join intervention on intervention.id= menage_beneficiaire.id_intervention inner join fokontany on menage.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance >= '".$enfant."' and menage.sexe = 'F')) as nombre_enfant_menage_f",false);

       $this->db ->select("((select count(DISTINCT(menage_beneficiaire.id_menage)) from menage_beneficiaire inner join menage on menage.id=menage_beneficiaire.id_menage inner join intervention on intervention.id= menage_beneficiaire.id_intervention inner join fokontany on menage.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance >= '".$enfant."' and menage.sexe = 'H')) as nombre_enfant_menage_h",false);

       $this->db ->select("((select count(DISTINCT(menage_beneficiaire.id_menage)) from menage_beneficiaire inner join menage on menage.id=menage_beneficiaire.id_menage inner join intervention on intervention.id= menage_beneficiaire.id_intervention inner join fokontany on menage.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and menage.sexe = 'F')) as nombre_agescolaire_menage_f",false);

       $this->db ->select("((select count(DISTINCT(menage_beneficiaire.id_menage)) from menage_beneficiaire inner join menage on menage.id=menage_beneficiaire.id_menage inner join intervention on intervention.id= menage_beneficiaire.id_intervention inner join fokontany on menage.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and menage.sexe = 'H')) as nombre_agescolaire_menage_h",false); 

       $this->db ->select("((select count(DISTINCT(menage_beneficiaire.id_menage)) from menage_beneficiaire inner join menage on menage.id=menage_beneficiaire.id_menage inner join intervention on intervention.id= menage_beneficiaire.id_intervention inner join fokontany on menage.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and menage.sexe = 'F')) as nombre_agetravaille_menage_f",false);

       $this->db ->select("((select count(DISTINCT(menage_beneficiaire.id_menage)) from menage_beneficiaire inner join menage on menage.id=menage_beneficiaire.id_menage inner join intervention on intervention.id= menage_beneficiaire.id_intervention inner join fokontany on menage.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and menage.sexe = 'H')) as nombre_agetravaille_menage_h",false);

       $this->db ->select("((select count(DISTINCT(menage_beneficiaire.id_menage)) from menage_beneficiaire inner join menage on menage.id=menage_beneficiaire.id_menage inner join intervention on intervention.id= menage_beneficiaire.id_intervention inner join fokontany on menage.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance <= '".$agee."' and menage.sexe = 'F')) as nombre_agee_menage_f",false);

       $this->db ->select("((select count(DISTINCT(menage_beneficiaire.id_menage)) from menage_beneficiaire inner join menage on menage.id=menage_beneficiaire.id_menage inner join intervention on intervention.id= menage_beneficiaire.id_intervention inner join fokontany on menage.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and date_naissance <= '".$agee."' and menage.sexe = 'H')) as nombre_agee_menage_h",false);

        $result =  $this->db->from('region,commune,district ,individu,menage,fokontany,intervention')
                    
                    ->where('region.id = district.region_id')
                    ->where('district.id = commune.district_id')
                    ->where('commune.id = fokontany.id_commune')
                    ->where('individu.id_fokontany = fokontany.id')
                    ->where('menage.id_fokontany = fokontany.id')
                    ->where($requete)
                    ->group_by('id_reg,id_dist,id_com')
                                       
                    ->get()
                    ->result();                              

        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

    public function findNbr_cumule_beneficiaire($requete)
    {
       $this->db->select("region.id as id_reg,region.nom as nom_region,district.id as id_dist,commune.id as id_com ");
        
       $this->db ->select("((select count(DISTINCT(individu_beneficiaire.id_individu)) from individu_beneficiaire inner join individu on individu.id= individu_beneficiaire.id_individu inner join intervention on intervention.id= individu_beneficiaire.id_intervention inner join fokontany on individu.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete.")) as nombre_individu",false);     

       $this->db ->select("((select count(DISTINCT(menage_beneficiaire.id_menage)) from menage_beneficiaire inner join menage on menage.id=menage_beneficiaire.id_menage inner join intervention on intervention.id= menage_beneficiaire.id_intervention inner join fokontany on menage.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete.")) as nombre_menage",false);
       

        $result =  $this->db->from('region,commune,district ,individu,menage,fokontany,intervention')
                    
                    ->where('region.id = district.region_id')
                    ->where('district.id = commune.district_id')
                    ->where('commune.id = fokontany.id_commune')
                    ->where('individu.id_fokontany = fokontany.id')
                    ->where('menage.id_fokontany = fokontany.id')
                    ->where($requete)
                    ->group_by('id_reg,id_dist,id_com')
                                       
                    ->get()
                    ->result();                              

        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

    public function findEffectif_beneficiaire_handicape($requete)
    {
       $result =  $this->db->select('count(DISTINCT(individu_beneficiaire.id_individu)) as nombre_individu')
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

    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
    public function findByIdArray($id)  {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('id', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return array();
        }                 
    }
}