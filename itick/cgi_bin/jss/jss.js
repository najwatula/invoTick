// by Jupiter & Najwa & Tula (2015/03/25 00:00)
//***********************************************
if (!jss){var jss={}};

window.onload=function(){if(typeof(window.jss.Init)=='function'){jss.Parsec();jss.Init();}}

// Data Comun General
//***********************************************
jss.dataParse=function(obj, param){
	if (typeof param.renderTo == 'object'){obj.renderTo=param.renderTo}
	else{obj.renderTo= document.getElementById(param.renderTo) || document.body}
	if (typeof param.content == 'object'){obj.content=param.content}
	else{obj.content= document.getElementById(param.content) || ''}

	if(obj.renderTo.id == obj.content.id){obj.content=obj.renderTo.childNodes}

	obj.id = param.id || 'jss-'+Math.floor(Math.random()*1001);
	obj.fireEvent = param.fireEvent || '';
	obj.putTo = param.putTo || '';	
	obj.values = param.values || '';
	obj.columns = param.columns || '';
	obj.types = param.types || '';	
	obj.title = param.title || '';
	obj.submitTo = param.submitTo || '';
	obj.type = param.type || 'Panel';
	obj.html = param.html || '';
	obj.url = param.url || '';
	obj.collapseMode = param.collapseMode || '';
	obj.collapsed = param.collapsed || false;
	obj.iFrame = param.iFrame || {};
	obj.style=param.style || {};
	obj.style.overflow = obj.style.overflow || '';
	obj.style.width = obj.style.width || '';
	obj.style.height = obj.style.height || '100%';	
	obj.button=param.button || false;
	obj.tabItems=param.items || false;
}

// jssPanel 
//***********************************************
jss.Panel = function(param){
	var builderHTML='', n, vth=this;

	//Prepara las Variables
	//*********************************************
	jss.dataParse(this, param)
	
	// Crea el panel y lo mete en el contenedor
	//*********************************************
	this.renderTo.style.verticalAlign='top';
	
	//table
	this.table=document.createElement('table');
	this.table.className='jss-Panel';
	this.table.id=this.id;
	for (st in this.style){this.table.style[st]=this.style[st];}
 	this.originalHeight=this.table.style.height;
	this.originalWidth=this.table.style.width;
	
	//caption table
	this.table.insertRow(0);
	this.table.rows[0].insertCell(0);
	this.table.rows[0].style.height='14pt';
	this.caption=document.createElement('table');
    this.caption.className='jss-Caption';
	this.caption.style.width='100%';this.caption.style.borderCollapse='collapse';this.caption.style.cellSpacing=0;

	this.caption.insertRow(0);this.caption.rows[0].insertCell(0);
	this.caption.rows[0].cells[0].innerHTML=this.title;
	this.table.rows[0].cells[0].appendChild(this.caption);
	
    //container
	this.table.insertRow(1);this.table.rows[1].insertCell(0);
	this.table.rows[1].cells[0].vAlign='top';
	this.container=document.createElement('div');
	this.table.rows[1].cells[0].appendChild(this.container);
	this.container.style.height='100%';	this.container.style.width='100%';
	this.container.style.overflow=this.style.overflow;

    //boton collapse
	if (this.collapseMode){
		this.caption.rows[0].insertCell(1);
	    this.bcollapse=this.caption.rows[0].cells[1];
		this.bcollapse.style.width='18px';
	    this.bcollapse.className=this.collapseMode;
	    this.bcollapse.onclick=function(el){vth.collapse(el)}
	    if (this.collapseMode=='jss-Down'){this.collapsed=true}
	}
	if (this.collapsed){
		this.container.style.display = 'none';
   		this.table.style.height='16pt';
	}

    //boton
	if (this.button){
		this.caption.rows[0].insertCell(1);
	    this.pButton=this.caption.rows[0].cells[1];
		this.pButton.style.width='18px';
		this.pButton.innerHTML=this.button;
	}

	// Mete el contenido
	//*********************************************
	if (param.replace){this.renderTo.replaceChild(this.table,param.replace)}
	else{this.renderTo.appendChild(this.table)};
   	this.render();
    //if (this.style.overflow=='auto' || this.style.overflow=='scroll'){this.container.style.width=this.container.clientWidth;this.container.style.height=this.container.clientHeight;}
	return this;
}

jss.Panel.prototype.close=function(){
	if (this.table){this.table.parentNode.removeChild(this.table)};
	jss.Disable(false);
}
jss.Panel.prototype.setTitle=function(title){
	if (title){this.caption.rows[0].cells[0].innerHTML=title;}
	return this.caption.rows[0].cells[0].innerHTML;
}
jss.Panel.prototype.setHtml=function(html){
	if (html){
	  	this.container.innerHTML=html;
	}
	this.html = this.container.innerHTML;
	return this.html;
}

jss.Panel.prototype.render=function(el){
	jss.render(this);
}

