<?php
	/**
	 * Mannella Luca
	 * Distributed Programming (03NQVOC)
	 * Academic Year 2015/2016
	 * Laboratory 8 - Exercise 1
	 */

	if (!isset($_COOKIE["pag"])) {
		setcookie("pag", 1);
		$page = 1;
	}
	else {
		$page = $_COOKIE["pag"] + 1;
		setcookie("pag", $page);
	}
?>

<?php
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
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Lab 8.1</title>
		<link href="styles.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="cookies.js"></script>
		<script type="text/javascript" src="ajax.js"></script>
		<script type="text/javascript" src="jquery.js"></script>

		<script>
			function goBack() {
				var page = document.getElementById("pagina").innerHTML;
				scriviCookie("pag", page-2);
				$("#MyForm").submit();
			}

			function nextElements() {
				if (req.readyState==4 && (req.status== 0 || req.status==200)) {
					if (req.responseText != null)
						document.getElementById("tochange").innerHTML = req.responseText;
					else
						alert("Ajax error: No data received");

					req.open("GET","lab8_1_server.php", true);
					req.send();
				}
				else
					alert("Data not yet received");
			}
		</script>
	</head>

	<body>
		<script>
			req = ajaxRequest();
			req.open("GET","lab8_1_server.php", true);
			req.send();
		</script>
		<noscript>
			<p>Unfortunately your browser <strong>does not support Javascript</strong> and some functionality are not available!</p>
		</noscript>
		
		<div id="tochange">
			<p>Page number:</p>	<p id='pagina'><?php echo $page; ?></p>
			<TABLE id="tabella">
				<TR><TH> Description </TH><TH> Price </TH><TH> Quantity </TH></TR>
				<?php
					$row = mysqli_fetch_array($res);
					while($row != NULL) {
						echo "<TR><TD>".$row['Descrizione']."</TD><TD>".$row['Prezzo']."</TD><TD>".$row['Quantita']."</TD></TR>";
						$row = mysqli_fetch_array($res);
					}
					mysqli_free_result($res);
					mysqli_close($conn);
				?>
			</TABLE>
			<br>
			<form id="MyForm">
				<?php if($page!==1): ?>
					<button type="button" onclick="goBack();">Previous Page</button>
				<?php endif;
				if($numRows===10): ?> 
					<button type="button" onclick="nextElements();">Next Page</button>
				<?php endif;?>
			</form>
		</div>
		<div id="footer">
			<TABLE ID="Outer" > <TR>
				<TH> <img src="http://security.polito.it/img/polito.gif" alt="PoliTo's Logo"> </TH>
				<TH> <div>This website was developed by Luca Mannella&reg;, all rights are reserved.</div>
					<TABLE ID="Inner" style="border-spacing: 10px;" > <TR>
						<TH><a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="CSS Valido!" /></a></TH>
						<TH><a href="https://it.linkedin.com/pub/luca-mannella/a1/859/9a5"> The author </a></TH>
						<TH><em><a href="mailto:luca.mannella@studenti.polito.it?Subject=Bug%20or%20Problem%20found!">Contact me</a></em></TH>
						<TH><i><a href="http://lukeman.altervista.org">Go back to the Home</a></i></TH>
						<TH><a href="http://validator.w3.org/check?uri=referer"> <img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Strict" height="31" width="88"></a></TH>
					</TR> </TABLE>
				</TH>
			</TR> </TABLE>
		</div>
		
	</body>
</html>