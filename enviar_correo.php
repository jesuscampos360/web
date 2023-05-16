<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Recuperar los datos del formulario
  $nombre = $_POST['nombre'];
  $email = $_POST['email'];
  $mensaje = $_POST['mensaje'];
  $foto = $_FILES['foto'];

  // Dirección de correo electrónico a la que se enviará el formulario
  $destinatario = "$email;

  // Asunto del correo
  $asunto = "Nuevo mensaje del formulario de contacto";

  // Construir el cuerpo del correo
  $cuerpo = "Nombre: " . $nombre . "\n";
  $cuerpo .= "Email: " . $email . "\n";
  $cuerpo .= "Mensaje: " . $mensaje . "\n";

  // Nombre temporal y ruta del archivo adjunto
  $archivo_temporal = $foto['tmp_name'];
  $nombre_archivo = $foto['name'];

  // Tipo de contenido del archivo adjunto
  $tipo_contenido = $foto['type'];

  // Leer el contenido del archivo adjunto
  $contenido_adjunto = file_get_contents($archivo_temporal);

  // Codificar el contenido adjunto en base64
  $contenido_adjunto_codificado = base64_encode($contenido_adjunto);

  // Crear la cabecera del correo
  $cabecera = "From: " . $email . "\r\n";
  $cabecera .= "Reply-To: " . $email . "\r\n";
  $cabecera .= "MIME-Version: 1.0\r\n";
  $cabecera .= "Content-Type: multipart/mixed; boundary=\"frontier\"\r\n";

  // Crear el cuerpo del correo
  $cuerpo_correo = "--frontier\r\n";
  $cuerpo_correo .= "Content-Type: text/plain; charset=utf-8\r\n";
  $cuerpo_correo .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
  $cuerpo_correo .= $cuerpo . "\r\n";

  // Adjuntar la foto al correo
  $cuerpo_correo .= "--frontier\r\n";
  $cuerpo_correo .= "Content-Type: " . $tipo_contenido . "; name=\"" . $nombre_archivo . "\"\r\n";
  $cuerpo_correo .= "Content-Transfer-Encoding: base64\r\n";
  $cuerpo_correo .= "Content-Disposition: attachment; filename=\"" . $nombre_archivo . "\"\r\n\r\n";
  $cuerpo_correo .= $contenido_adjunto_codificado . "\r\n";
  $cuerpo_correo .= "--frontier--";

  // Enviar el correo
  if (mail($destinatario, $asunto, $cuerpo_correo, $cabecera)) {
    echo "El formulario ha sido enviado correctamente.";
  } else {
    echo "Ha ocurrido un error al enviar el formulario.";
  }
}
?>