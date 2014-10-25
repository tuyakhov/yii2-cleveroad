
var app = angular.module('Cleveroad', ['ngRoute']).config(function($routeProvider) {
    $routeProvider.when('/login', {
        controller: "LoginCtrl"
    });
});

app.controller('LoginCtrl', ['$scope', 'loginService', function($scope, loginService) {
    $scope.login = function() {
        console.log(loginService.login($scope.credentials))
    };

    console.log(loginService.login);
}]);

app.factory('loginService', function($http) {
    return {
        login: function(credentials) {
            $promice = $http.post('', credentials)
        }
    }
});