// JavaScript Document

function klikBtnDoSearch()
{
	var cariBerdasarkan = $("#idHalCariMailInv input[type='radio']:checked").val();
	var teksCari = $('#teksCari').val();
	var startDate = $('#startDate').val();
	var endDate = $('#endDate').val();
	var sortBy = $('#sortBy').val();
	
	$('#idErrorMsg').css('visibility','hidden'); 
	//alert(cariBerdasarkan);
	if((cariBerdasarkan == "senderVendor" || cariBerdasarkan == "company") && $.trim( teksCari ) == '')
	{
		pesanError("Sender / Vendor or Company value is still empty", "teksCari");
		return false;
	}
	
	if((cariBerdasarkan == "invDate" || cariBerdasarkan == "entryDate") && (startDate == '' && endDate == ''))
	{
		pesanError("Start or End Date is still empty", "startDate");
		return false;
	}

	if((cariBerdasarkan == "invDate" || cariBerdasarkan == "entryDate") && parseInt(tahunbalik(startDate)) > parseInt(tahunbalik(endDate)))
	{
		pesanError("End Date must be greater than Start Date", "startDate");
		return false;
	}
	
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	disabledBtn('btnView');
	disabledBtn('btnBatchnoGroup');

	$('#iframeList').attr('src', '');
	$('#iframeList').attr('src', '../templates/halIncomingList.php?aksi=cariMailInv&cariBerdasarkan='+cariBerdasarkan+'&teksCari='+teksCari+'&startDate='+startDate+'&endDate='+endDate+'&sortBy='+sortBy);
}

function klikBtnBatchnoGroup()
{
	var batchno = $('#batchno').val();
	var thnBln = batchno.substr(0, 6);
	var tgl = batchno.substr(6,2);
	
	$("#menuBatchnoThnBln", parent.document).val( thnBln );
	parent.ajaxGet(thnBln, tgl, 'pilihMenuThnBln', 'idTdMenuTgl'); //AJAX UNTUK MERUBAH MENU DROPDOWN TAHUNBULAN DAN TANGGAL
	parent.beriBatchno(); //ISI HIDDEN TEXT DENGAN ID BATCHNO

	parent.disabledBtn('btnEdit');
	parent.disabledBtn('btnDelete');
	
	parent.teksMap("INCOMING");
	
	$('#iframeList', parent.document).attr('src', '');
	$('#iframeList', parent.document).attr('src', 'templates/halIncomingList.php?aksi=display&thnBln='+thnBln+'&tgl='+tgl);
 
	setTimeout(function(){ tutup();  }, 250);
}

function klikBtnView()
{
	var batchno = $('#batchno').val(); // AMBIL NILAI BATCHNO DARI HIDDEN TEKS DI HALAMAN CARI
	var idMailInv = $('#idMailInv').val(); // AMBIL NILAI IDMAILINV DARI HIDDEN TEKS DI HALAMAN CARI
	$("#batchno", parent.document).val( batchno ); // BERI NILAI BATCHNO DI HALAMAN INCOMING DENGAN NILAI BATCHNO DARI HALAMAN CARI
	$("#idMailInv", parent.document).val( idMailInv ); // BERI NILAI IDMAILINV DI HALAMAN INCOMING DENGAN NILAI IDMAILINV DARI HALAMAN CARI
	
	parent.openThickboxWindow('klikBtnEdit');
	
	return false;
}

function teksMap(value)
{
	parent.document.getElementById('idTeksMap').innerHTML = "<a>"+value+"</a>";
}

function tahunbalik(str)
{
	var thn = str.substring(6,str.length)
	var bln = str.substring(3,5)
	var tgl = str.substring(0,2)
	return thn.concat(bln).concat(tgl)
}

function enabledBtn(idButton)
{
	$('#'+idButton).attr("disabled","");
	$('#'+idButton).removeClass("btnStandarDis");
    $('#'+idButton).addClass("btnStandar");
}

function disabledBtn(idButton)
{
	$('#'+idButton).attr("disabled","disabled");
	$('#'+idButton).removeClass("btnStandar");
    $('#'+idButton).addClass("btnStandarDis");
}

function tutup()
{
	parent.tb_remove(false);
	//parent.klikBtnDisplay();
}

function setup()
{
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var startDate = new DateMask("dd/MM/yyyy", "startDate");
	startDate.validationMessage = errorMessage;
	var endDate = new DateMask("dd/MM/yyyy", "endDate");
	endDate.validationMessage = errorMessage;
}

function pesanError(pesan, itemFokus)
{
	document.getElementById(itemFokus).focus(); 
	$('#idErrorMsg').css('visibility','visible'); 
	$("#idErrorMsg").html("<img src=\"../picture/exclamation-red.png\"/>&nbsp;<span>"+pesan+"</span>&nbsp;");
}

function panggilEnableLeftClick()
{
	document.onmousedown=enableLeftClick;
}

function pleaseWait()
{
	$('#loaderImg').css('visibility', 'visible');
}

function doneWait()
{
	$('#loaderImg').css('visibility', 'hidden');
}

function disableLeftClick()
{
	if (event.button==1) 
	{
		alert('Please Wait...')
		return false;
	}
}
function enableLeftClick()
{
	if (event.button==1) 
	{
		return true;
	}
}

function blinker() {
    $('#idErrorMsg').fadeOut(250);
	$('#idErrorMsg img').fadeOut(250);
		
    $('#idErrorMsg').fadeIn(500);
	$('#idErrorMsg img').fadeIn(500);
}

setInterval(blinker, 1500);
