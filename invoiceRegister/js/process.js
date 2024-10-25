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

		});
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
		// var batchno = $('#batchno').val();
		var batchno = $("#menuBatchnoThnBln").val()+$("#menuBatchnoTgl").val();
		var idMailInv = $('#idMailInv').val();
		$('#hrefThickbox').attr('href', 'templates/halDetailProcessRet.php?batchno='+batchno+'&idMailInv='+idMailInv+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=575&width=950&modal=true');
	}
	if(aksi == 'klikBtnDetailRetYes')
	{
		var batchno = $('#batchno').val();
		var idMailInv = $('#idMailInv').val();
		$('#hrefThickbox').attr('href', 'templates/halDetailProcessRetYes.php?batchno='+batchno+'&idMailInv='+idMailInv+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=575&width=950&modal=true');
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
	//new NumberMask(new NumberParser(0, "", "", true), "subCode", 5);
	//new NumberMask(new NumberParser(0, "", "", true), "kreditAcc", 5);
	new NumberMask(new NumberParser(0, "", "", true), "voucher", 5);
	new NumberMask(new NumberParser(0, "", "", true), "reference", 5);
	new NumberMask(new NumberParser(0, "", "", true), "dueDayRet", 3);

	//var deducRetMaskk = new NumberParser(2, ".", ",", true);
	//var deducRetMask = new NumberMask(deducRetMaskk, "deducRet", 12);
	//var addiRetMaskk = new NumberParser(2, ".", ",", true);
	//var addiRetMask = new NumberMask(addiRetMaskk, "addiRet", 12);

	$("#subCode").keypress(function (e){
	  var charCode = (e.which) ? e.which : e.keyCode;
	  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	  }
	});

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
	
	$.post( "../halPostMailInv.php", { aksi:"closePage", idMailInv:$("#idMailInv").val() }, function( data )
	{});
}

