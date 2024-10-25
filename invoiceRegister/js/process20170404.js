// JavaScript Document
function halamanOnLoad(tglPilih)
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	//rubahMenuThnBln(tglPilih);
	ajaxGet($('#menuBatchnoThnBln').val(), tglPilih, 'pilihMenuThnBln', 'idTdMenuTgl');
	beriBatchno();
	klikBtnDisplay();
}

function ajaxGet(id, id2, paramAksi, halaman)
{	
	if(paramAksi == "pilihMenuThnBln")
	{
		var paramThnBln = id;
		var paramTgl = id2;	
		
		$.post( "halPostMailInv.php", { aksi:paramAksi, thnBln:paramThnBln, tgl:paramTgl }, function( data )
		{
			$('#'+halaman).html( data );
		});
	}
}

function rubahMenuBatchnoThnBln()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	ajaxGet($('#menuBatchnoThnBln').val(), '', 'pilihMenuThnBln', 'idTdMenuTgl');
	beriBatchno();
	klikBtnDisplay();
	emptyFrameList('iframeList');
}

function rubahMenuBatchnoTgl()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	beriBatchno();
	klikBtnDisplay();
	emptyFrameList('iframeList');
}

function beriBatchno()
{
	setTimeout(function()
	{
		$('#batchno').val( $('#menuBatchnoThnBln').val()+$('#menuBatchnoTgl').val() );
	},300);	
	
	/*setTimeout(function()
	{
		klikBtnDisplay();
	},500);*/
}

function emptyFrameList(iframeId)
{
	//document.getElementById(iframeId).src = "";
	loadIframe(iframeId, "");
}

function teksMap(value)
{
	parent.document.getElementById('idTeksMap').innerHTML = "<a>"+value+"</a>";
}

/*function rubahMenuThnBlnTgl(tglPilih)
{
	var thnBlnPilih = $('#menuBatchnoThnBln').val();
	var tglPilih = tglPilih;
	
	ajaxGet(thnBlnPilih, tglPilih, 'pilihMenuThnBln', 'idTdMenuTgl');
}*/

function klikBtnDisplay()
{
	setTimeout(function()
	{
		var sortBy = $('#sortBy').val();
		var ascBy = $('#ascBy').val();
		var thnBln = $('#menuBatchnoThnBln').val();
		var tgl = $('#menuBatchnoTgl').val();
		var jenisProcess = $('#jenisProcess').val();
		
		loadIframe('iframeList', '');
		loadIframe('iframeList', 'templates/halInvProcessList.php?aksi=display&thnBln='+thnBln+'&tgl='+tgl+'&sortBy='+sortBy+'&ascBy='+ascBy+'&jenisProcess='+jenisProcess);
	},300);
}

function klikBtnRefresh()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	klikBtnDisplay();
}

