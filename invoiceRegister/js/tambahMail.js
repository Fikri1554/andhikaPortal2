// JavaScript Document
$(document).ready(function()
{
	$(this).click(function()
	{
		$.post( "../halPostMailInv.php", { aksi:"cekBarcode", barcode:$('#barcode').val() }, function( data )
		{
			$('#idCekBarcodeSama').html( data );
		});
	});
	
	$(this).mouseover(function()
	{
		$.post( "../halPostMailInv.php", { aksi:"cekBarcode", barcode:$('#barcode').val() }, function( data )
		{
			$('#idCekBarcodeSama').html( data );
		});
	});	
	
	$("#sendervendor1").keydown(function( eSen )
	{
		
		var senderVendor1 = $("#sendervendor1");
		if(eSen.keyCode == 222)// UNTUK CHAR TANDA KUTIP (")(')
		{
			return false;
		}
	});
	
	$("#noInvoice").keydown(function( eInv )
	{
		if(eInv.keyCode == 222)// UNTUK CHAR TANDA KUTIP (")(')
		{
			return false;
		}
	});
	$("#dueDay").keydown(function( eDue )
	{
		if(eDue.keyCode ==  189 || eDue.keyCode ==  109) // UNTUK CHAR MINUS (-)
		{
			return false;
		}
	});
/*	$("#noInvoice").keyup(function( event )
	{
		$(this).val( $(this).val().replace(/"|'/g,'') );
	});
	*/
	$("#dueDay").keyup(function()
	{
		pilihDueDay(); // KETIKA DUE DAY DIISI MAKA YANG BERUBAH ADALAH
	});
	
	$("#invoiceDate").focus(function()
	{
		pilihInvoiceDate();
	});
	
	$("#invoiceDate").keyup(function()
	{
		pilihInvoiceDate();
	});
	
	$("#dueDate").focus(function()
	{
		pilihDueDate();
	});
	
	$("#dueDate").keyup(function()
	{
		pilihDueDate();
	});
	
	
	// AUTO COMPLETE UNTUK MENU SENDER VENDOR DI HALAMAN HALTAMBAHMAILINV.PHP
	$("#senderVendor").focus(function (event)
	{	
		$('#autoCompSender').css('display','block'); // MUNCULKAN MENU AUTOCOMPLETE SENDERVENDOR
		$('#autoCompSender').html( '' );
		$.post( "../halPostMailInv.php", { aksi:"ketikAutoComplSender", param:$('#senderVendor').val() }, function( data )
		{
			$('#autoCompSender').html( data );
		});
	});
	
	$("#senderVendor").keyup(function (event)
	{	
		$('#senderVendorCode2').html( '' );
		
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
					$('#senderVendorCode2').html( data );
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
			//$( this ).find( "a" ).text( "mouse out " );
			/*$(":focus").each(function() 
			{
				$('#autoCompSender').css('display','block');
				if(this.id != "senderVendor")
				{
					$('#autoCompSender').css('display','none');
				}
			});*/
		}
	});
});

function clickMenuSend(acctCode, acctName)
{
	$('#senderVendorCode').val(acctCode);
	$('#senderVendorCode2').html(acctCode);
	$('#senderVendor').val(acctName);
	
	$('#autoCompSender').css('display','none');
	$('#urutSendSelect').val('0'); // JIKA KETIK PADA SENDER VENDOR MAKA URUTAN AUTO COMPLETE KEMBALI KE 0
}


function pilihInvoiceDate()
{
	var invoiceDate = $('#invoiceDate');
	var dueDay = $('#dueDay');
	var dueDate = $('#dueDate');

	if(dueDay.val() != "" && dueDate.val() == "" || dueDay.val() != "" && dueDate.val() != "")
	{
		$.post( "../halPostMailInv.php", { aksi:"isiDueDate", invoiceDate:invoiceDate.val(), dueDay:dueDay.val() }, function( data )
		{
			$('#divDueDate').html( data )
			dueDate.val( $('#divDueDate').text() );
		});
	}
	else if(dueDay.val() == "" && dueDate.val() != "")
	{
		$.post( "../halPostMailInv.php", { aksi:"isiDueDay", invoiceDate:invoiceDate.val(), dueDate:dueDate.val() }, function( data )
		{
			$('#divDueDay').html( data );
			dueDay.val( $('#divDueDay').text() );
		});
	}
}

function pilihDueDate()
{
	var invoiceDate = $('#invoiceDate');
	var dueDay = $('#dueDay');
	var dueDate = $('#dueDate');
	
	if(invoiceDate.val() != "") 
	{// YANG BERUBAH NILAINYA ADALAH DUE DAY
		$.post( "../halPostMailInv.php", { aksi:"isiDueDay", invoiceDate:invoiceDate.val(), dueDate:dueDate.val() }, function( data )
		{
			$('#divDueDay').html( data ); // ISI DIV 'divDueDay' DENGAN DATA HASIL AJAX
			dueDay.val( $('#divDueDay').text() ); // BERI NILAI KOLOM dueDay DENGAN ISI TEKS DARI 'divDueDay'
		});
	}
}