// Render Panel
//*********************************************
jss.render=function(obj){
	var contentHTML='';

	if (typeof(obj.content)=='object'){
		var myLen=obj.content.length;
		for (var n=0;n<myLen;n++){
			obj.container.appendChild(this.content[0]);
		}
		obj.content='';
		return;
	}
	if (obj.html){obj.container.innerHTML=obj.html;}
	if (obj.url){obj.container.innerHTML=loadAjax(obj.url);}
	if (obj.iFrame.id){
		try{var i=document.createElement('<iframe name="'+(obj.iFrame.id)+'" id="'+(obj.iFrame.id)+'"></iframe>');}
		catch(err){var i=document.createElement('iframe'); i.name=(obj.iFrame.id); i.id=(obj.iFrame.id);}
		i.frameBorder=0;
		i.scrolling=obj.iFrame.scroll || 'no';
		i.width='100%';//i.height='100%';
		i.height=obj.container.parentNode.clientHeight-4;
		if(obj.iFrame.url){i.src=obj.iFrame.url;}
		if(obj.type=='Tab'){obj.container.innerHTML='';}
		obj.container.appendChild(i);
	}
}


// jssTab
//***********************************************
jss.Tab = function (param){
	var builderHTML, vth=this, n;
	param.type='Tab';
	jss.dataParse(this, param)

	this.table=document.createElement('table');
	for (var st in this.style){this.table.style[st]=this.style[st]}	
	this.table.className="jss-Tab";
	this.table.cellSpacing=2;
	this.table.id=this.id;

	// Construye el Tab
	//*********************************************
	if (this.tabItems){
		this.table.insertRow(0);
		this.table.rows[0].style.height='1%';
		for (n=0;this.tabItems[n];n++){
			this.table.rows[0].insertCell(n);
			this.table.rows[0].cells[n].innerHTML=this.tabItems[n].title;
			this.table.rows[0].cells[n].style.width='10%';
			this.table.rows[0].cells[n].noWrap=true;
			this.table.rows[0].cells[n].className='jss-TabInactive';
			this.table.rows[0].cells[n].onclick=function(el){vth.render(el)};
		}
		this.table.rows[0].insertCell(n);
		this.table.rows[0].cells[n].style.width='10%';

		this.table.insertRow(1);
		this.table.rows[1].style.height='99%';		
		this.table.rows[1].insertCell(0);
		this.table.rows[1].cells[0].colSpan=n+1;
		this.container=document.createElement('div');
		this.container.className='jss-Panel';
		this.container.style.height='100%';	this.container.style.width='100%';
		this.container.style.overflow=this.style.overflow;
		this.table.rows[1].cells[0].appendChild(this.container);
		this.tabActive=0;
		if (param.replace){this.renderTo.replaceChild(this.table,param.replace)}
		else{this.renderTo.appendChild(this.table)};
		this.render(0);
	}
	return this;
}

// Render Tab
//*********************************************
jss.Tab.prototype.render=function(el){
	if (typeof el=='number'){qTab=el}
	else{
		var evento = el || window.event;
		qTab=evento.srcElement ? evento.srcElement.cellIndex : evento.target ? evento.target.cellIndex : 0;
	}
	this.table.rows[0].cells[this.tabActive].className='jss-TabInactive';
	this.tabActive=qTab;
	this.table.rows[0].cells[this.tabActive].className='jss-TabActive';
	this.url=this.tabItems[this.tabActive].url;
	this.html=this.tabItems[this.tabActive].html;	
	this.iFrame.id=this.tabItems[this.tabActive].iFrame.id;
	this.iFrame.url=this.tabItems[this.tabActive].iFrame.url;
	this.iFrame.scroll=this.tabItems[this.tabActive].iFrame.scroll;
	this.container.parentNode.height=(this.container.parentNode.parentNode.parentNode.parentNode.parentNode.clientHeight-36);
	//alert(this.container.parentNode.parentNode.parentNode.parentNode.id);	
	jss.render(this);
}

// Colapsa el panel
//*********************************************	
jss.Panel.prototype.collapse=function(el){
	//var evento = el || window.event;
	//var clase=evento.srcElement || evento.target || el;
	switch (this.bcollapse.className){
		case 'jss-Down':
			this.container.style.display = '';
			this.table.style.height = this.originalHeight;
			this.table.style.width = this.originalWidth;
			this.bcollapse.className ='jss-Up';
			break;
		case 'jss-Up':
			this.container.style.display = 'none';
      		this.table.style.height='16pt';
			this.bcollapse.className = 'jss-Down';
			break;
		case 'jss-Left':
			this.caption.rows[0].cells[0].style.display='none';
			this.container.style.display = 'none';
			this.table.rows[1].cells[0].style.backgroundColor='#666D7A';			
			this.table.style.width = '20pt';
			this.bcollapse.className = 'jss-Right';
			break;
		case 'jss-Right':
			this.caption.rows[0].cells[0].style.display='';
			this.container.style.display = '';
			this.table.rows[1].cells[0].style.backgroundColor='';			
			this.table.style.width = this.originalWidth;
			this.table.style.height = this.originalHeight;
			this.bcollapse.className = 'jss-Left';
		break;						
	}	
}

