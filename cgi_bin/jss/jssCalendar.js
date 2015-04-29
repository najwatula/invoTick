// by Jupiter & Najwa & Tula (2014/06/20 00:00)
//***********************************************

Date.prototype.getDayName = function(day) {
	if (isNaN(day)){var nDate=this;}
	else{var nDate=new Date (2001, 0, day+1);}
	
	var valRet=nDate.toDateString().split(' ');
	return valRet[0];
}
Date.prototype.getMonthName = function() {
	var valRet=this.toDateString().split(' ');
	return valRet[1];
}

Date.prototype.daysInMonth = function () {
   return new Date(this.getFullYear(), this.getMonth()+1, 0).getDate()
}

Date.prototype.calendar = function(original, from, to) {
   // The number of days in the month (when date=0 Javascript gives last date of previous month).
   var numDays = this.daysInMonth();
   var iniDay=0;
   // Get the starting day of this calendar, mon, tue, wed, etc.
   var startDay= new Date(this.getFullYear(), this.getMonth(), iniDay).getDay();
   
	// Constructor
   var buildStr ='<table class="jss-Table" style="width: 100%; text-align: center;">';
   buildStr+='<tr class="jss-Bar"><td class="jss-Left" id="jssMonthLeft">&nbsp;</td><td>'+this.getMonthName()+' '+this.getFullYear()+'</td><td class="jss-Right" id="jssMonthRight">&nbsp;</td></tr></table>';
   buildStr+='<table class="jss-Table" style="width: 100%; text-align: center;"><tr class="jss-Bar">';
   for (var i=0; i<7; i++){
   		buildStr+='<td style="width: 10%">'+this.getDayName(i).substr(0,2)+"</td>";
   }
   buildStr+='</tr></table><table id="jssCal" class="jss-TableCell" style="width: 100%; text-align: center;"><tr style="height: 20px">';
   
   // Create blank boxes until we get to the day which actually starts the month
   for(var i=0; i<startDay; i++) {buildStr+='<td style="width: 10%; background-color: #FFFFFF;">&nbsp;</td>';}
   var border=startDay;
   var nlines=0;
   
   // For each day in the month, insert it into the calendar.
	for(i=1; i<=numDays; i++) {
		this.setDate(i);
		qColor='';
		if (from>this || to<this){qColor='; background-color: #FFFFFF';}
		if (this.valueOf()==original.valueOf()){qColor='; background-color: #E0E1B7';}
      	buildStr+='<td style="width: 10%'+qColor+'">'+i+'</td>';
      	border++;
      	if (((border%7)==0)&&(i<numDays)) {buildStr+='</tr><tr style="height: 20px;">';nlines++;}
   	}
   
   while((border++%7)!=0) {buildStr+='<td style="width: 10%; background-color: #FFFFFF;">&nbsp;</td>'}
   if (nlines<5){buildStr+='</tr><tr style="height: 20px;"><td colspan="7" style="background-color: #FFFFFF;"></td>'}
   buildStr+='</tr></table>';  
   return buildStr;
}

Date.prototype.format = function(f)
{
    if (!this.valueOf())
        return ' ';
    var d = this;
    return f.replace(/(yyyy|yy|mmmm|mmm|mm|dddd|ddd|dd|hh|nn)/gi,
        function($1)
        {
            switch ($1.toLowerCase())
            {
            case 'yyyy': return d.getFullYear();
            case 'yy':   return d.getFullYear().toString().slice(2);
            case 'mmmm': return d.getMonthName();
            case 'mmm':  return d.getMonthName().substr(0,3);
            case 'mm':   return ('0'+(d.getMonth() + 1)).slice(-2);
            case 'dddd': return d.getDayName();
            case 'dd':   return ('0'+d.getDate()).slice(-2);
            case 'hh':   return d.getHours();
            case 'nn':   return ('0'+d.getMinutes()).substring(0,2);            
            }
        }
    );
}

// string to Date
//***********************************************
function strToDate (d){
	var sDate=new Date();

	if (d.indexOf("-") != -1) {d = d.split("-");}
	else if (d.indexOf("/") != -1) { d = d.split("/"); }
	else { return sDate; }
	
	if (parseInt(d[0], 10) >= 1000) { var sDate = new Date(d[0]+"/"+d[1]+"/"+d[2]);}
    else if (parseInt(d[2], 10) >= 1000) {var sDate = new Date(d[2]+"/"+d[1]+"/"+d[0]);} 
	return sDate;
}

