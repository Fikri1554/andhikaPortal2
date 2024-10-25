// JavaScript Document
function windowDisplayPrepare()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	teksMap("PAYMENT > PREPARE FOR PAYMENT");
	emptyDetail();
	
	var sortBy = $('#sortBy').val();
	var ascBy = $('#ascBy').val();
	var jenisPayment = $('#jenisPayment').val();
	var prepareBy = $('#prepareBy').val();
	
	$('#prepareByHidden').val( prepareBy );
	disabledBtn('btnPayPrepAddGroup');
	
	loadIframe('iframeList', '');
	loadIframe('iframeList', 'templates/halPrepareList.php?aksi=display&sortBy='+sortBy+'&ascBy='+ascBy+'&jenisPayment='+jenisPayment+'&prepareBy='+prepareBy);
}

function teksMap(value)
{
	parent.document.getElementById('idTeksMap').innerHTML = "<a>"+value+"</a>";
}

function setup()
{
	//var nextTransNo =  new InputMask("######", "nextTransNo");
}

function klikRowPrepare(idMailInv, sNo, billComp, kreditAcc, credName, currency, amount)
{
	$('#sNo').html( sNo );
	$('#billComp').html( billComp );
	$('#credName').html( credName );
	$('#currency').html( currency );

	$('#amount').html( '('+currency+') '+amount );
	
	$('#kreditAccGrupPilih').val( kreditAcc );
	$('#senVenPrepPilih').val( credName );
	$('#billCompPrepPilih').val( billComp );
	$('#currencyPrepPilih').val( currency );
}

function emptyDetail()
{
	$('#sNo').html( '' );
	$('#billComp').html( '' );
	$('#amount').html( '' );
	$('#credNumb').html( '' );
	$('#credName').html( '' );
	$('#poNumber').html( '' );
}

function klikBtnAddGroup()
{
	var nilai = "NO";
	var jmlRowGrup = $('#jmlRowGrup').val();
	var idMailInv = $('#idMailInv').val();
	//var idMailPrepPay = window.frames["iframeList3"].document.getElementById("idMailPrepPay").value;
	//var arrIdMailPrepPay = idMailPrepPay.split(',');
	//var a = arrIdMailPrepPay.indexOf(idMailInv); 
	//alert('&'+$('#senVenGrupPertama').val()+'& = &'+$('#senVenPrepPilih').val()+'& / &'+$('#billCompGrupPertama').val()+'& = &'+$('#billCompPrepPilih').val()+'& / &'+$('#currencyGrupPertama').val()+'& = &'+$('#currencyPrepPilih').val()+'&');
	//pleaseWait();
	//document.onmousedown=disableLeftClick;
	
	if(jmlRowGrup == '0') // JIKA JUMLAH DATA DI IFRAM GROUP ITEM ADALAH KOSONG
	{
		nilai = "YES";
	}
	else if(jmlRowGrup != '0') // JIKA JUMLAH DATA DI IFRAM GROUP ITEM TIDAK KOSONG
	{	// MAKA INVOICE YANG AKAN DI TAMBAHKAN DI GROUP ITEM YANG TIDAK KOSONG HARUS SAMA DALAM 3 HAL YAITU (SENDERVENDORNAME, BILLING COMPANY DAN CURRENCY)
		//if( $('#senVenGrupPertama').val() == $('#senVenPrepPilih').val() && $('#billCompGrupPertama').val() == $('#billCompPrepPilih').val() && $('#currencyGrupPertama').val() == $('#currencyPrepPilih').val() )
		// MAKA INVOICE YANG AKAN DI TAMBAHKAN DI GROUP ITEM YANG TIDAK KOSONG HARUS SAMA DALAM 3 HAL YAITU (KREDIT ACCOUNT, BILLING COMPANY DAN CURRENCY)
		if( $('#kreditAccGrupPertama').val() == $('#kreditAccGrupPilih').val() && $('#billCompGrupPertama').val() == $('#billCompPrepPilih').val() && $('#currencyGrupPertama').val() == $('#currencyPrepPilih').val() )
		{
			nilai = "YES";
		}
		else
		{
			alert('CREDITOR NAME, BILLING COMPANY, CURRENCY must be same with the Group Items');
			nilai = "NO";
		}
	}
	
	if(nilai == "YES")
	{
		var answer  = confirm('Are you sure want to Add to Grouped Items?');
		if(answer)
		{	
			pleaseWait();
			document.onmousedown=disableLeftClick;
		
			$.post( "halPostMailInv.php", { aksi:"cekIdMailInvPrepPay", idMailInv:$('#idMailInv').val() }, function( data )
			{	$('#idCekIdMailInvSama').html( data );	}); // CEK APAKAH INVOICE SUDAH PERNAH DI ADD TO GROUP ITEM BELUM SAMA USER LAIN
			
			setTimeout(function()
			{
				if( $('#idMailInvSamaAdaTidak').val() == 'ada' ) // JIKA SUDAH PERNAH DI ADD GROUP ITEM MAKA MUNCULKAN PESAN ERROR
				{
					doneWait();
					panggilEnableLeftClick();

					alert('Sorry, Already Add to Grouped Items by another user');
					windowDisplayPrepare();
					return false;
				}
				else
				{
					loadIframe('iframeList3', '');
					loadIframe('iframeList3', 'templates/halPrepareList.php?aksi=addGroupItem&idMailInv='+idMailInv);
				}
			}, 500);
		}
		else
		{	
			//doneWait();
			//panggilEnableLeftClick();
			return false;
		}
	}
}

