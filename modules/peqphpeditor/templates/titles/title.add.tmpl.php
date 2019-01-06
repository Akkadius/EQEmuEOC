  <div style="margin: auto; width: 500px;">
    <center>
      <iframe id="searchframe" src="templates/iframes/playersearch.php" style="display:none;"></iframe>
      <input id="button" type="button" value="Hide Search" onclick="javascript:hideSearch();" style="display:none;">
    </center>
    <div class="edit_form">
      <div class="edit_form_header">
        <table width="100%">
          <tr>
            <td>
              Add a New Title
            </td>
          </tr>
        </table>
      </div>
      <div class="edit_form_content">
        <form name="title_create" method="post" action="index.php?editor=titles&action=3">
          <table width="100%" cellspacing="0" cellpadding="3">
            <tr>
              <td>
                <strong>ID:</strong><br>
                <input type="text" name="id" size="4" value="<?=$next_id?>">
              </td>
              <td>
                <strong>Status:</strong><br>
                <input type="text" name="status" size="4" value="-1">
              </td>
              <td>
                <strong>Item ID:</strong><br>
                <input type="text" name="item_id" size="4" value="-1">
              </td>
              <td>
                <strong>Title Set:</strong><br>
                <input type="text" name="title_set" size="4" value="0">
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">
                <strong>Class:</strong><br>
                <select name="class">
                  <option value="-1">-1: N/A</option>
<?foreach ($classes as $k=>$v):?>
                  <option value="<?=$k?>"><?=$k?>: <?=$classes[$k]?></option>
<?endforeach;?>
                </select>
              </td>
              <td>
                <strong>Gender:</strong><br>
                <select name="gender">
                  <option value="-1">-1: N/A</option>
<?foreach ($genders as $k=>$v):?>
                  <option value="<?=$k?>"><?=$k?>: <?=$genders[$k]?></option>
<?endforeach;?>
                </select>
              </td>
              <td>
                <b>Char ID:</b> <a href="javascript:showSearch();">Search</a><br>
                <input type="text" id="player" name="char_id" size="6" value="-1">
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4">
                <strong>Skill:</strong><br>
                <select name="skill_id">
<?foreach ($skilltypes as $k=>$v):?>
                  <option value="<?=$k?>"><?=$k?>: <?=$skilltypes[$k]?></option>
<?endforeach;?>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <strong>Min Skill:</strong><br>
                <input type="text" name="min_skill_value" size="4" value="-1">
              </td>
              <td>
                <strong>Max Skill:</strong><br>
                <input type="text" name="max_skill_value" size="4" value="-1">
              </td>
              <td>
                <strong>Min AA:</strong><br>
                <input type="text" name="min_aa_points" size="4" value="-1">
              </td>
              <td>
                <strong>Max AA:</strong><br>
                <input type="text" name="max_aa_points" size="4" value="-1">
              </td>
            </tr>
            <tr>
              <td colspan="5">
                <strong>Prefix:</strong><br>
                <input type="text" name="prefix" size="70" value="">
              </td>
            </tr>
            <tr>
              <td colspan="5">
                <strong>Suffix:</strong><br>
                <input type="text" name="suffix" size="70" value="">
              </td>
            </tr>
          </table><br><br>
          <center><input type="submit" value="Add Title">&nbsp;&nbsp;<input type="button" value="Cancel" onClick="history.back();"></center>
        </form>
      </div>
    </div>
  </div>
