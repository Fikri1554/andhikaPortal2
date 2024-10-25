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
	$("#customer").focus(function (event)
	{	
		$('#autoCompCust').css('display','block'); // MUNCULKAN MENU AUTOCOMPLETE customer
		$('#autoCompCust').html( '' );
		$.post( "../halPostMailInv.php", { aksi:"ketikAutoComplCust", param:$('#customer').val() }, function( data )
		{
			$('#autoCompCust').html( data );
		});
	});
	
	$("#customer").keyup(function (event)
	{	
		$('#custCode2').html( '' );
		
		var status = "true";
		if(event.keyCode == 40 || event.keyCode == 38)//arrow down and up
		{ // JIKA KETIK PANAH ATAS BAWAH MENU AUTOCOMPLETE TIDAK MUNCUL LAGI KARENA SUDAH MUNCUL KETIKA customer FOCUS
			status = "false";
		}
		
		if(status == "true") // JIKA KEYBOARD DIKETIK SELAIN PANAH ATAS DAN BAWAH MAKA AMBIL DATA DI DATABASE DENGAN POST
		{
			if(event.keyCode != 13)//enter // JIKA BUKAN ENTER YANG DIKETIK 
			{
				$('#autoCompCust').css('display','block');
				$('#autoCompCust').html( '' );
				$.post( "../halPostMailInv.php", { aksi:"ketikAutoComplCust", param:$('#customer').val(), urutCustSelect:$('#urutCustSelect').val() }, function( data )
				{
					$('#autoCompCust').html( data );
				});
				$.post( "../halPostMailInv.php", { aksi:"cekSenderVendorCode", senderVendorName:$('#customer').val() }, function( data )
				{ // KETIKA KETIK customer DAN CEK DATABASE PAKE AJAX LALU HASIL NYA ISIKAN KE SENDERVENDERCODE
					$('#custCode').val( data );
					$('#custCode2').html( data );
				});
			}
		}
		
		var idSelect = $('#urutCustSelect').val(); // urutan terakhir yang dipilih
		var urutId = idSelect;
		var urutIdNext = 0;
		var urutIdPrev = 0;
		var totalSendVend = $('#totalSendVend').val();
		var scrollTopNow = $('#autoCompCust').scrollTop();				
		
		if(event.keyCode == 40)//arrow down
		{	
			urutIdNext = (parseInt(urutId) + 1); 
			if(urutIdNext > totalSendVend) // JIKA URUT ID BERIKUTNYA SAMA DENGAN TOTAL ROW MAKA BERHENTI DI ROW TERAKHIR
				urutIdNext = totalSendVend;
				
			$('#trId_'+urutId).css('background-color','FFFFFF');
			$('#aId_'+urutId).css('color','555');
			$('#trId_'+urutIdNext).css('background-color','93A070');
			$('#aId_'+urutIdNext).css('color','FFFFFF');
			$('#urutCustSelect').val(urutIdNext); // ISI HIDDEN INPUT DENGAN URUTAN TERKINI
			
			if(urutIdNext > 1) // JIKA URUT ID LEBIH DARI 1 MAKA SCROOL MENU AUTO COMPLETE KEBAWAH
				$('#autoCompCust').scrollTop(scrollTopNow + $('#trId_'+urutId).eq(0).height());
			
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
			$('#urutCustSelect').val(urutIdPrev);

			if(urutIdNext < (totalSendVend))// JIKA URUT ID KURANG DARI TOTAL DATA MAKA SCROOL MENU AUTO COMPLETE KEATAS
				$('#autoCompCust').scrollTop(scrollTopNow - $('#trId_'+urutId).eq(0).height());
		}
		if(event.keyCode == 13)//enter
		{
			var acctName = $('#actName_'+urutId).val();
			if(acctName == undefined) // JIKA TEKAN ENTER BUKAN DARI MENU AUTOCOMPLETE MAKA AMBIL NILAI ACCTNAME DARI INPUT SENDER VENDOR
				acctName = $('#customer').val();
			clickMenuSend($('#acctCode_'+urutId).val(), acctName);
			
			$('#autoCompCust').css('display','none');
		}
		
	});
	
	$(this).mouseup(function (e)
	{
		var idActElem = $(document.activeElement).attr("id");
		var container = $('#autoCompCust');
		var container2 = $('#customer');
		//alert(idActElem);
		if (!container.is(e.target) // if the target of the click isn't the container...
			&& container.has(e.target).length === 0 // ... nor a descendant of the container
			&& idActElem != "customer" // APAKAH ELEMENT YANG AKTIF TESVEND APA BUKAN
			) 
		{
			$('#autoCompCust').css('display','none');
			$('#urutCustSelect').val('0');
		}
	});
});

