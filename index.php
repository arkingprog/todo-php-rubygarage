<?php
include_once 'server/dbconnect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>TODO RUBYGARAGE</title>
    <?php include_once 'view/header.html'?>
    <script src="js/angular.min.js"></script>
    <script src="js/moment.js"></script>

    <script src="app/app.js"></script>
</head>
<body>

<?php if(!isset($_SESSION['user'])): ?>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="./index.php">TODO RUBYGARAGE</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="./view/register.php">Sign up</a></li>
                    <li><a href="./index.php">Login</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
    <div class="container">
        <div class="row">
                <?php include 'view/login.php'?>
        </div>
    </div>
<?php else: ?>

    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="./index.php">TODO RUBYGARAGE</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">

                <ul class="nav navbar-nav navbar-right">
                    <li><a href="./index.php">
                            <?php
                            include_once 'server/dbconnect.php';
                            if(!isset($_SESSION['user']))
                            {
                                header("Location: view/login.php");
                            }

                            $selectQuery=$db->prepare("SELECT * FROM users WHERE id=:id");
                            $selectQuery->execute(['id'=>$_SESSION['user']]);
                            $items=$selectQuery->rowCount() ? $selectQuery : [];
                            foreach($items as $item){
                                $userRow=$item;
                                echo $userRow['username'];
                            }

                            ?>
                        </a></li>
                    <li><a href="view/logout.php?logout">Logout</a></li>

                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>

    <div ng-app="myApp">
       <div ng-controller="todoListCtrl" ng-cloak>
           <div ng-repeat="todo_list in todo_lists">
               <div class="row">
                   <div class="col-md-6 col-centered index_row" id="todo_list_id_">
                       <div class="index_row clearfix " id="todo_list_id_7">
                           <div class="container-fluid title bg-primary">
                               <div class="row">
                                   <div class="col-md-8 ">
                                       <h2 class="todo_list_title"><span class="glyphicon glyphicon-calendar"></span>
                                          {{todo_list.title}}
                                       </h2>
                                   </div>
                                   <div class="col-md-2 col-md-offset-2 text-right">
                                       <div class="trash">
                                           <h3>
                                               <i ng-click="editTodoList(todo_list)" class="glyphicon glyphicon-pencil"></i>
                                                              |
                                               <i ng-click="deleteTodoList(todo_list.id)" class="glyphicon glyphicon-trash"></i>
                                           </h3>
                                       </div>
                                   </div>
                               </div>
                           </div>

                           <div class="row" style="margin-top: 15px; margin-bottom: 15px">
                               <div class="col-md-12 ">
                                   <div class="form" style="width: 100%;">
                                       <form ng-submit="submitAddItem(todo_list.id)" class="new_todo_item form form-inline">
                                           <span class="">&nbsp;</span>
                                           <span class="glyphicon glyphicon-plus"></span>
                                           <input name="utf8" type="hidden" value="?">
                                           <input class="form-control" placeholder="Start typing here to create a task" required="required" ng-model="content.content[todo_list.id]" minlength="3" type="text" style="width: 80%">
                                           <input type="submit" name="commit" value="Add Task" style="width: 15%" class="create-todo-item btn btn-default">
                                       </form>
                                   </div>
                               </div>
                           </div>
                           <div id="todo_items_wrapper_list" class="row" ng-repeat="item in todo_list.items">
                               <div id="todo_items_wrapper ">
                                   <div class="row">
                                       <div class="col-md-1">
                                           <div class="complete pull-right">
                                                   <i class="glyphicon glyphicon-ok" ng-click="completeItem(todo_list.id,item.id)"></i>
                                           </div>
                                       </div>
                                       <div class="col-md-8">
                                           <div class="todo_item">
                                               <p ng-style="getStyleComplete(item.compiled_at)">{{item.content}} </p>
                                           </div>
                                       </div>
                                       <div class="col-md-1">
                                           <p data-placement="right" data-toggle="tooltip" class="pull-right glyphicon glyphicon-calendar" title="{{item.deadline_text}}">
                                           </p>
                                       </div>
                                       <div class="col-md-2">
                                           <div class="trash" title="test">
                                               <i ng-click="itemPriority(todo_list.id,item.id)" ng-class="item.priorityClass"></i>
                                                                 <i> | </i>
                                               <i ng-click="editItem(item)" class="glyphicon glyphicon-pencil"></i>
                                                                  <i> | </i>
                                               <i ng-click="deleteItem(todo_list.id,item.id)" class="glyphicon glyphicon-trash"></i>
                                           </div>
                                       </div>
                                   </div>

                               </div>

                           </div>
                       </div>
                   </div>
               </div>





           </div>
           <div class="row">
               <div class="col-centered col-md-1">
                   <button class="btn btn-primary" ng-click="addTodoList()"><span class="glyphicon glyphicon-plus"></span> New project</button>
               </div>
           </div>
       </div>







   </div>
<?php endif; ?>

<!-- Libraries -->


<script src="js/ui-bootstrap-tpls-0.12.1.min.js"></script>
<script src="js/angular-route.min.js"></script>
<script src="js/angular-animate.min.js"></script>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
</body>
</html>