<html>
    <head>
    <title>STARK MRP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="js/ajax.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/popper.min.js"></script>
    <script src="s/bootstrap.min.js"></script> 
    <script>
         function PrintDiv() {
            var data=document.getElementById('Printable').innerHTML;
            var myWindow = window.open('', 'my div', 'height=400,width=600');
            myWindow.document.write('<html><head><title>my div</title>');
            myWindow.document.write('</head><body >');
            myWindow.document.write(data);
            myWindow.document.write('</body></html>');
            myWindow.document.close();
            myWindow.onload=function(){
                myWindow.focus();
                myWindow.print();
                myWindow.close();
            };
        }
    </script>
    </head>
    <body  style="background:  linear-gradient(to bottom, #ffffff 0%,#f3f3f3 45%,#ededed 94%);">   
    <nav class="navbar navbar-dark bg-primary">
        <a class="navbar-brand display-4" style="display: block;margin-left: auto;margin-right: auto;width: 40%;text-align: center;" href="#">Stark Material Requirement Processing</a>
    </nav>  
        </br>
        
        <div class="division" style="width:650px; float:right; margin: 0px 40px 80px 40px ;"> 
            <div class="w3-third" align="left">
                <form style="overflow: hidden; padding: 10px 0;">
                    <h4 class="text-primary display-4" style="text-align: center"> Order Products </h4>
                    <h6 class="text-info" style="text-align: center"> Please enter only the product with product id available in MRPlan. </h6>
                    <h6 class="text-secondary">Requirement date:</h6><input type="date" id="dedate" class="form-control" required /><br>
                    <h6 class="text-secondary">Product name: (refer MRP table)</h6><input type="text" id="pname" class="form-control" required><br>
                    <h6 class="text-secondary">Product number: (refer MRP table) </h6>
                    <select class="custom-select form-control" id="pnum" required >
                        <option selected>Choose...</option>
                        <option value="7011">7011</option>
                        <option value="7012">7012</option>
                        <option value="7013">7013</option>
                    </select>
                    <h6 class="text-secondary">Required quantity: </h6> <input type="number" id="rnum" class="form-control" required /><br>
                    <INPUT type="submit" style="display: block; margin: 0 auto;" class="btn btn-primary" id="insertbtn" value="Order">
                </form>
                <hr>
                <form style="overflow: hidden; padding: 0px 30px;" action="#" method="post">
                    <h4 class="text-primary display-4" align="center"> Demand Analysis</h4>
                    <h6 class="text-info" style="text-align: center"> Enter a date to get material reqiurements and total bill on indicated date.</h6>
                    <h6 class="text-secondary">Enter date:</h6> <input type="date" name="dedate2" class="form-control"   required><br>
                    <INPUT type="submit" style="display: block; margin: 0 auto;" class="btn btn-primary" name="date" value="Calculate">
                </form>
            </div>
        </div>
        <?php include 'php/connection.php';?>
        <?php include 'php/loader.php';?>
        <hr style="width:550px; float:left; margin: 25px 25px 0 58px ;">
        </br>
        
