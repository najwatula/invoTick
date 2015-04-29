var currentFamily='', currentRate='';currentBgColor='';

function putFamilys(){
	var strsql='select * from familys order by Family';
	var datos=loadAjax('../../../cgi_bin/phpFun.php','jsgetArray=1&strsql='+strsql).evalDecode();
	if (typeof datos!='object'){window.alert(datos); return;};	

	var values=datos['values'];
	var columns=datos['columns'];
	var dataLen=values.length;
	var table=document.createElement('table');
	table.className='jss-TableCell';
	table.style.width='100%';

	var pRows=0, pCells=0;
	table.insertRow(pRows);table.rows[pRows].style.height='40px';
	for (n=0;n<dataLen;n++){
		if(pCells >4 ){
			pRows ++; pCells=0;
			table.insertRow(pRows);
			table.rows[pRows].style.height='50px';				
		}
		table.rows[pRows].insertCell(pCells);
		table.rows[pRows].cells[pCells].style.backgroundColor = values[n][2];
		table.rows[pRows].cells[pCells].style.textAlign='center';
		table.rows[pRows].cells[pCells].id=values[n][0];
		table.rows[pRows].cells[pCells].innerHTML=values[n][1];
		table.rows[pRows].cells[pCells].onclick=function(){putProducts(this.id, this.style.backgroundColor);}	
		pCells++;
	}
	document.getElementById('pFamilys').innerHTML='';
	document.getElementById('pFamilys').appendChild(table);
	putProducts(values[0][0],values[0][2]);
}
	
function putProducts(idFamily, bgColor){
	currentFamily=idFamily;
	currentBgColor=bgColor;
	document.getElementById('pProducts').innerHTML='';

    var strImg='';
	var idRate=document.getElementById('idRate').value;
	var strsql='select idProduct, item, vatValue, price from prices where idFamily='+idFamily+' and idRate='+idRate+' order by item';
	var datos=loadAjax('../../../cgi_bin/phpFun.php','jsgetArray=1&strsql='+strsql).evalDecode();
	if (typeof datos!='object'){document.getElementById('pProducts').innerHTML=datos; return;};	
	dataProduct=datos['values'];
	var dataLen=dataProduct.length;
	if (dataLen<1 || datos['values'][0][0]<'0'){return;}
	
	var table=document.createElement('table');
	table.className='jss-TableCell';
	table.style.width='100%';

	var pRows=0, pCells=0;
	table.insertRow(pRows);table.rows[pRows].style.height='40px';
	for (n=0; n < dataLen; n++){
		if(pCells >4 ){
			pRows ++; pCells=0;
			table.insertRow(pRows);
			table.rows[pRows].style.height='50px';
		}
		if(showImages=='1'){
		    strImg='<img src="';
		    strImg+='../../../cgi_bin/phpFun.php?getImg=base64&strsql=';
		    strImg+=window.btoa('select image from images where tableName="products" and id='+dataProduct[n][0]);
		    strImg+='" style="max-height: 80px; max-width: 100px;"><br>';
		}
		table.rows[pRows].insertCell(pCells);
		table.rows[pRows].cells[pCells].style.backgroundColor = bgColor;
		table.rows[pRows].cells[pCells].style.textAlign='center';
		table.rows[pRows].cells[pCells].id=n;		
		table.rows[pRows].cells[pCells].innerHTML=strImg+dataProduct[n][1];
		table.rows[pRows].cells[pCells].onclick=function(){putItems(this.id);}
		pCells++;
	}
	document.getElementById('pProducts').appendChild(table);
}

