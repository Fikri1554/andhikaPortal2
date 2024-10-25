// JavaScript Document
 
/*function checkNotif20170512()
{
	var old_count = 0;
	var i = 0;
	//var totalEmail = 0;
	//var timerId = 0;
	//var fromShip = "";
	//var jenisReport = "";

	timerId = setInterval(function()
	{    
		//var nmVsl = $("#nmVsl option:selected").val();
		//alert(nmVsl);
		$.ajax({
			type : "POST",
			url : "halPostVslRep.php?aksi=checkEmailEop",
			success : function(data) // CONTOH DATA YANG DIKIRIMKAN (3#####ANDHIKA NARESWARI##eop##20170316111804#####BHShoBAAw4d2lMJnR/Gmco6eK43TT4YjSz4lEfqmGhwQL4kc0lvU5WHI1ofGJrJk48V4nQ6dnahVsNCsrOnMxjLWQtxGU5MeqocjunejnBjtg47qpgG6H+nYH26BylAFDNaDoRYFZBydrMjAfXN23YZZ1II4d4POGUI5U+kMdTj8JJOPoy+GQrDfar5fNPdR3PGaL+gN5xpzTgMfcObkVRSLDdGcSk2IFQxVaDmVSBKFHeDNTBp4RNSf6dXxFTkUtj8mSYKhJaEgLX7lY2K/Jt6LCwInyHxvDIJJZO3UJBLX5p09v8xnlh65WQS+DlRN9IPnehEZmRWu8VPbb2CvdL9kZ340sjKNgG2ec3WTIpNXhWh/m6eccxjRESm1jKSdKoKuhHF+uzLknMVqTwNijgSqWY7DnCwZrvc8boka9/xBEK6fSp2OzKRMZ8NVoTOjoHNQMHMJwWRE/VftlqNcMfE5BybBVwIAbqC8+GLITZKDYvXlT1D/RZS6wbnbAf/w1Kqd85Mw43QDh2KKJHdIMAPN+/woi8wkw4MrO3NKscRgwQSk1GQUkcZik+eFweMNN+oCWF7IFX74XxJkBCh1tnlnzumGqXgn1S6U78PvQo18N1snO4UKqRk3fD+hWL+GOMhl1je5LaOKH4Z3h2Jlx3/uM8d5CKWv8NRbpvvxjlurzxHqOnaMBSr9q44XYwTqI3LRh6GlJ5A+DUF9hI5eA+pTVta8pFmY6Aw1DliaEaY5QbPmqSa7pSK6uuRGz2PbBQ3r11aMUxffwXu06c2S3Yp7YodQTPoaz//j/rTd/Ry9X+wWGjyC7RQ5glGL5s3DRGhvV0n0jmt489Kp9CshVkzeK+WsUEAAE036jKvq2YY+zabSQv95Pd9VNTerem3r+EGi8huBEu7sefSG4I2Di4HKDqeBLDMjTuRBvJlq7/ULQ1Iy5YEKkdGhEZAJPC0XrwbMggscQqDdlvaRdfyb/hKrVWmKA1P3Jwbxit076z1Qq+PuYbDHwRY2ReAv1uWnEewTGrBuoRf9iY3qUzta+G88SSVIKp3BubJHf8OhqSxUJkpFluF0niNiDU2Y6qhmdxl9+jD6TOfmYPhjKyW8IdSrwAZdAOxqOvr+gZNYjNAmlsLBsFOSAfxKY7wVRc3aKJOsqjlcMqeCS3VPYKbZSB0OMvU8Kw+yGVGlSLGOK9FdOqe0W7A2BxmASvB6ZaQ8kAM/28lo8BCEbWb8pg+ccp0mD1vlg5NNvwZmHzaAIAreLprJqmcBEq6ftWFo0+nhrII5GOcFqwDZgpLxWhkAE4u+NJIqeBPzZF2JrnSxK39+Qzb7O3jlUszI/c1WYOx07TIiRexa6xUcxyMq5IfmwOexwYh1Hxda0qXNkEbvXFawujlp8LzLPmebDp0vg/A+iT5mlaIawxVnKao2b9o7/0VGt3g4tDeAeFT3wxPMLEtxgbFOq4U5kBobJeF7wHHROyGNpjPpsTnw9Dq2rfELanWj3fw/UpXSpUunEmuH9YUMTUFmbo6qKWtKcPjXIQSG7K5QH67WotS4bupjcZj/wIc4+Avfe/j8jwAUWWjcbz1PJXRKmYYeJGk6Mvr+s69CzxRJkRpwvGIJNA4sIB6w85rWWJllSzYZiYIVXD8TL6T5iL0VCfM5lV46fRDv7FgGzd4KArr9PihR9FRkwYPajfVxUPlDS/FhIekJoIYJ3+JlxKtO30gvjk1WOSC1wnNsny008i8sb4uj/qJshFwzgPG3XAvo89l8KX88EgQhjv8=))
			{
				var splitData = data.split("#####"); // // PISAHKAN SUBJECT BERDASARKAN KARAKTER #####
				var totalEmail = splitData[0]; // AMBIL TOTAL EMAIL YANG BERADA DI URUTAN PERTAMA
				
				var warningTeks = totalEmail.search(/Warning/i); // CARI TEKS WARNING DI TEKS TOTAL EMAIL YANG DIKIRIM DARI POST
				if(warningTeks != -1) // JIKA MENEMUKAN ADA TEKS WARNING MAKA REFRESH HALAMAN, KADANG2 KONEKSI SUKA BATUK DAN CEK EMAIL PAKAI IMAP SANGAT SENSITIF
				{
					window.location.href = "";
				}
				
				var subjectBaru = splitData[1];
				var messageEnc = splitData[2];
						
				//$("#divDetailInfo").html(totalEmail); // DATA = TOTAL EMAIL DI INBOX VIS@ANDHIKA.COM
				if (totalEmail > old_count) // JIKA DATA LEBIH BESAR DARI DATA YANG LAMA ARTINYA ADA EMAIL BARU MASUK
				{ 
					if (i == 0)
					{
						old_count = totalEmail;
					} 
					else // JIKA ADA EMAIL BARU
					{
						//alert(totalEmail+", "+subjectBaru+", "+messageEnc);
						old_count = totalEmail;
				
						var splitSubject = subjectBaru.split("##");// CONTOH DATA splitSubject (ANDHIKA NARESWARI##eop##20170316111804)
						var fromShip = splitSubject[0];
						var jenisReport = splitSubject[1];
						
						pesanNotif("New Message : "+jenisReport.toUpperCase()+" From "+fromShip);
						
						$.post( "halPostVslRep.php", { aksi:"simpanNewEmailEop", messageEnc:messageEnc, jenisReport:jenisReport }, function( dataa )
						{
							//$("#divDetailInfo").html(dataa);	
							alert(dataa);
						});
						
						// JIKA INGIN MEMBERHENTIKAN TIMER
						// clearInterval(timerId); 
						
						/*setTimeout(function()
						{
							klikBtnRefresh();
						}, 250);*/
					}
				} 
				i=1;
			}
		});
	},5000);
}


