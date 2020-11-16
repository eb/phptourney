function setClass( id, class_name )
{
	document.getElementById( id ).className = class_name;
}

function unsetClass( id )
{
	document.getElementById( id ).className = "";
}

function newWindow( url )
{
	window.open( url, '_blank', 'location=no,menubar=no,resizable=yes,status=no' );
}