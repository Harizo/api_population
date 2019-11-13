<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Systeme_protection_social_model extends CI_Model
{    
   
    public function repartitionBeneficiaireIndividu_sexe_age($requete,$enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee)
    {
       $this->db->select("region.id as id_reg,region.nom as nom_region, district.id as id_dist, district.nom as nom_dist, commune.id as id_com, commune.nom as nom_com, intervention.id as id_int, intervention.intitule as intitule_intervention");

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance >= '".$enfant."' and indi.sexe = 'H')) as nbr_enfant_homme",false);

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance >= '".$enfant."' and indi.sexe = 'F')) as nbr_enfant_fille",false);
        
       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and indi.sexe = 'H')) as nbr_agescolaire_homme",false);

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and".$requete." and indi.date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and indi.sexe = 'F')) as nbr_agescolaire_fille",false); 

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and indi.sexe = 'F')) as nbr_agetravaille_fille",false);

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and indi.sexe = 'H')) as nbr_agetravaille_homme",false);

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance <= '".$agee."' and indi.sexe = 'F')) as nbr_agee_fille",false);

       $this->db ->select("((select count(DISTINCT(indi_bene.id_individu)) from individu_beneficiaire as indi_bene inner join individu as indi on indi.id= indi_bene.id_individu inner join intervention as interv on interv.id= indi_bene.id_intervention inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and interv.id=intervention.id and ".$requete." and indi.date_naissance <= '".$agee."' and indi.sexe = 'H')) as nbr_agee_homme",false);

       

        $result =  $this->db->from('region,commune,district,fokontany,individu,individu_beneficiaire,intervention')
                    
                    ->where('region.id = district.region_id')
                    ->where('district.id = commune.district_id')
                    ->where('commune.id = fokontany.id_commune')
                    
                    ->where('individu.id_fokontany = fokontany.id')                    
                    ->where('individu.id = individu_beneficiaire.id_individu')
                    ->where('individu_beneficiaire.id_intervention = intervention.id')
                   
                    ->where($requete)
                    ->group_by('id_reg,id_dist,id_com,id_int')
                                       
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

    /* public function repartitionBeneficiaire_sexe_age($requete,$enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee)
    {
      $this->db->select("intervention.id as id_int, intervention.intitule as intitule_intervention");

       $this->db ->select("(select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance >= '".$enfant."' and ind.sexe = 'H') as nbr_enfant_homme",false);

       $this->db ->select("(select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance >= '".$enfant."' and ind.sexe = 'F') as nbr_enfant_fille",false);

       $this->db ->select("(select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and ind.sexe = 'F') as nbr_agescolaire_fille",false);

        $this->db ->select("(select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and ind.sexe = 'H') as nbr_agescolaire_homme",false); 

       $this->db ->select("((select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and ind.sexe = 'F')+(select count((menage_beneficiaire.id)) from menage_beneficiaire inner join  menage as mena on mena.id= menage_beneficiaire.id_menage  inner join fokontany  on mena.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where menage_beneficiaire.id_intervention = intervention.id  and ".$requete." and mena.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and mena.sexe = 'F')) as nbr_agetravaille_fille",false);

       $this->db ->select("((select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and ind.sexe = 'H')+(select count((menage_beneficiaire.id)) from menage_beneficiaire inner join  menage as mena on mena.id= menage_beneficiaire.id_menage  inner join fokontany  on mena.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where menage_beneficiaire.id_intervention = intervention.id  and ".$requete." and mena.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and mena.sexe = 'H')) as nbr_agetravaille_homme",false);

       $this->db ->select("((select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance <= '".$agee."' and ind.sexe = 'F')+(select count((menage_beneficiaire.id)) from menage_beneficiaire inner join  menage as mena on mena.id= menage_beneficiaire.id_menage  inner join fokontany  on mena.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where menage_beneficiaire.id_intervention = intervention.id  and ".$requete." and mena.date_naissance <= '".$agee."' and mena.sexe = 'F')) as nbr_agee_fille",false);

       $this->db ->select("((select count((individu_beneficiaire.id)) from individu_beneficiaire inner join  individu as ind on ind.id= individu_beneficiaire.id_individu inner join fokontany  on ind.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where individu_beneficiaire.id_intervention = intervention.id  and ".$requete." and ind.date_naissance <= '".$agee."' and ind.sexe = 'H')+(select count((menage_beneficiaire.id)) from menage_beneficiaire inner join  menage as mena on mena.id= menage_beneficiaire.id_menage  inner join fokontany  on mena.id_fokontany= fokontany.id inner join commune  on commune.id= fokontany.id_commune inner join district  on commune.district_id= district.id inner join region  on region.id = district.region_id  where menage_beneficiaire.id_intervention = intervention.id  and ".$requete." and mena.date_naissance <= '".$agee."' and mena.sexe = 'H')) as nbr_agee_homme",false);
       
       //HARIZO                               

        $result =  $this->db->from('intervention')
                    ->get()
                    ->result();  

        //HARIZO                            

        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }*/
}
