<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//api

//activite
$route['activite']['get'] = 'api/activite/index';
$route['activite']['post'] = 'api/activite/index';
$route['activite/(:num)']['put'] = 'api/activite/index/$1';
$route['activite/(:num)']['delete'] = 'api/activite/index/$1';

//avoire
$route['avoire']['get'] = 'api/avoire/index';
$route['avoire']['post'] = 'api/avoire/index';
$route['avoire/(:num)']['put'] = 'api/avoire/index/$1';
$route['avoire/(:num)']['delete'] = 'api/avoire/index/$1';

//commune
$route['commune']['get'] = 'api/commune/index';
$route['commune']['post'] = 'api/commune/index';
$route['commune/(:num)']['put'] = 'api/commune/index/$1';
$route['commune/(:num)']['delete'] = 'api/commune/index/$1';

//depense
$route['depense']['get'] = 'api/depense/index';
$route['depense']['post'] = 'api/depense/index';
$route['depense/(:num)']['put'] = 'api/depense/index/$1';
$route['depense/(:num)']['delete'] = 'api/depense/index/$1';

//detailpta
$route['detailpta']['get'] = 'api/detailpta/index';
$route['detailpta']['post'] = 'api/detailpta/index';
$route['detailpta/(:num)']['put'] = 'api/detailpta/index/$1';
$route['detailpta/(:num)']['delete'] = 'api/detailpta/index/$1';

//dette
$route['dette']['get'] = 'api/dette/index';
$route['dette']['post'] = 'api/dette/index';
$route['dette/(:num)']['put'] = 'api/dette/index/$1';
$route['dette/(:num)']['delete'] = 'api/dette/index/$1';

//district
$route['district']['get'] = 'api/district/index';
$route['district']['post'] = 'api/district/index';
$route['district/(:num)']['put'] = 'api/district/index/$1';
$route['district/(:num)']['delete'] = 'api/district/index/$1';

//donneesfinanciere
$route['donneesfinanciere']['get'] = 'api/donneesfinanciere/index';
$route['donneesfinanciere']['post'] = 'api/donneesfinanciere/index';
$route['donneesfinanciere/(:num)']['put'] = 'api/donneesfinanciere/index/$1';
$route['donneesfinanciere/(:num)']['delete'] = 'api/donneesfinanciere/index/$1';

//enfants
$route['enfants']['get'] = 'api/enfants/index';
$route['enfants']['post'] = 'api/enfants/index';
$route['enfants/(:num)']['put'] = 'api/enfants/index/$1';
$route['enfants/(:num)']['delete'] = 'api/enfants/index/$1';

//evenement
$route['evenement']['get'] = 'api/evenement/index';
$route['evenement']['post'] = 'api/evenement/index';
$route['evenement/(:num)']['put'] = 'api/evenement/index/$1';
$route['evenement/(:num)']['delete'] = 'api/evenement/index/$1';

//indicateur
$route['indicateur']['get'] = 'api/indicateur/index';
$route['indicateur']['post'] = 'api/indicateur/index';
$route['indicateur/(:num)']['put'] = 'api/indicateur/index/$1';
$route['indicateur/(:num)']['delete'] = 'api/indicateur/index/$1';

//interesse
$route['interesse']['get'] = 'api/interesse/index';
$route['interesse']['post'] = 'api/interesse/index';
$route['interesse/(:num)']['put'] = 'api/interesse/index/$1';
$route['interesse/(:num)']['delete'] = 'api/interesse/index/$1';

//intereteconomique
$route['intereteconomique']['get'] = 'api/intereteconomique/index';
$route['intereteconomique']['post'] = 'api/intereteconomique/index';
$route['intereteconomique/(:num)']['put'] = 'api/intereteconomique/index/$1';
$route['intereteconomique/(:num)']['delete'] = 'api/intereteconomique/index/$1';

//lignebuudgetaire
$route['lignebuudgetaire']['get'] = 'api/lignebuudgetaire/index';
$route['lignebuudgetaire']['post'] = 'api/lignebuudgetaire/index';
$route['lignebuudgetaire/(:num)']['put'] = 'api/lignebuudgetaire/index/$1';
$route['lignebuudgetaire/(:num)']['delete'] = 'api/lignebuudgetaire/index/$1';

//mail
$route['mail']['get'] = 'api/mail/index';

//message
$route['message']['get'] = 'api/message/index';
$route['message']['post'] = 'api/message/index';
$route['message/(:num)']['put'] = 'api/message/index/$1';
$route['message/(:num)']['delete'] = 'api/message/index/$1';

//nature
$route['nature']['get'] = 'api/nature/index';
$route['nature']['post'] = 'api/nature/index';
$route['nature/(:num)']['put'] = 'api/nature/index/$1';
$route['nature/(:num)']['delete'] = 'api/nature/index/$1';

//objectifstrategique
$route['objectifstrategique']['get'] = 'api/objectifstrategique/index';
$route['objectifstrategique']['post'] = 'api/objectifstrategique/index';
$route['objectifstrategique/(:num)']['put'] = 'api/objectifstrategique/index/$1';
$route['objectifstrategique/(:num)']['delete'] = 'api/objectifstrategique/index/$1';

//organisationaction
$route['organisationaction']['get'] = 'api/organisationaction/index';
$route['organisationaction']['post'] = 'api/organisationaction/index';
$route['organisationaction/(:num)']['put'] = 'api/organisationaction/index/$1';
$route['organisationaction/(:num)']['delete'] = 'api/organisationaction/index/$1';