function pilihDueDay()
{
	var invoiceDate = $('#receiveDate');
	var dueDay = $('#dueDay');
	var dueDate = $('#dueDate');

	if(invoiceDate.val() != "") 
	{
		// YANG BERUBAH NILAINYA ADALAH DUE DATE
		$.post( "../halPostMailInv.php", { aksi:"isiDueDate", invoiceDate:invoiceDate.val(), dueDay:dueDay.val() }, function( data )
		{
			$('#divDueDate').html( data )
			dueDate.val( $('#divDueDate').text() );
		});
	}
}

function tutupNewMail(sure)
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

function setup()
{
	//var upperMask = new InputMask(fieldBuilder.upperAll(), "sendervendor1");
	
	var barcodeMask =  new InputMask("U#######", "barcode");
	var dueDayMask = new NumberMask(new NumberParser(0, "", "", true), "dueDay", 3);
		
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var receiveDate = new DateMask("dd/MM/yyyy", "receiveDate");
	receiveDate.validationMessage = errorMessage;
	var dueDate = new DateMask("dd/MM/yyyy", "dueDate");
	dueDate.validationMessage = errorMessage;
	var invoiceDate = new DateMask("dd/MM/yyyy", "invoiceDate");
	invoiceDate.validationMessage = errorMessage;
	
	var decimalSeparator = ",";
    var groupSeparator = ".";
		
	//var amountMaskk = new NumberParser(2, ".", ",", true);
	//var amountMask = new NumberMask(amountMaskk, "amount", 12);
}

function hanyaAngkaAmount()
{
	amountMask = new Mask("#,###.##", "number");
	amountMask.attach(document.getElementById('amount'));
}

function isiBarcode(nilai)
{
	var amount = $('#amount');
	var dueDay = $('#dueDay');
	var dueDate = $('#dueDate');
	var currency = $('#currency');
	
	amount.attr("disabled","");
	amount.css("background-color","");
	dueDay.attr("disabled","");
	dueDay.css("background-color","");
	dueDate.attr("disabled","");
	dueDate.css("background-color","");
	$('#imgCalDueDate').attr("disabled","");
	currency.attr("disabled","");
	currency.css("background-color","");
	if(nilai.substring(0,1) == "S")
	{
		amount.attr("disabled","disabled");
		amount.css("background-color","E9E9E9");
		dueDay.attr("disabled","disabled");
		dueDay.css("background-color","E9E9E9");
		dueDate.attr("disabled","disabled");
		dueDate.css("background-color","E9E9E9");
		$('#imgCalDueDate').attr("disabled","disabled");
		currency.attr("disabled","disabled");
		currency.css("background-color","E9E9E9");
	}
}

function tahunbalik(str)
{
	var thn = str.substring(6,str.length)
	var bln = str.substring(3,5)
	var tgl = str.substring(0,2)
	return thn.concat(bln).concat(tgl)
}

