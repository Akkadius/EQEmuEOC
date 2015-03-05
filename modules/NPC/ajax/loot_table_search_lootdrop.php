<?php
/**
 * Created by PhpStorm.
 * User: Akkadius
 * Date: 3/5/2015
 * Time: 3:19 AM
 */

    /* Loot Table :: Search for Loot Drop Tables */
    if($_GET['loottable_add']){
        $loot_table = $_GET['loottable_add'];
        $Content .= FormStart();
        $Content .= FormInput('Lootdrop Search', '<input type="text" class="form-control" id="loot_search" onkeyup="do_loot_search(this.value)">');
        $Content .= FormInput('',
            '<a href="javascript:;" class="btn green btn-xs" onclick="do_loot_search($(\'#loot_search\').val())">
                    <i class="fa fa-search"></i>
                    Search
                </a>
                ');
        $Content .= FormEnd();
        $Content .= '<div id="loot_search_result"></div>';
        echo Modal('Loot Table, Search for Lootdrop', $Content, '');
        echo '<script type="text/javascript" src="modules/NPC/ajax/loot_table_search_lootdrop.js"></script>';
    }

?>