// jssWindow
//***********************************************
jss.Window = function(param){
	var vth=this;
	jss.Disable(true);
	param.type='Window';
	param.collapseMode='jss-Close';
	param.style.position='absolute';
	
	this.panel= new jss.Panel(param);
	var windowHeight = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight;
	this.top= param.top ||  parseInt((windowHeight - this.panel.table.offsetHeight)/2) + document.body.scrollTop;
	this.left= param.left || parseInt((document.body.clientWidth - this.panel.table.offsetWidth)/2) + document.body.scrollLeft;
	
	//this.panel.table.style.position='absolute';
	this.panel.table.className ='jss-Window';
	this.panel.table.style.top=this.top;
	this.panel.table.style.left=this.left;
	this.panel.container.style.backgroundColor='#F5F5F3';
	
	this.panel.bcollapse.onclick=function(el){vth.panel.close(el)};
	
	this.panel.caption.style.cursor='move';
	this.panel.caption.onmousedown=function(el){vth.moveStart(el)};

	return this;
}

// jssWindow Move
//***********************************************
jss.Window.prototype.moveStart= function (el){
	var evento = el || window.event;
	var vth=this;
	
  	if(window.scrollX){
	   this.iniLeftCursor= evento.clientX+window.scrollX;
	   this.iniTopCursor= evento.clientY+window.scrollY;     
  	}else{
  	  this.iniLeftCursor=evento.clientX+document.documentElement.scrollLeft+document.body.scrollLeft;
  	  this.iniTopCursor=evento.clientY+document.documentElement.scrollTop+document.body.scrollTop;  
  	}
  	this.iniLeft=parseInt(this.panel.table.style.left);
  	this.iniTop=parseInt(this.panel.table.style.top);
  
	if (document.getElementById('jssDisable')){
	    document.getElementById('jssDisable').onmousemove= function(el){vth.move(el)}
	    document.getElementById('jssDisable').onmouseup= function(el){vth.moveStop(el)}    
	}
  	this.panel.table.onmousemove= function(el){vth.move(el)}  
 	this.panel.table.onmouseup= function(el){vth.moveStop(el)}	
	
	//obj.onmouseout= function(el){v.move(el)}
  	this.panel.table.style.zIndex=0;
}
//************************************************
jss.Window.prototype.move= function (el){
    var evento = el || window.event;
  if(window.scrollX){
	   var posX= evento.clientX+window.scrollX;
	   var posY= evento.clientY+window.scrollY;     
  }else{
    var posX=evento.clientX+document.documentElement.scrollLeft+document.body.scrollLeft;
    var posY=evento.clientY+document.documentElement.scrollTop+document.body.scrollTop;  
  }    
    this.panel.table.style.left =(posX + this.iniLeft- this.iniLeftCursor)+'px';
    this.panel.table.style.top = (posY + this.iniTop - this.iniTopCursor)+'px';       
}
//************************************************
jss.Window.prototype.moveStop= function (el){
	if (document.getElementById('jssDisable')){
    obj=document.getElementById('jssDisable');
    obj.onmousemove=null;
    obj.onmouseup=null;
  }  

  this.panel.table.onmousemove=null;
  this.panel.table.onmouseup=null;  
}

// Alert
//***********************************************
jss.Alert = function(param){
	param.msg= param.msg || '';
	this.onReply= param.onReply|| '';
	this.yes= param.yes || '';
	this.no= param.no || '';
	this.reply='';
	this.type =param.type || 'Alert';
	param.style={width:300,height:130};
	var builderHTML;
	builderHTML='<table style="width: 100%; height: 100%" class="jss-Table">';
	builderHTML+='<tr><td style="height: 60%; overflow: auto" colspan="2" align="center">'+(param.msg)+'</td></tr><tr>';
	if (this.type=='Alert'){
		builderHTML+='<td align="center" colspan="2"><input class="jss-Boton" id="jss-Yes" type="button" value="Ok."></td></tr></table>';	
	}
	else if (this.type=='Confirm'){
		builderHTML+='<td align="center"><input class="jss-Boton" id="jss-Yes" type="button" value="yes"></td>';
		builderHTML+='<td align="center"><input class="jss-Boton" id="jss-No" type="button" value="no"></td></tr></table>';
	}
	else if (this.type=='Input'){
		builderHTML+='<td align="center">input class="jss-FieldAuto" maxlength="250" id="jss-Input"></td>';
		builderHTML+='<td align="center"><input class="jss-Boton" id="jss-Yes" type="button" value="Accept"></td>';
		this.yes='document.getElementById(\'jss-Input\').value';
	}	
	param.html=builderHTML;
	this.window = new jss.Window(param);

	var vth=this;
	document.getElementById('jss-Yes').onclick= function() {vth.getReply('Yes')};
	if (this.type=='Confirm'){
		document.getElementById('jss-No').onclick= function() {vth.getReply('No')};
	}
	this.window.panel.bcollapse.onclick=function(){vth.getReply('Cancel')};
}

