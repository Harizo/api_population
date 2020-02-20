<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menage_model extends CI_Model {
    protected $table = 'menage';

    public function add($menage) {
		// Ajout d'un enregitrement
        $this->db->set($this->_set($menage))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function addchefmenage($menage) {
		// Ajout che ménage
        $this->db->set($this->_setbeneficiaire_acteur($menage))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $menage) {
		// Mise à jour d'un enregitrement
        $this->db->set($this->_set($menage))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($menage) {
		// Affectation des valeurs
        return array(
			'identifiant_unique'     => $menage['identifiant_unique'],
			'identifiant_appariement'=> $menage['identifiant_appariement'],
			'numero_sequentiel'      => $menage['numero_sequentiel'],
			'lieu_residence'         => $menage['lieu_residence'],
			'surnom_chefmenage'      => $menage['surnom_chefmenage'],
			'nom'                    => $menage['nom'],
			'prenom'                 => $menage['prenom'],
			'cin'                    => $menage['cin'],
			'chef_menage'            => $menage['chef_menage'],
			'adresse'                => $menage['adresse'],
			'date_naissance'         => $menage['date_naissance'],
			'profession'             => $menage['profession'],
			'id_situation_matrimoniale' => $menage['id_situation_matrimoniale'],
			'sexe'                   => $menage['sexe'],
			'date_inscription'       => $menage['date_inscription'],
			'nom_prenom_pere'        => $menage['nom_prenom_pere'],
			'nom_prenom_mere'        => $menage['nom_prenom_mere'],
			'telephone'              => $menage['telephone'],
			'statut'                 => $menage['statut'],
			'date_sortie'            => $menage['date_sortie'],
			'nom_enqueteur'            => $menage['nom_enqueteur'],
			'date_enquete'            => $menage['date_enquete'],
			'nom_superviseur_enquete' => $menage['nom_superviseur_enquete'],
			'date_supervision'       => $menage['date_supervision'],
			'flag_integration_donnees' => $menage['flag_integration_donnees'],
			'nouvelle_integration'   => $menage['nouvelle_integration'],
			'commentaire'            => $menage['commentaire'],
			'revenu_mensuel'         => $menage['revenu_mensuel'],
			'depense_mensuel'        => $menage['depense_mensuel'],
			'id_fokontany'           => $menage['id_fokontany'],
			'id_acteur'              => $menage['id_acteur'],
			'etat_groupe'            => $menage['etat_groupe'],
			'decede'                 => $menage['decede'],
			'date_deces'             => $menage['date_deces'],
			'id_indice_vulnerabilite' => $menage['id_indice_vulnerabilite'],
        );
    }
    public function _setbeneficiaire_acteur($menage) {
		// Affectation des valeurs par acteur
        return array(
			'identifiant_unique'     => $menage['identifiant_unique'],
			'identifiant_appariement'=> $menage['identifiant_appariement'],
			'numero_sequentiel'      => $menage['numero_sequentiel'],
			'lieu_residence'         => $menage['lieu_residence'],
			'surnom_chefmenage'      => $menage['surnom_chefmenage'],
			'nom'                    => $menage['nom'],
			'prenom'                 => $menage['prenom'],
			'cin'                    => $menage['cin'],
			'chef_menage'            => $menage['chef_menage'],
			'adresse'                => $menage['adresse'],
			'date_naissance'         => $menage['date_naissance'],
			'profession'             => $menage['profession'],
			'id_situation_matrimoniale' => $menage['id_situation_matrimoniale'],
			'sexe'                   => $menage['sexe'],
			'date_inscription'       => $menage['date_inscription'],
			'nom_prenom_pere'        => $menage['nom_prenom_pere'],
			'nom_prenom_mere'        => $menage['nom_prenom_mere'],
			'telephone'              => $menage['telephone'],
			'statut'                 => $menage['statut'],
			'date_sortie'            => $menage['date_sortie'],
			'nom_enqueteur'            => $menage['nom_enqueteur'],
			'date_enquete'            => $menage['date_enquete'],
			'nom_superviseur_enquete' => $menage['nom_superviseur_enquete'],
			'date_supervision'       => $menage['date_supervision'],
			'flag_integration_donnees' => $menage['flag_integration_donnees'],
			'nouvelle_integration'   => $menage['nouvelle_integration'],
			'commentaire'            => $menage['commentaire'],
			'revenu_mensuel'         => $menage['revenu_mensuel'],
			'depense_mensuel'        => $menage['depense_mensuel'],
			'id_fokontany'           => $menage['id_fokontany'],
			'id_acteur'              => $menage['id_acteur'],
			'etat_groupe'            => $menage['etat_groupe'],
			'decede'                 => $menage['decede'],
			'date_deces'             => $menage['date_deces'],
 			'id_indice_vulnerabilite' => $menage['id_indice_vulnerabilite'],
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
		// Selection par id_fokontany
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
}
?>