function cekAck(paramIdCekBox, paramChecked, paramHalaman, i)
{
	var answer  = confirm("Are you sure?");
	if(answer) // JIKA YANG DIPILIH OK / YES
	{
		$('#sudahAck'+i).val( '' );
		$.post( "../halPostMailInv.php", { aksi:"cekSudahAck", idCekBox:paramIdCekBox}, function( data )
		{ 
	// KETIKA KETIK SENDERVENDOR DAN CEK DATABASE PAKE AJAX LALU HASIL NYA ISIKAN KE SENDERVENDERCODE ATAU KETIKA MELAKUKAN ACK CEK APAKAH SUDAH PERNAH DI ACK SEBELUMNYA OLEH USER LAIN DALAM WAKTU YANG BERSAMAAN
			$('#sudahAck'+i).val( data );
		});
		
		if(paramChecked == true) // JIKA AKAN BERI CENTANG ACKNOWLEDGE
		{
			setTimeout(function()
			{ 	
				if($('#sudahAck'+i).val() == "Y")
				{
					alert('Sorry, Already Acknowledge by Another user');
					parent.klikBtnRefresh();
					return false;	
				}
				if($('#sudahAck'+i).val() == "N")
				{
					$.post( "../halPostMailInv.php", { aksi:'cekAck', nilaiChecked:paramChecked, halaman:paramHalaman, idCekBox:paramIdCekBox, urutan:i  }, function( data )
					{
						$('#'+paramHalaman).html( data );
					});
					
					$('#tdIcon'+i).html( '&nbsp;<img src="../picture/eye.png" width="14" title="ALREADY ACKNOWLEDGE">' );
				}
			}, 250);
		}
		if(paramChecked == false) // JIKA AKAN MENGOSONGKAN CENTANG ACKNOWLEDGE
		{
			setTimeout(function()
			{ 
				$.post( "../halPostMailInv.php", { aksi:'cekAck', nilaiChecked:paramChecked, halaman:paramHalaman, idCekBox:paramIdCekBox, urutan:i  }, function( data )
				{
					$('#'+paramHalaman).html( data );
				});
				
				$('#tdIcon'+i).html( '&nbsp;' );
			}, 250);
		}
	}
	else// JIKA YANG DIPILIH NO / CANCEL
	{
		var sortBy = $('#sortBy', parent.document).val();
		var ascBy = $('#ascBy', parent.document).val();
		var thnBln = $('#menuBatchnoThnBln', parent.document).val();
		var tgl = $('#menuBatchnoTgl', parent.document).val();
		var jenisProcess = $('#jenisProcess', parent.document).val();
		//alert(sortBy+' / '+ascBy+' / '+thnBln+' / '+tgl+' / '+jenisProcess);
		
		parent.loadIframe('iframeList', '');
		parent.loadIframe('iframeList', 'templates/halInvProcessList.php?aksi=display&thnBln='+thnBln+'&tgl='+tgl+'&sortBy='+sortBy+'&ascBy='+ascBy+'&jenisProcess='+jenisProcess);
	}
}

function openThickboxWindow(aksi)
{	
	if(aksi == 'klikBtnDetailRet')
	{
		var batchno = $('#batchno').val();
		var idMailInv = $('#idMailInv').val();
		$('#hrefThickbox').attr('href', 'templates/halDetailProcessRet.php?batchno='+batchno+'&idMailInv='+idMailInv+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=575&width=950&modal=true');
	}
	if(aksi == 'klikBtnDetailAck')
	{
		var batchno = $('#batchno').val();
		var idMailInv = $('#idMailInv').val();
		//$('#hrefThickbox').attr('href', 'templates/halDetailProcessAck.php?batchno='+batchno+'&idMailInv='+idMailInv+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=575&width=550&modal=true');
		$('#hrefThickbox').attr('href', 'templates/halDetailProcessAck.php?batchno='+batchno+'&idMailInv='+idMailInv+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=575&width=950&modal=true');
	}
	if(aksi == 'klikBtnPrint')
	{
		//document.getElementById('hrefThickbox').href = "templates/halCariMailInv.php?placeValuesBeforeTB_=savedValues&TB_iframe=true&height=530&width=850&modal=true";
	}
	
	$('#hrefThickbox').click();
}

function setupDetilProcessRet()
{
	var errorMessageDate = "Invalid date: ${value}. Expected format: ${mask}";
	var dateReturned = new DateMask("dd/MM/yyyy", "dateReturned");
	dateReturned.validationMessage = errorMessageDate;
	
	//var debitAccMask = new NumberMask(new NumberParser(0, "", "", true), "debitAcc", 5);
	//var subCodeMask = new NumberMask(new NumberParser(0, "", "", true), "subCode", 5);
	//var kreditAccMask = new NumberMask(new NumberParser(0, "", "", true), "kreditAcc", 5);
	
	//var debitAccMask = new InputMask(JST_MASK_NUMBERS, "debitAcc"); // KOLOM HANYA BOLEH DIISI OLEH ANGKA
	//var subCodeMask = new InputMask(JST_MASK_NUMBERS, "subCode");
	//var kreditAccMask = new InputMask(JST_MASK_NUMBERS, "kreditAcc");
	/*var dueDayRetMask = new InputMask(JST_MASK_NUMBERS, "dueDayRet");
	new SizeLimit("dueDayRet", 3, "");*/
	
	//new NumberMask(new NumberParser(0, "", "", true), "debitAcc", 5); // KOLOM HANYA BOLEH DIISI OLEH ANGKA DAN LIMIT 5 ANGKA
	new NumberMask(new NumberParser(0, "", "", true), "subCode", 5);
	//new NumberMask(new NumberParser(0, "", "", true), "kreditAcc", 5);
	new NumberMask(new NumberParser(0, "", "", true), "voucher", 5);
	new NumberMask(new NumberParser(0, "", "", true), "reference", 5);
	new NumberMask(new NumberParser(0, "", "", true), "dueDayRet", 3);

	//var deducRetMaskk = new NumberParser(2, ".", ",", true);
	//var deducRetMask = new NumberMask(deducRetMaskk, "deducRet", 12);
	//var addiRetMaskk = new NumberParser(2, ".", ",", true);
	//var addiRetMask = new NumberMask(addiRetMaskk, "addiRet", 12);
	
	$("#debitAcc").keypress(function (e){
	  var charCode = (e.which) ? e.which : e.keyCode;
	  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	  }
	});
	
	$("#kreditAcc").keypress(function (e){
	  var charCode = (e.which) ? e.which : e.keyCode;
	  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	  }
	});
}

