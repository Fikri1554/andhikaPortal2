// JavaScript Document
function pilihTeksTransfer()
{
	$('#idSpanPaidFromTo').html('TO');
	$('#idSpanDbCr').html('CR');
}

function pilihTeksReceive()
{
	$('#idSpanPaidFromTo').html('FROM');
	$('#idSpanDbCr').html('DB');
}

function klikBtnCariVoucherList()
{
	if( $('#elementCariVoucher').css("visibility") == 'hidden' ) // MUNCULKAN ELEMENT
	{
		$('#elementCariVoucher').css('visibility','visible');
	}
	else
	{ // SEMBUNYIKAN ELEMENT
		$('#elementCariVoucher').css('visibility','hidden');
		$('#elementCariVoucher').val('');
		loadIframe('iframeVoucherList', '');
		loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=displayVoucher&pageBatchno=1');
	}
}

function ketikElementCariVoucher(paramCari)
{
	var paramCari = $('#elementCariVoucher').val();
	//var menuPageTransno = $('#menuPageTransno').val();
	loadIframe('iframeVoucherList', '');
	loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=ketikElementCariVo&paramCari='+paramCari+'&pageBatchno=1');
}

function pilihBtnSave()
{
	var idVoucher = $('#idVoucher').val();
	var aksiSimpanVoucher = $('#aksiSimpanVoucher').val();
	var paidTo = $('#paidTo').val();
	var voucherType = $("input:radio[name='voucherType']:checked").val(); // NILAI T = TRANSFER, R = RECEIVE
	var company = $('#company').val();
	var payType = $('#payMethod').val();
	var bankCode = $('#bankCode').val();
	var voucher = $('#voucher').val();
	var reference = $('#reference').val();
	var chequeNumber = $('#chequeNumber').val();
	var invNo = $('#invNo').val();
	var jobNo = $('#jobNo').val();
	var datePaid = $('#datePaid').val();
	var currency = $('#currency').val();
	var amount = $('#amount').val();
	
	$('#idErrorMsg').css('visibility','hidden'); 
	if(paidTo == '')
	{
		pesanError('Paid To is still empty', 'paidTo');
		return false;
	}

	if(company == 'XXX')
	{
		pesanError('You have not selected Company', 'company');
		return false;
	}
	
	/*if( $.trim( voucher ) == '' )
	{
		pesanError("Voucher Number is still empty", "voucher");
		return false;
	}
	
	if( $.trim( reference ) == '' )
	{
		pesanError("Reference Number is still empty", "reference");
		return false;
	}*/
	
	if(payType == 'cheque')
	{
		if( $.trim( chequeNumber ) == '' )
		{
			pesanError("Cheque Number is still empty", "chequeNumber");
			return false;
		}
	}
	
	if( $.trim( datePaid ) == '' )
	{
		pesanError("Date Paid is still empty", "datePaid");
		return false;
	}
	
	if(currency == 'XXX')
	{
		pesanError('You have not selected Currency', 'currency');
		return false;
	}	
	
	if(amount.replace(/,/gi,'') == '')
	{
		pesanError('Amount is still empty', 'amount');
		return false;
	}
	
	var page = $('#menuPageBatchno').val();
	var answer  = confirm('Are you sure want to Save?');
	if(answer)
	{	
		pleaseWait();
		document.onmousedown=disableLeftClick;
		
		loadIframe('iframeVoucherList', '');
		loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi='+aksiSimpanVoucher+'&idVoucher='+idVoucher+'&paidTo='+paidTo+'&voucherType='+voucherType+'&company='+company+'&payType='+payType+'&bankCode='+bankCode+'&voucher='+voucher+'&reference='+reference+'&chequeNumber='+chequeNumber+'&invNo='+invNo+'&jobNo='+jobNo+'&datePaid='+datePaid+'&currency='+currency+'&amount='+amount+'&pageBatchno='+page);
	}
	else
	{	return false;	}
}

