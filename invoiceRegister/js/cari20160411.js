// JavaScript Document
function setup()
{
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var startDate = new DateMask("dd/MM/yyyy", "startDate");
	startDate.validationMessage = errorMessage;
	var endDate = new DateMask("dd/MM/yyyy", "endDate");
	endDate.validationMessage = errorMessage;
}

function klikBtnCari()
{
	parent.disabledBtn('btnCariPrintDetail');
	
	var cariBerdasarkan = $('#cariBerdasarkan').val();
	var teksCari = $('#teksCari').val();
	var startDate = $('#startDate').val();
	var endDate = $('#endDate').val();
	
	$('#idErrorMsg').css('visibility','hidden'); 
	if((cariBerdasarkan == "senderVendor" || cariBerdasarkan == "company" || cariBerdasarkan == "mailInvNo" || cariBerdasarkan == "poNumber" || cariBerdasarkan == "senVen" || cariBerdasarkan == "unit") && $.trim( teksCari ) == '')
	{
		pesanError("Please types in your search", "teksCari");
		return false;
	}
	
	if((cariBerdasarkan == "mailInvDate" || cariBerdasarkan == "batchno") && (startDate == '' && endDate == ''))
	{
		pesanError("Start or End Date is still empty", "startDate");
		return false;
	}
	if((cariBerdasarkan == "mailInvDate" || cariBerdasarkan == "batchno") && parseInt(tahunbalik(startDate)) > parseInt(tahunbalik(endDate)))
	{
		pesanError("End Date must be greater than Start Date", "startDate");
		return false;
	}
	
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	$('#iframeList').attr('src', '');
	$('#iframeList').attr('src', 'templates/halCariList.php?aksi=lakukanCari&cariBerdasarkan='+cariBerdasarkan+'&teksCari='+teksCari+'&startDate='+startDate+'&endDate='+endDate);
	
	$('#divDetailCari').html( '' );
}

function klikBtnCariPrintResult()
{
/*	var idData = document.getElementById('idData').value;
	var printReport = getValuesRadio('printReport');
	if(printReport == "printFull")
	{
		var left = (screen.width/2) - (1024/2);
		var top = (screen.height/2)-(768/2);
		
		window.open("halPrint.php?aksi=printReport&&idData="+idData, "report", "titlebar=no, toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width="+screen.width+", height="+screen.height+", top="+top+", left="+left+"");
	}
	if(printReport == "printFullNot")
	{
		document.getElementById('formPrint').action = "halPrint.php?aksi=printReport&idData="+idData;
		formPrint.submit()
	}
*/

	var cariBerdasarkan = $('#cariBerdasarkan').val();
	var teksCari = $('#teksCari').val();
	var startDate = $('#startDate').val();
	var endDate = $('#endDate').val();
	
	$('#idErrorMsg').css('visibility','hidden'); 
	if((cariBerdasarkan == "senderVendor" || cariBerdasarkan == "company" || cariBerdasarkan == "mailInvNo" || cariBerdasarkan == "poNumber" || cariBerdasarkan == "senVen" || cariBerdasarkan == "unit") && $.trim( teksCari ) == '')
	{
		pesanError("Please types in your search", "teksCari");
		return false;
	}
	
	if((cariBerdasarkan == "mailInvDate" || cariBerdasarkan == "batchno") && (startDate == '' && endDate == ''))
	{
		pesanError("Start or End Date is still empty", "startDate");
		return false;
	}
	if((cariBerdasarkan == "mailInvDate" || cariBerdasarkan == "batchno") && parseInt(tahunbalik(startDate)) > parseInt(tahunbalik(endDate)))
	{
		pesanError("End Date must be greater than Start Date", "startDate");
		return false;
	}
	
	var cariBerdasarkan = $('#cariBerdasarkan').val();
	var teksCari = $('#teksCari').val();
	var startDate = $('#startDate').val();
	var endDate = $('#endDate').val();
	$('#formPrintResult').attr('action', 'halPrint.php?aksi=printResult&cariBerdasarkan='+cariBerdasarkan+'&teksCari='+teksCari+'&startDate='+startDate+'&endDate='+endDate);
	//document.getElementById('formPrintResult').action = "halPrint.php?aksi=printResult";
	formPrintResult.submit()
}

function klikBtnCariPrintDetail()
{
	var idMailInv = $('#idMailInv').val();
	$('#formPrintDetail').attr('action', 'halPrint.php?aksi=printDetail&idMailInv='+idMailInv);
	formPrintDetail.submit()
}

function tahunbalik(str)
{
	var thn = str.substring(6,str.length)
	var bln = str.substring(3,5)
	var tgl = str.substring(0,2)
	return thn.concat(bln).concat(tgl)
}

function pesanError(pesan, itemFokus)
{
	document.getElementById(itemFokus).focus(); 
	$('#idErrorMsg').css('visibility','visible'); 
	$('#idErrorMsg').html("<img src=\"../picture/exclamation-red.png\"/>&nbsp;<span>"+pesan+"</span>&nbsp;");
}

function blinker() {
    $('#idErrorMsg').fadeOut(250);
	$('#idErrorMsg img').fadeOut(250);
		
    $('#idErrorMsg').fadeIn(500);
	$('#idErrorMsg img').fadeIn(500);
}

setInterval(blinker, 1500);

