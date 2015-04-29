// by Jupiter & Najwa & Tula (2013/01/03 00:00)
//***********************************************

// jssEdit
//***********************************************
var level=0;

jss.Edit=function(param){
	var qButtons=Array('cut','copy','paste','-','undo','redo','-','createLink','insertImage','insertTable','-','bold','italic','underline','-','foreColor','hiliteColor','-','justifyLeft','justifyCenter','justify','justifyRight','-','insertOrderedList','insertUnorderedList','outdent','indent','-','insertHorizontalRule','removeFormat','html');
	var qFonts=Array('-','Arial','Arial Narrow','Calibri','Courier New','Tahoma','Verdana');
	var qSizes=Array('-','1','2','3','4','5','6','7');
	this.buttons=param.buttons || null;
	var vth=this;
	
	this.panel= new jss.Panel(param);
	this.panel.caption.rows[0].cells[0].className='jss-Bar';
	
	var builderHTML='<div id="editButtons">', sep='<div style="width: 10px; height:1px; float: left;"></div>';
	for(n=0;n<qButtons.length;n++){
		if(qButtons[n]=='-'){builderHTML+=sep;}
   		else{builderHTML+='<div class="img-edit img-'+qButtons[n]+'" id="'+qButtons[n]+'" title="'+qButtons[n]+'"></div>';}
   	}
   	if(this.buttons){
	   	builderHTML+=sep;
		for(b in this.buttons){
	   		builderHTML+='<div class="img-edit img-'+b+'" id="'+b+'" title="'+b+'"></div>';
		}
   	}
   	//builderHTML+='</div><div style="float: left;">Font: </div>'+sep;
   	builderHTML+=sep+'<select size="1" class="img-edit" style="width: 100pt;" id="fontName" name="fontName">';
	for(n=0;n<qFonts.length;n++){
		builderHTML+='<option>'+qFonts[n]+'</option>';
   	}
   	builderHTML+='</select>'+sep;
   	builderHTML+='<select size="1" class="img-edit" style="width: 30pt;" id="fontSize" name="fontSize">';
	for(n=0;n<qSizes.length;n++){
		builderHTML+='<option>'+qSizes[n]+'</option>';
   	}
   	builderHTML+='</select></div>'+sep;

   	this.panel.setTitle(builderHTML);
   	this.editArea=this.panel.container;	
   	this.editArea.style.overflow='auto';
   	this.editArea.contentEditable='true';
    this.editArea.designMode = 'on';
  	try {document.execCommand("undo", false, null);}  
  	catch (e) {alert(e);}
	this.panel.caption.rows[0].cells[0].firstChild.onmousedown=function(el){vth.goCommand(el)};
	document.getElementById('fontName').onchange=function(el){vth.goCommand(el)};
	document.getElementById('fontSize').onchange=function(el){vth.goCommand(el)};
   	return this;
}

jss.Edit.prototype.goCommand=function(el){
	var evento = el || window.event;
	var obj= evento.srcElement || evento.target;
	
	//alert(document.getSelection());
   	if(this.buttons){
		for(b in this.buttons){
	   		if(obj.id==b){
				var qEval=(this.buttons[b]); try{eval(qEval);} catch(err){alert(err);}
	   			return;
	   		}
		}
   	}
   	
   	switch(obj.id){
	case 'fontName' || 'fontSize':
	   	document.execCommand(obj.id, false, obj.options[obj.selectedIndex].text);
	   	break;
   	case 'createLink' || 'insertImage':
     	var qUrl = prompt('Enter a URL:', 'http://');
    	if (qUrl) {document.execCommand(obj.id,false, qUrl);}
    	break;
	case 'html':
		var v= new jss.Window({
			renderTo: document.body,
			title:'HTML Code',
			style:{width: '99%', height: '99%'},
			html:('<textarea id="jss-editHtml" style="font-family: Courier New; font-size: 9pt; width:100%; height:100%">'+tabHTML(this.getHTML())+'</textarea>')
		})
		var qEdit=this;
		v.panel.bcollapse.onclick=function(el){qEdit.putHTML(document.getElementById('jss-editHtml').value);v.panel.close(el)};
    	break;
	default:
		document.execCommand(obj.id, false, null);
	}
}

jss.Edit.prototype.getHTML=function(){
	return this.editArea.innerHTML;
}
jss.Edit.prototype.putHTML=function(html){
	if(this.editArea){this.editArea.innerHTML=html;}
}
jss.Edit.prototype.execCommand=function(command){
	this.editArea.focus();
	document.execCommand(command);	
}

function tabHTML(code) {
	var l, n, m, valRet='', varTag;
	code=code.replace(/\n/g,'');
	code=code.replace(/\r/g,'');
	code=code.replace(/\t/g,'');
	code=code.replace(/>/g,'>\n');
	//code=code.replace(/</g,'\r<');
	l=code.split('\n');

	for(n=l.length-1; n>=0; n--){
		varTag=l[n].match(/<\/t.*/);
		if (varTag){
			varTag=varTag[0].replace('/','').replace('>','');
			for(m=n-1; (l[m].search(varTag)<0 && m>1); m--){
				l[m]=' '+l[m].trim();
			}
		}
	}
	for (n=0; n<l.length; n++){
		valRet+=l[n].trim()+'\n';
	}
	return valRet;
}


