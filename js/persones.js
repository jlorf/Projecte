var urlParams;
$(document).ready(function() {
    // let index = 1;
    // for(let property in navigator){
    //     let str = navigator[property];
    //     let tr = document.createElement("tr");
    //     let th = document.createElement("th");
    //     th.scope = "row";
    //     th.innerHTML = index;
    //     let td1 = document.createElement("td");
    //     td1.innerHTML = property;
    //     let td2 = document.createElement("td");
    //     //td2.innerHTML = JSON.stringify(str);
    //     let _string = typeof str === "string" ? str : JSON.stringify(str)
    //     td2.innerHTML = _string;
    //     tr.appendChild(th);
    //     tr.appendChild(td1);
    //     tr.appendChild(td2);
    //     document.getElementById("taula").appendChild(tr);
    //     index++;
    // }
    urlParams = new URLSearchParams(window.location.search);
    CarregarTaula();
});

function CheckUncheck(input)
{
  
}

function CarregarTaula(){
    var API = "Api/persona/json.php";
    $.getJSON( API, {
      professor: urlParams.has('alumnes') ? 0 : 1
    })
      .done(function( data ) {
        $.each( data.records, function( i, item ) {
          let str = item.Nom;
        let tr = document.createElement("tr");
        let th = document.createElement("th");
        th.scope = "row";
        th.innerHTML = i;
        let td1 = document.createElement("td");
        td1.innerHTML = item.Cognoms;
        let td2 = document.createElement("td");
        //td2.innerHTML = JSON.stringify(str);
        let _string = typeof str === "string" ? str : JSON.stringify(str)
        td2.innerHTML = _string;
        let td3 = document.createElement("td");
        var input = document.createElement("input");
        input.type = "checkbox";
        input.className= "custom-control-input";
        input.value = item.professor;
        td3.appendChild(input);
        tr.appendChild(th);
        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        document.getElementById("taula").appendChild(tr);
        });
      });
}