<?php
if (!empty($_POST)){
    
$dedate2= $_POST['dedate2'];

$dfrmt ="YYYY/MM/DD";
$datereq = oci_parse($conn, 'SELECT YARNDMD FROM DEMAND WHERE DEDATE = TO_DATE(:dedate2,:dfrmt)');
$datereq2 = oci_parse($conn, 'SELECT FABRICDMND FROM DEMAND WHERE DEDATE = TO_DATE(:dedate2,:dfrmt)');
$datereq3 = oci_parse($conn, 'SELECT DYEDMD FROM DEMAND WHERE DEDATE = TO_DATE(:dedate2,:dfrmt)');
$datereq4 = oci_parse($conn, 'SELECT DECORATIVESDMD FROM DEMAND WHERE DEDATE = TO_DATE(:dedate2,:dfrmt)');
           
oci_bind_by_name($datereq, ':dedate2', $dedate2);
oci_bind_by_name($datereq, ':dfrmt', $dfrmt);
oci_execute($datereq);

oci_bind_by_name($datereq2, ':dedate2', $dedate2);
oci_bind_by_name($datereq2, ':dfrmt', $dfrmt);
oci_execute($datereq2);

oci_bind_by_name($datereq3, ':dedate2', $dedate2);
oci_bind_by_name($datereq3, ':dfrmt', $dfrmt);
oci_execute($datereq3);

oci_bind_by_name($datereq4, ':dedate2', $dedate2);
oci_bind_by_name($datereq4, ':dfrmt', $dfrmt);
oci_execute($datereq4);

#############################################################################################################################################

$yar="Yarn";   
$fab="Fabric";   
$dye="Dye";   
$deco="Decoratives";   

$vals = oci_parse($conn, 'SELECT rate FROM bom WHERE name = :yar');
oci_bind_by_name($vals, ':yar', $yar);
oci_execute($vals);
oci_fetch($vals);

$fabs = oci_parse($conn, 'SELECT rate FROM bom WHERE name = :fab');
oci_bind_by_name($fabs, ':fab', $fab);
oci_execute($fabs);
oci_fetch($fabs);

$dyes = oci_parse($conn, 'SELECT rate FROM bom WHERE name = :dye');
oci_bind_by_name($dyes, ':dye', $dye);
oci_execute($dyes);
oci_fetch($dyes);

$decs = oci_parse($conn, 'SELECT rate FROM bom WHERE name = :deco');
oci_bind_by_name($decs, ':deco', $deco);
oci_execute($decs);
oci_fetch($decs);

$yarnrate=oci_result($vals, 'RATE');
$fabrate=oci_result($fabs, 'RATE');
$dyerate=oci_result($dyes, 'RATE');
$decorate=oci_result($decs, 'RATE');

#############################################################################################################################################

echo "</br><H3 class='text-primary lead' style='display: block;margin-left: auto;margin-right: auto; width:72%;'>Material Requirement Report</H3>";

$yarnstotal=0;
$yarnbill=0;
$fabbill=0;
$dyebill=0;
$decorbill=0;

$count=0;

while ($row = oci_fetch_array($datereq, OCI_ASSOC+OCI_RETURN_NULLS)){
    echo "<tr STYLE='text-align:center;'>\n";
    foreach ($row as $item) {
        $yarnstotal=$yarnstotal+$item;
        $count=$count+1;
        }
        $yarnbill=$yarnstotal*$yarnrate;
    }

    $fabricstotal=0;
    while ($row = oci_fetch_array($datereq2, OCI_ASSOC+OCI_RETURN_NULLS) ) {
        echo "<tr STYLE='text-align:center;'>\n";
        foreach ($row as $item) {
            $fabricstotal=$fabricstotal+$item;
            }
            $fabbill=$fabricstotal*$fabrate;
        } 

        $dyestotal=0;
    while ($row = oci_fetch_array($datereq3, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo "<tr STYLE='text-align:center;'>\n";
        foreach ($row as $item) {
            $dyestotal=$dyestotal+$item;
            }
            $dyebill=$dyestotal*$dyerate;
        }   

        $decorstotal=0;
    while ($row = oci_fetch_array($datereq4, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo "<tr STYLE='text-align:center;'>\n";
        foreach ($row as $item) {
            $decorstotal=$decorstotal+$item;
            }
            $decorbill=$decorstotal*$decorate;
        }   
    $mattotal=0;
    $billtotal=0;
    $mattotal=$yarnstotal+$fabricstotal+$dyestotal+$decorstotal+$billtotal;
    $billtotal=$yarnbill+$fabbill+$dyebill+$decorbill;
    
        echo "<div id='Printable' class='division2' style='width: 39%;margin: 0px 40px 0 40px ;' ><pre>-----------------------------------------------------------------------------</br>                              Date: $dedate2</br>-----------------------------------------------------------------------------</br> YARNS REQUIREMENT: $yarnstotal             YARNS BILL: $yarnbill <br /> FABRICS REQUIREMENT: $fabricstotal           FABRICS BILL: $fabbill <br /> DYES REQUIREMENT: $dyestotal              DYES BILL: $dyebill<br /> DECORATIVES REQUIREMENT: $decorstotal      DECORATIVES BILL: $decorbill<br /> </br > TOTAL NUMBER OF ORDERS: $count <br /> TOTAL MATERIALS REQUIRED: $mattotal </br> TOTAL BILL: ₹ $billtotal </br>-----------------------------------------------------------------------------</pre> <input class='btn btn-primary' style='position: absolute; left: 295px;' type='button' value='Print' onclick='PrintDiv()' /> </div> ";
    }    
?>
 </br></br></br></br></br>
</body>
<div class="card-footer text-muted" style="position: fixed; left: 0; bottom: -14; width: 100%; background-color: #f4f4f4; text-align: center;">
     <p><small>❮ Built with 💙 by AzizStark ❯</small></p>
</div>
</html>