function daysBetween(date1, date2){
   if (date1.indexOf("-") != -1) {date1 = date1.split("-");} else if (date1.indexOf("/") != -1) { date1 = date1.split("/"); } else { return 0; } 
   if (date2.indexOf("-") != -1) {date2 = date2.split("-");} else if (date2.indexOf("/") != -1) { date2 = date2.split("/"); } else { return 0; } 
   if (parseInt(date1[0], 10) >= 1000) { 
       var sDate = new Date(date1[0]+"/"+date1[1]+"/"+date1[2]); 
   } else if (parseInt(date1[2], 10) >= 1000) { 
       var sDate = new Date(date1[2]+"/"+date1[1]+"/"+date1[0]); 
   } else { 
       return 0; 
   } 
   if (parseInt(date2[0], 10) >= 1000) { 
       var eDate = new Date(date2[0]+"/"+date2[1]+"/"+date2[2]); 
   } else if (parseInt(date2[2], 10) >= 1000) { 
       var eDate = new Date(date2[2]+"/"+date2[1]+"/"+date2[0]); 
   } else { 
       return 0; 
   } 
   var one_day = 1000*60*60*24; 
   var daysApart = Math.abs(Math.ceil((sDate.getTime()-eDate.getTime())/one_day)); 
   return daysApart; 
}

function dateWithin(beginDate,endDate,checkDate) {
	if((checkDate<= endDate && checkDate>= beginDate)) {
		return true;
	}
	return false;
}

// jssDatePicker
//***********************************************
jss.DatePicker= function(param){
	this.dat= strToDate(param.date || '');
	this.original=new Date(this.dat.valueOf());
	this.fromDate=strToDate(param.fromDate || '01/01/1970');
	this.toDate=strToDate(param.toDate || '01/01/9999');
	this.format = param.format || 'yyyy-mm-dd';
	this.putTo = param.putTo || '';
	if (document.getElementById(this.putTo)){
		var qPos=getPos(document.getElementById(this.putTo));
		param.top=qPos['top']+20;
		param.left=qPos['left'];
	}
	param.style={width:280,height:220};
	param.title='Calendar';
	this.window=new jss.Window(param);
	this.goMonth(0);
	return this;
}

jss.DatePicker.prototype.goMonth=function(op){
	var vth=this;
	this.dat.setDate(1);
	this.dat.setMonth(this.dat.getMonth()+op);
  	this.window.panel.container.innerHTML=this.dat.calendar(this.original, this.fromDate, this.toDate);
	this.window.panel.container.focus();
	document.getElementById('jssMonthLeft').onclick=function(){vth.goMonth(-1)};
	document.getElementById('jssMonthRight').onclick=function(){vth.goMonth(1)};
	document.getElementById('jssCal').onclick=function(el){vth.retVal(el)};
}

jss.DatePicker.prototype.retVal=function(el){
	var evento = el || window.event;
	var contenido= evento.srcElement ? evento.srcElement.innerText : evento.target.textContent;
	var color=evento.srcElement ? evento.srcElement.style.backgroundColor : evento.target.style.backgroundColor;
	if (contenido>'0' && color<'0'){
		var valRet=(this.dat.getFullYear()+'/'+(this.dat.getMonth()+1)+'/'+ (contenido));
		this.dat=new Date(valRet);
		var valRet=this.dat.format(this.format);
		if(document.getElementById(this.window.panel.putTo)){document.getElementById(this.window.panel.putTo).value=valRet};
		this.window.panel.close();
		if (this.window.panel.fireEvent){eval(this.window.panel.fireEvent)}
	}
}

// Parsec Date Picker
//***********************************************
jss.ParsecDatePicker=function(){
	var n, extra='';
	var inputs=document.getElementsByTagName('input');
	for(n=0; n<inputs.length; n++){
		if (inputs[n].className=='jss-DatePicker'){
			if(!inputs[n].id){inputs[n].id=inputs[n].name}
			if(inputs[n].title){extra=','+inputs[n].title}
			var myFun='new jss.DatePicker({putTo:(this.id), date:(this.value)'+extra+'});';
			inputs[n].onclick=function(el){eval(myFun)};
		}
	}
}

