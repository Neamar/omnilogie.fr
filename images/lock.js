var locker;

function initLock()
{
	locker = setInterval("getLock()",60000);
	getLock();
}

function getLock()
{
	var xhr;
	try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
	catch (e)
	{
		try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
		catch (e2)
		{
			try {  xhr = new XMLHttpRequest();     }
			catch (e3) {  xhr = false;   }
		}
	}

	xhr.onreadystatechange  = function()
	{
		if(xhr.readyState  == 4)
		{
			if(xhr.responseText!='OK')
			{
				alert(xhr.responseText + "\nVous ne pourrez pas enregistrer vos modifications. Rechargez la page dans quelques minutes");
				document.getElementById('save_change').disabled='disabled';
				document.getElementById('save_change').value="Enregistrement désactivé, conflit détecté.";
				clearInterval(locker);
			}
		}
	};

	xhr.open("GET", "/membres/Lock/" + document.location.pathname.replace(new RegExp('/(membres|admin)/(Edit|Ref)/','i'),''),true);
	xhr.send(null);
}

inits.push(initLock);