// $(document).ready(function(){
//     $.Callbacks().add(callback);
// });
var jwt;
function callbackindex(){
  jwt = globalThis.localStorage.getItem("JWT");
    if (!jwt || jwt === undefined || jwt === null){
      JWTBuit();
  } else {
    ComprovarToken();
    //$('#tablogin').remove();
    //$("#login").hide();
  }
}

function login() {
  $("#login").show();
}

function ComprovarToken(){
  $.post( "JWT/validate_token.php", '{ "jwt": "' + jwt + '"}')
  .done(function(data, responsetext, response) {
    if (response !== undefined && response !== null && response.status === 200){
      $('#tablogin').remove();
      $("#login").hide();
    } else {
      globalThis.localStorage.removeItem("JWT");
      JWTBuit();
    }
  });
}

function JWTBuit(){
  $('#tabprofessors').remove();
      $('#tabalumnes').remove();
      if (window.location.search === null || window.location.search === undefined || window.location.search === "" || window.location.search?.substring(1).split("reset=")[1] !== "true") {
          $("#reset").hide();
      } else {
          $("#login").hide();
          $("#reset").show();
      }
}
