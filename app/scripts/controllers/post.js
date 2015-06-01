'use strict';

/**
 * @ngdoc function
 * @name bilderbrettApp.controller:PostctlCtrl
 * @description
 * # PostctlCtrl
 * Controller of the bilderbrettApp
 */
angular.module('bilderbrettApp')
  .controller('PostCtrl', function ($scope, $http, $routeParams, $upload) {

    $scope.formMaster = {
      threadnumber: 0,
      subject: "",
      comment: "",
      user: "",
      mail: "",
      password: ""
    };

    $scope.files = [];

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

      /*jQuery.post(endpoint, {data: JSON.stringify($scope.form)}).done(function(){
          $scope.form = angular.copy($scope.formMaster);
          $scope.$apply();
        }
      );*/

      $upload.upload({url: endpoint, data: $scope.form, file: $scope.files, fileFormDataName: "file[]"}
        ).success(function(){
          $scope.form = angular.copy($scope.formMaster);
          $scope.items=[];
      });
    };

    $scope.isNewThread = function () {
      return typeof $routeParams.postnumber === 'undefined' || !$routeParams.postnumber;
    };

    $scope.filesDropped = function($files, $event, $rejectedFiles) {
      for(var i=0;i<$files.length;i++) {
        $scope.files.push($files[i]);
      }
    };
    $scope.fileSelected = function($files, $event) {
      $scope.push($files[i]);
    }
  });
