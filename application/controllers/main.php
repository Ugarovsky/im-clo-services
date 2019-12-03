<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index() {

	}

	public function activation(){

		$this->load->library('auth');
		if (!$this->auth->loggedin()){
            redirect('login');
            exit();
        }
		$this->load->model('user_model');
    	$this->load->model('main_model');
    	$id = $this->auth->userid();
    	$user = $this->user_model->get('id', $id);
    	$data['user'] = $user;
		if($data['user']['active'] == 1){
			redirect('/');
		}

    	$error_data = [];

    	if($this->input->post('api_token')){
            $user['api_token'] = $this->input->post('api_token');
            //активация ключа
            $activation_json = $this->main_model->curl('http://134.209.80.110/activation/'.$user['api_token'].'/'.$_SERVER["HTTP_HOST"]);
            $activation = json_decode($activation_json, true);
            if($activation['succes'] == 1){
                $data['succes'] = "Лицензия ".$user['api_token']." успешно активирована";
                $user['active'] = 1;
                $this->main_model->update('users', ["id" => $data['user']['id']], $user);
                $this->main_model->curl('http://134.209.80.110/upd/'.$user['api_token'].'/'.$_SERVER["HTTP_HOST"]);
                redirect('/');
            }else{
                $error_data = 'Ошибка активации';
            }
        }
        $data['error'] = $error_data;
    	$this->load->view('activation',$data);
	}

	public function postback($id, $status, $pay){
    	$this->load->model('main_model');
    	$this->main_model->update('leads', ['id' => $id], ['approve' => $status, 'pay' => $pay]);
	}

	    public function nadd_domain(){

        $this->load->model('main_model');

        $data['succes'] = false;
		$error_data = [];


		$api_token = $this->input->post('api_token');
		$key = $this->main_model->get('users');
		if(!$key['api_token']) $error_data['errors'][] = 'token';
		if(!$api_token) $error_data['errors'][] = 'token';
        if($key['api_token'] != $api_token) $error_data['errors'][] = 'token';

			$traf_type = ['ANY' => 0, 'MOB' => 2, 'DESC' => 3];

            $new['domain'] = trim($this->input->post('domain'));
            $new['link'] = urldecode($this->input->post('black'));
            $new['white_link'] = urldecode($this->input->post('white'));
            $geo_data = $this->input->post('geo');
            $traf = $this->input->post('type');
            @$new['traf'] = $traf_type[$traf];

            $vowels = array("https://", "www.", "http://", "/", ":");
            $new['domain'] = str_replace($vowels, "", $new['domain']);

            if(!$new['domain']) $error_data['errors'][] = 'no domain';
            if(!$geo_data) $error_data['errors'][] = 'no geo';
            if(!$traf) $error_data['errors'][] = 'no type';

            if ($error_data) {
                $data['error'] = $error_data;
            }else{

            	$geos = explode(",", $geo_data);
            	foreach ($geos as $geo) {
            		$new['country'] = $geo;
            		$check_domain = $this->main_model->get('domains' , ['domain' => $new['domain'], 'country' => $new['country']]);
            		if($check_domain){
            			$data['succes'] = false;
            			$error_data['errors'][] = 'double domain '.$new['domain'].'-'.$new['country'];
            		}else{
            			$data['succes'] = true;
            			$this->main_model->insert('domains', $new);
            		}
            	}
            }

        echo json_encode($data);
    }

	public function nlist_domains() {
		$this->load->model('main_model');

		$api_token = $this->input->post('api_token');
		$key = $this->main_model->get('users');
		if(!$key['api_token']) $error_data['errors'][] = 'token';
		if(!$api_token) $error_data['errors'][] = 'token';
        if($key['api_token'] != $api_token) $error_data['errors'][] = 'token';

		if ($error_data) {
			$data['error'] = $error_data;
		} else {
			$data['succes'] = true;
			$data['domains'] = $this->main_model->getAll('domains');
		}
		echo json_encode($data);
	}

    public function nlist_country() {
        $this->load->model('main_model');

        $api_token = $this->input->post('api_token');
		$key = $this->main_model->get('users');
		if(!$key['api_token']) $error_data['errors'][] = 'token';
		if(!$api_token) $error_data['errors'][] = 'token';
        if($key['api_token'] != $api_token) $error_data['errors'][] = 'token';

		if ($error_data) {
			$data['error'] = $error_data;
		} else {
			$data['succes'] = true;
			$data['country'] = $this->main_model->getAll('country');
		}
		echo json_encode($data);
    }

	public function ndel_domain() {
		$this->load->model('main_model');

		$api_token = $this->input->post('api_token');
		$key = $this->main_model->get('users');
		if(!$key['api_token']) $error_data['errors'][] = 'token';
		if(!$api_token) $error_data['errors'][] = 'token';
        if($key['api_token'] != $api_token) $error_data['errors'][] = 'token';

		$error_data = [];
		$data['succes'] = false;

		$domain = $this->input->post('domain');
		if(!$domain) $error_data['errors'][] = 'no post domain';;

		if ($error_data) {
			$data['error'] = $error_data;
		} else {
			$data['succes'] = true;
			$this->main_model->delete('domains', ['domain' => $domain]);
		}

		 echo json_encode($data);
	}

	public function delete_logs(){
		 $this->load->model('main_model');
		 $this->main_model->truncate('logs');
		 $this->main_model->truncate('black_ip');
		 $this->main_model->truncate('log_country_check');
	}

	public function add_lead(){
		$this->load->model('main_model');
		$add['old_phone'] = $this->input->get('old_phone');
		$add['phone'] = $this->input->get('phone');
		$add['name'] = $this->input->get('name');
		$add['ip'] = $this->input->get('ip');
		$add['number'] = $this->input->get('number');
		$add['host'] = $this->input->get('host');
		$add['date'] = date("Y-m-d H:i:s");
		$add['status'] = 0;

		$check_country = $this->main_model->get('log_country_check', ['ip' => $add['ip']]);
		if($check_country){
			$add['country'] = $check_country['country'];
		}else{
			$country_check = $this->sx_geo->getCountry($log['ip']);
			if($country_check){
				$add['country'] = $country_check;
				$this->main_model->insert('log_country_check', ['ip' => $add['ip'], 'country' => $country_check]);
			}else{
				$add['country'] = 'no_detected';
			}
		}

		$lead = $this->main_model->insert('leads', $add);
		echo json_encode(['id' => $lead]);
	}

	public function update_lead(){
		$this->load->model('main_model');

		$id = $this->input->get('id');
		$response_api = $this->input->get('response_api');
		$this->main_model->update('leads', ['id' => $id], ['response_api' => $response_api]);
	}

	public function check_ip(){
		$this->load->model('main_model');
		$this->load->library('sx_geo');

		$error_data = [];

		$log['ip'] = $this->input->post('ip');

		$log['domain'] = $this->input->post('domain');
		$log['domain'] = str_replace("www.", "", $log['domain']);
		$log['referer'] = $this->input->post('referer');
		$log['user_agent'] = $this->input->post('user_agent');

		$log['land'] = $this->input->post('land');

		$check_net_data = $this->check_net_data($log['ip']);
		if($check_net_data){
			$log['net'] = $check_net_data['net'];
			$log['descr'] = $check_net_data['descr'];
		}

		$_SERVER['HTTP_USER_AGENT'] = $log['user_agent'];
		$this->load->library('user_agent');

		if($this->agent->is_mobile()){
			$log['mobile'] = 1;
		}else{
			$log['mobile'] = 0;
		}

//нормализация юзер агента
		$fb_repl = stristr($log['user_agent'], ' [FB_IAB', true);
		if($fb_repl) $log['user_agent'] = $fb_repl;
		$fb_repl1 = stristr($log['user_agent'], ' [FBAN/', true);
		if($fb_repl1) $log['user_agent'] = $fb_repl1;
		$in_repl = stristr($log['user_agent'], ' Instagram', true);
		if($in_repl) $log['user_agent'] = $in_repl;

		$log['date'] = date("Y-m-d H:i:s");
		$log['headers'] = $this->input->post('headers');

//https://www.facebook.com/business/help/1514372351922333 определение предпросмотра
		$headers = json_decode($log['headers'], true);
		if(@$headers['X-Purpose'] == 'preview' && (@$headers['X-FB-HTTP-Engine'] == 'Liger' || @$headers['x-fb-http-engine'] == 'Liger')) {
			$error_data['errors'][] = 'prewiew.';
			$log['preview'] = 1;
		}else{
			$log['preview'] = 0;
		}

		if($log['preview'] == 0){
		$log_id = $this->main_model->insert('logs', $log);
		}

//проверяем необходимые переменные
		if(!$log['ip']) $error_data['errors'][] = 'no IP.';
		if(!$log['domain']) $error_data['errors'][] = 'no BD domain.';


 //$header_country = $headers['GEOIP_COUNTRY_CODE'];

//проверяем определяли ли уже страну, если нет то определяем
		$check_country = $this->main_model->get('log_country_check', ['ip' => $log['ip']]);
		if($check_country){
			$db_country = $check_country['country'];
		}else{
			$country_check = $this->sx_geo->getCountry($log['ip']);
			if($country_check){
				$db_country = $country_check;
				$this->main_model->insert('log_country_check', ['ip' => $log['ip'], 'country' => $country_check]);
			}else{
				$db_country = 'no_detected';
				$error_data['errors'][] = 'sx geo error.';
			}
		}



		if($log['preview'] == 0){
		$this->main_model->update('logs', ['id' => $log_id], ['country' => $db_country]);
		}


		$count_klick = $this->main_model->count('logs', ['domain' => $log['domain'], 'preview' => 0], 'id');
		if($count_klick <= 15 ){
			//$error_data['errors'][] = 'min 15 leads.';
		}

//может быть несколько записей одного домена с разными гео и данными

//берем из базы данные о домене, выдавать их в случае если нужно показывать IP!
		$check_domain = $this->main_model->get('domains', ['domain' => $log['domain']]);
		if(!$check_domain){
			$check_domain['white_link'] = '';
			$check_domain['country'] = 'no_detected';
			$check_domain['metrika_id'] = '';

			$error_data['errors'][] = 'no domain.';
		}


//проверяем блэк лист сетей
		$db_net = $this->main_model->getAll('black_net');
		foreach($db_net as $net){
			$mas = explode("/", $net['net']);
			$check_net = $this->main_model->net_search($log['ip'], $mas[0], $mas[1]);
			if($check_net){
				$error_net[] = $net['net'];
				$error_data['errors'][] = 'Net black '.$net['net'];
			}
		}

//проверять имя подсети на допустимое
		if(empty($error_net)){
			if($log['descr'] AND $log['net']){
				$black_net_names = $this->main_model->getAll('black_isp');
				foreach ($black_net_names as $black_net_name) {
					$posn = strpos($log['descr'], $black_net_name['name']);
					if ($posn === false) {
					}else{
						$error_net[] = 'add';
						$this->main_model->insert('black_net', ['net' => $log['net'], 'cause' => 'auto ban']);
						$error_data['errors'][] = 'Net black auto ban.';
					}
				}
			}
		}


//Если нет в бане по подсети
		if(empty($error_net)){

//проверяем блэк лист IP
			$check_blacl_list = $this->main_model->get('black_ip', ['ip' => $log['ip']]);
			if($check_blacl_list){
				$error_data['errors'][] = 'IP black.';
			}else{

//Если юзер агент менялся более 10х раз
				$count_ua = $this->main_model->count('logs', ['ip' => $log['ip'], 'preview' => 0], 'user_agent');
				if(@$count_ua >= 10){
					$error_data['errors'][] = 'doudle UA, IP added black list.';
					$this->main_model->insert('black_ip', ['ip' => $log['ip'], 'country' => $db_country, 'cause' => 'random_ua', 'comment' => $log['descr']]);
				}

 //проверять юзер агент на допустимый
				$black_user_agents = $this->main_model->getAll('black_ua');
				foreach ($black_user_agents as $black_user_agent) {
					$pos = strpos($log['user_agent'], $black_user_agent['user_agent']);
					if ($pos === false) {
					}else{
						$error_data['errors'][] = 'UA black.';
						$check_blacl_list = $this->main_model->get('black_ip', ['ip' => $log['ip']]);
						if(!$check_blacl_list){
							$error_data['errors'][] = 'UA black, IP added black list.';
							$this->main_model->insert('black_ip', ['ip' => $log['ip'], 'country' => $db_country, 'cause' => 'black_ua', 'comment' => $log['descr']]);
						}
					}
				}

//если не передан юзер агент
				if(!$log['user_agent']){
					$error_data['errors'][] = 'no user_agent, IP added black list.';
					$this->main_model->insert('black_ip', ['ip' => $log['ip'], 'country' => $db_country, 'cause' => 'no_ua', 'comment' => $log['descr']]);
				}

//если не передан реферер
				if(!$log['referer']){
   // $error_data['errors'][] = 'no referrer, IP added black list.';
   // $this->main_model->insert('black_ip', ['ip' => $log['ip'], 'country' => $db_country, 'cause' => 'no_ref', 'comment' => $log['descr']]);
				}

//если не переданы заголовки запроса
				if(!$log['headers']){
					$error_data['errors'][] = 'no headers.';
 // $this->main_model->insert('black_ip', ['ip' => $log['ip'], 'country' => $db_country, 'cause' => 'no_headers', 'comment' => $log['descr']]);
				}

//проверка страны 
				$check_domain_country = $this->main_model->get('domains', ['domain' => $log['domain'] , 'country' => $db_country]);
				if($check_domain_country){
					$check_domain = $check_domain_country;
				}else{
					$error_data['errors'][] = 'country error, IP added black list.';
					$this->main_model->insert('black_ip', ['ip' => $log['ip'], 'country' => $db_country, 'cause' => 'country', 'comment' => $log['descr']]);
				}

				if(@$check_domain['traf'] == 1){
//любой
				}elseif(@$check_domain['traf'] == 2){
//моб
					if($log['mobile'] == 0){
						$error_data['errors'][] = 'desctop traf';
						$this->main_model->insert('black_ip', ['ip' => $log['ip'], 'country' => $db_country, 'cause' => 'desctop traf', 'comment' => $log['descr']]);
					} 
				}elseif(@$check_domain['traf'] == 3){
//деск
					if($log['mobile'] == 1){
						$error_data['errors'][] = 'mobile traf';
						$this->main_model->insert('black_ip', ['ip' => $log['ip'], 'country' => $db_country, 'cause' => 'mobile traf', 'comment' => $log['descr']]);
					} 
				}

/*if($check_domain['country'] != $db_country){
  $error_data['errors'][] = 'country error, IP added black list.';
  $this->main_model->insert('black_ip', ['ip' => $log['ip'], 'country' => $db_country, 'cause' => 'country', 'comment' => $log['descr']]);
} */
}

}

//выводим результат
if ($error_data) {
	$db_res = 'white';
	$error_data['result'] = 0;
	$error_data['log_id'] = $log_id;
	$error_data['white_link'] = $check_domain['white_link'];
	$error_data['metrika_id'] = $check_domain['metrika_id'];
	$result = json_encode($error_data);

} else {
	$db_res = 'black';
	$check_domain['white_link'] = false;
	$check_domain['result'] = 1;
	$check_domain['log_id'] = $log_id;
	$result = json_encode($check_domain);

}

//пишем в базу рзультат
if($log['preview'] == 0){
$this->main_model->update('logs', ['id' => $log_id], ['response_api' => $result, 'result' => $db_res]);
}
echo $result;
}