function klikPayMethod(payMethod)
{	
	$.post( "halPostVoucher.php", { aksi:"klikPayMethod", payMethod:payMethod, lastSelBank:$('#lastSelBank').val() }, function( data )
	{
		$('#tdBankCode').html( data );
	});
	
	$('#chequeNumber').attr("disabled","disabled");
	$('#chequeNumber').css("background-color","CCC");
	if(payMethod == "cheque")
	{
		$('#chequeNumber').attr("disabled","");
		$('#chequeNumber').css("background-color","");
	}
	
	if(payMethod == "transfer" || payMethod == "cash")
	{
		$('#chequeNumber').val("");
	}
}

function klikBankMenu(bankCode)
{
	//SIMPAN BANK CODE YANG TERAKHIR DIPILIH
	$('#lastSelBank').val( $('#bankCode').val() );
}

function pilihBtnAmount(amountAngka)
{
	if( $('#spanAmountTeks').css("visibility") == "hidden")
	{
		$('#spanAmountTeks').css("visibility", "visible");
	}
	else
	{
		$('#spanAmountTeks').css("visibility", "hidden");
	}

	
	$.post( "halPostVoucher.php", { aksi:"ketikAmount", amountAngka:amountAngka }, function( data )
	{
		$('#spanAmountTeks').html( data );
	});
}

function cekBalanceSebPrint()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	klikBtnRetrieve();
	
	setTimeout(function()
	{
		var idVoucher = $('#idVoucher').val();
		var batchno = $('#batchnoHid').val();
		
		loadIframe('iframeCekBalance', '');
		loadIframe('iframeCekBalance', 'templates/halCekBalance.php?aksi=cekSebPrint&idVoucher='+idVoucher+'&batchno='+batchno); //REFRESH FRAME
	}, 250);	
}

function klikBtnPrintVoucher(statusBalance)
{
	var idVoucher = $('#idVoucher').val();
	var batchno = $('#batchnoHid').val();
	var datePaid = $('#datePaid').val();
	
	if(statusBalance == "no")
	{
		var answer  = confirm('Amount is not Balance, Continued?');
		if(answer)
		{	
			$('#formPrintVoucher').attr('action', 'halPrint.php?aksi=printVoucher&idVoucher='+idVoucher+'&batchno='+batchno+'&datePaid='+datePaid);
			formPrintVoucher.submit();
		}
		else
		{	return false;	}
	}
	if(statusBalance == "yes")
	{
		$('#formPrintVoucher').attr('action', 'halPrint.php?aksi=printVoucher&idVoucher='+idVoucher+'&batchno='+batchno+'&datePaid='+datePaid);
		formPrintVoucher.submit();
	}
}

function klikRowVoucher(batchno, transferAcct, voucherType, paidTo, company, payType, bankCode, voucher, reference, chequeNumber, invNo, jobNo, datePaid, curr, amount)
{
	//alert(voucherType+' - '+paidTo+' - '+company);
	
	kosongkanElementHid();
	
	$('#judulAddVoucher').html('EDIT VOUCHER&nbsp;'); // GANTI JUDUL MENJADI EDIT VOUCHER
	$('#aksiSimpanVoucher').val('simpanUbahVoucher');
	
	$('#divStatusBlc', parent.document).css("visibility", "hidden"); //  HIDDEN UNTUK TEKS BALANCE
	$('#idSpanBalance', parent.document).html('&nbsp;'); // KOSONGKAN TEKS BALANCE
	$('#divStatusTrf', parent.document).css("visibility", "hidden"); //  HIDDEN UNTUK TEKS SUKSES TRANSFER TO ACCOUNTING
	
	enabledBtn('btnRetrieve');
	enabledBtn('btnRetrieve');
	disabledBtn('btnSave');
	enabledBtn('btnDelete');
	disabledBtn('btnPrintVoc');
	disabledBtn('btnCheckBalc');
	disabledBtn('btnPayTransAcct');
	disabledBtn('btnAddDesc');
	disabledBtn('btnEditDesc');
	disabledBtn('btnLargeView');
	
	$('#batchnoHid').val(batchno);
	$('#transferAcct').val(transferAcct);
	$('#voucherTypeHid').val(voucherType);
	$('#paidToHid').val(paidTo);
	$('#companyHid').val(company);
	$('#payTypeHid').val(payType);
	$('#bankCodeHid').val(bankCode);
	$('#voucherHid').val(voucher);
	$('#refHid').val(reference);
	$('#chequeNumberHid').val(chequeNumber);
	$('#invNoHid').val(invNo);
	$('#jobNoHid').val(jobNo);
	$('#datePaidHid').val(datePaid);
	$('#currHid').val(curr);
	$('#amountHid').val(amount);
	
	allElemReadOnlyTrue();
}

