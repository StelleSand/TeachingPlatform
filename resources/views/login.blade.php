<!Doctype html>
<html ng-app="myApp">
<head>
<meta charset="utf-8">
<title>用户登录</title>
<link href="./css/bootstrap.min.css" type="text/css" rel="stylesheet">
<script src="./js/angular.min.js"></script>
<script>
angular.module('myApp', [])
    .controller('FormController', function ($scope, $http) {
        $scope.submitForm = function () {
            $http({ method: 'POST', url: '/userLogin', params: $scope.user, headers: { 'Content-Type': 'application/x-www-form-urlencoded' }})
                .success(function (data) {
                    console.log(data);
                });
        };
        $scope.resetForm = function () {
            $scope.user.username = '';
            $scope.user.password = '';
        }
    })
</script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form name="loginForm" ng-controller="FormController" action="/userLogin" method="post">
                <div class="form-group">
                    <label class="control-label">用户名</label>
                    <input class="form-control" type="text" name="username" placeholder="请输入用户名" required ng-model="user.username">
                    <div ng-show="loginForm.username.$dirty && loginForm.username.$invalid">
                        <p class="text-danger" ng-show="loginForm.username.$error.required">用户名必须填写</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">密码</label>
                    <input class="form-control" type="password" name="password" placeholder="请输入密码" required ng-model="user.password">
                    <div ng-show="loginForm.password.$dirty && loginForm.password.$invalid">
                        <p class="text-danger" ng-show="loginForm.password.$error.required">密码必须填写</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 text-center"><button type="submit" class="btn btn-primary" ng-disabled="loginForm.$invalid">登录</button></div>
                    <div class="col-md-6 text-center"><button class="btn btn-primary" ng-disabled="loginForm.username.$error.required && loginForm.password.$error.required" ng-click="resetForm()">重置</button></div>
                </div>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>
</body>
</html>