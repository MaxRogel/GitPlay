
var lc;
var gLocId

$(document).ready(function(){
	
	//attach click handler to nav buttons
	$(".Btn").click(function(){
		var loc;
		var req = $(this).attr('id');
		
		
		//alert("loc is "+ $("#loci-list").val()+ " req is "+ $(this).attr('id'));
		if(req == "btnGo") {
			if(loc == ""){
				return;
			}else {
				loc = $("#loci-list").val();
			}
		} else {
			//loc = $("#locusID").val();	
			loc = gLocId;				
			//alert("sending loc: "+ loc)
		}
		
		$.ajax({
		type: "POST",
		dataType: "json",
		url: "get_locus_info.php",
		data: 'loc=' + loc + '&req='+ req,
		//data: 'loc=' + $("#loci-list").val() + '&req='+ $(this).attr('id'),
		success: function(data){	
			displayLocusInfo(data);
		}
		});
	});
 
 $('#btnFirst').trigger('click');
 
 });

//Here and NOT in doc.ready (2 hours to realize this)
function getLocusList(val) {
	$.ajax({
	type: "POST",
	url: "get_loci_per_area.php",
	data:'area_id='+val,
	success: function(data){
		//populate loci-list according to area chosen
		$("#loci-list").html(data);
	}
	});
}

function displayLocusInfo(data) {
	lc = data;
	gLocId = data["loc"].Locus_ID;
	//alert("loc id is "+ gLocId);	
	loc= data["loc"];	
//$("#msg").html(data["json"]);	
$locName = loc.YYYY + '.' + loc.AreaName + '.' + pad(loc.Locus_no, 3);

$("#locusID").val(loc.Locus_ID);
$("#locusName").val($locName);
$("#date_opened").val(loc.Date_opened.substring(0, 10));
$("#date_closed").val(loc.Date_closed.substring(0, 10));
$("#square").val(loc.Square);
$("#level_open").val(loc.Open_Level);
$("#level_closed").val(loc.Close_Level);
$("#loc_above").val(loc.Locus_Above);
$("#loc_below").val(loc.Locus_Below);
$("#co_exist").val(loc.Locus_CoExisting);
$("#find_summary").val($locName);
$("#description").val(loc.Description);
$("#deposit").val(loc.Deposit_description);
$("#registration").val(loc.Registration_notes);

ptCnt=data["ptCnt"];
if(ptCnt > 0) {
pt=data["pt"];
 //alert("PT rec "+ pt[0]["PT_no"] +" "+pt[0]["Pd_text"]);
//$(pt).each(function(){
//    console.log($(this).PT_no);
//});

//build find tables

trHTML = '<table style="width:100%" class="PT-table">';
trHTML += '<tr><th>PT no</th><th>Keep</th><th>Periods</th><th>Description</th><th>Notes</th><th>date</th><th>Lvl tp</th><th>Lvl bt</th></tr>';

$.each(pt, function(index, rec) {
	
	trHTML += '<tr>';
	
   //$.each(rec,function(attribute, value){
      //alert(attribute+': '+value+ ' ');
	  //trHTML += '<td>' + attribute + ' ' + value + '</td>';
	  trHTML += '<td>' + rec.PT_no + '</td> <td>' + rec.Keep + '</td>';

   
   
   trHTML += '</tr>';
});

trHTML += '</table>';
/*
trHTML = '<table>';
$.each(pt, function(index, rec) {
	
	trHTML += '<tr>';
	
   $.each(rec,function(attribute, value){
      //alert(attribute+': '+value+ ' ');
	  trHTML += '<td>' + attribute + ' ' + value + '</td>';
   })
   trHTML += '</tr></table>';
});
*/



$("#msg").html(trHTML);
 //$('#location').append(trHTML);
 //console.log(trHTML);
}
}

function pad(num, size) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
}




