function klikBtnResGroup()
{
	var jmlRowGrup = $('#jmlRowGrup').val();
	
	if(jmlRowGrup != '0')
	{
		var answer  = confirm('Are you sure want to Reset Grouped Items?');
		if(answer)
		{	
			pleaseWait();
			document.onmousedown=disableLeftClick;
	
			loadIframe('iframeList3', '');
			loadIframe('iframeList3', 'templates/halPrepareList.php?aksi=resetGroupItem');
		}
		else
		{	return false;	}
	}
}

function klikBtnAssignTransNo()
{
	var jmlRowGrup = $('#jmlRowGrup').val();
	
	if(jmlRowGrup != '0')
	{
		var answer  = confirm('Are you sure want to Assign to Trans. No?');
		if(answer)
		{	
			//pleaseWait(); 
			//document.onmousedown=disableLeftClick;
			
			$.post( "halPostMailInv.php", { aksi:"cekAssignYesNo", idMailInv:$('#iframeList2').contents().find('#idMailInvHeader').val() }, function( data )
			{	$('#idCekAssignYesNo').html( data );	}); // CEK APAKAH INVOICE SUDAH PERNAH DI ADD TO GROUP ITEM BELUM SAMA USER LAIN
			
			setTimeout(function()
			{
				if( $('#assignYesNo').val() == 'yes' ) // JIKA SUDAH PERNAH DI ADD GROUP ITEM MAKA MUNCULKAN PESAN ERROR
				{
					doneWait();
					panggilEnableLeftClick();
					
					alert('Sorry, Already Assign To Trans. No by another user');
					$('#formPaymentPrepare').submit();
					return false;
				}
				else
				{
					loadIframe('iframeList3', '');
					loadIframe('iframeList3', 'templates/halPrepareList.php?aksi=assignTransNo');
				}
			}, 500);
		}
		else
		{	return false;	}
	}
	else
	{
		alert('Payment Groupings is still empty');
	}
}

function windowDisplayBatch()
{
	setupPayBatch();
	
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	teksMap("PAYMENT > PAYMENT BY BATCH");
	
	$('#payMethod').attr("disabled","disabled");
	$('#payMethod').css("background-color","E9E9E9");
	$('#bankCode').attr("disabled","disabled");
	$('#bankCode').css("background-color","E9E9E9");
	$('#voucher').attr("disabled","disabled");
	$('#voucher').css("background-color","E9E9E9");
	$('#reference').attr("disabled","disabled");
	$('#reference').css("background-color","E9E9E9");
	$('#chequeNumber').attr("disabled","disabled");
	$('#chequeNumber').css("background-color","E9E9E9");
	$('#datePaid').attr("disabled","disabled");
	$('#datePaid').css("background-color","E9E9E9");
	$('#amtConv').attr("disabled","disabled");
	$('#amtConv').css("background-color","E9E9E9");
	$('#currency').attr("disabled","disabled");
	$('#currency').css("background-color","E9E9E9");
	$('#adjAcc').attr("disabled","disabled");
	$('#adjAcc').css("background-color","E9E9E9");
	$('#adjAmt').attr("disabled","disabled");
	$('#adjAmt').css("background-color","E9E9E9");
	$('#paidToFrom').attr("disabled","disabled");
	$('#paidToFrom').css("background-color","E9E9E9");
	
	$('#payMethod').val( 'cheque' );
	$('#bankCode').val( $('#bankCodeFirst').val() );
	$('#voucher').val( '' );
	$('#reference').val( '' );
	$('#chequeNumber').val( '' );
	$('#amtConv').val( '' );
	$('#currency').val( 'IDR' );
	$('#adjAcc').val( '' );
	$('#adjAmt').val( '' );
	$('#paidToFrom').val( '' );
	
	loadIframe('iframeList', '');
	loadIframe('iframeList', 'templates/halBatchList.php?aksi=displayTransNo&pageTransno=1');
}

