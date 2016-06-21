<?php

	echo '<script type="text/javascript">
				function toggle() {
					var elements = document.getElementsByClassName("Unknown");
					var names = "";
					for(var i=0; i<elements.length; i++) {
						if(elements[i].style.display == ""){
							elements[i].style.display = "none";
						}
						else{
							elements[i].style.display = "";
						}
					}
					var elements = document.getElementsByClassName("augmentation");
					var names = "";
					for(var i=0; i<elements.length; i++) {
						if(elements[i].style.display == ""){
							elements[i].style.display = "none";
						}
						else{
							elements[i].style.display = "";
						}
					}				
				}
				function OpenClose() {
					var ele = document.getElementById("IrrContent");
					if(ele.style.display == "block") {
						ele.style.display = "none";
					}
					else {
						ele.style.display = "block";
					}
				}
				function FieldEdit(fieldid, width, height, fieldvalue) {
					window.open("min.php?Mod=IE&EditField=" + fieldid + "&Value=" + fieldvalue, fieldid, "width=" + width + ",height=" + height + ",toolbar=0,location=no,status=0,menubar=0,resizable=0,scrollbars=1");
				}
				function FieldEditGet(fieldid, width, height) {
					window.open("min.php?Mod=IE&EditField=" + fieldid + "&Value=" + document.getElementsByName(fieldid)[0].value, fieldid, "width=" + width + ",height=" + height + ",toolbar=0,location=no,status=0,menubar=0,resizable=0,scrollbars=1");
				}
				function EditorOptions(){
					window.open("min.php?Mod=IE&EditorOptions=", "Edit Options", "width=600,height=600,toolbar=0,location=no,status=0,menubar=0,resizable=0,scrollbars=1");
				}
				function UpdateField(fieldid, valuefromchild) {
					if(valuefromchild != 0){
						opener.document.getElementsByName(fieldid)[0].value = valuefromchild;
						opener.document.getElementsByName(fieldid)[0].setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
						ChildStatusUpdate("Changed field \'<font color=yellow>" + fieldid + "</font>\' to value <font color=#66FF00>" + valuefromchild + "</font>");
					}
				}
				function IconEdit(id) {
					window.open("min.php?Mod=IE&previcon=" + document.getElementById("icon").value, id, "width=1000,height=700,toolbar=0,location=no,status=0,menubar=0,resizable=0,scrollbars=1");
				} 
				function FinishIcon(id) {
					//opener.document.images.MyIcon.src="includes/img.php?type=iconimage&id=" + id
					opener.document.images.MyIcon.setAttribute("class","icon-" + id);
					opener.document.getElementById("icon").value = id;
					opener.document.getElementById("icon").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
					ChildStatusUpdate("Updated Icon graphic: Set to <font color=yellow><b>" + id + " in editor window");
				}
				function UpdateIcon(id) { 
					//document.images.MyIcon.src="includes/img.php?type=iconimage&id=" + document.getElementById("icon").value
					document.images.MyIcon.class="icon-" + document.getElementById("icon").value;
				} 
				function IDFileEdit(id) {
					window.open("min.php?Mod=IE&prevITfile=" + document.getElementById("idfile").value, id, "width=1000,height=700,toolbar=0,location=no,status=0,menubar=0,resizable=0,scrollbars=1");
				} 
				function FinishIDFile(id) {
					if(opener.document.images.MyIDFile){ 
						opener.document.images.MyIDFile.src="includes/img.php?type=weaponimage&id=" + id
					}
					if(opener.document.getElementById("idfile")){
						opener.document.getElementById("idfile").value = "IT" + id;
						opener.document.getElementById("idfile").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
						ChildStatusUpdate("Updated Weapon graphic: Set to <font color=yellow><b>" + "IT" + id + "</font> in editor window");
					}';
					/* NPC Editor Hack */
					if($_GET['Field'] == "d_meele_texture1"){
						echo '	if(opener.document.getElementById( "' . $_GET['NPC'] . '^d_meele_texture1")){
									opener.document.getElementById("' . $_GET['NPC'] . '^d_meele_texture1").value = id;
									opener.document.getElementById("' . $_GET['NPC'] . '^d_meele_texture1").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
									opener.document.getElementById("d_meele_texture1").src="includes/img.php?type=weaponimage&id=" + id;
									opener.UpdateSingleNPCEdit(' . $_GET['NPC'] . ', "' . $_GET['Field'] . '", id);
								}
							';
					}
					if($_GET['Field'] == "d_meele_texture2"){ 
						echo '	if(opener.document.getElementById( "' . $_GET['NPC'] . '^d_meele_texture2")){
									opener.document.getElementById("' . $_GET['NPC'] . '^d_meele_texture2").value = id;
									opener.document.getElementById("' . $_GET['NPC'] . '^d_meele_texture2").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
									opener.document.getElementById("d_meele_texture2").src="includes/img.php?type=weaponimage&id=" + id;
									opener.UpdateSingleNPCEdit(' . $_GET['NPC'] . ', "' . $_GET['Field'] . '", id); 
								}
							';
					}
					
				echo '}
				function UpdateIDFile(id) { 
					document.images.MyIDFile.src="includes/img.php?type=weaponimage&id=" + document.getElementById("idfile").value
				}
				function PreviewItem(element) { 
					window.open("min.php?Mod=IE&previd=" + element, element + "2", "width=700,height=680,toolbar=0,location=no,status=0,menubar=0,resizable=0,scrollbars=0");
				}
				function UpdateImage(id){
					// document.images.MyRaceID.src="includes/img.php?type=race&id=" + id + ""
					$("#MyRaceID").fadeOut(200, function() {
						$("#MyRaceID").attr("src", "includes/img.php?type=race&id=" + id);
					}) .fadeIn(200);
				}
				function UpdateFieldsMinMax(fieldsname){
					if(fieldsname == "resists"){
						var min = parseInt(document.getElementById("resistmin").value);
						var max = parseInt(document.getElementById("resistmax").value);
						if(min >= 0 && max >= 0){
							document.getElementById("cr").value = Math.round(randomRange(min, max));
							document.getElementById("cr").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
							document.getElementById("fr").value = Math.round(randomRange(min, max));
							document.getElementById("fr").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
							document.getElementById("pr").value = Math.round(randomRange(min, max));
							document.getElementById("pr").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
							document.getElementById("dr").value = Math.round(randomRange(min, max));
							document.getElementById("dr").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
							document.getElementById("mr").value = Math.round(randomRange(min, max));
							document.getElementById("mr").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
							document.getElementById("svcorruption").value = Math.round(randomRange(min, max));
							document.getElementById("svcorruption").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
							ChildStatusUpdate(
								"<font color=yellow><b>Cold Resist</b></font> Set to <font color=#66FF00><b>" + document.getElementById("cr").value +
								"</b></font><br><font color=yellow><b>Fire Resist</b></font> Set to <font color=#66FF00><b>" + document.getElementById("fr").value +
								"</b></font><br><font color=yellow><b>Poison Resist</b></font> Set to <font color=#66FF00><b>" + document.getElementById("pr").value +
								"</b></font><br><font color=yellow><b>Disease Resist</b></font> Set to <font color=#66FF00><b>" + document.getElementById("dr").value +
								"</b></font><br><font color=yellow><b>Magic Resist</b></font> Set to <font color=#66FF00><b>" + document.getElementById("mr").value +
								"</b></font><br><font color=yellow><b>Corruption Resist</b></font> Set to <font color=#66FF00><b>" + document.getElementById("svcorruption").value +
								"");
						}
						else{ ChildStatusUpdate("You must have valid numbers BOTH your min max fields!"); }
					}
					if(fieldsname == "hresists"){
						var min = parseInt(document.getElementById("hresistmin").value);
						var max = parseInt(document.getElementById("hresistmax").value);
						if(min >= 0 && max >= 0){
							document.getElementById("heroic_cr").value = Math.round(randomRange(min, max));
							document.getElementById("heroic_cr").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
							document.getElementById("heroic_fr").value = Math.round(randomRange(min, max));
							document.getElementById("heroic_fr").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
							document.getElementById("heroic_pr").value = Math.round(randomRange(min, max));
							document.getElementById("heroic_pr").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
							document.getElementById("heroic_dr").value = Math.round(randomRange(min, max));
							document.getElementById("heroic_dr").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
							document.getElementById("heroic_mr").value = Math.round(randomRange(min, max));
							document.getElementById("heroic_mr").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
							document.getElementById("heroic_svcorrup").value = Math.round(randomRange(min, max));
							document.getElementById("heroic_svcorrup").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
							ChildStatusUpdate(
								"<font color=yellow><b>Heroic Cold Resist</b></font> Set to <font color=#66FF00><b>" + document.getElementById("heroic_cr").value +
								"</b></font><br><font color=yellow><b>Heroic Fire Resist</b></font> Set to <font color=#66FF00><b>" + document.getElementById("heroic_fr").value +
								"</b></font><br><font color=yellow><b>Heroic Poison Resist</b></font> Set to <font color=#66FF00><b>" + document.getElementById("heroic_pr").value +
								"</b></font><br><font color=yellow><b>Heroic Disease Resist</b></font> Set to <font color=#66FF00><b>" + document.getElementById("heroic_dr").value +
								"</b></font><br><font color=yellow><b>Heroic Magic Resist</b></font> Set to <font color=#66FF00><b>" + document.getElementById("heroic_mr").value +
								"</b></font><br><font color=yellow><b>Heroic Corruption Resist</b></font> Set to <font color=#66FF00><b>" + document.getElementById("heroic_svcorrup").value +
								"");
						}
						else{ ChildStatusUpdate("You must have valid numbers BOTH your min max fields!"); }
					}
					if(fieldsname == "stats"){
						var min = parseInt(document.getElementById("statsmin").value);
						var max = parseInt(document.getElementById("statsmax").value);
						if(min >= 0 && max >= 0){
							document.getElementById("astr").value = Math.round(randomRange(min, max));
							document.getElementById("astr").setAttribute("style","border-color: rgba(82, 168, 236, 0.8)");
							document.getElementById("adex").value = Math.round(randomRange(min, max));
							document.getElementById("asta").value = Math.round(randomRange(min, max));
							document.getElementById("aagi").value = Math.round(randomRange(min, max));
							document.getElementById("acha").value = Math.round(randomRange(min, max));
							document.getElementById("awis").value = Math.round(randomRange(min, max));
							document.getElementById("aint").value = Math.round(randomRange(min, max));
							ChildStatusUpdate(
								"<font color=yellow><b>Strength</b></font> Set to <font color=#66FF00><b>" + document.getElementById("astr").value +
								"</b></font><br><font color=yellow><b>Dexterity</b></font> Set to <font color=#66FF00><b>" + document.getElementById("adex").value +
								"</b></font><br><font color=yellow><b>Stamina</b></font> Set to <font color=#66FF00><b>" + document.getElementById("asta").value +
								"</b></font><br><font color=yellow><b>Agility</b></font> Set to <font color=#66FF00><b>" + document.getElementById("aagi").value +
								"</b></font><br><font color=yellow><b>Charisma</b></font> Set to <font color=#66FF00><b>" + document.getElementById("acha").value +
								"</b></font><br><font color=yellow><b>Wisdom</b></font> Set to <font color=#66FF00><b>" + document.getElementById("awis").value +
								"</b></font><br><font color=yellow><b>Intelligence</b></font> Set to <font color=#66FF00><b>" + document.getElementById("aint").value +
								"");
						}
						else{ ChildStatusUpdate("You must have valid numbers BOTH your min max fields!"); }
					}
					if(fieldsname == "hstats"){
						var min = parseInt(document.getElementById("hstatsmin").value);
						var max = parseInt(document.getElementById("hstatsmax").value);
						if(min >= 0 && max >= 0){
						document.getElementById("heroic_str").value = Math.round(randomRange(min, max));
						document.getElementById("heroic_dex").value = Math.round(randomRange(min, max));
						document.getElementById("heroic_sta").value = Math.round(randomRange(min, max));
						document.getElementById("heroic_agi").value = Math.round(randomRange(min, max));
						document.getElementById("heroic_cha").value = Math.round(randomRange(min, max));
						document.getElementById("heroic_wis").value = Math.round(randomRange(min, max));
						document.getElementById("heroic_int").value = Math.round(randomRange(min, max));
						ChildStatusUpdate(
							"<font color=yellow><b>Heroic Strength</b></font> Set to <font color=#66FF00><b>" + document.getElementById("heroic_str").value +
							"</b></font><br><font color=yellow><b>Heroic Dexterity</b></font> Set to <font color=#66FF00><b>" + document.getElementById("heroic_dex").value +
							"</b></font><br><font color=yellow><b>Heroic Stamina</b></font> Set to <font color=#66FF00><b>" + document.getElementById("heroic_sta").value +
							"</b></font><br><font color=yellow><b>Heroic Agility</b></font> Set to <font color=#66FF00><b>" + document.getElementById("heroic_agi").value +
							"</b></font><br><font color=yellow><b>Heroic Charisma</b></font> Set to <font color=#66FF00><b>" + document.getElementById("heroic_cha").value +
							"</b></font><br><font color=yellow><b>Heroic Wisdom</b></font> Set to <font color=#66FF00><b>" + document.getElementById("heroic_wis").value +
							"</b></font><br><font color=yellow><b>Heroic Intelligence</b></font> Set to <font color=#66FF00><b>" + document.getElementById("heroic_int").value +
							"");
						}
						else{ ChildStatusUpdate("You must have valid numbers BOTH your min max fields!"); }
					}
				}
				function randomRange(min,max) { 
					var min = parseInt(min);
					var max = parseInt(max);
					var result;
					result = Math.random()*(max-min) + min;
					return result; 
				}
				</script>';
?>