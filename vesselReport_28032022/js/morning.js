// JavaScript Document
function checkNotif(jenisReport)
{
	var old_count = 0;
	var i = 0;
	var hitung = 0;
	
	timerId = setInterval(function() // TIMER TIAP 3 DETIK AKAN MENGECEK APAKAH JUMLAH TOTAL PESAN YANG BELUM DIBACA BERTAMBAH APA TIDAK
	{  
		$.ajax(
		{
			type : "POST", url : "halPostVslRep.php?aksi=checkPesan&jenisReport="+jenisReport, success : function(data)
			{
				var totalEmail = data; 
				//alert(totalEmail);
				if (totalEmail > old_count) // JIKA DATA LEBIH BESAR DARI DATA YANG LAMA ARTINYA ADA EMAIL BARU MASUK
				{ 
					hitung = 0;
					if (i == 0)
					{
						old_count = totalEmail;
					} 
					else // JIKA ADA EMAIL BARU
					{
						old_count = totalEmail;
						var answer  = confirm("New Message from System\r\nAre you sure want to Open?");
						if(answer)
						{	
							klikBtnNotif(jenisReport);
						}
						else
						{	
							checkBtnNotif(jenisReport);
							return false;
						}
					}
				}
				else
				{
					old_count = totalEmail;
				}
				
				i=1;
				hitung++;
				/*$("#divPesanNotif").html(hitung+"<br>Total Baru : "+totalEmail+"<br>Total Lama : "+old_count);*/
			}
		});
	},3000);  
}

function checkBtnNotif(jenisReport)
{
	$.post( "halPostVslRep.php", { aksi:"checkBtnNotif", jenisReport:jenisReport }, function( data )
	{
		$("#divBtnMessage").html(data);	
	});
}

function klikBtnNotif(jenisReport)
{
	$("#hrefThickbox").prop("href", "templates/halPopUp.php?aksi=checkNotif&reportType="+jenisReport+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=400&width=400&modal=true");
	$("#hrefThickbox").click();
}

function klikBtnRefresh()
{
	disabledBtn('btnPrint');
	checkBtnNotif("morning");
	
	loadIframe("iframeList", "");
	loadIframe("iframeList", "templates/halMorningList.php?aksi=display&nmVsl="+$('#nmVsl').val());
	
	$("#divDetailInfo").html("");	
}

function klikBtnPrint()
{
	var idMorning = $("#idMorning").val();
	
	$('#formPrint').attr('action', 'halPrint.php?aksi=printMorning&idMorning='+idMorning);
	formPrint.submit();
	
}