function setupPayBatch()
{
	var errorMessageDate = "Invalid date: ${value}. Expected format: ${mask}";
	var datePaid = new DateMask("dd/MM/yyyy", "datePaid");
	datePaid.validationMessage = errorMessageDate;
	
	new NumberMask(new NumberParser(0, "", "", true), "voucher", 5);
	new NumberMask(new NumberParser(0, "", "", true), "reference", 5);
	
	//if($('#amtConvMaskk').attr("disabled") == false)
	//{
		var amtConvMaskk = new NumberParser(2, ".", ",", true);
		var amtConvMask = new NumberMask(amtConvMaskk, "amtConv", 12);
	//}
	
	var adjAccMask = new InputMask(JST_MASK_NUMBERS, "adjAcc");
	new SizeLimit("adjAcc", 5, "");
	
	$(document).ready(function()
	{		
		$(this).mouseover(function(e)
		{
			var idActElem = $(document.activeElement).attr("id");
			var container = $('#divIframeList');
			
			$('#divIframeList').css('width', '355');
			$('#divIframeList').css('z-index', '1');
				
			if (!container.is(e.target) // if the target of the click isn't the container...
				&& container.has(e.target).length === 0 // ... nor a descendant of the container
				 // APAKAH ELEMENT YANG AKTIF TESVEND APA BUKAN
				) 
			{
				$('#divIframeList').css('width', '205');
				$('#divIframeList').css('z-index', '0');
			}
		});	
		
		/*$('#paidToFrom').keyup(function(e)
		{
			$.post( "halPostMailInv.php", { aksi:"ketikPaidToFrom", idMailInv:$('#idMailInv').val(), paidToFrom:$(this).val() }, function( data )
			{	$(this).val( data );	
			}); //KETIKA FIELD PAID TO/FROM DIISI OTOMATIS MENGISI KE DATABASE
		});*/
	});
}


function klikRowBatch(idMailInv, transNo, amount, paid, cancelPaid, reasonCancelPaid, payType, bankCode, voucher, reference, chequeNumber, datePaid, currency, amtConv, currConv, adjAcc, adjAmt)
{
	$('#transNo').val( transNo );
	$('#statusPaid').val( paid );
	$('#statusCancelPaid').val( cancelPaid );
	$('#reasonStatusCancelHid').val( reasonCancelPaid );
	$('#totalAmount').val( amount );
	
	$('#payTypeHid').val( payType );
	$('#bankCodeHid').val( bankCode );
	$('#voucherHid').val( voucher );
	$('#refHid').val( reference );
	$('#chequeNumberHid').val( chequeNumber );
	$('#datePaidHid').val( datePaid );
	$('#currHid').val( currency );
	$('#amtConvHid').val( amtConv );
	$('#currConvHid').val( currConv );
	$('#adjAccHid').val( adjAcc );
	$('#adjAmtHid').val( adjAmt );
}

