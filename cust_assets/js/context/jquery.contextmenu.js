/**
 * jQuery plugin for Pretty looking right click context menu.
 * Icon needs to be 16x16. I recommend the Fugue icon set from: http://p.yusukekamiyamane.com/ 
 * - Joe Walnes, 2011 http://joewalnes.com/
 *   https://github.com/joewalnes/jquery-simple-context-menu
 */
jQuery.fn.contextPopup = function(menuData) {
	// Define default settings
	var settings = {
		contextMenuClass: 'contextMenuPlugin',
		gutterLineClass: 'gutterLine',
		headerClass: 'header',
		seperatorClass: 'divider',
		title: '',
		items: []
	};
	$.extend(settings, menuData); /* Merge Settings */
	
	function createMenu(e, data) {
		var menu = $('<ul class="' + settings.contextMenuClass + '"><div class="' + settings.gutterLineClass + '"></div></ul>').appendTo(document.body);
		if (data.title) {
			$('<li class="' + settings.headerClass + '"></li>').text(data.title).appendTo(menu); 
		}
		data.items.forEach(function(item) {
			if (item) {
				var rowCode = '<li><a href="javascript:;" onclick="' + item.click + '"><span></span></a></li>';  
				var row = $(rowCode).appendTo(menu); 
				if(item.icon){
					var icon = $('<img>');
					icon.attr('src', item.icon);
					icon.insertBefore(row.find('span'));
				}
				row.find('span').text(item.label);
			} 
			else { $('<li class="' + settings.seperatorClass + '"></li>').appendTo(menu); }
		});
		menu.find('.' + settings.headerClass ).text(data.title);
		return menu;
	}

  /* Right Click */
  this.bind('contextmenu', function(e) {
	console.log('highlighted entity ' + highlighted_entity_id);
	if(in_ent == 2){
		data = {
		  title: 'Client Menu',
		  items: [
			{label:'Follow Entity', icon:'cust_assets/js/context/icons/navigation-000-button-white.png', click:'FollowEntity(' + highlighted_entity_id + ')' },
			{label:'Move Entity', icon:'cust_assets/js/context/icons/arrow-move.png', click:'RegisterEntity(' + highlighted_entity_id + ')' },
		  ]
		};
	}
	if(in_ent == 3){
		data = {
		  title: 'NPC Menu',
		  items: [
				{label:'Follow Entity', icon:'cust_assets/js/context/icons/navigation-000-button-white.png', click:'FollowEntity(' + highlighted_entity_id + ')' },
				{label:'Move Entity', icon:'cust_assets/js/context/icons/arrow-move.png', click:'RegisterEntity(' + highlighted_entity_id + ')' },
				{label:'Kill Entity', icon:'cust_assets/js/context/icons/skull-sad.png', click:'KillEntity(' + highlighted_entity_id + ')' },
			]
		};
		// item = {label:'Follow Entity', icon:'cust_assets/js/context/icons/navigation-000-button-white.png', click:'FollowEntity(' + highlighted_entity_id + ')' };
		// data.items.push(item);
	}
	$( '.contextMenuPlugin').unbind( "mouseenter"); $( '.contextMenuPlugin').unbind( "mouseleave");
	
	$('.contextMenuPlugin').remove();

	if(in_ent == 0){ return; } 
	
    var menu = createMenu(e, data).show();
	
    var left = e.pageX + 5, /* nudge to the right, so the pointer is covering the title */
	top = e.pageY;
    if (top + menu.height() >= $(window).height()) { top -= menu.height(); }
    if (left + menu.width() >= $(window).width()) {  left -= menu.width(); } 

    // Create and show menu
    menu.css({zIndex:1000001, left:left, top:top}).bind('contextmenu', function() { return false; });

	/* If mouse leaves the context menu, destroy it */
	$('.contextMenuPlugin').bind( "mouseleave", function() { 
		$( '.contextMenuPlugin').unbind( "mouseenter"); 
		$( '.contextMenuPlugin').unbind( "mouseleave");
		$('.contextMenuPlugin').remove();
		in_context_menu = 0;
	});	 
	$('.contextMenuPlugin').bind( "mouseenter", function() { in_context_menu = 1; highlighted_entity_id = 0; });	
	
    // When clicking on a link in menu: clean up (in addition to handlers on link already)
    menu.find('a').click(function() { menu.remove(); });

    // Cancel event, so real browser popup doesn't appear.
    return false;
  });

  return this;
};