jss.Alert.prototype.getReply=function(option){
	this.reply=option;
	this.window.panel.close();
	if(this.onReply){var qEval=this.onReply+"('"+option+"')"; eval(qEval);}
	if(this.yes && this.reply=='Yes'){var qEval=(this.yes); try{eval(qEval)} catch(err){alert(err)}};
	if(this.no && this.reply=='No'){var qEval=(this.no); try{eval(qEval)} catch(err){alert(err)}};
	
	return this.reply;
}

// Confirm
//***********************************************
jss.Confirm = function(param){
	param.type='Confirm';
	new jss.Alert(param);
}

// Input
//***********************************************
jss.Input = function(param){
	param.type='Input';
	new jss.Alert(param);
}

// jssDisable
//***********************************************
jss.Disable = function(q){
	if (document.getElementById('jssDisable')){
		document.getElementById('jssDisable').parentNode.removeChild(document.getElementById('jssDisable'));
	}
	
	if (q){
		var obj = document.createElement('div');
		obj.id='jssDisable';
		obj.className='jss-Disable';
		obj.style.height='100%'; //getHeight();
		document.body.appendChild(obj);
	}
}

// jssPickItem
//***********************************************
jss.PickItem= function (param){
	param.style.overflow='auto';
	this.window= new jss.Window(param);
	var objContenedor=this.window.panel.container;
    objContenedor.style.height=param.style.height-22;
    
	this.grid=new jss.Grid({
		renderTo:(objContenedor),
		fireEvent: (param.fireEvent || ''),
		columns: (param.columns || ''),
		values: (param.values || '')
	})	

	return this;
}

// Form
//***********************************************
jss.Form=function(param){
	jss.dataParse(this, param);
	this.render();
	return this;
}

// Form Render
//***********************************************
jss.Form.prototype.render=function(){
	var maxCols=2, nCont=0, builderHTMLi='';
	// Form
	this.title=this.title+' / '+this.columns[0]+': '+this.values[0][0];
	var builderHTML='<table class="jss-Table" style="font-weight: bold; font-style: italic; color: #005900; width:'+(this.style.width)+'">';
	builderHTML+='<col width="10%" align="right"><col width="40%"><col width="10%" align="right"><col width="40%">';
	builderHTML+='<tr class="jss-Bar"><td align="left" colspan="3">&nbsp'+this.title+'</td>';
	builderHTML+='<td align="right"></td></tr>';
	for (var n=1; this.columns[n]; n+=2){
		builderHTML+="<tr>";
		for(var m=0; m<2; m++){
			if (this.columns[(n+m)]){
				var valor=this.values[0][(n+m)] || '';
				if (this.columns[(n+m)]=='image'){
					builderHTMLi+='<img alt="" style="width: 50%" src="data:image/jpeg;base64,'+(valor)+'">';
				}else{
				    builderHTML+='<td align="right">'+this.columns[(n+m)].toCap()+':</td>';
				    builderHTML+='<td><input class="jss-FieldAuto" value="'+valor+'" name="'+this.columns[(n+m)]+'"></td>';
				}
			}
		}
		builderHTML+="</tr>";
	}
	builderHTML+='</table>';
    //builderHTML='<table style="width: 100%"><tr><td style="width: 80%">'+builderHTML+'</td><td style="width: 20%; text-align: center">'+builderHTMLi+'</td></tr></table';
	this.renderTo.innerHTML=builderHTML;
	return this;
}

// Grid
//***********************************************
jss.Grid=function(param){
	jss.dataParse(this, param);
	if(isNumeric(param.group)){this.group=param.group;}
	else{this.group='x';}
	this.totals=param.totals || false;
	if (this.dataCheck()){this.render()}
	return this;
}

jss.Grid.prototype.dataCheck=function(){
	if(undefined==this.values[0]){return false};
	if(this.columns.length==0){
		if(this.values.length==0){return false}
		if(!this.values[0]){
			var tmp=jsonToArray(this.values);
			this.columns=tmp.columns;
			this.values=tmp.values;
		}
		else{
			this.columns=new Array(this.values[0].length);
			for (var n=0; n<this.values[0].length; n++){this.columns[n]=n+1};
		}
	}
	return true;
}

// Return 2 dimensions Array columns & values
//***********************************************
jsonToArray = function(json){ 
    var arrOut = {columns:[],values:[]};
    for(var k in json){
    	for(var i in json[k]){
	    	var c=0;
	    	arrOut.values[i]=new Array();
	    	for(var j in json[k][i]){
	    		arrOut.columns[c]=j;
	    		arrOut.values[i][c]=json[k][i][j];
	    		c++;
	    	}
    	}
    }
    return arrOut;
}

