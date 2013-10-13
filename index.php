<?php
if (!defined('__ROOT__')) { define('__ROOT__', dirname(__FILE__)); } //define constant __ROOT__ with root directory.
require_once __ROOT__.'/includes/common.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
	<meta charset="utf-8"> 
	<title>Online Alarm Clock @ Ritesh Apps</title> 
	<link href="./css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="./css/jquery-ui-1.9.1.custom.css" rel="stylesheet" type="text/css" >
	<link href="./css/style.css" rel="stylesheet" type="text/css" >
</head>

<body>
<div id="wrap">
	<div class="navbar">
		<div class="navbar-inner" >	
			<div class="container">		
				<ul class="nav">  
					<li class="dropdown">  
						<a href="#"  class="dropdown-toggle"  data-toggle="dropdown">  Ritesh Apps  <b class="caret"></b>  </a>  
						<ul class="dropdown-menu">  
							<li><a href="#">Find Your Pending Request</a></li>  
							<li><a href="#">Who Join First</a></li>  
							<li><a href="#">Quizzing</a></li>  
						</ul>  
					</li>  
				</ul>  
				<ul class="nav pull-right">
					<li ><a href="javascript:;" style="font-size:17px; color: rgb(11, 0, 134);"> <img src="./img/facebook-connect-button.gif">	</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="container-fluid"> 
	<div class="row-fluid">
		<div class="span3">
		</div>
		<div class="span9">
			<div class="row-fluid">
				<div class="span12">
				&nbsp;	
					<span class="pull-right" id="alarmTimeHolder" style="display:none;"> Alarm Set For : <span id="alarmtimeGUI"> </span></span>
					<span id="dateHolder"> Date : 12/Oct/2013 </span>
				</div>
			</div>
			<br><br><br><br>
				<div class="row-fluid">
				<div class="span1">	</div>
				<div class="span10"><center><span id="time"></span><span id="timeAmPm"></span></center>	</div>
				</div>
			<br><br><br><br>
			
			<div class="row-fluid">
				<div class="span1">	</div>
				<div class="span10" >
				<center>
				<span class="timeContainer">
				HOUR:
				<select id="alarmHour"  class="timeselect"> 
				<option value="0"> 0 </option>
				<option value="1"> 1 </option>
				<option value="2"> 2 </option>
				<option value="3"> 3 </option>
				<option value="4"> 4 </option>
				<option value="5"> 5 </option>
				<option value="6"> 6 </option>
				<option value="7"> 7 </option>
				<option value="8"> 8 </option>
				<option value="9"> 9 </option>
				<option value="10"> 10 </option>
				<option value="11"> 11 </option>
				</select>
				<span>
				<span class="timeContainer">
				MIN:
				<select id="alarmMinute" class="timeselect"> 
				<option value="0"> 0 </option>
				<option value="1"> 1 </option>
				<option value="2"> 2 </option>
				<option value="3"> 3 </option>
				<option value="4"> 4 </option>
				<option value="5"> 5 </option>
				<option value="6"> 6 </option>
				<option value="7"> 7 </option>
				<option value="8"> 8 </option>
				<option value="9"> 9 </option>
				<option value="10"> 10 </option>
				<option value="11"> 11 </option>
				<option value="12"> 12 </option>
				<option value="13"> 13 </option>
				<option value="14"> 14 </option>
				<option value="15"> 15 </option>
				<option value="16"> 16 </option>
				<option value="17"> 17 </option>
				<option value="18"> 18 </option>
				<option value="19"> 19 </option>
				<option value="20"> 20 </option>
				<option value="21"> 21 </option>
				<option value="22"> 22 </option>
				<option value="23"> 23 </option>
				<option value="24"> 24 </option>
				<option value="25"> 25 </option>
				<option value="26"> 26 </option>
				<option value="27"> 27 </option>
				<option value="28"> 28 </option>
				<option value="29"> 29 </option>
				<option value="30"> 30 </option>
				<option value="31"> 31 </option>
				<option value="32"> 32 </option>
				<option value="33"> 33 </option>
				<option value="34"> 34 </option>
				<option value="35"> 35 </option>
				<option value="36"> 36 </option>
				<option value="37"> 37 </option>
				<option value="38"> 38 </option>
				<option value="39"> 39 </option>
				<option value="40"> 40 </option>
				<option value="41"> 41 </option>
				<option value="42"> 42 </option>
				<option value="43"> 43 </option>
				<option value="44"> 44 </option>
				<option value="45"> 45 </option>
				<option value="46"> 46 </option>
				<option value="47"> 47 </option>
				<option value="48"> 48 </option>
				<option value="49"> 49 </option>
				<option value="50"> 50 </option>
				<option value="51"> 51 </option>
				<option value="52"> 52 </option>
				<option value="53"> 53 </option>
				<option value="54"> 54 </option>
				<option value="55"> 55 </option>
				<option value="56"> 56 </option>
				<option value="57"> 57 </option>
				<option value="58"> 58 </option>
				<option value="59"> 59 </option>
				</select>
				</span>
				<span class="timeContainer">
				AM/PM:
				<select id="alarmAmPm"  class="timeselect">
				<option value="0"> AM </option>
				<option value="1"> PM </option>
				</select>
				</span>
				<br>
				
				<span class="btn btn-info setbutton" id="setAlarm"> Set Alarm </span> 
				<span class="btn btn-info setbutton" id="snoozeAlarm" style="display:none;"> Snooze Alarm </span> 
				<span class="btn btn-danger setbutton" id="cancelAlarm"> Cancel Alarm </span> <br>
				
				<span class="advanceOptionLable" title="Click To View Advance option"> Advance Options </span>
				</center>
				<div class="advanceOptionHolder">
					<div class=" row-fluid"> 				
						<span class="span3"> </span> 
						<span class="span3">Snooze Time Interval : </span> 
						<span class="span2"><input type="text" id="snoozeTime" value="10"></span>
						<span class="span1"><i>(Minutes)</i></span>
					
					</div>
					<div class=" row-fluid "> 
					<span class="span3">
						<audio>
							  <source src="audio\horse.ogg" type="audio/ogg">
							  <source src="audio\horse.mp3" type="audio/mpeg">
								Your browser does not support the audio element.
						</audio>	
					</span> 
						<span class="span3"> Select Alarm Sound: </span> 
						<span class="span2">
							<select id="alarmSoundSelect">
								<option value="horse">Horse Sound</option>
								<option value="sci_fci2">sci_fci2</option>
								<option value="sci_fci">sci_fci.mp3</option>
								<option value="fire_detecter">fire_detecter.mp3</option>
							</select>
						</span>
						<span id="playSelectedSound">Play Sound</span>
					</div>
					<div class=" row-fluid "> 
						<span class="span3" id="sound"></span> 
						<span class="span3 btn btn-success" id="alarmAdvncOk"> OK  </span> 
						<span class="span3 btn btn-danger" id="alarmAdvncCancle"> Cancel </span>			
					</div>
					
				</div>
				<!--<a href="javascript: void(0);" onclick="playSound('horse.mp3');">Play Sound</a> -->
				
				
				</div>
			</div>
		</div>
	</div>
	
	</div>
	<div id="push">
	</div>
</div> <!-- end wrap -->

<div id="footer">
      <div class="container">
        <center>Develop By : <a href="https://www.facebook.com/Ritesh.chandora" target="_blank">Ritesh Chandora</a></center>
      </div>
</div> 

<script>

 </script>
<script type="text/javascript">
</script>
<?php
require_once __ROOT__.'/views/Head.php';
?>
 </body>
 </html>
 
