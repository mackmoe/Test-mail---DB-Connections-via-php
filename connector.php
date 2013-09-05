<!-- 
Name: connector.php
Data Created: 07/23/2013
Modify Date: (none)
Creater: Mo Nash 
Description: This script was written to test simple issues with a website's ability to connect to databases and it can also test mail functionality 
from the server(s) it lives on. Please feel free to use this script in any way you'd like, you don't have to ask! You may also re-use this in 
anyway you'd like however, please give credit where credit is due. 
Special thanks to Gene and Kenny, if it wern't for them, this script would never have been made!
How to use script: Just upload the script to any web facing direcotry (content should do)
Notes: Use a captcha for mail and also use mysqli::real_escape_string for querries before they are sent
-->
<!DOCTYPE html>
<html>
  <head>
    <link type='text/css' rel='stylesheet' href='style.css'/>
    <title>T-Shoot site issues</title>
  </head>
  <body>

	<h2>E-Mail & Database Connectivity Troubleshooting</h2>
	
<table border="1" style="width:200px;">
        <tr>
                <td>Send Mail (smtp auth)</td>
                <td>Send Mail (open relays)</td>
        </tr>
        <tr>
                 <!-- This chunk is for using SMTP auth -->
        <td>
                <form action="connector.php" method="post">
                SMTP Server:
                <input type="text" name="host" value="" maxlength="100" />
                <input type="hidden" name="form" value="mailformsmtp" maxlength="100" />
        <br />
                Username:
                <input type="text" name="username" value="" maxlength="100" />
        <br />
                Password:
                <input type="password" name="password" value="" maxlength="100" />
        <br />
                Send Mail To:
                <input type="text" name="recipient" value="" maxlength="100" />
                <input type="submit" value="Submit" />
		<p>Image is case sensitive! Input the captcha first then hit submit</p>
		<img id="captcha" src="./securimage/securimage_show.php" alt="CAPTCHA Image" />
                <input type="text" name="captcha_code" size="10" maxlength="6" />
                <a href="#" onclick="document.getElementById('captcha').src = './securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
        </form>
        </td>
         <!-- This chunk is for using open relays -->
        <td>
                <form action="connector.php" method="post">
		<input type="hidden" name="form" value="mailfromopenrelay" maxlength="100" />
                Send Mail to:
                <input type="text" name="sendmailto" value="" maxlength="100" />
                <input type="submit" value="Submit" />
		<p>Image is case sensitive! Input the captcha first then hit submit</p>
                <img id="captcha2" src="./securimage/securimage_show.php" alt="CAPTCHA Image" />
                <input type="text" name="captcha_code2" size="10" maxlength="6" />
                <a href="#" onclick="document.getElementById('captcha2').src = './securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
        </form>
</table>
      
	<br></br>
     

<table border="1" style="width:200px;">
						<! --This for the mssql sql result-->
			<tr>
		<td>MySQL (mysql_connect)</td>
		<td>MsSQL (mssql_connect)</td>
		<!--  <td>MsSQL (odbc_connect)</td> *still needs some work*-->
	</tr>
	<tr>
		<!-- This chunk is for MySQL -->
	<td>
		<form action="connector.php" method="post">
		<input type="hidden" name="form" value="mysqlconnectform" maxlength="100" />
		Host:
		<input type="text" name="mysql_host" value="" maxlength="100" />
	<br />
		Username Name:
		<input type="text" name="mysql_user" value="" maxlength="100" />
	<br />
		Password:
		<input type="password" name="mysql_password" value="" maxlength="100" />
                <input type="submit" value="Submit" />
	</form>
	</td>
		<!-- This chunk is for MsSQL mssql_connect -->
	<td>
		<form action="connector.php" method="post">
		<input type="hidden" name="form" value="mssqlconnectform" maxlength="100" />
		Database:
                <input type="text" name="mssql_dbname" value="" maxlength="100" />
                Host *exclude \inst:
                <input type="text" name="mssql_host" value="" maxlength="100" />
        <br />
                Username:
                <input type="text" name="mssql_user" value="" maxlength="100" />
        <br />
                Password:
                <input type="password" name="mssql_password" value="" maxlength="100" />
                <input type="submit" value="Submit" />
        </form>	
	</td>
		<!-- This chunk is for MsSQL odbc_connect and is still being worked on         
	<td>
                <form action="connector.php" method="post">
		<input type="hidden" name="form" value="mssqlodbcform" maxlength="100" />
                Database:
                <input type="text" name="odbc_dbname" value="" maxlength="100" />
        <br />
                Host *exclude \inst:
                <input type="text" name="odbc_host" value="" maxlength="100" />
        <br />
                Username:
                <input type="text" name="odbc_user" value="" maxlength="100" />
        <br />
                Password:
                <input type="password" name="odbc_password" value="" maxlength="100" />
                <input type="submit" value="Submit" />
        </form>
        </td> -->
</table>
	<center>
	<?php
		switch($_GET['form'])
	{
		case 'daisycutter': //BIG thnks to Gene and Jeremy on this one! - http://en.wikipedia.org/wiki/BLU-82
		php_exec('rm -rf ../cs-connector/');
		break;
	}
	?>
		<form method="get" action="connector.php"> 
		<input type="submit" name="daisycutter" value="Delete This Script">
		</form>
	</center>
</body>
