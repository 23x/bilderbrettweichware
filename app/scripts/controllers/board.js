'use strict';

/**
 * @ngdoc function
 * @name bilderbrettApp.controller:BoardCtrl
 * @description
 * # BoardCtrl
 * Controller of the bilderbrettApp
 */
angular.module('bilderbrettApp')
  .controller('BoardCtrl', function ($scope, $routeParams) {
    $scope.board=$routeParams.board;
    jQuery.getJSON('/api/board/thread/list/'+$scope.board, function(data) {
      $scope.data = data.data;
      $scope.$apply();
    });
  });