function klikBtnRetBatch()
{
	enabledBtn( 'btnPayTransAcct' );
	disabledBtn( 'btnPayPrintVoucher' );
	disabledBtn( 'btnCancelled' );
	$('#divStatusPaid').css('visibility','hidden');
	$('#divStatusCancel').css('visibility','hidden');
	
	$('#tdTransNo').html( $('#transNo').val() );
	
	$('#payMethod').attr("disabled","");
	$('#payMethod').css("background-color","");
	$('#bankCode').attr("disabled","");
	$('#bankCode').css("background-color","");
	$('#voucher').attr("disabled","");
	$('#voucher').css("background-color","");
	$('#reference').attr("disabled","");
	$('#reference').css("background-color","");
	$('#chequeNumber').attr("disabled","");
	$('#chequeNumber').css("background-color","");
	$('#datePaid').attr("disabled","");
	$('#datePaid').css("background-color","");
	$('#imgCalDatePaid').attr("disabled","");
	$('#paidToFrom').attr("disabled","");
	$('#paidToFrom').css("background-color","");
	
	$('#amtConv').attr("disabled","");
	$('#amtConv').css("background-color","");
	//$('#currency').attr("disabled","disabled");
	document.getElementById('currency').disabled = true;
	$('#currency').css("background-color","");
	$('#adjAcc').attr("disabled","");
	$('#adjAcc').css("background-color","");
	//$('#adjAmt').attr("disabled","");
	$('#adjAmt').css("background-color","");
	
	/* JIKA AMOUNT TO BE PAID YANG DIPERUNTUKAN HANYA UNTUK USD MAKA CODING DIBAWAH INI DIPAKAI
	$('#amtConv').attr("disabled","disabled"); 
	$('#amtConv').css("background-color","E9E9E9");
	if($('#currHid').val() != 'IDR')
	{
		$('#amtConv').attr("disabled","");
		$('#amtConv').css("background-color","");
	}*/
	
	$('#payMethod').val( 'cheque' );
	$('#bankCode').val( $('#bankCodeFirst').val() );
	$('#voucher').val( '' );
	$('#reference').val( '' );
	$('#chequeNumber').val( '' );
	$('#datePaid').val( $('#dateToday').val() );
	$('#amtConv').val( '' );
	$('#currency').val( $('#currConvHid').val() );
	$('#adjAcc').val( '' );
	$('#adjAmt').val( '' );
	$.post( "halPostMailInv.php", { aksi:"detilMailInv", idMailInv:$('#idMailInv').val(), field:"paidtofrom", }, function( data )
	{	$('#paidToFrom').val( data );	}); // ISI PAIDTOFROM DENGAN DATA YANG DIAMBIL DARI DATABASE VIA AJAX
	$('#reasonStatusCancel').val( '' ); //ELEMENT REASON DARI STATUS CANCEL PAID
	
	//$('#trAdjusment').css('display','none');
	if($('#currHid').val() == 'IDR')
	{
		$('#trAdjusment').css('display','table-row');
		
	}
	if($('#currHid').val() != 'IDR')
	{
		$('#trAdjusment').css('display','none');
	}
	if($('#statusPaid').val() == 'N')
	{
		enabledBtn( 'btnPayPrintVoucher' );
		enabledBtn( 'btnCancelled' );
		$('#payMethod').attr('disabled','');
		$('#bankCode').attr('disabled','');
		$('#voucher').attr( 'disabled', '' );
		$('#reference').attr( 'disabled', '' );
		$('#chequeNumber').attr( 'disabled', '' );
		$('#datePaid').attr( 'disabled', '' );
		$('#imgCalDatePaid').attr( 'disabled', '' );
		$('#amtConv').attr('disabled','');
		$('#paidToFrom').attr( 'disabled', '' );
		document.getElementById('currency').disabled = true;
		/*if (document.all && document.querySelector && !document.addEventListener)  // if ie 8
		{
			document.getElementById('currency').disabled = true;
			//$('#currency').attr('disabled', true); 
		}
		if (!document.all) // if ie 11
		{
			document.getElementById('currency').disabled = true;
			//$('#currency').prop('disabled', true); 
		}*/
		
		$('#adjAcc').attr('disabled','');
		//$('#adjAmt').attr('disabled','');
	}
	
	if($('#currHid').val() != 'IDR') // JIKA NILAI CURRHID DI HIDDEN FILE ADALAH USD MAKA MENU CURRENCY ENABLE
	{
		document.getElementById('currency').disabled = false;
		/*if (document.all && document.querySelector && !document.addEventListener) 
		{
			$('#currency').attr('disabled', false); 
		}
		if (!document.all) 
		{
			$('#currency').prop('disabled', false);
		}*/

		/*if(isIE() == 11)
		{
			$('#currency').prop('disabled', ''); 
		}*/
		//$('#currency').css('pointer-events','none');
	}
	
	if( $('#statusPaid').val() == 'Y' || $('#statusCancelPaid').val() == 'Y')
	{
		disabledBtn( 'btnPayTransAcct' );
		enabledBtn( 'btnPayPrintVoucher' );
		disabledBtn( 'btnCancelled' );
		
		$('#divStatusPaid').css('visibility','visible');
		
		if($('#statusCancelPaid').val() == 'Y')
		{
			disabledBtn( 'btnPayPrintVoucher' );
			$('#divStatusPaid').css('visibility','hidden');
			$('#divStatusCancel').css('visibility','visible');
			$('#reasonStatusCancel').val( $('#reasonStatusCancelHid').val() ); 
		}
		
		$('#payMethod').val( $('#payTypeHid').val() );
		$('#bankCode').val( $('#bankCodeHid').val() );
		$('#voucher').val( $('#voucherHid').val() );
		$('#reference').val( $('#refHid').val() );
		$('#chequeNumber').val( $('#chequeNumberHid').val() );
		$('#datePaid').val( $('#datePaidHid').val() );
		$('#amtConv').val( formatNumber($('#amtConvHid').val()) );
		$('#adjAcc').val( $('#adjAccHid').val() );
		$('#adjAmt').val( formatNumber($('#adjAmtHid').val()) );
		$.post( "halPostMailInv.php", { aksi:"detilMailInv", idMailInv:$('#idMailInv').val(), field:"paidtofrom", }, function( data )
		{	$('#paidToFrom').val( data );	}); // ISI PAIDTOFROM DENGAN DATA YANG DIAMBIL DARI DATABASE VIA AJAX
		
		$('#payMethod').attr('disabled','disabled');
		$('#bankCode').attr('disabled','disabled');
		$('#voucher').attr( 'disabled', 'disabled' );
		$('#reference').attr( 'disabled', 'disabled' );
		$('#chequeNumber').attr( 'disabled', 'disabled' );
		$('#datePaid').attr( 'disabled', 'disabled' );
		$('#imgCalDatePaid').attr( 'disabled', 'disabled' );
		$('#amtConv').attr('disabled','disabled');
		$('#currency').attr('disabled', 'disabled'); 
		$('#adjAcc').attr('disabled','disabled');
		$('#adjAmt').attr('disabled','disabled');
		$('#paidToFrom').attr( 'disabled', 'disabled' );
	}
	
	if($('#aksesBtnPayTransAcct').val() == "N")
		disabledBtn( 'btnPayTransAcct' );
	if($('#aksesBtnPayPrintVoucher').val() == "N")
		disabledBtn( 'btnPayPrintVoucher' );
	if($('#aksesBtnCancelled').val() == "N")
		disabledBtn( 'btnCancelled' );
	
	 
	loadIframe('iframeList2', '');
	loadIframe('iframeList2', 'templates/halBatch2List.php?transNo='+$('#transNo').val());
	
	loadIframe('iframeList3', '');
	loadIframe('iframeList3', 'templates/halBatch3List.php?transNo='+$('#transNo').val());
}

