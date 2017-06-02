var idVal = '';
var LastidVal = '';
var idValID = '';
var thisattr = '';
var check = 0;
var idValUser = '';
var numVal = '';
var assignlocker = document.getElementById('assignlocker');
var returnlocker = document.getElementById('returnlocker');


function isselected() {
	if(this.style.border) {

	this.style.border = 'solid';
	}
}

//Each locker is a list item. Detect if the user has selected one. Grab its id and
//toggle the visual effect via border

$('li').click(function() {
numVal = $(this).text();
numVal = numVal.trim();

if(!idVal) {
  this.style.border = 'solid gray';
  idVal = $(this).attr('id');
  LastidVal = idVal;
} else {
  this.style.border = 'solid gray';
  idValId = '#' + idVal;
  $(idValId).closest("li").css({"border": 0});
        LastidVal = idVal;
 idVal = $(this).attr('id');
  if (LastidVal == idVal) {
        idVal = '';
} else {
  idVal = $(this).attr('id');
  LastidVal = idVal;
}
}
});


assignlocker.onclick = function() {
idValUser = document.getElementById("user").value;
whichscript = document.getElementById("assignscript").value;

  if(!idValUser) {
  alert("Please enter a user identity");
    return false;
}

if (idVal >= 500) {
  alert('Please select an unused locker');
  return false;
}
  if(!idVal || !idValUser) {
   alert("Please select a locker");
  return false;
  } else {
switch (document.title) {
        case "Red Lockers LL1":
        whichpage = "combo_locker_ll1.php";
        break;
case "Gray Lockers L2":
        whichpage = "combo_locker_l2.php";
        break;
case "Key Lockers LL1":
        whichpage = "";

}

 $.post(whichscript, {lockerID: idVal, userID: idValUser, numVal: numVal})
  .done(function( data ) {
idVal = 500;
document.write ('<html><body bgcolor="yellow"><p><br></p><br><p><br></p><p><br></p><br><p><br></p></p><p></p><p><p></p><p></p>' + data + '<BR><a href="' + whichpage + '"> Go Back</a></p></body></html>');

});
return true;
}
};

returnlocker.onclick = function() {
switch (document.title) {
	case "Red Lockers LL1":
	whichdb = "combo_lockers_ll1";
	break;
	case "Key Lockers LL1":
	whichdb = "key_lockers_ll1";
	break;
	case "Gray Lockers L2":
	whichdb = "combo_lockers_l2";
	break;
}
if(!idVal) {
	alert("Please select a locker");
	return false;
}

idValUser = document.getElementById("user").value;
 if(!idValUser) {
  alert("Please enter a user identity");
    return false;
}
refundAmount = document.getElementById("refund").value;
//if(!refundAmount) {
// var confirmNoRefund =  confirm("No refund will be recorded for this return if you click OK.\n  Click cancel to enter refund information");
// if (confirmNoRefund == false) {
//	return false;
//} 
//}
  $.post('return_locker.php', {whichdb: whichdb, lockerID: idVal, refund: refundAmount,  userID: idValUser, numVal: numVal})
  .done(function( data ) { 
  alert( data );
});
setTimeout(function(){// wait for 5 secs(2)
           location.reload(true); // then reload the page.(3)
      }, 4000);

return true;
};

