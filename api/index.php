<?php
require '../vendor/autoload.php';
session_start();
$app =new \Slim\App();
function getDB()
{
    //return $db=new PDO('mysql:dbname=rubygarage_todo;host=localhost','root');
    $dsn = 'pgsql:'
        . 'host=ec2-107-21-229-87.compute-1.amazonaws.com;'
        . 'dbname=d6cs0p001bbf4j;'
        . 'user=gnxhobringpqjy;'
        . 'port=5432;'
        . 'sslmode=require;'
        . 'password=3KuSCO4nBlUZ_EYSk-v_WXzeKs';

    return $db=new PDO($dsn);
}
//todo_list api
$app->post('/todo_list',function($request,$response,$args){
    $allGetVars = $request->getParsedBody();
    $db=getDB();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $addedQuery=$db->prepare('insert into todo_lists(user_id,title,created_at,updated_at) values(:user_id,:title,NOW(),NOW())');


    try{
        $addedQuery->execute(['title'=>$allGetVars['title'],'user_id'=>1]);


    }
    catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }
});
$app->get('/todo_list/{id}',function($request,$response,$args){
    $db=getDB();
    $todoListQuery= $db->prepare("select * from todo_lists where id=:id");
    $todoListQuery->execute(['id'=>$args['id']]);
    $todoList=$todoListQuery->fetchAll(PDO::FETCH_ASSOC);
    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($todoList));
});
$app->get('/todo_list',function($request,$response,$args){
    $db=getDB();
    $todoListQuery= $db->prepare("select * from todo_lists");
    $todoListQuery->execute([]);
    $todoList=$todoListQuery->fetchAll(PDO::FETCH_ASSOC);
    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($todoList));
});
$app->delete('/todo_list/{id}',function($request,$response,$args){
    $db=getDB();
    $deleteTodoListQuery= $db->prepare("delete from todo_lists where id=:id");
    $deleteTodoListQuery->execute(['id'=>$args['id']]);
    return $response;
});
$app->put('/todo_list/{id}',function($request,$response,$args){
    $db=getDB();
    $allGetVars = $request->getParsedBody();
    $updateTodoListQuery= $db->prepare("update todo_lists set title=:title, updated_at=now() where id=:id");
    $updateTodoListQuery->execute(['id'=>$args['id'],'title'=>$allGetVars['title']]);
    echo $allGetVars['title'];
});
//todo_item api
$app->get('/todo_list/{todo_list_id}/todo_item/{todo_item_id}',function($request,$response,$args){
    $db=getDB();
    $todoItemQuery= $db->prepare("select * from todo_items where id=:id and todo_list_id=:todo_list_id");
    $todoItemQuery->execute(['id'=>$args['todo_item_id'],'todo_list_id'=>$args['todo_list_id']]);
    $todoItem=$todoItemQuery->fetchAll(PDO::FETCH_ASSOC);
    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($todoItem));
});
$app->get('/todo_list/{todo_list_id}/todo_item',function($request,$response,$args){
    $db=getDB();
    $todoItemQuery= $db->prepare("select * from todo_items where todo_list_id=:todo_list_id ORDER BY priority DESC, created_at DESC ");
    $todoItemQuery->execute(['todo_list_id'=>$args['todo_list_id']]);
    $todoItem=$todoItemQuery->fetchAll(PDO::FETCH_ASSOC);
    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($todoItem));
});


$app->delete('/todo_list/{todo_list_id}/todo_item/{todo_item_id}',function($request,$response,$args){
    $db=getDB();
    $deleteTodoListQuery= $db->prepare("delete from todo_items where id=:id");
    $deleteTodoListQuery->execute(['id'=>$args['todo_item_id']]);
});
$app->post('/todo_list/{todo_list_id}/todo_item',function($request,$response,$args){
    $allGetVars = $request->getParsedBody();
    $db=getDB();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $addedQuery=$db->prepare("insert into todo_items(todo_list_id,content,created_at,updated_at,compiled_at,deadline,priority) values(:todo_list_id,:content,NOW(),NOW(),false ,null,:priority)");
    try{
        $addedQuery->execute(['todo_list_id'=>$args['todo_list_id'],'content'=>$allGetVars['content'],'priority'=>0]);
    }
    catch(PDOException $e){
        echo "<br>" . $e->getMessage();
    }
    echo $allGetVars['deadline'];
});
$app->put('/todo_list/{todo_list_id}/todo_item/{todo_item_id}',function($request,$response,$args){
    $db=getDB();
    $allGetVars = $request->getParsedBody();
    $updateTodoListQuery= $db->prepare("update todo_items set content=:content,deadline=:deadline where id=:id");
    $updateTodoListQuery->execute(['id'=>$args['todo_item_id'],'content'=>$allGetVars['content'],'deadline'=>$allGetVars['deadline']]);
});
$app->put('/todo_list/{todo_list_id}/todo_item/{todo_item_id}/priority',function($request,$response,$args){
    $db=getDB();
    $todoItemQuery= $db->prepare("select priority from todo_items where id=:id");
    $todoItemQuery->execute(['id'=>$args['todo_item_id']]);
    $todoItem=$todoItemQuery->fetch(PDO::FETCH_ASSOC);
    $updateTodoListQuery= $db->prepare("update todo_items set priority=:priority where id=:id");
    if($todoItem['priority']==0)
    {
        $updateTodoListQuery->execute(['id'=>$args['todo_item_id'],'priority'=>1]);
    }
   else
   {
       $updateTodoListQuery->execute(['id'=>$args['todo_item_id'],'priority'=>0]);
   }
    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($todoItem));
});
$app->put('/todo_list/{todo_list_id}/todo_item/{todo_item_id}/complete',function($request,$response,$args){
    $db=getDB();
    $todoItemQuery= $db->prepare("select compiled_at from todo_items where id=:id");
    $todoItemQuery->execute(['id'=>$args['todo_item_id']]);
    $todoItem=$todoItemQuery->fetch(PDO::FETCH_ASSOC);
    $updateTodoListQuery= $db->prepare("update todo_items set compiled_at=TRUE where id=:id");
    $updateTodoListQuery->execute(['id'=>$args['todo_item_id']]);
    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($todoItem));
});

$app->run();


