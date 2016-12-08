<?php
/**
 * Created by PhpStorm.
 * User: cmiles
 * Date: 12/5/2016
 * Time: 3:08 PM
 */

echo '<script type="text/javascript" src="modules/quests/js.js"></script>';

if(isset($_GET['translator'])){
    echo '<h4 class="page-title">Quest ID DB Translator</h4><hr>';
    echo '<li>This tool takes in quest scripts and translates ID\'s in quest commands and lists to give you readable comments. It uses the connected database as the source for this information</li>';

    echo '<hr>';
    echo form_start('', 1);
    echo form_input('Translate Type',
        '<select id="translate_options" onchange="do_translate()" class="form-control">
            <option value="auto">Auto</option>
            <option value="npc">NPC</option>
            <option value="faction">Factions</option>
            <option value="items">Items</option>
            <option value="items_lore">Items with Lore</option>
            <option value="spells">Spells</option>
            <option value="tasks">Tasks</option>
            <option value="tasks_with_activities">Tasks (With activities)</option>
        </select>');
    echo form_input('Lines',
        '<select id="translate_lines" onchange="do_translate()" class="form-control">
            <option value="same_line">Keep inline with code</option>
            <option value="comment_own_line">Output comments on own line</option>
        </select>');
    echo form_input ('', '
    <button type="button" class="btn btn-default" onclick="comma_split()"><i class="fa fa-ellipsis-h"></i> Split commas onto newlines</button>
    <button type="button" class="btn btn-default" onclick="trim_whitespace()"><i class="fa fa-ellipsis-h"></i> Trim Whitespace</button>
    ');
    echo form_input('Input Quest Script', '<textarea id="translate_input" onchange="do_translate()" class="form-control" style="height:200px"></textarea>');
    echo form_input('Translated Quest Script', '<textarea id="translate_output" class="input_area form-control" style="height:600px"></textarea>');
    echo form_end();
}

?>
