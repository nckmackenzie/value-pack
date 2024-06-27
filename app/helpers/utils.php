<?php
declare(strict_types=1);
require APPROOT . '/vendor/autoload.php';

function redirect($page){
    header('location: ' . URLROOT . '/' . $page);
}

function invalid_setter($field):string
{
    return !empty($field) ? 'is-invalid' : '';
}

function alert_type($type):string{
    switch ($type) {
        case 'success':
            return 'alert custom-success alert-dismissible fade show';
            // break;
        case 'error':
            return 'alert custom-destructive alert-dismissible fade show';
            // break;
        default:
            return 'alert custom-warning alert-dismissible fade show';
            // break;
    }
}

function selectdCheck($value1,$value2){
    if ($value1 == $value2){
      echo 'selected="selected"';
     } else {
       echo '';
     }
     return;
}

function resultset($con,$sql,$arr){
    $stmt = $con->prepare($sql);
    $stmt->execute($arr);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function cuid($short = false) {
    $cuid = new EndyJasmi\Cuid;
    if($short){
        return $cuid->slug();
    }
    return $cuid->cuid(); 
}

function singleset($con,$sql,$arr){
    $stmt = $con->prepare($sql);
    $stmt->execute($arr);
    return $stmt->fetch(PDO::FETCH_OBJ);
}

function getdbvalue($con,$sql,$arr){
    $stmt = $con->prepare($sql);
    $stmt->execute($arr);
    return $stmt->fetchColumn();
}

function getusermenuitems($con,$user_id)
{
    $role = getdbvalue($con,'SELECT role_id FROM users WHERE id=?',[$user_id]);
    $is_admin = (int)$role < 3; 
    if ($is_admin)
    {
        $sql = 'SELECT 
                    DISTINCT module
                FROM
                    forms
                ORDER BY
                    module_id
        ';
        $stmt = $con->prepare($sql);
        $stmt->execute();
    }
    else
    {
        $sql = 'SELECT 
                        DISTINCT f.module 
                FROM 
                    role_rights r INNER JOIN forms f on r.form_id = f.id 
                WHERE (r.role_id = ?)
                ORDER BY f.module_id';
        $stmt = $con->prepare($sql);
        $stmt->execute([$role]);
    }    
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);
    $modules = array();
    foreach($results as $result) {
        array_push($modules,$result->module);
    }
    return $modules;
}

function getmodulemenuitems($con,$userid,$module)
{
    $role = getdbvalue($con,'SELECT role_id FROM users WHERE id=?',[$userid]);
    $is_admin = (int)$role < 3; 

    if($is_admin){
        $sql = 'SELECT form_name,
                       path
                FROM   forms
                WHERE  (module = :menu)
                ORDER BY menu_order';
        $stmt = $con->prepare($sql);
    }else{
        $has_rights = getdbvalue($con,'SELECT COUNT(*) FROM user_rights WHERE user_id=?',[$userid]) > 0;
        if($has_rights){
            $sql = 'SELECT f.form_name,
                        f.path
                    FROM   user_rights r inner join forms f on r.form_id = f.id
                    WHERE  r.user_id = :usid AND (f.module = :menu)
                    ORDER BY menu_order';
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':usid',$userid);
        }
        if(!$has_rights && !is_null($role)){
            $sql = 'SELECT f.form_name,
                        f.path
                    FROM   role_rights r inner join forms f on r.form_id = f.id
                    WHERE  r.role_id = :roleid AND (f.module = :menu)
                    ORDER BY menu_order';
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':roleid',$role);
        }
        if(!$has_rights && is_null($role)){
            return [];
        }         
    }
    $stmt->bindValue(':menu',$module);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function check_role_rights($con,$role,$form){
    $stmt = $con->prepare('SELECT COUNT(*) 
                           FROM forms f
                           INNER JOIN role_rights r on r.form_id = f.id
                           WHERE (role_id = ?) AND (form_name = ?)');
    $stmt->execute([$role,$form]);
    $count = (int)$stmt->fetchColumn();
    if($count === 0){
        return false;
    }else{
        return true;
    }
}

function check_rights($model,$form){
    if(isset($_SESSION['role']) && (int)$_SESSION['role'] > 2  && !$model->check_rights($form)){
        var_dump($_SESSION['role']);
        redirect('auth/forbidden');
        exit;
    }
}

function calculate_vat($type, $net_amount){
    $vat_percentage = 0.16;
    switch ($type) {
        case "no-vat":
            return array($net_amount, 0, $net_amount);
        case "inclusive":
            $vat_amount = ($vat_percentage * $net_amount) / 1.16;
            $exclusive_amount = $net_amount - $vat_amount;
            return array($exclusive_amount, $vat_amount, $net_amount);
        case "exclusive":
            $vat_amount = $net_amount * $vat_percentage;
            $inclusive_amount = $net_amount + $vat_amount;
            return array($net_amount, $vat_amount, $inclusive_amount);
        default:
            return null;
    }
}

function get_next_db_no($con,$table,$field = 'id'){
    $count = (int)getdbvalue($con,"SELECT COUNT(*) FROM $table",[]);
    if($count === 0) return 1;
    return (int)getdbvalue($con,"SELECT MAX($field) FROM $table",[]) + 1;
}

function date_validator($validator_type,$date1,$date2 = ''){
    switch ($validator_type) {
        case 'greater_than_today':
            return strtotime($date1) > strtotime(date('Y-m-d'));
        default:
            return false;
    }
}

function to_float($input) {
    $cleanedInput = str_replace(',', '', $input);

    $floatValue = (float)$cleanedInput;
    
    return $floatValue;
}