// Grid Make
//***********************************************
jss.Grid.prototype.render=function(){
	var vth=this;
	var cols=this.columns.length || 0;
	var rows=this.values.length
	var size=new Array(cols);
	var totals=new Array(cols);
	var subTotals=new Array(cols);
	
	for (var n=0; n<cols; n++){size[n]=0;totals[n]=0;subTotals[n]=0;}

	var table=document.createElement('table');
	table.className='jss-TableList';
	table.style.width='100%';
	table.onclick=function(el){vth.retVal(el)};
		
	// Data content
	//*********************************************	
	var contenido='';
	var group=this.values[0][this.group];	
	for (var n=0; n<rows ; n++){
		if (isNumeric(this.group)){
			if(group!=this.values[n][this.group]){
				table.insertRow(-1);
				var nRow=table.rows.length-1;
				table.rows[nRow].style.fontWeight='bold';
				table.rows[nRow].insertCell(0);
				table.rows[nRow].cells[0].colSpan=this.group+1;
				for (var m=this.group+1; m<cols ; m++){
				    if (isDecimal(subTotals[m])){subTotals[m]=subTotals[m].toFix(2)}
					table.rows[nRow].insertCell(m-this.group);
					table.rows[nRow].cells[m-this.group].style.textAlign='right';					
					if(subTotals[m]>0){table.rows[nRow].cells[m-this.group].innerHTML=subTotals[m];}
					subTotals[m]=0;					
				}
				table.rows[nRow].cells[0].innerHTML=group;
				group=this.values[n][this.group];
			}
		}

		table.insertRow(-1);
		var nRow=table.rows.length-1;
		for (var m=0; m<cols ; m++){
			table.rows[nRow].insertCell(m);
			if (isNumeric(this.values[n][m])){
				table.rows[nRow].cells[m].style.textAlign='right';
				totals[m]+=toNumber(this.values[n][m]);
				subTotals[m]+=toNumber(this.values[n][m]);				
			}
			else{
				//totals[m]+=1;
				//subTotals[m]+=1;
			}
			if (isDecimal(this.values[n][m])){table.rows[nRow].cells[m].innerHTML=this.values[n][m].toFix(2);}
			else {table.rows[nRow].cells[m].innerHTML=this.values[n][m];}
			if(this.values[n][m]){if (size[m] < this.values[n][m].length){size[m] = this.values[n][m].length;};}
		}
	}	
	
	// Column Size
	var totalSize=0;
	for (var n=0; this.columns[n]; n++){totalSize+=size[n]}
	for (var n=0; this.columns[n]; n++){
		size[n]=parseInt(size[n]*(5*cols)/totalSize)+5;
	}

	// Header	
	table.insertRow(0);
	table.rows[0].className='jss-Bar';
	for (var n=0; n<cols; n++){
		table.rows[0].insertCell(n);
		table.rows[0].cells[n].style.width=(size[n]+'%');
		
		var caption=document.createElement('table');
		caption.style.borderCollapse='collapse';
		caption.style.width='100%';
		caption.insertRow(0);
		caption.rows[0].className="jss-Bar";
		caption.rows[0].insertCell(0);
		caption.rows[0].cells[0].innerHTML=this.columns[n];
		caption.rows[0].insertCell(1);
		caption.rows[0].cells[1].className="jss-BarGrid";
		caption.rows[0].cells[1].style.width='12px';
		
		table.rows[0].cells[n].appendChild(caption);
	}

	// Footer
	if (isNumeric(this.group)){
		table.insertRow(-1);
		var nRow=table.rows.length-1;
		table.rows[nRow].style.fontWeight='bold';
		table.rows[nRow].insertCell(0);
		table.rows[nRow].cells[0].colSpan=this.group+1;
		for (var m=this.group+1; m<cols ; m++){
		    if (isDecimal(subTotals[m])){subTotals[m]=subTotals[m].toFix(2)}
			table.rows[nRow].insertCell(m-this.group);
			table.rows[nRow].cells[m-this.group].style.textAlign='right';					
			if(subTotals[m]>0){table.rows[nRow].cells[m-this.group].innerHTML=subTotals[m];}
		}
		table.rows[nRow].cells[0].innerHTML=group;
	}
	if (vth.totals){
		table.insertRow(-1);
		var nRow=table.rows.length-1;
		table.rows[nRow].style.fontWeight='bold';
		for (var n=0; n<cols; n++){
			if (isDecimal(totals[n])){totals[n]=totals[n].toFix(2)}
			table.rows[nRow].insertCell(n);
			table.rows[nRow].cells[n].style.textAlign='right';
			if(totals[n]>0){table.rows[nRow].cells[n].innerHTML=totals[n];}
		}
	}
	this.renderTo.appendChild(table);
	this.table=table;
}

