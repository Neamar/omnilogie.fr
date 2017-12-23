window.onload=function()
{
	for(var i = 0;i < inits.length;i++)
		inits[i]();
}

function bannerLink()
{
	//Mettre un lien cliquable sur toutes les bannières
	var Images = document.getElementsByTagName('img');
	for(i=0;i<Images.length;i++)
	{
		if(Images[i].src.replace('http://'+document.location.host,'').substr(0,15) == '/images/Banner/')
		{
			Images[i].onclick=function(){document.location='/O/' + this.alt.replace(/ /g,'_')};
			Images[i].style.cursor = 'pointer';
		}
	}
}

inits.push(bannerLink);