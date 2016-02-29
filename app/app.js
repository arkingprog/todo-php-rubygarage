var app=angular.module('myApp',['ui.bootstrap']);
    app.controller('editItemCtrl', function ($scope, $http,$modalInstance,item) {
        $scope.items=item;

        $scope.dt = new Date($scope.items.deadline);
        $scope.open = function($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.opened = true;
        };
        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1
        };

        $scope.formats = ['dd-MMMM-yyyy'];
        $scope.format = $scope.formats[0];
        $scope.ok=function(){
            $http.put('./api/todo_list/'+$scope.items.todo_list_id+'/todo_item/'+$scope.items.id, {'content': $scope.items.content,'deadline':$scope.dt}).success(function (data) {
                $modalInstance.close();
            })
            $modalInstance.close('all');
        };
        $scope.cancel=function(){

            $modalInstance.dismiss('cancel');
        };
    });

    app.controller('addTodoListCtrl', function ($scope, $modalInstance,$http) {
        $scope.sendTodoList=function(){
            if($scope.titleTodoList!=undefined)
                $http.post('./api/todo_list',{'title':$scope.titleTodoList}).success(function(data){
                    $modalInstance.close();
                })
        };
    });
    app.controller('editTodoListCtrl', function ($scope, $modalInstance,$http,todo_list) {
        $scope.todo_list=todo_list;
        $scope.editTodoList=function(){
            if($scope.todo_list.title!=undefined) {
                $http.put('./api/todo_list/' + $scope.todo_list.id, {'title': $scope.todo_list.title}).success(function (data) {
                    $modalInstance.close();
                })
            }
        };
    });


    app.controller('todoListCtrl', function($scope,$http,$modal,$log){
    get_todolist();

    $scope.editItem=function(item){
      var modalInstance=$modal.open({
          templateUrl:'view/editItem.html',
          controller: 'editItemCtrl',
          resolve:{
              item:function(){
                  return item;
              }
          }
      });
    };



    $scope.content={
        content:[],
        priority:[]
    };
    function get_todolist(){
            $http.get('./api/todo_list').success(function(data){
                data.forEach(function(item,i,arr){
                    $http.get('./api/todo_list/'+item.id+'/todo_item').success(function(t) {
                        t.forEach(function(item_i,q,a){
                            var deadlineTime=moment(item_i.deadline);
                            if(item_i.deadline!=null)
                            {
                                if(deadlineTime.isAfter(moment()))
                                {
                                    item_i.deadline_text=moment().to(deadlineTime);
                                }
                                else{
                                    item_i.deadline_text="Deadline expired"
                                }
                            }
                            else {
                                item_i.deadline_text = "No deadline";
                            }

                            if(item_i.priority==0)
                                item_i.priorityClass='glyphicon glyphicon-star-empty'
                            else
                                item_i.priorityClass='glyphicon glyphicon-star';
                        });
                        data[i].items=t;
                    });
                });


                $scope.todo_lists=data;
            });
        };
    function find(array, value) {
        if (array.indexOf) { // если метод существует
            return array.indexOf(value);
        }

        for (var i = 0; i < array.length; i++) {
            if (array[i] === value) return i;
        }

        return -1;
    }
    $scope.submitAddItem=function(id){
        $http.post('./api/todo_list/'+id+'/todo_item',{'content': $scope.content.content[id]}).success(function(data){
            get_todolist();
            $scope.content={content:[]};
        });
    };
    $scope.deleteItem=function(todo_list_id,todo_item_id){
        $http.delete('./api/todo_list/'+todo_list_id+'/todo_item/'+todo_item_id,{}).success(function(data){
            get_todolist();
        });
    };
    $scope.itemPriority=function(todo_list_id,item_id){
      $http.put('./api/todo_list/'+todo_list_id+'/todo_item/'+item_id+'/priority',{}).success(function(data){
        get_todolist();
      });
    };
    $scope.getClassPriority=function(todo_item_priority){
        if(todo_item_priority==0)
            return 'glyphicon glyphicon-star-empty'
        else
            return'glyphicon glyphicon-star';
    };
    $scope.getStyleComplete=function(todo_item_complete){
            if(!todo_item_complete)
                return {
                }
            else
                return {"text-decoration":"line-through",
                    "opacity":"0.4"
                }
        };
    $scope.getStyleCalendar=function(todo_item_deadline){
            if(!todo_item_complete)
                return {
                }
            else
                return {"text-decoration":"line-through",
                    "opacity":"0.4"
                }
        };

    $scope.completeItem=function(todo_list_id,todo_item_id){
        $http.put('./api/todo_list/'+todo_list_id+'/todo_item/'+todo_item_id+'/complete',{}).success(function(data){
            get_todolist();
        });
    };
    $scope.deleteTodoList=function(todo_list_id){
        $http.delete('./api/todo_list/'+todo_list_id,{}).success(function(data){
            get_todolist();
        });
    };
    $scope.addTodoList=function(){
            var modalInstance=$modal.open({
                templateUrl:'view/addTodoList.html',
                controller:'addTodoListCtrl',
                resolve:{
                }
            });
        };
    $scope.editTodoList=function(todo_list){
        var modalInstance=$modal.open({
            templateUrl:'view/editTodoList.html',
            controller:'editTodoListCtrl',
            resolve:{
                todo_list:function()
                {
                    return todo_list;
                }
            }
        });
    };




});