function closeAndErase()
{
	var answer  = confirm("Are you sure want to Close And Erase? \r\n( Will erase split, deduction, additional data in temporary )");
	if(answer)
	{	
		parent.tb_remove(false);
		parent.pleaseWait();
		parent.document.onmousedown=parent.disableLeftClick;
		parent.klikBtnDisplay();
		emptyTabelSplitTemp();
	}
	else
	{	return false;	}
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
	var cekIgnoredJe = $("#ignoredJe").attr("checked");
	var cekInvReturned = $("#invReturned").attr("checked");
	var cekSplitDebAcc = $("#splitDebAcc").attr("checked");
	var cekSplitCreAcc = $("#splitCreAcc").attr("checked");
	var source = $("#source");
	var debitAcc = $("#debitAcc");
	var subCode = $("#subCode");
	var description = $("#description");
	var kreditAcc = $("#kreditAcc");
	var voucher = $("#voucher");
	var reference = $("#reference");
	var mailInvNo = $("#mailInvNo");
	var deducRet = $("#deducRet");
	var addiRet = $("#addiRet");
	var reasonDeduc = $("#reasonDeduc");
	
	var invDate = $("#invoiceDate");
	var jurnalDate = $("#tglJurnal");
	
	var kreditAccAdaTidak = $("#kreditAccAdaTidak");
	var debitAccAdaTidak = $("#debitAccAdaTidak");

	//cekDataLockNya($("#idMailInv").val());

	$("#idErrorMsg").css("visibility","hidden"); 
	if(cekInvReturned == false)
	{
		pesanError("You have not checked Invoice Returned", "invReturned");
		return false;
	}
	if( $.trim( source.val() ) == "" )
	{
		pesanError("Source is still empty", "source");
		return false;
	}
	if( $.trim( debitAcc.val() ) == "" )
	{
		pesanError("Debit Account is still empty", "debitAcc");
		return false;
	}
	if(debitAcc.val().length < 5)
	{
		pesanError("Debit Account text length must be 5", "debitAcc");
		return false;
	}
	if(debitAccAdaTidak.val() == "kosong")
	{
		pesanError("Debit Account not in the database", "debitAcc");
		return false;
	}
	
	if( $.trim( subCode.val() ) != "" )
	{
		if(subCode.val().length < 5)
		{
			pesanError("Sub Code text length must be 5", "subCode");
			return false;
		}
	}
	
	if( $.trim( kreditAcc.val() ) == "" )
	{
		pesanError("Kredit Account is still empty", "kreditAcc");
		return false;
	}
	if(kreditAcc.val().length < 5)
	{
		pesanError("Kredit Account text length must be 5", "kreditAcc");
		return false;
	}
	if(kreditAccAdaTidak.val() == "kosong")
	{
		pesanError("Kredit Account not in the database", "kreditAcc");
		return false;
	}
	if(  $.trim( description.val() ) == "" )
	{
		pesanError("Description is still empty", "description");
		return false;
	}
	if( $.trim( voucher.val() ) == "" )
	{
		pesanError("Voucher is still empty", "voucher");
		return false;
	}
	if( $.trim( reference.val() ) == "" )
	{
		pesanError("Reference is still empty", "reference");
		return false;
	}
	if(  $.trim( mailInvNo.val() ) == "" )
	{
		pesanError("Mail/Invoice No is still empty", "mailInvNo");
		return false;
	}
	if(  $.trim( deducRet.val() ) != "" &&  $.trim( reasonDeduc.val() ) == "")
	{
		pesanError("Reason is still empty", "reasonDeduc");
		return false;
	}
	if(  $.trim( addiRet.val() ) != "" &&  $.trim( reasonDeduc.val() ) == "")
	{
		pesanError("Reason is still empty", "reasonDeduc");
		return false;
	}
	
	var statusDeducAddi = "NO"; //STATUS UNTK LANJUT APA TIDAK KETIKA DEDUCTION / ADDITIONAL DIISI ATAU TIDAK
	var totalDeduc = 0;
	var totalAddi = 0;
	for(var i=1; i<=5;i++)
	{
		if($.trim( $("#deducAcc"+i).val() ) == "" ||  $.trim( $("#deducAmount"+i).val() ) == "" || $.trim( $("#deducReason"+i).val() ) == "")
		{ // JIKA DESCRIPTION BELUM DIISI
			if($.trim( $("#deducAcc"+i).val() ) == "" &&  $.trim( $("#deducAmount"+i).val() ) == "" && $.trim( $("#deducReason"+i).val() ) == "")
			{
				//return true
				statusDeducAddi = "YES";
			}
			else
			{		
				if($.trim( $("#deducAcc"+i).val() ) == "")
				{
					pesanError("Account "+i+" is still empty", "deducAcc"+i);
					return false;
				}
				if($("#deducAcc"+i).val().length < 5)
				{ // JIKA JUMLAH TEKS TIDAK SAMA DENGAN 5
					pesanError("Account "+i+" text length must be 5", "deducAcc"+i);
					return false;	
				}
			
				if($.trim( $("#deducAmount"+i).val() ) == "")
				{
					pesanError("Amount "+i+" is still empty", "deducAmount"+i);
					return false;
				}	
				var amountPtg = $("#deducAmount"+i).val().split(".");
				if(parseInt(amountPtg[0].replace(/,/g,"")) > 999999999999)
				{
					pesanError("Amount "+(i+1)+" cant more than 999,999,999,999.99", "deducAmount"+i);
					return false;
				}	
				if($.trim( $("#deducReason"+i).val() ) == "")
				{
					pesanError("Description "+i+" is still empty", "deducReason"+i);
					return false;
				}
			}
		}
		
		
		/*
		SEMENTARA SEMUA YANG TERKAIT ADDITIONAL DIHILANGKAN
		if($.trim( $("#addiAcc"+i).val() ) == "" ||  $.trim( $("#addiAmount"+i).val() ) == "" || $.trim( $("#addiReason"+i).val() ) == "")
		{ // JIKA DESCRIPTION BELUM DIISI
			if($.trim( $("#addiAcc"+i).val() ) == "" &&  $.trim( $("#addiAmount"+i).val() ) == "" && $.trim( $("#addiReason"+i).val() ) == "")
			{
				//return true
				statusDeducAddi = "YES";
			}
			else
			{		
				if($.trim( $("#addiAcc"+i).val() ) == "")
				{
					pesanError("Account "+i+" is still empty", "addiAcc"+i);
					return false;
				}
				if($("#addiAcc"+i).val().length < 5)
				{ // JIKA JUMLAH TEKS TIDAK SAMA DENGAN 5
					pesanError("Account "+i+" text length must be 5", "addiAcc"+i);
					return false;	
				}
			
				if($.trim( $("#addiAmount"+i).val() ) == "")
				{
					pesanError("Amount "+i+" is still empty", "addiAmount"+i);
					return false;
				}	
				var amountPtg = $("#addiAmount"+i).val().split(".");
				if(parseInt(amountPtg[0].replace(/,/g,"")) > 999999999999)
				{
					pesanError("Amount "+(i+1)+" cant more than 999,999,999,999.99", "addiAmount"+i);
					return false;
				}	
				if($.trim( $("#addiReason"+i).val() ) == "")
				{
					pesanError("Description "+i+" is still empty", "addiReason"+i);
					return false;
				}
			}
		}*/
	}
	
	if(cekSplitDebAcc == true) // JIKA SPLIT ACCOUNT DICENTANG DAN BALANCE TIDAK SAMA DENGAN 0
	{
		//alert($("#balanceAmt").val());
		if($.trim( $("#balanceAmt").val() ) != 0 || $.trim( $("#balanceAmt").val() ) != 0.00)
		{
			alert("Jurnal is not Balance");
			return false;
		}
	}
	
	if(statusDeducAddi == "NO")
	{
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
	
	if(kreditAccAdaTidak.val() == "tidaksama")
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

	$.post( "../halPostMailInv.php", { aksi:"cekDateLockNya", id:$("#idMailInv").val(), jurnalDate:$("#tglJurnal").val() }, function( dataNya )
	{
		if(dataNya == "lock")
		{
			pesanError("Check Your Jurnal Date , Because Date is Lock..!!", "tglJurnal");
			return false;
		}else{			
			setTimeout(function()
			{
				var answer  = confirm("Are you sure want to Transfer?");
				if(answer)
				{	
					pleaseWait();
					document.onmousedown=disableLeftClick;
					
					$.post( "../halPostMailInv.php", { aksi:"cekRetYesNo", idMailInv:$("#idMailInv").val() }, function( data )
					{
						$("#returnYesNo").val( data );
					}); // CEK APAKAH INVOICE SUDAH PERNAH DI DITRANSFER BELUM SAMA USER LAIN
					
					setTimeout(function()
					{
						if( $("#returnYesNo").val() == "yes" ) //JIKA SUDAH PERNAH DI DITRANSFER OLEH USER LAIN 
						{
							doneWait();
							panggilEnableLeftClick();
							
							var answer  = confirm("Sorry, Already Transfer by Another user.\n\rAre you wanto to Refresh page?");
							if(answer)
							{
								parent.openThickboxWindow("klikBtnDetailAck");return false; // REFRESH PAGE
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
	});

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
	
	$('#splitDebAcc').click(function()
	{
		$('#splitDebAccNo').html( 'NO' );
		disabledBtn("btnViewSplitDeb");
		
		$("#divKontenSplitDeb").css("display","none");	
		$("#divCloseSplitDeb").css("display","none");
		enabledBtn("btnTransfer");
		enabledBtn("btnReset");
		
		if($('#splitDebAcc').attr('checked') == true) // JIKA AKAN BERI CENTANG ELEMENT SPLIT DEBIT ACCOUNT, DEBIT ACCOUNT TIDAK BOLEH KOSONG DAN KARAKTER HARUS BERJUMLAH 5
		{
			$("#idErrorMsg").css("visibility","hidden"); 
			if( $.trim( $("#debitAcc").val() ) == "" )
			{
				pesanError("Debit Account is still empty", "debitAcc");
				return false;
			}
			if($("#debitAcc").val().length < 5)
			{
				pesanError("Debit Account text length must be 5", "debitAcc");
				return false;
			}
			
			// JIKA CENTANG BERHASIL KEMUDIAN SIMPAN 1 ROW DALAM DB TABEL TBLSPLITTEMP SECARA DEFAULT NILAINYA URUTAN=1, FIELDAKSI=memodebit, BOOKSTS=DB, ACCOUNT=$("#debitAcc").val(), AMOUNT=$("#incomingAmt").val() DESCRIPTION=$().val("#description")
			$.post( "../halPostMailInv.php", { aksi:"cekSplitAcc", debitAcc:$('#debitAcc').val(), vesselCode:$('#vesselName').val(), amount:$('#incomingAmt').val(), desc:$('#description').val(), idMailInv:$('#idMailInv').val() }, function( data )
			{ /*$("#tess").html( data );*/	});
			
			$("#splitDebAccNo").html( "YES" ); // RUBAH TEKS MENJADI YES
			enabledBtn("btnViewSplitDeb"); // BUTTON VIEW SPLIT ACCOUNT BISA DIKLIK
		}
		
		setTimeout(function()
		{
			totalBalanceSplit(); // HITUNG ULANG BALANCE
		},100);
	});

	$("#splitCreAcc").click(function(){
		$('#splitCreAccNo').html( 'NO' );
		disabledBtn("btnViewSplitCre");
		
		$("#divKontenSplitCre").css("display","none");	
		$("#divCloseSplitCre").css("display","none");
		enabledBtn("btnTransfer");
		enabledBtn("btnReset");
		
		if($('#splitCreAcc').attr('checked') == true) // JIKA AKAN BERI CENTANG ELEMENT SPLIT DEBIT ACCOUNT, DEBIT ACCOUNT TIDAK BOLEH KOSONG DAN KARAKTER HARUS BERJUMLAH 5
		{
			$("#idErrorMsg").css("visibility","hidden"); 
			if( $.trim( $("#kreditAcc").val() ) == "" )
			{
				pesanError("Credit Account is still empty", "kreditAcc");
				return false;
			}
			if($("#kreditAcc").val().length < 5)
			{
				pesanError("Credit Account text length must be 5", "kreditAcc");
				return false;
			}
			
			// JIKA CENTANG BERHASIL KEMUDIAN SIMPAN 1 ROW DALAM DB TABEL TBLSPLITTEMP SECARA DEFAULT NILAINYA URUTAN=1, FIELDAKSI=memodebit, BOOKSTS=DB, ACCOUNT=$("#debitAcc").val(), AMOUNT=$("#incomingAmt").val() DESCRIPTION=$().val("#description")
			$.post( "../halPostMailInv.php", { 
				aksi:"cekSplitCreAcc", creditAcc:$('#kreditAcc').val(), vesselCode:$('#vesselName').val(), amount:$('#incomingAmt').val(), 
				desc:$('#description').val(), idMailInv:$('#idMailInv').val() 
			}, function( data )
			{ /*$("#tess").html( data );*/});
			
			$("#splitCreAccNo").html( "YES" ); // RUBAH TEKS MENJADI YES
			enabledBtn("btnViewSplitCre"); // BUTTON VIEW SPLIT ACCOUNT BISA DIKLIK
			setTimeout(function()
			{
				totalBalanceSplitCredit(); // HITUNG ULANG BALANCE
			},100);
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
	
	$("#vesselName").change( function()
	{
		//KETIKA VESSEL NAME DIGANTI DAN KOLOM CENTANG SPLIT ACCOUNT DALAM KEADAAN CENTANG MAKA UPDATE FIELD VSLCODE DI TBLSPLITTEMP DENGAN AKSI=MEMODEBIT DAN URUTAN=1
		if($('#splitDebAcc').attr('checked') == true)
		{
			$.post( "../halPostMailInv.php", { aksi:"simpanSplitDeb", idMailInv:$('#idMailInv').val(), nilaiElement:$('#vesselName').val(), urutan:"1", idElement:"vslCode" }, function( data ){});
		}
	});
	
	$("#debitAcc").keyup( function()
	{	// KETIKA KOLOM DEBIT ACCOUNT DIISI DAN KOLOM CENTANG SPLIT ACCOUNT DALAM KEADAAN CENTANG MAKA UPDATE FIELD ACCOUNT DI TBLSPLITTEMP, DENGAN FIELD AKSI=MEMODEBIT, URUTAN=1 
		if($('#splitDebAcc').attr('checked') == true)
		{
			$.post( "../halPostMailInv.php", { aksi:"simpanSplitDeb", idMailInv:$('#idMailInv').val(), nilaiElement:$('#debitAcc').val(), urutan:"1", idElement:"account" }, function( data ){});
		}
		// KETIKA KOLOM DEBIT ACCOUNT DIISI MAKA MUNCULKAN DEBIT NAME BERDASARKAN ACCOUNTCODE DEBIT ACCOUNT 
		$.post( "../halPostMailInv.php", { aksi:"cekDebitAcc", debitAcc:$('#debitAcc').val() }, function( data )
		{
			$('#idCekDebitAcc').html( data );
		});
		
		$.post( "../halPostMailInv.php", { aksi:"tulisDebitAccName", debitAcc:$('#debitAcc').val() }, function( data )
		{
			$('#spanDebitAccName').html( data );
		});
	});
	
	$("#mailInvNo").keydown( function( eInv )
	{
		if(eInv.keyCode == 222)// UNTUK CHAR TANDA KUTIP (")(')
		{
			return false;
		}
	});
	
	/*for(var i=1; i<=5;i++)
	{
		$("#deducAmount"+i).keyup( function()
		{
			updateFinalAmt("deduc");
		});
		$("#addiAmount"+i).keyup( function()
		{
			updateFinalAmt("addi");
		});
	}*/
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
	
	/*for(var b=1; b<=5; b++)
	{
		
		$("#deducAmount"+b).keyup( function(e)
		{
			var deducAmount = this.value;
			
			//aa = i;
			//var aaa = ;
			
			updateFinalAmt("deduc");
		});
	}*/
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

function cekTabelDeduc() // CEK APAKAH TERDAPAT ROW DI TBLSPLITTEMP DENGAN FIELD AKSI ADDITONAL / DEDUCTION, JIKA TIDAK ADA MAKA LANGSUNG TAMBAHKAN MASING2 5 ROW
{
	$.post( "../halPostMailInv.php", { aksi:"cekTabelDeduc", idMailInv:$("#idMailInv").val() }, function( data )
	{
		//alert(data);
	});
}

function emptyTabelSplitTemp() // KOSONGKAN ROW DI TBLSPLITTEMP BERDASARKAN USERID DAN IDMAILINV
{
	$.post( "../halPostMailInv.php", { aksi:"emptyTabelSplitTemp", idMailInv:$("#idMailInv").val() }, function( data )
	{
		//alert( data );
	});
}

function klikBtnViewDeb()
{
	//$("#btnViewSplitDeb").css("display","none");
	$("#idErrorMsg").css("visibility","hidden"); 
	if( $.trim( $("#debitAcc").val() ) == "" )
	{
		pesanError("Debit Account is still empty", "debitAcc");
		return false;
	}
	if($("#debitAcc").val().length < 5)
	{
		pesanError("Debit Account text length must be 5", "debitAcc");
		return false;
	}
	
	document.getElementById('iframeSplitDebList').contentWindow.location.reload(true);
	$("#divKontenSplitDeb").css("display","inline");	
	$("#divCloseSplitDeb").css("display","inline");
	
	disabledBtn("btnClose");
	disabledBtn("btnCloseErase");
	disabledBtn("btnTransfer");
	disabledBtn("btnReset");
}

function klikBtnViewCre()
{
	$("#idErrorMsg").css("visibility","hidden"); 
	if( $.trim( $("#kreditAcc").val() ) == "" )
	{
		pesanError("Credit Account is still empty", "kreditAcc");
		return false;
	}
	if($("#kreditAcc").val().length < 5)
	{
		pesanError("Credit Account text length must be 5", "kreditAcc");
		return false;
	}
	
	document.getElementById('iframeSplitCreList').contentWindow.location.reload(true);
	$("#divKontenSplitCre").css("display","inline");	
	$("#divCloseSplitCre").css("display","inline");
	
	disabledBtn("btnClose");
	disabledBtn("btnCloseErase");
	disabledBtn("btnTransfer");
	disabledBtn("btnReset");
}

function klikBtnCloseSplitDeb()
{
	var allUrutan = window.frames['iframeSplitDebList'].$('#allUrutan').val(); // AMBIL NILAI HIDDEN DGN ID 'ALLURUTAN' YANG BERISI KUMPULAN URUTAN DARI TABEL SPLIT YG DELETESTS=0
	var splitAllUrutan = allUrutan.split(","); // PISAHKAN URUTAN YANG DIDAPAT BERDASAR KOMA
	var pjgSplitAllUrutan = splitAllUrutan.length; // SETELAH DIPISAH BERDASAR KOMA DAPAT KAN JUMLAH URUTAN YANG DIDAPAT BIASANYA ADA 2 KOSONG
	for(var i=0; i<=(pjgSplitAllUrutan-2);i++)
	{
		//alert(splitAllUrutan[i]);
		if($.trim( window.frames['iframeSplitDebList'].$('#account'+splitAllUrutan[i]).val() ) == "")
		{ // JIKA DESCRIPTION BELUM DIISI
			alert('Account '+(i+1)+' is still empty');
			window.frames['iframeSplitDebList'].$('#account'+splitAllUrutan[i]).focus();
			return false;
		}
		if(window.frames['iframeSplitDebList'].$('#account'+splitAllUrutan[i]).val().length < 5)
		{ // JIKA JUMLAH TEKS TIDAK SAMA DENGAN 5
			alert('Account '+(i+1)+' text length must be 5');
			window.frames['iframeSplitDebList'].$('#account'+splitAllUrutan[i]).focus(); 
			return false;	
		}
		if($.trim( window.frames['iframeSplitDebList'].$('#amount'+splitAllUrutan[i]).val() ) == "" || window.frames['iframeSplitDebList'].$('#amount'+splitAllUrutan[i]).val() == 0.00 || window.frames['iframeSplitDebList'].$('#amount'+splitAllUrutan[i]).val() == 0)
		{ // JIKA AMOUNT BELUM DIISI
			alert('Amount '+(i+1)+' is still empty');
			window.frames['iframeSplitDebList'].$('#amount'+splitAllUrutan[i]).focus();
			return false;
		}
		var amountPtg = window.frames['iframeSplitDebList'].$('#amount'+splitAllUrutan[i]).val().split(".");
		if(parseInt(amountPtg[0].replace(/,/g,"")) > 999999999999)
		{
			alert('Amount '+(i+1)+' cant more than 999,999,999,999.99');
			window.frames['iframeSplitDebList'].$('#amount'+splitAllUrutan[i]).focus();
			return false;
		}
		if($.trim( window.frames['iframeSplitDebList'].$('#description'+splitAllUrutan[i]).val() ) == "")
		{ // JIKA DESCRIPTION BELUM DIISI
			alert('Description '+(i+1)+' is still empty');
			window.frames['iframeSplitDebList'].$('#description'+splitAllUrutan[i]).focus();
			return false;
		}
	}
	
	$("#divKontenSplitDeb").css("display","none");	
	$("#divCloseSplitDeb").css("display","none");
	
	enabledBtn("btnClose");
	enabledBtn("btnCloseErase");
	enabledBtn("btnTransfer");
	enabledBtn("btnReset");
}
function klikBtnCloseSplitCre()
{
	$("#divKontenSplitCre").css("display","none");	
	$("#divCloseSplitCre").css("display","none");
	
	enabledBtn("btnClose");
	enabledBtn("btnCloseErase");
	enabledBtn("btnTransfer");
	enabledBtn("btnReset");
}

function klikBtnAddDesc()
{
	var idMailInv = $('#idMailInv').val();

	loadIframe("iframeSplitDebList", "");
	loadIframe("iframeSplitDebList", "../templates/halSplitDebList.php?aksi=tambahSplit&idMailInv="+idMailInv);
}

function klikBtnAddCreditDesc()
{
	var idMailInv = $('#idMailInv').val();

	loadIframe("iframeSplitCreList", "");
	loadIframe("iframeSplitCreList", "../templates/halSplitCreList.php?aksi=tambahSplit&idMailInv="+idMailInv);
}

function simpanSplitCred(nilaiElement, fieldAksi, urutan, idElement)
{
	$.post( "../halPostMailInv.php", { aksi:"simpanSplitCred", idMailInv:$("#idMailInv").val(), fieldAksi:fieldAksi, nilaiElement:nilaiElement, urutan:urutan, idElement:idElement }, function( data )
	{});
}

function changeCekPay(idElement, checked)
{
	var ambilUrutan = idElement.replace("cekPay","");

	$.post( "../halPostMailInv.php", { aksi:"simpanCekPay", idMailInv:$("#idMailInv").val(), nilaiChecked:checked, urutan:$.trim(ambilUrutan) }, function( data )
	{});
}

function updateFinalAmtOld(keyUp)
{
	var hasil = "";
	var incomingAmt = $("#incomingAmt").val();
	var totalDeducAmt = 0;
	var deducAmount;
	var totalAddiAmt = 0;
	var addiAmount;
	
	for(var i=1; i<=5;i++)
	{
		deducAmount = parseFloat( $("#deducAmount"+i).val().replace(/,/g,""));
		if (!isNaN(deducAmount)) 
		{
			totalDeducAmt += deducAmount; 
		}
		
		/* SEMENTARA SEMUA YANG TERKAIT ADDITIONAL DIHILANGKAN
		addiAmount = parseFloat( $("#addiAmount"+i).val().replace(/,/g,""));
		if (!isNaN(addiAmount)) 
		{
			totalAddiAmt += addiAmount; 
		}*/
	}
	
	hasil = ( parseFloat(incomingAmt) - totalDeducAmt) + totalAddiAmt;

	$("#spanFinalAmt").html( "" );
	$("#spanFinalAmt").html( formatNumber(hasil) );
	$("#finalAmt").val( "" );
	$("#finalAmt").val( hasil );
	//totalAddi += parseFloat( addiAmount );
	if(keyUp == "deduc")// JIKA AMOUNT DEDUCTION DIISI MAKA UPDATE TOTALCREDAMT
	{
		var totalCredAmt = 0;
		totalCredAmt = (hasil - totalAddiAmt) + totalDeducAmt;
		
		$("#totalDeduc").val( totalDeducAmt );
		$("#spanTotalCredAmt").html( formatNumber(totalCredAmt) );
		$("#totalCredAmt").val( totalCredAmt );
	}
	if(keyUp == "addi") // JIKA AMOUNT ADDITIONAL DIISI MAKA UPDATE TOTALDEBAMT
	{
		var totalDebAmt = 0;
		var totalSplitDeb = parseInt( $("#totalSplitDeb").val() );
		totalDebAmt = (totalSplitDeb + totalAddiAmt);
		
		$("#totalAddi").val( totalAddiAmt );
		$("#spanTotalDebAmt").html( formatNumber(totalDebAmt) );
		$("#totalDebAmt").val( totalDebAmt );
	}
	
	setTimeout(function()
	{
		totalBalanceSplit();
	},100);
}

function updateFinalAmt(keyUp)
{
	
	var hasil = "";
	var incomingAmt = document.getElementById("incomingAmt").value;
	var totalDeducAmt = 0;
	var deducAmount;
	var totalAddiAmt = 0;
	var addiAmount;
	var totalCredAmt = 0;
	
	for(var i=1; i<=5;i++)
	{
		deducAmount = parseFloat( $("#deducAmount"+i).val().replace(/,/g,""));
		if (!isNaN(deducAmount)) 
		{
			totalDeducAmt += deducAmount; 
		}
		
		/* SEMENTARA SEMUA YANG TERKAIT ADDITIONAL DIHILANGKAN
		addiAmount = parseFloat( $("#addiAmount"+i).val().replace(/,/g,""));
		if (!isNaN(addiAmount)) 
		{
			totalAddiAmt += addiAmount; 
		}*/
	}
	
	if(keyUp == "deduc")// JIKA AMOUNT DEDUCTION DIISI MAKA UPDATE TOTALCREDAMT
	{
		//totalCredAmt = parseFloat(incomingAmt) - totalDeducAmt;
		totalCredAmt = parseFloat(incomingAmt);

		$("#totalDeduc").val( totalDeducAmt );
		$("#spanTotalCredAmt").html( formatNumber(totalCredAmt) );
		$("#totalCredAmt").val( totalCredAmt );
	}
	
	if(keyUp == "addi") // JIKA AMOUNT ADDITIONAL DIISI MAKA UPDATE TOTALDEBAMT
	{
		var totalDebAmt = 0;
		//var totalSplitDeb = parseFloat( $("#totalSplitDeb").val() );
		var totalSplitDeb = parseFloat( document.getElementById("totalSplitDeb").value );
		totalDebAmt = (totalSplitDeb + totalAddiAmt); // TOTAL AMOUNT DEBIT ADALAH TOTAL JUMLAH YANG DI SPLIT DITAMBAH DENGAN TOTAL ADDITIONAL
		totalCredAmt = parseFloat(incomingAmt) +  + totalAddiAmt;
		
		
		document.getElementById("totalAddi").value = totalAddiAmt;
		
		document.getElementById("spanTotalCredAmt").innerHTML = formatNumber(totalCredAmt);
		document.getElementById("totalCredAmt").value = totalCredAmt;
		document.getElementById("spanTotalDebAmt").innerHTML = formatNumber(totalDebAmt);
		document.getElementById("totalDebAmt").value = totalDebAmt;
		
		$("#totalAddi").val( totalAddiAmt );
		
		$("#spanTotalCredAmt").html( formatNumber(totalCredAmt) );
		$("#totalCredAmt").val( totalCredAmt );
		$("#spanTotalDebAmt").html( formatNumber(totalDebAmt) );
		$("#totalDebAmt").val( totalDebAmt );
	}
	
	setTimeout(function()
	{
		totalBalanceSplit();
	},100);
}

function totalBalanceSplit()
{
	var totalCredAmt = parseFloat( $("#totalCredAmt").val() );
	var totalDebAmt = parseFloat( $("#totalDebAmt").val() );
	var balanceAmt = totalCredAmt - totalDebAmt;
	/*alert(totalCredAmt+" | "+totalDebAmt);*/
	
	if($('#splitDebAcc').attr('checked') == true)
	{
		/*$("#spanFinalBalance").html( "" );
		$("#spanFinalBalance").html( formatNumber(balanceAmt).replace("-","") );*/
		$("#spanBalance").html( "" );
		$("#spanBalance").html( formatNumber(balanceAmt).replace("-","") );
		$("#balanceAmt").val( "" );
		$("#balanceAmt").val( balanceAmt );
	}
	else
	{
		/*$("#spanFinalBalance").html( "" );
		$("#spanFinalBalance").html( "0" );*/
		$("#spanBalance").html( "" );
		$("#spanBalance").html( "0" );
		$("#balanceAmt").val( "" );
		$("#balanceAmt").val( "0" );
	}
}

function totalBalanceSplitCredit()
{
	var totalCredAmt = parseFloat( $("#totalCredAmt").val() );
	// var totalDebAmt = parseFloat( $("#totalDebAmt").val() );
	var totalSpliteCre = parseFloat( $("#totalSplitCre").val() );
	var balanceAmt = totalCredAmt - totalSpliteCre;
	/*alert(totalCredAmt+" | "+totalDebAmt);*/
	 // alert("Kredit = "+totalCredAmt+" Total Splite Credit = "+totalSpliteCre);
	// return false;
	if($('#splitCreAcc').attr('checked') == true)
	{
		/*$("#spanFinalBalance").html( "" );
		$("#spanFinalBalance").html( formatNumber(balanceAmt).replace("-","") );*/
		$("#spanBalanceCre").html( "" );
		$("#spanBalanceCre").html( formatNumber(balanceAmt).replace("-","") );
		$("#balanceAmt").val( "" );
		$("#balanceAmt").val( balanceAmt );
	}
	else
	{
		/*$("#spanFinalBalance").html( "" );
		$("#spanFinalBalance").html( "0" );*/
		$("#spanBalanceCre").html( "" );
		$("#spanBalanceCre").html( "0" );
		$("#balanceAmt").val( "" );
		$("#balanceAmt").val( "0" );
	}
}

function klikBtnViewDebYes()
{
	document.getElementById('iframeSplitDebList').contentWindow.location.reload(true);

	$("#divKontenSplitDeb").css("display","inline");	
	$("#divCloseSplitDeb").css("display","inline");
	
	disabledBtn("btnCancelTrf");
	disabledBtn("btnReset");
}

function klikBtnViewCreYes()
{
	document.getElementById('iframeSplitCreList').contentWindow.location.reload(true);

	$("#divKontenSplitCre").css("display","inline");	
	$("#divCloseSplitCre").css("display","inline");
	
	disabledBtn("btnCancelTrf");
	disabledBtn("btnReset");
}

function klikBtnCloseSplitDebYes()
{	
	$("#divKontenSplitDeb").css("display","none");	
	$("#divCloseSplitDeb").css("display","none");
	
	enabledBtn("btnCancelTrf");
	enabledBtn("btnReset");
}

function klikBtnCloseSplitCreYes()
{	
	$("#divKontenSplitCre").css("display","none");	
	$("#divCloseSplitCre").css("display","none");
	
	enabledBtn("btnCancelTrf");
	enabledBtn("btnReset");
}

function pilihBtnCancelTrf()
{	
	var answer  = confirm("Have you already cancel the manipulating data in Accounting?");
	if(answer)
	{	
		var answer  = confirm("Are you sure want to Cancel Transfer?");
		if(answer)
		{	
			pleaseWait();
			document.onmousedown=disableLeftClick;
			
			formCancelRet.submit();
		}
		else
		{	return false;	}
	}
	else
	{	return false;	}	
}

function formatNumber (num) 
{
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

function cekDataLockNya(idMailInv)
{
	$.post( "../halPostMailInv.php",
	{ aksi:"cekLockDataNya", id:idMailInv }, 
	function(data){
		if(data != "")
		{
			alert(data);
			return false;
		}else{
			alert(data+" <= kosong");
		}
	});
}

