  <div class="edit_form" style="width: 600px">
    <div class="edit_form_header">
      Edit Temp Merchant List for NPC: <?=$npcid?>
    </div>
    <div class="edit_form_content">
      <form name="merchantlist" method="post" action="index.php?editor=merchant&z=<?=$currzone?>&zoneid=<?=$currzoneid?>&npcid=<?=$npcid?>&action=10">
        <table width="100%">
          <tr>
            <th>Old<br/>Slot</th>
            <th>New<br/>Slot</th>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Charges</th>
          </tr>
<?$x=1; foreach ($slots as $slot => $v):?>
          <tr>
            <td align="center"><?=$slot?></td>
            <td align="center"><input type="text" size="3" name="newslot<?=$x?>" value="<?=$slot?>"/></td>
            <td align="center"><input type="text" size="7" name="itemid<?=$x?>" value="<?=$v['itemid']?>"/></td>
            <td><?=$v['item_name']?></td>
            <td align="center"><input type="text" size="3" name="charges<?=$x?>" value="<?=$v['charges']?>"/></td>
            <input type="hidden" name="slot<?=$x?>" value="<?=$slot?>">
          </tr>
<?$x++; endforeach?>
        </table><br/><br/>
        <center>
          <input type="hidden" name="count" value="<?=($x - 1)?>">
          <input type="submit" value="Submit Changes">
        </center>
      </form>
    </div>
  </div>