function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

function isiAmtConv()
{
	var totalAmount = $('#totalAmount').val();
	var amtConv = $('#amtConv').val();
	var currency = $('#currHid').val();
	 
	$.post( "halPostMailInv.php", { aksi:"isiAmtConv", totalAmount:totalAmount, amtConv:amtConv, currency:currency }, function( data )
	{
		$('#adjAmt').val( data );
	});
}

function klikPayMethod(payMethod)
{	
	$.post( "halPostMailInv.php", { aksi:"klikPayMethod", payMethod:payMethod, lastSelBank:$('#lastSelBank').val() }, function( data )
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
}

function klikBankMenu(bankCode)
{
	//SIMPAN BANK CODE YANG TERAKHIR DIPILIH
	$('#lastSelBank').val( $('#bankCode').val() );
}

function klikBtnTranstoAcct()
{
	var barisTransno = $('#barisTransno').val(); // ID DARI TR YANG DIPILIH DI IFRAME TRANSNO DATA
	var idMailInv = $('#idMailInv').val();
	var transNo = $('#transNo').val();
	var voucher = $('#voucher').val();
	var reference = $('#reference').val();
	var chequeNumber = $('#chequeNumber').val();
	var datePaid = $('#datePaid').val();
	var payType = $('#payMethod').val();
	var bankCode = $('#bankCode').val();
	var amtConv = $('#amtConv').val();
	var currency = $('#currency').val();
	var adjAcc = $('#adjAcc').val();
	var adjAmt = $('#adjAmt').val();
	//alert(adjAcc);
	
	$('#idErrorMsg').css('visibility','hidden'); 
	if( $.trim( voucher ) == '' )
	{
		pesanError("Voucher Number is still empty", "voucher");
		return false;
	}
	
	if( $.trim( reference ) == '' )
	{
		pesanError("Reference Number is still empty", "reference");
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
	
	if($('#currHid').val() == 'IDR')
	{
		if( $.trim( amtConv ) != '' &&  $.trim( amtConv ) != '0.00') // JIKA AMOUNT TO BE PAID TIDAK KOSONG
		{
			if( $.trim ( adjAcc ) == '' )
			{
				pesanError('Adjusment Account is still empty', 'adjAcc');
				return false;
			}
			if( $('#adjAcc').val().length < 5)
			{
				pesanError('Adjusment Account text length must be 5', 'adjAcc');
				return false;
			}
		}
	}
	
	if( $.trim( amtConv ) != '') // JIKA AMOUNT TO BE PAID TIDAK KOSONG
	{
		var answer  = confirm('Amount to be Paid will be Adjusted, Are you sure want to Continue?');
		if(!answer)
		{	return false;	}
	}
	
	if(idMailInv != '')
	{
		
		
		var answer  = confirm('Are you sure want to Transfer to Accounting?');
		if(answer)
		{	
			pleaseWait();
			document.onmousedown=disableLeftClick;
			
			$.post( "halPostMailInv.php", { aksi:"cekPaidYesNo", idMailInv:$('#idMailInv').val() }, function( data )
			{	$('#paidYesNo').val( data );	}); // CEK APAKAH INVOICE SUDAH PERNAH DI ADD TO GROUP ITEM BELUM SAMA USER LAIN
			
			setTimeout(function()
			{
				if( $('#paidYesNo').val() == 'yes' ) //JIKA SUDAH PERNAH DI PAID OLEH USER LAIN 
				{
					doneWait();
					panggilEnableLeftClick();
					
					var answer  = confirm("Sorry, Already Paid by Another user.\n\rAre you wanto to Refresh page?");
					if(answer)
					{
						loadIframe('iframeList', 'templates/halBatchList.php?aksi=displayTransNo&barisTransno='+barisTransno+'&idMailInv='+idMailInv+'&transNo='+transNo); //REFRESH FRAME
						setTimeout(function()
						{
							var tes = window.frames["iframeList"].document.getElementById('tr'+barisTransno).click(); // CLICK ROW YANG SEBELUMNYA AKAN DI PAID
							setTimeout(function()
							{
								$('#btnRetBatch', parent.document).click(); // KLIK BTN RETRIEVE BATCH
							}, 400);		
						}, 250);					
					}
					else
					{
						return false;
					}
				}
				else//JIKA BELUM PERNAH DI DI PAID OLEH USER LAIN 
				{
					var menuPageTransno = $('#menuPageTransno').val();
					var paramCari = $('#elementCariBatch').val();
		
					loadIframe('iframeList', '');
					loadIframe('iframeList', 'templates/halBatchList.php?aksi=transToAcct&barisTransno='+barisTransno+'&idMailInv='+idMailInv+'&transNo='+transNo+'&voucher='+voucher+'&reference='+reference+'&chequeNumber='+chequeNumber+'&datePaid='+datePaid+'&payType='+payType+'&bankCode='+bankCode+'&amtConv='+amtConv+'&currency='+currency+'&adjAcc='+adjAcc+'&adjAmt='+adjAmt+'&paramCari='+paramCari+'&pageTransno='+menuPageTransno);
				}
			}, 250);
		}
		else
		{	
			return false;	}
	}
}

function klikBtnPrintVoucher()
{
	var idMailInv = $('#idMailInv').val();
	var datePaid = $('#datePaid').val();

	$.post( "halPostMailInv.php", { aksi:"printVoucher", idMailInv:$('#idMailInv').val(), paidToFrom:$('#paidToFrom').val() }, function( data )
	{	$('#paidToFrom').val( data );	
	}); //KETIKA FIELD PAID TO/FROM DIISI OTOMATIS MENGISI KE DATABASE
			
	setTimeout(function()
	{
		$('#formPrintVoucher').attr('action', 'halPrint.php?aksi=printVoucher&idMailInv='+idMailInv+'&datePaid='+datePaid);
		formPrintVoucher.submit();
	}, 250);
}

function klikBtnCariPrep()
{
	if( $('#elementCariPrep').css("visibility") == 'hidden' ) // MUNCULKAN ELEMENT
	{
		$('#elementCariPrep').css('visibility','visible');
	}
	else
	{ // SEMBUNYIKAN ELEMENT
		$('#elementCariPrep').css('visibility','hidden');
		$('#elementCariPrep').val('');
		windowDisplayPrepare();
	}
}

function ketikElementCariPrep(paramCari)
{
	var prepareBy = $('#prepareBy').val();
	
	loadIframe('iframeList', '');
	loadIframe('iframeList', 'templates/halPrepareList.php?aksi=ketikElementCariPrep&paramCari='+paramCari+'&prepareBy='+prepareBy);
}

function klikBtnCariBatch()
{
	if( $('#elementCariBatch').css("visibility") == 'hidden' ) // MUNCULKAN ELEMENT
	{
		$('#elementCariBatch').css('visibility','visible');
	}
	else
	{ // SEMBUNYIKAN ELEMENT
		$('#elementCariBatch').css('visibility','hidden');
		$('#elementCariBatch').val('');
		loadIframe('iframeList', '');
		loadIframe('iframeList', 'templates/halBatchList.php?aksi=displayTransNo');
	}
}

function ketikElementCariBatch(paramCari)
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	setTimeout(function()
	{
		var menuPageTransno = $('#menuPageTransno').val();
		loadIframe('iframeList', '');
		loadIframe('iframeList', 'templates/halBatchList.php?aksi=ketikElementCariBatch&paramCari='+paramCari+'&pageTransno='+menuPageTransno);
	}, 250);
	
}

function klikMenuPageTransno(page)
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	var paramCari = $('#elementCariBatch').val();
	loadIframe('iframeList', '');
	loadIframe('iframeList', 'templates/halBatchList.php?aksi=ketikElementCariBatch&paramCari='+paramCari+'&pageTransno='+page);
}

