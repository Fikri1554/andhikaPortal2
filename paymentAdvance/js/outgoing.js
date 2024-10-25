// JavaScript Document

$(document).ready(function()
{		
	
});

function ajaxGet(id, id2, paramAksi, halaman)
{
	if(paramAksi == "pilihMenuThnBlnOutgoing")
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
	
	ajaxGet($('#menuBatchnoThnBln').val(), tglPilih, 'pilihMenuThnBlnOutgoing', 'idTdMenuTgl');
	beriBatchno();
	klikBtnDisplay();
}

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
	
	ajaxGet($('#menuBatchnoThnBln').val(), '', 'pilihMenuThnBlnOutgoing', 'idTdMenuTgl');
	
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
		
		var sortBy = $('#sortBy').val();
		var ascBy = $('#ascBy').val();
		var thnBln = $('#menuBatchnoThnBln').val();
		var tgl = $('#menuBatchnoTgl').val();
	
		loadIframe('iframeList', '');
		/*loadIframe('iframeList', 'templates/halIncomingList.php?aksi=display&thnBln='+thnBln+'&tgl='+tgl+'&sortBy='+sortBy+'&ascBy='+ascBy);*/
		loadIframe('iframeList', 'templates/halOutgoingList.php?aksi=display&thnBln='+thnBln+'&tgl='+tgl+'&sortBy='+sortBy+'&ascBy='+ascBy);
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
		document.getElementById('hrefThickbox').href = "templates/halTambahOutgoingInv.php?aksi=tambahMailInv&batchno="+batchno+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=550&width=550&modal=true";
	}
	if(aksi == 'klikBtnEdit')
	{
		var batchno = $('#batchno').val();
		var idOutgoingInv = $('#idOutgoingInv').val();
		document.getElementById('hrefThickbox').href = "templates/halUbahOutgoingInv.php?batchno="+batchno+"&idOutgoingInv="+idOutgoingInv+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=550&width=550&modal=true";
	}
	if(aksi == 'klikBtnCari')
	{
		document.getElementById('hrefThickbox').href = "templates/halCariMailInv.php?placeValuesBeforeTB_=savedValues&TB_iframe=true&height=530&width=850&modal=true";
	}
	if(aksi == 'klikBtnPrint')
	{
		document.getElementById('hrefThickbox').href = "templates/halCariMailInv.php?placeValuesBeforeTB_=savedValues&TB_iframe=true&height=530&width=850&modal=true";
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
		var idOutgoingInv = $('#idOutgoingInv').val();
		var thnBln = document.getElementById('menuBatchnoThnBln').value;
		var tgl = document.getElementById('menuBatchnoTgl').value; 
		document.getElementById('iframeList').src = "";
		document.getElementById('iframeList').src = "templates/halOutgoingList.php?aksi=delete&thnBln="+thnBln+"&tgl="+tgl+"&idOutgoingInv="+idOutgoingInv+"&batchno="+batchno;
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