function hanyaAngkaDeduc()
{
	deducRetMask = new Mask("#,###.##", "number");
	deducRetMask.attach(document.getElementById('deducRet'));
}

function hanyaAngkaAddi()
{
	addiRetMask = new Mask("#,###.##", "number");
	addiRetMask.attach(document.getElementById('addiRet'));
}

function klikBtnCariDebAcc()
{
	if( $.trim( $('#debitAcc').val() ) != "") // JIKA DEBIT ACCOUNT TIDAK SAMA KOSONG MUNCULKAN ACCOUNT CODE
	{
		if($('#hasilDebitAcc').css("display") == 'none')
		{
			$('#hasilDebitAcc').css('display','inline');
			$('#hasilDebitAcc').html( '' );
			$.post( "../halPostMailInv.php", { aksi:"cariDebAcc", debitAcc:$('#debitAcc').val() }, function( data )
			{
				$('#hasilDebitAcc').html( data );
				$('#listLoading1').css('display','none');
			});
		}
		else
		{
			$('#hasilDebitAcc').css('display','none');
		}
	}
	else
	{
		$('#debitAcc').focus();
	}
}

function pilihMenuDebitAcc(debitAcc)
{
	$('#debitAcc').val($.trim(debitAcc));
	$('#hasilDebitAcc').css('display','none');
	
	$("#debitAcc").keyup();
}

function klikBtnCariKredAcc()
{
	if( $.trim( $('#kreditAcc').val() ) != "") // JIKA CREDIT ACCOUNT TIDAK SAMA KOSONG MUNCULKAN ACCOUNT CODE
	{
		if($('#hasilKreditAcc').css("display") == 'none')
		{
			//pleaseWait();
			//document.onmousedown=disableLeftClick;
			
			$('#hasilKreditAcc').css('display','inline');
			$('#hasilKreditAcc').html( '' );
			$.post( "../halPostMailInv.php", { aksi:"cariKredAcc", kreditAcc:$('#kreditAcc').val() }, function( data )
			{
				$('#hasilKreditAcc').html( data );
				$('#listLoading').css('display','none');
			});
		}
		else
		{
			$('#hasilKreditAcc').css('display','none');
		}
	}
	else
	{
		$('#kreditAcc').focus();
	}
}

function pilihMenuKreditAcc(kreditAcc)
{
	$('#kreditAcc').val($.trim(kreditAcc));
	$('#hasilKreditAcc').css('display','none');
	
	$("#kreditAcc").keyup();
}

function pilihDueDayRet()
{
	var invoiceDate = $('#invoiceDate');
	var dueDayRet = $('#dueDayRet');
	//var dueDate = $('#dueDate');
	
	$.post( "../halPostMailInv.php", { aksi:"isiDueDateRet", teksBlink:"no", invoiceDate:invoiceDate.val(), dueDay:dueDayRet.val() }, function( data )
	{
		$('#dueDate').val( data )
		//$('#spanDueDate').html( $('#dueDate').val() );
	});
	
	$.post( "../halPostMailInv.php", { aksi:"isiDueDateRet", teksBlink:"yes", invoiceDate:invoiceDate.val(), dueDay:dueDayRet.val() }, function( data )
	{
		//$('#dueDate').val( data )
		$('#spanDueDate').html( data );
	});
}

