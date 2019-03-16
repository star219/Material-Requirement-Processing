<?php

    $stid = oci_parse($conn,  'SELECT * FROM MRP');
    $stid3 = oci_parse($conn, 'SELECT * FROM PRODEMAND ORDER BY dedate');

    oci_execute($stid);
    oci_execute($stid3);
        
    echo "<H4 class='text-primary lead' style='display: block;margin-left: auto;margin-right: auto; width:69%; padding:10px 0; '>Material Requirement Plan </H4>";
    echo "<table class='table table-sm table-light table-hover table-borderless table-striped ' style='width: 39%;margin: 0px 40px 0 40px ;'>\n";
    echo "<tr class='bg-primary text-light'> <td>". "PRODUCT NUMBER" . "</td>\n"."<td>". "PRODUCT NAME" . "</td>\n"."<td>". "YARNS" . "</td>\n"."<td>". "FABRICS" . "</td>\n"."<td>". "DYES" . "</td>\n"."<td>". "DECORATIVES" . "</td> </tr>";
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo "<tr STYLE='text-align:center;'>\n";
        foreach ($row as $item) {
            echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
        }
        echo "</tr>";
        }
    echo "</table>";

    ##########################################################################################################
    
    echo "<H4 class='text-primary lead' style='display: block;margin-left: auto;margin-right: auto; width:64%; padding:10px 0;'>Product Demand</H4>";
    echo "<table id='mydiv' class='table table-sm table-light table-hover table-borderless table-striped' style='width: 39%;margin: 0px 40px 0 40px ;'>\n";
    echo "<tr class='bg-primary text-light'> <td>". "DEMANDDATE" . "</td>\n"."<td>". "PRODUCTNAME" . "</td>\n"."<td>". "PRODUCTNUMBER" . "</td>\n"."<td>". "PRODUCTREQUIREMENT" .  "</td> </tr>"; 
        while ($row = oci_fetch_array($stid3, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo "<tr STYLE='text-align:center;'>\n";
        foreach ($row as $item) {
            echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
        }
            echo "</tr>";
    }
    echo "</table>";
?>