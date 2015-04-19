'use strict';

/**
 * @ngdoc filter
 * @name bilderbrettApp.filter:postFilter
 * @function
 * @description
 * # postFilter
 * Filter in the bilderbrettApp.
 */
angular.module('bilderbrettApp')
  .filter('postFilter', function () {
    return function (input, property, value) {
      if(!input) {
        return input;
      }
      var filteredPosts=[];
      for(var i=0;i<input.length;i++){
        if(input[i][property] == value) {
          filteredPosts.push(input[i]);
        }
      }
      return filteredPosts;
    };
  });
