
<?php
        // Connects to the XE service (i.e. database) on the "localhost" machine     

        $db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))" ;

        $conn = oci_connect('SYSTEM', '148635Stark', $db);
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            echo "WARNING:ORACLE DB NOT AVAILABLE!";
        }
        else{
            echo" <pre class='text-warning'> ORACLE DB STATUS : Connection Established! </pre> ";
        }

?>
