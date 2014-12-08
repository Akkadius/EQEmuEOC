var ajax_tooltipObj = false; 
var ajax_tooltipObj_iframe = false;
var currentTooltipObject = false;  

function ajax_showTooltip(e, externalFile, inputObj, iscloseable, theight, twidth){
   set_height = theight;
   set_width = twidth;
   
	if(!ajax_tooltipObj)	/* Tooltip div not created yet ? */
	{
		ajax_tooltipObj = document.createElement('DIV');
		ajax_tooltipObj.style.position = 'absolute';
		ajax_tooltipObj.id = 'ajax_tooltipObj';		
		
		document.body.appendChild(ajax_tooltipObj);

		var leftDiv = document.createElement('DIV');	/* Create arrow div */
		leftDiv.className='ajax_tooltip_arrow';
		leftDiv.id = 'ajax_tooltip_arrow';
		ajax_tooltipObj.appendChild(leftDiv);
		
		var contentDiv = document.createElement('DIV'); /* Create tooltip content div */
		contentDiv.className = 'ajax_tooltip_content';
		ajax_tooltipObj.appendChild(contentDiv); 
		contentDiv.id = 'ajax_tooltip_content';
		contentDiv.style.marginBottom = '15px';
		
		if(twidth > 0){ contentDiv.style.width = twidth + 'px'; }
		else{ contentDiv.style.width = 'auto'; }
		if(theight > 0){ contentDiv.style.height = theight + 'px'; } 
		else{ contentDiv.style.height = 'auto'; }  
		
		// Creating button div
		if(iscloseable == 1){ 
			var buttonDiv = document.createElement('DIV');
			buttonDiv.style.cssText = 'position:absolute;left:50%;bottom:20px;text-align:center;font-size:0.8em;height:15px;z-index:10000000;background: url(images/widgetBg.png) repeat;  border-radius: 10px;';
			buttonDiv.innerHTML = '<a href="#" onclick="ajax_hideTooltip();return false" class="btn btn-default btn-xs" style="width:80px;"><i class="fa fa-times-circle"></i> Close</a>';
			ajax_tooltipObj.appendChild(buttonDiv);
		}	
	}
	/* Find position of tooltip */
	ajax_tooltipObj.style.display='block';
	$('.ajax_tooltip_content').html('<br><i class="fa fa-spinner fa-spin" style="font-size:80px">');
	$.ajax({
		url: url,
		context: document.body,
		type: "GET"
	}).done(function(html) {
		$('.ajax_tooltip_content').html(html);  
		ajax_positionTooltip(e, inputObj); 
	});
}

function ajax_positionTooltip(e, inputObj) {
	if(inputObj){
		var offset = inputObj.offset();
		var leftPos = (offset.left + inputObj.outerWidth()); 
		var topPos = offset.top;
	}else{ 
	   var leftPos = e.clientX;
	   var topPos = e.clientY;
	} 
	var tooltipHeight = document.getElementById('ajax_tooltip_content').offsetHeight +  document.getElementById('ajax_tooltip_arrow').offsetHeight;
	var tooltipWidth = $('#ajax_tooltip_content').width(); 
   
	/* Width Testing */
	// console.log('Screen width ' + $( document ).width());
	// console.log('leftpos ' + leftPos);
	// console.log('Tooltip Width ' + tooltipWidth);
	// console.log('Client X ' + e.clientX);

	/* Height Testing */
	// console.log('Screen height ' + $( window ).height());
	// console.log('topPos ' + topPos);
	// console.log('Tooltip Height ' + tooltipHeight);
	// console.log('Client Y ' + e.clientY); 
    
	/* We want to make sure we don't fall off the screen width wise */
	// console.log('test ' + ($( document ).width() - e.clientX)); 
	if(tooltipWidth > ($( window ).width() - e.clientX - 100)){ 
		// console.log('Falling off screen width'); 
		leftPos = leftPos - tooltipWidth - 100;
	}else{
		// console.log('On screen');
	}
	
	// console.log('test ' + ($( window ).height() - e.clientY)); 
	if(tooltipWidth > ($( window ).height() - e.clientY - 50)){  
		// console.log('Falling off screen height'); 
		// console.log('Difference ' + (tooltipHeight - ($( window ).height() - e.clientY - 150))); 
		topPos = topPos - (tooltipHeight - ($( window ).height() - e.clientY - 50));
	}else{
		// console.log('On screen'); 
	}
   
   ajax_tooltipObj.style.left = leftPos + 'px';
   ajax_tooltipObj.style.top = topPos + 30 + 'px';   
} 

function ajax_hideTooltipSlow(timer){
	TooltipHide=setInterval(function(){ajax_hideTooltipAction();},timer);
}

function ajax_hideTooltipAction() {
	window.clearInterval(TooltipHide);
	ajax_tooltipObj.style.display='none';
}

function ajax_hideTooltip() {
	ajax_tooltipObj.style.display='none';
}