function kosongkanElementHid()
{
	//$('#statusBalance').val('N')
	
	$('#batchnoHid').val()
	$('#voucherTypeHid').val('');
	$('#paidToHid').val('');
	$('#companyHid').val('');
	$('#payTypeHid').val('');
	$('#bankCodeHid').val('');
	$('#voucherHid').val('');
	$('#refHid').val('');
	$('#chequeNumberHid').val('');
	$('#datePaidHid').val('');
	$('#currHid').val('');
	$('#amountHid').val('');
	
	$('#tdBatchno').html( '&nbsp;' );
	$("input:radio[name='voucherType'][value='T']").click();
	$('#paidTo').val( '' );
	$('#company').val( 'XXX' );
	$('#payMethod').val( 'cheque' );
	$('#bankCode').val( $('#bankCodeFirst').val() );
	$('#voucher').val( '' );
	$('#reference').val( '' );
	
	$('#chequeNumber').val( '' );
	$('#chequeNumber').attr("disabled","");
	$('#chequeNumber').css("background-color","");
		
	$('#invNo').val( '' );
	$('#jobNo').val( '' );
	$('#datePaid').val( '' );
	$('#currency').val( $('#currFirst').val() );
	$('#amount').val( '' );
	
	$('#idErrorMsg').html(''); 
	$('#idErrorMsg').css('visibility','hidden'); 
	$('#spanAmountTeks').html(''); 
	$('#spanAmountTeks').css('visibility','hidden'); 
	
	$('#spanJmlDesc').html('');
	$('#btnEditDesc').css("display","inline"); // MUNCULKAN KEMBALI TOMBOL EDIT DESCRIPTION
	$('#btnSaveDesc').css("display","none"); // HILANGKAN TOMBOL SAVE DESCRIPTION
	$('#btnCancelDesc').css("display","none"); // HILANGKAN TOMBOL CANCEL DESCRIPTION
	loadIframe('iframeDescList', '');
	//loadIframe('iframeDescList', 'templates/halDescList.php?aksi=tambahDesc&idVoucher='+idVoucher+'&batchno='+batchno);
}

function allElemReadOnlyFalse()
{
	$('input[name="voucherType"]').attr("disabled", false);
	$('#transferVcrText').attr("disabled", false);
	$('#receiveVcrText').attr("disabled", false);
	$('#paidTo').attr("readonly", false);
	$('#company option').attr("disabled", false);
	$('#payMethod option').attr("disabled", false);
	$('#bankCode option').attr("disabled", false);
	$('#voucher').attr("readonly", false);
	$('#reference').attr("readonly", false);
	$('#chequeNumber').attr("readonly", false);
	$('#invNo').attr("readonly", false);
	$('#jobNo').attr("readonly", true);
	$('#datePaid').attr("readonly", false);
	$('#currency option').attr("disabled", false);
	$('#imgCalDatePaid').attr("disabled", false);
	$('#amount').attr("readonly", false);
}

