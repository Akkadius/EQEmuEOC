<?php 

/** Displays the results of a query for objects returning the 'id' and 'name' fields.
 *  The query must have been done with at least a limit of '$MaxRowsReturned + 1'.
 *  '$MaxObjectsReturned' can be '0', which means the query had no limits (ex: $MaxNpcsReturned).
 *  '$OpenObjectById' must contain the name of the page used to open one of the object (ex: npc.php) by passing it
 *    the ID by GET method.
 *  'IdAttribute' and 'NameAttribute' are the name of the columns retrieved used for ID and Name (ex: 'id' and 'name').
 *  '$ObjectDescription' is the text describing the kind of objects to display (ex: 'NPC'). '$ObjectsDescription' is the plural.
 */
function PrintQueryResults($FoundObjects, $MaxObjectsReturned, $OpenObjectByIdPage, $ObjectDescription, $ObjectsDescription, $IdAttribute, $NameAttribute, $ExtraField, $ExtraFieldDescription, $ExtraSkill){
	global $dbskills;
	$ObjectsToShow = mysql_num_rows($FoundObjects);
	if($ObjectsToShow > LimitToUse($MaxObjectsReturned)){
		$ObjectsToShow = LimitToUse($MaxObjectsReturned);
		$MoreObjectsExist = True;
	}
	else
	{
		$MoreObjectsExist = False;
	} 

	if($ObjectsToShow == 0)
	{
		echo  "<ul><li><b>No ".$ObjectDescription." found.</b></li></ul>\n";
	}
	else
	{
		echo  "<ul><li><b>".$ObjectsToShow." ".($ObjectsToShow == 1 ? $ObjectDescription : $ObjectsDescription)." displayed.";
		if($MoreObjectsExist){
			echo  " More ".$ObjectsDescription." exist but you have reached the search limit.";
		}
		echo  "</b></li>\n";
		echo  "<ul>\n";
		for( $j = 1 ; $j <= $ObjectsToShow ; $j ++ ){
			$row = mysql_fetch_array($FoundObjects);
			$PrintString = " <li><a href='".$OpenObjectByIdPage."?id=".$row[$IdAttribute]."'>";
			if ($ObjectDescription == "npc"){
				// Clean up the name for NPCs
				$PrintString .= ReadableNpcName($row[$NameAttribute]);
			}
			else{
				$PrintString .= $row[$NameAttribute];
			}
			$PrintString .= " (".$row[$IdAttribute].")</a>";

			if ($ExtraField && $ExtraFieldDescription && $ExtraSkill){
				$PrintString .= " - ".ucfirstwords(str_replace("_"," ",$dbskills[$row[$ExtraSkill]])).", $ExtraFieldDescription ".$row[$ExtraField];
			}
			echo  $PrintString;
			echo  "</li>\n";
		}
		echo  "</ul>\n</ul>\n";
	}
}

/** Returns the actual limit to use for queries for the specified limit '$MaxObjects'
 *  Essentially transforms the '0' in a very large integer.
 *  Could be use to put an extra (hard-coded) upper limit to queries.
 */
function LimitToUse($MaxObjects)
{
	if($MaxObjects == 0)
		$Result = 2147483647;
	else
		$Result = $MaxObjects;
	return $Result;
}

/** Returns the "readable" name of an NPC from its database-encoded '$DbName'.
 */
function ReadableNpcName($DbName)
{
	$Result = str_replace('-', '`', str_replace('_', ' ', str_replace('#', '', str_replace('!', '', str_replace('~', '', $DbName)))));
	for ($i = 0; $i < 10; $i++)
	{
		$Result = str_replace($i, '', $Result);
	}
	return $Result;
}

/** Returns the type of NPC based on the name of an NPC from its database-encoded '$DbName'.
 */
function NpcTypeFromName($DbName)
{
	global $NPCTypeArray;
	foreach ($NPCTypeArray as $key => $type)
	{
		$KeyCount = substr_count($DbName, $key);
		$StringLength = strlen($DbName);
		$KeyLength = strlen($key);
		if($KeyCount > 0 && substr($DbName, 0, $KeyLength) == $key)
		{
			return $type;
		}
	}
	return "Normal";
}

// Converts the first letter of each word in $str to upper case and the rest to lower case.
function ucfirstwords($str)
{
	return ucwords(strtolower($str));
}

/** Returns the URL in the Wiki to the image illustrating the NPC with ID '$NpcId'
 *  Returns an empty string if the image does not exist in the Wiki
 */
function NpcImage($WikiServerUrl, $WikiRootName, $NpcId)
{ $SystemCall = "wget -q \"".$WikiServerUrl.$WikiRootName."/index.php/Image:Npc-".$NpcId.".jpg\" -O -| grep \"/".$WikiRootName."/images\" | head -1 | sed 's;.*\\(/".$WikiRootName."/images/[^\"]*\\).*;\\1;'";
  $Result = `$SystemCall`;
  if($Result != "")
    $Result = $WikiServerUrl.$Result;

  return $Result;
}


/** Returns a uniform value 'Yes'/'No' for many ways of modelling a predicate.
 */
function YesNo($val)
{ switch (strtolower($val))
  { case TRUE:
    case 1:
    case "yes":
      $Result = "Yes";
      break;

    case FALSE:
    case 0:
    case "no":
      $Result = "No";
      break;
  }
  return $Result;
}

/** Returns a human-readable translation of '$sec' seconds (for respawn times)
 *  If '$sec' is '0', returns 'time' (prints 'Spawns all the time' as a result)
 */
function translate_time($sec)
{ if($sec == 0)
    $Result = "time";
  else
  { $h = floor($sec / 3600);
    $m = floor(($sec - $h * 3600) / 60);
    $s = $sec - $h * 3600 - $m * 60;
    $Result = ( $h > 1 ? "$h hours " : "" ).( $h == 1 ? "1 hour " : "").( $m > 0 ? "$m min " : "").( $s > 0 ? "$s sec" : "" );
  }
  return $Result;
}

function make_thumb($FileSrc) {
  // If PHP is installed with GD and jpeg support, uncomment the following line
  //execute_make_thumb($FileSrc);
}

// This function (execute_make_thumb) requires to install PHP with GD & jpeg-6 support
// GD -> http://www.boutell.com/gd/
// JPEG -> ftp://ftp.uu.net/graphics/jpeg/
// 
function execute_make_thumb($FileSrc){
  $tnH=100;
  $size=getimagesize($FileSrc); 
  $src=imagecreatefromjpeg($FileSrc); 
  $destW=$size[0]*$tnH/$size[1];
  $destH=$tnH;
  $dest=imagecreate($destW,$destH); // creation de l'image de destination
  imagecopyresized($dest,$src,0,0,0,0,$destW,$destH,$size[0],$size[1]);
  $tn_name=$FileSrc;
  $tn_name = preg_replace("/\.(gif|jpe|jpg|jpeg|png|wbmp)$/i","_tn",$tn_name);
  imagejpeg($dest, $tn_name.".jpg");
}

/** Returns the rest of the euclidian division of '$d' by '$v'
 *  Returns '0' if '$v' equals '0'
 *  Supposes '$d' and '$v' are positive
 */
function modulo($d,$v)
{ if($v == 0)
    $Result = 0;
  else
  { $s=floor($d/$v);
    $Result = $d - $v * $s;
  }
}

/** Returns the list of slot names '$val' corresponds to (as a bit field)
 */