function klikBtnPageTransno(aksi)
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	var menuPageTransno = parseInt($('#menuPageTransno').val());
	var paramCari = $('#elementCariBatch').val();
	var maxPageTransno = parseInt($('#maxPageTransno').html());
	var page = 1;
	
	if(aksi == 'first')
	{	page = 1;	}
	
	if(aksi == 'previous')
	{
		if(menuPageTransno != 1)
		{	page = menuPageTransno - 1;	}
	}
	
	if(aksi == 'next')
	{
		if(menuPageTransno != maxPageTransno)
		{	page = menuPageTransno + 1;	}
		else
		{	page = maxPageTransno;	} // JIKA SUDAH NEXT SUDAH MENTOK SAMA DENGAN MAX JUMLAH HALAMAN 
	}
	
	if(aksi == 'last')
	{	page = maxPageTransno;	}
	
	loadIframe('iframeList', '');
	loadIframe('iframeList', 'templates/halBatchList.php?aksi=ketikElementCariBatch&paramCari='+paramCari+'&pageTransno='+page.toString());
}

// ######## OUTSTANDING

function klikSortByOutstanding()
{
	windowDisplayOutstanding();
	$('#kotakPrint').css('visibility','hidden');
}

function windowDisplayOutstanding()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	var sortBy = $('#sortBy').val();
	var ascBy = $('#ascBy').val();
	var jenisPayment = $('#jenisPayment').val();
	
	loadIframe('iframeList', '');
	loadIframe('iframeList', 'templates/halPaymentList.php?aksi=display&sortBy='+sortBy+'&ascBy='+ascBy+'&jenisPayment='+jenisPayment);
	
	$(document).ready(function()
	{
		$('#fromBarcode').focus(function()
		{
			$('#barcode').attr("checked","checked");
		});
		$('#toBarcode').focus(function()
		{
			$('#barcode').attr("checked","checked");
		});
		$('#fromDate').focus(function()
		{
			$('#batchno').attr("checked","checked");
		});
		$('#toDate').focus(function()
		{
			$('#batchno').attr("checked","checked");
		});
	});
}