function allElemReadOnlyTrue()
{
	$('input[name="voucherType"]').attr("disabled", true);
	$('#transferVcrText').attr("disabled", true);
	$('#receiveVcrText').attr("disabled", true);
	$('#paidTo').attr("readonly", true);
	$('#company option').attr("disabled", true);
	$('#company option[value=XXX]').attr("disabled", false);
	$('#payMethod option').attr("disabled", true);
	$('#payMethod option[value=cheque]').attr("disabled", false);
	$('#bankCode option').attr("disabled", true);
	$('#bankCode option[value='+$('#bankCodeFirst').val()+']').attr("disabled", false);
	$('#voucher').attr("readonly", true);
	$('#reference').attr("readonly", true);
	$('#chequeNumber').attr("readonly", true);
	$('#invNo').attr("readonly", true);
	$('#jobNo').attr("readonly", true);
	$('#datePaid').attr("readonly", true);
	$('#currency option').attr("disabled", true);
	$('#currency option[value='+$('#currFirst').val()+']').attr("disabled", false);
	$('#imgCalDatePaid').attr("disabled", true);
	$('#amount').attr("readonly", true);
}

function klikBtnRetrieve()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	allElemReadOnlyFalse();
	
	enabledBtn('btnSave');
	disabledBtn('btnDelete');
	enabledBtn('btnPrintVoc');
	
	$('#tdBatchno').html( $('#batchnoHid').val() );
	var voucherTypeHid = $('#voucherTypeHid').val();
	if(voucherTypeHid == "R")
	{
		$("input:radio[name='voucherType'][value='R']").click();
	}
	if(voucherTypeHid == "T")
	{
		$("input:radio[name='voucherType'][value='T']").click();
	}
	
	$('#paidTo').val( $('#paidToHid').val() );
	$('#company').val( $('#companyHid').val() );
	$('#payMethod').val( $('#payTypeHid').val() );
	$('#bankCode').val( $('#bankCodeHid').val() );
	$('#voucher').val( $('#voucherHid').val() );
	$('#reference').val( $('#refHid').val() );
	
	$('#chequeNumber').val( $('#chequeNumberHid').val() );
	$('#chequeNumber').attr("disabled","");
	$('#chequeNumber').css("background-color","");
	if($('#payTypeHid').val() == "transfer" || $('#payTypeHid').val() == "cash")
	{
		$('#chequeNumber').attr("disabled","disabled");
		$('#chequeNumber').css("background-color","CCC");
	}
	
	$('#invNo').val( $('#invNoHid').val() );
	$('#jobNo').val( $('#jobNoHid').val() );
	$('#datePaid').val( $('#datePaidHid').val() );
	$('#currency').val( $('#currHid').val() );
	$('#amount').val( formatNumber($('#amountHid').val()) );
	
	$('#idErrorMsg').css('visibility','hidden'); 
	
	//pilihBtnCancelSaveDesc(); // MUNCULKAN BUTTON EDIT DESC DAN HILANGKAN BUTTON SAVE DESC DAN CANCEL DAN REFRESH IFRAMEDESCLIST
	
	$('#btnEditDesc').css("display","inline"); // MUNCULKAN KEMBALI TOMBOL EDIT DESCRIPTION
	$('#btnSaveDesc').css("display","none"); // HILANGKAN TOMBOL SAVE DESCRIPTION
	$('#btnCancelDesc').css("display","none"); // HILANGKAN TOMBOL CANCEL DESCRIPTION

	var idVoucher = $('#idVoucher').val();
	var batchno = $('#batchnoHid').val();
	loadIframe('iframeDescList', ''); // BUKA IFRAME DESCRIPTION LIST KETIKA TOMBOL RETRIEVE DIKLIK
	loadIframe('iframeDescList', 'templates/halDescList.php?aksi=display&idVoucher='+idVoucher+'&batchno='+batchno);
	
	/*if($('#transferAcct').val() == "Y")
	{
		disabledBtn('btnSave');
		disabledBtn('btnAddDesc');
		disabledBtn('btnEditDesc');
		$('#divStatusTrf').css("visibility", "visible"); //  HIDDEN UNTUK TEKS SUKSES TRANSFER TO ACCOUNTING
	}*/
}

