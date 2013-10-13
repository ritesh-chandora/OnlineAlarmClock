$(document).ready(function(){
var AmPm = 0;
var setAlarmFlag = 0;
var snoozeFlag =0;
var alarmHour=0;
var alarmMinute=0;
var alarmAmPm=0;
var snoozeTime =10;
var myAudio = document.getElementsByTagName('audio')[0];
var soundSelct = false;
	// var is=0;
	// var im = 59;
	// var ih =23;
$('#time').css('font-size',$(window).width()+'%');
$('#timeAmPm').css('font-size',$(window).width()/4+'%');
$('.dropdown-toggle').dropdown();
	
	
window.onresize = function(event) {
	$('#time').css('font-size',$(window).width()+'%');
	$('#timeAmPm').css('font-size',$(window).width()/4+'%');
}

//Timer Function
function startTime() {	
	// if(is>59) { is=0; im++;}
	// if(im>59) {im=0; ih++;}
	// if(ih>23) {ih=0;}
	// var today= new Date('Mon Oct 14 2013 '+ih+':'+im+':'+(is++)+' GMT+0530 (India Standard Time)');
	
	var today=new Date();
	
	var h=today.getHours();
	var m=today.getMinutes();
	var s=today.getSeconds();
	if(h>12) {
		AmPm = 1;
		h-=12;
		$('#timeAmPm').text('PM');
	}
	else{
		$('#timeAmPm').text('AM');
	}
	h=(h<10)?"0"+ h:h;
	m=(m<10)?"0"+ m:m;
	s=(s<10)?"0"+ s:s;
	$('#time').text(h+":"+m+":"+s);	
	if(setAlarmFlag && parseInt(h)==parseInt(alarmHour) && parseInt(m) == parseInt(alarmMinute) && parseInt(AmPm)==parseInt(alarmAmPm)){
		StartAlarm();
	}
	t=setTimeout(function(){startTime()},500);
}

function StartAlarm() {
	setAlarmFlag=0;
	$('#setAlarm').hide();
	$('#snoozeAlarm').show();
	$('#snoozeAlarm').removeAttr('disabled');
	if(soundSelct){
		myAudio = document.getElementsByTagName('audio')[1];
	}
	else{
		myAudio = document.getElementsByTagName('audio')[0];
	}
		if (typeof myAudio.loop == 'boolean'){
		myAudio.loop = true;
		}
		else{
			myAudio.addEventListener('ended', function() {
					this.currentTime = 0;
					this.play();
			}, false);
		}		
		myAudio.play();
	
} //End of document.load

//Alarm Set/Cancel/Snooze Functions
$('#setAlarm').click(function(){
	if(setAlarmFlag) return;
	setAlarmFlag = 1;
	alarmHour = parseInt($('#alarmHour').val());
	alarmMinute = parseInt($('#alarmMinute').val());
	alarmAmPm = parseInt($('#alarmAmPm').val());
	dialog(diff(((alarmAmPm)?alarmHour+12:alarmHour),alarmMinute)+" hours left for Alarm.");
	$('#setAlarm').attr('disabled','disabled');
	$('#alarmTimeHolder').show(2000);
	$('#alarmtimeGUI').text(alarmHour+':'+((alarmMinute<10)?"0"+ alarmMinute:alarmMinute)+' '+(alarmAmPm?'PM':'AM'));
});

$('#cancelAlarm').click(function(){
	setAlarmFlag = 0;
	snoozeFlag =0;
	myAudio.pause(); 
	$('#setAlarm').removeAttr('disabled').show();;
	$('#snoozeAlarm').hide();
	$('#alarmTimeHolder').hide(3000);
});

$('#snoozeAlarm').click(function(){	
	if(snoozeFlag) return;
	snoozeFlag =1;
	myAudio.pause(); 
	$('#snoozeAlarm').attr('disabled','disabled');
	setAlarmFlag = 1;
	if((parseInt(alarmMinute)+parseInt(snoozeTime))>60){
		alarmMinute = parseInt(alarmMinute)+parseInt(snoozeTime)-60;
		alarmHour = parseInt(alarmHour)+1;
		if(alarmAmPm==0){
			if(parseInt(alarmHour)==12){
				alarmAmPm=1;
			}
		}
		else{
			if(parseInt(alarmHour)==12){
				alarmAmPm=0;
				alarmHour=0;
			}
		}		
	}
	else{
		alarmMinute = parseInt(alarmMinute) + parseInt(snoozeTime);
	}
	$('#alarmtimeGUI').text(alarmHour+':'+((alarmMinute<10)?"0"+ alarmMinute:alarmMinute)+' '+(alarmAmPm?'PM':'AM'));
	dialog("Alarm is snoozed till "+alarmHour+':'+((alarmMinute<10)?"0"+ alarmMinute:alarmMinute)+' '+(alarmAmPm?'PM':'AM')+" hour");
});

//Advance Alarm Options 
$('.advanceOptionLable').click(function(){
	$('.advanceOptionHolder').toggle("slow");
});

$('#playSelectedSound').click(function(){ 
	document.getElementById('sound').innerHTML = '<audio> <source src=\"audio\\'+$('#alarmSoundSelect').val()+'.ogg\" type="audio/ogg">  <source src=\"audio\\'+$('#alarmSoundSelect').val()+'.mp3\" type="audio/mpeg"> Your browser does not support the audio element.	</audio>';
	var playAudio = document.getElementsByTagName('audio')[1];
	playAudio.play();
});

$('#alarmAdvncOk').click(function(){
	$('.advanceOptionHolder').toggle("slow");
	snoozeTime = ((parseInt($('#snoozeTime').val())> 1) && (parseInt($('#snoozeTime').val())< 60))?(parseInt($('#snoozeTime').val())):10;
	document.getElementById('sound').innerHTML = '<audio> <source src=\"audio\\'+$('#alarmSoundSelect').val()+'.ogg\" type="audio/ogg">  <source src=\"audio\\'+$('#alarmSoundSelect').val()+'.mp3\" type="audio/mpeg"> Your browser does not support the audio element.	</audio>';
	soundSelct = true;
	$('#cancelAlarm').trigger('click');
});

$('#alarmAdvncCancle').click(function(){
	$('.advanceOptionHolder').toggle("slow");
});

//Start The Timer
startTime();
});

