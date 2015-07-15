'use strict';

var api = (function() {

  var sources = [];
  var success;

  var get = function(sourceApi, callback) {
    $.get(sourceApi, {
      cache: false,
      dataType: 'json'
    }).success(function(data) {

      sources = data;
      success = true;
      callback(data);

    }).error(function(xhr, status, error) {

      success = false;
      console.error(status + ', ' + error);

    }).done();
  }
  return {
    get: get,
    sources: sources,
    success: success
  };
})();
