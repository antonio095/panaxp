<pre>
<?php

function getNumeroDeCliente($rfc){

    $serverName = "192.168.2.25\\sqlexpress,1433"; //serverName\instanceName
    $connectionInfo = array( "Database"=>"DARWIN17_18", "UID"=>"sa", "PWD"=>"MaStErS1**");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);
    
    if( $conn ) {
     //     echo "Connection established.<br />";
    }else{
         echo "Connection could not be established.<br />";
         die( print_r( sqlsrv_errors(), true));
    }

    $sql = "SELECT Numero FROM VT_Clientes WHERE RFC = '$rfc' AND  Estatus != '1' ";
    $stmt = sqlsrv_query( $conn, $sql );
    $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
    $numeroDeCliente = $row['Numero'];

    return $numeroDeCliente;
}

?>
</pre>