'use strict';

/**
 * @ngdoc overview
 * @name bilderbrettApp
 * @description
 * # bilderbrettApp
 *
 * Main module of the application.
 */
angular
  .module('bilderbrettApp', [
    'ngRoute',
    'ngSanitize',
    'pascalprecht.translate',
    'angularFileUpload'
  ])
  .config(function ($routeProvider, $locationProvider, $translateProvider) {

    $locationProvider.html5Mode({
      enabled: true,
      requireBase: false
    });

    $translateProvider.useStaticFilesLoader({
      prefix: '/lang/',
      suffix: '.json'
    });
    $translateProvider.preferredLanguage('en');

    $routeProvider
      .when('/', {
        templateUrl: '/views/main.html',
        controller: 'MainCtrl'
      })
      .when('/about', {
        templateUrl: '/views/about.html',
        controller: 'AboutCtrl'
      })
      .when('/:board/', {
        templateUrl: '/views/board.html',
        controller: 'BoardCtrl'
      })
      .when('/:board/thread/:postnumber', {
        templateUrl: '/views/thread.html',
        controller: 'ThreadCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