function checkNotif()
{
	this.hitung = 0;
	this.timerId = 0;
	this.stopCheck = stopCheckNotif;
	this.startCheck = startCheckNotif;
}

function startCheckNotif(checkNotiff)
{
	var old_count = 0;
	var i = 0;
	var hitung = this.hitung;
	this.timerId = setInterval(function() // TIMER TIAP 3 DETIK AKAN MENGECEK APAKAH JUMLAH TOTAL PESAN YANG BELUM DIBACA BERTAMBAH APA TIDAK
	{
		hitung++; 
		//$("#divPesanNotif").html(hitung);
		$.ajax(
		{
			type : "POST", url : "halPostVslRep.php?aksi=checkPesanEop", success : function(data)
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
							//pleaseWait();
							//document.onmousedown=disableLeftClick;
							
							klikBtnNotif();
						}
						else
						{	
							checkBtnNotif();
							return false;
						}
					}
				}
				
				i=1;
				
				$("#divPesanNotif").html(hitung+"<br>Total Baru : "+totalEmail+"<br>Total Lama : "+old_count);
			}
		});
	}, 1000);
	
	return this;
}

function stopCheckNotif(checkNotiff)
{
	clearInterval(this.timerId);
	this.startCheck();
}


function checkNotif()
{
	var old_count = 0;
	var i = 0;
	var hitung = 0;
	
	timerId = setInterval(function() // TIMER TIAP 3 DETIK AKAN MENGECEK APAKAH JUMLAH TOTAL PESAN YANG BELUM DIBACA BERTAMBAH APA TIDAK
	{  
		$.ajax(
		{
			type : "POST", url : "halPostVslRep.php?aksi=checkPesanEop", success : function(data)
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
							//pleaseWait();
							//document.onmousedown=disableLeftClick;
							
							klikBtnNotif();
						}
						else
						{	
							checkBtnNotif();
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
				$("#divPesanNotif").html(hitung+"<br>Total Baru : "+totalEmail+"<br>Total Lama : "+old_count);
			}
			
		});
	},3000);  
}

function refreshCheckNotif()
{
	
}

function tes(){
	//alert("tes");
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
	loadIframe("iframeList", "templates/halEopList.php?aksi=display&nmVsl="+nmVsl);
}
function klikBtnRefresh()
{
	disabledBtn('btnPrint');
	checkBtnNotif();
	
	loadIframe("iframeList", "");
	loadIframe("iframeList", "templates/halEopList.php?aksi=display&nmVsl="+$('#nmVsl').val());
	
	$("#divDetailInfo").html("");	
}

function klikBtnPrint()
{
	var idEop = $("#idEop").val();
	
	$('#formPrint').attr('action', 'halPrint.php?aksi=printEop&idEop='+idEop);
	formPrint.submit();
}

function checkBtnNotif()
{
	$.post( "halPostVslRep.php", { aksi:"checkBtnNotif", jenisReport:"eop" }, function( data )
	{
		$("#divBtnMessage").html(data);	
	});
}

function klikBtnNotif()
{
	$("#hrefThickbox").prop("href", "templates/halPopUp.php?aksi=checkNotif&reportType=eop&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=400&width=400&modal=true");
	$("#hrefThickbox").click();
}
*/
/*
Warning: imap_open() [function.imap-open]: Couldn't open stream {192.168.168.99:993/imap/ssl/novalidate-cert}INBOX in C:\wamp\www\andhikaPortalTes\vesselReport\data\imapx\src\imapxPHP.php on line 65
Cannot connect to Server: TLS/SSL failure for 192.168.168.99: Unexpected SSPI or certificate error 8009030f - report this*/

