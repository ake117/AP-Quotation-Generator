<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['translate_uri_dashes'] = false;

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
$route['default_controller']    = 'grandeasset';
$route['admin'] 				= 'admin/dashboard';
$route['admin/login']			= 'admin/logon/index';
$route['admin/logout']			= 'admin/logon/out';
$route['admin/recovery']		= 'admin/logon/recovery';
$route['admin/recovery/(:any)'] = 'admin/logon/recovery/$1';
$route['admin/project-banner']			= 'admin/project_banner/index';
$route['404_override'] = '';

/*
| -------------------------------------------------------------------
|  Setup Routes
| -------------------------------------------------------------------
*/
$route['setup'] 				= 'setup/main';
$route['setup/migrate'] 		= 'setup/main/migrate';
$route['setup/migrate/(:any)'] 	= 'setup/main/migrate/$1';
$route['setup/firstadmin'] 		= 'setup/main/firstadmin';

/*
| -------------------------------------------------------------------
|  Site Routes
| -------------------------------------------------------------------
*/
$route['posts'] 				= 'main/posts';
$route['posts/(:any)'] 			= 'main/posts/$1';
$route['posts/(:any)/(:any)'] 	= 'main/posts/$1';
$route['post/(:any)'] 			= 'main/post/$1';
$route['events']				= 'main/events';
$route['event/(:any)'] 			= 'main/post/$1';
$route['search']				= 'main/search';
$route['albuns'] 				= 'main/albuns';
$route['album/(:any)/(:any)'] 	= 'main/album/$1';
$route['foto/(:any)/(:any)'] 	= 'main/foto/$2';
$route['videos'] 				= 'main/videos';
$route['video/(:any)/(:any)'] 	= 'main/video/$1';
$route['newsletter'] 			= 'main/newsletter';
$route['contact'] 				= 'main/contact';
$route['rss']               	= 'main/rss';



$route['project/(:any)/(:num)'] = 'project/index/$2';

$route['signout']               = 'signin/signout';
$route['signin/(submit)'] 	= 'signin/$1';
$route['signin/(:any)']         = 'signin/index/$1';

$route['signup/(get_amphur|get_tumbon|get_postcode|signup|check_unique)'] 	= 'signup/$1';
$route['signup/(:any)'] 	= 'signup/index/$1';
$route['switch/(:any)'] 	= 'LanguageSwitcher/switchLang/$1';

$route['booking/(:any)/(:num)'] 	= 'booking/index/$2';
$route['booking/(:num)/checkout'] 	= 'booking/checkout/$1';
$route['booking/(:num)/successUrl'] 	= 'booking/successUrl/$1';
$route['booking/data_feed'] 	= 'booking/data_feed/';
$route['booking/failUrl'] 	= 'booking/failUrl/';
$route['booking/(:num)/cancelUrl'] 	= 'booking/cancelUrl/$1';
$route['booking/(:any)/complete_order'] 	= 'booking/complete/$1';
//$route['ข่าวสาร']="magazine";
/* End of file routes.php */
/* Location: ./application/config/routes.php */


$route['password/create/(:any)'] = 'password/create/$1';
$route['signup/verify/(:any)'] = 'signup/verify/$1';
$route['signup/(:any)'] = 'signup/$1';

$route['agents/loadRecord/(:any)'] = 'agents/loadRecord/$1/$2/$3';
$route['agents/(signin|submit|signout)'] = 'agents/$1';
$route['agents/(:any)/(reservation|history)/(success|fail|cancel)'] = 'agents/index/$1/$2/$3';
$route['agents/(:any)/(reservation|history)'] = 'agents/index/$1/$2';
$route['agents/(:any)'] = 'agents/index/$1';