function klikBtnRefresh()
{
	//window.location = "../voucher/";
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	$('#judulAddVoucher').html('ADD VOUCHER&nbsp;');
	
	disabledBtn('btnRetrieve');
	enabledBtn('btnSave');
	disabledBtn('btnDelete');
	disabledBtn('btnPrintVoc');
	disabledBtn('btnCheckBalc');
	disabledBtn('btnPayTransAcct');
	
	disabledBtn('btnAddDesc');
	disabledBtn('btnEditDesc');
	disabledBtn('btnSaveDesc');
	disabledBtn('btnCancelDesc');
	disabledBtn('btnLargeView');
	
	$('#aksiSimpanVoucher').val( 'simpanBaruVoucher' );
	$('#lastSelBank').val( '' );
	$('#idVoucher').val( '' );
	$('#transferAcct').val( '' );
	
	kosongkanElementHid();
	
	var idVoucher = $('#idVoucher').val();
	var page = $('#menuPageBatchno').val();
	loadIframe('iframeVoucherList', '');
	loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=displayVoucher&idVoucher='+idVoucher+'&pageBatchno='+page); //REFRESH FRAME*/
}


function backPilihVoucher()
{
	$('#judulAddVoucher').html('ADD VOUCHER&nbsp;');
	disabledBtn('btnRetrieve');
	//disabledBtn('btnBack');
	enabledBtn('btnSave');
	enabledBtn('btnDelete');
	disabledBtn('btnAddDesc');
	disabledBtn('btnEditDesc');
	disabledBtn('btnLargeView');
	
	kosongkanElementHid();
	//$('#idVoucher').val('');
	//$("input:radio[name='voucherType'][value='T']").click();
	//$('#aksiSimpanVoucher').val('simpanBaruVoucher');

	var idVoucher = $('#idVoucher').val();
	var page = $('#menuPageBatchno').val();
	loadIframe('iframeVoucherList', '');
	loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=btnBackVoucher&idVoucher='+idVoucher+'&pageBatchno='+page); //REFRESH FRAME
}

function klikBtnDelete()
{
	var answer  = confirm('Are you sure want to Delete?');
	if(answer)
	{	
		pleaseWait();
		document.onmousedown=disableLeftClick;

		var idVoucher = $('#idVoucher').val();
		var page = $('#menuPageBatchno').val();
		loadIframe('iframeVoucherList', '');
		loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=deleteVoucher&idVoucher='+idVoucher+'&pageBatchno='+page);
		//document.getElementById('iframeVoucherList').src = "";
		//document.getElementById('iframeVoucherList').src = "templates/halVoucherList.php?aksi=deleteVoucher&idVoucher="+idVoucher;
	}
	else
	{	return false;	}
}

function pilihBtnAddDesc()
{
	var idVoucher = $('#idVoucher').val();
	var batchno = $('#batchnoHid').val();
		
	var answer  = confirm('Add Description?');
	if(answer)
	{	
		pleaseWait();
		document.onmousedown=disableLeftClick;

		loadIframe('iframeDescList', '');
		loadIframe('iframeDescList', 'templates/halDescList.php?aksi=tambahDesc&idVoucher='+idVoucher+'&batchno='+batchno);
	}
	else
	{	return false;	}
}

function pilihBtnEditDesc()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
		
	//disabledBtn('btnBack');
	disabledBtn('btnSave');
	//disabledBtn('btnAddDesc');
	enabledBtn('btnSaveDesc');
	enabledBtn('btnCancelDesc');
	//disabledBtn('btnLargeView');
	disabledBtn('btnCheckBalc');
	disabledBtn('btnPayTransAcct');
	
	$('#btnEditDesc').css("display","none");
	$('#btnSaveDesc').css("display","inline");
	$('#btnCancelDesc').css("display","inline");
	
	var idVoucher = $('#idVoucher').val();
	var batchno = $('#batchnoHid').val();			
	loadIframe('iframeDescList', '');
	loadIframe('iframeDescList', 'templates/halDescList.php?aksi=editDesc&idVoucher='+idVoucher+'&batchno='+batchno);
}

