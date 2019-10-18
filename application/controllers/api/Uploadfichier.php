<?php
//harizo
define ('SITE_ROOT', realpath(dirname(__FILE__)));
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadfichier extends CI_Controller {
	 public function __construct() {
        parent::__construct();
       
    }
	public function save_recommandation() {	
		$erreur="aucun";
		$replace=array('e','e','e','a','o','c','_','_','_');
		$search= array('é','è','ê','à','ö','ç',' ','&','°');
		$repertoire= $_POST['repertoire'];
		$repertoire=str_replace($search,$replace,$repertoire);
		//The name of the directory that we need to create.
		$directoryName = dirname(__FILE__) ."/../../../../" .$repertoire;
		//Check if the directory already exists.
		if(!is_dir($directoryName)){
			//Directory does not exist, so lets create it.
			mkdir($directoryName, 0777,true);
		}				

		$emplacement=array();
		$emplacement[0]=dirname(__FILE__) ."/../../../../" .$repertoire.'/';
		$config['upload_path']          = dirname(__FILE__) ."/../../../../".$repertoire;
		$config['allowed_types'] = 'gif|jpg|png|xls|xlsx|doc|docx|pdf|txt';
		$config['max_size'] = 222048;
		$config['overwrite'] = TRUE;
		if (isset($_FILES['file']['tmp_name'])) {
			$name=$_FILES['file']['name'];
			$name1=str_replace($search,$replace,$name);
			$emplacement[1]=$name1;
			$emplacement[2]=$repertoire;
			$config['file_name'] = $name1;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$ff=$this->upload->do_upload('file');
		} else {
            echo 'File upload not found';
		} 
		echo json_encode($emplacement);
	}  
	public function prendre_fichier()  {
		$filename = $_POST["nom_fichier"]; 
		$rep = $_POST["repertoire"];
		$data=$rep.$filename;    
		$this->load->helper('download');
		$name = 'h'.$filename;
		force_download($name, $data);
        echo json_encode($data);
	}  	
}
?>