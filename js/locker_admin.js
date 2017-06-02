var checkboxarray = [];

function LetsDoThis() {
for (var i = 1; i < 6; i++) {
document.getElementById(i).onclick = function() {
if (this.checked) {
  checkboxarray[this.id] = 'somebody';
} else {
 checkboxarray[this.id] = 'nobody'; 
}
};
}
}

window.onbeforeunload = function() {
$.post('locker_admin_process.php', {checkboxarray: checkboxarray})
  .done(function( data ) {
 alert( data );
});
};

document.getElementById('save').onclick = function() {
 $.post('locker_admin_process.php', {checkboxarray: checkboxarray})
  .done(function( data ) {
 alert( data );
});
};



window.onload = function () {
LetsDoThis();
};