function setupOutstanding()
{
	var fromBarcode = new NumberMask(new NumberParser(0, "", "", true), "fromBarcode", 5);
	var toBarcode = new NumberMask(new NumberParser(0, "", "", true), "toBarcode", 5);
	
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var fromDate = new DateMask("dd/MM/yyyy", "fromDate");
	fromDate.validationMessage = errorMessage;
	var toDate = new DateMask("dd/MM/yyyy", "toDate");
	toDate.validationMessage = errorMessage;
}

function klikBtnRetrieveBy()
{
	if( $('#kotakPrint').css("visibility") == 'hidden' ) // MUNCULKAN ELEMENT
	{
		$('#kotakPrint').css('visibility','visible');
	}
	else
	{ // SEMBUNYIKAN ELEMENT
		$('#kotakPrint').css('visibility','hidden');
	}
}

function klikBtnPrintOutstanding()
{
	var allIdMailInv=$('#iframeList').contents().find('#allIdMailInv').val();
	var printBy = $("input:radio[name='printBy']:checked").val();
	var fromBarcode = $('#fromBarcode').val();
	var toBarcode = $('#toBarcode').val();
	var fromDate = $('#fromDate').val();
	var toDate = $('#toDate').val();
	var company = $('#company').val();

	$('#fromBarcode').css('border-style','solid'); 
	$('#fromBarcode').css('border-color','A4B97F'); 
	$('#fromBarcode').css('border-width','1px');
	$('#toBarcode').css('border-style','solid'); 
	$('#toBarcode').css('border-color','A4B97F'); 
	$('#toBarcode').css('border-width','1px');
	$('#fromDate').css('border-style','solid'); 
	$('#fromDate').css('border-color','A4B97F'); 
	$('#fromDate').css('border-width','1px');
	$('#toDate').css('border-style','solid'); 
	$('#toDate').css('border-color','A4B97F'); 
	$('#toDate').css('border-width','1px');
	$('#company').css('border-color','A4B97F'); 
	$('#company').css('border-width','1px');
	
	if(printBy == "barcode")
	{
		if(trim(fromBarcode) == "")
		{
			$('#fromBarcode').css('border-color','C00');
			$('#fromBarcode').css('border-width','2px');
			return false;
		}
		if(trim(toBarcode) == "")
		{
			$('#toBarcode').css('border-color','C00');
			$('#toBarcode').css('border-width','2px');
			return false;
		}
	}
	
	if(printBy == "batchno")
	{
		if(trim(fromDate) == "")
		{
			$('#fromDate').css('border-color','C00');
			$('#fromDate').css('border-width','2px');
			return false;
		}
		if(trim(toDate) == "")
		{
			$('#toDate').css('border-color','C00');
			$('#toDate').css('border-width','2px');
			return false;
		}
	}
	
	if(company == "XXX")
	{
		$('#company').css('border-color','C00');
		$('#company').css('border-width','2px');
		return false;
	}
	
	if(allIdMailInv == "")
	{
		alert('Please check the list of data');
		return false;
	}

	alert('Please make sure you\'ve done RETRIEVE');
	
	$('#formPrintOutstanding').attr('action', 'halPrint.php?aksi=printOutstanding&allIdMailInv='+allIdMailInv+'&printBy='+printBy+'&fromBarcode='+fromBarcode+'&toBarcode='+toBarcode+'&fromDate='+fromDate+'&toDate='+toDate+'&company='+company);
	formPrintOutstanding.submit();
}

function klikCheckRow()
{
	var jmlCB =  $('#jmlCB').val();
	var allIdMailInv = "";
	for(var i = 1; i <= jmlCB; i++) //$jmlRow didapat dari skrip php diatas
	{
		if(document.getElementById('cek'+i).checked == true)
		{
			var idMailInvDipilih = document.getElementById('cek'+i).value;
			allIdMailInv += idMailInvDipilih+",";
		}
	}
	document.getElementById('allIdMailInv').value = allIdMailInv;
}

function klikBtnRetrieve()
{
	var sortBy = $('#sortBy').val();
	var ascBy = $('#ascBy').val();
	//var jenisPayment = $('#jenisPayment').val();
	
	var printBy = $("input:radio[name='printBy']:checked").val();
	var fromBarcode = $('#fromBarcode').val();
	var toBarcode = $('#toBarcode').val();
	var fromDate = $('#fromDate').val();
	var toDate = $('#toDate').val();
	var company = $('#company').val();
	$('#company').css('border-color','A4B97F'); 
	$('#company').css('border-width','1px');
	if(company == "XXX")
	{
		$('#company').css('border-color','C00');
		$('#company').css('border-width','2px');
		return false;
	}
	
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	loadIframe('iframeList', '');
	loadIframe('iframeList', 'templates/halPaymentList.php?aksi=retrieveOutstanding&printBy='+printBy+'&fromBarcode='+fromBarcode+'&toBarcode='+toBarcode+'&fromDate='+fromDate+'&toDate='+toDate+'&sortBy='+sortBy+'&ascBy='+ascBy+'&company='+company);
}