function getslots($val)
{ global $dbslots;
  reset($dbslots);
  do
  { $key=key($dbslots);
    if($key <= $val)
    { $val -= $key;
      $Result .= $v.current($dbslots);
      $v=", ";
    }
  } while (next($dbslots));
  return $Result;
}

function getclasses($val) {
	global $dbiclasses;
	reset($dbiclasses);
	do 
	{
		$key = key($dbiclasses);
		if ($key <= $val)
		{
			$val -= $key;
			$res .= "<b class='btn btn-default btn-xs' style='display:inline'>" . $v.current($dbiclasses) . "</b> ";
		}
	}
	while
	(
		next($dbiclasses)
	);
	return $res;
}

function getraces($val) {
  global $dbraces;
  reset($dbraces);
  do {
    $key=key($dbraces);
    if ($key<=$val) { $val-=$key; $res.= "<b class='btn btn-default btn-xs ' style='display:inline'>" . $v.current($dbraces) . "</b> "; }
  } while (next($dbraces));
  return $res;
}

function getsize($val) {
  switch($val) {
    case 0: return "Tiny"; break;
    case 1: return "Small"; break;
    case 2: return "Medium"; break;
    case 3: return "Large"; break; 
    case 4: return "Giant"; break;
    default: return "$val?"; break;
  }
}

function getspell($id) {
	global $tbspells,$tbspellglobals,$UseSpellGlobals;
	if ($UseSpellGlobals==TRUE)
	{
		$query="SELECT spells_new.* FROM spells_new WHERE spells_new.id=".$id." 
			AND (ISNULL((SELECT " . $tbspellglobals . ".spellid FROM " . $tbspellglobals . " 
				WHERE " . $tbspellglobals . ".spellid = spells_new.id)) 
				OR (SELECT quest_globals.name FROM " . $tbspellglobals . " , quest_globals
				WHERE " . $tbspellglobals . ".spellid = spells_new.id 
				AND " . $tbspellglobals . ".qglobal = quest_globals.name 
				AND " . $tbspellglobals . ".value = quest_globals.value 
				LIMIT 1))";
	}
	else
	{
		$query="SELECT * FROM spells_new WHERE id=$id"; 
	}
	$result=mysql_query($query) or message_die('functions.php','getspell',$query,mysql_error());
	$s=mysql_fetch_array($result);
	return $s;
}

function gedeities($val) {
  global $dbideities;
  reset($dbideities);
  do {
    $key=key($dbideities);
    if ($key<=$val) { $val-=$key; $res.=$v.current($dbideities); $v=", "; }
  } while (next($dbideities));
  return $res;
}

function SelectClass($name,$selected) {
  global $dbclasses;
  print "<SELECT name=\"$name\">";
  print "<option value='0'>-</option>\n";
  for ($i=1; $i<=16; $i++) {
    print "<option value='".$i."'";
    if ($i==$selected) { print " selected='1'"; }
    print ">".$dbclasses[$i]."</option>\n";
  } 
  print "</SELECT>";
}

function SelectDeity($name,$selected) {
  global $dbideities;
  print "<SELECT name=\"$name\">";
  print "<option value='0'>-</option>\n";
  for ($i=2; $i<=65536; $i*=2) {
    print "<option value='".$i."'";
    if ($i==$selected) { print " selected='1'"; }
    print ">".$dbideities[$i]."</option>\n";
  } 
  print "</SELECT>";
}

function SelectRace($name,$selected) {
  global $dbraces;
  print "<SELECT name=\"$name\">";
  print "<option value='0'>-</option>\n";
  for ($i=1; $i<32768; $i*=2) {
    print "<option value='".$i."'";
    if ($i==$selected) { print " selected='1'"; }
    print ">".$dbraces[$i]."</option>\n";
  } 
  print "</SELECT>";
}

function SelectMobRace($name,$selected) {
	global $dbiracenames;
	print "<SELECT name=\"$name\">";
	print "<option value='0'>-</option>\n";
	foreach ($dbiracenames as $key => $value)
	{
		print "<option value='".$key."'";
		if ($key==$selected) { print " selected='1'"; }
		print ">".$value."</option>\n";
	}
	print "</SELECT>";
}

function SelectIClass($name,$selected) {
	global $dbiclasses;
	print "<SELECT name=\"$name\">";
	print "<option value='0'>-</option>\n";
	for ($i=1; $i<=32768; $i*=2)
	{
		print "<option value='".$i."'";
		if ($i==$selected) { print " selected='1'"; }
		print ">".$dbiclasses[$i]."</option>\n";
	}   
	print "</SELECT>";
}

function SelectIType($name,$selected) {
  global $dbitypes;
  print "<SELECT name=\"$name\">";
  print "<option value='-1'>-</option>\n";
  reset($dbitypes);
  do {
    $key=key($dbitypes);
    print "<option value='".$key."'";
    if ($key==$selected) { print " selected='1'"; }
    print ">".current($dbitypes)."</option>\n";
  } while (next($dbitypes));  
  print "</SELECT>";
}

function SelectSlot($name,$selected) {
  global $dbslots;
  print "<SELECT name=\"$name\">";
  print "<option value='0'>-</option>\n";
  reset($dbslots);
  do {
    $key=key($dbslots);
    print "<option value='".$key."'";
    if ($key==$selected) { print " selected='1'"; }
    print ">".current($dbslots)."</option>\n";
  } while (next($dbslots));  
  print "</SELECT>";
}

function SelectSpellEffect($name,$selected) {
	global $dbspelleffects;
	print "<SELECT name=\"$name\">";
	print "<option value=-1>-</option>\n";
	reset($dbspelleffects);
	do
	{
		$key=key($dbspelleffects);
		print "<option value='".$key."'";
		if ($key==$selected)
		{
			print " selected='1'";
		}
		print ">".current($dbspelleffects)."</option>\n";
	}
	while (next($dbspelleffects));  
	print "</SELECT>";
}

function SelectAugSlot($name,$selected) {
  print "<SELECT name=\"$name\">";
  print "<option value='0'>-</option>\n";
  for ($i=1; $i<=25; $i++) {
    print "<option value='".$i."'";
    if ($i==$selected) { print " selected='1'"; }
    print ">slot $i</option>\n";
  }
  print "</SELECT>";
}

function SelectLevel($name,$maxlevel,$selevel) {
	print "<SELECT name=\"$name\">";
	print "<option value='0'>-</option>\n";
	for ($i=1; $i<=$maxlevel; $i++)
	{
		print "<option value='".$i."'";
		if ($i==$selevel) { print " selected='1'"; }
		print ">$i</option>\n";
	} 
	print "</SELECT>";
}

function SelectTradeSkills($name,$selected) {
  print "<SELECT name=\"$name\">";
  WriteIt("0","-",$selected);
  WriteIt("59","Alchemy",$selected);
  WriteIt("60","Baking",$selected);
  WriteIt("63","Blacksmithing",$selected);
  WriteIt("65","Brewing",$selected);
  WriteIt("55","Fishing",$selected);
  WriteIt("64","Fletching",$selected);
  WriteIt("68","Jewelery making",$selected);
  WriteIt("56","Poison making",$selected);
  WriteIt("69","Pottery making",$selected);
  WriteIt("58","Research",$selected);
  WriteIt("61","Tailoring",$selected);
  WriteIt("57","Tinkering",$selected);
  print "</SELECT>";
}

