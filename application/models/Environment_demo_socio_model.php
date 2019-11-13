<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Environment_demo_socio_model extends CI_Model
{
    protected $table = 'region';

    public function findEffectif_sexe_age($requete,$enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee)
    {
      $this->db->select("region.id as id_reg, region.nom as nom_region, district.id as id_dist, district.nom as nom_dist, commune.nom as nom_com, commune.id as id_com ");

       $this->db ->select("((select count(indi.id) from individu as indi inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and".$requete." and indi.date_naissance >= '".$enfant."' and indi.sexe = 'F')) as nbr_enfant_fille",false);
       
       $this->db ->select("((select count(indi.id) from individu as indi inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and".$requete." and indi.date_naissance >= '".$enfant."' and indi.sexe = 'H')) as nbr_enfant_homme",false);

       $this->db ->select("((select count(indi.id) from individu as indi inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and ".$requete." and indi.date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and indi.sexe = 'F')) as nbr_agescolaire_fille",false);

        $this->db ->select("((select count(indi.id) from individu as indi inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and ".$requete." and indi.date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' and indi.sexe = 'H')) as nbr_agescolaire_homme",false);

       $this->db ->select("((select count(mena.id) from menage as mena inner join fokontany as foko on mena.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where  com.id=commune.id and ".$requete." and mena.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and mena.sexe = 'H')+(select count(indi.id) from individu as indi inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and ".$requete." and indi.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and indi.sexe = 'H')) as nbr_agetravaille_homme ",false);

       $this->db ->select("((select count(mena.id) from menage as mena inner join fokontany as foko on mena.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where  com.id=commune.id and ".$requete." and mena.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and mena.sexe = 'F')+(select count(indi.id) from individu as indi inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and ".$requete." and indi.date_naissance BETWEEN '".$travail_max."' AND '".$travail_min."' and indi.sexe = 'F')) as nbr_agetravaille_fille ",false);

       $this->db ->select("((select count(indi.id) from individu as indi inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and ".$requete." and indi.date_naissance <= '".$agee."' and indi.sexe = 'H')+(select count(mena.id) from menage as mena inner join fokontany as foko on mena.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where  com.id=commune.id and ".$requete." and mena.date_naissance <= '".$agee."' and mena.sexe = 'H')) as nbr_agee_homme ",false);

       $this->db ->select("((select count(indi.id) from individu as indi inner join fokontany as foko on indi.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and ".$requete." and indi.date_naissance <= '".$agee."' and indi.sexe = 'F')+(select count(mena.id) from menage as mena inner join fokontany as foko on mena.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where  com.id=commune.id and ".$requete." and mena.date_naissance <= '".$agee."' and mena.sexe = 'F')) as nbr_agee_fille ",false);

        $result =  $this->db->from('region,commune,district ,individu,fokontany')
                    
                    ->where('region.id = district.region_id')
                    ->where('district.id = commune.district_id')
                    ->where('commune.id = fokontany.id_commune')
                    ->where('individu.id_fokontany = fokontany.id')
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

   /* public function findEffectif_menage_enfant($requete,$enfant,$scolaire_min,$scolaire_max)
    {
       $this->db->select("region.id as id_reg,region.nom as nom_region,district.id as id_dist,commune.id as id_com ");

       $this->db ->select("((select count(DISTINCT(menage.id)) from menage inner join individu on individu.id_menage = menage.id inner join fokontany on menage.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and individu.date_naissance >= '".$enfant."')) as nombre_menage_enfant",false);

       $this->db ->select("((select count(DISTINCT(menage.id)) from menage inner join individu on individu.id_menage = menage.id inner join fokontany on menage.id_fokontany= fokontany.id inner join commune on commune.id= fokontany.id_commune inner join district on commune.district_id= district.id where  ".$requete." and individu.date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' )) as nombre_menage_agescollaire",false);

        $result =  $this->db->from('region,commune,district ,individu,fokontany')
                    
                    ->where('region.id = district.region_id')
                    ->where('district.id = commune.district_id')
                    ->where('commune.id = fokontany.id_commune')
                    ->where('individu.id_fokontany = fokontany.id')
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
    }*/
    public function findEffectif_menage_enfant($requete,$enfant,$scolaire_min,$scolaire_max)
    {
       $this->db->select("region.id as id_reg, region.nom as nom_region, district.id as id_dist, district.nom as nom_dist, commune.nom as nom_com, commune.id as id_com ");

       $this->db ->select("((select count(DISTINCT(mena.id)) from menage as mena inner join individu as indi on indi.id_menage = mena.id inner join fokontany as foko on mena.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and ".$requete." and indi.date_naissance >= '".$enfant."')) as nbr_menage_enfant",false);

       $this->db ->select("((select count(DISTINCT(mena.id)) from menage as mena inner join individu as indi on indi.id_menage = mena.id inner join fokontany as foko on mena.id_fokontany= foko.id inner join commune as com on com.id= foko.id_commune inner join district as dist on com.district_id= dist.id where com.id=commune.id and ".$requete." and indi.date_naissance BETWEEN '".$scolaire_max."' AND '".$scolaire_min."' )) as nbr_menage_agescollaire",false);

        $result =  $this->db->from('region,commune,district ,individu,fokontany')
                    
                    ->where('region.id = district.region_id')
                    ->where('district.id = commune.district_id')
                    ->where('commune.id = fokontany.id_commune')
                    ->where('individu.id_fokontany = fokontany.id')
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
