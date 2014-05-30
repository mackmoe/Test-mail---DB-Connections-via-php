<?php session_start(); ?>
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
    <?php
        require_once "Mail.php";
        include_once './securimage/securimage.php';
        $securimage = new Securimage();
        switch ($_POST['form']) {
            // This case is for smtp auth... thank goodness for Gene!!
            case 'mailformsmtp':
                $from = "Cloud Sites Support <support@supportrocks.dev>";
                $to = $_POST['recipient'];
                $subject = "PHP mail test (using SMTP Authentication)";
                $body = "If you are reading this message, sending mail from your site works reliably!";
                $host = $_POST['host'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $headers = array ('From' => $from,
                                  'To' => $to,
                                  'Subject' => $subject);
                $smtp = Mail::factory('smtp',
                                      array ('host' => $host,
                                      'auth' => true,
                                      'username' => $username,
                                      'password' => $password));
                // Checking for humans... which should handle the error so that the form processor doesn't continue
                if ($securimage->check($_POST['captcha_code']) == false) {
                    echo "The security code entered was incorrect.<br /><br />";
                    echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
                    exit;
                }
                $mail = $smtp->send($to, $headers, $body);
                if (PEAR::isError($mail) {
                    echo("<p>" . $mail->getMessage() . "</p>");
                } else {
                    echo("<p>Message successfully sent!</p>");
                }
    	        break;

    		case 'mailfromopenrelay':
                // PHP.net aint great but it got me this far...
                $message = "Sent to you from Cloud Sites Support - via mail relays using the php mail function. It may get there a bit late. If not, good but if so please use smtp_auth in your scripts to send mail reliably.";
                $message = wordwrap($message, 70, "\r\n");
                // Checking for humans... which should handle the error so that the form processor doesn't continue
                if ($securimage->check($_POST['captcha_code2']) == false) {
                    echo "The security code entered was incorrect.<br /><br />";
                    echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
                    exit;
                }
    			mail($_POST["sendmailto"],'using open mail relays', $message, $headders);
    			$relaymsg = 'Message sent ...now we wait';
    			echo $relaymsg;
    			break;

            case 'mysqlconnectform':
                // I used used some of the following code from php.net for this one... and MOST from Kenny's test script here
                if ($_POST['mysql_host'] == '') die('Hostname Required');
                if ($_POST['mysql_user'] == '') die('Username Required');
                if ($_POST['mysql_password'] == '') die('Password Required');
                $link = mysql_connect($_POST['mysql_host'],$_POST['mysql_user'],$_POST['mysql_password'],$_POST['mysql_dbanme']) or die ('Could not connect: ' . mysql_error());
                $results = mysql_query('show databases;');
                $mysqlresponse =  "Successfully connected to your database. The list of accessable databases shown below: </br><ul>";
                mysql_close($link);
                break;

            case 'mssqlconnectform':
                // Found the proper connection string w/inst on the wiki comment posted by Daniel Kinkade. The rest is from lady rainicorn and BMO!
                if ($_POST['mssql_dbname'] == '') die('Database Name Required');
                if ($_POST['mssql_host'] == '') die('Hostname Required');
                if ($_POST['mssql_user'] == '') die('Username Required');
                if ($_POST['mssql_password'] == '') die('Password Required');
                // Connect to MSSQL with a slightly modded line for mssql adaptability
                $sql_link = mssql_connect($_POST['mssql_host'], $_POST['mssql_user'], $_POST['mssql_password']) or die ('Could not connect: ' . mssql_get_last_message());
                $mssqlresponse = "Successfully connected to your database. Showing results for the executed sql query: </br><ul>";
                if (!$sql_link || !mssql_select_db($_POST['mssql_dbname'], $sql_link)) {
                    die('Unable to connect or select database!');
                }
                // Do a simple query, select the version of
                // MSSQL and print it.
                $version = mssql_query('SELECT @@VERSION');
                $row = mssql_fetch_array($version);
                // Clean up
                mssql_free_result($version);
                break;
        }
    ?>
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
        </td>
      </tr>
    </table>
    
    <br></br>

    <table border="1" style="width:200px;">
    <?php 
        echo $mysqlresponse, $db['Database'];
        if (isset ($results)) {
            while ($db = mysql_fetch_assoc($results)) {
                print '<li>'.$db['Database'].'</li>';
            }
            print '</ul>';
        }
        // This for the mssql sql result
        echo $mssqlresponse . $row[0]; 
    ?>
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
      </tr>
    </table>
      <center>
        <?php //http://en.wikipedia.org/wiki/BLU-82 - Thanks again Gene and Jeremy for the idea! :-)
            echo 
                "<form action='' method='post'> 
                <input type='submit' name='daisycutter' value='Delete this script!' /> 
                </form>"; 
            if(isset($_POST['daisycutter'])) { 
                echo "It's gone... refresh the page" . shell_exec('rm -rf ../cs-connector'); 
            } 
        ?>	
      </center>
  </body>
</html>