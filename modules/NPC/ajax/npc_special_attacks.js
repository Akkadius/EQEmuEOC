/**
 * Created by Akkadius on 2/15/2015.
 */

/* Iterate through special abilities fields and calculate the final string */
$( ".ability_check, .ability_check_sub" ).bind( "click onchange keyup", function() {
    console.log("CalcSpecialAbilities");
    CalcSpecialAbilities();
});

function CalcSpecialAbilities(){
    total_abilities = "";
    $( ".ability_check" ).each(function() {
        if(($(this).is(':checked') && $(this).is(':checkbox')) || ($(this).is('select') && $(this).val() > 0)){
            total_abilities = total_abilities + $(this).attr("ability") + ",1";
            parent_ability = $(this).attr("ability");
            // console.log('Parent ability is ' + parent_ability);
            $( ".ability_check_sub" ).each(function() {
                if($(this).attr("ability") == parent_ability){
                    if($(this).val() != "") {
                        // console.log('checking sub ability ' + $(this).attr("ability"));
                        total_abilities = total_abilities + "," + $(this).val();
                    }
                }
            });
            total_abilities = total_abilities + "^";
        }
        else{
            if($(this).val() > 0) {
                total_abilities = total_abilities + $(this).attr("ability") + "," + $(this).val() + "^";
            }
        }
        // console.log($(this).attr("ability")

    });
    total_abilities = total_abilities.substr(0, total_abilities.length - 1);
    $("#special_attacks_result").val(total_abilities);
}

$( document ).ready(function() {
    $("select").each(function() {
        $(this).tooltip();
    });
    $("input").each(function() {
        $(this).tooltip();
    });
});