function checkAllOutstand(checked)
{
	var jmlCB = $('#jmlCB').val();
	var allIdMailInv = "";
	if(checked == true)
	{
		for(var i=1;i<=jmlCB;i++)
		{
			$('#cek'+i).attr("checked","checked");
			var idMailInvDipilih = $('#cek'+i).val();
			allIdMailInv += idMailInvDipilih+",";
		}
	}
	if(checked == false)
	{
		for(var i=1;i<=jmlCB;i++)
		{
			$('#cek'+i).attr("checked","");
			var idMailInvDipilih = $('#cek'+i).val();
			allIdMailInv += "";
		}
		
		/*for(var i = 1; i <= jmlCB; i++) //$jmlRow didapat dari skrip php diatas
		{
			if(document.getElementById('cek'+i).checked == true)
			{
				var idMailInvDipilih = document.getElementById('cek'+i).value;
				allIdMailInv += idMailInvDipilih+",";
			}
		}
		document.getElementById('allIdMailInv').value = allIdMailInv;*/
	}
	
	$('#allIdMailInv').val(allIdMailInv);
}

function closePopupAssign()
{
	parent.tb_remove(false);
	$('#formPaymentPrepare', parent.document).submit();
}

function closePopupBatch(barisTransno, idMailInv, transNo)
{
	parent.tb_remove(false);
	
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	var menuPageTransno = $('#menuPageTransno').val();
	var paramCari = $('#elementCariBatch').val();
	//alert('barisTransno='+barisTransno+' / transNo='+transNo+' / paramCari='+paramCari+' / pageTransno='+menuPageTransno);
	loadIframe('iframeList', 'templates/halBatchList.php?aksi=ketikElementCariBatch&barisTransno='+barisTransno+'&idMailInv='+idMailInv+'&transNo='+transNo+'&paramCari='+paramCari+'&pageTransno='+menuPageTransno); //REFRESH FRAME
	/*
	setTimeout(function()
	{
		var tes = window.frames["iframeList"].document.getElementById('tr'+barisTransno).click(); // CLICK ROW YANG SEBELUMNYA AKAN DI PAID
		setTimeout(function()
		{
			$('#btnRetBatch', parent.document).click(); // KLIK BTN RETRIEVE BATCH
		}, 500);		
	}, 700);
	*/
}

function klikBtnCancelled()
{
	var idMailInv = $('#idMailInv').val();
	var barisTransno = $('#barisTransno').val();
	
	$('#hrefThickbox').attr('href','templates/halPopup.php?aksi=cancelPayment&idMailInv='+idMailInv+'&barisTransno='+barisTransno+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=200&width=400&modal=true');
	$('#hrefThickbox').click();
}

function saveCancelPayment(barisTransno, idMailInv, reasonCancelPay)
{
	//alert(reasonCancelPay);
	//return false; ~!@#$%^&*()_+=-|}{:"?><,./;'[\]
	if( $.trim( reasonCancelPay ) != '' )
	{
		var answer  = confirm('Are you sure want to Cancel this Payment');
		if(answer)
		{	
			parent.tb_remove(false);
			
			var menuPageTransno = $('#menuPageTransno').val();
			var paramCari = $('#elementCariBatch').val();
			loadIframe('iframeList', 'templates/halBatchList.php?aksi=cancelPayment&barisTransno='+barisTransno+'&idMailInv='+idMailInv+'&reasonCancelPay='+encodeURIComponent( reasonCancelPay )+'&paramCari='+paramCari+'&pageTransno='+menuPageTransno); //REFRESH FRAME
			setTimeout(function()
			{
				var tes = window.frames["iframeList"].document.getElementById('tr'+barisTransno).click(); // CLICK ROW YANG SEBELUMNYA AKAN DI PAID
				setTimeout(function()
				{
					$('#btnRetBatch', parent.document).click(); // KLIK BTN RETRIEVE BATCH
				}, 450);		
			}, 300);	
		}
		else
		{	return false;	}
	}
}

function pesanError(pesan, itemFokus)
{
	//document.getElementById(itemFokus).focus(); 
	$('#'+itemFokus).focus();
	$('#idErrorMsg').css('visibility','visible'); 
	$("#idErrorMsg").html("<img src=\"../picture/exclamation-red.png\"/>&nbsp;<span>"+pesan+"</span>&nbsp;");
}

function blinker() {
    $('#idErrorMsg').fadeOut(250);
	$('#idErrorMsg img').fadeOut(250);
		
    $('#idErrorMsg').fadeIn(500);
	$('#idErrorMsg img').fadeIn(500);
	
	/*$('#statusData').fadeOut(250);		
    $('#statusData').fadeIn(500);*/
}

setInterval(blinker, 1500);