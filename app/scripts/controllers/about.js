'use strict';

/**
 * @ngdoc function
 * @name bilderbrettApp.controller:AboutCtrl
 * @description
 * # AboutCtrl
 * Controller of the bilderbrettApp
 */
angular.module('bilderbrettApp')
  .controller('AboutCtrl', function ($scope) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];
  });
