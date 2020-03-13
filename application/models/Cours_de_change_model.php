<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cours_de_change_model extends CI_Model {
    protected $table = 'cours_de_change';

    public function add($cours_de_change) {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($cours_de_change))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $cours_de_change) {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($cours_de_change))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function updateByDate($date_cours,$id_devise,$cours_de_change) {
		// Mise à jour d'un enregitrement par date
        $this->db->set($this->_set($cours_de_change))
                            ->where('id_devise', (int) $id_devise)
                            ->where('date_cours', $date_cours)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($cours_de_change) {
		// Affectation des valeurs
        return array(
            'id_devise'  => $cours_de_change['id_devise'],
            'date_cours' => $cours_de_change['date_cours'],
            'cours'      => $cours_de_change['cours'],
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
    public function findById($id) {
		// Selection par id
        $result =  $this->db->select('*')
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
	public function RequeteTitreDevise() {
		$requete = "select base.id,
			 case when base.position_caractere >0 then
				concat_ws('_',substring(lower(base.description) from 1 for (base.position_caractere - 1)),
				substring(lower(base.description) from (base.position_caractere + 1)))
			 else
				lower(base.description)
			 end as description,
			 base.description as titre,base.position_caractere
			 from 
			 ( select 0 as id,'Date' as description,0 as position_caractere
			 UNION
			 select id,description,position(' ' in description) as position_caractere from devise
			 order by id ) as base
				 ";
		$result = $this->db->query($requete)->result();
        if($result) {
            return $result;
        }else{
            return null;
        }     		
	}
	public function RequeteTitreValeurCours() {
		$requete = "select 
			 case when base.position_caractere >0 then
				concat_ws('_',substring(lower(base.description) from 1 for (base.position_caractere - 1)),
				substring(lower(base.description) from (base.position_caractere + 1)))
			 else
				lower(base.description)
			 end as titre
			 from 
			 ( select 0 as id,'Date' as description,0 as position_caractere
			 UNION
			 select id,description,position(' ' in description) as position_caractere from devise
			 order by id ) as base
				 ";
		$result = $this->db->query($requete)->result();
        if($result) {
            return $result;
        }else{
            return null;
        }     		
	}
	// Récupération liste cours de change par interval de date et le transformer en colonne de devise et par ligne de date
	// Exemple [{10/03/2020,1250,2500,3500},{11/03/2020,1300,2560,3590}]les 3 chiffres represente le cours de change de devise USD,EUR,LIVRE
	public function Requetedonneescroisee($date_debut,$date_fin) {
		$retour=array();
		$date_debut = new DateTime($date_debut);
		$date_fin = new DateTime($date_fin);
		$date_debut_titre=$date_debut->format("d/m/Y");
		$date_fin_titre=$date_fin->format("d/m/Y");
		$titrecolonne=$this->RequeteTitreDevise();
		while ($date_debut <= $date_fin) {
			$date=$date_debut->format("Y-m-d");
			$requete = "select d.id,c.date_cours,c.cours"
						." from cours_de_change as c"
						." join devise as d on d.id=c.id_devise"
						." where c.date_cours='".$date."'"
						." order by d.id";
			$result = $this->db->query($requete)->result();
			if($result) {
				$nombre_cours_par_date=count($result);
				$valeur_temp=array();
				for($i=0;$i<$nombre_cours_par_date;$i++) {
					if($i==0) {
						$valeur_temp["date"]=$date_debut_titre;
						$valeur_temp[$titrecolonne[($i + 1)]->description]=$result[$i]->cours;
					} else {
						$valeur_temp[$titrecolonne[($i + 1)]->description]= $result[$i]->cours;
					}
				}
				$retour[] = $valeur_temp;				
			}	
			$date_debut->add(new DateInterval("P1D"));
			$date_debut_titre=$date_debut->format("d/m/Y");
		}	
		return $retour;
	}	
	// Même principe que la fonction Requetedonneescroisee mais à une différence : les id sont en paramètres et non la date
	// Récupération liste cours de change par interval de date et le transformer en colonne de devise et par ligne de date
	// Exemple [{10/03/2020,1250,2500,3500},{11/03/2020,1300,2560,3590}]les 3 chiffres represente le cours de change de devise USD,EUR,LIVRE
	public function RequetedonneescroiseeById($date_cours,$liste_id_cours) {
		$retour=array();
		$date_cours = new DateTime($date_cours);
		$date_cours_titre=$date_cours->format("d/m/Y");
		$titrecolonne=$this->RequeteTitreDevise();
			$requete = "select d.id,c.date_cours,c.cours"
						." from cours_de_change as c"
						." join devise as d on d.id=c.id_devise"
						." where c.id in(" . implode(",", $liste_id_cours) . ") "
						." order by d.id";
			$result = $this->db->query($requete)->result();		
			if($result) {
				$nombre_resultat_cours=count($result);
				$valeur_temp=array();
				for($i=0;$i<$nombre_resultat_cours;$i++) {
					if($i==0) {
						$valeur_temp["date"]=$date_cours_titre;
						$valeur_temp[$titrecolonne[($i + 1)]->description]=$result[$i]->cours;
					} else {
						$valeur_temp[$titrecolonne[($i + 1)]->description]= $result[$i]->cours;
					}
				}
				$retour[] = $valeur_temp;				
			}	
		return $retour;
	}	
	public function TesterSiMiseajour($date_cours,$id_devise) {
		$requete = "select id from cours_de_change where date_cours='".$date_cours."' and id_devise='".$id_devise."'";
		$result = $this->db->query($requete)->result();
        if($result) {
            return $result;
        }else{
            return null;
        }     		
	}
}
?>