function pilihBtnCancelSaveDesc()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	//enabledBtn('btnBack');
	enabledBtn('btnSave');
	enabledBtn('btnAddDesc');
	enabledBtn('btnLargeView');
	
	$('#btnEditDesc').css("display","inline");
	$('#btnSaveDesc').css("display","none");
	$('#btnCancelDesc').css("display","none");

	var idVoucher = $('#idVoucher').val();
	var batchno = $('#batchnoHid').val();
	loadIframe('iframeDescList', '');
	loadIframe('iframeDescList', 'templates/halDescList.php?aksi=display&idVoucher='+idVoucher+'&batchno='+batchno);
	
	/*setTimeout(function()
	{
		//alert(window.frames['iframeDescList'].$('#jmlDesc').val());
		if(window.frames['iframeDescList'].$('#jmlDesc').val() != "0") //JIKA JUMLAH DESCRIPTION LIST TIDAK SAMA DENGAN 0 MAKA ENABLE BUTTON DIBAWAH INI
		{
			enabledBtn('btnCheckBalc');
			//enabledBtn('btnPayTransAcct');
		}
	}, 400);
	
	setTimeout(function()
	{
		if($('#transferAcct').val() == "Y") // JIKA SUDAH DILAKUKAN TRANSFER TO ACCOUNTING MAKA BUTTON CHECK BALANCE DISABLED
		{
			disabledBtn('btnCheckBalc');
		}
	}, 200);*/
	
}

function pilihBtnSaveDesc()
{
	var idVoucher = $('#idVoucher').val();
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
	
	var bookStsVoucher = $('#idSpanDbCr').html();
	var amount = parseFloat($('#amount').val().replace(/,/g, "")); // JUMLAH AMOUNT DI PARENT VOUCHER
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
		
		//totalAmountDesc += +window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val().replace(/,/g, "");
		if(window.frames['iframeDescList'].$('#descBookSts'+splitAllIdDesc[i]).val() == "cr") // JIKA BOOKSTS DESC ADALAH CR MAKA SEMUA AMOUNT CR DIJUMLAHKAN
		{
			
			totalAmountDescCR += +window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val().replace(/,/g, "");
			//alert(window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val().replace(/,/g, ""));
		}
		if(window.frames['iframeDescList'].$('#descBookSts'+splitAllIdDesc[i]).val() == "db") // JIKA BOOKSTS DESC ADALAH DB MAKA SEMUA AMOUNT DB DIJUMLAHKAN
		{
			totalAmountDescDB += +window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val().replace(/,/g, "");
			//alert(window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val().replace(/,/g, ""));
		}
	}
	
	// JIKA BOOKSTS VOUCHER PARENT ADALAH CR MAKA TOTAL AMOUNT CR ADALAH AMOUNT PARENT DIJUMLAHKAN DENGAN TOTAL AMOUNT DESC YANG MEMPUNYAI BOOKSTS CR SEDANGKAN TOTAL AMOUNT DB HANYA BERISI TOTAL AMOUNT DESC YANG MEMPUNYAI BOOKSTS DB
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
	//allKetDesc = 'templates/halDescList.php?aksi=saveDesc&idVoucher='+idVoucher+'&batchno='+batchno+'&allIdDesc='+allIdDesc+allKetDesc; 
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
			loadIframe('iframeDescList', 'templates/halDescList.php?aksi=saveDesc&idVoucher='+idVoucher+'&batchno='+batchno+'&allIdDesc='+allIdDesc+allKetDesc+allDescBookSts+allDescAmount+allPerkiraan+allSubAcc+allDescDiv+allBooksts+allVesCode);
		}
		else
		{	return false;	}
	}
}

