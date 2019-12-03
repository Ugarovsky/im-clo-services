<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Admin extends CI_Controller {

    var $user;

    public function __construct(){
        parent::__construct();
        $this->load->library('auth');
        if (!$this->auth->loggedin()){
            redirect('login');
            exit();
        }
        $id = $this->auth->userid();
        $this->load->model('user_model');
        $this->load->model('main_model');

        $user = $this->user_model->get('id', $id);
        $this->user = $user;

        $check_json = $this->main_model->curl('http://134.209.80.110/check/'.$user['api_token'].'/'.$_SERVER["HTTP_HOST"]);
        $check = json_decode($check_json, true);
        if($check['succes'] == 0){
            $this->main_model->update('users', ['id' => $user['id']], ['active' => 0]);
        }

        if($user['active'] == 0){
            redirect('activation');
            exit();
        }
    }

    public function index() {
        $data['user'] = $this->user;

        $data['graph']  = json_encode($this->main_model->charts());

        $this->load->view('/backend/block/head',$data);
        $this->load->view('/backend/block/menu',$data);
        $this->load->view('/backend/block/sidebar',$data);
        $this->load->view('/backend/dash',$data);
        $this->load->view('/backend/block/footer',$data);
    }

    public function setup() {
        //тут привязка ключа на сервере лицензий
        $data['user'] = $this->user;

        $error_data = [];
        $data['succes'] = false;

        if($this->input->post('api_token')){
            $user['api_token'] = $this->input->post('api_token');

            //активация ключа
            $activation_json = $this->main_model->curl('http://134.209.80.110/activation/'.$user['api_token']);
            $activation = json_decode($activation_json, true);
            if($activation['succes'] == 1){
                $data['succes'] = "Лицензия ".$user['api_token']." успешно активирована";
                $user['active'] = 1;
                $id = $this->main_model->update('users', ["id" => $data['user']['id']], $user);
            }else{
                $error_data['errors'][] = 'Ошибка активации.';
            }

        }elseif($this->input->post('password')){

            $user['password'] = $this->user_model->hash($this->input->post('password'));
            $id = $this->main_model->update('users', ["id" => $data['user']['id']], $user);
        }

        if($error_data) $data['error'] = $error_data;

        $this->load->view('/backend/block/head',$data);
        $this->load->view('/backend/block/menu',$data);
        $this->load->view('/backend/block/sidebar',$data);
        $this->load->view('/backend/setup',$data);
        $this->load->view('/backend/block/footer',$data);
    }

    public function ip($ip=false){
        $data['user'] = $this->user;

        $ip_data = $this->main_model->getAll('logs', ['ip' => $ip],  0, 'date');
        $data['menu']='ips';
        foreach ($ip_data as &$value) {
            $value['response_api'] = json_decode($value['response_api']);
        }

        $data['logs'] = $ip_data;
        $this->load->view('/backend/block/head',$data);
        $this->load->view('/backend/block/menu',$data);
        $this->load->view('/backend/block/sidebar',$data);
        $this->load->view('/backend/ip',$data);
        $this->load->view('/backend/block/footer',$data);
    }



    public function net(){
        $data['user'] = $this->user;

        $data['succes'] = false;
        if($this->input->post('new')){

            $error_data = [];
            $new['net'] = $this->input->post('net');
            $new['cause'] = $this->input->post('cause');

            $checks_net = $this->main_model->get('black_net' , ['net' => $new['net']]);
            if($checks_net){
                $error_data['errors'][] = 'подсеть уже есть в базе';
            }

            $pos = strpos($new['net'], '/');
            if ($pos === false) {
                $error_data['errors'][] = 'в эту тублицу можно добавить только под сети вида 0.0.0.0/0'; 
            }

            if(!$new['net']) $error_data['errors'][] = 'заполните поле сеть';
            if(!$new['cause']) $error_data['errors'][] = 'заполните поле причина';

            if ($error_data) {
                $data['error'] = $error_data;
            } else {
                $this->main_model->insert('black_net', $new);
                $data['succes'] = $new['net'];

                $this->main_model->curl('http://killeren.beget.tech/api/add_net', ['net' => $new['net']]);

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
    }

    $data['black_net'] = $this->main_model->getAll('black_net');
    $this->load->view('/backend/block/head',$data);
    $this->load->view('/backend/block/menu',$data);
    $this->load->view('/backend/block/sidebar',$data);
    $this->load->view('/backend/net',$data);
    $this->load->view('/backend/block/footer',$data);
}

public function isp()
{
        $data['user'] = $this->user;
        $data['succes'] = false;
        if($this->input->post('new')){
            $error_data = [];
            $new['name'] = $this->input->post('isp');
            $checks_net = $this->main_model->get('black_isp' , ['name' => $new['name']]);
            if($checks_net){
                $error_data['errors'][] = 'isp уже есть в базе';
            }
            if(!$new['name']) $error_data['errors'][] = 'заполните поле isp';
            if ($error_data) {
                $data['error'] = $error_data;
            } else {
                $this->main_model->insert('black_isp', $new);
                $data['succes'] = $new['name'];
        }
    }

    $data['black_isp'] = $this->main_model->getAll('black_isp');
    $this->load->view('/backend/block/head',$data);
    $this->load->view('/backend/block/menu',$data);
    $this->load->view('/backend/block/sidebar',$data);
    $this->load->view('/backend/isp',$data);
    $this->load->view('/backend/block/footer',$data);
}

public function leads()
{
    $data['user'] = $this->user;
    $data['succes'] = false;

    $data['menu']='leads';

    $this->load->view('/backend/block/head',$data);
    $this->load->view('/backend/block/menu',$data);
    $this->load->view('/backend/block/sidebar',$data);
    $this->load->view('/backend/leads',$data);
}

public function ajax_leads(){

    $post['length'] = $this->input->post('length');
    $post['start'] = $this->input->post('start');
    $search = $this->input->post('search');
    $post['search_value'] = $search['value'];
    $post['order'] = $this->input->post('order');
    $post['draw'] = $this->input->post('draw');
    $post['status'] = $this->input->post('status');

    $post['recordsTotal'] = $this->main_model->count('leads', NULL, 'id');
        if ($post['search_value']) {
            $post['recordsFiltered'] = $this->main_model->count_limit('leads', $post['length'], $post['start'], $post['search_value']);
        } else {
            $post['recordsFiltered'] = $post['recordsTotal'];
        }

    $tabledata = $post;

    $data = $this->main_model->getAlls('leads', $post['length'], $post['start'], $post['search_value']);

    foreach ($data as &$value) {
            $row = array();

            $row['id'] = $value['id'];
            $row[] = $value['date'];
            $row[] = $value['host'];
            $row[] = $value['name'];
            $row[] = $value['phone'];
            $row[] = $value['ip'];
            $row[] = $value['pay'];
            $row[] = $value['approve'];

            $tabledata['data'][] = $row;
    }

    echo json_encode($tabledata);
}

public function ua()
{
        $data['user'] = $this->user;
        $data['succes'] = false;
        if($this->input->post('new')){
            $error_data = [];
            $new['user_agent'] = $this->input->post('ua');
            $checks_net = $this->main_model->get('black_ua' , ['user_agent' => $new['user_agent']]);
            if($checks_net){
                $error_data['errors'][] = 'ua уже есть в базе';
            }
            if(!$new['user_agent']) $error_data['errors'][] = 'заполните поле isp';
            if ($error_data) {
                $data['error'] = $error_data;
            } else {
                $this->main_model->insert('black_ua', $new);
                $data['succes'] = $new['user_agent'];
            }
    }

    $data['black_ua'] = $this->main_model->getAll('black_ua');
    $this->load->view('/backend/block/head',$data);
    $this->load->view('/backend/block/menu',$data);
    $this->load->view('/backend/block/sidebar',$data);
    $this->load->view('/backend/ua',$data);
    $this->load->view('/backend/block/footer',$data);
}

public function ips($country = false){
    $data['user'] = $this->user;

   $data['menu']='ips';

   $this->load->view('/backend/block/head',$data);
   $this->load->view('/backend/block/menu',$data);
   $this->load->view('/backend/block/sidebar',$data);
   $this->load->view('/backend/ips',$data);
}

public function ajax_ips(){

    $post['length'] = $this->input->post('length');
    $post['start'] = $this->input->post('start');
    $search = $this->input->post('search');
    $post['search_value'] = $search['value'];
    $post['order'] = $this->input->post('order');
    $post['draw'] = $this->input->post('draw');
    $post['status'] = $this->input->post('status');

    $post['recordsTotal'] = $this->main_model->count('black_ip', NULL, 'id');
        if ($post['search_value']) {
            $post['recordsFiltered'] = $this->main_model->count_limit('black_ip', $post['length'], $post['start'], $post['search_value']);
        } else {
            $post['recordsFiltered'] = $post['recordsTotal'];
        }

    $tabledata = $post;

    $data = $this->main_model->get_ips($post['length'], $post['start'], $post['search_value']);

    foreach ($data as &$value) {
            $row = array();

            $row['id'] = $value['id'];
            $row[] = $value['id'];
            $row[] = "<span class=\"label label-primary\">".$value['total']."</span>";
            $row[] = "<span class=\"label label-success\">".$value['totals']."</span>";
            $row[] = "<span class=\"label label-warning\">".$value['totalua']."</span>";
            $row[] = "<a href=\"/ips/".$value['country']."\"><img alt=\"".$value['country']."\" title=\"".$value['country']."\" src=\"/assets/flags/24/".$value['country'].".png\" alt=\"".$value['country']."\"> </a>";
            $row[] ="<a target=\"_blank\" href=\"/ip/".$value['ip']."\"> ".$value['ip']."</a>";
            $row[] = $value['cause'];
            $row[] = $value['comment'];

            $row[] = "<a href=\"https://apps.db.ripe.net/search/query.html?searchtext=".$value['ip']."&bflag=true&source=GRS#resultsAnchor#resultsAnchor#resultsAnchor\" target=\"_blank\" class=\"btn btn-xs btn-success\"><i class=\"fa fa-info-circle\"></i></a><a href=\"/delete_black_ip/".$value['ip']."\" class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i></a>";

            $tabledata['data'][] = $row;
    }

    echo json_encode($tabledata);
}


public function delete_black_ip($ip = false){
    $this->load->model('main_model');
    $this->main_model->delete('black_ip', ['ip' => $ip]);
    redirect('ips');
}

public function delete_logs(){
    $this->load->model('main_model');
    $this->main_model->truncate('logs');
    $this->main_model->truncate('black_ip');
    $this->main_model->truncate('log_country_check');
    redirect('domains');
}

public function delete_logs_domain($domain){
    $this->load->model('main_model');
    $this->main_model->delete('logs', ["domain" => $domain]);
    redirect('domain/'.$domain);
}

public function delete_all_black(){
    $this->load->model('main_model');
    $this->main_model->delete('black_ip', ['ip !=' => 1]);
    redirect('ips');
}

public function delete_domain($domain = false){
    $this->load->model('main_model');
    $this->main_model->delete('domains', ['id' => $domain]);
    redirect('domains');
}

public function delete_net($net = false){
    $this->load->model('main_model');
    $this->main_model->delete('black_net', ['id' => $net]);
    redirect('net');
}

public function delete_isp($net = false){
    $this->load->model('main_model');
    $this->main_model->delete('black_isp', ['id' => $net]);
    redirect('isp');
}

public function delete_ua($net = false){
    $this->load->model('main_model');
    $this->main_model->delete('black_ua', ['id' => $net]);
    redirect('ua');
}

public function pixel_red()
{
        $this->load->model('main_model');
        $id = $this->input->post('id');
        $name = trim($this->input->post('name'));
        $text = $this->input->post('text');
        $result = $this->main_model->update('domains', ['id' => $id], [$name => $text]);
        if ($result) {
            $data['result'] = true;
        } else {
            $data['result'] = false;
        }
        echo json_encode($data);
}


public function ajax_domains(){

    $post['length'] = $this->input->post('length');
    $post['start'] = $this->input->post('start');
    $search = $this->input->post('search');
    $post['search_value'] = $search['value'];
    $post['order'] = $this->input->post('order');
    $post['draw'] = $this->input->post('draw');
    $post['status'] = $this->input->post('status');

    $post['recordsTotal'] = $this->main_model->count('domains', NULL, 'id');
        if ($post['search_value']) {
            $post['recordsFiltered'] = $this->main_model->count_limit('domains', $post['length'], $post['start'], $post['search_value']);
        } else {
            $post['recordsFiltered'] = $post['recordsTotal'];
        }

    $tabledata = $post;

    if($post['order'][0]['column']==0){
        $order = "domain";
    }elseif($post['order'][0]['column']==1){
        $order = "comment";
    }elseif($post['order'][0]['column']==2){
        $order = "link";
    }elseif($post['order'][0]['column']==3){
        $order = "white_link";
    }elseif($post['order'][0]['column']==4){
        $order = "total";
    }elseif($post['order'][0]['column']==5){
        $order = "black";
    }elseif($post['order'][0]['column']==6){
        $order = "land";
    }elseif($post['order'][0]['column']==7){
        $order = "white";
    }elseif($post['order'][0]['column']==8){
        $order = "leads";
    }elseif($post['order'][0]['column']==9){
        $order = "yes_approve";
    }elseif($post['order'][0]['column']==10){
        $order = "no_approve";
    }

    $data = $this->main_model->get_all_stat($this->user['id'], $post['start'], $post['length'], $post['search_value'], $order,$post['order'][0]['dir']);

    foreach ($data as &$value) {
            $row = array();

            $row['id'] = $value['id'];

            $row[] = "<img alt=\"" . $value['country'] . "\" title=\"" . $value['country'] . "\" src=\"/assets/flags/24/" . $value['country'] . ".png\" alt=\"\"> <a href=\"/domain/" . $value['domain'] . "\">" . $value['domain'] . "</a></br><span class=\"label label-default\">" . $value['status_s'] . "</span>";
            $row[] = $value['comment'];
            $row[] = $value['link'];
            $row[] = $value['white_link'];
            $row[] = $value['total'];
            $row[] = $value['black'];
            $row[] = $value['land'];
            $row[] = $value['white'];
            $row[] = $value['leads'];
            $row[] = $value['yes_approve'];
            $row[] = $value['no_approve'];
            $row[] = "<a href=\"/delete_domain/".$value['id']."\" class=\"btn btn-danger\"><i class=\"fa fa-trash\"></i></a><a href=\"/download_domain/".$value['id']."\" class=\"btn btn-success\"><i class=\"fa fa-download\"></i></a>";

            $tabledata['data'][] = $row;
    }

    echo json_encode($tabledata);
}


public function download_domain($domain=false){
    if($domain){
        $index = '<?
 $post["ip"] = $_SERVER["HTTP_CF_CONNECTING_IP"]? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER["REMOTE_ADDR"];
 $post["domain"] = $_SERVER["HTTP_HOST"];
 $post["referer"] = @$_SERVER["HTTP_REFERER"];
 $post["user_agent"] = $_SERVER["HTTP_USER_AGENT"];
 $post["headers"] = json_encode(apache_request_headers());
 // $post["land"] = 1; //раскомментировать на в индексном файле лендинга

 $curl = curl_init("'.base_url().'api/check_ip");
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
 curl_setopt($curl, CURLOPT_TIMEOUT, 5);
 curl_setopt($curl, CURLOPT_POST, true);
 curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

 $json_reqest = curl_exec($curl);
 curl_close($curl);
 $api_reqest = json_decode($json_reqest);

if(!@$api_reqest || @$api_reqest->white_link || @$api_reqest->result == 0){
    require_once("w.php");
 }else{
    require_once("b.php");
 }';
header('Content-Description: File Transfer');
header('Content-type: application/php');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Disposition: attachment; filename="index.php"');
echo $index;
    }
}

public function domains(){
    $data['user'] = $this->user;
    $data['domains'] = "";

    if($this->input->post('new')){

        $error_data = [];
        $succes_data = [];

        $new['link'] = $this->input->post('link');
        $new['comment'] = $this->input->post('comment');
        $new['traf'] = $this->input->post('traf');
        $new['white_link'] = $this->input->post('white_link');
        $new['user'] = $this->user['id'];
        $vowels = array("https://", "www.", "http://", "/", ":");

        if(!$new['traf']) $new['traf'] = 1;

        $countrys = $this->input->post('country');
        if(!$countrys) $error_data['errors'][] = 'выберите хотя бы одну страну.';

        $domains = $this->input->post('domen');

        if(!$domains){
            $error_data['errors'][] = 'заполните поле домен.';
        }elseif($domains AND $countrys){
            $domains_arr = explode(',', $domains);
            foreach ($domains_arr as $key => $domain) {
                $domain = trim($domain);
                $domain = str_replace($vowels, "", $domain);
                foreach ($countrys as $key => $country) {
                    $check_domain = $this->main_model->get('domains' , ['domain' => $domain, 'country' => $country]);
                    if($check_domain){
                         $error_data['errors'][] = "Домен ".$domain." - ".$country." уже добавлен.";
                    }else{

                        $check_serv_ip = @dns_get_record($domain, DNS_A);
                        if(@$check_serv_ip[0]['ip']){
                            $new['status_s'] = $check_serv_ip[0]['ip'];
                        }
                        $new['domain'] = $domain;
                        $new['country'] = $country;
                        $this->main_model->insert('domains', $new);
                        $succes_data['succes'][] = "Домен ".$new['domain']." - ".$new['country']." успешно добавлен.";
                    }
                }
            }
        }
        if($error_data) $data['domains'] = $domains;
        $data['error'] = $error_data;
        $data['succes'] = $succes_data;
    }

    $data['error'] = @$error_data;
    $data['succes'] = @$succes_data;

    $data['countries'] = $this->main_model->getAll('countries', ['alpha2 !=' => 'no_detected'], 0, 'langEN');
    $check_user_country = $this->main_model->check_user_country($data['user']['id']);

    foreach ($check_user_country as $key_u => $user_countrie) {
        foreach ($data['countries'] as $key_c => $countrie) {
            if($countrie['alpha2'] == $user_countrie['country']){
                unset($data['countries'][$key_c]);
                array_unshift($data['countries'], $countrie);
            }
        }
    }

    $data['menu']='domains';

    $this->load->view('/backend/block/head',$data);
    $this->load->view('/backend/block/menu',$data);
    $this->load->view('/backend/block/sidebar',$data);
    $this->load->view('/backend/domains',$data);
}


public function domain($domain=false){
    $data['user'] = $this->user;
    $data['succes'] = false;

    $data['menu']='domain';
    $data['domain']=$domain;

    $this->load->view('/backend/block/head',$data);
    $this->load->view('/backend/block/menu',$data);
    $this->load->view('/backend/block/sidebar',$data);
    $this->load->view('/backend/domain',$data);
}

public function ajax_domain($domain=false){

    $post['length'] = $this->input->post('length');
    $post['start'] = $this->input->post('start');
    $search = $this->input->post('search');
    $post['search_value'] = $search['value'];
    $post['order'] = $this->input->post('order');
    $post['draw'] = $this->input->post('draw');
    $post['status'] = $this->input->post('status');

    $post['recordsTotal'] = $this->main_model->count('logs', ["domain" => $domain], 'id');
        if ($post['search_value']) {
            $post['recordsFiltered'] = $this->main_model->count_limitd('logs', $post['length'], $post['start'], $post['search_value'], ["domain" => $domain]);
        } else {
            $post['recordsFiltered'] = $post['recordsTotal'];
        }

    $tabledata = $post;

    $data = $this->main_model->getAllsd('logs', $post['length'], $post['start'], ["domain" => $domain]);

    foreach ($data as &$value) {
            $row = array();

            $row['id'] = $value['id'];
            $row[] = $value['id'];
            $row[] = $value['date'];
            $row[] = "<img alt=\"" . $value['country'] . "\" title=\"" . $value['country'] . "\" src=\"/assets/flags/24/" . $value['country'] . ".png\" alt=\"\"><span class=\"label label-default\">" . $value['ip'] . "</span>";

            if($value['result'] == 'white'){
                $res = '<i class="fa fa-ban" aria-hidden="true"></i>';
            }elseif($value['result'] == 'black' AND $value['land'] == 1){
                $res = '<th><i class="fa fa-bullseye" aria-hidden="true"></i></th>';
            }else{
                $res = '<th><i class="fa fa-check" aria-hidden="true"></i></th>';
            }

            $row[] = $res;

            $errors = "";
            $bals = json_decode($value['response_api'], true);
            if(@$bals['errors']){
            foreach ($bals['errors'] as $key => $val) {
                $errors .= $val.'<br>';
            }
            }
            $row[] = $errors;

            if ($value['mobile'] == 1) {
                $value['mobile'] = '<i class="fa fa-mobile" aria-hidden="true"></i>';
            } elseif ($value['mobile'] == 0) {
                $value['mobile'] = '<i class="fa fa-desktop" aria-hidden="true"></i>';
            }

            $row[] = $value['mobile'];

            $row[] = $value['descr'];

            $tabledata['data'][] = $row;
    }

    echo json_encode($tabledata);
}

}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */