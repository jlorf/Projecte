function includeHTML() {
  var z, i, elmnt, file, xhttp;
  /* Loop through a collection of all HTML elements: */
  z = document.getElementsByTagName("*");
  for (i = 0; i < z.length; i++) {
    elmnt = z[i];
    /*search for elements with a certain atrribute:*/
    file = elmnt.getAttribute("w3-include-html");
    if (file) {
      /* Make an HTTP request using the attribute value as the file name: */
      xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
          if (this.status == 200)
          {
            elmnt.innerHTML = this.responseText;
            if (elmnt.getAttribute("show") === "false")
            {
              $("#" + elmnt.getAttribute("id")).hide();
              let filename = window.location.pathname.substr(window.location.pathname.lastIndexOf("/") + 1).substr(0, window.location.pathname.substr(window.location.pathname.lastIndexOf("/") + 1).lastIndexOf("."));
              //Cridar callback + file, exemple 'callbackindex'
              window["callback" + filename]();
            }
          }
          if (this.status == 404) {elmnt.innerHTML = "Page not found.";}
          /* Remove the attribute, and call this function once more: */
          elmnt.removeAttribute("w3-include-html");
          includeHTML();
        }
      }
      xhttp.open("GET", file, true);
      xhttp.send();
      ComprovarLogin();
      /* Exit the function: */
      return;
    }
  }
}

$(document).ready(function(){
    includeHTML();
});
var jwt;
function ComprovarLogin(){
  jwt = globalThis.localStorage.getItem("JWT");
    if (!jwt || jwt === undefined || jwt === null){
        $('#tabprofessors').remove();
        $('#tabalumnes').remove();
    } else {
        $('#tablogin').remove();
        $("#login").hide();
    }
}