function clickMenuSend(acctCode, acctName)
{
	$('#custCode').val(acctCode);
	$('#custCode2').html(acctCode);
	$('#customer').val(acctName);
	
	$('#autoCompCust').css('display','none');
	$('#urutCustSelect').val('0'); // JIKA KETIK PADA SENDER VENDOR MAKA URUTAN AUTO COMPLETE KEMBALI KE 0
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
	var invoiceDate = $('#invoiceDate');
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
	var dueDayMask = new NumberMask(new NumberParser(0, "", "", true), "dueDay", 3);
		
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	
	var dueDate = new DateMask("dd/MM/yyyy", "dueDate");
	dueDate.validationMessage = errorMessage;
	var invoiceDate = new DateMask("dd/MM/yyyy", "invoiceDate");
	invoiceDate.validationMessage = errorMessage;
	
	var decimalSeparator = ",";
    var groupSeparator = ".";
		
	var amountMaskk = new NumberParser(2, ".", ",", true);
	var amountMask = new NumberMask(amountMaskk, "amount", 12);
	
	var sentDate = new DateMask("dd/MM/yyyy", "sentDate");
	sentDate.validationMessage = errorMessage;
}

function tahunbalik(str)
{
	var thn = str.substring(6,str.length)
	var bln = str.substring(3,5)
	var tgl = str.substring(0,2)
	return thn.concat(bln).concat(tgl)
}

function pilihBtnSave555555555()
{
	var dateNow = $('#dateNow');
	var customer = $('#customer');
	var company = $('#company');
	var invoiceDate = $('#invoiceDate');
	var dueDay = $('#dueDay');
	var dueDate = $('#dueDate');
	var noInvoice = $('#noInvoice');
	var amount = $('#amount');
	var currency = $('#currency');
	var sentDate = $('#sentDate');

	$('#idErrorMsg').css('visibility','hidden'); 
	
	if(company.val() == 'XXX')
	{
		pesanError('You have not selected Company', 'company');
		return false;
	}
	
	if($.trim( customer.val() ) == '')
	{
		pesanError('Customer is still empty', 'customer');
		return false;
	}
			
	if(invoiceDate.val() == '')
	{
		pesanError('Invoice Date is still empty', 'invoiceDate');
		return false;
	}
	
	if(dueDay.val() == '')
	{
		pesanError('Invoice Due Date is still empty', 'dueDay');
		return false;
	}	
	
	if(dueDate.val() == '')
	{
		pesanError('Invoice Due Date is still empty', 'dueDate');
		return false;
	}	
	
	if(noInvoice.val() == '')
	{
		pesanError('Invoice No is still empty', 'noInvoice');
		return false;
	}		
	
	if(amount.val() == '')
	{
		pesanError('Amount is still empty', 'amount');
		return false;
	}
	
	if(currency.val() == 'XXX')
	{
		pesanError('Currency is still empty', 'currency');
		return false;
	}
	
	if(parseInt(tahunbalik(sentDate.val())) > parseInt(tahunbalik(dateNow.val())))
	{
		pesanError('Sent Date must be less than Today', 'sentDate');
		return false;
	}
	if(sentDate.val() == '')
	{
		pesanError('Sent Date is still empty', 'sentDate');
		return false;
	}
			
	setTimeout(function()
	{ 
		var answer  = confirm('Are you sure want to Save?');
		if(answer)
		{	
			pleaseWait();
			document.onmousedown=disableLeftClick;
			
			$.post( "../halPostMailInv.php", { aksi:"cekOutgoingAckYesNo", idOutgoingInv:$('#idOutgoingInv').val() }, function( data )
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
					formTambahOutgoingInv.submit();
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

setInterval(blinker, 1500);