public function net($ip=false){
	$this->load->model('main_model');

	$black_ip = $this->main_model->getAll('black_ip');

	foreach($black_ip as $black_ip){
		$ip = $black_ip['ip'];
		$db_net = $this->main_model->getAll('black_net');
		foreach($db_net as $net){
			$mas = explode("/", $net['net']);
			$check_net = $this->main_model->net_search($ip, $mas[0], $mas[1]);
			if($check_net){
				$this->main_model->delete('black_ip', ['ip' => $ip]);
			}else{

			}
		}
	}
}


public function img_tracker(){
	$this->load->model('main_model');
	$log_id = $this->input->post('log_id');
	$this->main_model->update('logs', ['id' => $log_id], ['img_tracker' => 1]);
}


private function check_net_data($ip){
	$this->load->model('main_model');
	$res['net'] = '';
	$res['descr'] = '';
 // http://rest.db.ripe.net/search.json?query-string=31.13.113.78&type-filter=route&flags=resource
  //$data_ip = $this->main_model->curl('https://rest.db.ripe.net/search.json?query-string='.$ip.'&flags=no-filtering');
	$data_ip = $this->main_model->curl('http://rest.db.ripe.net/search.json?query-string='.$ip.'&type-filter=route&flags=resource');
	$data_ip = json_decode($data_ip);
  //print_r($data_ip);
	if(@$data_ip->objects->object){
		foreach ($data_ip->objects->object as $value) {
			if($value->type == 'route'){
				foreach($value->attributes->attribute as $atr){
					if($atr->name == 'route'){
						$res['net'] =  $atr->value;
					}
					if($atr->name == 'descr'){
						$res['descr'] =  $atr->value;
					}
				}
			}
		}
	}

	return $res;
}


