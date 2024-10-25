// JavaScript Document
 
function checkNotif()
{
	var old_count = 0;
	var i = 0;
	var totalEmail = 0;
	var timerId = 0;
	var fromShip = "";
	var jenisReport = "";
	var timeSendHo = "";
	var subjectBaru = "";
	
	timerId = setInterval(function()
	{    
		//var nmVsl = $("#nmVsl option:selected").val();
		//alert(nmVsl);
		$.ajax({
			type : "POST",
			url : "halPostVslRep.php?aksi=checkEmailEop",
			success : function(data)// CONTOH DATA YANG DIKIRIMKAN (DATA = 3##ANDHIKA NARESWARI##eop##20170316111804)
			{
				var splitData = data.split("##"); // // PISAHKAN SUBJECT BERDASARKAN KARAKTER ##
				totalEmail = splitData[0]; // AMBIL TOTAL EMAIL YANG BERADA DI URUTAN PERTAMA
				fromShip = splitData[1];
				jenisReport = splitData[2];
				timeSendHo = splitData[3];
				subjectBaru = fromShip+"##"+jenisReport+"##"+timeSendHo
				
				//$("#divDetailInfo").html(totalEmail); // DATA = TOTAL EMAIL DI INBOX VIS@ANDHIKA.COM
				
				if (totalEmail > old_count) // JIKA DATA LEBIH BESAR DARI DATA YANG LAMA ARTINYA ADA EMAIL BARU MASUK
				{ 
					if (i == 0)
					{
						old_count = totalEmail;
					} 
					else // JIKA ADA EMAIL BARU
					{
						alert(totalEmail+", "+fromShip+", "+jenisReport+", "+timeSendHo);
						old_count = totalEmail;
						
						pesanNotif("New Message : "+jenisReport.toUpperCase()+" From "+fromShip);
						
						$.post( "halPostVslRep.php", { aksi:"simpanNewEmailEop", subjectBaru:subjectBaru }, function( dataa )
						{
							//$("#divDetailInfo").html(dataa);	
						});
						
						// JIKA INGIN MEMBERHENTIKAN TIMER
						//clearInterval(timerId); 
						
						setTimeout(function()
						{
							klikBtnRefresh();
						}, 250);
					}
				} 
				i=1;
			}
		});
	},3000);
}

function pesanNotif(teks)
{
	$("#divPesanNotif").html( teks );
	setTimeout(function()
	{
		$("#divPesanNotif").html("Standby...");
	}, 1000);
}
 
function pilihMenuVsl(nmVsl)
{
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	loadIframe("iframeList", "");
	loadIframe("iframeList", "templates/halVslRepList.php?aksi=display&nmVsl="+nmVsl);
}
function klikBtnRefresh()
{
	loadIframe("iframeList", "");
	loadIframe("iframeList", "templates/halVslRepList.php?aksi=display&nmVsl="+$('#nmVsl').val());
	$("#divDetailInfo").html("");	
}