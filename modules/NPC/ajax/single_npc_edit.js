/**
 * Created by Akkadius on 2/22/2015.
 */

$(".modal-body input, .modal-body select").each(function () {
    $(this).addClass("form-control");
});

$("select").each(function () {
    $(this).tooltip();
});

$(":text").each(function () {
    $(this).tooltip();
});

$(document).ready(function () {
    $("#section_Appearance").html(
        "<table style=\"padding:5px\">" +
            "<tr>" +
                "<td style=\"padding:5px\">Armor Tint</td><td style=\"padding:5px\"></td>" +
                "<td>" +
                "<div id=\"picker\" style=\"display:inline\"></div>" +
                "</td>" +
            "</tr>" +
        "</table>");
    $("#picker").colpick({
        layout: "hex",
        submit: 0,
        colorScheme: "dark",
        onChange: function (hsb, hex, rgb, el, bySetColor) {
            // console.log(rgb);
            $(el).css("border-color", "#" + hex);
            $("#color_preview").css("background-color", "#" + hex);
            // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
            if (!bySetColor) $(el).val("#" + hex);
            $(".armortint_red").val(rgb.r);
            $(".armortint_green").val(rgb.g);
            $(".armortint_blue").val(rgb.b);
        },
        onHide: function (e) {
            // console.log("hidden");
            update_npc_field($("#npc_id").val(), "armortint_red", $(".armortint_red").val());
            update_npc_field($("#npc_id").val(), "armortint_green", $(".armortint_green").val());
            update_npc_field($("#npc_id").val(), "armortint_blue", $(".armortint_blue").val());
        }
    }).keyup(function () {
        $(this).colpickSetColor(this.value);
    });

    $("select").each(function() {
        $(this).tooltip();
    });
    $(":text, select, input").each(function() {
        $(this).css("height", "30px");
        $(this).tooltip();
    });
    $(".page-content img").each(function() {
        $(this).css("border", "2px solid #666");
    });
});