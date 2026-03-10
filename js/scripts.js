function ProvinceChanged(cmb, invest, lng){	
	var selected = cmb.options[cmb.selectedIndex].value;	
	if(invest)
		x_AJAXGetInvestmentsDistricts(selected, lng, AJAXGetDistrictsCallback);
	else
		x_AJAXGetDistricts(selected, lng, AJAXGetDistrictsCallback);
}

function AJAXGetDistrictsCallback(result){
	var cmb = document.getElementById("cmbDistrict");
	var len = cmb.length;
	for(i = 0; i < len; i++){
		cmb.remove(0);
	}
	AJAXGetLocationsCallback(new Array());
	for(i in result){
		insertOption(cmb, result[i], result[i]);	
	}
	if(cmb.length == 0) insertOption(cmb, "wybierz powiat", "-1");
}

function DistrictChanged(cmb, invest, lng){
	var list = new Array();
	for (i=0; i < cmb.length; i++){
		var op = cmb.options[i]; 
		if(op.selected && op.value != "-1")	list.push(op.value);
	}
	if(list.length > 0){
		if(invest)
			x_AJAXGetInvestmentsLocations(list,lng, AJAXGetLocationsCallback);
		else
			x_AJAXGetLocations(list, lng, AJAXGetLocationsCallback);
	}
}

function AJAXGetLocationsCallback(result){
	var cmb = document.getElementById("cmbLocation");
	var len = cmb.length;
	for(i = 0; i < len; i++){
		cmb.remove(0);
	}
	AJAXGetQuartersCallback(new Array());
	for(i in result){
		insertOption(cmb, result[i], result[i]);	
	}
	if(cmb.length == 0) insertOption(cmb, "wybierz miasto", "-1");
}

function LocationChanged(cmb, invest, lng){
	var list = new Array();
	for (i=0; i < cmb.length; i++){
		var op = cmb.options[i]; 
		if(op.selected && op.value != "-1")	list.push(op.value);
	}
	if(list.length > 0){
		if(invest)
			x_AJAXGetInvestmentsQuarters(list,lng, AJAXGetQuartersCallback);
		else
			x_AJAXGetQuarters(list, lng, AJAXGetQuartersCallback);
	}
}

function AJAXGetQuartersCallback(result){
	var cmb = document.getElementById("cmbQuarter");
	var len = cmb.length;
	for(i = 0; i < len; i++){
		cmb.remove(0);
	}
	for(i in result){
		insertOption(cmb, result[i], result[i]);	
	}
	if(cmb.length == 0) insertOption(cmb, "wybierz dzielnicę", "-1");
}

function insertOption(cmb, text, value){
	var op = document.createElement('option');
	op.text = text;
	op.value = value;
	try{
		cmb.add(op, null); // standards compliant
	}catch(ex){
		cmb.add(op); // IE only
	}	
}

function ObjectChange(cmb){
	var selected = cmb.options[cmb.selectedIndex].value;	
	var dvF = document.getElementById("dvFlatType");
	var dvH = document.getElementById("dvHouseType");
	var dvA = document.getElementById("dvFieldDestiny");
	var dvL = document.getElementById("dvLocalDestiny");
	dvA.style.display = "none";
	dvL.style.display = "none";
	dvF.style.display = "none";
	dvH.style.display = "none";
	if(selected == "Mieszkanie") dvF.style.display = "";
	if(selected == "Dzialka") dvA.style.display = "";
	if(selected == "Dom") dvH.style.display = "";
	if(selected == "Lokal") dvL.style.display = "";
}

function DoPostBack(action, hidId, value){
	var frm = document.getElementById('frmMain');
	var hidA = document.getElementById('hidAction');
	hidA.value = action;
	if(hidId != ''){
		var hidP = document.getElementById(hidId);
		hidP.value = value;
	}
	frm.submit();
}

function ShowPhoto(id, mod){
	var win = window.open('index' + mod + '.php?action=photo&id='+id, 'Photo', 'location=0,status=0,scrollbars=0,width=640,height=500');	
}

function ShowSWF(id, mod){
	var win = window.open('index' + mod + '.php?action=swf&id='+id, 'Photo', 'location=0,status=0,scrollbars=0,width=555,height=500');	
}

function Chsize(){
	var fotoID = document.getElementById('fotoID');
	tx = fotoID.width + 40;
	ty = fotoID.height + 60;
	if(tx > 100){ 
		window.resizeTo(tx, ty); 
	}else{
		setTimeout("Chsize()", 100);
	}
}

function LoadMap(lat, lng) {	
  if (GBrowserIsCompatible()) {
    map = new GMap2(document.getElementById("mapa"));
    map.setCenter(new GLatLng(lng, lat), 13);
    var mapControl = new GMapTypeControl();
    map.addControl(mapControl);
    map.addControl(new GLargeMapControl());	
    var point = new GLatLng(lng, lat);
    var mk = new GMarker(point);
    mk.id = 1;
	map.addOverlay(mk);
  }
}

function SynchronizeDB(){
	x_AJAXSynchronizeDB(SynchronizeDBCallback);
}

function SynchronizeOffersCount(){
    x_AJAXSynchronizeOffersCount(SynchronizeOffersCountCallBack);
}

function SynchronizeDBCallback(result){	}
function SynchronizeOffersCountCallBack(result){}

