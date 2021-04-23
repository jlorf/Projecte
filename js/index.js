// $(document).ready(function(){
//     $.Callbacks().add(callback);
// });
var jwt;
function callbackindex(){
  jwt = globalThis.localStorage.getItem("JWT");
    if (!jwt || jwt === undefined || jwt === null){
      $('#tabprofessors').remove();
      $('#tabalumnes').remove();
      if (window.location.search === null || window.location.search === undefined || window.location.search === "" || window.location.search?.substring(1).split("reset=")[1] !== "true") {
          $("#reset").hide();
      } else {
          $("#login").hide();
          $("#reset").show();
      }
  } else {
    $('#tablogin').remove();
    $("#login").hide();
  }
}

function login() {
  $("#login").show();
}
