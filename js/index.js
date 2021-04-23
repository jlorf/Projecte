// $(document).ready(function(){
//     $.Callbacks().add(callback);
// });

function callbackindex(){
  if (window.location.search === null || window.location.search === undefined || window.location.search === "" || window.location.search?.substring(1).split("reset=")[1] !== "true") {
      $("#reset").hide();
  } else {
      $("#login").hide();
      $("#reset").show();
  }
}

function login() {
  $("#login").show();
}
