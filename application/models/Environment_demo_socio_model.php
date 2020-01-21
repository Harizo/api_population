<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Environment_demo_socio_model extends CI_Model
{
    protected $table = 'region';

    //Début Req1-theme 1 Si on veut une liste avec les commune qui n'ont pas de données

    public function findEffectif_sexe_age($requete,$enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee)
    {
        $this->db->select("region.id as id_reg, region.nom as nom_region, district.id as id_dist, district.nom as nom_dist, commune.nom as nom_com, commune.id as id_com ");

       $this->db ->select("((
        select 
            count(DISTINCT(indi.id)) 
        from    
            individu as indi 
            inner join fokontany as foko on indi.id_fokontany= foko.id 
            inner join commune as com on com.id= foko.id_commune 
            inner join district as dist on com.district_id= dist.id 
            inner join individu_beneficiaire as ib on indi.id = ib.id_individu
        where   
            com.id=commune.id 
            and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 7
            and indi.sexe = 'F')) 
        as nbr_enfant_fille",false);
       
       $this->db ->select("((
        select 
            count(DISTINCT(indi.id)) 
        from 
            individu as indi 
            inner join fokontany as foko on indi.id_fokontany = foko.id 
            inner join commune as com on com.id= foko.id_commune 
            inner join district as dist on com.district_id= dist.id 
            inner join individu_beneficiaire as ib on indi.id = ib.id_individu
        where 
            com.id=commune.id 
            and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 7
            and indi.sexe = 'H')) 
        as nbr_enfant_homme",false);

       $this->db ->select("((
        select 
            count(DISTINCT(indi.id)) 
        from 
            individu as indi 
            inner join fokontany as foko on indi.id_fokontany= foko.id 
            inner join commune as com on com.id= foko.id_commune 
            inner join district as dist on com.district_id= dist.id 
            inner join individu_beneficiaire as ib on indi.id = ib.id_individu
        where 
            com.id=commune.id 
            and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 7
            and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 18
            and indi.sexe = 'F')) 
        as nbr_agescolaire_fille",false);

        $this->db ->select("((
        select 
            count(DISTINCT(indi.id)) 
        from 
            individu as indi 
            inner join fokontany as foko on indi.id_fokontany= foko.id 
            inner join commune as com on com.id= foko.id_commune 
            inner join district as dist on com.district_id= dist.id
            inner join individu_beneficiaire as ib on indi.id = ib.id_individu 
        where 
            com.id=commune.id 
           
            and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 7
            and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 18
            and indi.sexe = 'H'))
        as nbr_agescolaire_homme",false);

       $this->db ->select("
        ((
        select 
            count(DISTINCT(mena.id)) 
        from 
            menage as mena 
            inner join fokontany as foko on mena.id_fokontany= foko.id 
            inner join commune as com on com.id= foko.id_commune 
            inner join district as dist on com.district_id= dist.id 
            inner join menage_beneficiaire as mb on mena.id = mb.id_menage 
        where  
            com.id=commune.id 
            and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) >= 18
            and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) < 60
            and mena.sexe = 'H')
        +
        (
        select 
            count(DISTINCT(indi.id)) 
        from 
            individu as indi 
            inner join fokontany as foko on indi.id_fokontany= foko.id 
            inner join commune as com on com.id= foko.id_commune 
            inner join district as dist on com.district_id= dist.id 
            inner join individu_beneficiaire as ib on indi.id = ib.id_individu 
        where 
            com.id=commune.id 
            and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 18
            and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 60
            and indi.sexe = 'H'
        )) 
        as nbr_agetravaille_homme ",false);

       $this->db ->select("
        ((
        select 
            count(DISTINCT(mena.id)) 
        from 
            menage as mena 
            inner join fokontany as foko on mena.id_fokontany= foko.id 
            inner join commune as com on com.id= foko.id_commune 
            inner join district as dist on com.district_id= dist.id 
            inner join menage_beneficiaire as mb on mena.id = mb.id_menage 
        where  
            com.id=commune.id 
            and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) >= 18
            and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) < 60
            and mena.sexe = 'F')
        +
        (
        select 
            count(DISTINCT(indi.id)) 
        from 
            individu as indi 
            inner join fokontany as foko on indi.id_fokontany= foko.id 
            inner join commune as com on com.id= foko.id_commune 
            inner join district as dist on com.district_id= dist.id 
            inner join individu_beneficiaire as ib on indi.id = ib.id_individu
        where 
        com.id=commune.id 
        and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 18
        and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 60
        and indi.sexe = 'F'
        )) as nbr_agetravaille_fille ",false);

       $this->db ->select("
        ((
        select 
            count(DISTINCT(indi.id)) 
        from 
            individu as indi 
            inner join fokontany as foko on indi.id_fokontany= foko.id 
            inner join commune as com on com.id= foko.id_commune 
            inner join district as dist on com.district_id= dist.id
            inner join individu_beneficiaire as ib on indi.id = ib.id_individu 
            
        where 
            com.id=commune.id 
            and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 60
            and indi.sexe = 'H'
        )
        +
        (
        select 
            count(DISTINCT(mena.id)) 
        from 
            menage as mena 
            inner join fokontany as foko on mena.id_fokontany= foko.id 
            inner join commune as com on com.id= foko.id_commune 
            inner join district as dist on com.district_id= dist.id 
            inner join menage_beneficiaire as mb on mena.id = mb.id_menage 
        where  
            com.id=commune.id 
            and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) >= 60
            and mena.sexe = 'H'
        )) as nbr_agee_homme ",false);

 

       $this->db ->select("
        ((
        select 
            count(DISTINCT(indi.id)) 
        from 
            individu as indi 
            inner join fokontany as foko on indi.id_fokontany= foko.id 
            inner join commune as com on com.id= foko.id_commune 
            inner join district as dist on com.district_id= dist.id
            inner join individu_beneficiaire as ib on indi.id = ib.id_individu 
            
        where 
            com.id=commune.id 
            and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 60
            and indi.sexe = 'F'
        )
        +
        (
        select 
            count(DISTINCT(mena.id)) 
        from 
            menage as mena 
            inner join fokontany as foko on mena.id_fokontany= foko.id 
            inner join commune as com on com.id= foko.id_commune 
            inner join district as dist on com.district_id= dist.id 
            inner join menage_beneficiaire as mb on mena.id = mb.id_menage 
        where  
            com.id=commune.id 
            and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) >= 60
            and mena.sexe = 'F'
        )) as nbr_agee_fille ",false);

        $result =  $this->db->from('region')
                    
                    ->join('district', 'region.id = district.region_id')
                    ->join('commune', 'district.id = commune.district_id')
                   // ->join('fokontany', 'commune.id = fokontany.id_commune')
                   
                    ->where($requete)
                
                    ->group_by('region.id, district.id, commune.id')
                                       
                    ->get()
                    ->result();                              

        if($result)
        {
            return $result;
        }else{
            return null;
        }            
    }

    //Fin Req1-theme 1 Si on veut une liste avec les commune qui n'ont pas de données
    //Début Req1-theme 1
    public function effectif_par_age_sexe_population()
    {
        $sql =  "

                                select
                                    niveau_1.nom_region as nom_region,
                                    niveau_1.nom_dist as nom_dist,
                                    niveau_1.nom_com as nom_com,
                                    niveau_1.id_commune as id_commune,

                                    sum(niveau_1.nbr_enfant_homme) as nbr_enfant_homme,
                                    sum(niveau_1.nbr_enfant_fille) as nbr_enfant_fille,
                                    sum(niveau_1.nbr_agescolaire_fille) as nbr_agescolaire_fille,
                                    sum(niveau_1.nbr_agescolaire_homme) as nbr_agescolaire_homme,

                                    (sum(niveau_1.nbr_agetravaille_homme_menage) + sum(niveau_1.nbr_agetravaille_homme_individu)) as nbr_agetravaille_homme,
                                    (sum(niveau_1.nbr_agetravaille_fille_menage) + sum(niveau_1.nbr_agetravaille_fille_individu)) as nbr_agetravaille_fille,

                                    (sum(niveau_1.nbr_agee_homme_menage) + sum(niveau_1.nbr_agee_homme_individu)) as nbr_agee_homme,
                                    (sum(niveau_1.nbr_agee_fille_menage) + sum(niveau_1.nbr_agee_fille_individu)) as nbr_agee_fille


                                from(
                                    (
                                        select 
                                            reg.id as id_region,
                                            reg.nom as nom_region,
                                            dist.id as id_district,
                                            dist.nom as nom_dist,
                                            com.id as id_commune,
                                            com.nom as nom_com,


                                            count(DISTINCT(indi.id)) as nbr_enfant_homme,
                                            0 as nbr_enfant_fille,
                                            0 as nbr_agescolaire_fille,
                                            0 as nbr_agescolaire_homme,
                                            0 as nbr_agetravaille_homme_menage,
                                            0 as nbr_agetravaille_homme_individu,
                                            0 as nbr_agetravaille_fille_menage,
                                            0 as nbr_agetravaille_fille_individu,

                                            0 as nbr_agee_homme_menage,
                                            0 as nbr_agee_homme_individu,
                                            0 as nbr_agee_fille_menage,
                                            0 as nbr_agee_fille_individu


                                        from    
                                            individu as indi 
                                            inner join fokontany as foko on indi.id_fokontany= foko.id 
                                            inner join commune as com on com.id= foko.id_commune 
                                            inner join district as dist on com.district_id= dist.id 
                                            inner join region as reg on dist.region_id= reg.id 
                                            inner join individu_beneficiaire as ib on indi.id = ib.id_individu
                                        where   
                                            
                                             (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 7
                                            and indi.sexe = 'H'

                                        group by 
                                            reg.id,
                                            dist.id,
                                            com.id 
                                    )
                                    UNION
                                    (
                                        select 
                                            reg.id as id_region,
                                            reg.nom as nom_region,
                                            dist.id as id_district,
                                            dist.nom as nom_dist,
                                            com.id as id_commune,
                                            com.nom as nom_com,

                                            0 as nbr_enfant_homme,
                                            count(DISTINCT(indi.id)) as nbr_enfant_fille,
                                            0 as nbr_agescolaire_fille,
                                            0 as nbr_agescolaire_homme,
                                            0 as nbr_agetravaille_homme_menage,
                                            0 as nbr_agetravaille_homme_individu,
                                            0 as nbr_agetravaille_fille_menage,
                                            0 as nbr_agetravaille_fille_individu,

                                            0 as nbr_agee_homme_menage,
                                            0 as nbr_agee_homme_individu,
                                            0 as nbr_agee_fille_menage,
                                            0 as nbr_agee_fille_individu


                                        from    
                                            individu as indi 
                                            inner join fokontany as foko on indi.id_fokontany= foko.id 
                                            inner join commune as com on com.id= foko.id_commune 
                                            inner join district as dist on com.district_id= dist.id 
                                            inner join region as reg on dist.region_id= reg.id 
                                            inner join individu_beneficiaire as ib on indi.id = ib.id_individu
                                        where   
                                            
                                             (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 7
                                            and indi.sexe = 'F'

                                        group by 
                                            reg.id,
                                            dist.id,
                                            com.id 
                                    )
                                    UNION
                                    (
                                        select 
                                            reg.id as id_region,
                                            reg.nom as nom_region,
                                            dist.id as id_district,
                                            dist.nom as nom_dist,
                                            com.id as id_commune,
                                            com.nom as nom_com,

                                            0 as nbr_enfant_homme,
                                            0 as nbr_enfant_fille,
                                            count(DISTINCT(indi.id)) as nbr_agescolaire_fille,
                                            0 as nbr_agescolaire_homme,
                                            0 as nbr_agetravaille_homme_menage,
                                            0 as nbr_agetravaille_homme_individu,
                                            0 as nbr_agetravaille_fille_menage,
                                            0 as nbr_agetravaille_fille_individu,

                                            0 as nbr_agee_homme_menage,
                                            0 as nbr_agee_homme_individu,
                                            0 as nbr_agee_fille_menage,
                                            0 as nbr_agee_fille_individu


                                        from 
                                            individu as indi 
                                            inner join fokontany as foko on indi.id_fokontany= foko.id 
                                            inner join commune as com on com.id= foko.id_commune 
                                            inner join district as dist on com.district_id= dist.id 
                                            inner join region as reg on dist.region_id= reg.id 
                                            inner join individu_beneficiaire as ib on indi.id = ib.id_individu
                                        where 
                                            
                                             (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 7
                                            and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 18
                                            and indi.sexe = 'F'

                                        group by 
                                            reg.id,
                                            dist.id,
                                            com.id 
                                    )
                                    UNION
                                    (
                                        select 

                                            reg.id as id_region,
                                            reg.nom as nom_region,
                                            dist.id as id_district,
                                            dist.nom as nom_dist,
                                            com.id as id_commune,
                                            com.nom as nom_com,

                                            0 as nbr_enfant_homme,
                                            0 as nbr_enfant_fille,
                                            0 as nbr_agescolaire_fille,
                                            count(DISTINCT(indi.id)) as nbr_agescolaire_homme,
                                            0 as nbr_agetravaille_homme_menage,
                                            0 as nbr_agetravaille_homme_individu,
                                            0 as nbr_agetravaille_fille_menage,
                                            0 as nbr_agetravaille_fille_individu,

                                            0 as nbr_agee_homme_menage,
                                            0 as nbr_agee_homme_individu,
                                            0 as nbr_agee_fille_menage,
                                            0 as nbr_agee_fille_individu


                                        from 
                                            individu as indi 
                                            inner join fokontany as foko on indi.id_fokontany= foko.id 
                                            inner join commune as com on com.id= foko.id_commune 
                                            inner join district as dist on com.district_id= dist.id
                                            inner join region as reg on dist.region_id= reg.id 
                                            inner join individu_beneficiaire as ib on indi.id = ib.id_individu 
                                        where 
                                            (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 7
                                            and (SELECT DATE_PART('year', ib.date_inscription) - DATE_PART('year', indi.date_naissance)) < 18
                                            and indi.sexe = 'H'

                                        group by 
                                            reg.id,
                                            dist.id,
                                            com.id 
                                    )
                                    UNION
                                    (
                                        select 

                                            reg.id as id_region,
                                            reg.nom as nom_region,
                                            dist.id as id_district,
                                            dist.nom as nom_dist,
                                            com.id as id_commune,
                                            com.nom as nom_com,

                                            0 as nbr_enfant_homme,
                                            0 as nbr_enfant_fille,
                                            0 as nbr_agescolaire_fille,
                                            0 as nbr_agescolaire_homme,
                                            count(DISTINCT(mena.id)) as nbr_agetravaille_homme_menage,
                                            0 as nbr_agetravaille_homme_individu,
                                            0 as nbr_agetravaille_fille_menage,
                                            0 as nbr_agetravaille_fille_individu,

                                            0 as nbr_agee_homme_menage,
                                            0 as nbr_agee_homme_individu,
                                            0 as nbr_agee_fille_menage,
                                            0 as nbr_agee_fille_individu


                                        from 
                                            menage as mena 
                                            inner join fokontany as foko on mena.id_fokontany= foko.id 
                                            inner join commune as com on com.id= foko.id_commune 
                                            inner join district as dist on com.district_id= dist.id 
                                            inner join region as reg on dist.region_id= reg.id 
                                            inner join menage_beneficiaire as mb on mena.id = mb.id_menage 
                                        where  
                                            (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) >= 18
                                            and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) < 60
                                            and mena.sexe = 'H'

                                        group by 
                                            reg.id,
                                            dist.id,
                                            com.id 
                                    )
                                    UNION
                                    (
                                        select 

                                            reg.id as id_region,
                                            reg.nom as nom_region,
                                            dist.id as id_district,
                                            dist.nom as nom_dist,
                                            com.id as id_commune,
                                            com.nom as nom_com,

                                            0 as nbr_enfant_homme,
                                            0 as nbr_enfant_fille,
                                            0 as nbr_agescolaire_fille,
                                            0 as nbr_agescolaire_homme,
                                            0 as nbr_agetravaille_homme_menage,
                                            count(DISTINCT(indi.id)) as nbr_agetravaille_homme_individu,
                                            0 as nbr_agetravaille_fille_menage,
                                            0 as nbr_agetravaille_fille_individu,

                                            0 as nbr_agee_homme_menage,
                                            0 as nbr_agee_homme_individu,
                                            0 as nbr_agee_fille_menage,
                                            0 as nbr_agee_fille_individu


                                        from 
                                            individu as indi 
                                            inner join fokontany as foko on indi.id_fokontany= foko.id 
                                            inner join commune as com on com.id= foko.id_commune 
                                            inner join district as dist on com.district_id= dist.id 
                                            inner join region as reg on dist.region_id= reg.id 
                                            inner join individu_beneficiaire as mb on indi.id = mb.id_individu 
                                        where  
                                            (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 18
                                            and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', indi.date_naissance)) < 60
                                            and indi.sexe = 'H'

                                        group by 
                                            reg.id,
                                            dist.id,
                                            com.id 
                                    )
                                    UNION
                                    (
                                        select 

                                            reg.id as id_region,
                                            reg.nom as nom_region,
                                            dist.id as id_district,
                                            dist.nom as nom_dist,
                                            com.id as id_commune,
                                            com.nom as nom_com,

                                            0 as nbr_enfant_homme,
                                            0 as nbr_enfant_fille,
                                            0 as nbr_agescolaire_fille,
                                            0 as nbr_agescolaire_homme,
                                            0 as nbr_agetravaille_homme_menage,
                                            0 as nbr_agetravaille_homme_individu,
                                            count(DISTINCT(mena.id)) as nbr_agetravaille_fille_menage,
                                            0 as nbr_agetravaille_fille_individu,

                                            0 as nbr_agee_homme_menage,
                                            0 as nbr_agee_homme_individu,
                                            0 as nbr_agee_fille_menage,
                                            0 as nbr_agee_fille_individu


                                        from 
                                            menage as mena 
                                            inner join fokontany as foko on mena.id_fokontany= foko.id 
                                            inner join commune as com on com.id= foko.id_commune 
                                            inner join district as dist on com.district_id= dist.id 
                                            inner join region as reg on dist.region_id= reg.id 
                                            inner join menage_beneficiaire as mb on mena.id = mb.id_menage 
                                        where  
                                            (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) >= 18
                                            and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) < 60
                                            and mena.sexe = 'F'

                                        group by 
                                            reg.id,
                                            dist.id,
                                            com.id 
                                    )
                                    UNION
                                    (
                                        select 

                                            reg.id as id_region,
                                            reg.nom as nom_region,
                                            dist.id as id_district,
                                            dist.nom as nom_dist,
                                            com.id as id_commune,
                                            com.nom as nom_com,

                                            0 as nbr_enfant_homme,
                                            0 as nbr_enfant_fille,
                                            0 as nbr_agescolaire_fille,
                                            0 as nbr_agescolaire_homme,
                                            0 as nbr_agetravaille_homme_menage,
                                            0 as nbr_agetravaille_homme_individu,
                                            0 as nbr_agetravaille_fille_menage,
                                            count(DISTINCT(indi.id)) as nbr_agetravaille_fille_individu,

                                            0 as nbr_agee_homme_menage,
                                            0 as nbr_agee_homme_individu,
                                            0 as nbr_agee_fille_menage,
                                            0 as nbr_agee_fille_individu


                                        from 
                                            individu as indi 
                                            inner join fokontany as foko on indi.id_fokontany= foko.id 
                                            inner join commune as com on com.id= foko.id_commune 
                                            inner join district as dist on com.district_id= dist.id 
                                            inner join region as reg on dist.region_id= reg.id 
                                            inner join individu_beneficiaire as mb on indi.id = mb.id_individu 
                                        where  
                                            (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 18
                                            and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', indi.date_naissance)) < 60
                                            and indi.sexe = 'F'

                                        group by 
                                            reg.id,
                                            dist.id,
                                            com.id 
                                    )
                                    UNION
                                    (
                                        select 

                                            reg.id as id_region,
                                            reg.nom as nom_region,
                                            dist.id as id_district,
                                            dist.nom as nom_dist,
                                            com.id as id_commune,
                                            com.nom as nom_com,

                                            0 as nbr_enfant_homme,
                                            0 as nbr_enfant_fille,
                                            0 as nbr_agescolaire_fille,
                                            0 as nbr_agescolaire_homme,
                                            0 as nbr_agetravaille_homme_menage,
                                            0 as nbr_agetravaille_homme_individu,
                                            0 as nbr_agetravaille_fille_menage,
                                            0 as nbr_agetravaille_fille_individu,

                                            count(DISTINCT(mena.id)) as nbr_agee_homme_menage,
                                            0 as nbr_agee_homme_individu,
                                            0 as nbr_agee_fille_menage,
                                            0 as nbr_agee_fille_individu


                                        from 
                                            menage as mena 
                                            inner join fokontany as foko on mena.id_fokontany= foko.id 
                                            inner join commune as com on com.id= foko.id_commune 
                                            inner join district as dist on com.district_id= dist.id 
                                            inner join region as reg on dist.region_id= reg.id 
                                            inner join menage_beneficiaire as mb on mena.id = mb.id_menage 
                                        where  
                                            (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) >= 60
                                            and mena.sexe = 'H'

                                        group by 
                                            reg.id,
                                            dist.id,
                                            com.id 
                                    )
                                    UNION
                                    (
                                        select 

                                            reg.id as id_region,
                                            reg.nom as nom_region,
                                            dist.id as id_district,
                                            dist.nom as nom_dist,
                                            com.id as id_commune,
                                            com.nom as nom_com,

                                            0 as nbr_enfant_homme,
                                            0 as nbr_enfant_fille,
                                            0 as nbr_agescolaire_fille,
                                            0 as nbr_agescolaire_homme,
                                            0 as nbr_agetravaille_homme_menage,
                                            0 as nbr_agetravaille_homme_individu,
                                            0 as nbr_agetravaille_fille_menage,
                                            0 as nbr_agetravaille_fille_individu,

                                            0 as nbr_agee_homme_menage,
                                            count(DISTINCT(indi.id)) as nbr_agee_homme_individu,
                                            0 as nbr_agee_fille_menage,
                                            0 as nbr_agee_fille_individu


                                        from 
                                            individu as indi 
                                            inner join fokontany as foko on indi.id_fokontany= foko.id 
                                            inner join commune as com on com.id= foko.id_commune 
                                            inner join district as dist on com.district_id= dist.id 
                                            inner join region as reg on dist.region_id= reg.id 
                                            inner join individu_beneficiaire as mb on indi.id = mb.id_individu 
                                        where  
                                            (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 18
                                            and (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', indi.date_naissance)) < 60
                                            and indi.sexe = 'H'

                                        group by 
                                            reg.id,
                                            dist.id,
                                            com.id 
                                    )
                                    UNION
                                    (
                                        select 

                                            reg.id as id_region,
                                            reg.nom as nom_region,
                                            dist.id as id_district,
                                            dist.nom as nom_dist,
                                            com.id as id_commune,
                                            com.nom as nom_com,

                                            0 as nbr_enfant_homme,
                                            0 as nbr_enfant_fille,
                                            0 as nbr_agescolaire_fille,
                                            0 as nbr_agescolaire_homme,
                                            0 as nbr_agetravaille_homme_menage,
                                            0 as nbr_agetravaille_homme_individu,
                                            0 as nbr_agetravaille_fille_menage,
                                            0 as nbr_agetravaille_fille_individu,

                                            0 as nbr_agee_homme_menage,
                                            0 as nbr_agee_homme_individu,
                                            count(DISTINCT(mena.id))  as nbr_agee_fille_menage,
                                            0 as nbr_agee_fille_individu


                                        from 
                                            menage as mena 
                                            inner join fokontany as foko on mena.id_fokontany= foko.id 
                                            inner join commune as com on com.id= foko.id_commune 
                                            inner join district as dist on com.district_id= dist.id 
                                            inner join region as reg on dist.region_id= reg.id 
                                            inner join menage_beneficiaire as mb on mena.id = mb.id_menage 
                                        where  
                                            (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', mena.date_naissance)) >= 60
                                            and mena.sexe = 'F'

                                        group by 
                                            reg.id,
                                            dist.id,
                                            com.id 
                                    )
                                    UNION
                                    (
                                        select 

                                            reg.id as id_region,
                                            reg.nom as nom_region,
                                            dist.id as id_district,
                                            dist.nom as nom_dist,
                                            com.id as id_commune,
                                            com.nom as nom_com,

                                            0 as nbr_enfant_homme,
                                            0 as nbr_enfant_fille,
                                            0 as nbr_agescolaire_fille,
                                            0 as nbr_agescolaire_homme,
                                            0 as nbr_agetravaille_homme_menage,
                                            0 as nbr_agetravaille_homme_individu,
                                            0 as nbr_agetravaille_fille_menage,
                                            0 as nbr_agetravaille_fille_individu,

                                            0 as nbr_agee_homme_menage,
                                            0 as nbr_agee_homme_individu,
                                            0 as nbr_agee_fille_menage,
                                            count(DISTINCT(indi.id)) as nbr_agee_fille_individu


                                        from 
                                            individu as indi 
                                            inner join fokontany as foko on indi.id_fokontany= foko.id 
                                            inner join commune as com on com.id= foko.id_commune 
                                            inner join district as dist on com.district_id= dist.id 
                                            inner join region as reg on dist.region_id= reg.id 
                                            inner join individu_beneficiaire as mb on indi.id = mb.id_individu 
                                        where  
                                            (SELECT DATE_PART('year', mb.date_inscription) - DATE_PART('year', indi.date_naissance)) >= 60
                                            and indi.sexe = 'F'

                                        group by 
                                            reg.id,
                                            dist.id,
                                            com.id 
                                    )

                                ) niveau_1

                                        group by 
                                            nom_region,
                                            nom_dist,
                                            nom_com,
                                            id_commune
                        

                " ;

        return $this->db->query($sql)->result();
    }
    //Fin Req1-theme 1

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
