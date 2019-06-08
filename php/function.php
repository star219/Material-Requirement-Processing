<?php

    $db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))" ;

    $conn = oci_connect('SYSTEM', '148635Stark', $db);
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        echo "WARNING:ORACLE DB NOT AVAILABLE!";
    }

    #################################  INSERTION INTO THE PRODEMAND TABLE  ####################################

    $dedate= $_POST['dedatee'];
    $pname = $_POST['pnamee'];
    $pnum = $_POST['pnumm'];
    $rnum = $_POST['rnumm'];
    $dfrmt ="YYYY/MM/DD";
    if (empty($dedate) or empty( $pname) or empty($pnum) or empty($rnum)) {
        echo "Alert! Please enter all the values";
    }
    
    else{
    $insrt = oci_parse($conn,'INSERT INTO prodemand (DEDATE, PNAME, PRODUCTNO, PRODUTREQ) VALUES (TO_DATE(:dedate,:dfrmt),:pname,:pnum,:rnum)');
    oci_bind_by_name($insrt, ':dedate', $dedate);
    oci_bind_by_name($insrt, ':dfrmt', $dfrmt);
    oci_bind_by_name($insrt, ':pname', $pname);
    oci_bind_by_name($insrt, ':pnum', $pnum);
    oci_bind_by_name($insrt, ':rnum', $rnum);
    oci_execute($insrt);

    #################################### INSERTION INTO THE DEMAND TABLE  #####################################
 
    $insrt2 = oci_parse($conn, 'INSERT INTO demand (DEDATE, YARNDMD, FABRICDMND, DYEDMD, DECORATIVESDMD,PRODUCTNO) VALUES (TO_DATE(:dedate,:dfrmt), ((SELECT produtreq FROM prodemand WHERE dedate = TO_DATE(:dedate,:dfrmt) AND PRODUCTNO  = :pnum)*(SELECT YARNQTY FROM MRP WHERE PRODUCTNO = :pnum)), ((SELECT produtreq FROM prodemand WHERE dedate = TO_DATE(:dedate,:dfrmt) AND PRODUCTNO  = :pnum)*(SELECT FABRICQTY FROM MRP WHERE PRODUCTNO = :pnum)), ((SELECT produtreq FROM prodemand WHERE dedate = TO_DATE(:dedate,:dfrmt) AND PRODUCTNO  = :pnum)*(SELECT DYEQTY FROM MRP WHERE PRODUCTNO = :pnum)),((SELECT produtreq FROM prodemand WHERE dedate = TO_DATE(:dedate,:dfrmt) AND PRODUCTNO  = :pnum)*(SELECT DECORATIVESQTY FROM MRP WHERE PRODUCTNO = :pnum)),:pnum)');
    oci_bind_by_name($insrt2, ':dedate', $dedate);
    oci_bind_by_name($insrt2, ':dfrmt', $dfrmt);
    oci_bind_by_name($insrt2, ':pnum', $pnum);
    oci_execute($insrt2);

    ###########################################################################################################
    $cdate="01/01/2019";
    $cfrmt="DD/MM/YYYY";
    $stid = oci_parse($conn,  'SELECT produtreq FROM prodemand WHERE dedate = TO_DATE(:cdate,:cfrmt)');
    oci_bind_by_name($stid, ':cdate', $cdate);
    oci_bind_by_name($stid, ':cfrmt', $cfrmt);
    oci_execute($stid);
    echo "Order Successful!";
    
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
      foreach ($row as $item) {
          echo( "\r\n");
          $vari= ($item !== null ? htmlentities($item, ENT_QUOTES) : "\r\n");
          echo $vari;
      }
   }
}
    ##############################################################################################################
    
if($_POST['action'] == "submit2"){
    $dedate2= $_POST['dedatee2'];
    if (empty($dedate2)) {
        echo "<h1>Alert: Enter a date</h1>";
    }
    else{
        echo "<h1>$dedate2</h1>";
    }

}

?>