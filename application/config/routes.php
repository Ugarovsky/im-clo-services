<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['api'] = 'main';
$route['api/(:any)'] = 'main/$1';
$route['api/(:any)/(:any)'] = 'main/$1/$2';

$route['activation'] = 'main/activation';

$route['ips'] = 'admin/ips';
$route['ips/(:any)'] = 'admin/ips/$1';
$route['ip/(:any)'] = 'admin/ip/$1';
$route['ajax_ips'] = 'admin/ajax_ips';

$route['domain/(:any)'] = 'admin/domain/$1';
$route['ajax_domain/(:any)'] = 'admin/ajax_domain/$1';

$route['domains'] = 'admin/domains';
$route['ajax_domains'] = 'admin/ajax_domains';
$route['delete_logs'] = 'admin/delete_logs';
$route['delete_logs_domain/(:any)'] = 'admin/delete_logs_domain/$1';

$route['download_domain/(:any)'] = 'admin/download_domain/$1';

$route['pixel_red'] = 'admin/pixel_red';

$route['leads'] = 'admin/leads';
$route['postback/(:any)/(:any)'] = 'main/postback/$1/$2';
$route['ajax_leads'] = 'admin/ajax_leads';

$route['setup'] = 'admin/setup';

$route['net'] = 'admin/net';
$route['delete_net/(:any)'] = 'admin/delete_net/$1';

$route['isp'] = 'admin/isp';
$route['delete_isp/(:any)'] = 'admin/delete_isp/$1';

$route['ua'] = 'admin/ua';
$route['delete_ua/(:any)'] = 'admin/delete_ua/$1';



$route['delete_all_black'] = 'admin/delete_all_black';
$route['delete_black_ip/(:any)'] = 'admin/delete_black_ip/$1';
$route['delete_domain/(:any)'] = 'admin/delete_domain/$1';

$route['default_controller'] = "admin";
$route['404_override'] = '';





/* End of file routes.php */
/* Location: ./application/config/routes.php */