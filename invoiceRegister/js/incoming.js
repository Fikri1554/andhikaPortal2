// JavaScript Document

/*function loadUrl(newLocation)
{
	window.location = newLocation;
	return false;
}

function klikBtnSearch()
{
	document.getElementById('hrefThickbox').href="templates/halSearch.php?placeValuesBeforeTB_=savedValues&TB_iframe=true&height=570&width=900&modal=true";
	document.getElementById('hrefThickbox').click();
}
*/
$(document).ready(function()
{		
	/*$(this).click(function(event) 
	{ // JIKA SELAIN BUTON SEARCH DIKLIK MAKA POPUP WINDOW CARI AKAN HILANG
    	if(!$(event.target).closest('#idDivTableCari').length) 
		{
        	if($('#idDivTableCari').is(":visible")) 
			{
            	$('#idDivTableCari').css('display','none');
        	}
   	 	}        
	});
	
	$("#btnCari").click(function()
	{
		if($('#idDivTableCari').css("display") == 'none')
		{
			$('#idDivTableCari').fadeTo(500, 10);
			$('#idDivTableCari').css('display','block');
			//$('#btnDoSearch').removeClass('btnStandar');
			//$('#btnDoSearch').addClass('btnStandar');
			return false;
		}
		if($('#idDivTableCari').css("display") == 'block')
		{
			$('#idDivTableCari').css('display','none');
			return false;
		}
	});*/
});

function ajaxGet(id, id2, paramAksi, halaman)
{
	
	if(paramAksi == "pilihMenuThnBln")
	{
		var paramThnBln = id;
		var paramTgl = id2;	
		//alert(paramThnBln+' / '+paramTgl);
		$.post( "halPostMailInv.php", { aksi:paramAksi, thnBln:paramThnBln, tgl:paramTgl }, function( data )
		{
			$('#'+halaman).html( data );
		});
	}
}

function halamanOnLoad(tglPilih)
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	//rubahMenuThnBln(tglPilih);
	ajaxGet($('#menuBatchnoThnBln').val(), tglPilih, 'pilihMenuThnBln', 'idTdMenuTgl');
	setTimeout(function()
	{
		beriBatchno();
		klikBtnDisplay();
	},300);
}

/*function rubahMenuThnBln(tglPilih)
{
	var thnBlnPilih = $('#menuBatchnoThnBln').val();
	var tglPilih = tglPilih;
	
	ajaxGet(thnBlnPilih, tglPilih, 'pilihMenuThnBln', 'idTdMenuTgl');
}*/

function beriBatchno()
{
	setTimeout(function()
	{
		$('#batchno').val( $('#menuBatchnoThnBln').val()+$('#menuBatchnoTgl').val() );
	},300);	
}

function rubahMenuBatchnoThnBln()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	//rubahMenuThnBln('');
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

function klikBtnRefresh()
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	klikBtnDisplay();
}

function klikBtnDisplay()
{
	setTimeout(function()
	{
		disabledBtn('btnIncomingEdit');
		disabledBtn('btnIncomingDelete');
		disabledBtn('btnIncomingTobePaid');
		
		var sortBy = $('#sortBy').val();
		var ascBy = $('#ascBy').val();
		var thnBln = $('#menuBatchnoThnBln').val();
		var tgl = $('#menuBatchnoTgl').val();
	
		loadIframe('iframeList', '');
		loadIframe('iframeList', 'templates/halIncomingList.php?aksi=display&thnBln='+thnBln+'&tgl='+tgl+'&sortBy='+sortBy+'&ascBy='+ascBy);
	},300);
}

function emptyFrameList(iframeId)
{
	//document.getElementById('iframeMailInvList').src = "";
	loadIframe(iframeId, "");
}

function openThickboxWindow(aksi)
{	
	if(aksi == 'klikBtnNew')
	{
		//var batchno = document.getElementById('batchno').value;
		var batchno = $('#batchno').val();
		document.getElementById('hrefThickbox').href = "templates/halTambahMailInv.php?aksi=tambahMailInv&batchno="+batchno+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=550&width=550&modal=true";
	}
	if(aksi == 'klikBtnEdit')
	{
		var batchno = $('#batchno').val();
		var idMailInv = $('#idMailInv').val();
		document.getElementById('hrefThickbox').href = "templates/halUbahMailInv.php?batchno="+batchno+"&idMailInv="+idMailInv+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=550&width=550&modal=true";
	}
	if(aksi == 'klikBtnCari')
	{
		document.getElementById('hrefThickbox').href = "templates/halCariMailInv.php?placeValuesBeforeTB_=savedValues&TB_iframe=true&height=530&width=850&modal=true";
	}
	if(aksi == 'klikBtnPrint')
	{
		document.getElementById('hrefThickbox').href = "templates/halCariMailInv.php?placeValuesBeforeTB_=savedValues&TB_iframe=true&height=530&width=850&modal=true";
	}
	if(aksi == 'klikBtnTobePaid')
	{
		var batchno = $('#batchno').val();
		var idMailInv = $('#idMailInv').val();
		document.getElementById('hrefThickbox').href = "templates/halTobePaid.php?batchno="+batchno+"&idMailInv="+idMailInv+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=550&width=550&modal=true";
	}
	
	document.getElementById('hrefThickbox').click();
}

function klikBtnDelete()
{
	
	var answer  = confirm('Are you sure want to Delete?');
	if(answer)
	{	
		pleaseWait();
		document.onmousedown=disableLeftClick;
		
		var batchno = $('#batchno').val();
		var idMailInv = $('#idMailInv').val();
		var thnBln = document.getElementById('menuBatchnoThnBln').value;
		var tgl = document.getElementById('menuBatchnoTgl').value; 
		document.getElementById('iframeList').src = "";
		document.getElementById('iframeList').src = "templates/halIncomingList.php?aksi=delete&thnBln="+thnBln+"&tgl="+tgl+"&idMailInv="+idMailInv+"&batchno="+batchno;
	}
	else
	{	return false;	}
}

function klikBtnInvProcess()
{
	var batchno = $('#batchno').val();
	var thnBlnPilih = $('#menuBatchnoThnBln').val();
	var tglPilih =  $('#menuBatchnoTgl').val();
	
	$('#formProcess').attr('action', 'index.php?thnBlnPilih='+thnBlnPilih+'&tglPilih='+tglPilih);
	
	$('#formProcess').submit();
	return false;
}


function teksMap(value)
{
	parent.document.getElementById('idTeksMap').innerHTML = "<a>"+value+"</a>";
}

function incomingToBePaid()
{
	var idMailInv = $('#idMailInv').val();
	var companyName = $('#txtHideCompanyName_24428').val();
	alert(idMailInv+" => "+companyName);
}