function putItems(id){
	var tabla=document.getElementById('pItems');
	var lastRow=tabla.rows.length-1;
	var found=0;
	//check if exist
	for(var n=1;n<lastRow && found==0;n++){
		if(tabla.rows[n].cells[2].innerHTML==dataProduct[id][1] && tabla.rows[n].cells[3].innerHTML==dataProduct[id][3]){
			sumAmount(n,0)
			tabla.rows[n].cells[1].innerHTML=parseInt(tabla.rows[n].cells[1].innerHTML)+parseInt(document.getElementById('units').value);
			sumAmount(n,1)
			found=1;
		}
	}
	if(found==0){
		if(vatType==1){var base=(parseFloat(dataProduct[id][3])/(parseFloat('1.'+dataProduct[id][2])));}
		else{var base=parseFloat(dataProduct[id][3]);}
		
		tabla.tBodies[0].appendChild(tabla.rows[lastRow].cloneNode(true));
		tabla.rows[lastRow].cells[0].lastChild.alt=lastRow;
		tabla.rows[lastRow].cells[0].id=dataProduct[id][0];
		tabla.rows[lastRow].cells[1].innerHTML=document.getElementById('units').value;
		tabla.rows[lastRow].cells[2].innerHTML=dataProduct[id][1];
		tabla.rows[lastRow].cells[3].innerHTML=base.toFix(3);
		tabla.rows[lastRow].cells[4].innerHTML=document.getElementById('discount').value;
		tabla.rows[lastRow].cells[5].innerHTML=dataProduct[id][2];
		sumAmount(lastRow, 1);
		
		for (var n=1;n<6;n++){
			tabla.rows[lastRow].cells[n].onfocus=function(el){getOldValue(this);};
			tabla.rows[lastRow].cells[n].onblur=function(el){sumAmount(0,2);};
			tabla.rows[lastRow].cells[n].contentEditable='true';
		}
	}
}
function deleteLine(nRow){
	var tabla=document.getElementById('pItems');
	var lastRow=tabla.rows.length-1;
	if (nRow<lastRow){
		sumAmount(nRow, 0);
		tabla.deleteRow(nRow);
	}
}
function getOldValue(cell){
	oldValue=cell.innerHTML;
	oldCellValue=cell;
}

function sumAmount(nRow, op){
	// check edit numeric value
	if (oldValue && oldCellValue.cellIndex!=2){
		if (!isNumeric(oldCellValue.innerHTML)){
			oldCellValue.innerHTML=oldValue;
			oldValue=false;
			return;
		}
		if(oldCellValue.cellIndex==1){
			oldCellValue.innerHTML=parseInt(oldCellValue.innerHTML);
		}
		else{
			oldCellValue.innerHTML=toNumber(oldCellValue.innerHTML).toFix(2);
		}	
	}
	oldValue=false;

	// op=2 suma todo
	var cellBase=document.getElementById('base');
	var cellvatAmount=document.getElementById('vatAmount');
	var cellTotal=document.getElementById('total');	
	var tabla=document.getElementById('pItems');
	var totalBase=0, totalIva=0, totalTotal=0;
	if(op==2){
		var nStart=1;
		var rowLen=tabla.rows.length-1;
		cellBase.innerHTML='0';
		cellvatAmount.innerHTML='0';
		cellTotal.innerHTML='0';
	}
	else{
		var nStart=nRow;
		var rowLen=nRow+1;
	}

	for (var nRow=nStart; nRow < rowLen; nRow++){
		var base=parseFloat(tabla.rows[nRow].cells[1].innerHTML*tabla.rows[nRow].cells[3].innerHTML);
		var descuento=parseFloat(tabla.rows[nRow].cells[4].innerHTML*base/100);
		var base=base-descuento;		
		var iva=parseFloat(tabla.rows[nRow].cells[5].innerHTML,4)*base/100;
		var total=(base+iva);
		totalBase+=base;
		totalIva+=iva;
		totalTotal+=total;
		tabla.rows[nRow].cells[6].innerHTML=total.toFix(2);
	}
	
	//0 resta, else suma
	if(op==0){
		cellBase.innerHTML=(parseFloat(cellBase.innerHTML)-totalBase).toFix(2);
		cellvatAmount.innerHTML=(parseFloat(cellvatAmount.innerHTML)-totalIva).toFix(2);
		cellTotal.innerHTML=(parseFloat(cellTotal.innerHTML)-totalTotal).toFix(2);
	}else{ 
		cellBase.innerHTML=(parseFloat(cellBase.innerHTML)+totalBase).toFix(2);
		cellvatAmount.innerHTML=(parseFloat(cellvatAmount.innerHTML)+totalIva).toFix(2);	
		cellTotal.innerHTML=(parseFloat(cellTotal.innerHTML)+totalTotal).toFix(2);	
	}
}

