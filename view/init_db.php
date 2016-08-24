<?php
include_once '../server/dbconnect.php';

$sql="create table users(
id SERIAL NOT NULL  PRIMARY KEY,
username VARCHAR(35),
email VARCHAR(35),
password VARCHAR(200));
create TABLE todo_lists( id SERIAL NOT NULL  PRIMARY KEY,
  user_id INT REFERENCES users(id) on DELETE CASCADE,
  title VARCHAR (1024),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
create TABLE todo_items(
  id SERIAL NOT NULL  PRIMARY KEY,
  todo_list_id INT REFERENCES todo_lists(id) on DELETE CASCADE,
  content VARCHAR(1024),
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  deadline TIMESTAMP,
  compiled_at boolean,
  priority INT
);";
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try{

    $db->exec($sql);
}
catch(PDOException $e)
{
    echo $sql . "<br>" . $e->getMessage();
}

