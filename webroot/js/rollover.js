function paCreateRollOvers(){
	if(document.getElementById){
		var paImages = document.getElementsByTagName('IMG');
		var paButtons = document.getElementsByTagName('INPUT');
	}else if(document.all && navigator.userAgent.indexOf('Mac')==-1){
		var paImages = document.all['mouseovers'].all.tags('IMG');
		var paButtons = document.all['mouseovers'].all.tags('INPUT');
	}else return;
	paPreloads = new Object();

	for (var i = 0; i < paImages.length; i++){
		var paImageOffSrc = paImages[i].src + "";
		if(paImageOffSrc.indexOf("_off.") != -1){
			var paImageOnSrc = paImageOffSrc.replace(/_off\./g, "_on.");
			paPreloads['paImageOff_' + i] = new Image();
			paPreloads['paImageOff_' + i].src = paImageOffSrc;
			paPreloads['paImageOn_' + i] = new Image();
			paPreloads['paImageOn_' + i].src = paImageOnSrc;
			/*paPreloads['paImageOn_' + i].onerror = function(){this.src='/images/blank.gif';}*/
			paImages[i].onmouseover = function(){this.src = this.src.replace(/_off\./g, "_on.");}
			paImages[i].onmouseout = function(){this.src = this.src.replace(/_on\./g, "_off.");}
		}
	} 

	for (var i = 0; i < paButtons.length; i++){
		if(paButtons[i].src){
		var paImageOffSrc = paButtons[i].src + "";
		if(paImageOffSrc.indexOf("_off.") != -1){
			var paImageOnSrc = paImageOffSrc.replace(/_off\./g, "_on.");
			paPreloads['paImageOff_' + i] = new Image();
			paPreloads['paImageOff_' + i].src = paImageOffSrc;
			paPreloads['paImageOn_' + i] = new Image();
			paPreloads['paImageOn_' + i].src = paImageOnSrc;
			/*paPreloads['paImageOn_' + i].onerror = function(){this.src='/images/blank.gif';}*/
			paButtons[i].onmouseover = function(){this.src = this.src.replace(/_off\./g, "_on.");}
			paButtons[i].onmouseout = function(){this.src = this.src.replace(/_on\./g, "_off.");}
		}
		}
	} 
}

window.onload = paCreateRollOvers;