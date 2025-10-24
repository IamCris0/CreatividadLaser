<?php
// Configuración
ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('America/Guayaquil');

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
    
    // Obtener el dominio actual
    $dominio = $_SERVER['HTTP_HOST'];
    
    // Crear el asunto del email
    $asunto = "Nueva Encuesta de Satisfaccion - Creatividad Laser";
    
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
                background-color: #f4f4f4;
            }
            .email-container {
                background-color: white;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            .header {
                background: linear-gradient(135deg, #ff1b8d, #ff006e);
                color: white;
                padding: 30px 20px;
                text-align: center;
            }
            .header h2 {
                margin: 0;
                font-size: 24px;
            }
            .content {
                padding: 30px;
            }
            .field {
                margin-bottom: 20px;
                padding: 15px;
                background: #f9f9f9;
                border-left: 4px solid #ff1b8d;
                border-radius: 5px;
            }
            .field-label {
                font-weight: bold;
                color: #ff1b8d;
                margin-bottom: 8px;
                display: block;
            }
            .field-value {
                color: #333;
                font-size: 15px;
            }
            .rating {
                font-size: 20px;
                color: #ff1b8d;
                font-weight: bold;
            }
            .footer {
                background: #333;
                color: white;
                padding: 20px;
                text-align: center;
                font-size: 12px;
            }
            .footer p {
                margin: 5px 0;
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='header'>
                <h2>✨ Nueva Encuesta de Satisfacción ✨</h2>
                <p>Creatividad Laser</p>
            </div>
            
            <div class='content'>
                <div class='field'>
                    <span class='field-label'>👤 Nombre Completo:</span>
                    <span class='field-value'>{$nombre}</span>
                </div>
                
                <div class='field'>
                    <span class='field-label'>📧 Correo Electrónico:</span>
                    <span class='field-value'>{$email}</span>
                </div>
                
                <div class='field'>
                    <span class='field-label'>📱 Teléfono:</span>
                    <span class='field-value'>{$telefono}</span>
                </div>
                
                <div class='field'>
                    <span class='field-label'>⭐ Calificación del Servicio:</span>
                    <span class='field-value rating'>{$calificacion}</span>
                </div>
                
                <div class='field'>
                    <span class='field-label'>🔧 Servicio Utilizado:</span>
                    <span class='field-value'>{$servicio}</span>
                </div>
                
                <div class='field'>
                    <span class='field-label'>❓ ¿Cómo nos conoció?:</span>
                    <span class='field-value'>{$comoNosConocio}</span>
                </div>
                
                <div class='field'>
                    <span class='field-label'>💬 Comentarios o Sugerencias:</span>
                    <span class='field-value'>{$comentarios}</span>
                </div>
                
                <div class='field'>
                    <span class='field-label'>📬 Desea recibir información:</span>
                    <span class='field-value'>{$recibirInfo}</span>
                </div>
            </div>
            
            <div class='footer'>
                <p><strong>Fecha:</strong> " . date('d/m/Y H:i:s') . "</p>
                <p>© " . date('Y') . " Creatividad Laser - Todos los derechos reservados</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Cabeceras del email
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Encuestas Creatividad Laser <noreply@{$dominio}>\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "X-Priority: 1\r\n";
    
    // Intentar enviar el email
    $enviado = @mail($email_destino, $asunto, $mensaje, $headers);
    
    if ($enviado) {
        // Éxito - redirigir con mensaje
        header("Location: ../encuesta/encuesta.html?success=1");
        exit();
    } else {
        // Error al enviar - registrar en log
        error_log("Error al enviar encuesta desde: {$email}");
        header("Location: ../encuesta/encuesta.html?error=envio_fallido");
        exit();
    }
    
} else {
    // Si no es POST, redirigir a la encuesta
    header("Location: ../encuesta/encuesta.html");
    exit();
}
?>