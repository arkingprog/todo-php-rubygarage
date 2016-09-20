<?php
session_start();

$dsn = 'pgsql:'
    . 'host=ec2-23-21-235-126.compute-1.amazonaws.com;'
    . 'dbname=d87nh9a60mmm1r;'
    . 'user=ocxitzuqvihisr;'
    . 'port=5432;'
    . 'sslmode=require;'
    . 'password=X6p-VelgjUtJG_m6igY5LIh__f';

$db=new PDO($dsn);
/*
$db=new PDO('mysql:dbname=rubygarage_todo;host=localhost','root');
*/