function pickClient(){
	try{wWin.close()} catch(err){};
	strsql=strsql='select idCompany, company from companies order by company';
	var datos=loadAjax('../../../cgi_bin/phpFun.php','jsgetArray=1&strsql='+strsql).evalDecode();
	if (typeof datos!='object'){document.getElementById('client').innerHTML=datos; return;};	
	
	wWin= new jss.PickItem({
		style:{width: '600',height: '300'},
		title:'Pick Item',
		fireEvent: 'getVal',
		values: datos['values'],
		columns: datos['columns']
	})
}

function getVal(q){
	document.getElementById('idClient').title=(q.cells[0].innerText || q.cells[0].textContent);
	document.getElementById('client').value=(q.cells[1].innerText || q.cells[1].textContent);
	wWin.window.panel.close();
}

function makeTicket(type){
	//document.getElementById('imgTicket').src='../../images/cash.png';
	var number='0';
	var myTable=document.getElementById('pItems');
	var rowLen=myTable.rows.length-1;
	if (rowLen<2){document.location.replace('invoices.php?type='+type); return false;}
	
	//incrementa el contador
	var serie=document.getElementById('serie')[document.getElementById('serie').selectedIndex].text;
	if(type=='TICKET'){number=loadAjax('../../../cgi_bin/phpFun.php?numInvoice=1&serie='+serie);}
	
	//factura
	var strsql='INSERT INTO [invoices] (type, idAgent, serie, number, base, vatAmount, total, idClient, idPayType, idPos) VALUES(';

	strsql+='\''+type+'\', ';
	strsql+=idAgent+', ';
	strsql+='\''+serie+'\', ';
	strsql+=number+', ';
	strsql+=document.getElementById('base').innerHTML+', ';
	strsql+=document.getElementById('vatAmount').innerHTML+', ';
	strsql+=document.getElementById('total').innerHTML+', ';
	strsql+=document.getElementById('idClient').title+', ';
	strsql+=document.getElementById('idPayType').value+', ';
	strsql+=document.getElementById('idPos').value+');';

	var idInvoice=loadAjax('../../../cgi_bin/phpFun.php','lastId=1&runSQL=base64&strsql='+strsql.btoa());
	//lineas de factura
	var cellLen=myTable.rows[1].cells.length;
	strsql='';
	strsqlIns='INSERT INTO [invoLines] (idInvoice, idProduct, quantity, concept, price, base, discount, discountAmount, vat, vatAmount, total) ';
	for (var n=1; n < rowLen; n++){
		var base=parseFloat(myTable.rows[n].cells[1].innerHTML*myTable.rows[n].cells[3].innerHTML).toFix(3);
		var iva=(base*myTable.rows[n].cells[5].innerHTML/100).toFix(2);
		var descuento=parseFloat(myTable.rows[n].cells[4].innerHTML*base/100).toFix(2);
		strsql+=strsqlIns+'VALUES('+idInvoice+', ';
		strsql+=myTable.rows[n].cells[0].id+', ';
		strsql+=myTable.rows[n].cells[1].innerHTML+', ';
		strsql+='\''+htmlToTxt(myTable.rows[n].cells[2].innerHTML,false,true)+'\', ';
		strsql+=myTable.rows[n].cells[3].innerHTML+', ';
		strsql+=base+', ';
		strsql+=myTable.rows[n].cells[4].innerHTML+', ';
		strsql+=descuento+', ';
		strsql+=myTable.rows[n].cells[5].innerHTML+', ';
		strsql+=iva+', ';
		strsql+=myTable.rows[n].cells[6].innerHTML+'); ';
	}
    //strsql=strsql.substring(0, strsql.length-2)

	var idLine=loadAjax('../../../cgi_bin/phpFun.php','runSQL=base64&strsql='+strsql.btoa());
	if (type=='TICKET'){printTicket(idInvoice);}
	else{deleteTicket();}
}

function deleteTicket(msg){
	if(msg){if (!window.confirm(msg)){return;};}
	var myTable=document.getElementById('pItems');
	var rowLen=myTable.rows.length-1;
	for (var n=1; n < rowLen; n++){myTable.deleteRow(1);}
	document.getElementById('base').innerHTML=0;
	document.getElementById('vatAmount').innerHTML=0;
	document.getElementById('total').innerHTML=0;
	if (requestId>'0'){
		strsql='delete from invoices where idInvoice='+requestId;	
		strsql+='; delete from invoLines where idInvoice='+requestId+';';
		var datos=loadAjax('../../../cgi_bin/phpFun.php','runSQL=base64&strsql='+strsql.btoa());
		requestId='0';
	}
}

