/**
 * Created by Akkadius on 2/22/2015.
 */

function HighlightField(fieldname) {
    if(document.getElementById(fieldname).checked){
        document.getElementById(fieldname).checked = false;
        document.getElementById(fieldname).style.filter="alpha(opacity=50);";
        document.getElementById(fieldname).style.opacity="0.5";
    }
    else{
        document.getElementById(fieldname).checked = true;
        document.getElementById(fieldname).style.filter="alpha(opacity=99);";
        document.getElementById(fieldname).style.opacity="0.99";
    }
    var total = parseInt(0);
    for (var i = 0; i < document.getElementById('myform').elements.length; i++) {
        if(document.getElementById('myform').elements[i].checked){
            if(document.getElementById('myform').elements[i].value > 0){
                total +=  parseInt(document.getElementById('myform').elements[i].value);
            }
        }
    }
    if(total >= 0){
        opener.document.getElementById("races").value = total;
        document.getElementById("result").innerHTML="Races set to <FONT COLOR='GREEN'><b>" + total + "</b></FONT>";
    }
    else{
        opener.document.getElementById("races").value = 0;
    }
}

function SetFieldCheckTrue(field_name){
    document.getElementById(field_name).checked = true;
}

function SetFieldCheckFalse(fieldname){
    document.getElementById(fieldname).checked = false;
}

function checkedAll () {
    for(var i = 1; i <= 16; i++){
        HighlightField("race" + i);
        HighlightField(i);
    }
}

function CheckAll(Type) {
    if (Type == "All") {
        if (document.getElementById("All").checked) {
            for (var i = 0; i <= 16; i++) {
                CheckClass(i, "true");
            }
        }
        else {
            for (var i = 0; i <= 16; i++) {
                CheckClass(i, "false");
            }
        }
    }
    var total = parseInt(0);
    for (var i = 0; i < document.getElementById('myform').elements.length; i++) {
        if (document.getElementById('myform').elements[i].checked) {
            if (document.getElementById('myform').elements[i].value > 0) {
                total += parseInt(document.getElementById('myform').elements[i].value);
            }
        }
    }
    if (total >= 0) {
        opener.document.getElementById("races").value = total;
        document.getElementById("result").innerHTML = "Races set to <FONT COLOR='GREEN'><b>" + total + "</b></FONT>";
    }
    else {
        opener.document.getElementById("races").value = 0;
    }
}

function CheckClass(Class, Boolean){
    if(Class){
        if(Boolean == "true"){
            document.getElementById(Class).checked = true;
            document.getElementById("race" + Class).checked = true;
            document.getElementById("race" + Class).style.filter="alpha(opacity=99);";
            document.getElementById("race" + Class).style.opacity="0.99";
        }
        else{
            document.getElementById(Class).checked = false;
            document.getElementById("race" + Class).checked = false;
            document.getElementById("race" + Class).style.filter="alpha(opacity=50);";
            document.getElementById("race" + Class).style.opacity="0.5";
        }
    }
}