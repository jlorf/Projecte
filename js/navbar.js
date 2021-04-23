var jwt;
$(document).ready(function(){
    jwt = globalThis.localStorage.getItem("JWT");
    if (!jwt || jwt === undefined || jwt === null){
        $('#tabprofessors').remove();
        $('#tabalumnes').remove();
    } else {
        $('#tablogin').remove();
        $("#login").hide();
    }
});