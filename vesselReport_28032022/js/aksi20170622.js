// JavaScript Document 
function checkNotif()
{
	var old_count = 0;
	var i = 0;
	var totalEmail = 0;
	var timerId = 0;
	var jenisReport = "";
	var fromShip = "";
	var timeSendHo = "";
	
	timerId = setInterval(function()
	{    
		var nmVsl = $("#nmVsl option:selected").val();
		//alert(nmVsl);
		$.ajax({
			type : "POST",
			url : "halPostVslRep.php?aksi=checkNotifEop&nmVsl="+nmVsl,
			success : function(data)// CONTOH DATA YANG DIKIRIMKAN (DATA = 3##ANDHIKA NARESWARI##eop##20170316111804)
			{
				var splitData = data.split("##"); // // PISAHKAN SUBJECT BERDASARKAN KARAKTER ##
				totalEmail = splitData[0]; // AMBIL TOTAL EMAIL YANG BERADA DI URUTAN PERTAMA
				fromShip = splitData[1];
				jenisReport = splitData[2];
				timeSendHo = splitData[3];
				//$("#divDetailInfo").html(totalEmail); // DATA = TOTAL EMAIL DI INBOX VIS@ANDHIKA.COM
				
				if (totalEmail > old_count) // JIKA DATA LEBIH BESAR DARI DATA YANG LAMA ARTINYA ADA EMAIL BARU MASUK
				{ 
					if (i == 0)
					{
						old_count = totalEmail;
					} 
					else // JIKA ADA EMAIL BARU
					{
						//alert('Email baru');
						old_count = totalEmail;
						pesanNotif("New Message : "+jenisReport.toUpperCase()+" From "+fromShip);
						
						$.post( "halPostVslRep.php", { aksi:"simpanNewEmailEop", subjectBaru:fromShip+"##"+jenisReport+"##"+timeSendHo }, function( dataa )
						{
							//alert(dataa);
							//$("#divDetailInfo").html(dataa);	
						});
						//clearInterval(timerId); // JIKA INGIN MEMBERHENTIKAN TIMER
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