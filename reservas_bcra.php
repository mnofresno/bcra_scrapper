<?php


$ch = curl_init('http://www.bcra.gov.ar/PublicacionesEstadisticas/Principales_variables_datos.asp');

$today = date('Ymd');
$todayIso = date('Y-m-d');

$params = "fecha_desde=2016-01-01&fecha_hasta=$todayIso&B1=Enviar&primeravez=1&fecha_desde=20140101&fecha_hasta=$today&serie=246&serie1=0&serie2=0&serie3=0&serie4=0&detalle=Reservas+Internacionales+del+BCRA%A0%28en+millones+de+d%F3lares+-+cifras+provisorias+sujetas+a+cambio+de+valuaci%F3n%29";
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_TIMEOUT, 1800);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

$contents = curl_exec($ch);

curl_close ($ch);
$DOM = new DOMDocument();
$DOM->loadHTML($contents);
$xpath = new DOMXPath($DOM);
$table = $xpath->query('//table')->item(0);
$rows = $table->getElementsByTagName("tr");

$result = [];

foreach($rows as $row) {
    $cells = $row-> getElementsByTagName('td');

    $rowOutput = [];
    foreach ($cells as $cell) {
        $rowOutput[] = trim($cell->nodeValue);
    }

    $result[] = $rowOutput;

}

$last = end($result);

function isBrowser() {
    $browserAgents = ['WhatsApp', 'Mozilla'];

	return array_reduce($browserAgents, function ($carry, $item) {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
		$carry = $carry || (strpos($userAgent, $item) !== false);
		return $carry;
	});
}

if (isBrowser()) {
?>
<html>
<head>
<title>Datos oficiales del BCRA</title>
<meta name="description" content="description of your website/webpage, make sure you use keywords!">
<meta property="og:title" content="Reservas en tiempo real del banco central" />
<meta property="og:url" content="https://www.example.com/webpage/" />
<meta property="og:description" content="Al <?php echo $last[0]; ?> quedan s&oacute;lamente: <?php echo $last[1]; ?> mil millones de U$S">
<meta property="og:image" content="//cdn.example.com/uploads/images/webpage_300x200.png">
<meta property="og:type" content="article" />
<meta property="og:locale" content="en_GB" />
<meta property="og:locale:alternate" content="fr_FR" />
<meta property="og:locale:alternate" content="es_ES" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">

</head>
<body>
Al <?php echo $last[0]; ?> quedan: <?php echo $last[1]; ?> mil millones de U$S
</body>
</html>

<?php
} else {
    echo "\e[101mReservas del BCRA:\e[0m\n";
    echo "\e[31mAl $last[0] quedan: $last[1] mil millones de U\$S\e[0m\n";
}
?>