function tutupDetailProcess(sure)
{
	if(sure == 'Y')
	{
		var answer  = confirm('Are you sure want to Close?');
		if(answer)
		{	
			parent.tb_remove(false);
			parent.pleaseWait();
			parent.document.onmousedown=parent.disableLeftClick;
			parent.klikBtnDisplay();
		}
		else
		{	return false;	}
	}
	else if(sure == 'N')
	{
		parent.tb_remove(false);
		parent.pleaseWait();
		parent.document.onmousedown=parent.disableLeftClick;
		parent.klikBtnDisplay();
	}
}

/*function klikBtnShowDeduction()
{
	if($('#divDeduc').css("display") == 'none')
	{
		//pleaseWait();
		//document.onmousedown=disableLeftClick;
		$('#divDeduc').css('display','block');
		//$('#divDeduc').html( '' );
		//$.post( "../halPostMailInv.php", { aksi:"showDeduction" }, function( data )
		//{
		//	$('#divDeduc').html( data );
		//});
	}
	else
	{
		$('#divDeduc').css('display','none');
	}
}*/

function pilihBtnSaveRet()
{
	var cekIgnoredJe = $('#ignoredJe').attr('checked');
	var cekInvReturned = $('#invReturned').attr('checked');
	var source = $('#source');
	var debitAcc = $('#debitAcc');
	var subCode = $('#subCode');
	var description = $('#description');
	//var senderVendor = $('#senderVendor');
	var kreditAcc = $('#kreditAcc');
	var voucher = $('#voucher');
	var reference = $('#reference');
	var mailInvNo = $('#mailInvNo');
	var deducRet = $('#deducRet');
	var addiRet = $('#addiRet');
	var reasonDeduc = $('#reasonDeduc');
	
	var invDate = $('#invoiceDate');
	var jurnalDate = $('#tglJurnal');
	
	var kreditAccAdaTidak = $('#kreditAccAdaTidak');
	var debitAccAdaTidak = $('#debitAccAdaTidak');

	$('#idErrorMsg').css('visibility','hidden'); 
	if(cekInvReturned == false)
	{
		pesanError('You have not checked Invoice Returned', 'invReturned');
		return false;
	}
	if( $.trim( source.val() ) == '' )
	{
		pesanError("Source is still empty", "source");
		return false;
	}
	if( $.trim( debitAcc.val() ) == '' )
	{
		pesanError("Debit Account is still empty", "debitAcc");
		return false;
	}
	if(debitAcc.val().length < 5)
	{
		pesanError('Debit Account text length must be 5', 'debitAcc');
		return false;
	}
	if(debitAccAdaTidak.val() == 'kosong')
	{
		pesanError("Debit Account not in the database", "debitAcc");
		return false;
	}
	
	if( $.trim( subCode.val() ) != '' )
	{
		if(subCode.val().length < 5)
		{
			pesanError('Sub Code text length must be 5', 'subCode');
			return false;
		}
	}
	
	if( $.trim( kreditAcc.val() ) == '' )
	{
		pesanError("Kredit Account is still empty", "kreditAcc");
		return false;
	}
	if(kreditAcc.val().length < 5)
	{
		pesanError('Kredit Account text length must be 5', 'kreditAcc');
		return false;
	}
	if(kreditAccAdaTidak.val() == 'kosong')
	{
		pesanError("Kredit Account not in the database", "kreditAcc");
		return false;
	}
	if(  $.trim( description.val() ) == '' )
	{
		pesanError("Description is still empty", "description");
		return false;
	}
	/*if(  $.trim( senderVendor.val() ) == '' )
	{
		pesanError("Sender/Vendor is still empty", "senderVendor");
		return false;
	}*/
	if( $.trim( voucher.val() ) == '' )
	{
		pesanError("Voucher is still empty", "voucher");
		return false;
	}
	if( $.trim( reference.val() ) == '' )
	{
		pesanError("Reference is still empty", "reference");
		return false;
	}
	if(  $.trim( mailInvNo.val() ) == '' )
	{
		pesanError("Mail/Invoice No is still empty", "mailInvNo");
		return false;
	}
	if(  $.trim( deducRet.val() ) != '' &&  $.trim( reasonDeduc.val() ) == '')
	{
		pesanError("Reason is still empty", "reasonDeduc");
		return false;
	}
	if(  $.trim( addiRet.val() ) != '' &&  $.trim( reasonDeduc.val() ) == '')
	{
		pesanError("Reason is still empty", "reasonDeduc");
		return false;
	}
		
	if(cekIgnoredJe == true)
	{
		var answer  = confirm("Are you sure want to Ignore JE");
		if(!answer)
		{
			return false;	
		}
	}	
	
	if(kreditAccAdaTidak.val() == 'tidaksama')
	{
		var answer  = confirm("Sender/Vendor and Credit Account Name are not same, if yes Sender/Vendor will be replaced?");
		if(!answer)
		{
			return false;	
		}
	}
	
	if(invDate.val() != jurnalDate.val())
	{
		var answer  = confirm("Invoice Date and Journal Date are not same, Are you sure to continue ?");
		if(!answer)
		{
			return false;	
		}
	}
	
	setTimeout(function()
	{ 		
		var answer  = confirm('Are you sure want to Transfer?');
		if(answer)
		{	
			pleaseWait();
			document.onmousedown=disableLeftClick;
			
			$.post( "../halPostMailInv.php", { aksi:"cekRetYesNo", idMailInv:$('#idMailInv').val() }, function( data )
			{	$('#returnYesNo').val( data );	}); // CEK APAKAH INVOICE SUDAH PERNAH DI DITRANSFER BELUM SAMA USER LAIN
			
			setTimeout(function()
			{
				if( $('#returnYesNo').val() == 'yes' ) //JIKA SUDAH PERNAH DI DITRANSFER OLEH USER LAIN 
				{
					doneWait();
					panggilEnableLeftClick();
					
					var answer  = confirm("Sorry, Already Transfer by Another user.\n\rAre you wanto to Refresh page?");
					if(answer)
					{
						parent.openThickboxWindow('klikBtnDetailAck');return false; // REFRESH PAGE
					}
					else
					{
						return false;
					}
				}
				else//JIKA BELUM PERNAH DI DITRANSFER OLEH USER LAIN 
				{
					formUbahProcessRet.submit();
				}
			}, 250);
		}
		else
		{	return false;	}
	}, 250);
}

