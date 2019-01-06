  <div class="table_container" style="width: 500px">
    <div class="table_header">
      <div style="float: right">
        <a href="index.php?editor=loot&id=<?=$global_loot['id']?>&action=52"><img src="images/edit2.gif" width="13" height="13" border="0" title="Edit Global Loot"></a>&nbsp;
        <a onClick="return confirm('Really Delete Global Loot <?=$global_loot['id']?>?');" href="index.php?editor=loot&id=<?=$global_loot['id']?>&action=54"><img src="images/remove3.gif" border="0" title="Delete Global Loot"></a>
      </div>
      Global Loot: <?=$global_loot['id']?> - "<?=$global_loot['description']?>"
    </div>
    <div class="table_content">
      <table width="100%" cellpadding="3" cellspacing="0" border="0">
        <tr>
          <td colspan="2"><strong>Enabled:</strong> <?echo $yesno[$global_loot['enabled']]?></td>
          <td colspan="2"><strong>Min Level:</strong>  <?=$global_loot['min_level']?></td>
          <td colspan="2"><strong>Max Level:</strong> <?=$global_loot['max_level']?></td>
        </tr>
        <tr>
          <td colspan="2"><strong>Loottable ID:</strong> <?=$global_loot['loottable_id']?></td>
          <td colspan="2"><strong>Rare:</strong> <?echo ($global_loot['rare'] != "") ? $global_loot['rare'] : "N/A";?></td>
          <td colspan="2"><strong>Raid:</strong> <?echo ($global_loot['raid'] != "") ? $global_loot['raid'] : "N/A";?></td>
        </tr>
        <tr>
          <td colspan="6"><strong>Races:</strong> <?echo ($global_loot['race'] != "") ? $race : "ALL";?></td>
        </tr>
        <tr>
          <td colspan="6"><strong>Classes: </strong> <?echo ($global_loot['class'] != "") ? $class : "ALL"?></td>
        </tr>
        <tr>
          <td colspan="6"><strong>Bodytypes:</strong> <?echo ($global_loot['bodytype'] != "") ? $bodytype : "ALL"?></td>
        </tr>
        <tr>
          <td colspan="6"><strong>Zones: </strong> <?echo ($global_loot['zone'] != "") ? $zone : "ALL"?></td>
        </tr>
      </table>
    </div>
  </div><br>
<?if ($id):?>
  <div class="table_container" style="width: 350px;">
    <div class="table_header">
      <div style="float: right">
        <a href="index.php?editor=loot&id=<?=$global_loot['id']?>&action=56"><img src="images/edit2.gif" width="13" height="13" border="0" title="Edit Loottable"></a>&nbsp;
        <a onClick="return confirm('Really Delete Loottable <?=$global_loot['id']?>? NOTE: This will NOT delete the associated lootdrops.');" href="index.php?editor=loot&id=<?=$global_loot['id']?>&action=58"><img src="images/remove3.gif" border="0" title="Delete Loottable"></a>
      </div>
      Loottable: <?=$id?> - "<?=$name?>"
    </div>
    <div class="table_content">
      Cash Loot:<br>
      <div style="padding: 5px 0px 0px 20px;">
        Min Cash: <?=$mincash?><br>
        Max Cash: <?=$maxcash?><br>
      </div>
      <div style="padding: 5px 0px 0px 0px;">
        Lootdrops associated with this loottable: <?=$lootdrop_count?> <a href="index.php?editor=loot&id=<?=$global_loot['id']?>&ltid=<?=$id?>&action=60"><img src="images/add.gif" border="0" title="Add a Lootdrop to this Loottable"></a>
      </div>
    </div>
  </div>
<?else:?>
  <div class="table_container" style="width: 200px;">
    <div class="table_content" style="text-align: center;">
      <a href="index.php?editor=loot&id=<?=$global_loot['id']?>&action=59">Create New Loottable</a>
    </div>
  </div>
<?endif;?>
<?
  if ($lootdrop_count != 0):
    foreach ($lootdrops as $lootdrop):
?>
      <br>
      <div class="table_container">
        <div class="table_header">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td>Lootdrop: <?=$lootdrop['id']?> - "<?=$lootdrop['name']?>"</td>
              <td>Mindrop: <?=$lootdrop['mindrop']?></td>
              <td>Droplimit: <?=$lootdrop['droplimit']?></td>
              <td>Multiplier: <?=$lootdrop['multiplier']?></td>
              <td>Probability: <?=$lootdrop['probability']?></td>
              <td align="right">
                <a href="index.php?editor=loot&id=<?=$global_loot['id']?>&ltid=<?=$id?>&ldid=<?=$lootdrop['id']?>&action=62"><img src="images/edit2.gif" border="0" title="Edit this Lootdrop"></a>
                <a href="index.php?editor=loot&id=<?=$global_loot['id']?>&ldid=<?=$lootdrop['id']?>&action=65"><img src="images/add.gif" border="0" title="Add an Item to this Lootdrop Table"></a>
                <a onClick="return confirm('Really delete Lootdrop <?=$lootdrop['id']?>?');" href="index.php?editor=loot&id=<?=$global_loot['id']?>&ltid=<?=$id?>&ldid=<?=$lootdrop['id']?>&action=64"><img src="images/remove2.gif" border="0" title="Permanently delete this Lootdrop"></a>
              </td>
            </tr>
          </table>
        </div>
        <table class="table_content2" width="100%">
