<?php
// index.php

// 1. Incluir el archivo con las funciones de la API
require_once 'api_nessie.php';

// 2. Hacer la consulta a la API (Ejemplo: Obtener clientes)
// Aquí puedes cambiar '/customers' por el endpoint de cuentas cuando lo necesites
$listaClientes = callNessieAPI('GET', '/customers');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capital One Challenge - HackMTY</title>
    <!-- Incluir Bootstrap para estilos rápidos y limpios -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4 text-primary">Dashboard de Inteligencia Financiera</h1>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Cuentas Registradas en Nessie</h5>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($listaClientes['error'])): ?>
                        <!-- Manejo de errores -->
                        <div class="alert alert-danger">
                            <?php echo $listaClientes['error']; ?>
                        </div>
                    <?php elseif (empty($listaClientes)): ?>
                        <!-- Si el arreglo está vacío -->
                        <div class="alert alert-warning">
                            No hay clientes o cuentas registradas aún. ¡Es hora de hacer un POST!
                        </div>
                    <?php else: ?>
                        <!-- Tabla para mostrar los datos de la API -->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listaClientes as $cliente): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($cliente['_id'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['first_name'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['last_name'] ?? 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>