$(document).ready(function()
{	
	//setInterval(blinkDueDay, 1500);
	
	$('#invReturned').click(function()
	{
		$('#invReturnedNo').html( 'NO' );
		if($('#invReturned').attr('checked') == true)
		{
			$('#invReturnedNo').html( 'YES' );
		}
	});
	
	$('#ignoredJe').click(function()
	{
		$('#ignoredJeNo').html( 'NO' );
		if($('#ignoredJe').attr('checked') == true)
		{
			$('#ignoredJeNo').html( 'YES' );
		}
	});
	
	$('#approvePayment').click(function()
	{
		$('#approvePaymentNo').html( 'NO' );
		if($('#approvePayment').attr('checked') == true)
		{
			$('#approvePaymentNo').html( 'YES' );
		}
	});
	
	
	$("#kreditAcc").keyup(function()
	{ // CEK KREDIT ACCOUNT AADA TIDAK DI ACCOUNT CODE DI DATABASE SQL SERVER
		$.post( "../halPostMailInv.php", { aksi:"cekKreditAcc", kreditAcc:$('#kreditAcc').val(), idMailInv:$('#idMailInv').val() }, function( data )
		{
			$('#idCekKreditAcc').html( data );
		});
		
		$.post("../halPostMailInv.php", { aksi:"tulisKreditAccName", kreditAcc:$('#kreditAcc').val() }, function ( data )
		{
			$('#spanKreditAccName').html( data );
		});
	});
	
	$("#debitAcc").keyup( function()
	{
		$.post( "../halPostMailInv.php", { aksi:"cekDebitAcc", debitAcc:$('#debitAcc').val() }, function( data )
		{
			$('#idCekDebitAcc').html( data );
		});
		
		$.post( "../halPostMailInv.php", { aksi:"tulisDebitAccName", debitAcc:$('#debitAcc').val() }, function( data )
		{
			$('#spanDebitAccName').html( data );
		});
	});
	
	$("#mailInvNo").keydown(function( eInv )
	{
		if(eInv.keyCode == 222)// UNTUK CHAR TANDA KUTIP (")(')
		{
			return false;
		}
	});
});

