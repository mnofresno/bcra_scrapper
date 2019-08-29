<?php

$ch = curl_init('http://www.bcra.gov.ar/PublicacionesEstadisticas/Principales_variables_datos.asp');

$today = date('Ymd');
$params = "fecha_desde=2016-01-01&fecha_hasta=2019-08-27&B1=Enviar&primeravez=1&fecha_desde=20140101&fecha_hasta=$today&serie=246&serie1=0&serie2=0&serie3=0&serie4=0&detalle=Reservas+Internacionales+del+BCRA%A0%28en+millones+de+d%F3lares+-+cifras+provisorias+sujetas+a+cambio+de+valuaci%F3n%29";
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_TIMEOUT, 1800);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);


$contents = curl_exec($ch);
file_put_contents('/tmp/file.html', $contents);
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

print_r($last);