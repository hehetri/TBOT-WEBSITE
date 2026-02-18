function jsReg1test() {
	var frm = document.getElementById('frm_general');
	if(frm.tos_agree.checked) {
		if(frm.pass1.value=='' || frm.pass1.value.length < 6) {
			jsRegDispMsg('Panjang password tidak valid','general_error_3');
			return(false);
		}
		if(frm.pass1.value==frm.pass2.value) {
			frm.px.value = calcMD5(frm.pass1.value);
			var p = calcMD5(frm.px.value);
			frm.pass1.value = p.substr(0,12);
			p = calcMD5(frm.pass1.value);
			frm.pass2.value = p.substr(0,12);
			return(true);
		} else {
			jsRegDispMsg('Retype Password tidak sama','general_error_4');
			return(false);
		}
	} else {
		alert('Pengguna harus setuju dengan EULA.');
		return(false);
	}
}

function jsRegGetUserID() {
	var xhrArgs = {
		url: "/registration/checkid",
		form: "frm_general",
		handleAs: "text",
		preventCache: true,
		load: function(data) {
			//Replace newlines with nice HTML tags.
			if(data=='0') {
				// sudah terpakai
				jsRegDispMsg('ID sudah terpakai...','general_error');
			} else if(data=='2') {
				// belum terpakai
				jsRegDispMsg('ID kosong atau salah format...','general_error');
			} else {
				jsRegDispMsg('ID Ok','general_error');
			}
		},
		error: function(error) {
			jsRegDispMsg('An unexpected error occurred: '+ error,'general_error');
		}
	}
	var deferred = dojo.xhrPost(xhrArgs);
}

function jsRegGetUserNick() {
	var xhrArgs = {
		url: "/registration/checknick",
		form: "frm_general",
		handleAs: "text",
		preventCache: true,
		load: function(data) {
			//Replace newlines with nice HTML tags.
			if(data=='0') {
				// sudah terpakai
				jsRegDispMsg('Nick sudah terpakai...','general_error_2');
			} else if(data=='2') {
				// belum terpakai
				jsRegDispMsg('Nick kosong atau salah format...','general_error_2');
			} else {
				jsRegDispMsg('Nick Ok','general_error_2');
			}
		},
		error: function(error) {
			jsRegDispMsg('An unexpected error occurred: '+ error,'general_error_2');
		}
	}
	var deferred = dojo.xhrPost(xhrArgs);
}

function jsUsr1test() {
	var frm = document.getElementById('frm_general');
	if(frm.pass0.value!='') {
		if((frm.pass1.value!='') && (frm.pass1.value==frm.pass2.value)) {
			frm.passx.value = calcMD5(frm.pass0.value);
			var p = calcMD5(frm.pass0.value);
			frm.pass0.value = p.substr(0,12);
			frm.passy.value = calcMD5(frm.pass1.value);
			p = calcMD5(frm.pass1.value);
			frm.pass1.value = p.substr(0,12);
			frm.pass2.value = p.substr(0,12);
			frm.passz.value = calcMD5(frm.passx.value + '-' + frm.passy.value);
			return(true);
		} else {
			jsRegDispMsg('Password baru tidak valid','general_error');
			return(false);
		}
	} else {
		jsRegDispMsg('Password lama tidak valid','general_error');
		return(false);
	}
}

function jsUsr2test() {
	var frm = document.getElementById('frm_general');
	if(frm.pass0.value!='') {
		frm.passx.value = calcMD5(frm.pass0.value);
		var p = calcMD5(frm.pass0.value);
		frm.pass0.value = p.substr(0,12);
		return(true);
	} else {
		jsRegDispMsg('Password tidak boleh kosong','general_error_1');
		return(false);
	}
}

function jsRegDispMsg(msg,elm) {
	var erm=document.getElementById(elm);
	if(erm)	{
		erm.innerHTML=msg;	
		var t=setTimeout("jsRegClearMsg('"+elm+"')",10000);
	}
}

function jsRegClearMsg(elm) {
	var erm=document.getElementById(elm);
	if(erm)	erm.innerHTML='';	
}

function jsRegShowTOS() {
	dijit.byId('dialogTOS').show();
}

function js2test(frm_name) {
	var frm = document.getElementById(frm_name);
	if(frm.user_id.value=='' || frm.user_id.value=='Orange ID'){
		alert('User ID not Valid');
		return(false);
	}
	if(frm.passw.value!='') {
		frm.passx.value = calcMD5(frm.passw.value);
		var p = calcMD5(frm.passx.value);
		frm.passw.value = p.substr(0,12);
		return(true);
	} else {
		alert('Password not Valid');
		return(false);
	}
}

function jsRedirectTo(url) {
	window.location.href=url;
}

function jsOpenWin(url) {
	window.open(url);
	NewWindow.focus();
}

function jsOverToggler(bid,cls,cannotChange) {
	if(cannotChange!=true) dojo.toggleClass(bid, cls);
}

function ligSocial(sId,refr) { 
	var surl = ''; 
	surl = 'https://auth.orangegame.co.id/socialauth.do?id='+sId; 
	surl += (refr==''?'':'&ref='+encodeURIComponent(refr)); 
	jsRedirectTo(surl);
}
