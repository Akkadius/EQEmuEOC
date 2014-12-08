function DoSpellFieldInputEdit(FieldID){
	SpellID = $('#' + FieldID).attr('spell-id');
	Val = $('#' + FieldID).val(); 
	/* Perform Save */
	
	$.ajax({
		url: "ajax.php?M=SpellEditor&DoFieldEditSave&Spell_ID=" + encodeURIComponent(SpellID) + 
			"&Field=" + encodeURIComponent(FieldID) + "&Val=" + encodeURIComponent(Val), 
		context: document.body
	}).done(function(e) {
		if(e.indexOf("Success") > -1){
			$.notific8('<br>Field \'' + FieldID + '\' with value \'' + Val + '\' updated!', {  heading: "Spell Editor: " + 'Spell ID: ' + SpellID,  theme: "ruby", life: 3000 });
		}else{
			$.notific8('Unknown error saving!', {  heading: "Spell Editor: " + 'Spell ID: ' + SpellID,  theme: "ruby", life: 3000 });
		}
	});
}

function DoComponentsSelect(val){ 
	$('#comp_select').html('<i class="fa fa-spinner fa-spin">');
	$.ajax({
		url: "ajax.php?M=SpellEditor&components_select&val=" + val,  
		context: document.body
	}).done(function(e) {
		$('#comp_select').html(e);
	}); 
}

function UpdateParentFieldSpellIcon(FieldID, Val){
	SpellID = $('#' + FieldID).attr('spell-id'); 
	
	$('#' + FieldID + '_ico').attr("src", "includes/img.php?type=spellimage&id=" + Val + "");
	
	/* Perform Save */
	$.ajax({ 
		url: "ajax.php?M=SpellEditor&DoFieldEditSave&Spell_ID=" + encodeURIComponent(SpellID) + 
			"&Field=" + encodeURIComponent(FieldID) + "&Val=" + encodeURIComponent(Val), 
		context: document.body
	}).done(function(e) {
		if(e.indexOf("Success") > -1){
			
			$.notific8('<br>Field \'' + FieldID + '\' with value \'' + Val + '\' updated!', {  heading: "Spell Editor :: " + 'Spell ID: ' + SpellID,  theme: "ruby", life: 3000 });
		}else{
			$.notific8('Unknown error saving!', {  heading: "Spell Editor: " + 'Spell ID: ' + SpellID,  theme: "ruby", life: 3000 });
		}
	}); 
	
}

function PreviewSpellAnim(anim){
	$.ajax({ 
		url: "ajax.php?M=SpellEditor&DoVideoPreview=" + encodeURIComponent(anim), 
		context: document.body
	}).done(function(e) {
		$('#spellanim_video').html(e);
	}); 
}