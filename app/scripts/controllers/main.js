'use strict';

/**
 * @ngdoc function
 * @name bilderbrettApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the bilderbrettApp
 */
angular.module('bilderbrettApp')
  .controller('MainCtrl', function ($scope) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];
  });
