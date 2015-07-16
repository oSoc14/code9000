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
  var postEvent = function(event) {
    return $.post('api/1/events', event);
  }
  return {
    get: get,
    postEvent: postEvent
  };
})();