function WriteIt($value,$name,$sel) {
  print "  <option value='".$value."'";
  if ($value==$sel) { print " selected='1'"; }
  print ">$name</option>\n";
}
function SelectStats($name,$stat) {
  print "<select name=\"$name\">\n";
  print "  <option value=''>-</option>\n";
  WriteIt("hp","Hit Points",$stat);
  WriteIt("mana","Mana",$stat);
  WriteIt("ac","AC",$stat);
  WriteIt("attack","Attack",$stat);
  WriteIt("aagi","Agility",$stat);
  WriteIt("acha","Charisma",$stat);
  WriteIt("adex","Dexterity",$stat);
  WriteIt("aint","Intelligence",$stat);
  WriteIt("asta","Stamina",$stat);
  WriteIt("astr","Strength",$stat);
  WriteIt("awis","Wisdom",$stat);
  WriteIt("damage","Damage",$stat);
  WriteIt("delay","Delay",$stat);
  WriteIt("ratio","Ratio",$stat);
  WriteIt("haste","Haste",$stat);
  WriteIt("regen","HP Regen",$stat);
  WriteIt("manaregen","Mana Regen",$stat);
  WriteIt("enduranceregen","Endurance Regen",$stat);
  print "</select>\n";
}

function RWriteIt($value,$name,$sel) {
  $var .= "  <option value='".$value."'";
  if ($value==$sel) { $var .= " selected='1'"; }
  $var .= ">$name</option>\n";
  return $var;
}
function RSelectStats($name,$stat) {
  $value .= "<select name=\"$name\">";
  $value .= "  <option value=''>-</option>";
  $value .= RWriteIt("hp","Hit Points",$stat);
  $value .= RWriteIt("mana","Mana",$stat);
  $value .= RWriteIt("ac","AC",$stat);
  $value .= RWriteIt("attack","Attack",$stat);
  $value .= RWriteIt("aagi","Agility",$stat);
  $value .= RWriteIt("acha","Charisma",$stat);
  $value .= RWriteIt("adex","Dexterity",$stat);
  $value .= RWriteIt("aint","Intelligence",$stat);
  $value .= RWriteIt("asta","Stamina",$stat);
  $value .= RWriteIt("astr","Strength",$stat);
  $value .= RWriteIt("awis","Wisdom",$stat);
  $value .= RWriteIt("damage","Damage",$stat);
  $value .= RWriteIt("delay","Delay",$stat);
  $value .= RWriteIt("ratio","Ratio",$stat);
  $value .= RWriteIt("haste","Haste",$stat);
  $value .= RWriteIt("regen","HP Regen",$stat);
  $value .= RWriteIt("manaregen","Mana Regen",$stat);
  $value .= RWriteIt("enduranceregen","Endurance Regen",$stat);
  $value .= "</select>\n";
  return $value;
}

function SelectHeroicStats($name,$heroic) {
  print "<select name=\"$name\">\n";
  print "  <option value=''>-</option>\n";
  WriteIt("heroic_agi","Heroic Agility",$stat);
  WriteIt("heroic_cha","Heroic Charisma",$stat);
  WriteIt("heroic_dex","Heroic Dexterity",$stat);
  WriteIt("heroic_int","Heroic Intelligence",$stat);
  WriteIt("heroic_sta","Heroic Stamina",$stat);
  WriteIt("heroic_str","Heroic Strength",$stat);
  WriteIt("heroic_wis","Heroic Wisdom",$stat);
  WriteIt("heroic_mr","Heroic Resist Magic",$heroic);
  WriteIt("heroic_fr","Heroic Resist Fire",$heroic);
  WriteIt("heroic_cr","Heroic Resist Cold",$heroic);
  WriteIt("heroic_pr","Heroic Resist Poison",$heroic);
  WriteIt("heroic_dr","Heroic Resist Disease",$heroic);
  WriteIt("heroic_svcorrup","Heroic Resist Corruption",$heroic);
  print "</select>\n";
}

function SelectResists($name,$resist) {
  print "<select name=\"$name\">\n";
  print "  <option value=''>-</option>\n";
  WriteIt("mr","Resist Magic",$resist);
  WriteIt("fr","Resist Fire",$resist);
  WriteIt("cr","Resist Cold",$resist);
  WriteIt("pr","Resist Poison",$resist);
  WriteIt("dr","Resist Disease",$resist);
  WriteIt("svcorruption","Resist Corruption",$resist);
  print "</select>\n";
}

function SelectModifiers($name,$mod) {
  print "<select name=\"$name\">\n";
  print "  <option value=''>-</option>\n";
  WriteIt("avoidance","Avoidance",$mod);
  WriteIt("accuracy","Accuracy",$mod);
  WriteIt("backstabdmg","Backstab Damage",$mod);
  WriteIt("clairvoyance","Clairvoyance",$mod);
  WriteIt("combateffects","Combat Effects",$mod);
  WriteIt("damageshield","Damage Shield",$mod);
  WriteIt("dsmitigation","Damage Shield Mit",$mod);
  WriteIt("dotshielding","DoT Shielding",$mod);
  WriteIt("extradmgamt","Extra Damage",$mod);
  WriteIt("healamt","Heal Amount",$mod);
  WriteIt("purity","Purity",$mod);
  WriteIt("shielding","Shielding",$mod);
  WriteIt("spelldmg","Spell Damage",$mod);
  WriteIt("spellshield","Spell Shielding",$mod);
  WriteIt("strikethrough","Strikethrough",$mod);
  WriteIt("stunresist","Stun Resist",$mod);
  print "</select>\n";
}

// spell_effects.cpp int Mob::CalcSpellEffectValue_formula(int formula, int base, int max, int caster_level, int16 spell_id)
function CalcSpellEffectValue($form,$base,$max,$lvl) { 
 // print " (base=$base form=$form max=$max, lvl=$lvl)";
  $sign=1; $ubase=abs($base); $result=0;
  if (($max<$base) AND ($max!=0)) { $sign=-1; }
  switch($form) {
		case 0:
		case 100:
			$result=$ubase; break;
		case 101:
			$result=$ubase+$sign*($lvl/2); break;
		case 102:	
			$result=$ubase+$sign*$lvl; break;
		case 103:	
			$result=$ubase+$sign*$lvl*2; break;
		case 104:	
			$result=$ubase+$sign*$lvl*3; break;
		case 105:	
		case 107:
			$result=$ubase+$sign*$lvl*4; break;
		case 108:	
			$result=floor($ubase+$sign*$lvl/3); break;
		case 109:	
			$result=floor($ubase+$sign*$lvl/4); break;
		case 110:	
			$result=floor($ubase+$lvl/5); break;
		case 111:
			$result=$ubase+5*($lvl-16); break;
		case 112:
			$result=$ubase+8*($lvl-24); break;
		case 113:
			$result=$ubase+12*($lvl-34); break;
		case 114:
			$result=$ubase+15*($lvl-44); break;
		case 115:
			$result=$ubase+15*($lvl-54); break;
	  case 116:
	    $result=floor($ubase+8*($lvl-24)); break;
	  case 117:
	    $result=$ubase+11*($lvl-34); break;
	  case 118:
	    $result=$ubase+17*($lvl-44); break;
	  case 119:
			$result=floor($ubase+$lvl/8); break;
	  case 121:
			$result=floor($ubase+$lvl/3); break;
	  
		default:
			if ($form<100) { $result=$ubase+($lvl*$form); }
  } // end switch
  if ($max!=0) {
	  if ($sign==1) {
    	if ($result>$max) { $result=$max; }
  	} else {
  		if ($result<$max) { $result=$max; }
    }
	}
	if (($base<0) && ($result>0)) { $result*=-1; }
  return $result;
}

