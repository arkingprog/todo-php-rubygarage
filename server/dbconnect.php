<?php
session_start();

$dsn = 'pgsql:'
    . 'host=ec2-107-21-229-87.compute-1.amazonaws.com;'
    . 'dbname=d6cs0p001bbf4j;'
    . 'user=gnxhobringpqjy;'
    . 'port=5432;'
    . 'sslmode=require;'
    . 'password=3KuSCO4nBlUZ_EYSk-v_WXzeKs';

$db=new PDO($dsn);
/*
$db=new PDO('mysql:dbname=rubygarage_todo;host=localhost','root');
*/