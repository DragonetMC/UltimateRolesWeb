/**
 * call back should be: 
 * function(success, data)
 * when success, success=true, when fail, success=result.message
 */
function api(addr, callback) {
  $.ajaxSetup({cache:false})
  $.getJSON(addr, function(result){
    if (result.status == "success") {
      callback(true, result);
    } else {
      callback(result.message, result);
    }
  });
}

function value(url, jquery_filter) {
  $.ajaxSetup({cache:false})
  $(jquery_filter).html("loading... ");
  api(url, function(succ, data) {
  if(succ != true) {
    $(jquery_filter).html("failed loading :(");
  } else {
    $(jquery_filter).html(data.value);
    }
  });
}

function buttonToggle(jquery_filter, status, text) {
  $(jquery_filter).html(text);
  if(status == true) {
    $(jquery_filter).removeAttr("disabled");
  } else {
    $(jquery_filter).attr("disabled", "");
  }
}
