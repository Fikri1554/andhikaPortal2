// JavaScript Document

function frameJquery()
{
	$("#frameAging").load( function() {
		parent.$('#loaderImg').css('visibility','hidden');
		
		$(function () {
			$("html,body").scroll(function () {
				if ($("html,body").scrollTop() > 10) {
					alert('if');
				} else {
					alert('else');
				}
			});
		});
	});
}

function setup()
{
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var fromDate = new DateMask("dd/MM/yyyy", "fromDate");
	fromDate.validationMessage = errorMessage;
}

function blinker() {
    $('#idErrorMsg').fadeOut(250);
	$('#idErrorMsg img').fadeOut(250);
		
    $('#idErrorMsg').fadeIn(500);
	$('#idErrorMsg img').fadeIn(500);
}
setInterval(blinker, 1500);

function printAging(jenis)
{
	$('#idErrorMsg').css("visibility","hidden");
	
	var company = $('#company').val();
	var companyName = $('#company').find(":selected").text();
	var account = $('input[name=account]:radio:checked').val();
	var fromDate = $('#fromDate').val();
	var endDate = $('#endDate').val();
	var userId = $('#userId').val();
	
	if(account == "pay")
		var tempPreview = "halAgingPreview.php";
	if(account == "rec")
		var tempPreview = "halAgingPreviewRec.php";
	
	if(company == "XXX")
	{
		$('#idErrorMsg').css("visibility","visible");
		$('#idErrorMsg').html('<img src="../picture/exclamation-red.png"/>&nbsp;Company still empty');
		$('#company').focus();
		
		return false;
	}
	if(fromDate.replace(/ /g,"") == "")
	{
		$('#idErrorMsg').css("visibility","visible");
		$('#idErrorMsg').html('<img src="../picture/exclamation-red.png"/>&nbsp;Start Date still empty');
		$('#fromDate').focus();
		
		return false;
	}
	if(endDate.replace(/ /g,"") == "")
	{
		$('#idErrorMsg').css("visibility","visible");
		$('#idErrorMsg').html('<img src="../picture/exclamation-red.png"/>&nbsp;End Date still empty');
		$('#endDate').focus();
		
		return false;
	}
	else
	{
		if(jenis == 'preview')
		{
			$.post( "halPostMailInv.php", { aksi:'insertSummary', company:company, fromDate:fromDate, endDate:endDate, userId:userId, account:account }, 
			function( data )
			{
				if(data == 1 || data == "")
				{
					$('#loaderImg').css('visibility','visible');
					
					$('#divFrame').css('visibility','visible');
					$('#divFrame').css('border','1px solid #CCC');
					$('#frameAging').attr('src','templates/'+tempPreview+'?company='+company+'&fromDate='+fromDate+'&endDate='+endDate+'&userId='+userId);
				}
				else
				{
					alert('Something Wrong, please call your System Administrator');
				}
			});
			
		}
		if(jenis == 'print')
		{
			$('#formPrintAging').attr('action', 'halPrint.php?aksi=printAging&userId='+userId+'&companyName='+companyName+'&fromDate='+fromDate+'&account='+account);
			formPrintAging.submit();
		}
	}
}