function dialog(message,type,speed,id){
	if (!type) type = "success";
	if(type=="error")
		 var dialog = $("<div style='position:absolute; top: 10px; height: 20;width: 100%;color: white;background-color: rgb(204, 0, 0);padding: 5px;font-size: 21;font-weight: bold;'></div>");// .attr({'class':"alert alert-" + type + " topDialog"}).css({'opacity':'0', 'background-color':'#CC0000' , 'color': 'white'});
	else
		var dialog = $("<div style='position:absolute; top: 10px; height: 20;width: 100%;color: white;background-color: rgb(28, 163, 2);padding: 5px;font-size: 21;font-weight: bold;'></div>");// .attr({'class':"alert alert-" + type + " topDialog"}).css({'opacity':'0', 'background-color':'#CC0000' , 'color': 'white'});

	dialog.html('<center>'+message+'</center>');
	$("body").append(dialog);
	if(!speed){
		dialog.animate({opacity: 1, top: 20}, 300).delay(3000).animate({opacity: 0, top: 40}, 300).queue(function(){ $(this).remove();
		});
	}
	else {
		dialog.animate({opacity: 1, top: 20}, 300).delay(speed).animate({opacity: 0, top: 40}, 300).queue(function(){$(this).remove();
		});
	} 
}

function diff(ah,am) {
    var startDate = new Date();
    var endDate = new Date();
	endDate.setHours(ah,am);
    var diff = endDate.getTime() - startDate.getTime();
    var hours = Math.floor(diff / 1000 / 60 / 60);
    diff -= hours * 1000 * 60 * 60;
    var minutes = Math.floor(diff / 1000 / 60);
    return ((hours <= 9 && hours>=0) ? "0" : "") + (hours<0?hours+24:hours) + ":" + (minutes <= 9 ? "0" : "") + minutes;
}