// Grid lineClick
//***********************************************
jss.Grid.prototype.retVal=function(el){
	var evento = el || window.event;
	var myCell= evento.srcElement || evento.target;
	if (myCell){
		var myRow=myCell.parentNode;
		if (myRow.rowIndex==0){
			var masterCell=myRow.parentNode.parentNode.parentNode;
			var masterRow=masterCell.parentNode;
			var masterTable=masterRow.parentNode.parentNode;
			for (n=0;n<masterRow.cells.length;n++){masterRow.cells[n].lastChild.lastChild.lastChild.cells[1].className='jss-BarGrid';}
			myRow.cells[1].className='jss-BarGridAct';
			sortTable(masterTable, masterCell.cellIndex,'asc', this.totals)
		}
		else{
			if (this.fireEvent){
				var qeval=this.fireEvent+'(myRow)';
				eval(qeval);
			}
		}
	}
}

// Sort Table
//***********************************************
function sortTable(tName, index, order, footer){
	if (typeof tName=='string'){var myTable=document.getElementById(tName) || false;}
	else{var myTable=tName}
	if (!myTable.rows){return false};
	var order=order || 'asc';
	var n=0, m=0, x=0;
	var rowLen=myTable.rows.length-1;
	if (footer){rowLen=rowLen-1};
	var cellLen=myTable.rows[1].cells.length;
	var values=new Array(rowLen);
	for (n=0; n < rowLen; n++){
		values[n]=new Array(cellLen);
		for (m=0; m <cellLen ; m++){
			x= (m==0) ? index : (m==index) ? 0 : m;
			values[n][m]=myTable.rows[n+1].cells[x].innerHTML ||'';
		}
	}
	values.sort();
	if (order!='asc'){values.reverse()}
	for (n=0; n < rowLen ; n++){
		for (m=0; m<cellLen; m++){
			x= (m==0) ? index : (m==index) ? 0 : m;
			myTable.rows[n+1].cells[x].innerHTML=values[n][m];
		}
	}
	return true;
}

// Menu
//***********************************************
function jssMenuCollapse(el){
	var evento = el || window.event;
	var myCell= evento.srcElement || evento.target || el;
	if (myCell){
		var myRow=myCell.parentNode;
		var myTable=myRow.parentNode;
		while(myTable.tagName.toLowerCase()!='table'){myTable=myTable.parentNode}
		if (myRow.cells[0].className=='jss-Up'){
			var display='none';
			myRow.cells[0].className='jss-Down';
		}else{
			var display='';
			myRow.cells[0].className='jss-Up';		
		}
		for (n=1; n < myTable.rows.length; n++){
			myTable.rows[n].style.display=(display);
		}
		myTable.style.display='';
	}
}

// Ajax
//***********************************************
function loadAjax (pageName, data){
  	if (!pageName){return 'i need url';}
  	
	var rnd=Math.floor(Math.random()*1001);
	var ajaxProc=new XMLHttpRequest();
	
	if(data){
		ajaxProc.open('POST', pageName, false);;
		ajaxProc.setRequestHeader("Content-type","application/x-www-form-urlencoded"); data+='&rnd='+rnd;
	}
	else{
		ajaxProc.open('GET', pageName, false);;
		pageName=pageName.replace('?','?rnd='+rnd+'&');
	}
	
	try{
	    ajaxProc.send(data);
	    valRet=ajaxProc.responseText;
	} 
	catch(e){
	    valRet= pageName +'<br>' + data + '<br>' + e;
	}
	return valRet;

}

// Resize image
//***********************************************
function resize(imgObj, maxh, maxw) {
	imgObj.style.display='';

 	var ratio = maxh/maxw;
  	if (imgObj.height/imgObj.width > ratio){
     	// height is the problem
      	imgObj.width = Math.round(imgObj.width*(maxh/imgObj.height));
      	imgObj.height = maxh;
  	} else {
    	// width is the problem
      	imgObj.height = Math.round(imgObj.height*(maxw/imgObj.width));
      	imgObj.width = maxw;
  	}
  	return true;
}

