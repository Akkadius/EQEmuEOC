       <table class="edit_form">
         <tr>
           <td class="edit_form_header">
             Search Results
           </td>
         </tr>
         <tr>
           <td class="edit_form_content">
<?if ($results != ''):?>
             <ul>
<?foreach($results as $result): extract($result);?>
             <li><a href="index.php?editor=spawn&z=<?=$currzone?>&zoneid=<?=$currzoneid?>&npcid=<?=$npcid?>&sid=<?=$sid?>&npc=<?=$id?>&action=8"><?=$name?> (<?=$id?>) (<?=get_zone_by_npcid($id)?>) - Level <?=$level?></a></li>
<?endforeach;?>
             </ul>
<?endif;?>
<?if ($results == ''):?>
            <center>
              Your search produced no results!<br><br>
              <a href="index.php?editor=spawn&z=<?=$currzone?>&zoneid=<?=$currzoneid?>&npcid=<?=$npcid?>&sid=<?=$sid?>&action=8">Try again</a>
            </center>
<?endif;?>
           </td>
         </tr>
       </table>
