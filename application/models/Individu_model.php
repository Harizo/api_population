<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Individu_model extends CI_Model {
    protected $table = 'individu';

    public function add($individu) {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($individu))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $individu) {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($individu))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($individu) {
		// Affectation des valeurs
        return array(
			'id_menage'                => $individu['id_menage'],
			'identifiant_unique'       => $individu['identifiant_unique'],
			'identifiant_appariement'  => $individu['identifiant_appariement'],
			'date_enregistrement'      => $individu['date_enregistrement'],
			'numero_ordre'             => $individu['numero_ordre'],
			'numero_ordre_pere'        => $individu['numero_ordre_pere'],
			'numero_ordre_mere'        => $individu['numero_ordre_mere'],
			'inscription_etatcivil'    => $individu['inscription_etatcivil'],
			'numero_extrait_naissance' => $individu['numero_extrait_naissance'],
			'id_groupe_appartenance'   => $individu['id_groupe_appartenance'],
			'frequente_ecole'          => $individu['frequente_ecole'],
			'avait_frequente_ecole'    => $individu['avait_frequente_ecole'],
			'nom_ecole'                => $individu['nom_ecole'],
			'occupation'                => $individu['occupation'],
			'statut'                   => $individu['statut'],
			'date_sortie'              => $individu['date_sortie'],
			'flag_integration_donnees' => $individu['flag_integration_donnees'],
			'nouvelle_integration'     => $individu['nouvelle_integration'],
			'commentaire'              => $individu['commentaire'],
			'possede_cin'              => $individu['possede_cin'],
			'nom'                      => $individu['nom'],
			'prenom'                   => $individu['prenom'],
			'cin'                      => $individu['cin'],
			'date_naissance'           => $individu['date_naissance'],
			'sexe'                     => $individu['sexe'],
			'id_liendeparente'         => $individu['id_liendeparente'],
			'id_handicap_visuel'       => $individu['id_handicap_visuel'],
			'id_handicap_parole'       => $individu['id_handicap_parole'],
			'id_handicap_auditif'      => $individu['id_handicap_auditif'],
			'id_handicap_mental'       => $individu['id_handicap_mental'],
			'id_handicap_moteur'       => $individu['id_handicap_moteur'],
			'id_type_ecole'            => $individu['id_type_ecole'],
			'id_niveau_de_classe'      => $individu['id_niveau_de_classe'],
			'id_situation_matrimoniale' => $individu['id_situation_matrimoniale'],
			'id_fokontany'              => $individu['id_fokontany'],
			'id_acteur'                 => $individu['id_acteur'],
			'decede'                    => $individu['decede'],
			'date_deces'                => $individu['date_deces'],
			'chef_menage'               => $individu['chef_menage'],
			'handicap_visuel'           => $individu['handicap_visuel'],
			'handicap_parole'           => $individu['handicap_parole'],
			'handicap_auditif'          => $individu['handicap_auditif'],
			'handicap_moteur'           => $individu['handicap_moteur'],
			'handicap_mental'           => $individu['handicap_mental'],
			'id_indice_vulnerabilite'   => $individu['id_indice_vulnerabilite'],
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
    public function findByIdFokontany($id_fokontany) {
		// Selection individu par id_fokontany
        $result =  $this->db->select('*')
                        ->from($this->table)
						->where("id_fokontany", $id_fokontany)
                        ->order_by('nom')
                        ->order_by('prenom')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findById($id)  {
		// Selection par id
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
    public function findAllByMenage($menage_id)
		// Selection individu par ménage 
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id')
                        ->where("id_menage", $menage_id)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
}
?>
