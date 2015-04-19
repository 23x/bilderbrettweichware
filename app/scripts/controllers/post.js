'use strict';

/**
 * @ngdoc function
 * @name bilderbrettApp.controller:PostctlCtrl
 * @description
 * # PostctlCtrl
 * Controller of the bilderbrettApp
 */
angular.module('bilderbrettApp')
  .controller('PostCtrl', function ($scope, $http, $routeParams, FileUploader) {

    $scope.uploader = new FileUploader();

    $scope.formMaster = {
      threadnumber: 0,
      subject: "",
      comment: "",
      user: "",
      mail: "",
      password: ""
    };

    $scope.form = angular.copy($scope.formMaster);

    $scope.submitPost = function (){
      var endpoint = "";
      $scope.form.board = $routeParams.board;

      if($scope.isNewThread()){
        endpoint = "/api/thread/create";
      } else {
        endpoint = "/api/post/create";
        $scope.form.threadnumber = $routeParams.postnumber;
      }

      jQuery.post(endpoint, {data: JSON.stringify($scope.form)}).done(function(){
          $scope.form = angular.copy($scope.formMaster);
          $scope.$apply();
        }
      );
    }

    $scope.isNewThread = function () {
      return typeof $routeParams.postnumber === 'undefined' || !$routeParams.postnumber;
    }
  });
