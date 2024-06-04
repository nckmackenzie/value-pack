<?php

function redirect($page){
    header('location: ' . URLROOT . '/' . $page);
}

function resultset($con,$sql,$arr){
    $stmt = $con->prepare($sql);
    $stmt->execute($arr);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function singleset($con,$sql,$arr){
    $stmt = $con->prepare($sql);
    $stmt->execute($arr);
    return $stmt->fetch(PDO::FETCH_OBJ);
}