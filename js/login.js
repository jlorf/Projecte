function validateForm() {
    var x = document.forms["myForm"]["email"].value;
    if (x == "") {
        alert("No s'ha omplert el mail.");
        return false;
    }
    return true;
}
function EnviarForm(){
    if (validateForm()){
        var datastring = $("#myForm").serialize();
        $.ajax({
            type: "POST",
            url: "JWT/login.php",
            data: datastring,
            dataType: "json",
            success: function(data) {
                globalThis.localStorage.setItem("JWT", data.jwt);
                $("#login").hide();
            },
            error: function(e) {
                if (e.status === 401){
                    alert('Usuari o contrasenya incorrectes.');
                } else if (e.status === 200){
                    debugger;
                    var data = JSON.parse(e.responseText);
                    var jwt = data.jwt;
                }
            }
        });
    }
}
function localpost(){
    EnviarForm();
    return false;
}