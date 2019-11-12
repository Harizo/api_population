<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Importerdecoupageadministratif_model extends CI_Model
{
    protected $table = 'region';

	public function selectionregion($nom) {
		$requete="select id,nom,code from region where lower(nom)='".$nom."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectionregionparid($nom) {
		$requete="select id,nom,code from region where nom like'%".$nom."%'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectionregionparcode($code) {
		$requete="select id,nom,code from region where code='".$code."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectiondistrict($nom,$id_region) {
		if(intval($id_region)==5) {
			$requete="select id,nom,code from district where region_id ='".$id_region."' limit 1";
		} else {
			$requete="select id,nom,code from district where lower(nom)='".$nom."' and region_id ='".$id_region."' limit 1";
		}	
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectiondistrictparid($id) {
			$requete="select id,nom,code,region_id from district where id ='".$id."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectiondistrictparcode($code) {
			$requete="select id,nom,code,region_id from district where code='".$code."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function Coderegionsuivant() {
		$requete="select count(*) as nombreregion from region";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectioncommune($nom,$id_district) {
		$requete="select id,nom,code from commune where lower(nom)='".$nom."' and district_id ='".$id_district."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectioncommuneparid($id) {
		$requete="select id,nom,code,district_id from commune where id='".$id."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectioncommuneparcode($code) {
		$requete="select id,nom,code,district_id from commune where code='".$code."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectionfokontany($nom,$id_commune) {
		$requete="select id,nom,code from fokontany where lower(nom)='".$nom."' and id_commune ='".$id_commune."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectionfokontanyparcode($code) {
		$requete="select id,nom,code,id_commune from fokontany where code='".$code."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function RAZ_Fokontany() {
		$requete="delete from fokontany;
			ALTER SEQUENCE fokontany_id_seq RESTART WITH 1	";
		$query = $this->db->query($requete);
        return 'OK';				
	}
	public function selectionparametres($libelle,$nomdetable) {
		$requete="select id,libelle from ".$nomdetable." where lower(libelle)='".$libelle."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectionzone($libelle) {
		$requete="select id,libelle,code from zone where lower(libelle)='".$libelle."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectionposte($libelle,$id_zone) {
		$requete="select id,libelle,code from poste where lower(libelle)='".$libelle."' and id_zone='".$id_zone."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectionstation($libelle,$id_poste) {
		$requete="select id,libelle,code from station where meteo_acridienne=1 and lower(localite)='".$libelle."' and id_post='".$id_poste."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectionstationmeteo($libelle,$id_poste) {
		$requete="select id,libelle,code from station where meteo_acridienne=0 and lower(localite)='".$libelle."' and id_post='".$id_poste."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectionstationparlibelle($libelle) {
		$requete="select id,id_post,libelle,code from station where meteo_acridienne=1 and lower(localite)='".$libelle."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectionprospectionparidreleve($reference) {
		$requete="select id,reference_prospection from fiche_prospection where reference_prospection='".$reference."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function selectionpesticide($libelle) {
		$requete="select id,libelle from pesticide where libelle='".$libelle."' limit 1";
		$query = $this->db->query($requete);
        return $query->result();				
	}
	public function listedetailmeteo() {
		$requete="select z.libelle as zone,p.libelle as poste,s.libelle as station,"
			." fm.reference_fiche_meteo,fm.mois,fm.annee,s.latitude,s.longitude,s.altitude"
			." from fiche_meteo as fm"
			." left outer join station as s on s.id=fm.id_station"
			." left outer join poste as p on p.id=fm.id_post"
			." left outer join zone as z on z.id=p.id_zone"
			." order by fm.annee,z.libelle,p.libelle,s.libelle,fm.mois"	
			." limit 500";
		$query = $this->db->query($requete);
        return $query->result();				
	}	
	public function listedetailautonome($id_debut,$id_fin,$id_debut_detail,$id_fin_detail) {
		$requete="select fm.id,z.libelle as zone,p.libelle as poste,s.libelle as station,"
			." fm.date_envoi,fm.mois,fm.annee,s.latitude,s.longitude,s.altitude"
			." from fiche_autonome as fm"
			." left outer join station as s on s.id=fm.id_station"
			." left outer join poste as p on p.id=fm.id_post"
			." left outer join zone as z on z.id=p.id_zone"
			." where fm.id >=".$id_debut." and fm.id <=".$id_fin
			." order by fm.annee,z.libelle,p.libelle,s.libelle,fm.mois"	
			." limit 500";
		$enreg = $this->db->query($requete)->result();
		$ret=array();
		if($enreg) {
			foreach($enreg as $k=>$val) {
				$tmp=array();
				$tmp["id"]=$val->id;
				$tmp["zone"]=$val->zone;
				$tmp["poste"]=$val->poste;
				$tmp["station"]=$val->station;
				$tmp["date_envoi"]=$val->date_envoi;
				$tmp["mois"]=$val->mois;
				$tmp["annee"]=$val->annee;
				$tmp["latitude"]=$val->latitude;
				$tmp["longitude"]=$val->longitude;
				$tmp["altitude"]=$val->altitude;
				$reqdet= "select * from traitement_autonome where id_fiche_meteo=".$val->id
				." and id >=".$id_debut_detail." and id <=".$id_fin_detail
				." order by jour,heure";
				$detail = $this->db->query($reqdet)->result();
				$tmp["detail"]=$detail;
				$ret[]=$tmp;
			}	
		}
		return $ret;
	}	
	public function testerexistenceentetemeteo($id_poste,$id_station,$mois,$annee) {
		$requete="select id from fiche_meteo where id_post='".$id_poste."' and id_station='".$id_station."' and mois='".$mois."' and annee='".$annee."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}	
	public function testerexistenceentetestationautonome($id_poste,$id_station,$mois,$annee) {
		$requete="select id from fiche_autonome where id_post='".$id_poste."' and id_station='".$id_station."' and mois='".$mois."' and annee='".$annee."'";
		$query = $this->db->query($requete);
        return $query->result();				
	}	
    public function add($region)
    {
        $this->db->set($this->_set($region))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $region)
    {
        $this->db->set($this->_set($region))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($region)
    {
        return array(
            'code'       =>      $region['code'],
            'nom'        =>      $region['nom'],
            'surface'    =>      $region['surface']                       
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
                        ->order_by('nom')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
	public function CorrigerMiseajourCommuneDistrictRegion() {
		$this->load->model('region_model', 'regionManager');
		$this->load->model('district_model', 'districtManager');
		$this->load->model('commune_model', 'communeManager');
		$liste_region = $this->regionManager->findAll();
		$liste_district = $this->districtManager->findAll();
		$liste_commune = $this->communeManager->findAll();
		$tadiavo=array('&eacute;','e','e','a','o','c','-');
		$soloy= array('é','è','ê','à','ö','ç',' ');
		foreach($liste_region as $ind=>$v) {
			$x= strpos($v->nom,' ');
			if($x >0) {
				$nomregion=$v->nom;
				$id=$v->id;
				$code=$v->code;
				$surface=$v->surface;
				$nomregion=str_replace($tadiavo,$soloy,$nomregion);
                $data = array(
                    'code' => $code,
                    'nom' => $nomregion,
                    'surface' => $surface
                );               
				$ok=$this->regionManager->update($id, $data);		
				
			}		
		}
		foreach($liste_district as $ind=>$v) {
			$x= strpos($v->nom,' ');
			if($x >0) {
				$nomdistrict=$v->nom;
				$code=$v->code;
				$region_id=$v->region_id;
				$id=$v->id;
				$nomdistrict=str_replace($tadiavo,$soloy,$nomdistrict);		
                $data = array(
                    'code' => $code,
                    'nom' => $nomdistrict,
                    'region_id' => $region_id
                );               
				$ok=$this->districtManager->update($id, $data);	
			}		
		}
		foreach($liste_commune as $ind=>$v) {
			$x= strpos($v->nom,' ');
			if($x >0) {
				$nomcommune=$v->nom;
				$code=$v->code;
				$district_id=$v->district_id;
				$id=$v->id;
				$nomcommune=str_replace($tadiavo,$soloy,$nomcommune);				
                $data = array(
                    'code' => $code,
                    'nom' => $nomcommune,
                    'district_id' => $district_id
                );               
				$ok=$this->communeManager->update($id, $data);	
			}			
		}
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

}
