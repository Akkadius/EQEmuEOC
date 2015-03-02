function CharacterTool(val) {
    u = "character_copy_options";
    $("#" + u).html(global_loader);
    $.ajax({
        url: "ajax.php?M=Character&character_copier",
        context: document.body
    }).done(function(e) {
        $("#" + u).html(e);
    });
}

function CharacterSearch(val) {
    u = "character_search_result";
    $("#" + u).html(global_loader);
    $.ajax({
        url: "ajax.php?M=Character&character_search=" + val,
        context: document.body
    }).done(function(e) {
        $("#" + u).html(e);
    });
}

function handle_source_character(val){
    $('#origchar').val(val);
}

function handle_destination_account(val){
    $('#dest_account').val(val);
}

function CopyCharacter(){
    u = "character_copy_result";
    if($('#new_character_name').val() == "") {
        $("#" + u).html("You must specify a new character name");
        return;
    }
    if($('#origchar').val() == "Origin Character"){
        $("#" + u).html("There needs to be an Origin Character specified");
        return;
    }
    if($('#dest_account').val() == "Destination Account"){
        $("#" + u).html("There needs to be a Destination Account specified");
        return;
    }

    var character_copy = $('#origchar').val();
    var destination_account = $('#dest_account').val();

    $("#" + u).html(global_loader);
    $.ajax({
        url: "ajax.php?M=Character&do_copy_character&source_character=" + character_copy + "&destination_account=" + destination_account + "&new_character_name=" + encodeURIComponent($('#new_character_name').val()),
        context: document.body
    }).done(function(e) {
        $("#" + u).html(e);
    });

}