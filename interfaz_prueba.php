<?php
function validar_y_sanitizar_correos(array $lista_correos) {
    $correos_validos = [];
    $correos_invalidos = [];
    
    foreach ($lista_correos as $correo) {
        if (is_string($correo)) {
            $correo_sanitizado = preg_replace('/[^a-zA-Z0-9@._-]/', '', $correo);
            if (filter_var($correo_sanitizado, FILTER_VALIDATE_EMAIL)) {
                $correos_validos[] = $correo_sanitizado;
            } else {
                $correos_invalidos[] = $correo;
            }
        } else {
            $correos_invalidos[] = $correo;
        }
    }
    
    return [$correos_validos, $correos_invalidos];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_correos = isset($_POST['correos']) ? trim($_POST['correos']) : '';
    
    
    $lista_de_correos = array_filter(array_map('trim', explode(',', str_replace("\n", ',', $input_correos))));
    
   
    list($correos_validos, $correos_invalidos) = validar_y_sanitizar_correos($lista_de_correos);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz para Probar Validación de Correos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { margin-bottom: 20px; }
        textarea { width: 100%; height: 100px; }
        .result { margin-top: 20px; padding: 10px; border: 1px solid #ccc; }
        .valid { color: green; }
        .invalid { color: red; }
    </style>
</head>
<body>
    <h1>Prueba de Validación y Sanitización de Correos Electrónicos</h1>
    <p>Ingresa una lista de correos electrónicos separados por comas o saltos de línea. Por ejemplo:</p>
    <p><em>user1@example.com, user2@exam ple.com, </em></p>
    
    <form method="POST" action="">
        <label for="correos">Ingresa los correos:</label><br>
        <textarea name="correos" id="correos" placeholder="Ej: user1@example.com, user2@example.com"></textarea><br>
        <button type="submit">Enviar</button>
    </form>
    
    <?php if (isset($correos_validos) && isset($correos_invalidos)): ?>
        <div class="result">
            <h2>Resultados:</h2>
            <h3>Correos válidos:</h3>
            <p class="valid">
                <?php if (!empty($correos_validos)): ?>
                    <?php echo implode('<br>', $correos_validos); ?>
                <?php else: ?>
                    Ninguno.
                <?php endif; ?>
            </p>
            
            <h3>Correos inválidos:</h3>
            <p class="invalid">
                <?php if (!empty($correos_invalidos)): ?>
                    <?php echo implode('<br>', $correos_invalidos); ?>
                <?php else: ?>
                    Ninguno.
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>
</body>
</html>