function klikBtnIncoming()
{
	var thnBlnPilih = $('#menuBatchnoThnBln').val();
	var tglPilih =  $('#menuBatchnoTgl').val();
	
	$('#formIncoming').attr('action', 'index.php?thnBlnPilih='+thnBlnPilih+'&tglPilih='+tglPilih);
	$('#formIncoming').submit();
	return false;
}

function klikBtnAck()
{
	var thnBlnPilih = $('#menuBatchnoThnBln').val();
	var tglPilih =  $('#menuBatchnoTgl').val();
	
	$('#formProcess').attr('action', 'index.php?thnBlnPilih='+thnBlnPilih+'&tglPilih='+tglPilih);
	$('#formProcess').submit();
	return false;
}

function klikBtnReturn()
{
	var thnBlnPilih = $('#menuBatchnoThnBln').val();
	var tglPilih =  $('#menuBatchnoTgl').val();
	
	$('#formProcessRet').attr('action', 'index.php?thnBlnPilih='+thnBlnPilih+'&tglPilih='+tglPilih);
	$('#formProcessRet').submit();
	return false;
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
}

setInterval(blinker, 1500);

$(document).ready(function()
{
	// AUTO COMPLETE UNTUK MENU SENDER VENDOR DI HALAMAN HALTAMBAHMAILINV.PHP
	$("#senderVendor").focus(function (event)
	{	
		$('#autoCompSender').css('display','block'); // MUNCULKAN MENU AUTOCOMPLETE SENDERVENDOR
		$('#autoCompSender').html( '' );
		$.post( "../halPostMailInv.php", { aksi:"ketikAutoComplSender", param:$('#senderVendor').val() }, function( data )
		{
			$('#autoCompSender').html( data );
		});
		
		$('#hasilKreditAcc').css('display','none');
	});
	
	$("#senderVendor").keyup(function (event)
	{	
		//$('#senderVendorCode2').html( '' );
		
		var status = "true";
		if(event.keyCode == 40 || event.keyCode == 38)//arrow down and up
		{ // JIKA KETIK PANAH ATAS BAWAH MENU AUTOCOMPLETE TIDAK MUNCUL LAGI KARENA SUDAH MUNCUL KETIKA SENDERVENDOR FOCUS
			status = "false";
		}
		
		if(status == "true") // JIKA KEYBOARD DIKETIK SELAIN PANAH ATAS DAN BAWAH MAKA AMBIL DATA DI DATABASE DENGAN POST
		{
			if(event.keyCode != 13)//enter // JIKA BUKAN ENTER YANG DIKETIK 
			{
				$('#autoCompSender').css('display','block');
				$('#autoCompSender').html( '' );
				$.post( "../halPostMailInv.php", { aksi:"ketikAutoComplSender", param:$('#senderVendor').val(), urutSendSelect:$('#urutSendSelect').val() }, function( data )
				{
					$('#autoCompSender').html( data );
				});
				$.post( "../halPostMailInv.php", { aksi:"cekSenderVendorCode", senderVendorName:$('#senderVendor').val() }, function( data )
				{ // KETIKA KETIK SENDERVENDOR DAN CEK DATABASE PAKE AJAX LALU HASIL NYA ISIKAN KE SENDERVENDERCODE
					$('#senderVendorCode').val( data );
					if(data == "")
					{
						var senderVendorHtml = '<input type="text" name="kreditAcc" id="kreditAcc" class="elementInput" style="width:60px;"/>';
						$('#senderVendorCode2').html( senderVendorHtml );
						$('#btnCariKredAcc').attr('className','btnStandar');
						$('#btnCariKredAcc').removeAttr('disabled');
					}
					if(data != "")
					{
						$('#btnCariKredAcc').attr('disabled','disabled');
						$('#btnCariKredAcc').attr('className','btnStandarDis');
						$('#senderVendorCode2').html( data+' ('+$('#senderVendor').val()+')' );
					}
				});
			}
		}
		
		var idSelect = $('#urutSendSelect').val(); // urutan terakhir yang dipilih
		var urutId = idSelect;
		var urutIdNext = 0;
		var urutIdPrev = 0;
		var totalSendVend = $('#totalSendVend').val();
		var scrollTopNow = $('#autoCompSender').scrollTop();				
		
		if(event.keyCode == 40)//arrow down
		{	
			urutIdNext = (parseInt(urutId) + 1); 
			if(urutIdNext > totalSendVend) // JIKA URUT ID BERIKUTNYA SAMA DENGAN TOTAL ROW MAKA BERHENTI DI ROW TERAKHIR
				urutIdNext = totalSendVend;
				
			$('#trId_'+urutId).css('background-color','FFFFFF');
			$('#aId_'+urutId).css('color','555');
			$('#trId_'+urutIdNext).css('background-color','93A070');
			$('#aId_'+urutIdNext).css('color','FFFFFF');
			$('#urutSendSelect').val(urutIdNext); // ISI HIDDEN INPUT DENGAN URUTAN TERKINI
			
			if(urutIdNext > 1) // JIKA URUT ID LEBIH DARI 1 MAKA SCROOL MENU AUTO COMPLETE KEBAWAH
				$('#autoCompSender').scrollTop(scrollTopNow + $('#trId_'+urutId).eq(0).height());
			
		}
		if(event.keyCode == 38)//arrow up
		{
			urutIdPrev = (parseInt(urutId) - 1);
			if(urutIdPrev < 0) // JIKA URUT ID SEBELUMNYA KURANG DARI 0 MAKA BERHENTI DI ROW PERTAMA
				urutIdPrev = 0;
				
			$('#trId_'+urutId).css('background-color','FFFFFF');
			$('#aId_'+urutId).css('color','555');
			$('#trId_'+urutIdPrev).css('background-color','93A070');
			$('#aId_'+urutIdPrev).css('color','FFFFFF');
			$('#urutSendSelect').val(urutIdPrev);

			if(urutIdNext < (totalSendVend))// JIKA URUT ID KURANG DARI TOTAL DATA MAKA SCROOL MENU AUTO COMPLETE KEATAS
				$('#autoCompSender').scrollTop(scrollTopNow - $('#trId_'+urutId).eq(0).height());
		}
		if(event.keyCode == 13)//enter
		{
			var acctName = $('#actName_'+urutId).val();
			if(acctName == undefined) // JIKA TEKAN ENTER BUKAN DARI MENU AUTOCOMPLETE MAKA AMBIL NILAI ACCTNAME DARI INPUT SENDER VENDOR
				acctName = $('#senderVendor').val();

			clickMenuSend($('#acctCode_'+urutId).val(), acctName);
			
			$('#autoCompSender').css('display','none');
		}	
		
		//var trIdSelect = $(this).closest('tr').find('.classSelect').id();	
		
	});
	
	$(this).mouseup(function (e)
	{
		var idActElem = $(document.activeElement).attr("id");
		var container = $('#autoCompSender');
		var container2 = $('#senderVendor');
		//alert(idActElem);
		if (!container.is(e.target) // if the target of the click isn't the container...
			&& container.has(e.target).length === 0 // ... nor a descendant of the container
			&& idActElem != "senderVendor" // APAKAH ELEMENT YANG AKTIF TESVEND APA BUKAN
			) 
		{
			$('#autoCompSender').css('display','none');
			$('#urutSendSelect').val('0');
		}
	});
});

