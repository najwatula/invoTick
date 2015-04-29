// by Jupiter & Najwa & Tula (2012/10/16 00:00)
//***********************************************

// jssColorPicker
//***********************************************
jss.ColorPicker = function(param) {
    var r = new Array("00","33","66","99","BB","DD","FF");
    var vth = this;

    this.color = param.color || 'FFFFFF';
    this.putTo = param.putTo || '';

    if (document.getElementById(this.putTo)) {
        var qPos = getPos(document.getElementById(this.putTo));
        param.top = qPos['top'] + 25;
        param.left = qPos['left'];
    }
    param.style = { width: 250, height: 200 }
    param.title='Color Picker';
    var buildStr = '<table class="jss-Cursor jss-Table" style="width: 100%; height: 100%" id="jssColor">';
    for (i=0;i<r.length;i++){
    	buildStr += '<tr>';
        for (j=0;j<r.length;j++) {        
      	    for (k=0;k<r.length;k++) { 
         	    var jssColor = r[i] + r[j] + r[k]; 
         	    buildStr+='<td style="background-color: #' + jssColor + '" title="'+jssColor+'"></td>'; 
        	}    	
   	    }        	    
        buildStr += '</tr>'; 
    }    
    buildStr += '</table>';

    this.window = new jss.Window(param);
    this.window.panel.container.innerHTML = buildStr;
    this.window.panel.container.focus();
    document.getElementById('jssColor').onclick = function(el) { vth.retVal(el) };

    return this;
}

jss.ColorPicker.prototype.retVal = function(el) {
    var evento = el || window.event;
    var jssValColor = evento.srcElement ? evento.srcElement.title : evento.target.title;
    var jssColor = evento.srcElement ? evento.srcElement.style.backgroundColor : evento.target.style.backgroundColor;
    if (document.getElementById(this.window.panel.putTo)) {
        document.getElementById(this.window.panel.putTo).value = '#'+jssValColor;
        document.getElementById(this.window.panel.putTo).style.backgroundColor = jssColor;
    }
    this.window.panel.close();
    if (this.window.panel.fireEvent) {eval(this.window.panel.fireEvent)}
}

// Parsec Color Picker
//***********************************************
jss.ParsecColorPicker = function() {
    var n, extra = '';
    var inputs = document.getElementsByTagName('input');
    for (n = 0; n < inputs.length; n++) {
        if (inputs[n].className == 'jss-ColorPicker') {
            if (!inputs[n].id) { inputs[n].id = inputs[n].name }
            if (inputs[n].title) { extra = ',' + inputs[n].title }
            var myFun = 'new jss.ColorPicker({putTo:(this.id), color:(this.value)' + extra + '});';
            inputs[n].onclick = function(el) { eval(myFun) };
        }
    }
}