public function up_net_name(){
	$this->load->model('main_model');

	$nets = $this->main_model->getAll('black_net');

	foreach ($nets as $net) {

		$check = $this->check_net_data_name($net['net']);
		if($check){
			$this->main_model->update('black_net', ['net' => $net['net']], ['net_name' => $check['descr'], 'country' => $check['country']]);
		}
	}
}


private function check_net_data_name($ip){
	$this->load->model('main_model');
	$this->load->library('sx_geo');

	$nets = explode("/", $ip);
	$country_check = $this->sx_geo->getCountry($nets[0]);
	$res['country'] = $country_check;
	$res['descr'] = '';
	$data_ip = $this->main_model->curl('http://rest.db.ripe.net/search.json?query-string='.$ip.'&type-filter=route&flags=resource');
	$data_ip = json_decode($data_ip);
	//print_r($data_ip);
	if(@$data_ip->objects->object){
		foreach ($data_ip->objects->object as $value) {
			if($value->type == 'route'){
				foreach($value->attributes->attribute as $atr){
					if($atr->name == 'route'){
						//$res['net'] =  $atr->value;
					}
					if($atr->name == 'descr'){
						$res['descr'] =  $atr->value;
					}
				}
			}
		}
	}

	return $res;
}


public function update_status_domains(){
	$this->load->model('main_model');
	$domains = $this->main_model->getAll('domains');

	foreach ($domains as $domain) {
		$check_ban = $this->main_model->check_block_domain($domain['domain']);
		if($check_ban){
			$this->main_model->update('domains', ['id' => $domain['id']], ['status' => 1]);
		}else{
			$this->main_model->update('domains', ['id' => $domain['id']], ['status' => 0]);
		}

		$check_serv_ip = @dns_get_record($domain['domain'], DNS_A);
		$serv_ip = $check_serv_ip[0]['ip'];
		$this->main_model->update('domains', ['id' => $domain['id']], ['status_s' => $serv_ip]);


/*		$check_ban_s = $this->main_model->check_block_domain($serv_ip);
		if($check_ban_s){
			$this->main_model->update('domains', ['id' => $domain['id']], ['status_s' => 1]);
		}else{
			$this->main_model->update('domains', ['id' => $domain['id']], ['status_s' => 0]);
		}*/

	}

}

