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


    //CODE HARIZO

        public function beneficiare_sortie_programme()//effectif beneficiaire sorti du programme (41)
        {

                $sql = "select sum(principale.nombre_menage)  as nombre_menage, 
                         sum(principale.nombre_individu) as nombre_individu,
                         principale.intervention,
                         principale.sexe


                    FROM (select count(mb.id) as nombre_menage, 0 as nombre_individu, m.sexe as sexe ,
                            it.intitule as intervention
                            from menage as m
                            join menage_beneficiaire as mb on mb.id_menage = m.id
                            join intervention as it on it.id = mb.id_intervention  
                        where mb.date_sortie is not null
                        group by it.id, m.sexe 

                        UNION 

                        select 0 as nombre_menage, count(ib.id) as nombre_individu, i.sexe as sexe ,
                                it.intitule as intervention

                            from individu as i
                            join individu_beneficiaire as ib on ib.id_individu=i.id
                            join intervention as it on it.id=ib.id_intervention
                        where ib.date_sortie is not null
                        group by it.id, i.sexe 

                         ) principale

                group by 
                        principale.intervention, principale.sexe" ;


            return $this->db->query($sql)->result();

      
        }


        public function nombre_beneficiaire_handicap()//Effectif beneficiaire handicapés(40)
        {
             $sql = "select sum(principale.nbr_hand_visu) as nbr_hand_visu,
                            sum(principale.nbr_hand_paro) as nbr_hand_paro,
                            sum(principale.nbr_hand_audi) as nbr_hand_audi,
                            sum(principale.nbr_hand_ment) as nbr_hand_ment,
                            sum(principale.nbr_hand_mote) as nbr_hand_mote,
                            principale.intervention


                    FROM (

                            select count(enq_ind.id) as nbr_hand_visu,
                                   0 as nbr_hand_paro,
                                   0 as nbr_hand_audi,
                                   0 as nbr_hand_ment,
                                   0 as nbr_hand_mote,
                                   int.intitule as intervention

                            from enquete_individu as enq_ind

                                join individu as i on i.id = enq_ind.id_individu
                                join individu_beneficiaire as indiv_bene on i.id = indiv_bene.id_individu
                                join intervention as int on int.id = indiv_bene.id_intervention
                                
                                where enq_ind.id_handicap_visuel is not null
                                and ((select count(*) from individu_beneficiaire as indiv_bene where indiv_bene.id_individu = enq_ind.id_individu) > 0)

                                group by int.id

                            UNION

                            select 0 as nbr_hand_visu, 
                                   count(enq_ind.id) as nbr_hand_paro,
                                   0 as nbr_hand_audi,
                                   0 as nbr_hand_ment,
                                   0 as nbr_hand_mote,
                                   int.intitule as intervention

                            from enquete_individu as enq_ind

                                join individu as i on i.id = enq_ind.id_individu
                                join individu_beneficiaire as indiv_bene on i.id = indiv_bene.id_individu
                                join intervention as int on int.id = indiv_bene.id_intervention

                                where enq_ind.id_handicap_parole is not null
                                and ((select count(*) from individu_beneficiaire as indiv_bene where indiv_bene.id_individu = enq_ind.id_individu) > 0)
                                group by int.id

                            UNION

                            select 0 as nbr_hand_visu, 
                                   0 as nbr_hand_paro,
                                   count(enq_ind.id) as nbr_hand_audi,
                                   0 as nbr_hand_ment,
                                   0 as nbr_hand_mote,
                                   int.intitule as intervention

                            from enquete_individu as enq_ind

                                join individu as i on i.id = enq_ind.id_individu
                                join individu_beneficiaire as indiv_bene on i.id = indiv_bene.id_individu
                                join intervention as int on int.id = indiv_bene.id_intervention

                                where enq_ind.id_handicap_auditif is not null
                                and ((select count(*) from individu_beneficiaire as indiv_bene where indiv_bene.id_individu = enq_ind.id_individu) > 0)
                                group by int.id

                            UNION

                            select 0 as nbr_hand_visu, 
                                   0 as nbr_hand_paro,
                                   0 as nbr_hand_audi,
                                   count(enq_ind.id) as nbr_hand_ment,
                                   0 as nbr_hand_mote,
                                   int.intitule as intervention

                            from enquete_individu as enq_ind

                                join individu as i on i.id = enq_ind.id_individu
                                join individu_beneficiaire as indiv_bene on i.id = indiv_bene.id_individu
                                join intervention as int on int.id = indiv_bene.id_intervention

                                where enq_ind.id_handicap_mental is not null
                                and ((select count(*) from individu_beneficiaire as indiv_bene where indiv_bene.id_individu = enq_ind.id_individu) > 0)
                                group by int.id


                            UNION

                            select 0 as nbr_hand_visu, 
                                   0 as nbr_hand_paro,
                                   0 as nbr_hand_audi,
                                   0 as nbr_hand_ment,
                                   count(enq_ind.id) as nbr_hand_mote,
                                   int.intitule as intervention

                            from enquete_individu as enq_ind

                                join individu as i on i.id = enq_ind.id_individu
                                join individu_beneficiaire as indiv_bene on i.id = indiv_bene.id_individu
                                join intervention as int on int.id = indiv_bene.id_intervention

                                where enq_ind.id_handicap_moteur is not null
                                and ((select count(*) from individu_beneficiaire as indiv_bene where indiv_bene.id_individu = enq_ind.id_individu) > 0)
                                group by int.id
                            

                         ) principale

                    group by principale.intervention

                " ;

                return $this->db->query($sql)->result();
        }

        public function total_transfert($id_type_transfert, $date_debut, $date_fin)
        {
            $sql = "

                    select int.intitule as intitule_intervention,
                           sum(smdt.valeur_quantite) as quantite,
                           um.description as unite_mesure,
                           dtt.description as detail_type_transfert


                    from suivi_menage_detail_transfert as smdt

                            join suivi_menage_entete as sme on sme.id = smdt.id_suivi_menage_entete
                            join intervention as int on int.id = sme.id_intervention
                            join detail_type_transfert as dtt on dtt.id = smdt.id_detail_type_transfert
                            join unite_mesure as um on um.id = dtt.id_unite_mesure
                            join type_transfert as tt on tt.id = dtt.id_type_transfert
                    where tt.id = ".$id_type_transfert." and sme.date_suivi BETWEEN '".$date_debut."' AND '".$date_fin."'

                    group by um.id, int.id, dtt.id,int.id



            " ;

            return $this->db->query($sql)->result();
        }


        public function Moyenne_transfert($id_type_transfert, $date_debut, $date_fin)
        {
            $sql = "

                    select int.intitule as intitule_intervention,
                           (sum(smdt.valeur_quantite)/count(smdt.id)) as moyenne,
                           um.description as unite_mesure,
                           dtt.description as detail_type_transfert


                    from suivi_menage_detail_transfert as smdt

                            join suivi_menage_entete as sme on sme.id = smdt.id_suivi_menage_entete
                            join intervention as int on int.id = sme.id_intervention
                            join detail_type_transfert as dtt on dtt.id = smdt.id_detail_type_transfert
                            join unite_mesure as um on um.id = dtt.id_unite_mesure
                            join type_transfert as tt on tt.id = dtt.id_type_transfert
                    where tt.id = ".$id_type_transfert." and sme.date_suivi BETWEEN '".$date_debut."' AND '".$date_fin."'

                    group by um.id, int.id, dtt.id,int.id



            " ;

            return $this->db->query($sql)->result();
        }

       
    //FIN CODE HARIZO

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