function pilihBtnLargeView()
{
	var idVoucher = $('#idVoucher').val();
	var batchno = $('#batchnoHid').val();
	//alert('pilihBtnLargeView');
	$('#hrefThickbox').attr('href','templates/halPopup.php?aksi=descLargeView&idVoucher='+idVoucher+'&batchno='+batchno+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=550&width=900&modal=true');
	$('#hrefThickbox').click();
}
// #####################################################################################
// POP UP WINDOWD SUCCESFULLY SAVE
// #####################################################################################
function closePopupNewVoucher(idVoucher, moreNewInv)
{
	var page = $('#menuPageBatchno').val();
	var aksiSimpanVoucher = $('#aksiSimpanVoucher').val();
	if(aksiSimpanVoucher == "simpanBaruVoucher")
	{
		page = 1;
	}
	
	$('#menuPageBatchno').val(page);

	if(moreNewInv == "on")
	{
		parent.tb_remove(false);
		setTimeout(function()
		{
			$('#btnRefresh', parent.document).click(); // KLIK BTN RETRIEVE BATCH
		}, 250);
	}
	else
	{
		parent.tb_remove(false);
		parent.loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=closePopUp&idVoucher='+idVoucher+'&pageBatchno='+page); //REFRESH FRAME
	}
	
}

function closePopup(idVoucher)
{
	var page = $('#menuPageBatchno').val();
	var aksiSimpanVoucher = $('#aksiSimpanVoucher').val();
	if(aksiSimpanVoucher == "simpanBaruVoucher")
		page = 1;
	
	$('#menuPageBatchno').val(page);
	
	parent.tb_remove(false);
	parent.loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=closePopUp&idVoucher='+idVoucher+'&pageBatchno='+page); //REFRESH FRAME
}

function closePopupCari(idVoucher, page)
{
	alert(idVoucher, page);
	//var page = $('#menuPageBatchno').val();
	var aksiSimpanVoucher = $('#aksiSimpanVoucher').val();
	if(aksiSimpanVoucher == "simpanBaruVoucher")
	{
		page = 1;
	}
	$('#menuPageBatchno').val(page);

	loadIframe('iframeVoucherList', '');
	loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=closePopUp&idVoucher='+idVoucher+'&pageBatchno='+page); //REFRESH FRAME
}

function pilihBtnCheckBalance()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	klikBtnRetrieve();
	
	setTimeout(function()
	{
		var idVoucher = $('#idVoucher').val();
		var batchno = $('#batchnoHid').val();
		
		loadIframe('iframeCekBalance', '');
		loadIframe('iframeCekBalance', 'templates/halCekBalance.php?aksi=cek&idVoucher='+idVoucher+'&batchno='+batchno); //REFRESH FRAME
	}, 250);	
	
}

function pilihBtnTranstoAcct()
{
	var paidTo = $('#paidTo').val();
	var company = $('#company').val();
	var voucher = $('#voucher').val();
	var reference = $('#reference').val();
	var datePaid = $('#datePaid').val();
	//var amount = $('#amount').val();
	
	$('#idErrorMsg').css('visibility','hidden'); 
	if(paidTo == '')
	{
		pesanError('Paid To cannot empty', 'paidTo');
		return false;
	}
	if(company == 'XXX')
	{
		pesanError('You must select Company', 'company');
		return false;
	}
	if( $.trim( voucher ) == '' )
	{
		pesanError("Voucher cannot empty", "voucher");
		return false;
	}
	
	if( $.trim( reference ) == '' )
	{
		pesanError("Reference cannot empty", "reference");
		return false;
	}
	if( $.trim( datePaid ) == '' )
	{
		pesanError("Date Paid is still empty", "datePaid");
		return false;
	}
	
/*	if(amount.replace(/,/gi,'') == '')
	{
		pesanError('Amount is still empty', 'amount');
		return false;
	}*/
	
	//alert('pilihBtnTranstoAcct');
	var answer  = confirm('Are you sure want to Transfer to Accounting?');
	if(answer)
	{	
		pleaseWait();
		document.onmousedown=disableLeftClick;
			
		var idVoucher = $('#idVoucher').val();
		var batchno = $('#batchnoHid').val();
		var page = $('#menuPageBatchno').val();
		
		//loadIframe('iframeCekBalance', '');
		//loadIframe('iframeCekBalance', 'templates/halCekBalance.php?aksi=transToAcct&idVoucher='+idVoucher+'&batchno='+batchno); //REFRESH FRAME
		loadIframe('iframeVoucherList', '');
		loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=transToAcct&idVoucher='+idVoucher+'&batchno='+batchno+'&pageBatchno='+page); //REFRESH FRAME
	}
	else
	{	return false;	}
	
}

function ubahUrutan(req, idDesc)
{
	//parent.$('#loaderImgDesc').css('visibility','visible');
	parent.pleaseWait();
	parent.document.onmousedown=parent.disableLeftClick;
	
	var idVoucher = $('#idVoucher', parent.document).val();
	var batchno = $('#batchnoHid', parent.document).val();
	
	$.post( "../halPostVoucher.php", { aksi:"ubahUrutan", req:req, idDesc:idDesc }, function( data )
	{
		//parent.$('#btnRetrieve').click();
		parent.doneWait();
		parent.panggilEnableLeftClick();

		parent.loadIframe('iframeDescList', '');
		parent.loadIframe('iframeDescList', 'templates/halDescList.php?aksi=editDesc&idVoucher='+idVoucher+'&batchno='+batchno);

	});

	
	 	/*var posting = $.ajax( "../halPostVoucher.php", { aksi:"ubahUrutan", req:req, idDesc:idDesc } );
		posting.done(function( data ) 
		{
			doneWait();
			panggilEnableLeftClick();
  		});*/
}

function ubahUrutanPopUp(req, idDesc)
{
	//parent.$('#loaderImg').css('visibility','visible');
	parent.pleaseWait();
	parent.document.onmousedown=parent.disableLeftClick;
	
	var idVoucher = $('#idVoucherHid', parent.document).val();
	var batchno = $('#batchnoHid', parent.document).val();
		
	$.post( "../halPostVoucher.php", { aksi:"ubahUrutan", req:req, idDesc:idDesc }, function( data )
	{
		//parent.$('#iframeDescList').attr('src','../templates/halPopupDescList.php?aksi=display&idVoucher='+idVoucher+'&batchno='+batchno);
		parent.doneWait();
		parent.panggilEnableLeftClick();
		
		parent.loadIframe('iframeDescList', '');
		parent.loadIframe('iframeDescList', '../templates/halPopupDescList.php?aksi=editDesc&idVoucher='+idVoucher+'&batchno='+batchno);
	});
}

function klikBtnPageBatchno(aksi)
{
	var menuPageBatchno = $('#menuPageBatchno').val();
	var paramCari = $('#elementCariVoucher').val();
	var maxPageBatchno = $('#maxPageBatchno').html();
	var page = "";
	
	
	//alert(maxPageBatchno);
	
	if(aksi == 'first')
	{
		page = 1;
	}
	
	if(aksi == 'previous' && parseInt(menuPageBatchno) >= 1)
	{
		page = (parseInt(menuPageBatchno) - 1);
	}
	if(aksi == 'previous' && parseInt(menuPageBatchno) == 1)
	{
		return false;
	}
	
	if(aksi == 'next' && parseInt(menuPageBatchno) <= parseInt(maxPageBatchno))
	{
		page = (parseInt(menuPageBatchno) + 1);
	}
	if(aksi == 'next' && parseInt(menuPageBatchno) == parseInt(maxPageBatchno))
	{
		return false;
	}
	
	if(aksi == 'last')
	{
		page = maxPageBatchno;
	}
	
	var aksiKirim = "displayVoucher";
	if(paramCari != "")
		aksiKirim = "ketikElementCariVo";
	
	loadIframe('iframeVoucherList', '');
	loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi='+aksiKirim+'&paramCari='+paramCari+'&pageBatchno='+page);
}

function klikMenuPageBatchno(page)
{
	var paramCari = $('#elementCariVoucher').val();
	loadIframe('iframeVoucherList', '');
	loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=ketikElementCariVo&paramCari='+paramCari+'&pageBatchno='+page);
}

function klikBtnSearch()
{
	var idVoucher = $('#idVoucher').val();
	var batchno = $('#batchnoHid').val();
	var page = $('#menuPageBatchno').val();
		
	$('#hrefThickbox').attr('href','templates/halPopup.php?aksi=cari&idVoucher='+idVoucher+'&batchno='+batchno+'&pageBatchno='+page+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&width=800&height=500&modal=true');
	$('#hrefThickbox').click();
}