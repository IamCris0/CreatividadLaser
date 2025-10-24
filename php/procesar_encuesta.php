<?php
// Configuración
header('Content-Type: text/html; charset=UTF-8');

// Email donde recibirás las encuestas
$email_destino = "gcristopher417@gmail.com";

// Verificar que sea una petición POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener y limpiar datos del formulario
    $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $telefono = htmlspecialchars(trim($_POST['telefono'] ?? 'No proporcionado'));
    $calificacion = htmlspecialchars($_POST['calificacion'] ?? '');
    $servicio = htmlspecialchars($_POST['servicio'] ?? '');
    $comoNosConocio = htmlspecialchars($_POST['comoNosConocio'] ?? '');
    $comentarios = htmlspecialchars(trim($_POST['comentarios'] ?? 'Sin comentarios'));
    $recibirInfo = isset($_POST['recibirInfo']) ? 'Sí' : 'No';
    
    // Validar campos requeridos
    if (empty($nombre) || empty($email) || empty($calificacion) || empty($servicio) || empty($comoNosConocio)) {
        header("Location: ../encuesta/encuesta.html?error=campos_requeridos");
        exit();
    }
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../encuesta/encuesta.html?error=email_invalido");
        exit();
    }
    
    // Crear el asunto del email
    $asunto = "Nueva Encuesta de Satisfacción - Creatividad Laser";
    
    // Crear el cuerpo del email en HTML
    $mensaje = "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }
            .header {
                background: linear-gradient(135deg, #ff1b8d, #ff006e);
                color: white;
                padding: 20px;
                border-radius: 10px 10px 0 0;
                text-align: center;
            }
            .content {
                background: #f9f9f9;
                padding: 30px;
                border: 1px solid #ddd;
            }
            .field {
                margin-bottom: 20px;
                padding: 15px;
                background: white;
                border-left: 4px solid #ff1b8d;
                border-radius: 5px;
            }
            .field-label {
                font-weight: bold;
                color: #ff1b8d;
                margin-bottom: 5px;
            }
            .field-value {
                color: #333;
                margin-top: 5px;
            }
            .footer {
                background: #333;
                color: white;
                padding: 15px;
                text-align: center;
                border-radius: 0 0 10px 10px;
                font-size: 12px;
            }
            .rating {
                font-size: 24px;
                color: #ff1b8d;
            }
        </style>
    </head>
    <body>
        <div class='header'>
            <h2>✨ Nueva Encuesta de Satisfacción ✨</h2>
            <p>Creatividad Laser</p>
        </div>
        
        <div class='content'>
            <div class='field'>
                <div class='field-label'>👤 Nombre Completo:</div>
                <div class='field-value'>{$nombre}</div>
            </div>
            
            <div class='field'>
                <div class='field-label'>📧 Correo Electrónico:</div>
                <div class='field-value'>{$email}</div>
            </div>
            
            <div class='field'>
                <div class='field-label'>📱 Teléfono:</div>
                <div class='field-value'>{$telefono}</div>
            </div>
            
            <div class='field'>
                <div class='field-label'>⭐ Calificación del Servicio:</div>
                <div class='field-value rating'>{$calificacion}</div>
            </div>
            
            <div class='field'>
                <div class='field-label'>🔧 Servicio Utilizado:</div>
                <div class='field-value'>{$servicio}</div>
            </div>
            
            <div class='field'>
                <div class='field-label'>❓ ¿Cómo nos conoció?:</div>
                <div class='field-value'>{$comoNosConocio}</div>
            </div>
            
            <div class='field'>
                <div class='field-label'>💬 Comentarios o Sugerencias:</div>
                <div class='field-value'>{$comentarios}</div>
            </div>
            
            <div class='field'>
                <div class='field-label'>📬 Desea recibir información:</div>
                <div class='field-value'>{$recibirInfo}</div>
            </div>
        </div>
        
        <div class='footer'>
            <p>Fecha: " . date('d/m/Y H:i:s') . "</p>
            <p>© " . date('Y') . " Creatividad Laser - Todos los derechos reservados</p>
        </div>
    </body>
    </html>
    ";
    
    // Cabeceras del email
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Encuestas Creatividad Laser <noreply@" . $_SERVER['HTTP_HOST'] . ">\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Enviar el email
    if (mail($email_destino, $asunto, $mensaje, $headers)) {
        // Redirigir a página de éxito
        header("Location: ../inicio/index.html?success=1");
        exit();
    } else {
        // Error al enviar
        header("Location: ../encuesta/encuesta.html?error=envio_fallido");
        exit();
    }
    
} else {
    // Si no es POST, redirigir a la encuesta
    header("Location: ../encuesta/encuesta.html");
    exit();
}
?>