var app = angular.module('Cleveroad', ['ngRoute']).config(function ($httpProvider, $routeProvider) {
    $routeProvider.when('/login', {
        controller: "LoginCtrl"
    });
    $httpProvider.interceptors.push('authInterceptor');
});

app.controller('LoginCtrl', ['$scope', 'restService', '$window', '$location', function ($scope, restService, $location, $window) {
    $scope.login = function () {
        restService.path = 'users/login';
        restService.postModel($scope.credentials)
            .success(function (token) {
                $location.path('/index');
                $window.sessionStorage.authToken = token;
            })
            .error(function (data) {
                delete $window.sessionStorage.authToken;
                angular.forEach(data, function (error) {
                    $scope.error = error.message;
                });
            });
    };
}]);

app.factory('restService', function ($http, $location, $routeParams) {
    return {

        baseUrl: 'http://api.cleveroad.dev/',
        path: undefined,

        models: function () {
            return $http.get(this.baseUrl + this.path + location.search);
        },

        model: function () {
            if ($routeParams.expand != null) {
                return $http.get(this.baseUrl + this.path + "/" + $routeParams.id + '?expand=' + $routeParams.expand);
            }
            return $http.get(this.baseUrl + this.path + "/" + $routeParams.id);
        },

        get: function () {
            return $http.get(this.baseUrl + this.path);
        },

        postModel: function (model) {
            return $http.post(this.baseUrl + this.path, model);
        },

        putModel: function (model) {
            return $http.put(this.baseUrl + this.path + "/" + $routeParams.id, model);
        },

        deleteModel: function () {
            return $http.delete(this.baseUrl + this.path);
        }
    };
});

app.factory('authInterceptor', function ($q, $window) {
    return {
        request: function (config) {
            if ($window.sessionStorage.authToken && config.url.substring(0, 4) == 'http') {
                config.params = {'access-token': $window.sessionStorage.authToken};
            }
            return config;
        },
        responseError: function (rejection) {
            if (rejection.status === 401) {
                $window.location = 'login';
            }
            return $q.reject(rejection);
        }
    };
});