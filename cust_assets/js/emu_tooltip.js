var cssAdded=false;
if (typeof $ToolTip=="undefined") {
	var $ToolTip = new function () {
		var head = document.getElementsByTagName("head")[0];
		var body = document.getElementsByTagName("body")[0];
		var tt,currentId;
		var items=[];
		var itemDiv;
		var MouseStartX = 0;
		var MouseStartY = 0;

		function createElement(type,p) {
			// type = html element type (ie: link, a, p)
			// p = array of attributes for type
			var newelement = document.createElement(type);
			if (p) {
				createObject(newelement, p);
			}
			return newelement;
		}

		function addElement(p,element, e) {
			return p.appendChild(element);
		}

		function addEvent(z,y,x) {
			if (window.attachEvent) {
				z.attachEvent("on"+y,x);
			} else {
				z.addEventListener(y,x,false);
			}
		}

		function createObject(ele,s) {
			for (var p in s) {
				if (typeof s[p]=="object") {
					if (!ele[p]) {
						ele[p] = {};
					}
					createObject(ele[p],s[p]);
				} else {
					ele[p] = s[p];
				}
			}
		}

		function $E(e) {
			if (!e) {
				e = event;
			}
			if (!e.button) {
				e._button = e.which ? e.which : e.button;
				e._target = e.target ? e.target : e.srcElement;
			}
			return e;
		}

		function onMouseOver(e) {
			e = $E(e);
			var t=e._target;

			if (t.nodeName!="A") {
				if (t.parentNode && t.parentNode.nodeName == "A") {
					t = t.parentNode;
				} else if (t.parentNode.parentNode && t.parentNode.parentNode.nodeName == "A") {
					t = t.parentNode.parentNode;
				} else {
					return;
				}
			}

			if ( !t.href.length || t.href.match(/post=1/i) ) {
				return;
			}

			var m = [];
			if (t.className) m['class'] = t.className;
			var v;
			var valid = 0;
			var thref = t.href.replace('%3A',':');

			//if (v = thref.match(/^http:\/\/(\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b)\/(.+)\/([^\?]+)\?([^\=]+)\=(.*)$/i))
			if (v = thref.match(/^http:\/\/([^\/]+)\/(.+)\/([^\?]+)\?([^\=]+)\=(.*)$/i))
			{
				m['host'] = v[1];
				m['page'] = v[2];
				m['file'] = v[3];
				m['type'] = v[4];
				m['id'] = v[5];
				//alert("Link Match: host " + m['host'] + " page " + m['page'] + " file " + m['file'] + " type " + m['type'] + " id " + m['id']);
				if (m['file'] == 'item.php' || m['file'] == 'spell.php'){
					valid = 1;
					
				}
			}
			if (v && valid == 1) {
				if (cssAdded == false)
				{
					addElement(head, createElement("link",{type:"text/css",href:"http://" + m['host'] + "/" + m['page'] + "/includes/tooltip.css",rel:"stylesheet"}));
					cssAdded=true;
				}
				t.title = '';	//remove the title attribute from items in the forums
				
				if (!t.onmouseover) {
					t.onmousemove=onMouseMove;
					t.onmouseout=onMouseOut;
				}
				MouseStartX = e.pageX;
				MouseStartY = e.pageY;
				displayToolTip(m);
			}
		}

		function onMouseMove(e) {
			e=$E(e);
			showAtCursor(e);
		}

		function onMouseOut(e) {
			tt = null;
			itemDiv.style.display='none';
		}

		function displayToolTip(m) {
			tt = 1;
	
			if (m['id']) {
				currentId = m['id'];
			} else {
				currentId = decodeURIComponent(m['name']);
			}
		
			var key = m['site'] + currentId + m['locale'] + m['source'] + m['type'];
			if (typeof items[key]=="object") { //If it's already in the items array
				showToolTip(items[key].tooltip) ;
			} else {
				if (!items[key]) {
					showLoading();
					requestToolTip(m);
				} else {
					showLoading();
				}
			}
		}

		function showToolTip(itemstr) {
			itemDiv.style.display="block";
			itemDiv.innerHTML = itemstr;
		}

		function showLoading() {
			itemDiv.style.display = 'block';
			itemDiv.innerHTML = "";
		}

		function requestToolTip(m) {
			var url = '';
			if (m['host'] && m['page'] && m['id']) {
				if (m['file']=='item.php') {
					url = "http://" + m['host'] + "/" + m['page'] + "/includes/tooltip.php?id=" + m['id'] + "&type=item";
				}
				else if (m['file']=='spell.php') {
					url = "http://" + m['host'] + "/" + m['page'] + "/includes/tooltip.php?id=" + m['id'] + "&type=spell";
				}
			}

			if (url != '')
			{
				getToolTip(url);
			}
		}

		function getToolTip(url) {
			addElement(head,createElement("script",{type:"text/javascript",src:url}));
		}

		function showAtCursor(e) {
			var obj = itemDiv;
			var maxX;
			var maxY;
			obj.style.position = "absolute";
			obj.style.display = "block";
			if (document.all && !window.opera) {
				if (document.documentElement && typeof document.documentElement.scrollTop != undefined) {
					maxX = document.documentElement.clientWidth + document.documentElement.scrollLeft;
					maxY = document.documentElement.clientHeight + document.documentElement.scrollTop;
					y = event.clientY + document.documentElement.scrollTop;
					x = event.clientX + document.documentElement.scrollLeft;
				} else {
					y = event.clientY + document.body.scrollTop;
					x = event.clientX + document.body.scrollLeft;
				}
			} else {
				if(document.body.scrollTop) {
					maxX = window.innerWidth + document.body.scrollLeft;
					maxY = window.innerHeight + document.body.scrollTop;
				} else {
					maxX = window.innerWidth + document.documentElement.scrollLeft;
					maxY = window.innerHeight + document.documentElement.scrollTop;
				}
				if (e)
				{
					y = e.pageY;
					x = e.pageX;
				}
				else
				{
					y = MouseStartY;
					x = MouseStartX;
				}
			}

			var divW = parseInt(obj.offsetWidth);
			var divH = parseInt(obj.offsetHeight);
			divW = divW ? divW : 400;
			divH = divH ? divH : 100;

			if (maxX && maxY) {
				while (x + divW > (maxX - 10) && x > 0) {
					x = x - (divW + 10);
				}

				while (y + divH > (maxY - 25) && y > 0) {
					y = y - 1;
				}
			}

			if (document.body.style.marginTop) y = y - parseInt(document.body.style.marginTop.replace('px',''));

			obj.style.left = x + 15 +"px";
			obj.style.top = y + 15 +"px";
		}

		this.registerItem=function(obj) {
			var site = obj.site;
			var id;
		

			if (obj.key) {
				id = obj.key;
			} else if (obj.id) {
				id = obj.id;
			} else {
				id = '1001';
			}

			var locale = typeof obj.locale != 'undefined' ? obj.locale : 'enUS';
			var source = typeof obj.source != 'undefined' ? obj.source : 'live';
			var key = site + id + locale + source;
			items[key] = obj;
			if (tt == 1 && id == currentId) {
				showToolTip(items[key].tooltip);
				showAtCursor(0);
			}
		}

		function onPageShow(e) {
			if (e.persisted) {
				tt = null;
				itemDiv.style.display='none';
			}
		}	

		function init() {
			if (!document.getElementById('tmpItemFrm')) {
				addElement(body, createElement("div",{id:'tmpItemFrm'}));
				document.getElementById('tmpItemFrm').style.display = 'none';
			}

			itemDiv = document.getElementById('tmpItemFrm');
			addEvent(document,"mouseover",onMouseOver);
			addEvent(window,"pageshow",onPageShow);
		}
		init();
	}
}