// A-Z Bar
//***********************************************
jss.azBar=function (param){
	jss.dataParse(this, param);
	var vth=this;
	var az=document.createElement('table');
	az.className='jss-TableCell';
	az.style.width='100%';
	az.insertRow(0);
	for (var ch=65; ch<91; ch++){
		az.rows[0].insertCell(ch-65);
		az.rows[0].cells[ch-65].style.width='3%';
		az.rows[0].cells[ch-65].style.textAlign='center';
		az.rows[0].cells[ch-65].style.borderColor='#636B7E';
		az.rows[0].cells[ch-65].innerHTML=String.fromCharCode(ch);
		az.rows[0].cells[ch-65].onclick=function(el){vth.retVal(el)};
	}
	this.renderTo.appendChild(az);
}
jss.azBar.prototype.retVal=function (el){
	var evento = el || window.event;
	var valRet= evento.srcElement ? evento.srcElement.innerText : evento.target.textContent;
	if (valRet){
		if(document.getElementById(this.putTo)){document.getElementById(this.putTo).value=valRet};
		if (this.fireEvent){eval(this.fireEvent)}
	}
}
// get Height
//***********************************************
function getHeight() {
	return Math.max(
		window.innerHeight ? window.innerHeight : 0,
		document.documentElement ? document.documentElement.clientHeight : 0,
		document.body ? document.body.clientHeight : 0
	);
}
// get Width
//***********************************************
function getWidth() {
	return Math.max(
		window.innerWidth ? window.innerWidth : 0,
		document.documentElement ? document.documentElement.clientWidth : 0,
		document.body ? document.body.clientWidth : 0
	);
}
// getPos xy
//***********************************************
function getPos(obj){
    var topValue= 0,leftValue= 0;
    while(obj){
		leftValue+= obj.offsetLeft;
		topValue+= obj.offsetTop;
		obj= obj.offsetParent;
    }
    return {left: leftValue, top: topValue};
}
// Decimal-Numeric?
//***********************************************
function isDecimal(valNum){
  if(!valNum){return false;}
	return isNumeric(valNum) & valNum.toString().indexOf('.') >0;
}
function isNumeric(valNum) {
  	return !isNaN (valNum-0);
} 
function toNumber(valNum){
	if(typeof valNum=='string'){valNum=Number(valNum)};
	return parseFloat(valNum.toString().replace(/\,/gi, '.'));
}
// pad Decimals
//***********************************************
Number.prototype.toFix = function(n){
	var s= this.toFixed(n);
	if(s.indexOf('.') <0){s+='.'};
	s+='00000';
	return s.substr(0,s.indexOf('.')+n+1);
}
// pad Decimals
//***********************************************
String.prototype.toFix = function(n){
	var s=toNumber(this);
	return s.toFix(n);
}
// string Capitalize
//***********************************************
String.prototype.toCap = function(){
	return this.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); });
}
// trim 
//***********************************************
String.prototype.trim = function(){
	return this.replace(/^\s*([\S\s]*)\b\s*$/, '$1');
}
// htmlentities 
//***********************************************
String.prototype.htmlEntities = function() {
   return this.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
// base64 
//***********************************************
String.prototype.atob= function() {
    if(window.atob){return window.atob(this.valueOf());}
	var base64s =  "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
	b64String=this.valueOf();
	var asciiString = '';
	var i;
	for(i = 0 ; i < b64String.length ; i += 4) {
		var asciiBitString =
		   (base64s.indexOf(b64String.charAt(i  )) & 0xff) << 18
		 | (base64s.indexOf(b64String.charAt(i+1)) & 0xff) << 12
		 | (base64s.indexOf(b64String.charAt(i+2)) & 0xff) <<  6
		 | (base64s.indexOf(b64String.charAt(i+3)) & 0xff);
		for (j = 2 ; j >= 0 ; j--) {
			if ((asciiBitString >> (8*j) & 0xff) == 0x00) {
				asciiString += String.fromCharCode(0x2205);
			}
			else {
				asciiString += String.fromCharCode(asciiBitString >> (8*j) & 0xff);
			}
		}
	}
	if      (b64String.charAt(i-2) == '=') {
		return asciiString.substring(0, asciiString.length -2);
	}
	else if (b64String.charAt(i-1) == '=') {
		return asciiString.substring(0, asciiString.length -1);
	}
	else {
		return asciiString;
	}
}
String.prototype.btoa= function() {
    if(window.btoa){return window.btoa(this.valueOf());}
}
// base64 to Array
//***********************************************
String.prototype.evalDecode= function() {
	try{var valRet=eval('('+this.valueOf()+')');}
	catch(err){return err.message+'<br>'+this.valueOf();}
	
	for(rows in valRet['values']){
		for(fields in valRet['values'][rows]){
		    if(valRet['columns'][fields]!='image'){
				valRet['values'][rows][fields]=valRet['values'][rows][fields].atob();
			}
		}
	}
	return valRet;
}
function genCode(q){
    str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    cad = "";
    for(i=0;i<q;i++) {cad += str.substr(Math.floor((Math.random()*62)+1),1);}
    return cad;
}

// String manipulate
//***********************************************    
function strLeft(qString, sString){
	var p=qString.indexOf(sString);
	if (p < 0) {return '';}
	return qString.substring(0,p);
}

// Relative to Absolute
//***********************************************    
function getUrl(url){
	var Loc = location.href;	
	Loc = Loc.substring(0, Loc.lastIndexOf('/'));
	while (/^\.\./.test(url)){		 
		Loc = Loc.substring(0, Loc.lastIndexOf('/'));
		url= url.substring(3);
	}
	return Loc + '/' + url;
}

// HTML to TXT
//***********************************************
function htmlToTxt(html, isUrl, isPlain){
  	var html= html || '';
  	if (isUrl){html=loadAjax(html)}
  	if (isPlain){
  	    var tmp = document.createElement('div');
        tmp.innerHTML=html;
        var valRet=tmp.innerText || tmp.textContent;   
  	}
  	else{
  	    var tmp = document.createElement('textarea');
  	    tmp.innerHTML=html;
  	    var valRet=tmp.value;
  	}
  	return valRet;
}

// put Data
//***********************************************
jss.putData=function(columns, values){
	var n, m;
	var inputs=document.getElementsByTagName('input');
	for(n=0; n<inputs.length; n++){
		for(m=0; m<columns.length; m++){
			if (inputs[n].name==columns[m]){
				if (inputs[n].type=='radio' || inputs[n].type=='checkbox'){
					if (inputs[n].value==values[0][m]){inputs[n].checked='checked'}
				}
				else{
					inputs[n].value=(values[0][m]) || '';
				}
			}
		}
	}
	var inputs=document.getElementsByTagName('textarea');	
	for(n=0; n<inputs.length; n++){
		for(m=0; m<columns.length; m++){
			if (inputs[n].name==columns[m]){
				inputs[n].value=(values[0][m]) || '';
			}
		}
	}	
	var inputs=document.getElementsByTagName('select');
	for(n=0; n<inputs.length; n++){
		for(m=0; m<columns.length; m++){
			if (inputs[n].name==columns[m]){
				inputs[n].value=values[0][m];
				for(var i = 0, j = inputs[n].options.length; i < j; ++i) {
        			if(inputs[n].options[i].innerHTML === values[0][m]) {
           				inputs[n].selectedIndex = i;
           				break;
        			}
        		}			
				break;
			}
		}
	}
}
// Parsec
//***********************************************
jss.Parsec=function(){
	jss.ParsecTables();
	if (typeof (window.jss.ParsecDatePicker) == 'function') { jss.ParsecDatePicker(); }
	if (typeof (window.jss.ParsecColorPicker) == 'function') { jss.ParsecColorPicker(); }	
}

// Parsec Menus & Panels
//***********************************************
jss.ParsecTables=function(){
	var n;
	var tables=document.getElementsByTagName('table');
	for(n=0; n<tables.length; n++){
		//Menus
		if (tables[n].className=='jss-Menu'){
			jssMenuCollapse(tables[n].rows[0].cells[0]);
			tables[n].rows[0].cells[0].onclick=function(el){jssMenuCollapse(el)};
		}
		
		//comun
		if (tables[n].className=='jss-Panel' || tables[n].className=='jss-Tab'){
			var renderTo= tables[n].parentNode || document.body;
			var id=tables[n].id || '';
			var rLen=tables[n].rows[0].cells.length;
			var style={};
			style.cssText=tables[n].style.cssText;
			style.overflow=tables[n].rows[1].cells[0].style.overflow || '';
			style.width=tables[n].style.width;
			style.height=tables[n].style.height;
		}
		
		//Panels		
		if (tables[n].className=='jss-Panel'){
			var title=tables[n].rows[0] ? tables[n].rows[0].cells[0].innerHTML : '';		
			var collapseMode='';
			if(rLen>1){collapseMode= tables[n].rows[0].cells[1].className;};
			//var content=tables[n].rows[1].cells[0].childNodes;
			var html=tables[n].rows[1].cells[0].innerHTML;
			var extra=tables[n].rows[1].cells[0].title;
			if (extra){tables[n].rows[1].cells[0].title=''; extra+=','}
			try{
				eval('var myParam={'+(extra)+'renderTo:(renderTo),html:(html),id:(id),title:(title),collapseMode:(collapseMode),style:(style),replace:(tables[n])}')
			}catch(err){
				html=extra+' Error:'+err.description;
				eval('var myParam={renderTo:(renderTo),html:(html),id:(id),title:(title),collapseMode:(collapseMode),style:(style),replace:(tables[n])}');
			}
			var myPanel=new jss.Panel(myParam);
			try{eval('window.'+(id)+'=myPanel')}
			catch(err){}
		}
		
		//Tabs
		if (tables[n].className=='jss-Tab'){
			var extra='items:[';
			for (var m=0; m<(rLen-1); m++){
				var title=tables[n].rows[0].cells[m].innerText || tables[n].rows[0].cells[m].textContent;
				extra+='{title:\''+title.replace('\n','','g')+'\',';
				extra+=tables[n].rows[0].cells[m].title || 'html:\'\'';
				extra+='},\n';
			}
			extra=extra.substring(0,extra.length-2)+']';
			try{
				eval('var myParam={'+(extra)+',renderTo:(renderTo),id:(id),style:(style),replace:(tables[n])}')
			}catch(err){
				html=extra+' Error:'+err.description;
				eval('var myParam={renderTo:(renderTo),html:(html),id:(id),style:(style),replace:(tables[n])}');
			}
			var myPanel=new jss.Tab(myParam);
			try{eval('window.'+(id)+'=myPanel')}
			catch(err){alert(err);}
		}
	}
}