function printTicket(idInvoice){
    var idModel=document.getElementById('idModel').value;
    var printer=document.getElementById('idModel')[document.getElementById('idModel').selectedIndex].text;
    var url='../reports/docs/parsec.php?idKey=idInvoice&id='+idInvoice+'&idModel='+idModel;

    if (printer.indexOf('*')>0){
        var printer=printer.split('*')[1].trim();
        var url='print.php?id='+idInvoice+'&printer='+printer;
    }
        
	wWin= new jss.Window({
	    style:{width: '70%',height: '80%'},
	    title:'Ticket',
	    iFrame:{id:'ifPrint', url:(url), scroll:'yes'}
	})

	if (url.substr(0,1)=='.'){window.frames.ifPrint.focus(); window.frames.ifPrint.print();}
	if(showChange>'0'){
		var funChange='window.frames.ifPrint.location=\'change.php\'';
		setTimeout(funChange,1000);
	}
	setTimeout('deleteTicket()',2000);
}

function getTicket(id){
	// coger datos de la cabecera
	var strsql='select serie, type, idPayType, idClient, company from invoClients where idInvoice='+id;
	var datos=loadAjax('../../../cgi_bin/phpFun.php?jsgetArray=base64&strsql='+strsql.btoa()).evalDecode();	
	if (typeof datos!='object'){window.alert(datos); return;};	
	
	//document.getElementById('serie')[document.getElementById('serie').selectedIndex].text=datos['values'][0][0];
    var objSerie=document.getElementById('serie');
	for(var n = 0; n < objSerie.options.length; n++) {
        if(objSerie[n].text == datos['values'][0][0]) {
           objSerie.selectedIndex = n;
           break;
        }
    }
	//document.getElementById('type').value=datos['values'][0][1];
	document.getElementById('idPayType').value=datos['values'][0][2];
	document.getElementById('idClient').title=datos['values'][0][3];
	document.getElementById('client').value=datos['values'][0][4];
	
	// coger datos de las lineas de concepto
	var tabla=document.getElementById('pItems');	
	var lastRow=1;
	var strsql='select idLine, quantity, concept, price, discount, vat, total from invoLines where idInvoice='+id;
	var datos=loadAjax('../../../cgi_bin/phpFun.php','jsgetArray=base64&strsql='+strsql.btoa()).evalDecode();
	if (typeof datos!='object'){window.alert(datos); return;};	
	
	for (var n=0;n< datos['values'].length; n++){	

		tabla.tBodies[0].appendChild(tabla.tBodies[0].rows[lastRow].cloneNode(true));
		
		tabla.rows[lastRow].cells[0].id=datos['values'][n][0];
		tabla.rows[lastRow].cells[1].innerHTML=datos['values'][n][1];
		tabla.rows[lastRow].cells[2].innerHTML=datos['values'][n][2];
		tabla.rows[lastRow].cells[3].innerHTML=datos['values'][n][3].toFix(3);
		tabla.rows[lastRow].cells[4].innerHTML=datos['values'][n][4];
		tabla.rows[lastRow].cells[5].innerHTML=datos['values'][n][5];
		tabla.rows[lastRow].cells[6].innerHTML=datos['values'][n][6].toFix(2);
		
		for (var m=1;m<6;m++){
			tabla.rows[lastRow].cells[m].onfocus=function(el){getOldValue(this)};
			tabla.rows[lastRow].cells[m].onblur=function(){sumAmount(0,2)};
			tabla.rows[lastRow].cells[m].contentEditable='true';
		}
		lastRow++;
	}
	sumAmount(0,2)
}

function putBarCode(bc){
	var strsql='select * from prices where barCode='+bc.value;
	var datos=loadAjax('../../../cgi_bin/phpFun.php','jsgetArray=1&strsql='+strsql).evalDecode();
	if (datos['values'][0][0]){
		dataProduct[0]=datos['values'][0];
		putItems(0);
	}
	bc.value=''
}
