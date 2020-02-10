<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Export_excel extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('environment_demo_socio_model', 'Environment_demo_socioManager');
        $this->load->model('systeme_protection_social_model', 'Systeme_protection_socialManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('commune_model', 'CommuneManager');
        $this->load->model('intervention_model', 'InterventionManager');
    }

    public function index_get() 
    {
        
        $menu = $this->get('menu'); 
        $repertoire = $this->get('repertoire'); 
        $nom_file = $this->get('nom_file'); 
        $id_region= $this->get('id_region'); 
        $id_district= $this->get('id_district');
        $id_commune= $this->get('id_commune');
        $id_intervention= $this->get('id_intervention');

        //CODE HARIZO
        $id_type_transfert= $this->get('id_type_transfert');
        $date_debut= $this->get('date_debut');
        $date_fin= $this->get('date_fin');
        //FIN CODE HARIZO
        $now = date('Y-m-d');

        $scolaire_max = date('Y-m-d', strtotime($now. ' -18 years +1 days'));
        $scolaire_min = date('Y-m-d', strtotime($now. ' -7 years')); 
        $agee = date('Y-m-d', strtotime($now. ' -60 years'));

        $travail_max = date('Y-m-d', strtotime($now. ' -60 years +1 days'));
        $travail_min = date('Y-m-d', strtotime($now. ' -18 years'));
        $enfant = date('Y-m-d', strtotime($now. ' -7 years +1 days'));

        
        
        

       

        //CODE HARIZO
            if ($menu == 'req41_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->beneficiare_sortie_programme() ;
            }

            if ($menu == 'req40_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->nombre_beneficiaire_handicap() ;
            }

            if ($menu == 'req42_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->Moyenne_transfert($id_type_transfert, $date_debut, $date_fin) ;
            }

            if ($menu == 'req43_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->total_transfert($id_type_transfert, $date_debut, $date_fin) ;
            }

            if ($menu == 'req10_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->decaissement_par_programme() ;
            }
            if ($menu == 'req11_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->decaissement_par_tutelle() ;
            }
            if ($menu == 'req12_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->decaissement_par_agence_execution() ;
            }
            if ($menu == 'req37_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->montant_budget_non_consommee_par_programme() ;
            }
            if ($menu == 'req36_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->taux_de_decaissement_par_programme() ;
            }
            if ($menu == 'req18_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->proportion_des_intervention_par_type_de_cible() ;
            }

            if ($menu=='req14_theme2')
            {
                $tmp = $this->Systeme_protection_socialManager->req14theme2_interven_nbrinter_budgetinit_peffectif_pcout_region_district();
                if($tmp)
                {
                    $data=$tmp;
                }
                else 
                    $data = array();

            }

            if($menu=='req20_theme2')
            {
                $data = $this->Systeme_protection_socialManager->proportion_des_intervention_avec_critere_sexe() ;
            }

            if ($menu == 'req19_theme2') //Age par rapport à la date de suivi de l'intervention
            {
                $data = $this->Systeme_protection_socialManager->proportion_des_intervention_avec_critere_age() ;
            }

            if ($menu == 'req32_theme2') 
            {
                $data = $this->Systeme_protection_socialManager->nbr_nouveau_beneficiaire($date_debut, $date_fin) ;
            }

            


        //FIN CODE HARIZO


        //CODE CORRIGER Par Harizo
        if ($menu =='req1_theme1') //Age par rapport à la date du jour 
        {
            $data = $this->Environment_demo_socioManager->effectif_par_age_sexe_population($enfant,$scolaire_min,$scolaire_max,$travail_min,$travail_max,$agee);
           
        }
        if ($menu == 'req34_theme2') //Age par rapport à la date d'inscription
        {
            $data = $this->Systeme_protection_socialManager->taux_atteinte_resultat() ;
        }
        if ($menu =='req3_theme1')
        {            
            $data = $this->Environment_demo_socioManager->menage_ayant_efant($enfant,$scolaire_min,$scolaire_max);      
           
        }
        if ($menu=='req7_theme2')//situtation(en cours ou new) par rapport à la debut et fin du programme
        {
            $tmp = $this->Systeme_protection_socialManager->repartition_financement_programme();
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();
        }

        if ($menu=='req8_theme2')//situtation(en cours ou new) par rapport à la debut et fin du programme
        {
            $tmp = $this->Systeme_protection_socialManager->repartition_financement_source_financement();
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();
        }

        if ($menu=='req9_theme2')//situtation(en cours ou new) par rapport à la debut et fin du programme
        {
            $tmp = $this->Systeme_protection_socialManager->repartition_financement_tutelle();
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();
        }

        if ($menu =='req38_theme2')
        {
            $data = $this->Systeme_protection_socialManager->repartition_par_age_sexe_beneficiaire();
            
            
        }
        //fin CODE CORRIGER Par Harizo

        

       

        if ($menu=='req31theme2_interven_nbrinter_program_beneparan_beneprevu_region')
        {
            $tmp = $this->Systeme_protection_socialManager->req31theme2_interven_nbrinter_program_beneparan_beneprevu_region($this->generer_requete_sql($id_region,'*','*',$id_intervention));
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();

        }

        if ($menu=='req34theme2_program_interven_nbrbene_nbrinter_tauxinter_region')
        {
            $tmp = $this->Systeme_protection_socialManager->req34theme2_program_interven_nbrbene_nbrinter_tauxinter_region($this->generer_requete_sql($id_region,'*','*',$id_intervention));
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();

        }



        if ($menu=='req20theme2_interven_pourcenfille_pourcenhomme_pcout')
        {
            $tmp = $this->Systeme_protection_socialManager->req20theme2_interven_pourcenfille_pourcenhomme_pcout();
            if($tmp)
            {
                $data=$tmp;
            }else $data = array();

        }
        


        //Export excel
        $this->export($data, $repertoire, $nom_file, $menu, $date_debut, $date_fin);
        //fin Export excel

    }


    public function export($data, $repertoire, $nom_file, $menu, $date_debut, $date_fin)
    {
        require_once 'Classes/PHPExcel.php';
        require_once 'Classes/PHPExcel/IOFactory.php';

        
        $directoryName = dirname(__FILE__) ."/../../../../exportexcel/".$repertoire;
        
        //Check if the directory already exists.
        if(!is_dir($directoryName))
        {
            mkdir($directoryName, 0777,true);
        }
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("App WEB MPPSPF")
                    ->setLastModifiedBy("App WEB MPPSPF")
                    ->setTitle("App WEB MPPSPF")
                    ->setSubject("App WEB MPPSPF")
                    ->setDescription("App WEB MPPSPF")
                    ->setKeywords("App WEB MPPSPF")
                    ->setCategory("App WEB MPPSPF");

        $ligne=1;            

        $date_debut = date("d-m-Y", strtotime($date_debut));
        $date_fin = date("d-m-Y", strtotime($date_fin));


        // Set Orientation, size and scaling
        // Set Orientation, size and scaling
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
        $objPHPExcel->getActiveSheet()->getPageMargins()->SetLeft(0.64); //***pour marge gauche
        $objPHPExcel->getActiveSheet()->getPageMargins()->SetRight(0.64); //***pour marge droite

        




        $styleTitre = array
        (
        'alignment' => array
            (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                
            ),
        'font' => array
            (
                //'name'  => 'Times New Roman',
                'bold'  => true,
                'size'  => 16
            ),
        );

        $stylesousTitre = array
        ('borders' => array
            (
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
            ),
        'alignment' => array
            (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                
            ),
        'font' => array
            (
                //'name'  => 'Times New Roman',
                'bold'  => true,
                'size'  => 12
            ),
        );

        $Titre1 = array
        (
        'alignment' => array
            (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                
            ),
        'font' => array
            (
                //'name'  => 'Times New Roman',
                'bold'  => true,
                'size'  => 12
            ),
        );

        $stylecontenu = array
        (
            'borders' => array
            (
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
            ),
        'alignment' => array
            (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            )
        );


        //CONTENU
            
            if ($menu == 'req1_theme1') //OK
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":N".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":N".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Région");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".($ligne+1));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'District');
                $objPHPExcel->getActiveSheet()->mergeCells("C".$ligne.":D".($ligne+1));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'Commune');
                $objPHPExcel->getActiveSheet()->mergeCells("E".$ligne.":F".($ligne+1));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, 'Enfant');
                $objPHPExcel->getActiveSheet()->mergeCells("G".$ligne.":H".($ligne));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($ligne+1), 'Homme');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.($ligne+1), 'Femme');

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, 'En âge scolaire');
                $objPHPExcel->getActiveSheet()->mergeCells("I".$ligne.":J".($ligne));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.($ligne+1), 'Homme');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.($ligne+1), 'Femme');

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ligne, 'En âge de travailler');
                $objPHPExcel->getActiveSheet()->mergeCells("K".$ligne.":L".($ligne));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.($ligne+1), 'Homme');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.($ligne+1), 'Femme');

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ligne, 'Âgées');
                $objPHPExcel->getActiveSheet()->mergeCells("M".$ligne.":N".($ligne));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.($ligne+1), 'Homme');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.($ligne+1), 'Femme');

                



                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":N".($ligne+1))->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":N".($ligne+1))->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

               $ligne = $ligne + 2 ;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->nom_region);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value->nom_dist);
                    $objPHPExcel->getActiveSheet()->mergeCells("C".$ligne.":D".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, $value->nom_com);
                    $objPHPExcel->getActiveSheet()->mergeCells("E".$ligne.":F".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, $value->nbr_enfant_homme);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, $value->nbr_enfant_fille);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, $value->nbr_agescolaire_homme);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, $value->nbr_agescolaire_fille);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ligne, $value->nbr_agetravaille_homme);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ligne, $value->nbr_agetravaille_fille);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ligne, $value->nbr_agee_homme);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ligne, $value->nbr_agee_fille);
                  

                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":N".$ligne)->applyFromArray($stylecontenu);
                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":N".$ligne)->getAlignment()->setWrapText(true);
                    $ligne++;
                }
            }

            if ($menu == 'req3_theme1')//OK
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":J".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":J".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Région");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'District');
                $objPHPExcel->getActiveSheet()->mergeCells("C".$ligne.":D".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'Commune');
                $objPHPExcel->getActiveSheet()->mergeCells("E".$ligne.":F".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, 'Effectif des ménages ayant des enfant de 0 - 6 ans');
                $objPHPExcel->getActiveSheet()->mergeCells("G".$ligne.":H".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, 'Effectif des ménages ayant des enfant en âge scolaire');
                $objPHPExcel->getActiveSheet()->mergeCells("I".$ligne.":J".$ligne);


                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":J".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":J".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->nom_reg);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value->nom_dist);
                    $objPHPExcel->getActiveSheet()->mergeCells("C".$ligne.":D".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, $value->nom_com);
                    $objPHPExcel->getActiveSheet()->mergeCells("E".$ligne.":F".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, $value->nbr_ayant_enfant);
                    $objPHPExcel->getActiveSheet()->mergeCells("G".$ligne.":H".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, $value->nbr_ayant_enfant_age_scolaire);
                    $objPHPExcel->getActiveSheet()->mergeCells("I".$ligne.":J".$ligne);


                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":J".$ligne)->applyFromArray($stylecontenu);
                    $ligne++;
                }
            }


            if ($menu == 'req7_theme2')
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":L".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":L".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Programme");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Situation');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Intervention');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":H".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, 'Montant initial');
                $objPHPExcel->getActiveSheet()->mergeCells("I".$ligne.":J".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ligne, 'Montant modifier');
                $objPHPExcel->getActiveSheet()->mergeCells("K".$ligne.":L".$ligne);

                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":L".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":L".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_programme);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $this->etat_nouveau($value->etat_nouveau));
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $value->intitule_intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":H".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, number_format($this->affichage_budget_initial($value),0,","," ")." ".$value->description_devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("I".$ligne.":J".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ligne, number_format($this->affichage_budget_modifie($value),0,","," ")." ".$value->description_devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("K".$ligne.":L".$ligne);


                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":L".$ligne)->applyFromArray($stylecontenu);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":L".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                    $ligne++;
                }


            }

            if ($menu == 'req8_theme2')
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":O".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":O".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Programme");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Situation');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Intervention');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":H".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, 'Source de financement');
                $objPHPExcel->getActiveSheet()->mergeCells("I".$ligne.":K".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ligne, 'Montant initial');
                $objPHPExcel->getActiveSheet()->mergeCells("L".$ligne.":M".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ligne, 'Montant modifier');
                $objPHPExcel->getActiveSheet()->mergeCells("N".$ligne.":O".$ligne);

                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":O".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":O".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_programme);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $this->etat_nouveau($value->etat_nouveau));
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $value->intitule_intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":H".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, $value->nom_source_financement);
                    $objPHPExcel->getActiveSheet()->mergeCells("I".$ligne.":K".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ligne, number_format($this->affichage_budget_initial($value),0,","," ")." ".$value->description_devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("L".$ligne.":M".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ligne, number_format($this->affichage_budget_modifie($value),0,","," ")." ".$value->description_devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("N".$ligne.":O".$ligne);


                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":O".$ligne)->applyFromArray($stylecontenu);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":O".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                    $ligne++;
                }


            }

            if ($menu == 'req9_theme2')
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":O".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":O".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Programme");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Situation');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Intervention');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":H".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, 'Tutelle');
                $objPHPExcel->getActiveSheet()->mergeCells("I".$ligne.":K".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ligne, 'Montant initial');
                $objPHPExcel->getActiveSheet()->mergeCells("L".$ligne.":M".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ligne, 'Montant modifier');
                $objPHPExcel->getActiveSheet()->mergeCells("N".$ligne.":O".$ligne);

                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":O".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":O".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_programme);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $this->etat_nouveau($value->etat_nouveau));
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $value->intitule_intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":H".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, $value->ministere_tutelle);
                    $objPHPExcel->getActiveSheet()->mergeCells("I".$ligne.":K".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ligne, number_format($this->affichage_budget_initial($value),0,","," ")." ".$value->description_devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("L".$ligne.":M".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ligne, number_format($this->affichage_budget_modifie($value),0,","," ")." ".$value->description_devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("N".$ligne.":O".$ligne);


                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":O".$ligne)->applyFromArray($stylecontenu);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":O".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                    $ligne++;
                }


            }

            if ($menu == 'req10_theme2') //OK
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":I".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Programme");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Intervention');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Montant initial');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'Montant révisé');
                $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);

                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_programme);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->intitule_intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, number_format($value->montant_init,0,","," ")." ".$value->devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, number_format($value->montant_revise,0,","," ")." ".$value->devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);


                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".$ligne)->applyFromArray($stylecontenu);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                    $ligne++;
                }
            }
            if ($menu == 'req11_theme2') //OK
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":I".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Tutelle");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Intervention');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Montant initial');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'Montant révisé');
                $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);

                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->tutelle);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->intitule_intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, number_format($value->montant_init,0,","," ")." ".$value->devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, number_format($value->montant_revise,0,","," ")." ".$value->devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);


                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".$ligne)->applyFromArray($stylecontenu);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                    $ligne++;
                }
            }
            if ($menu == 'req12_theme2') //OK
            {
               $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":G".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Tutelle");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Intervention');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, "Agence d'éxécution");
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);


                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_programme);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->intitule_intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $value->nom_acteur);
                    $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);


                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($stylecontenu);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                    $ligne++;
                }
            }
            
            

            if ($menu=='req14_theme2')//OK
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":K".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Intervention");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Région');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'District');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'Effectif');
                $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, 'Coût');
                $objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":K".$ligne);

                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_inter);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->nom_reg);
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $value->nom_dist);
                    $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, number_format($value->effectif_intervention,2,","," ")." %");
                    $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, number_format((int)(($value->total_cout_district * 100)/$value->total_cout_menage),2,","," ")." %");
                    $objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":K".$ligne);

                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($stylecontenu);
                    $ligne++;
                }

            }

            if ($menu == 'req18_theme2') //OK
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":I".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Intervention");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".($ligne+1));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Ménage');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".($ligne));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.($ligne+1), 'Effectif');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($ligne+1), 'Coût');

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Individu');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".($ligne));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.($ligne+1), 'Effectif');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($ligne+1), 'Coût');

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'Groupe');
                $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".($ligne));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.($ligne+1), 'Effectif');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.($ligne+1), 'Coût');



                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".($ligne+1))->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".($ligne+1))->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

               $ligne = $ligne + 2 ;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, number_format($value->stat_menage,2,","," ")." %");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, number_format($value->stat_montant_menage,2,","," ")." %");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, number_format($value->stat_individu,2,","," "));
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, number_format($value->stat_montant_individu,2,","," ")." %");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, number_format($value->stat_groupe,2,","," "));
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, number_format($value->stat_montant_groupe,2,","," ")." %");
                  

                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".$ligne)->applyFromArray($stylecontenu);
                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":I".$ligne)->getAlignment()->setWrapText(true);
                    $ligne++;
                }



            }

            if ($menu == 'req19_theme2')//OK 
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":K".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Intervention");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".($ligne+1));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Enfant');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".($ligne));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.($ligne+1), 'Effectif');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($ligne+1), 'Coût');

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Âge scolaire');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".($ligne));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.($ligne+1), 'Effectif');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($ligne+1), 'Coût');

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'Âge de travailler');
                $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".($ligne));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.($ligne+1), 'Effectif');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.($ligne+1), 'Coût');


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, 'Personne âgées');
                $objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":K".($ligne));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.($ligne+1), 'Effectif');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.($ligne+1), 'Coût');



                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".($ligne+1))->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".($ligne+1))->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

               $ligne = $ligne + 2 ;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, number_format((($value->nbr_enfant * 100)/$value->nbr_total_benaficiare),2,","," ")." %");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, number_format((($value->cout_enfant * 100)/$value->total_cout),2,","," ")." %");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, number_format((($value->nbr_age_scolaire * 100)/$value->nbr_total_benaficiare),2,","," ")." %");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, number_format((($value->cout_age_scolaire * 100)/$value->total_cout),2,","," ")." %");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, number_format((($value->nbr_age_travail * 100)/$value->nbr_total_benaficiare),2,","," ")." %");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, number_format((($value->cout_age_travail * 100)/$value->total_cout),2,","," ")." %");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, number_format((($value->nbr_agee * 100)/$value->nbr_total_benaficiare),2,","," ")." %");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ligne, number_format((($value->cout_agee * 100)/$value->total_cout),2,","," ")." %");
                  

                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($stylecontenu);
                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->getAlignment()->setWrapText(true);
                    $ligne++;
                }
            }

            if($menu=='req20_theme2')//OK
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":G".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Intervention");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".($ligne+1));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Homme');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".($ligne));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.($ligne+1), 'Effectif');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($ligne+1), 'Coût');

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Femme');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".($ligne));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.($ligne+1), 'Effectif');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($ligne+1), 'Coût');



                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".($ligne+1))->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".($ligne+1))->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

               $ligne = $ligne + 2 ;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_interv);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, number_format((($value->nbr_total_homme * 100)/$value->total_beneficiaire),2,","," ")." %");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, number_format((($value->cout_total_intervention_h * 100)/$value->total_cout_menage),2,","," ")." %");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, number_format((($value->nbr_total_femme * 100)/$value->total_beneficiaire),2,","," ")." %");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, number_format((($value->cout_total_intervention_f * 100)/$value->total_cout_menage),2,","," ")." %");
                  

                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($stylecontenu);
                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->getAlignment()->setWrapText(true);
                    $ligne++;
                }
            }

            

            if ($menu == 'req32_theme2')//OK 
            {
               $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":G".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Du: ");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, $date_debut);
                $objPHPExcel->getActiveSheet()->mergeCells("B".$ligne.":D".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, "Au: ");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $date_fin);
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($Titre1);

                $ligne = $ligne + 2 ;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Intervention");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Région');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);
             

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Nombre de nouveaux bénéficiaires');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);


                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_interv);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);


                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->nom_reg);
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $value->nbr_total_benaficiaire);
                    $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);


                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($stylecontenu);
                    $ligne++;
                }
            }

            if ($menu == 'req34_theme2')//OK 
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":K".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Programme");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Intervention');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Région');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'Taux ménage');
                $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, 'Taux individu');
                $objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":K".$ligne);

                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_programme);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->intitule_intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $value->nom_region);
                    $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, number_format((($value->nbr_menage_beneficiaire * 100)/$value->nbr_menage_prevu),2,","," ")." %");
                    $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, number_format((($value->nbr_individu_beneficiaire * 100)/$value->nbr_individu_prevu),2,","," ")." %");
                    $objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":K".$ligne);


                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($stylecontenu);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                    $ligne++;
                }
            }

            
            if ($menu == 'req36_theme2') //OK
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":K".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Programme");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Intervention');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Budget prévu');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'Décaissement');
                $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, 'Taux de décaissement');
                $objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":K".$ligne);

                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_programme);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->intitule_intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, number_format($value->sum_financement_par_intervention_par_programme,0,","," ")." ".$value->devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, number_format($value->sum_decaissement,0,","," ")." ".$value->devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, number_format($value->prop,3,","," ")." %");
                    $objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":K".$ligne);

                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($stylecontenu);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->getAlignment()->setWrapText(true);
                    $ligne++;
                }
                
            }

            if ($menu == 'req37_theme2') //OK
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":K".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Programme");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Budget prévu');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Décaissement');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'Budget non consommé');
                $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, 'Taux de décaissement');
                $objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":K".$ligne);

                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_programme);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, number_format($value->budget_prevu,0,","," ")." ".$value->desc_devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, number_format($value->somme_decaissement,0,","," ")." ".$value->desc_devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, number_format($value->budget_non_comnsommee,0,","," ")." ".$value->desc_devise);
                    $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, number_format($value->prop,2,","," ")." %");
                    $objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":K".$ligne);


                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->applyFromArray($stylecontenu);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":K".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                    $ligne++;
                }
            }

            if ($menu == 'req38_theme2') 
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":P".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":P".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Région");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".($ligne+1));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'District');
                $objPHPExcel->getActiveSheet()->mergeCells("C".$ligne.":D".($ligne+1));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'Commune');
                $objPHPExcel->getActiveSheet()->mergeCells("E".$ligne.":F".($ligne+1));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, 'Intervention');
                $objPHPExcel->getActiveSheet()->mergeCells("G".$ligne.":H".($ligne+1));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, 'Enfant');
                $objPHPExcel->getActiveSheet()->mergeCells("I".$ligne.":J".($ligne));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.($ligne+1), 'Homme');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.($ligne+1), 'Femme');

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ligne, 'En âge scolaire');
                $objPHPExcel->getActiveSheet()->mergeCells("K".$ligne.":L".($ligne));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.($ligne+1), 'Homme');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.($ligne+1), 'Femme');

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ligne, 'En âge de travailler');
                $objPHPExcel->getActiveSheet()->mergeCells("M".$ligne.":N".($ligne));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.($ligne+1), 'Homme');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.($ligne+1), 'Femme');

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$ligne, 'Âgées');
                $objPHPExcel->getActiveSheet()->mergeCells("O".$ligne.":P".($ligne));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.($ligne+1), 'Homme');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.($ligne+1), 'Femme');

                



                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":P".($ligne+1))->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":P".($ligne+1))->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

               $ligne = $ligne + 2 ;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->nom_region);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value->nom_dist);
                    $objPHPExcel->getActiveSheet()->mergeCells("C".$ligne.":D".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, $value->nom_com);
                    $objPHPExcel->getActiveSheet()->mergeCells("E".$ligne.":F".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, $value->intitule_intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("G".$ligne.":H".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, $value->nbr_enfant_homme);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, $value->nbr_enfant_fille);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ligne, $value->nbr_agescolaire_homme);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ligne, $value->nbr_agescolaire_fille);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ligne, $value->nbr_agetravaille_homme);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ligne, $value->nbr_agetravaille_fille);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$ligne, $value->nbr_agee_homme);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$ligne, $value->nbr_agee_fille);
                  

                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":P".$ligne)->applyFromArray($stylecontenu);
                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":P".$ligne)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                    $ligne++;
                }
            }

            

            if ($menu == 'req40_theme2') //OK
            {

                /*$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);*/

                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":M".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":M".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Intervention");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Bénéficiaire avec handicap visuel');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Bénéficiaire avec handicap de la parole');
                $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'Bénéficiaire avec handicap auditif');
                $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, 'Bénéficiaire avec handicap mentale');
                $objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":K".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ligne, 'Bénéficiaire avec handicap moteur');
                $objPHPExcel->getActiveSheet()->mergeCells("L".$ligne.":M".$ligne);

                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":M".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":M".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->nbr_hand_visu);
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":E".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $value->nbr_hand_paro);
                    $objPHPExcel->getActiveSheet()->mergeCells("F".$ligne.":G".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, $value->nbr_hand_audi);
                    $objPHPExcel->getActiveSheet()->mergeCells("H".$ligne.":I".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, $value->nbr_hand_ment);
                    $objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":K".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ligne, $value->nbr_hand_mote);
                    $objPHPExcel->getActiveSheet()->mergeCells("L".$ligne.":M".$ligne);

                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":M".$ligne)->applyFromArray($stylecontenu);
                    $ligne++;
                }
                
            }

            if ($menu == 'req41_theme2') 
            {

                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":H".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Intervention");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Sexe');
              
             

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'Nombre ménage');
                $objPHPExcel->getActiveSheet()->mergeCells("E".$ligne.":F".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, 'Nombre individu');
                $objPHPExcel->getActiveSheet()->mergeCells("G".$ligne.":H".$ligne);


                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->sexe);
                    

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, $value->nombre_menage);
                    $objPHPExcel->getActiveSheet()->mergeCells("E".$ligne.":F".$ligne);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, $value->nombre_individu);
                    $objPHPExcel->getActiveSheet()->mergeCells("G".$ligne.":H".$ligne);


                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->applyFromArray($stylecontenu);
                    $ligne++;
                }
            }

            if ($menu == 'req42_theme2') 
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":F".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":F".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Du: ");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, $date_debut);
                $objPHPExcel->getActiveSheet()->mergeCells("B".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, "Au: ");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, $date_fin);
                $objPHPExcel->getActiveSheet()->mergeCells("E".$ligne.":F".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":F".$ligne)->applyFromArray($Titre1);

                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Intervention");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Détail');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":F".$ligne);

                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":F".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":F".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                   

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->detail_type_transfert.": ".number_format($value->moyenne,0,","," ")." ".$value->unite_mesure);
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":F".$ligne);


                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":F".$ligne)->applyFromArray($stylecontenu);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":F".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                    $ligne++;
                }
            }

            if ($menu == 'req43_theme2') 
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()->setTitle("Tableau de bord");

                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":F".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":F".$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $nom_file);


                $ligne = $ligne + 2 ;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Du: ");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, $date_debut);
                $objPHPExcel->getActiveSheet()->mergeCells("B".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, "Au: ");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, $date_fin);
                $objPHPExcel->getActiveSheet()->mergeCells("E".$ligne.":F".$ligne);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":F".$ligne)->applyFromArray($Titre1);

                $ligne = $ligne + 2 ;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Intervention");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Détail');
                $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":F".$ligne);

                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":F".$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":F".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);

                $ligne++;

                foreach ($data as $key => $value) 
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $value->intitule_intervention);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);

                   

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->detail_type_transfert.": ".number_format($value->quantite,0,","," ")." ".$value->unite_mesure);
                    $objPHPExcel->getActiveSheet()->mergeCells("D".$ligne.":F".$ligne);


                    $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":F".$ligne)->applyFromArray($stylecontenu);
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":F".$ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                    $ligne++;
                }
            }

            


        //FIN CONTENU
       
        //ETAT DE RETOUR
        try
        {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(dirname(__FILE__) . "/../../../../exportexcel/".$repertoire.$nom_file.".xlsx");
            
            $this->response([
                'status' => TRUE,
                'nom_file' => $nom_file.".xlsx",
                'message' => 'Get file success',
            ], REST_Controller::HTTP_OK);
          
        } 
        catch (PHPExcel_Writer_Exception $e)
        {
            $this->response([
                  'status' => FALSE,
                   'nom_file' => array(),
                   'message' => "Something went wrong: ". $e->getMessage(),
                ], REST_Controller::HTTP_OK);
        }

        //ETAT DE RETOUR
    } 
    //misy amboarina le intervention
    public function generer_requete_filtre($id_region,$id_district,$id_commune,$id_intervention)
    {
        $requete = " region.id='".$id_region."'";
       /* if ($date_debut!=$date_debut) 
        {
            $requete = $requete."date_naissance BETWEEN '".$date_debut."' AND '".$date_fin."' " ;
        }else{
            $requete = $requete."date_naissance ='".$date_debut."'";
        }*/
        if (($id_district!='*')&&($id_district!='undefined')) 
        {
            $requete = $requete." AND district.id='".$id_district."'" ;
        }
        if (($id_commune!='*')&&($id_commune!='undefined')) 
        {
            $requete = $requete." AND commune.id='".$id_commune."'" ;
        }
        if (($id_intervention!='*')&&($id_intervention!='undefined')) 
        {
            $requete = $requete." AND intervention.id='".$id_intervention."'" ;
        }

        return $requete;
    }
    
    public function generer_requete_sql($id_region,$id_district,$id_commune,$id_intervention)
    {
        $requete = " reg.id='".$id_region."'";
        if (($id_intervention!='*')&&($id_intervention!='undefined')) 
        {
            $requete = $requete." AND interven.id='".$id_intervention."'" ;
        }

        return $requete;
    }

    private function etat_nouveau($new)
    {
        if (((int)$new) > 0) 
        {
            return "Nouveau" ;
        }
        else
            return "En cours" ;
    }

    private function affichage_budget_initial($data)
    {
        if ($data->etat_nouveau > 0) 
        {
            return $data->budget_initial_nouveau ;
        }
        else
            return $data->budget_initial_en_cours ;
    }

    private function affichage_budget_modifie($data)
    {
        if ($data->etat_nouveau > 0) 
        {
            return $data->budget_modifie_nouveau ;
        }
        else
            return $data->budget_modifie_en_cours ;
    }
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */