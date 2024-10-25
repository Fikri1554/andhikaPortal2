// JavaScript Document  
function pilihTeksTransfer()
{
	$('#idSpanPaidFromTo').html('TO');
	$('#idSpanDbCr').html('CR');
	$('#idSpanDbCr2').html('/ CREDIT');
}

function pilihTeksReceive()
{
	$('#idSpanPaidFromTo').html('FROM');
	$('#idSpanDbCr').html('DB');
	$('#idSpanDbCr2').html('/ DEBET');
}

function klikBtnCariVoucherList()
{
	if( $('#elementCariVoucher').css("visibility") == 'hidden' ) // MUNCULKAN ELEMENT
	{
		$('#elementCariVoucher').css('visibility','visible');
	}
	else
	{ // SEMBUNYIKAN ELEMENT
		var yearProcess = $('#yearProcess').val();

		$('#elementCariVoucher').css('visibility','hidden');
		$('#elementCariVoucher').val('');
		loadIframe('iframeVoucherList', '');
		loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=displayVoucher&pageBatchno=1&yearProcess='+yearProcess);
	}
}

function ketikElementCariVoucher(paramCari)
{
	disabledBtn('btnSave');
	disabledBtn('btnSearch');
	if($('#aksiSimpanVoucher').val() == "simpanUbahVoucher")
	{
		$('#judulAddVoucher').html('ADD VOUCHER&nbsp;');
	
		disabledBtn('btnRetrieve');
		//enabledBtn('btnSave');
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
		allElemReadOnlyFalse();
		
		$('#jobNo').prop("readonly", false);
		$('#divStatusBlc', parent.document).css("visibility", "hidden");
		$('#divStatusTrf', parent.document).css("visibility", "hidden");
	}
	
	var yearProcess = $('#yearProcess').val();
	var paramCari = $('#elementCariVoucher').val();
	//var menuPageTransno = $('#menuPageTransno').val();
	loadIframe('iframeVoucherList', '');
	loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=ketikElementCariVo&paramCari='+paramCari+'&pageBatchno=1&yearProcess='+yearProcess);
	
	//alert($('#aksiSimpanVoucher').val());
	
	/*pleaseWait();
	document.onmousedown=disableLeftClick;
	
	$('#elementCariVoucher').css('visibility','hidden');
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
	allElemReadOnlyFalse();
	
	$('#jobNo').prop("readonly", false);
	$('#divStatusBlc', parent.document).css("visibility", "hidden");
	$('#divStatusTrf', parent.document).css("visibility", "hidden");
	
	var idVoucher = $('#idVoucher').val();
	//var page = $('#menuPageBatchno').val();
	var page = 1;
	var yearProcess = $('#yearProcess').val();
	
	loadIframe('iframeVoucherList', '');
	loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=displayVoucher&&pageBatchno='+page+'&yearProcess='+yearProcess); //REFRESH FRAME*/
}

function pilihBtnSave()
{
	var yearProcess = $('#yearProcess').val();
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
	var addEntries = $('#addEntries').val();
	
	var yearProcess = $('#yearProcess').val();
	var paramCari = $('#elementCariVoucher').val();
	//var amount = $('#amount').val();
	
	$('#idErrorMsg').css('visibility','hidden'); 
	if($.trim( paidTo ) == '')
	{
		pesanError('Paid To is still empty', 'paidTo');
		return false;
	}
	if(paidTo.length > 70)
	{
		pesanError('Paid To / From characters is too long (Max 70 Characters)', 'paidTo');
		return false;
	}
	if(company == 'XXX')
	{
		pesanError('You have not selected Company', 'company');
		return false;
	}
	if(payType == 'XXX')
	{
		pesanError('You have not selected Payment Method', 'payMethod');
		return false;
	}
	if(bankCode == 'XXX')
	{
		pesanError('You have not selected Bank', 'bankCode');
		return false;
	}
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
	if( $.trim( yearProcess ) != ambilTahun(datePaid) )
	{
		pesanError("Year Paid is must be "+yearProcess, "datePaid");
		return false;
	}
	if(currency == 'XXX')
	{
		pesanError('You have not selected Currency', 'currency');
		return false;
	}	
	/*if(amount.replace(/,/gi,'') == '')
	{
		pesanError('Amount is still empty', 'amount');
		return false;
	}*/
	
	var page = $('#menuPageBatchno').val();
	var answer  = confirm('Are you sure want to Save?');
	if(answer)
	{	
		pleaseWait();
		document.onmousedown=disableLeftClick;
		
		loadIframe('iframeVoucherList', '');
		loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi='+aksiSimpanVoucher+'&idVoucher='+idVoucher+'&paidTo='+encodeURIComponent( paidTo )+'&voucherType='+voucherType+'&company='+company+'&payType='+payType+'&bankCode='+bankCode+'&voucher='+voucher+'&reference='+reference+'&chequeNumber='+chequeNumber+'&invNo='+encodeURIComponent( invNo )+'&jobNo='+jobNo+'&datePaid='+datePaid+'&currency='+currency+'&addEntries='+encodeURIComponent( addEntries )+'&pageBatchno='+page+'&yearProcess='+yearProcess+'&paramCari='+paramCari);
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
	
	$('#chequeNumber').prop("disabled","disabled");
	$('#chequeNumber').css("background-color","EAEAEA");
	if(payMethod == "cheque")
	{
		$('#chequeNumber').prop("disabled","");
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
	if($('#amountHid').val() < 0) // JIKA AMOUNT MINUS
	{
		alert('Amount can\'t be minus');
		return false;
	}
	
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
	if($('#amountHid').val() < 0) // JIKA AMOUNT MINUS
	{
		alert('Amount can\'t be minus');
		return false;
	}
	
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


function klikRowVoucher(batchno, transferAcct, voucherType, paidTo, company, payType, bankCode, voucher, reference, chequeNumber, invNo, jobNo, datePaid, curr, amount, additional)
{
	kosongkanElementHid();
	
	$('#judulAddVoucher').html('EDIT VOUCHER&nbsp;'); // GANTI JUDUL MENJADI EDIT VOUCHER
	$('#aksiSimpanVoucher').val('simpanUbahVoucher');
	
	$('#divStatusBlc', parent.document).css("visibility", "hidden"); //  HIDDEN UNTUK TEKS BALANCE
	$('#idSpanBalance', parent.document).html('&nbsp;'); // KOSONGKAN TEKS BALANCE
	$('#divStatusTrf', parent.document).css("visibility", "hidden"); //  HIDDEN UNTUK TEKS SUKSES TRANSFER TO ACCOUNTING
	
	enabledBtn('btnRetrieve');
	enabledBtn('btnRetrieve');
	disabledBtn('btnSave');
	
	if(transferAcct == "Y")
	{	disabledBtn('btnDelete');	}
	if(transferAcct == "N")
	{	enabledBtn('btnDelete');	}
	
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
	$('#lastSelBank').val(bankCode);
	$('#voucherHid').val(voucher);
	$('#refHid').val(reference);
	$('#chequeNumberHid').val(chequeNumber);
	$('#invNoHid').val(invNo);
	$('#jobNoHid').val(jobNo);
	$('#datePaidHid').val(datePaid);
	$('#currHid').val(curr);
	$('#amountHid').val(amount);
	$('#addEntriesHid').val(additional);
	
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
	$('#addEntriesHid').val('');
	
	$('#tdBatchno').html( '&nbsp;' );
	$("input:radio[name='voucherType'][value='T']").click();
	$('#paidTo').val( '' );
	$('#company').val( 'XXX' );
	//$('#payMethod').val( 'cheque' );
	//$('#bankCode').val( $('#bankCodeFirst').val() );
	$('#payMethod').val( 'XXX' );
	$('#bankCode').val( 'XXX' );
	$('#voucher').val( '' );
	$('#reference').val( '' );
	
	$('#chequeNumber').val( '' );
	$('#chequeNumber').prop("disabled","");
	$('#chequeNumber').css("background-color","");
		
	$('#invNo').val( '' );
	$('#jobNo').val( '' );
	$('#datePaid').val( '' );
	//$('#currency').val( $('#currFirst').val() );
	$('#currency').val( 'XXX' );
	$('#amount').val( '' );
	$('#addEntries').val( '' );
	
	//$('#idErrorMsg').html(''); 
	//$('#idErrorMsg').css('visibility','hidden'); 
	$('#idErrorMsg2').css('visibility','hidden');
	$('#spanAmountTeks').html(''); 
	$('#spanAmountTeks').css('visibility','hidden'); 
	
	$('#spanJmlDesc').html('');
	$('#btnEditDesc').css("display","inline"); // MUNCULKAN KEMBALI TOMBOL EDIT DESCRIPTION
	$('#btnSaveDesc').css("display","none"); // HILANGKAN TOMBOL SAVE DESCRIPTION
	$('#btnCancelDesc').css("display","none"); // HILANGKAN TOMBOL CANCEL DESCRIPTION
	loadIframe('iframeDescList', 'templates/halDescList.php');
	//loadIframe('iframeDescList', 'templates/halDescList.php?aksi=tambahDesc&idVoucher='+idVoucher+'&batchno='+batchno);
}

function allElemReadOnlyFalse()
{
	$('input[name="voucherType"]').prop("disabled", false);
	$('#transferVcrText').prop("disabled", false);
	$('#receiveVcrText').prop("disabled", false);
	$('#paidTo').prop("readonly", false);
	$('#company option').prop("disabled", false);
	$('#payMethod option').prop("disabled", false);
	$('#bankCode option').prop("disabled", false);
	$('#voucher').prop("readonly", false);
	$('#reference').prop("readonly", false);
	$('#chequeNumber').prop("readonly", false);
	$('#invNo').prop("readonly", false);
	$('#jobNo').prop("readonly", false);
	$('#datePaid').prop("readonly", false);
	$('#currency option').prop("disabled", false);
	$('#imgCalDatePaid').prop("disabled", false);
	//$('#amount').prop("readonly", false);
	$('#addEntries').prop("disabled", false);
}

function allElemReadOnlyTrue()
{
	$('input[name="voucherType"]').prop("disabled", true);
	$('#transferVcrText').prop("disabled", true);
	$('#receiveVcrText').prop("disabled", true);
	$('#paidTo').prop("readonly", true);
	$('#company option').prop("disabled", true);
	//$('#company option[value=XXX]').prop("disabled", false); // JIKA VALUE 'XXX' MAKA ENABLED
	$('#payMethod option').prop("disabled", true);
	//$('#payMethod option[value=cheque]').prop("disabled", false); // JIKA VALUE 'CHEQUE' MAKA ENABLED
	$('#bankCode option').prop("disabled", true);
	//$('#bankCode option[value='+$('#bankCodeFirst').val()+']').prop("disabled", false); // JIKA VALUE SAMA DENGAN NILAI DARI HIDDEN FILE YANG PUNYA ID BANKCODEFIRST MAKA ENABLED 
	$('#voucher').prop("readonly", true);
	$('#reference').prop("readonly", true);
	$('#chequeNumber').prop("readonly", true);
	$('#invNo').prop("readonly", true);
	$('#jobNo').prop("readonly", true);
	$('#datePaid').prop("readonly", true);
	$('#currency option').prop("disabled", true);
	//$('#currency option[value='+$('#currFirst').val()+']').prop("disabled", false);// JIKA VALUE SAMA DENGAN NILAI DARI HIDDEN FILE YANG PUNYA ID CURRFIRST MAKA ENABLED
	$('#imgCalDatePaid').prop("disabled", true);
	//$('#amount').prop("readonly", true);
	$('#addEntries').prop("disabled", true);
}

function klikPayMethodByRetieve(payMethod, selBankCode)
{	
	$.post( "halPostVoucher.php", { aksi:"klikPayMethod", payMethod:payMethod, lastSelBank:selBankCode }, function( data )
	{
		$('#tdBankCode').html( data );
	});
	
	$('#chequeNumber').prop("disabled","disabled");
	$('#chequeNumber').css("background-color","EAEAEA");
	if(payMethod == "cheque")
	{
		$('#chequeNumber').prop("disabled","");
		$('#chequeNumber').css("background-color","");
	}
	
	if(payMethod == "transfer" || payMethod == "cash")
	{
		$('#chequeNumber').val("");
	}
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
	klikPayMethodByRetieve($('#payTypeHid').val(), $('#bankCodeHid').val()); // AMBIL FUNCTION KETIKA MENU PAYMETHOD DIPILIH
	$('#voucher').val( $('#voucherHid').val() );
	$('#reference').val( $('#refHid').val() );
	
	$('#chequeNumber').val( $('#chequeNumberHid').val() );
	$('#chequeNumber').prop("disabled","");
	$('#chequeNumber').css("background-color","");
	if($('#payTypeHid').val() == "transfer" || $('#payTypeHid').val() == "cash")
	{
		$('#chequeNumber').prop("disabled","disabled");
		$('#chequeNumber').css("background-color","EAEAEA");
	}
	
	$('#invNo').val( $('#invNoHid').val() );
	$('#jobNo').val( $('#jobNoHid').val() );
	$('#datePaid').val( $('#datePaidHid').val() );
	$('#currency').val( $('#currHid').val() );
	$('#amount').val( formatNumber( $('#amountHid').val() ) );
	$('#addEntries').val( $('#addEntriesHid').val() );
	
	$('#amount').css('color','333');
	$('#idErrorMsg2').css('visibility','hidden');
	if($('#amountHid').val() < 0) // JIKA AMOUNT MINUS
	{
		$('#amount').css('color','C00');
		$('#idErrorMsg2').css('visibility','visible');
	}
	
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
	
	$('#elementCariVoucher').css('visibility','hidden');
	$('#elementCariVoucher').val('');
	$('#judulAddVoucher').html('ADD VOUCHER&nbsp;');
	
	disabledBtn('btnRetrieve');
	enabledBtn('btnSearch');
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
	allElemReadOnlyFalse();
	
	$('#jobNo').prop("readonly", false);
	$('#divStatusBlc', parent.document).css("visibility", "hidden");
	$('#divStatusTrf', parent.document).css("visibility", "hidden");
	
	var idVoucher = $('#idVoucher').val();
	//var page = $('#menuPageBatchno').val();
	var page = 1;
	var yearProcess = $('#yearProcess').val();
	
	loadIframe('iframeVoucherList', '');
	loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=displayVoucher&&pageBatchno='+page+'&yearProcess='+yearProcess); //REFRESH FRAME
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

		var yearProcess = $('#yearProcess').val();
		var idVoucher = $('#idVoucher').val();
		var page = $('#menuPageBatchno').val();
		loadIframe('iframeVoucherList', '');
		loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=deleteVoucher&idVoucher='+idVoucher+'&pageBatchno='+page+'&yearProcess='+yearProcess);
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

function pilihBtnCancelSaveDesc(idVoucher)
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	var yearProcess = $('#yearProcess').val();
	var paramCari = $('#elementCariVoucher').val();
	var page = $('#menuPageBatchno').val();
/*	var aksiSimpanVoucher = $('#aksiSimpanVoucher').val();
	if(aksiSimpanVoucher == "simpanBaruVoucher")
	{
		page = 1;
	}*/
	
	$('#menuPageBatchno').val(page);
	loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=closePopUp&idVoucher='+idVoucher+'&pageBatchno='+page+'&yearProcess='+yearProcess+'&paramCari='+paramCari);

	/*enabledBtn('btnSave');
	enabledBtn('btnAddDesc');
	enabledBtn('btnLargeView');
	
	$('#btnEditDesc').css("display","inline");
	$('#btnSaveDesc').css("display","none");
	$('#btnCancelDesc').css("display","none");

	var idVoucher = $('#idVoucher').val();
	var batchno = $('#batchnoHid').val();
	loadIframe('iframeDescList', '');
	loadIframe('iframeDescList', 'templates/halDescList.php?aksi=display&idVoucher='+idVoucher+'&batchno='+batchno);*/
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
		var amountPtg = window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val().split(".");
		//alert(parseInt(amountPtg[0].replace(/,/g,"")));
		if($.trim( window.frames['iframeDescList'].$('#desc'+splitAllIdDesc[i]).val() ) == "")
		{ // JIKA DESCRIPTION BELUM DIISI
			alert('Description '+(i+1)+' is still empty');
			window.frames['iframeDescList'].$('#desc'+splitAllIdDesc[i]).focus();
			return false;
		}
		if(window.frames['iframeDescList'].$('#desc'+splitAllIdDesc[i]).val().length > 70)
		{ // JIKA JUMLAH TEKS DESCRIPTION LEBIH DARI 70 KARAKTER
			alert('Description '+(i+1)+' characters is too long (Max 70 Characters)');
			return false;
		}
		if($.trim( window.frames['iframeDescList'].$('#descBookSts'+splitAllIdDesc[i]).val() ) == "XXX")
		{ // JIKA BOOK STS BELUM DIPILIH
			alert('Book Sts Description'+(i+1)+' has not been Chosen');
			window.frames['iframeDescList'].$('#descBookSts'+splitAllIdDesc[i]).focus();
			return false;
		}
		if($.trim( window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val() ) == "" || window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val() == 0.00 || window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val() == 0)
		{ // JIKA AMOUNT BELUM DIISI
			var answer  = confirm('Amount Description '+(i+1)+' is still empty, Are you want to Continued?');
			if(!answer)
			{	window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).focus();return false;	}
		}
		if(parseInt(amountPtg[0].replace(/,/g,"")) > 999999999999)
		{
			alert('Amount Description '+(i+1)+' cant more than 999,999,999,999.99');
			window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).focus();
			return false;
		}
		
		if($.trim( window.frames['iframeDescList'].$('#perkiraan'+splitAllIdDesc[i]).val() ) == "")
		{ // JIKA ACCOUNT BELUM DIISI
			var answer  = confirm('Acct # Description '+(i+1)+' is still empty, Are you want to Continued?');
			if(!answer)
			{	window.frames['iframeDescList'].$('#perkiraan'+splitAllIdDesc[i]).focus();return false;	}
		}
		if(window.frames['iframeDescList'].$('#perkiraan'+splitAllIdDesc[i]).val() != "" && window.frames['iframeDescList'].$('#perkiraan'+splitAllIdDesc[i]).val().length != 5)
		{ // JIKA JUMLAH TEKS TIDAK SAMA DENGAN 5
			alert('Acct # Description '+(i+1)+' text length must be 5');
			window.frames['iframeDescList'].$('#perkiraan'+splitAllIdDesc[i]).focus(); 
			return false;	
		}
		
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
	
	var answer  = confirm('Are you sure want to Save All Description?');
	if(answer)
	{	
		pleaseWait();
		document.onmousedown=disableLeftClick;

		loadIframe('iframeDescList', '');
		loadIframe('iframeDescList', 'templates/halDescList.php?aksi=saveDesc&idVoucher='+idVoucher+'&batchno='+batchno+'&bookStsVoucher='+bookStsVoucher+'&allIdDesc='+allIdDesc+allKetDesc+allDescBookSts+allDescAmount+allPerkiraan+allSubAcc+allDescDiv+allBooksts+allVesCode);
	}
	else
	{	return false;	}
}

function pilihBtnLargeView()
{
	var idVoucher = $('#idVoucher').val();
	var batchno = $('#batchnoHid').val();
	//alert('pilihBtnLargeView');
	$('#hrefThickbox').prop('href','templates/halPopup.php?aksi=descLargeView&idVoucher='+idVoucher+'&batchno='+batchno+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=550&width=975&modal=true');
	$('#hrefThickbox').click();
}
// #####################################################################################
// POP UP WINDOWD SUCCESFULLY SAVE
// #####################################################################################
function closePopupNewVoucher(idVoucher, yearProcess, moreNewInv)
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
		parent.loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=closePopUp&idVoucher='+idVoucher+'&pageBatchno='+page+'&yearProcess='+yearProcess); //REFRESH FRAME
	}
}

function closePopup(idVoucher)
{
	parent.tb_remove(false);
	
	var yearProcess = $('#yearProcess').val();
	var paramCari = $('#elementCariVoucher').val();
	var page = $('#menuPageBatchno').val();
	var aksiSimpanVoucher = $('#aksiSimpanVoucher').val();
	if(aksiSimpanVoucher == "simpanBaruVoucher")
	{
		page = 1;
	}
	
	$('#menuPageBatchno').val(page);
	
	parent.loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=closePopUp&idVoucher='+idVoucher+'&pageBatchno='+page+'&yearProcess='+yearProcess+'&paramCari='+paramCari); //REFRESH FRAME
}

function closePopupCari(idVoucher, page)
{
	//alert(idVoucher, page);
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
	if($('#amountHid').val() < 0) // JIKA AMOUNT MINUS
	{
		alert('Amount can\'t be minus');
		return false;
	}
	
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
	
	if(paidTo.length > 70)
	{
		pesanError('Paid To / From characters is too long (Max 70 Characters)', 'paidTo');
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

	// #########################################################
	// CEK KARAKTER KETERANGAN LEBIH DARI 70 DI DESCRIPTION LIST
	var allIdDesc = window.frames['iframeDescList'].$('#allIdDesc').val(); // AMBIL NILAI HIDDEN ELEMENT DGN ID 'ALLIDDESC' YANG BERISI KUMPULAN IDDESC BERDASAR VOUCHER DIPILIH
	var splitAllIdDesc = allIdDesc.split(","); // PISAHKAN IDDESC YANG DIDAPAT BERDASAR KOMA
	var pjgSplitAllIdDesc = splitAllIdDesc.length; // SETELAH DIPISAH BERDASAR KOMA DAPAT KAN JUMLAH IDDESC YANG DIDAPAT BIASANYA ADA 2 KOSONG
	for(var i=0; i<=(pjgSplitAllIdDesc-2);i++)
	{
		if(window.frames['iframeDescList'].$('#desc'+splitAllIdDesc[i]).val().length > 70) // JIKA KARAKTER KETERANGAN LEBIH DARI 70
		{ // JIKA JUMLAH TEKS DESCRIPTION LEBIH DARI 70 KARAKTER
			alert('Description '+(i+1)+' characters is too long (Max 70 Characters)');
			return false;
		}
		if($.trim( window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val() ) == "" || window.frames['iframeDescList'].$('#descAmount'+splitAllIdDesc[i]).val() == 0.00)
		{ // JIKA AMOUNT BELUM DIISI
			alert('Amount Description '+(i+1)+' is still empty');
			return false;
		}
		if($.trim( window.frames['iframeDescList'].$('#perkiraan'+splitAllIdDesc[i]).val() ) == "")
		{ // JIKA ACCOUNT BELUM DIISI
			alert('Acct # Description '+(i+1)+' is still empty');
			return false;
		}
		if(window.frames['iframeDescList'].$('#perkiraan'+splitAllIdDesc[i]).val() != "" && window.frames['iframeDescList'].$('#perkiraan'+splitAllIdDesc[i]).val().length != 5)
		{ // JIKA JUMLAH TEKS TIDAK SAMA DENGAN 5
			alert('Acct # Description '+(i+1)+' text length must be 5');
			return false;	
		}
	}
	
	pleaseWait();
	document.onmousedown=disableLeftClick;
	disabledBtn('btnPayTransAcct');
		
		
	var answer  = confirm('Are you sure want to Transfer to Accounting?');
	if(answer)
	{	
		//pleaseWait();
		//document.onmousedown=disableLeftClick;
			
		var yearProcess = $('#yearProcess').val();
		var idVoucher = $('#idVoucher').val();
		var batchno = $('#batchnoHid').val();
		var page = $('#menuPageBatchno').val();
		
		//loadIframe('iframeCekBalance', '');
		//loadIframe('iframeCekBalance', 'templates/halCekBalance.php?aksi=transToAcct&idVoucher='+idVoucher+'&batchno='+batchno); //REFRESH FRAME
		loadIframe('iframeVoucherList', '');
		loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=transToAcct&idVoucher='+idVoucher+'&batchno='+batchno+'&pageBatchno='+page+'&yearProcess='+yearProcess); //REFRESH FRAME
	}
	else
	{	
		doneWait();
		panggilEnableLeftClick();
		enabledBtn('btnPayTransAcct');
		return false;	
	}
	
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
		parent.loadIframe('iframeDescList', 'templates/halDescList.php?idVoucher='+idVoucher+'&batchno='+batchno);

	});
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
		//parent.$('#iframeDescList').prop('src','../templates/halPopupDescList.php?aksi=display&idVoucher='+idVoucher+'&batchno='+batchno);
		parent.doneWait();
		parent.panggilEnableLeftClick();
		
		parent.loadIframe('iframeDescList', '');
		parent.loadIframe('iframeDescList', '../templates/halPopupDescList.php?idVoucher='+idVoucher+'&batchno='+batchno);
	});
}

function klikBtnPageBatchno(aksi)
{
	if($('#aksiSimpanVoucher').val() == "simpanUbahVoucher")
	{
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
		allElemReadOnlyFalse();
		
		$('#jobNo').prop("readonly", false);
		$('#divStatusBlc', parent.document).css("visibility", "hidden");
		$('#divStatusTrf', parent.document).css("visibility", "hidden");
	}
	
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
		
	var yearProcess = $('#yearProcess').val(); 
	
	loadIframe('iframeVoucherList', '');
	loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi='+aksiKirim+'&paramCari='+paramCari+'&pageBatchno='+page+'&yearProcess='+yearProcess);
}

function klikMenuPageBatchno(page)
{
	if($('#aksiSimpanVoucher').val() == "simpanUbahVoucher")
	{
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
		allElemReadOnlyFalse();
		
		$('#jobNo').prop("readonly", false);
		$('#divStatusBlc', parent.document).css("visibility", "hidden");
		$('#divStatusTrf', parent.document).css("visibility", "hidden");
	}
	
	var yearProcess = $('#yearProcess').val(); 
	var paramCari = $('#elementCariVoucher').val();
	loadIframe('iframeVoucherList', '');
	loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=ketikElementCariVo&paramCari='+paramCari+'&pageBatchno='+page+'&yearProcess='+yearProcess);
}

function klikBtnSearch()
{
	var yearProcess = $('#yearProcess').val(); 
	var idVoucher = $('#idVoucher').val();
	var batchno = $('#batchnoHid').val();
	var page = $('#menuPageBatchno').val();
	
	$('#hrefThickbox').prop('href','templates/halPopup.php?aksi=cari&idVoucher='+idVoucher+'&batchno='+batchno+'&pageBatchno='+page+'&yearProcess='+yearProcess+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&width=800&height=500&modal=true');
	$('#hrefThickbox').click();
}

function klikYearProcess(status)
{
	$('#hrefThickbox').prop('href','templates/halPopUp.php?aksi=gantiTahun&status='+status+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=300&width=200&modal=true');
	$('#hrefThickbox').click();
}

function cancelChooseYear()
{
	parent.tb_remove(false);
	//parent.loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=closePopUp&idVoucher='+idVoucher+'&pageBatchno='+page); //REFRESH FRAME
}

function chooseYear(year)
{
	parent.tb_remove(false);
	
	$('#yearProcess').val(year);
	$('#spanYearProcess').html(year);
	$('#statusYearProc').val('');
	
	klikBtnRefresh();
}

function ambilTahun(str)
{
	var thn = str.substring(6,str.length)
	var bln = str.substring(3,5)
	var tgl = str.substring(0,2)
	return thn;
}