function CalcBuffDuration($lvl,$form,$duration) { // spells.cpp, carefull, return value in ticks, not in seconds
  //print " Duration lvl=$lvl, form=$form, duration=$duration ";
	switch($form) {
		case 0:	
		  return 0; 
		  break;
		case 1:	
		  $i=ceil($lvl/2); 
			return ($i<$duration?($i<1?1:$i):$duration);
      break;
		case 2:	
			$i=ceil($duration/5*3);
			return ($i<$duration?($i<1?1:$i):$duration);
			break;
    case 3:	
			$i=$lvl*30;
			return ($i<$duration?($i<1?1:$i):$duration);
      break;
		case 4:	
			return $duration;
      break;
		case 5:	
			$i=$duration;
			return ($i<3?($i<1?1:$i):3);
      break;
		case 6:	
			$i=ceil($lvl/2);
			return ($i<$duration?($i<1?1:$i):$duration);
      break;
		case 7:	
			$i=$lvl;
			return ($i<$duration?($i<1?1:$i):$duration);
      break;
		case 8:	
			$i=$lvl+10;
			return ($i<$duration?($i<1?1:$i):$duration);
      break;
		case 9:	
			$i=$lvl*2+10;
			return ($i<$duration?($i<1?1:$i):$duration);
      break;
		case 10:
			$i=$lvl*3+10;
			return ($i<$duration?($i<1?1:$i):$duration);
      break;
		case 11:
		case 12:	
			return $duration;
      break;
		case 50:	
			return 72000;	
		case 3600:
			return ($duration?$duration:3600);
	}
}

function SpecialAttacks($att) {
  $data=''; $v='';
  // from mobs.h
  for ($i=0; $i<strlen($att); $i++) {  
    switch ($att{$i}) {
      case 'A' : $data.=$v." Immune to melee"; $v=', '; break;
      case 'B' : $data.=$v." Immune to magic"; $v=', '; break;
      case 'C' : $data.=$v." Uncharmable"; $v=', '; break;
      case 'D' : $data.=$v." Unfearable"; $v=', '; break;
      case 'E' : $data.=$v." Enrage"; $v=', '; break;
      case 'F' : $data.=$v." Flurry"; $v=', '; break;
      case 'f' : $data.=$v." Immune to fleeing"; $v=', '; break;
      case 'I' : $data.=$v." Unsnarable"; $v=', '; break;
      case 'M' : $data.=$v." Unmezzable"; $v=', '; break;
      case 'N' : $data.=$v." Unstunable"; $v=', '; break;
      case 'O' : $data.=$v." Immune to melee except bane"; $v=', '; break;
      case 'Q' : $data.=$v." Quadruple Attack"; $v=', '; break;
      case 'R' : $data.=$v." Rampage"; $v=', '; break;
      case 'S' : $data.=$v." Summon"; $v=', '; break;
      case 'T' : $data.=$v." Triple Attack"; $v=', '; break;
      case 'U' : $data.=$v." Unslowable"; $v=', '; break;
      case 'W' : $data.=$v." Immune to melee except magical"; $v=', '; break;
    }
  }
  return $data; 
}

function price($price) {
  $res="";
  if ($price>=1000) { 
    $p=floor($price/1000);
    $price-=$p*1000;
  }
  if ($price>=100) {
    $g=floor($price/100);
    $price-=$g*100;
  }
  if ($price>=10) {
    $s=floor($price/10);
    $price-=$s*10;
  }
  $c=$price;
  if ($p>0) { $res=$p."p"; $sep=" "; }
  if ($g>0) { $res.=$sep.$g."g"; $sep=" "; }
  if ($s>0) { $res.=$sep.$s."s"; $sep=" "; }
  if ($c>0) { $res.=$sep.$c."c"; }
  return $res;
}

function sign($val) {
  if ($val>0) { return "<a href='javascript:;' class='btn yellow btn-xs' style='display:inline'>+$val</a>"; } else { return $val; }
}
function WriteDate($d) {
  return date("F d, Y",$d);
}

function isinteger($val) {
   return (intval($val)==$val);
}

function CanThisNPCDoubleAttack($class,$level) { // mob.cpp
  if ($level>26) { return true; } #NPC over lvl 26 all double attack
  switch ($class) {
    case 0: # monks and warriors
    case 1:
    case 20:
    case 26:
    case 27: 
      if ($level<15) { return false; }
      break;
    case 9: # rogues
    case 28: 
      if ($level<16) { return false; }
      break;
    case 4: # rangers
    case 23:
    case 5: # shadowknights
    case 24:
    case 3: # paladins
    case 22:
      if ($level<20) { return false; }
      break;
  }
  return false;
}

// Automatically format and populate the table based on the query
function AutoDataTable($Query) {
	$result = mysql_query($Query);
	if (!$result)
	{
		echo 'Could not run query: ' . mysql_error();
		exit;
	}
	$columns = mysql_num_fields($result);
	echo "<table border=0 width=100%><thead>";
	$RowClass = "lr";
	###Automatically Generate the column names from the Table	
		for ($i = 0; $i < $columns; $i++)
		{
			echo "<th class='menuh'>". ucfirstwords(str_replace('_',' ',mysql_field_name($result, $i))) . " </th>";
		}
	echo "</tr></thead><tbody>";
	while($row = mysql_fetch_array($result))
	{ 
		echo "<tr class='".$RowClass."'>";
		for($i = 0; $i < $columns; $i++)
		{
			echo "<td>" . $row[$i] . "</td>";
		}
		echo "</tr>";
		if ($RowClass == "lr")
		{
			$RowClass = "dr";
		}
		else
		{
			$RowClass = "lr";
		}	
	}
	echo "</tbody></table>";
}

// Prints all fields of a table to easily create an array for default values
// Example: AutoCreateArray("SELECT * FROM spells_new WHERE id = 3", "dbspelldefaults")
function AutoCreateArray($Query, $ArrayName) {
	$result = mysql_query($Query);
	if (!$result)
	{
		echo 'Could not run query: ' . mysql_error();
	}
	$columns = mysql_num_fields($result);
	$row = mysql_fetch_array($result);
	echo "\$" . $ArrayName . "=array(<br>";
	for ($i = 0; $i < $columns; $i++)
	{
		echo "	\"" . mysql_field_name($result, $i) . "\"	=>	\"" . $row[$i] . "\",<br>";
	}
	echo ");";
}

function CreateToolTip($ID, $Content){
	$Content = preg_replace("/'/i", "\'", $Content);
	echo '<script type="text/javascript">
		$(document).ready(function(){	
			$("a").easyTooltip();
			$("a#'. $ID . '").easyTooltip({
				tooltipId: "easyTooltip2",
				content: \''. $Content . '\'
			});
		});
	</script>';
}

function GetToolTip($ID, $Content){
	$Content = preg_replace("/'/i", "\'", $Content);
	$Tooltip = '<script type="text/javascript">
		$(document).ready(function(){	
			$("a").easyTooltip();
			$("a#'. $ID . '").easyTooltip({
				tooltipId: "easyTooltip2",
				content: \''. $Content . '\'
			});
		});
	</script>';
	return $Tooltip;
}


function Pagination($targetpage, $page, $total_pages, $limit, $adjacents)
{

	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1

	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage?page=$prev\">previous</a>";
		else
			$pagination.= "<span class=\"disabled\">previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage?page=$next\">next</a>";
		else
			$pagination.= "<span class=\"disabled\">next</span>";
		$pagination.= "</div>\n";		
	}
	return $pagination;
}	

