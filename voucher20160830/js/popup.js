// JavaScript Document

function pilihBtnAddDesc()
{
	var idVoucher = $('#idVoucherHid').val();
	var batchno = $('#batchnoHid').val();
		
	var answer  = confirm('Add Description?');
	if(answer)
	{	
		pleaseWait();
		document.onmousedown=disableLeftClick;

		loadIframe('iframeDescList', '');
		loadIframe('iframeDescList', '../templates/halPopupDescList.php?aksi=tambahDesc&idVoucher='+idVoucher+'&batchno='+batchno);
	}
	else
	{	return false;	}
}

function pilihBtnEditDesc()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	disabledBtn('btnAddDesc');
	disabledBtn('btnClose');
		
	$('#btnEditDesc').css("display","none");
	$('#btnSaveDesc').css("display","inline");
	$('#btnCancelDesc').css("display","inline");
	
	var idVoucher = $('#idVoucherHid').val();
	var batchno = $('#batchnoHid').val();			
	loadIframe('iframeDescList', '');
	loadIframe('iframeDescList', '../templates/halPopupDescList.php?aksi=editDesc&idVoucher='+idVoucher+'&batchno='+batchno);
}

function pilihBtnCancelSaveDesc()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	enabledBtn('btnAddDesc');
	enabledBtn('btnClose');
	
	$('#btnEditDesc').css("display","inline");
	$('#btnSaveDesc').css("display","none");
	$('#btnCancelDesc').css("display","none");

	var idVoucher = $('#idVoucherHid').val();
	var batchno = $('#batchnoHid').val();
	loadIframe('iframeDescList', '');
	loadIframe('iframeDescList', '../templates/halPopupDescList.php?aksi=display&idVoucher='+idVoucher+'&batchno='+batchno);
}

function pilihBtnSaveDesc()
{
	var idVoucher = $('#idVoucherHid').val();
	var batchno = $('#batchnoHid').val();
	var allIdDesc = window.frames['iframeDescList'].$('#allIdDesc').val(); // AMBIL NILAI HIDDEN ELEMENT DGN ID 'ALLIDDESC' YANG BERISI KUMPULAN IDDESC BERDASAR VOUCHER DIPILIH
	var splitAllIdDesc = allIdDesc.split(","); // PISAHKAN IDDESC YANG DIDAPAT BERDASAR KOMA
	var pjgSplitAllIdDesc = splitAllIdDesc.length; // SETELAH DIPISAH BERDASAR KOMA DAPAT KAN JUMLAH IDDESC YANG DIDAPAT BIASANYA ADA 2 KOSONG
	
	var allKetDesc = "";
	var allDescBookSts = "";
	/*var allDescCurr = "";*/
	var allDescAmount = "";
	var allPerkiraan = ""; // ACCT
	var allSubAcc = "";
	var allDescDiv = "";
	var allBooksts = "";
	var allVesCode = "";
	
	var bookStsVoucher = $('#bookStsHid').val();
	var amount = parseFloat($('#amountHid').val().replace(/,/g, "")); // JUMLAH AMOUNT DI PARENT VOUCHER
	var totalAmountCR = 0; // JIKA BOOKSTS PARENT ADALAH CR 
	var totalAmountDB = 0;// JIKA BOOKSTS PARENT ADALAH DB 

	//var totalAmountDesc = 0;
	var totalAmountDescCR = 0;
	var totalAmountDescDB = 0;	

	for(var i=0; i<=(pjgSplitAllIdDesc-2);i++)
	{
		// AMBIL NILAI KETERANGAN DESC ATAU YG PUNYA ID 'DESC+IDDESC' LALU DIKUMPULKAN BERDASAR JUMLAH IDDESC YANG DIDAPAT UNTUK DITEMPATKAN DI PARAMETER
		// MISAL : &desc1=AAAAA&desc3=BBB&desc4=CCC dst...
		allKetDesc += '&desc'+splitAllIdDesc[i]+'='+encodeURIComponent( window.frames['iframeDescList'].$('#desc'+splitAllIdDesc[i]).val() ); 
		allDescBookSts += '&descBookSts'+splitAllIdDesc[i]+'='+window.frames['iframeDescList'].$('#descBookSts'+splitAllIdDesc[i]).val(); 
		/*allDescCurr += '&descCurr'+splitAllIdDesc[i]+'='+window.frames['iframeDescList'].$('#descCurr'+splitAllIdDesc[i]).val(); */
		allDescAmount += '&descAmount'+splitAllIdDesc[i]+'='+window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val(); 
		allPerkiraan += '&perkiraan'+splitAllIdDesc[i]+'='+window.frames['iframeDescList'].$('#perkiraan'+splitAllIdDesc[i]).val(); 
		allSubAcc += '&subAcc'+splitAllIdDesc[i]+'='+window.frames['iframeDescList'].$('#subAcc'+splitAllIdDesc[i]).val(); 
		allDescDiv += '&descDiv'+splitAllIdDesc[i]+'='+window.frames['iframeDescList'].$('#descDiv'+splitAllIdDesc[i]).val(); 
		allBooksts += '&descBookSts'+splitAllIdDesc[i]+'='+window.frames['iframeDescList'].$('#descBookSts'+splitAllIdDesc[i]).val(); 
		allVesCode += '&vesCode'+splitAllIdDesc[i]+'='+window.frames['iframeDescList'].$('#vesCode'+splitAllIdDesc[i]).val(); 
		
		if(window.frames['iframeDescList'].$('#descBookSts'+splitAllIdDesc[i]).val() == "cr") // JIKA BOOKSTS DESC ADALAH CR MAKA SEMUA AMOUNT CR DIJUMLAHKAN
		{
			totalAmountDescCR += +window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val().replace(/,/g, "");
		}
		if(window.frames['iframeDescList'].$('#descBookSts'+splitAllIdDesc[i]).val() == "db") // JIKA BOOKSTS DESC ADALAH DB MAKA SEMUA AMOUNT DB DIJUMLAHKAN
		{
			totalAmountDescDB += +window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val().replace(/,/g, "");
		}
	}
	
	if(bookStsVoucher == "CR")
	{	
		totalAmountCR = (amount+totalAmountDescCR);
		totalAmountDB = totalAmountDescDB;
	}
	if(bookStsVoucher == "DB")
	{
		totalAmountCR = totalAmountDescCR;
		totalAmountDB = (amount+totalAmountDescDB);
	}
	//alert(totalAmountCR+' | '+totalAmountDB );
	var okCancel = "";
	if(totalAmountCR != totalAmountDB)
	{
		var answer  = confirm('Amount is not Balance, Continued?');
		if(answer)
		{	okCancel = "ok";	}
		else
		{	okCancel = "cancel";	}
	}
	else
	{	okCancel = "ok";	}
	
	if(okCancel == "ok")
	{
		var answer  = confirm('Are you sure want to Save All Description?');
		if(answer)
		{	
			pleaseWait();
			document.onmousedown=disableLeftClick;
	
			loadIframe('iframeDescList', '');
			loadIframe('iframeDescList', '../templates/halPopupDescList.php?aksi=saveDesc&idVoucher='+idVoucher+'&batchno='+batchno+'&allIdDesc='+allIdDesc+allKetDesc+allDescBookSts+allDescAmount+allPerkiraan+allSubAcc+allDescDiv+allBooksts+allVesCode);
		}
		else
		{	return false;	}
	}
	//allKetDesc = 'templates/halDescList.php?aksi=saveDesc&idVoucher='+idVoucher+'&batchno='+batchno+'&allIdDesc='+allIdDesc+allKetDesc; 
	
}

