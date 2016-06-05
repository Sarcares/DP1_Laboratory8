<?php
    /**
     * Mannella Luca
     * Distributed Programming (03NQVOC)
     * Academic Year 2015/2016
     * Laboratory 8 - Exercise 1 - Server Side
     */

    if (!isset($_COOKIE["pag"])) {
        setcookie("pag", 1);
        $page = 1;
    }
    else {
        $page = $_COOKIE["pag"] + 1;
        setcookie("pag", $page);
    }

    $conn = mysqli_connect("localhost", "root", "", "example");
    if($conn == false)
        die("Connection Error (".mysqli_connect_errno().")".mysqli_connect_error());
    if (!mysqli_set_charset($conn, "utf8"))
        die("Errore nel caricamento del set di caratteri utf8:" . mysqli_error($conn));

    if( isset($_COOKIE["pag"]) )
        $offset = $_COOKIE["pag"] * 10;
    else
        $offset = 0;

    $res = mysqli_query($conn, "SELECT Descrizione, Prezzo, Quantita FROM books LIMIT " . $offset . ",10");
    if (!$res)
        die("Error in query execution!");

    $numRows = mysqli_num_rows($res);
    if(($page>1) && ($numRows==0)) {
        $res = mysqli_query($conn, "SELECT Descrizione, Prezzo, Quantita FROM books LIMIT ".($offset-10).",10");
        $page--;
        setcookie("pag", $page);
    }

    // preparing the code to sent back to the client
    $toRet = "<p>Page number:</p> <p id='pagina'>".$page."</p>";
    $toRet = $toRet."<TABLE id='tabella'><TR><TH> Description </TH><TH> Price </TH><TH> Quantity </TH></TR>";

    $row = mysqli_fetch_array($res);
    while($row != NULL) {
        $toRet = $toRet."<TR><TD>".htmlentities($row['Descrizione'])."</TD><TD>".$row['Prezzo']."</TD><TD>".$row['Quantita']."</TD></TR>";
        $row = mysqli_fetch_array($res);
    }
    mysqli_free_result($res);
    mysqli_close($conn);

    $toRet = $toRet."</TABLE><br>";
    $toRet = $toRet."<form id='MyForm'>";

    if($page!==1)
        $toRet = $toRet."<button type='button' onclick='goBack();'>Previous Page</button>";
	if($numRows===10)
        $toRet = $toRet."<button type='button' onclick='nextElements();'>Next Page</button>";

    $toRet = $toRet."</form>";

    echo $toRet;
?>