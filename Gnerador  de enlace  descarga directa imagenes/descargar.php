<?php
if (isset($_GET['identificador'])) {
    $identificador = $_GET['identificador'];

    // Debes realizar una consulta a tu base de datos para obtener la ruta real del archivo asociado al identificador.
    // Esto es un ejemplo de cómo podría funcionar con MySQL, pero debes ajustarlo según tu base de datos y estructura.
    $mysqli = new mysqli("sql105.infinityfree.com", "if0_34948077", "ZkEIrzetjAvjBJ", "if0_34948077_descarga");
    
    if ($mysqli->connect_error) {
        die("Error de conexión a la base de datos: " . $mysqli->connect_error);
    }

    // Realizar una consulta para obtener la ruta del archivo
    $sql = "SELECT ruta_archivo FROM archivos WHERE identificador = '$identificador'";
    $result = $mysqli->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $rutaArchivo = $row['ruta_archivo'];

        // Verificar si el archivo existe en la ruta especificada
        if (file_exists($rutaArchivo)) {
            // Obtener el nombre del archivo
            $nombreArchivo = basename($rutaArchivo);

            // Definir las cabeceras HTTP para forzar la descarga con el nombre del archivo
            header('Content-Type: application/octet-stream');
            header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
            header('Content-Length: ' . filesize($rutaArchivo));

            // Leer y enviar el archivo al navegador
            readfile($rutaArchivo);

            // Finalizar el script PHP para evitar que se siga ejecutando
            exit();
        } else {
            echo "El archivo no existe.";
        }
    } else {
        echo "No se encontró información para el identificador proporcionado.";
    }

    // Cerrar la conexión a la base de datos
    $mysqli->close();
} else {
    echo "Identificador no válido.";
}
?>