function GetItemFormString($name, $width1, $fieldname, $value, $width2, $inputsize, $inputmax) {

	if (!$inputsize)
	{
		$inputsize = 3;
	}
	if (!$inputmax)
	{
		$inputmax= 5;
	}
	$PrintString = "";
	$PrintString = "<td width='".$width1."' nowrap='1'>".$name."</td><td width='".$width2."'><input type='text' name='".$fieldname."' value='".$value."' size='".$inputsize."' maxlength='".$inputmax."' /></td></tr>";

	return $PrintString;

}

function GetItemStatsString($name,$stat,$stat2,$stat2color) {

	if (!$stat2) { $stat2 = 0; }
	$PrintString = "";
	if (is_numeric($stat))
	{
		if($stat != 0 || $stat2 != 0)
		{
			$PrintString .= "<tr><td><b>".$name."</b></td><td align='right'>"; 
			if($stat < 0)
			{
				$PrintString .= "<font color='red'>".sign($stat)."</font>";
			}
			else
			{
				$PrintString .= "<b class='btn btn-default btn-xs' style='display:inline'>+" . $stat . "</b>";
			}
			if ($stat2 < 0)
			{
				$PrintString .= "<font color='red'> ".sign($stat2)."</font>";
			}
			elseif ($stat2 > 0)
			{
				if ($stat2color)
				{
					$PrintString .= "<font color='".$stat2color."'>".sign($stat2)."</font>";
				}
				else
				{
					$PrintString .= "<b class='btn btn-default btn-xs' style='display:inline'>" . sign($stat2) . '</b>';
				}
			}
			$PrintString .= "</td></tr>";
		}
	}
	else
	{
		if (ereg_replace("[^0-9]", "", $stat) > 0)
		{
			$PrintString .= "<tr><td><b>".$name."</b></td><td align='right'>".$stat."</td></tr>";
		}
	}
	return $PrintString;
}