<? if(isset($lootdrop['items']) && $lootdrop['items']): $x=0; ?>
          <tr>
            <th align="center" width="8%">Item ID</th>
            <th align="center" width="36%">Item Name</th>
            <th align="center" width="7%">Equipped?</th>
            <th align="center" width="7%">Charges</th>
            <th align="center" width="7%">MinLevel</th>
            <th align="center" width="7%">MaxLevel</th>
            <th align="center" width="8%">Multiplier</th>
            <th align="center" width="8%">Chance</th>
            <th width="13%"></th>
          </tr>
<?
  foreach ($lootdrop['items'] as $item): extract($item);
    $total = (($chance/100) * ($lootdrop['probability']/100)) * 100;
    $chance_total += $chance;
    if($lootdrop['probability'] == 0)
      $chance = 0;
    if($lootdrop['probability'] > 0 && $lootdrop['probability'] < 100)
      $chance = $total;
    if($lootdrop['probability'] >= 100)
      $chance = 100;
?>
          <tr bgcolor="#<? echo ($x % 2 == 0) ? "BBBBBB" : "AAAAAA";?>">
            <td align="center"><a href="index.php?editor=items&id=<?=$item_id?>&action=2"><?=$item_id?></td>
            <td align="center"><?echo (get_item_name($item_id) != "") ? get_item_name($item_id) : "<a title='Item not in database!'>UNKNOWN</a>";?> <span>[<a href="http://lucy.allakhazam.com/item.html?id=<?=$item_id?>" target="_blank">lucy</a>]</span></td>
            <td align="center" width="100"><?echo ($equip_item == 0) ? "No" : "Yes";?></td>
            <td align="center"><?=$item_charges?></td>
            <td align="center"><?=$minlevel?></td>
            <td align="center"><?=$maxlevel?></td>
            <td align="center"><?=$multiplier?></td>
            <td align="center"><?=$chance?></td>
            <td align="right">
              <a href="index.php?editor=loot&id=<?=$global_loot['id']?>&ldid=<?=$lootdrop['id']?>&itemid=<?=$item_id?>&action=67"><img src="images/edit2.gif" border="0" title="Edit Lootdrop Item"></a>
<? if($disabled_chance == 0 && $chance > 0): ?>
              <a href="index.php?editor=loot&id=<?=$global_loot['id']?>&ldid=<?=$lootdrop['id']?>&itemid=<?=$item_id?>&chance=<?=$chance?>&action=70"><img src="images/downgrade.gif" border="0" title="Disable Item"></a>
<? endif; ?>
<? if($disabled_chance > 0): ?>
              <a href="index.php?editor=loot&id=<?=$global_loot['id']?>&ldid=<?=$lootdrop['id']?>&itemid=<?=$item_id?>&dchance=<?=$disabled_chance?>&action=71"><img src="images/upgrade.gif" border="0" title="Enable Item"></a>
<? endif; ?>
              <a onClick="return confirm('Really remove item <?=$item_id?> from Lootdrop <?=$lootdrop['id']?>?');" href="index.php?editor=loot&id=<?=$global_loot['id']?>&ldid=<?=$lootdrop['id']?>&itemid=<?=$item_id?>&action=69"><img src="images/remove3.gif" border="0" title="Remove Item"></a>
            </td>
            <td>&nbsp;</td>
          </tr>
<?
      $x++;
    endforeach;
?>
          <tr bgcolor="#000000">
            <td colspan="10" align="right">
              <a title="Set chance for all items on this table to <?=$normalize_amount?>" href="index.php?editor=loot&id=<?=$global_loot['id']?>&ldid=<?=$lootdrop['id']?>&action=72" style="color:yellow;">Normalize Drops</a>
            </td>
          </tr>
<?
  endif;
?>
<? if(!isset($lootdrop['items'])):?>
          <tr>
            <td align="left" width="100" style="padding: 10px;">No items currently assigned to this lootdrop</td>
          </tr>
<? endif;?>
        </table>
      </div>
<? endforeach; ?>
    </div>
<? endif;?>
