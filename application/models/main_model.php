<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This is the autologin model used by the Authentication library
 * It handles interaction with the database to store autologin keys
 */
class Main_model extends CI_Model {

public function get_all_stat($user_id,$start,$lenght,$search = false, $order = false, $dir = false)
{
    $qGetAll = "SELECT d.domain, d.id,d.country,d.link,d.white_link,d.metrika_id,d.traf,d.status,d.status_s,d.comment,
    (select COUNT(logs.id) from  logs  where  d.domain = logs.domain AND d.country=logs.country) AS total,
    (select COUNT(leads.id) from  leads  where  d.domain = leads.host AND d.country=leads.country) AS leads,
    (select COUNT(leads.id) from  leads  where  d.domain = leads.host AND approve='confirmed' AND d.country=leads.country) AS yes_approve,
    (select COUNT(leads.id) from  leads  where  d.domain = leads.host AND approve='decline' AND d.country=leads.country) AS no_approve,
    (select COUNT(logs.id) from  logs  where logs.result = 'white' AND d.domain = logs.domain AND preview = 0) AS white,
    (select COUNT(logs.id) from  logs  where logs.result = 'black' AND d.domain = logs.domain AND preview = 0 AND logs.land=0 AND d.country=logs.country) AS black,
    (select COUNT(logs.id) from  logs  where logs.result = 'black' AND d.domain = logs.domain AND preview = 0 AND logs.land=1 AND d.country=logs.country) AS land
    FROM `domains` d WHERE d.user=".$user_id;
    if($search) $qGetAll .= " AND (d.domain LIKE '%".$search."%' OR d.comment LIKE '%".$search."%' OR d.link LIKE '%".$search."%' OR d.white_link LIKE '%".$search."%')";
    $qGetAll .= " group by d.id ";
    if($order)$qGetAll .= "ORDER BY ".$order." ".$dir;
    $qGetAll .= " LIMIT ".$start.", ".$lenght.";";
    $res = $this->db->query($qGetAll);
    return  $res->result_array();
}

public function check_user_country($user_id)
{
    $qGetAll = "SELECT count(country) as total, country  FROM `domains` WHERE user=".$user_id." GROUP BY `country` ORDER BY total ASC";
    $res = $this->db->query($qGetAll);

    return  $res->result_array();
}

public function get_ips($start,$lenght,$search = false, $order = false, $dir = false)
{
    $qGetAll = "SELECT b.id, b.ip, b.cause, b.comment, b.country,
    (select COUNT(DISTINCT logs.id) from  logs  where  b.ip = logs.ip) AS total,
    (select COUNT(DISTINCT logs.domain) from  logs  where  b.ip = logs.ip) AS totals,
    (select COUNT(DISTINCT logs.user_agent) from  logs  where  b.ip = logs.ip) AS totalua
    FROM `black_ip` b WHERE 1=1 GROUP BY `id`";

    $res = $this->db->query($qGetAll);
    return  $res->result_array();
}

public function charts()
{
        //$this->output->enable_profiler(TRUE);
        $graph=[];
        $new_graph =[];

        $this->db->select('DATE(date) as period, COUNT(id) as leads');
        $this->db->where('approve !=' , 'trash');
        $this->db->group_by('period');
        $result = $this->db->get('leads');
        $leads = $result->result_array();

        foreach ($leads as $key => $leadays) {
            $graph[$leadays['period']]['period'] = $leadays['period'];
            $graph[$leadays['period']]['leads'] = $leadays['leads'];
        }

        $this->db->select('DATE(date) as period, COUNT(id) as black');
        $this->db->where('result' , 'black');
        $this->db->group_by('period');
        $result2 = $this->db->get('logs');
        $black = $result2->result_array();

        foreach ($black as $key => $blackays) {
            $graph[$blackays['period']]['period'] = $blackays['period'];
            $graph[$blackays['period']]['black'] = $blackays['black'];
        }

        $this->db->select('DATE(date) as period, COUNT(id) as white');
        $this->db->where('result' , 'white');
        $this->db->group_by('period');
        $result3 = $this->db->get('logs');
        $white = $result3->result_array();

        foreach ($white as $key => $whitekays) {
            $graph[$whitekays['period']]['period'] = $whitekays['period'];
            $graph[$whitekays['period']]['white'] = $whitekays['white'];
        }

        foreach ($graph as $key => $value) {
             $period = $value['period'];
             $leads = @$value['leads'] ? $value['leads'] : 0;
             $black = @$value['black'] ? $value['black'] : 0;
             $white = @$value['white'] ? $value['white'] : 0;

             $new_graph[] = ["period" => $period, "leads" => $leads, "black" => $black, "white" => $white];
        }
        return $new_graph;
}

public function truncate($table)
{
    $this->db->truncate($table);
}

public function getAll($table, $condition = array(), $limit = 0, $order = false)
{
    if ($condition) $this->db->where($condition);
    if ($limit) $this->db->limit($limit);
    if ($order) $this->db->order_by($order, 'asc');
    $result = $this->db->get($table);
    return $result->result_array();
}

public function getAlls($table, $lenght = 0, $start = 0, $like = false, $nlaike = false)
{
    if ($like) $this->db->like($nlaike,$like);
    if (@$lenght) $this->db->limit($lenght,$start);
    if (@$order) $this->db->order_by($order, 'asc');
    $result = $this->db->get($table);
    return $result->result_array();
}

public function count_all_group($table, $condition = array(), $group = false)
{
    $this->db->where($condition);
    $this->db->select('ip, COUNT(id) as total');
    if ($group) $this->db->group_by($group); 
    $result = $this->db->get($table);
    return $result->row_array();
}



public function count_limit($table, $limit, $start, $like)
{
    $this->db->limit($limit, $start);
    if($like) $this->db->like('domain', $like);
    $result = $this->db->get($table);
    return $result->num_rows();
}

public function count($table, $condition = array(), $group = false)
{
    /*SELECT id_topic, COUNT(id_topic) FROM posts GROUP BY id_topic;*/

    $this->db->select($group.', COUNT('.$group.') as total');
    if($condition) $this->db->where($condition);
    $this->db->group_by($group);
    $result = $this->db->get($table);
    return $result->num_rows();
}

public function getAllsd($table, $lenght = 0, $start = 0, $condition = array(), $like = false)
{
    if ($like) $this->db->like($like);
    if (@$lenght) $this->db->limit($lenght,$start);
    if($condition) $this->db->where($condition);
    if (@$order) $this->db->order_by($order, 'asc');
    $result = $this->db->get($table);
    return $result->result_array();
}

public function count_limitd($table, $limit, $start, $like, $condition = array())
{
    $this->db->limit($limit, $start);
    if($condition) $this->db->where($condition);
    if($like) $this->db->like('domain', $like);
    $result = $this->db->get($table);
    return $result->num_rows();
}

public function getAllindex($table, $condition = array(), $limit = 0, $order = false)
{
    $this->db->where($condition);
    if ($limit) $this->db->limit($limit);
    if ($order) $this->db->order_by($order, 'desc');
    $result = $this->db->get($table);
    $res = [];
    foreach ($result->result_array() as $key => $value) {
        $res[$value['abbr']] = $value;
    }
    return $res;
}

public function get($table, $condition=false)
{
    if($condition)$this->db->where($condition);
    $result = $this->db->get($table);
    return $result->row_array();
}

public function delete($table, $condition)
{
    $this->db->where($condition);
    $this->db->delete($table);
}

public function insert($table, $data)
{
    $this->db->insert($table, $data);
    return $this->db->insert_id();
}


public function get_select($table, $condition = false, $select)
{
    $this->db->select($select);
    if ($condition) $this->db->where($condition);
    $result = $this->db->get($table);
    return $result->row_array();
}

public function update_item($table, $column, $data)
{
    $this->db->where($column, $data[$column]);
    $this->db->update($table, $data);
}

public function update($table, $condition, $data = false)
{
    $this->db->update($table, $data, $condition);
    return $this->db->affected_rows();
}

public function get_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

public function ip_search($ip, $net, $mask) {
  $ip = ip2long($ip);
  $mask = long2ip(pow(2, 32) - pow(2, (32-$mask)));
  $mask = ip2long($mask);
  $net = ip2long($net);
  if (($ip & $mask) == $net) {
    return 1;
}else {
    return 0;
}
}


public function check_country($ip=false){
    $curl = curl_init('http://api.sypexgeo.net/ygpeS/json/'.$ip);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json_sypexgeo = curl_exec($curl);
    curl_close($curl);
    $id = $this->insert('log_country_check', ['ip' => $ip, 'result' => $json_sypexgeo]);
    $sypexgeo = json_decode($json_sypexgeo);
    if(@$sypexgeo->country->iso){
        $this->main_model->update('log_country_check', ['id' => $id], ['country' => $sypexgeo->country->iso]);
        return $sypexgeo->country->iso;
    }else{
        return false;
    }
}

public function curl($url, $post = null, $head=0, $test = false){
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    if($test){
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
    }

    if($head){
        curl_setopt($ch,CURLOPT_HTTPHEADER, $head);
    }else{
        curl_setopt($ch,CURLOPT_HEADER, 0);
    }

    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU");
    if($post){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    $response = curl_exec($ch);
    $header_data = curl_getinfo($ch);
    curl_close($ch);
    if($test){
        return $header_data;
    }else{
        return $response;
    }
}

public function curl_handle($url){
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $url);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 5);
    curl_setopt($curl_handle, CURLOPT_NOSIGNAL, 1);
    curl_setopt($curl_handle, CURLOPT_HEADER, false);
    curl_setopt($curl_handle, CURLOPT_NOBODY, true);
    curl_setopt($curl_handle, CURLOPT_FRESH_CONNECT, true);
    curl_exec($curl_handle);
    curl_close($curl_handle);
}

public function check_block_domain($domain)
{
    return true;
}

public function net_search($ip, $net, $mask)
{
  $ip = ip2long($ip);
  $mask = long2ip(pow(2, 32) - pow(2, (32-$mask)));
  $mask = ip2long($mask);
  $net = ip2long($net);
  if (($ip & $mask) == $net) {
    return 1;
  }else {
    return 0;
  }
}

}