// Function to build item stats tables
// Used for item.php as well as for tooltips for items
function BuildItemStats($item, $show_name_icon, $TableClass='') {

	global $dbitypes, $dam2h, $dbbagtypes, $dbskills, $icons_url, $icons_dir, $tbspells, $dbiaugrestrict, $dbiracenames, $root_url;

	$TableProperties = " border='0' cellpadding='0' cellspacing='0' ";
	if ($TableClass)
	{
		$TableClass = " class='".$TableClass."'";
	}
	
	$html_string = "";
	$html_string .= "<table border='0' style='width:500px' ".$TableClass."><tr><td>";
	if ($show_name_icon)
	{
		$html_string .= "<h4 style='margin-top:0' class='page-title'>" . $item["Name"] . "</h4><hr style='margin: 0px 0; margin-bottom:10px'></td><td>";
		if(file_exists("cust_assets/icons/item_" . $item["icon"] . ".png"))
		{
			$html_string .= "<img src='cust_assets/icons/item_" . $item["icon"] . ".png' align='right' valign='top' class='btn btn-default'/>";
		}
		else
		{ 
			$html_string .= "<img src='cust_assets/icons/item_0.png' align='right' valign='top' class='btn btn-default'/>";
		}
		$html_string .= "</td></tr><tr><td colspan='2'>";
	}
	
	$html_string .= "<table border='0' width='100%'".$TableClass.">";

	// lore, nodrop, norent, magic
	$html_string .= "<tr>";
	$html_string .= "<td colspan='2' nowrap='1'><b style='display:inline'>";
	$v = "";
	if($item["itemtype"] == 54)  { $html_string .= "$v AUGMENTATION"; $v = " "; }
	if($item["magic"] == 1)      { $html_string .= "$v MAGIC ITEM";   $v = " "; }
	if($item["loreflag"] == 1)   { $html_string .= "$v LORE ITEM";    $v = " "; }
	if($item["nodrop"] == 0)     { $html_string .= "$v NO DROP";      $v = " "; }
	if($item["norent"] == 0)     { $html_string .= "$v NO RENT";      $v = " "; }
	$html_string .= "                            </b><br></td>";
	$html_string .= "                          </tr>";

	// Classes
	if($item["classes"] > 0)
	{
		$html_string .= "<tr><td colspan='2'><b>Classes </b> " .getclasses($item["classes"])."</td></tr>";
	}
	else
	{
		$html_string .= "<tr><td colspan='2' nowrap='1'><b>Classes </b> <b class='btn btn-default btn-xs' style='display:inline'>All classes</b></td></tr>";
	}
	// Races
	if($item["races"] > 0)
	{
		$html_string .= "<tr><td colspan='2'><b>Races </b> ".getraces($item["races"])."</td></tr>";
	}
	else 
	{
		$html_string .= "<tr><td colspan='2' nowrap='1'><b>Races</b> <b class='btn btn-default btn-xs' style='display:inline'>All races</b> </td></tr>";
	}  
	// Deity
	if($item["deity"] > 0)
	{
		$html_string .= "<tr><td colspan='2' nowrap='1'><b>Deity </b> <b class='btn btn-default btn-xs' style='display:inline'>".gedeities($item["deity"])."</b></td></tr>";
	}

	// Slots
	if($item["slots"] > 0)
	{
		$html_string .= "<tr><td colspan='2'><b>Slot </b> <b class='btn btn-default btn-xs' style='display:inline'>".strtoupper(getslots($item["slots"]))."</b></td></tr>";
	}
	if($item["slots"] == 0)
	{
		$html_string .= "<tr><td colspan='2' ><b>Slot </b> <b class='btn btn-default btn-xs' style='display:inline'>NONE</b></td></tr>";
	}

	$TypeString = "";
	switch($item["itemtype"])
	{
		case 0: // 1HS
		case 2: // 1HP
		case 3: // 1HB
		case 42: // H2H
		case 1: // 2hs
		case 4: // 2hb 
		case 35: // 2hp
			$TypeString = "Skill";
			break;
		default:
			$TypeString = "Item Type";
			break;
	}
	// Item type or Skill
	// Bags show as 1HS
	if(($dbitypes[$item["itemtype"]] != "") && ($item["bagslots"] == 0))
	{
		if($item["slots"] == 0)
		{
			$html_string .= "<tr><td width='0%' nowrap='1' colspan='2'><b> ".$TypeString." </b>Inventory";
		}
		else
		{
			$html_string .= "<tr><td width='0%' nowrap='1' colspan='2'><b>".$TypeString." </b> <b class='btn btn-default btn-xs' style='display:inline'>".$dbitypes[$item["itemtype"]] . "</b>";
		}
		if ($item["stackable"] > 0)
		{
			$html_string .= " (stackable)";
		}
		$html_string .= "<hr style='margin: 0px 0;margin-top:10px'></td></tr>";
	}

	// Bag-specific information
	if($item["bagslots"] > 0)
	{
		$html_string .= "<tr><td width='0%' nowrap='1'><b>Item Type </b> <b class='btn btn-default btn-xs' style='display:inline'>Container</b></td></tr>";
		$html_string .= "<tr><td width='0%' nowrap='1'><b>Number of Slots </b><b class='btn btn-default btn-xs' style='display:inline'>".$item["bagslots"]."</b></td></tr>";
		if($item["bagtype"] > 0)
		{
			$html_string .= "<tr><td width='0%' nowrap='1'><b>Trade Skill Container </b> <b class='btn btn-default btn-xs' style='display:inline'>".$dbbagtypes[$item["bagtype"]]."</b></td></tr>";
		}
		if($item["bagwr"] > 0)
		{
			$html_string .= "<tr><td width='0%'  nowrap='1'><b>Weight Reduction </b> <b class='btn btn-default btn-xs' style='display:inline'>".$item["bagwr"]."%</b></td></tr>";
		}
		$html_string .= "<tr><td width='0%' nowrap='1' colspan='2'>This can hold ".strtoupper(getsize($item["bagsize"]))." and smaller items.</td></tr>";
	}

	$html_string .= "</table>";
	$html_string .= "</td></tr><tr><td colspan='2'>";
	$html_string .= "<table border='0' width='0%'".$TableClass.">";

	// Weight, Size, Rec/Req Level, skill
	$html_string .= "<tr valign='top'><td>";
	$html_string .= "<table width='100%'".$TableProperties.$TableClass.">";
	$html_string .= "<tr><td><b>Size</b></td><td align='right'><b class='btn btn-default btn-xs' style='display:inline'>".strtoupper(getsize($item["size"]))."</b></td></tr>";
	$html_string .= GetItemStatsString("Weight","<b class='btn btn-default btn-xs' style='display:inline'>" . ($item["weight"] / 10) . '</b>');
	$html_string .= GetItemStatsString("Rec Level", "<b class='btn btn-default btn-xs' style='display:inline'>" . $item["reclevel"] . '</b>');
	$html_string .= GetItemStatsString("Req Level", "<b class='btn btn-default btn-xs' style='display:inline'>" . $item["reqlevel"] . '</b>');
	$html_string .= "</table>";
	$html_string .= "</td><td>";
	
	// AC, HP, Mana, End, Haste
	$html_string .= "<table width='100%'".$TableProperties.$TableClass.">";
	$html_string .= GetItemStatsString("AC",	"<b class='btn btn-default btn-xs' style='display:inline'>+ " . $item["ac"]		. '</b>');
	$html_string .= GetItemStatsString("HP",	"<b class='btn btn-default btn-xs' style='display:inline'>+ " . $item["hp"]		. '</b>');
	$html_string .= GetItemStatsString("Mana",	"<b class='btn btn-default btn-xs' style='display:inline'>+ " . $item["mana"]		. '</b>');
	$html_string .= GetItemStatsString("Endur",	"<b class='btn btn-default btn-xs' style='display:inline'>+ " . $item["endur"]		. '</b>');
	$html_string .= GetItemStatsString("Haste",	"<b class='btn btn-default btn-xs' style='display:inline'>+ " . $item["haste"."%"]	. '</b>');
	$html_string .= "</table>";
	$html_string .= "</td><td>";

	// Base Damage, Ele/Bane/BodyType Damage, BS Damage, Delay, Range, Damage Bonus, Range
	$html_string .= "<table width='100%'".$TableProperties.$TableClass.">";
	$html_string .= GetItemStatsString("Base Damage","<b class='btn btn-default btn-xs' style='display:inline'>+ " . $item["damage"] . "</b>");
	$html_string .= GetItemStatsString(ucfirstwords($dbelements[$item["elemdmgtype"]])." Damage",$item["elemdmgamt"]);
	if (($item["banedmgrace"]>0) && ($item["banedmgamt"]!=0))
	{
		$html_string .= "<tr><td><b>Bane Damage (";
		$html_string .= $dbiracenames[$item["banedmgrace"]];
		$html_string .= ") </b></td><td align='right'>".sign($item["banedmgamt"])."</td></tr>";
	}
	$html_string .= GetItemStatsString(ucfirstwords($dbbodytypes[$item["banedmgbody"]]),$item["banedmgamt"]);
	$html_string .= GetItemStatsString("Backstab Damage",$item["backstabdmg"]);
	$html_string .= GetItemStatsString("Delay",$item["delay"]);
	if($item["damage"] > 0)
	{
		switch($item["itemtype"])
		{
			case 0: // 1HS
			case 2: // 1HP
			case 3: // 1HB
			case 42: // H2H
				$dmgbonus = 13; // floor((65-25)/3)  main hand
				$html_string .= "<tr><td><b>Damage Bonus: </b></td><td align='right'>$dmgbonus</td></tr>";
				break;
			case 1: // 2hs
			case 4: // 2hb 
			case 35: // 2hp
				$dmgbonus = $dam2h[$item["delay"]]; 
				$html_string .= "<tr><td><b>Damage Bonus: </b></td><td align='right'>$dmgbonus</td></tr>";
				break;  
		}
	}
	$html_string .= GetItemStatsString("Range",$item["range"]);
	$html_string .= "</table>";
	
	$html_string .= "</td></tr>";

	$html_string .= "<tr valign='top'><td>";
	
	// Normal Stats
	$html_string .= "<hr style='margin: 0px 0;'><table width='100%'".$TableProperties.$TableClass.">";
	$html_string .= GetItemStatsString("Strength",$item["astr"],$item["heroic_str"],"orange");
	$html_string .= GetItemStatsString("Stamina",$item["asta"],$item["heroic_sta"],"orange");
	$html_string .= GetItemStatsString("Intelligence",$item["aint"],$item["heroic_int"],"orange");
	$html_string .= GetItemStatsString("Wisdom",$item["awis"],$item["heroic_wis"],"orange");
	$html_string .= GetItemStatsString("Agility",$item["aagi"],$item["heroic_agi"],"orange");
	$html_string .= GetItemStatsString("Dexterity",$item["adex"],$item["heroic_dex"],"orange");
	$html_string .= GetItemStatsString("Charisma",$item["acha"],$item["heroic_cha"],"orange");
	$html_string .= "</table>";
	$html_string .= "</td><td>";
	
	// Resists
	$html_string .= "<hr style='margin: 0px 0;'><table width='100%'".$TableProperties.$TableClass.">";
	$html_string .= GetItemStatsString("Magic Resist",$item["mr"],$item["heroic_mr"],"orange");
	$html_string .= GetItemStatsString("Fire Resist",$item["fr"],$item["heroic_fr"],"orange");
	$html_string .= GetItemStatsString("Cold Resist",$item["cr"],$item["heroic_cr"],"orange");
	$html_string .= GetItemStatsString("Disease Resist",$item["dr"],$item["heroic_dr"],"orange");
	$html_string .= GetItemStatsString("Poison Resist",$item["pr"],$item["heroic_pr"],"orange");
	$html_string .= "</table>";
	$html_string .= "</td><td>";
	
	// Modifiers
	$html_string .= "<hr style='margin: 0px 0;'><table width='100%'".$TableProperties.$TableClass.">";
	$html_string .= GetItemStatsString("Attack",$item["attack"]);
	$html_string .= GetItemStatsString("HP Regen",$item["regen"]);
	$html_string .= GetItemStatsString("Mana Regen",$item["manaregen"]);
	$html_string .= GetItemStatsString("Endurance Regen",$item["enduranceregen"]);
	$html_string .= GetItemStatsString("Spell Shielding",$item["spellshield"]);
	$html_string .= GetItemStatsString("Combat Effects",$item["combateffects"]);
	$html_string .= GetItemStatsString("Shielding",$item["shielding"]);
	$html_string .= GetItemStatsString("DoT Shielding",$item["dotshielding"]);
	$html_string .= GetItemStatsString("Avoidance",$item["avoidance"]);
	$html_string .= GetItemStatsString("Accuracy",$item["accuracy"]);
	$html_string .= GetItemStatsString("Stun Resist",$item["stunresist"]);
	$html_string .= GetItemStatsString("Strikethrough",$item["strikethrough"]);
	$html_string .= GetItemStatsString("Damage Shield",$item["damageshield"]);
	$html_string .= "</table>";
	$html_string .= "</td></tr></table><hr style='margin: 0px 0;margin-top:10px'>";
	
	// Extras
	if ($item["extradmgamt"]>0)
	{
		$html_string .= "<tr><td><b>".ucfirstwords($dbskills[$item["extradmgskill"]])." Damage</b>".sign($item["extradmgamt"])."</td></tr>";
	}
	
	// Skill Mods
	if (($item["skillmodtype"]>0) && ($item["skillmodvalue"]!=0))
	{
		$html_string .= "<tr><td colspan='2' nowrap='1'><b>Skill Mod".ucfirstwords($dbskills[$item["skillmodtype"]])."</b>".sign($item["skillmodvalue"])."%</td></tr>";
	}
	// Augmentations
	for( $i = 1; $i <= 5; $i ++)
	{
		if($item["augslot".$i."type"] > 0)
		{
			$html_string .= "<tr><td width='0%' nowrap='1' colspan='2'><b>Slot ".$i."</b> <b style='btn btn-default btn-xs'>Type ".$item["augslot".$i."type"]."</b> </td></tr>";
		}
	}
	//item proc
	if (($item["proceffect"]>0) && ($item["proceffect"]<65535))
	{ 
		$html_string .= "<tr><td colspan='2' nowrap='1'><b>Combat Effect </b><a href='". $root_url . "spell.php?id=".$item["proceffect"]."' target='". $item["proceffect"]."'>".GetFieldByQuery("name","SELECT name FROM spells_new WHERE id=".$item["proceffect"])."</a>";
		if ($item["proclevel2"]>0)
		{
			$html_string .= "<br><b>Level for effect </b>".$item["proclevel2"];
		}
		$html_string .= "</td></tr>";
	}
	// worn effect
	if (($item["worneffect"]>0) && ($item["worneffect"]<65535))
	{ 
		$html_string .= "<tr><td colspan='2' nowrap='1'><b>Worn Effect </b><a href='". $root_url . "spell.php?id=".$item["worneffect"]."' target='". $item["worneffect"]."'>".GetFieldByQuery("name","SELECT name FROM spells_new WHERE id=".$item["worneffect"])."</a>";
		if ($item["wornlevel"]>0)
		{
			$html_string .= "<br><b>Level for effect </b>".$item["wornlevel"];
		}
		$html_string .= "</td></tr>";
	}
	// focus effect
	if (($item["focuseffect"]>0) && ($item["focuseffect"]<65535))
	{
		$html_string .= "<tr><td colspan='2' nowrap='1'><b>Focus Effect </b><a href='". $root_url . "spell.php?id=".$item["focuseffect"]."' target='". $item["focuseffect"]."'>".GetFieldByQuery("name","SELECT name FROM spells_new WHERE id=".$item["focuseffect"])."</a>";
		if ($item["focuslevel"]>0)
		{
			$html_string .= "<br/><b>Level for effect</b>".$item["focuslevel"];
		}
		$html_string .= "</td></tr>";
	}
	// clicky effect
	if (($item["clickeffect"]>0) && ($item["clickeffect"]<65535))
	{ 
		$html_string .= "<tr><td colspan='2' nowrap='1'><b>Click Effect </b><a href='". $root_url . "spell.php?id=".$item["clickeffect"]."' target='". $item["clickeffect"]."'>".GetFieldByQuery("name","SELECT name FROM spells_new WHERE id=".$item["clickeffect"])."</a> (";
		if ($item["clicktype"]==4)
		{
			$html_string .= "Must Equip. ";
		}
		if ($item["casttime"]>0)
		{
			$html_string .= "<b>Casting time </b>".($item["casttime"]/1000)." sec";
		}
		else
		{
			$html_string .= "<b>Casting time </b>Instant";
		}
		$html_string .= ")";
		if ($item["clicklevel"]>0)
		{
			$html_string .= "<br/><b>Level for effect </b>".$item["clicklevel"];
		}
		if ($item["maxcharges"]>0)
		{
			$html_string .= "<br/><b>Charges </b>".$item["maxcharges"];
		}
		elseif ($item["maxcharges"]<0)
		{
			$html_string .= "<br/><b>Charges </b>Unlimited";
		}
		else
		{
			$html_string .= "<br/><b>Charges </b>None";
		}
		$html_string .= "</td></tr>";
	}
	// scroll
	if (($item["scrolleffect"]>0) && ($item["scrolleffect"]<65535))
	{ 
		$html_string .= "<tr><td colspan='2' nowrap='1'><b>Spell Scroll Effect </b><a href='". $root_url . "spell.php?id=".$item["scrolleffect"]."' target='". $item["scrolleffect"]."'>".GetFieldByQuery("name","SELECT name FROM spells_new WHERE id=".$item["scrolleffect"])."</a>";
		$html_string .= "</td></tr>";
	}
	// bard item ?
	if (($item["bardtype"]>22) && ($item["bardtype"]<65535))
	{
		$html_string .= "<tr><td width='0%' nowrap='1' colspan='2'><b>Bard skill </b> ".$dbbardskills[$item["bardtype"]];
		if ($dbbardskills[$item["bardtype"]]=="")
		{
			$html_string .= "Unknown".$item["bardtype"];
		}
		$val=($item["bardvalue"]*10)-100;
		if ($val>0)
		{
			$html_string .= " (".sign($val)."%)</td></tr>";
		}
	}

	// Augmentation type
	if($item["itemtype"] == 54)
	{
		if($item["augtype"] > 0)
		{
			$Comma = "";
			$AugSlots = "";
			$AugType = $item["augtype"];
			$Bit = 1;
			for ($i = 1; $i < 25; $i++)
			{
				if ($Bit <= $AugType && $Bit & $AugType)
				{
					$AugSlots .= $Comma.$i;
					$Comma = ", ";
				}
				$Bit *= 2;
			}	
			$html_string .= "<tr><td colspan='2' nowrap='1'><b>Augmentation Slot Type </b>".$AugSlots."</td></tr>";
		}
		else
		{
			$html_string .= "<tr><td colspan='2' nowrap='1'><b>Augmentation Slot Type </b>All Slots</td></tr>";
		}
		if ($item["augrestrict"] > 0)
		{
			if ($item["augrestrict"] > 12)
			{
				$html_string .= "<tr><td colspan='2' nowrap='1'><b>Augmentation Restriction </b>Unknown Type</td></tr>";
			}
			else
			{
				$Restriction = $dbiaugrestrict[$item["augrestrict"]];
				$html_string .= "<tr><td colspan='2' nowrap='1'><b>Augmentation Restriction </b>$Restriction</td></tr>";
			}
		}
	}

	$ItemPrice = $item["price"];
	$ItemValue = "";
	$Platinum = 0;
	$Gold = 0;
	$Silver = 0;
	$Copper = 0;
	
	if ($ItemPrice > 1000)
	{
		$Platinum = ((int)($ItemPrice / 1000));
	}
	if (($ItemPrice - ($Platinum * 1000)) > 100)
	{
		$Gold = ((int)(($ItemPrice - ($Platinum * 1000)) / 100));
	}
	if (($ItemPrice - ($Platinum * 1000) - ($Gold * 100)) > 10)
	{
		$Silver = ((int)(($ItemPrice - ($Platinum * 1000) - ($Gold * 100)) / 10));
	}
	if (($ItemPrice - ($Platinum * 1000) - ($Gold * 100) - ($Silver * 10)) > 0)
	{
		$Copper = ($ItemPrice - ($Platinum * 1000) - ($Gold * 100) - ($Silver * 10));
	}
	
	$ItemValue .= "<tr><td colspan='2' nowrap='1'><b>Value </b>";	
	$ItemValue .= $Platinum." <img src='cust_assets/icons/item_644.png' width='14' height='14'/> ".
	$Gold." <img src='cust_assets/icons/item_645.png' width='14' height='14'/> ".
	$Silver." <img src='cust_assets/icons/item_646.png' width='14' height='14'/> ".
	$Copper." <img src='cust_assets/icons/item_647.png' width='14' height='14'/>";
	$ItemValue .= "</td></tr>";
	$html_string .= $ItemValue;

	$html_string .= "</td></tr></table>";

	return $html_string;

}