function pilihBtnSave()
{
	var dateNow = $('#dateNow');
	var receiveDate = $('#receiveDate');
	var senderVendor = $('#senderVendor');
	var company = $('#company');
	var unitt = $('#unitt');
	var barcode = $('#barcode');
	var allowBarcode = $('#allowBarcode');
	var invoiceDate = $('#invoiceDate');
	var dueDate = $('#dueDate');
	var noInvoice = $('#noInvoice');
	var amount = $('#amount');
	var currency = $('#currency');
	var aksinya = $("#aksi").val();

	$('#idErrorMsg').css('visibility','hidden'); 
	if(parseInt(tahunbalik(receiveDate.val())) > parseInt(tahunbalik(dateNow.val())))
	{
		pesanError('Receive Date must be less than Today', 'receiveDate');
		return false;
	}
	if(receiveDate.val() == '')
	{
		pesanError('Receive Date is still empty', 'receiveDate');
		return false;
	}
	if($.trim( senderVendor.val() ) == '')
	{
		pesanError('Sender/Vendor is still empty', 'senderVendor');
		return false;
	}
	
	if(company.val() == 'XXX')
	{
		pesanError('You have not selected Company', 'company');
		return false;
	}
	
	if(unitt.val() == "XXX")
	{
		pesanError('You have not selected Unit', 'unitt');
		return false;
	}
	
	if( $.trim( barcode.val() ) == '' )
	{
		pesanError("Barcode is still empty", "barcode");
		return false;
	}
	
	if(barcode.val().length != 8)
	{
		pesanError('Barcode text length must be 8', 'barcode');
		return false;
	}
	
	
	if(barcode.val().substring(0,1) != 'A' && barcode.val().substring(0,1) != 'S')
	{
		pesanError("Barcode format is wrong", "barcode");
		return false;
	}

	if( $('#barcodeSamaAdaTidak').val() == 'ada' )
	{
		if(barcode.val() != allowBarcode.val())
		{
			pesanError('Barcode is already used', 'barcode');
			return false;
		}
	}
			
	if(invoiceDate.val() == '')
	{
		pesanError('Invoice Date is still empty', 'invoiceDate');
		return false;
	}
	
	if(barcode.val().substring(0,1) == 'A' && dueDate.val() == '')
	{
		pesanError('Due date is still empty', 'dueDate');
		return false;
	}
	
	if(barcode.val().substring(0,1) == 'A' && $.trim( noInvoice.val() ) == '')
	{
		pesanError('Mail / Invoice Number is still empty', 'noInvoice');
		return false;
	}
	
	/*if(noInvoice.val().search(/\\|\/|:|\*|\?|"|<|>|\|/i) != "-1")
	{
		pesanError('Mail/Invoice No cannot contain any of the following characters \ / : * ? " < > |', 'noInvoice');
		return false;
	}*/
	
	if(barcode.val().substring(0,1) == 'A' && amount.val().replace(/,/gi,'') == '')
	{
		pesanError('Amount is still empty', 'amount');
		return false;
	}
	if(barcode.val().substring(0,1) == 'A' && currency.val() == 'XXX')
	{
		pesanError('You have not selected Currency', 'currency');
		return false;
	}	

	setTimeout(function()
	{ 
		if( $('#barcodeSamaAdaTidak').val() == 'ada' )
		{
			if(barcode.val() != allowBarcode.val())
			{
				pesanError('Barcode is already used', 'barcode');
				return false;
			}
		}		
		
		var answer  = confirm('Are you sure want to Save?');
		if(answer)
		{	
			pleaseWait();
			document.onmousedown=disableLeftClick;
			
			$.post( "../halPostMailInv.php", { aksi:"cekAckYesNo", idMailInv:$('#idMailInv').val() }, function( data )
			{	$('#idCekAckYesNo').html( data );	}); // CEK APAKAH INVOICE SUDAH PERNAH DI ADD TO GROUP ITEM BELUM SAMA USER LAIN
			
			setTimeout(function()
			{
				if( $('#ackYesNo').val() == 'yes' ) //JIKA SUDAH PERNAH DI ACKNOWLEDGE OLEH USER LAIN 
				{
					doneWait();
					panggilEnableLeftClick();
					
					var answer  = confirm("Sorry, Already Acknowledge by Another user.\n\rAre you wanto to Refresh page?");
					if(answer)
					{
						parent.openThickboxWindow('klikBtnEdit');return false;// REFRESH PAGE
					}
					else
					{
						return false;
					}
				}
				else//JIKA BELUM PERNAH DI DI ACKNOWLEDGE OLEH USER LAIN 
				{
					if($('#barcode').val().substring(0,1) == 'A' && aksinya == 'simpanBaru')
					{
						$.post( "../halPostMailInv.php",
							{ aksi:"cekInvoiceNo", noInvoice:$('#noInvoice').val() }, 
							function( data )
							{
								if(data == "ada")
								{					
									pesanError('Invoice No Already Exist..!!', 'noInvoice');
									doneWait();
									return false;
								}else{
									$('#idErrorMsg').css('visibility','hidden');
									formTambahMailInv.submit();
								}
						});
					}else{
						formTambahMailInv.submit();
					}
				}
				
			}, 250);
		}
		else
		{	return false;	}
	}, 250);
}

function pesanError(pesan, itemFokus)
{
	document.getElementById(itemFokus).focus(); 
	$('#idErrorMsg').css('visibility','visible'); 
	$("#idErrorMsg").html("<img src=\"../picture/exclamation-red.png\"/>&nbsp;<span>"+pesan+"</span>&nbsp;");
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

function cekNoInvoiceNya()
{
	if($('#barcode').val() == "")
	{
		pesanError('Barcode is Empty..!!', 'barcode');
		$('#noInvoice').val("");
		return false;
	}
	if($('#barcode').val().substring(0,1) == 'A')
	{
		$.post( "../halPostMailInv.php",
			{ aksi:"cekInvoiceNo", noInvoice:$('#noInvoice').val() }, 
			function( data )
			{
				if(data == "ada")
				{					
					pesanError('Invoice No Already Exist..!!', 'noInvoice');
					doneWait();
					return false;
				}else{
					$('#idErrorMsg').css('visibility','hidden');
				}
		});
	}
}

setInterval(blinker, 1500);