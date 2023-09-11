<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['url_imagen'])) {
    // Obtener la URL de la imagen ingresada por el usuario
    $urlImagen = $_POST['url_imagen'];

    // Verificar si la URL es válida (puedes agregar validaciones adicionales si es necesario)
    if (filter_var($urlImagen, FILTER_VALIDATE_URL)) {
        // Generar un nombre de archivo único con el formato "bluewalls_numerorandom.jpg"
        $nombreArchivo = 'img/bluewalls_' . uniqid('', true) . '.jpg';

        // Descargar y guardar la imagen en la carpeta con el nombre generado
        $imagenDescargada = file_get_contents($urlImagen);
        file_put_contents($nombreArchivo, $imagenDescargada);

        // Generar un identificador único basado en la URL de la imagen
        $identificador = 'bluewalls_' . hash('sha256', $urlImagen);

        $mysqli = new mysqli("sql105.infinityfree.com", "if0_34948077", "ZkEIrzetjAvjBJ", "if0_34948077_descarga");

        if ($mysqli->connect_error) {
            die("Error de conexión a la base de datos: " . $mysqli->connect_error);
        }

        $sql = "INSERT INTO archivos (identificador, ruta_archivo) VALUES ('$identificador', '$nombreArchivo')";

        if ($mysqli->query($sql) === TRUE) {
            // Generar el enlace de descarga que incluye el identificador
            $enlaceDescarga = 'https://donloadimageandvideosbluewalls.infinityfreeapp.com/descargar.php?identificador=' . $identificador;

            // Mostrar el enlace en la respuesta
            echo $enlaceDescarga;
        } else {
            echo "Error al insertar el registro en la base de datos: " . $mysqli->error;
        }

        $mysqli->close();

        exit();
    } else {
        echo "URL de imagen no válida.";
    }
} else {
    echo "Acceso no permitido.";
}