// Function to build spell information tables
// Used for spell.php as well as for tooltips for spells
function BuildSpellInfo($spell, $show_name_icon) {

	global $dbclasses, $dbspelltargets, $dbspellresists, $dbskills, $icons_url, $tbitems, $icons_dir;
	
	$html_string = "<table border=0 style='padding:5px;width:500px'>";
		   
	if ($show_name_icon)
	{
		$html_string .= "<tr><td>";
		$html_string .= '<img src="includes/img.php?type=spellimage&id=' . $spell['new_icon'] . '" class="btn btn-default">';
		$html_string .= "</td><td><h2 style='margin-top:0'>" . $spell["name"] . "</h2><hr>";
		$html_string .= "</td></tr>";
		
	}

	$html_string .= "<tr><td nowrap><b>Classes</b></td><td>";
	$v="";
	$minlvl=70;
	for ($i=1; $i<=16; $i++)
	{
		if (($spell["classes$i"]>0) AND ($spell["classes$i"]<255))
		{
			$html_string .= "$v ".$dbclasses[$i]." (".$spell["classes$i"].")";
			$v=",";
			if ($spell["classes$i"]<$minlvl) { $minlvl=$spell["classes$i"]; }
		}
	}
	$html_string .= "</td></tr>";
	if ($spell["you_cast"]!="") { $html_string .= "<tr><td nowrap><b>When you cast </b></td><td>".$spell["you_cast"]."</td></tr>"; }
	if ($spell["other_casts"]!="") { $html_string .= "<tr><td nowrap><b>When others cast</b></td><td>".$spell["other_casts"]."</td></tr>"; }
	if ($spell["cast_on_you"]!="") { $html_string .= "<tr><td nowrap><b>When cast on you </b></td><td>".$spell["cast_on_you"]."</td></tr>"; }
	if ($spell["cast_on_other"]!="") { $html_string .= "<tr><td nowrap><b>When cast on other </b></td><td>".$spell["cast_on_other"]."</td></tr>"; }
	if ($spell["spell_fades"]!="") { $html_string .= "<tr><td nowrap><b>When fading </b></td><td>".$spell["spell_fades"]."</td></tr>"; }
	$html_string .= "<tr><td nowrap><b>Mana </b></td><td>".$spell["mana"]."</td></tr>";
	if ($spell["skill"]<52)
	{
		$html_string .= "<tr><td nowrap><b>Skill</b></td><td>".$dbskills[$spell["skill"]]."</td></tr>";
	}
	$html_string .= "<tr><td nowrap><b>Casting time</b></td><td>".($spell["cast_time"]/1000)." sec</td></tr>";
	$html_string .= "<tr><td nowrap><b>Recovery time</b></td><td>".($spell["recovery_time"]/1000)." sec</td></tr>";
	$html_string .= "<tr><td nowrap><b>Recast time</b></td><td>".($spell["recast_time"]/1000)." sec</td></tr>";
	$html_string .= "<tr><td nowrap><b>Range</b></td><td>".$spell["range"]."</td></tr>";
	$html_string .= "<tr><td nowrap><b>Target</b></td><td>";
	if ($dbspelltargets[$spell["targettype"]]!="") { $html_string .= $dbspelltargets[$spell["targettype"]]; }
	else { $html_string .= "Unknown target (".$spell["targettype"].")"; }
	$html_string .= "</td></tr>";
	$html_string .= "<tr><td nowrap><b>Resist</b></td><td>".$dbspellresists[$spell["resist"]]." (adjust".$spell["ResistDiff"].")</td></tr>";
	if ($spell["TimeOfDay"]==2) { $html_string .= "<tr><td nowrap><b>Casting restriction</b></td><td>Nighttime</td></tr>"; }
	$duration=CalcBuffDuration($minlvl,$spell["buffdurationformula"],$spell["buffduration"]);
	$html_string .= "<tr><td nowrap><b>Duration</b></td><td>";
	if ($duration==0) { $html_string .= "Instant"; } 
	else { $html_string .= translate_time($duration*6)." ($duration ticks)"; }
	$html_string .= "</td></tr>";
	for ($i=1; $i<=4; $i++)
	{
		// reagents
		if ($spell["components".$i]>0)
		{
			$html_string .= "<tr><td nowrap><b>Needed Reagents # $i </b></td><td>".
				"<a href=item.php?id=".$spell["components".$i].
				">".GetFieldByQuery("Name","SELECT Name FROM $tbitems WHERE id=".
				$spell["components".$i]).
				" </a> x ".$spell["component_counts".$i]." </td></tr>";
		}
	}

	$html_string .= "<tr><td colspan=2><b>Spell effects:</b></td></tr>";
	
	$html_string .=  "<td></td><td align='left' colspan=1><small>";
	for ($n=1; $n<=12; $n++) {
		$html_string .= SpellDescription($spell,$n, false, false);
	}
	$html_string .=  "</small></td>";
	
	$html_string .= "</table>";
	
	return $html_string;

}

	/** Runs '$query' and returns the value of '$field' of the first (arbitrarily) found row
	 *  If no row is selected by '$query', returns an emty string
	 */
	function GetFieldByQuery($field,$query)
	{ $QueryResult = mysql_query($query) or message_die('mysql.php','GetFiedByQuery',$query,mysql_error());
	  if(mysql_num_rows($QueryResult) > 0)
	  { $rows=mysql_fetch_array($QueryResult) or message_die('mysql.php','GetFiedByQuery',"MYSQL_FETCH_ARRAY",mysql_error());
		$Result = $rows[$field];
	  }
	  else
		$Result = "";

	  return $Result;
	}

	/** Runs '$query' and returns the first (arbitrarily) found row.
	 */
	function GetRowByQuery($query)
	{ $QueryResult = mysql_query($query) or mysql_die($query);
	  $Result = mysql_fetch_array($QueryResult);

	  return $Result;
	}

	function message_die($t1,$t2,$t3,$t4)
	{ print "<p><table width='100%' border=0>\n";
	  print "<tr><td align=left><b>$t1</b></td></tr>\n";
	  print "<tr><td align=left>$t2</td></tr>\n";
	  print "<tr><td align=left>$t3</td></tr>\n";
	  print "<tr><td align=left>$t4</td></tr>\n";
	  print "<tr><td align=center><font color=red><b>Have you sourced the eqbrowser tables in your database ?</b></font></td></tr>\n";
	  print "</table></p>\n";
	}
?>