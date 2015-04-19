'use strict';

/**
 * @ngdoc function
 * @name bilderbrettApp.controller:ThreadCtrl
 * @description
 * # ThreadCtrl
 * Controller of the bilderbrettApp
 */
angular.module('bilderbrettApp')
  .controller('ThreadCtrl', function ($scope, $routeParams) {
    $scope.board=$routeParams.board;
    $scope.postnumber=$routeParams.postnumber;
    jQuery.getJSON('/api/thread/'+$scope.board+'/'+$scope.postnumber, function(data) {
      $scope.data = data.data;
      $scope.$apply();
    });
  });