public function upd_net($api_token){
	set_time_limit(0);
	ignore_user_abort(true);
	$this->load->model('main_model');

	$data['succes'] = false;
	$error_data = [];

	$key = $this->main_model->get('users');
	if(!$key['api_token']) $error_data['errors'][] = 'no user token';
	if(!$api_token) $error_data['errors'][] = 'no post token';
    if($key['api_token'] != $api_token) $error_data['errors'][] = 'error token';

    if ($error_data) {
        $data['error'] = $error_data;
    }else{
    	$data['succes'] = true;
        $postData = file_get_contents('php://input');
		$upd = json_decode($postData, true);

	foreach ($upd['uas'] as $ua) {
		$check_ua = $this->main_model->get('black_ua', ['user_agent' => $ua['user_agent']]);
		if(!$check_ua){
			$addu['user_agent'] = $ua['user_agent'];
			$this->main_model->insert('black_ua', $addu);
		}
	}
	foreach ($upd['isps'] as $isp) {
		$check_isp = $this->main_model->get('black_isp', ['name' => $isp['name']]);
		if(!$check_isp){
			$addi['name'] = $isp['name'];
			$this->main_model->insert('black_isp', $addi);
		}
	}
	foreach ($upd['nets'] as $net) {
		$check_net = $this->main_model->get('black_net', ['net' => $net['net']]);
		if(!$check_net){
			$addn['net'] = $net['net'];
			$addn['cause'] = 'online';
			$this->main_model->insert('black_net', $addn);
		}
	}
}

	echo json_encode($data);

}

public function upd_geo($api_token){
		set_time_limit(0);
		ignore_user_abort(true);
		$this->load->model('main_model');

	    $data['succes'] = false;
		$error_data = [];

		$key = $this->main_model->get('users');
		if(!$key['api_token']) $error_data['errors'][] = 'no user token';
		if(!$api_token) $error_data['errors'][] = 'no post token';
        if($key['api_token'] != $api_token) $error_data['errors'][] = 'error token';

        if ($error_data) {
            $data['error'] = $error_data;
        }else{
            $postData = file_get_contents('php://input');
			$datas = json_decode($postData, true);
			$base = base64_decode($datas['base']);
			$result = file_put_contents("application/db/SxGeo.dat", $base);
			if($result){
				$data['succes'] = true;
			}else{
				$data['error'] = 'error save file';
			}
        }

        echo json_encode($data);
}

}