function klikBtnCariaa()
{
	parent.disabledBtn('btnCariPrintDetail');
	
	var cariBerdasarkan = $('#cariBerdasarkan').val();
	var teksCari = $('#teksCari').val();
	var startDate = $('#startDate').val();
	var endDate = $('#endDate').val();
	
	$('#idErrorMsg').css('visibility','hidden'); 
	if((cariBerdasarkan == "senderVendor" || cariBerdasarkan == "company" || cariBerdasarkan == "mailInvNo" || cariBerdasarkan == "poNumber" || cariBerdasarkan == "senVen") && $.trim( teksCari ) == '')
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

function setupCari()
{
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var startDate = new DateMask("dd/MM/yyyy", "startDate");
	startDate.validationMessage = errorMessage;
	var endDate = new DateMask("dd/MM/yyyy", "endDate");
	endDate.validationMessage = errorMessage;
}

function tahunbalik(str)
{
	var thn = str.substring(6,str.length)
	var bln = str.substring(3,5)
	var tgl = str.substring(0,2)
	return thn.concat(bln).concat(tgl)
}

function klikBtnCari()
{
	//parent.disabledBtn('btnCariPrintDetail');
	
	var cariBerdasarkan = $('#cariBerdasarkan').val();
	var teksCari = $('#teksCari').val();
	var startDate = $('#startDate').val();
	var endDate = $('#endDate').val();
	
	$('#idErrorMsg').css('visibility','hidden'); 
	if((cariBerdasarkan == "paidName" || cariBerdasarkan == "company" || cariBerdasarkan == "bank" || cariBerdasarkan == "voucherNo" || cariBerdasarkan == "invNo") && $.trim( teksCari ) == '')
	{
		pesanError("Please types in your search", "teksCari");
		return false;
	}
	
	if(cariBerdasarkan == "datePaid" && (startDate == '' && endDate == ''))
	{
		pesanError("Start or End Date is still empty", "startDate");
		return false;
	}
	if(cariBerdasarkan == "datePaid" && parseInt(tahunbalik(startDate)) > parseInt(tahunbalik(endDate)))
	{
		pesanError("End Date must be greater than Start Date", "startDate");
		return false;
	}
	
	//pleaseWait();
	//document.onmousedown=disableLeftClick;
	
	$('#iframeList').attr('src', '');
	$('#iframeList').attr('src', '../templates/halCariList.php?aksi=lakukanCari&cariBerdasarkan='+cariBerdasarkan+'&teksCari='+teksCari+'&startDate='+startDate+'&endDate='+endDate);
	
	$('#divDetailCari').html( '' );
}

function klikBtnClosed()
{
	parent.tb_remove(false);
	//parent.pleaseWait();
	//parent.document.onmousedown=parent.disableLeftClick;
	//parent.klikBtnDisplay();
}

function klikBtnRefresh()
{
	$('#cariBerdasarkan').val('paidName');
	$('#teksCari').val('');
	$('#startDate').val('');
	$('#endDate').val('');
	
	disabledBtn('btnGetResult');
	
	$('#iframeList').attr('src', '');
	$('#divDetailCari').html( '' );
}

function klikBtnResult()
{

	var idVoucher = $('#idVoucher').val();
	var page = $('#menuPageBatchno').val();

	//var page = $('#menuPageBatchno').val();
	$('#idVoucher', parent.document).val(idVoucher);
	$('#menuPageBatchno', parent.document).val(page);

	parent.tb_remove(false);
	
	parent.loadIframe('iframeVoucherList', '');
	parent.loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=closePopUp&idVoucher='+idVoucher+'&pageBatchno='+page); //REFRESH FRAME
}
