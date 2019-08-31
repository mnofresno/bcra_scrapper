<?php

class BCRAScrapper
{
    const SERIES = [
        7927 => 'Tipo de Cambio Minorista ($ por US$) Com. B 9791',
        272  => 'Tipo de Cambio Mayorista ($ por US$) Com.',
        7923 => 'Tasa de LELIQ (promedio en n.a.)',
        246  => 'Reservas Internacionales del BCRA (en millones de US$)'
    ];

    function getData(int $serie) {
        $ch = curl_init('http://www.bcra.gov.ar/PublicacionesEstadisticas/Principales_variables_datos.asp');
        $today = date('Ymd');
        $params = "fecha_desde=20160101"
                ."&primeravez=1"
                ."&fecha_hasta=$today"
                ."&serie=".$serie
                ."&serie1=0"
                ."&serie2=0"
                ."&serie3=0"
                ."&serie4=0";

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1800);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $contents = curl_exec($ch);
        curl_close($ch);
        $DOM = new DOMDocument();
        @$DOM->loadHTML($contents);
        $xpath = new DOMXPath($DOM);
        $table = $xpath->query('//table')->item(0);
        $rows = $table->getElementsByTagName("tr");
        $result = [];
        foreach ($rows as $row) {
            $cells = $row-> getElementsByTagName('td');
            $rowOutput = [];
            foreach ($cells as $cell) {
                $rowOutput[] = trim($cell->nodeValue);
            }
            $result[] = $rowOutput;
        }
        return $result;
    }

    function printSerie(string $descripcion, array $value) {
        if ($this->isBrowser()) {
            ?>
                <h4><?php echo $descripcion; ?>:</h4>
                <ul>
                    <li>Al <?php echo $value[0]; ?>:</li>
                    <li>El valor es: <?php echo $value[1]; ?>:</li>
                </ul>
            <?php
        } else {
            echo "\e[101m$descripcion:\e[0m\n";
            echo "\e[31mAl $value[0] el valor es: $value[1]\e[0m\n";
        }
    }

    function run() {
        foreach (self::SERIES as $serie => $descripcion) {
            $result = $this->getData($serie);
            $fileDescripcion = str_replace(' ', '_', $descripcion);
            file_put_contents("./data_bcra_{$fileDescripcion}.log", serialize($result));
            $last = end($result);
            $this->printSerie($descripcion, $last);
        }
    }

    function isBrowser() {
        $browserAgents = ['WhatsApp', 'Mozilla'];

        return array_reduce($browserAgents, function ($carry, $item) {
            $userAgent = @$_SERVER['HTTP_USER_AGENT'];
            $carry = $carry || (strpos($userAgent, $item) !== false);
            return $carry;
        });
    }
}

$scrapper = new BCRAScrapper();

if ($scrapper->isBrowser()) {
    ?>
    <html>
        <head>
            <title>Datos oficiales del BCRA</title>
            <meta name="description" content="Datos económicos obtenidos desde el BCRA">
            <meta property="og:title" content="Datos económicos obtenidos desde el BCRA" />
            <meta property="og:url" content="https://api-nightplan.catalisis.com.ar/" />
            <meta property="og:description" content="Datos econ&oacute;micos del d&iacute;a">
            <meta property="og:image" content="//img.pngio.com/dollar-sign-png-images-vectors-and-psd-files-free-download-on-png-dollar-sign-260_429.png">
            <meta property="og:type" content="article" />
            <meta property="og:locale" content="en_GB" />
            <meta property="og:locale:alternate" content="fr_FR" />
            <meta property="og:locale:alternate" content="es_ES" />
            <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        </head>
        <body>
            <h1>Datos oficiales BCRA:</h1>
            <?php
                $scrapper->run();
            ?>
        </body>
    </html>
<?php
} else {
    $scrapper->run();
}
?>