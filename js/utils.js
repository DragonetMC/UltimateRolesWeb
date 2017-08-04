/**
 * call back should be: 
 * function(success, data)
 * when success, success=true, when fail, success=result.message
 */
function api(addr, callback) {
  $.getJSON(addr, function(result){
    if (result.status == "success") {
      callback(true, result);
    } else {
      callback(result.message, result);
    }
  });
}

function value(url, jquery_filter) {
  $(jquery_filter).html("loading... ");
  api(url, function(succ, data) {
  if(succ != true) {
    $(jquery_filter).html("failed loading :(");
  } else {
    $(jquery_filter).html(data.value);
    }
  });
}