function clickMenuSend(acctCode, acctName)
{
	if(acctCode != undefined)
	{
		$('#senderVendorCode').val(acctCode);
		$('#senderVendorCode2').html(acctCode+' ('+acctName+')');
		$('#senderVendor').val(acctName);
		
		$('#autoCompSender').css('display','none');
		$('#urutSendSelect').val('0'); // JIKA KETIK PADA SENDER VENDOR MAKA URUTAN AUTO COMPLETE KEMBALI KE 0
		
		$('#btnCariKredAcc').attr('disabled','disabled');
		$('#btnCariKredAcc').attr('className','btnStandarDis');
	}
}

function listLoad()
{
	if($('#kreditAcc').val() != "" && $('#hasilKreditAcc').css('display') == 'none')
	{
		$('#listLoading').css('display','inline');
	}
}

function listLoad1()
{
	if($('#debitAcc').val() != "" && $('#hasilDebitAcc').css('display') == 'none')
	{
		$('#listLoading1').css('display','inline');
	}
}

function fungsiReset()
{
	$('#ignoredJeNo').html('NO');
	$('#approvePaymentNo').html('YES');
	$('#spanDebitAccName').html('');
	$('#spanKreditAccName').html('');
	setTimeout(function()
	{
		pilihDueDayRet();
	},10);
}