//participant
$route['participant']['get'] = 'api/participant/index';
$route['participant']['post'] = 'api/participant/index';
$route['participant/(:num)']['put'] = 'api/participant/index/$1';
$route['participant/(:num)']['delete'] = 'api/participant/index/$1';

//personnel
$route['personnel']['get'] = 'api/personnel/index';
$route['personnel']['post'] = 'api/personnel/index';
$route['personnel/(:num)']['put'] = 'api/personnel/index/$1';
$route['personnel/(:num)']['delete'] = 'api/personnel/index/$1';

//plancomptable
$route['plancomptable']['get'] = 'api/plancomptable/index';
$route['plancomptable']['post'] = 'api/plancomptable/index';
$route['plancomptable/(:num)']['put'] = 'api/plancomptable/index/$1';
$route['plancomptable/(:num)']['delete'] = 'api/plancomptable/index/$1';

//pta
$route['pta']['get'] = 'api/pta/index';
$route['pta']['post'] = 'api/pta/index';
$route['pta/(:num)']['put'] = 'api/pta/index/$1';
$route['pta/(:num)']['delete'] = 'api/pta/index/$1';

//region
$route['region']['get'] = 'api/region/index';
$route['region']['post'] = 'api/region/index';
$route['region/(:num)']['put'] = 'api/region/index/$1';
$route['region/(:num)']['delete'] = 'api/region/index/$1';

//ressource
$route['ressource']['get'] = 'api/ressource/index';
$route['ressource']['post'] = 'api/ressource/index';
$route['ressource/(:num)']['put'] = 'api/ressource/index/$1';
$route['ressource/(:num)']['delete'] = 'api/ressource/index/$1';

//secteur
$route['secteur']['get'] = 'api/secteur/index';
$route['secteur']['post'] = 'api/secteur/index';
$route['secteur/(:num)']['put'] = 'api/secteur/index/$1';
$route['secteur/(:num)']['delete'] = 'api/secteur/index/$1';

//site
$route['site']['get'] = 'api/site/index';
$route['site']['post'] = 'api/site/index';
$route['site/(:num)']['put'] = 'api/site/index/$1';
$route['site/(:num)']['delete'] = 'api/site/index/$1';

//situationmatrimonial
$route['situationmatrimonial']['get'] = 'api/situationmatrimonial/index';
$route['situationmatrimonial']['post'] = 'api/situationmatrimonial/index';
$route['situationmatrimonial/(:num)']['put'] = 'api/situationmatrimonial/index/$1';
$route['situationmatrimonial/(:num)']['delete'] = 'api/situationmatrimonial/index/$1';

//societe
$route['societe']['get'] = 'api/societe/index';
$route['societe']['post'] = 'api/societe/index';
$route['societe/(:num)']['put'] = 'api/societe/index/$1';
$route['societe/(:num)']['delete'] = 'api/societe/index/$1';

//sourcedefinancement
$route['sourcedefinancement']['get'] = 'api/sourcedefinancement/index';
$route['sourcedefinancement']['post'] = 'api/sourcedefinancement/index';
$route['sourcedefinancement/(:num)']['put'] = 'api/sourcedefinancement/index/$1';
$route['sourcedefinancement/(:num)']['delete'] = 'api/sourcedefinancement/index/$1';

//support
$route['support']['get'] = 'api/support/index';
$route['support']['post'] = 'api/support/index';
$route['support/(:num)']['put'] = 'api/support/index/$1';
$route['support/(:num)']['delete'] = 'api/support/index/$1';

//typeactivite
$route['typeactivite']['get'] = 'api/typeactivite/index';
$route['typeactivite']['post'] = 'api/typeactivite/index';
$route['typeactivite/(:num)']['put'] = 'api/typeactivite/index/$1';
$route['typeactivite/(:num)']['delete'] = 'api/typeactivite/index/$1';

//typeindicateur
$route['typeindicateur']['get'] = 'api/typeindicateur/index';
$route['typeindicateur']['post'] = 'api/typeindicateur/index';
$route['typeindicateur/(:num)']['put'] = 'api/typeindicateur/index/$1';
$route['typeindicateur/(:num)']['delete'] = 'api/typeindicateur/index/$1';

//typeorganisationaction
$route['typeorganisationaction']['get'] = 'api/typeorganisationaction/index';
$route['typeorganisationaction']['post'] = 'api/typeorganisationaction/index';
$route['typeorganisationaction/(:num)']['put'] = 'api/typeorganisationaction/index/$1';
$route['typeorganisationaction/(:num)']['delete'] = 'api/typeorganisationaction/index/$1';

//typestation
$route['typestation']['get'] = 'api/typestation/index';
$route['typestation']['post'] = 'api/typestation/index';
$route['typestation/(:num)']['put'] = 'api/typestation/index/$1';
$route['typestation/(:num)']['delete'] = 'api/typestation/index/$1';

//utilisateur
$route['utilisateurs']['get'] = 'api/utilisateurs/index';
$route['utilisateurs']['post'] = 'api/utilisateurs/index';
$route['utilisateurs/(:num)']['put'] = 'api/utilisateurs/index/$1';
$route['utilisateurs/(:num)']['delete'] = 'api/utilisateurs/index/$1';


//voletoudirection
$route['voletoudirection']['get'] = 'api/voletoudirection/index';
$route['voletoudirection']['post'] = 'api/voletoudirection/index';
$route['voletoudirection/(:num)']['put'] = 'api/voletoudirection/index/$1';
$route['voletoudirection/(:num)']['delete'] = 'api/voletoudirection/index/$1';
