// JavaScript Document

function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}

function sisaLimit(noteLength, countField, limitSet)
{
	var sisa = limitSet - noteLength;
	$('#'+countField+'').val(sisa);
}