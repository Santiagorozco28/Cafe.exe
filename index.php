<?php
// index.php

// 1. Incluir el archivo con las funciones de la API
require_once 'api_nessie.php';

// 2. Hacer la consulta a la API
$listaClientes = callNessieAPI('GET', '/customers');

// Opciones de situación laboral para simular la data
$opcionesLaborales = ['Empleado', 'Desempleado', 'Retirado', 'Freelancer'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capital One - Intelligence Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --capital-blue: #004B87;
            --capital-red: #D22E1E;
            --capital-light: #F4F5F7;
            --money-green: #198754; 
        }
        body {
            background-color: var(--capital-light);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        .navbar-custom { background-color: var(--capital-blue); }
        .card-profile {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
            border-radius: 12px;
            background-color: white;
        }
        .card-profile:hover { box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); }
        
        .border-blue { border-top: 4px solid var(--capital-blue); }
        .border-red { border-top: 4px solid var(--capital-red); }
        .border-green { border-top: 4px solid var(--money-green); }

        .metric-box {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            height: 100%;
        }
        .metric-title {
            font-size: 0.9rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .metric-value {
            font-size: 2rem;
            font-weight: 800;
        }
        .text-money { color: var(--money-green); }
        
        .address-box {
            background-color: #f8f9fa;
            border: 1px dashed #ced4da;
            border-radius: 8px;
            padding: 15px;
        }
    </style>
</head>
<body>

<!-- Menú de Navegación -->
<nav class="navbar navbar-expand-lg navbar-custom mb-4 shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand text-white fw-bold" href="#">
            <span style="color: var(--capital-red);">Capital</span> One <span class="text-white-50 mx-1">|</span> <span class="fw-light fs-6">Intelligence Dashboard</span>
        </a>
    </div>
</nav>

<div class="container-fluid px-4 pb-5">
    <h2 class="mb-4 fw-bold" style="color: var(--capital-blue);">Análisis Financiero de Clientes</h2>
    
    <?php if (isset($listaClientes['error'])): ?>
        <div class="alert alert-danger shadow-sm">
            <?php echo htmlspecialchars($listaClientes['error']); ?>
        </div>
    <?php elseif (empty($listaClientes)): ?>
        <div class="alert alert-warning shadow-sm">
            No hay clientes registrados en tu entorno.
        </div>
    <?php else: ?>
        
        <div class="d-flex flex-column gap-5">
            
            <?php foreach ($listaClientes as $cliente): 
                // Datos principales
                $id = htmlspecialchars($cliente['_id'] ?? 'N/A');
                $nombre = htmlspecialchars($cliente['first_name'] ?? 'Usuario');
                $apellido = htmlspecialchars($cliente['last_name'] ?? '');
                $correoBase = strtolower(trim($nombre) . '.' . trim($apellido));
                $correo = str_replace(' ', '', $correoBase) . '@capitalhack.com';
                
                // Extraer la dirección de Nessie
                $direccion = $cliente['address'] ?? [];
                $calleNum = htmlspecialchars(($direccion['street_number'] ?? '') . ' ' . ($direccion['street_name'] ?? 'No registrada'));
                $ciudadEstado = htmlspecialchars(($direccion['city'] ?? '') . ', ' . ($direccion['state'] ?? '') . ' ' . ($direccion['zip'] ?? ''));

                // Simulación financiera
                $estadoLaboral = $opcionesLaborales[array_rand($opcionesLaborales)];
                $ingresoMensual = rand(15000, 40000); 
                $saldo = $ingresoMensual * rand(3, 8) + rand(1000, 9000);
                $porcentajeAhorro = rand(10, 25);
                $montoAhorro = ($saldo * $porcentajeAhorro) / 100;
            ?>
            
            <!-- BLOQUE DE CLIENTE (3 Columnas) -->
            <div class="row g-4">
                
                <!-- COLUMNA 1 (IZQUIERDA): Perfil, Finanzas y Datos Adicionales API -->
                <div class="col-lg-4 col-xl-4">
                    <div class="card card-profile border-blue h-100 p-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="card-title fw-bold mb-1" style="color: var(--capital-blue);">
                                        <?php echo $nombre . ' ' . $apellido; ?>
                                    </h4>
                                    <div class="text-muted small">
                                        <i class="bi bi-envelope"></i> <?php echo $correo; ?>
                                    </div>
                                </div>
                                <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                    <?php echo $estadoLaboral; ?>
                                </span>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <div class="metric-box bg-light border-success border-opacity-25">
                                        <div class="metric-title fw-bold text-success">Saldo de Cuenta Principal</div>
                                        <div class="metric-value text-money">
                                            $<?php echo number_format($saldo, 2); ?> <span class="fs-5 text-muted">MXN</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="metric-box">
                                        <div class="metric-title">Ingreso Mensual</div>
                                        <div class="metric-value fs-4 text-dark">
                                            $<?php echo number_format($ingresoMensual, 2); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="metric-box">
                                        <div class="metric-title text-primary">Ahorro (<?php echo $porcentajeAhorro; ?>%)</div>
                                        <div class="metric-value fs-4 text-primary">
                                            $<?php echo number_format($montoAhorro, 2); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Datos de Nessie -->
                            <div class="address-box">
                                <h6 class="text-muted fw-bold mb-3" style="font-size: 0.8rem; letter-spacing: 1px;">
                                    DATOS DE REGISTRO (API NESSIE)
                                </h6>
                                <div class="mb-2">
                                    <span class="text-muted small d-block">ID de Cliente:</span>
                                    <span class="fw-bold text-dark font-monospace"><?php echo $id; ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted small d-block">Dirección (Calle):</span>
                                    <span class="text-dark"><i class="bi bi-geo-alt text-danger"></i> <?php echo $calleNum; ?></span>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Ciudad y Estado:</span>
                                    <span class="text-dark"><i class="bi bi-building text-primary"></i> <?php echo $ciudadEstado; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA 2 (CENTRAL): Historial y Recomendaciones -->
                <div class="col-lg-4 col-xl-4">
                    <div class="d-flex flex-column h-100 gap-4">
                        
                        <div class="card card-profile border-green p-2 flex-fill">
                            <div class="card-body">
                                <h5 class="fw-bold text-dark mb-3">Últimas Transacciones</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Descripción</th>
                                                <th>Tipo</th>
                                                <th class="text-end">Monto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-muted small">Hoy, 10:30 AM</td>
                                                <td>Pago de Nómina</td>
                                                <td><span class="badge bg-success bg-opacity-10 text-success border border-success">Ingreso</span></td>
                                                <td class="text-end fw-bold text-success">+$15,000.00</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted small">Ayer, 14:15 PM</td>
                                                <td>Amazon Web Services</td>
                                                <td><span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Gasto</span></td>
                                                <td class="text-end fw-bold text-danger">-$450.00</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted small">10 Sep 2026</td>
                                                <td>Fondo de Ahorro</td>
                                                <td><span class="badge bg-primary bg-opacity-10 text-primary border border-primary">Movimiento</span></td>
                                                <td class="text-end fw-bold text-primary">-$2,500.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card card-profile border-red p-2 flex-fill">
                            <div class="card-body">
                                <h5 class="fw-bold text-dark mb-3">Recomendaciones Inteligentes (IA)</h5>
                                <div class="alert alert-light border shadow-sm mb-2">
                                    <strong class="text-danger">Anomalía detectada:</strong> Aumento inusual del 40% en gastos de categoría "Restaurantes". Sugerimos establecer un límite de gasto.
                                </div>
                                <div class="alert alert-light border shadow-sm mb-0">
                                    <strong class="text-primary">Inversión:</strong> Tienes liquidez excedente. Recomendamos mover un 15% a tu fondo para generar rendimientos.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- COLUMNA 3 (DERECHA): Chatbot IA -->
                <div class="col-lg-4 col-xl-4">
                    <div class="card card-profile border-blue h-100 d-flex flex-column">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-2">
                                <i class="bi bi-robot fs-5"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">Capital Asistente Inteligente</h6>
                                <small class="text-success"><i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> En línea con Gemini Flash</small>
                            </div>
                        </div>
                        
                        <div class="card-body flex-grow-1 d-flex flex-column" style="background-color: #f8f9fa; max-height: 550px; overflow-y: auto;" id="chat-box-<?php echo $id; ?>">
                            
                            <!-- Mensaje del Bot Inicial -->
                            <div class="d-flex mb-3">
                                <div class="bg-white border rounded p-3 shadow-sm" style="max-width: 85%;">
                                    <p class="small mb-0 text-dark">¡Hola, <?php echo $nombre; ?>! Soy tu asesor financiero impulsado por IA. Puedo analizar tus gastos o ayudarte a planificar un presupuesto. ¿Qué necesitas hoy?</p>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="card-footer bg-white border-top p-3">
                            <!-- NUEVO: Formulario que inyecta los datos financieros al JavaScript -->
                            <form onsubmit="event.preventDefault(); enviarMensaje('<?php echo $id; ?>', <?php echo $saldo; ?>, <?php echo $ingresoMensual; ?>, <?php echo $montoAhorro; ?>);">
                                <div class="input-group">
                                    <input type="text" id="input-<?php echo $id; ?>" class="form-control border-end-0" placeholder="Pregunta sobre finanzas...">
                                    <button class="btn btn-outline-secondary border-start-0" type="submit">
                                        <i class="bi bi-send-fill text-primary"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Fin Columna 3 -->

            </div> <!-- Fin Bloque de Cliente -->
            
            <hr class="my-5 border-2 opacity-25">
            
            <?php endforeach; ?>
            
        </div>
    <?php endif; ?>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- NUEVO: Script con inyección de contexto -->
<script>
    async function enviarMensaje(clienteId, saldoCliente, ingresoCliente, ahorroCliente) {
        const input = document.getElementById('input-' + clienteId);
        const mensaje = input.value.trim();
        if(!mensaje) return;
        
        const chatBox = document.getElementById('chat-box-' + clienteId);
        
        // 1. Mostrar el mensaje del usuario en la pantalla
        chatBox.innerHTML += `
            <div class="d-flex mb-3 justify-content-end">
                <div class="text-white rounded p-3 shadow-sm" style="background-color: var(--capital-blue); max-width: 85%;">
                    <p class="small mb-0">${mensaje}</p>
                </div>
            </div>
        `;
        input.value = '';
        chatBox.scrollTop = chatBox.scrollHeight;
        
        // 2. Mostrar indicador de "Escribiendo..."
        const loadingId = 'loading-' + Date.now();
        chatBox.innerHTML += `
            <div id="${loadingId}" class="d-flex mb-3">
                <div class="bg-white border rounded p-3 shadow-sm" style="max-width: 85%;">
                    <p class="small mb-0 text-muted fst-italic">Analizando perfil financiero...</p>
                </div>
            </div>
        `;
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            // 3. Empaquetamos el mensaje Y el contexto financiero
            const payload = {
                mensaje: mensaje,
                contexto: {
                    saldo: saldoCliente,
                    ingreso: ingresoCliente,
                    ahorro: ahorroCliente
                }
            };

            // 4. Hacer la petición a nuestro PHP
            const respuesta = await fetch('chat_gemini.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            
            const datos = await respuesta.json();
            
            // Remover el indicador
            const loadingElement = document.getElementById(loadingId);
            if (loadingElement) loadingElement.remove();
            
            // Formatear negritas
            const textoFormateado = datos.respuesta ? datos.respuesta.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') : 'Error al obtener respuesta.';
            
            // 5. Imprimir respuesta de la IA
            chatBox.innerHTML += `
                <div class="d-flex mb-3">
                    <div class="bg-white border rounded p-3 shadow-sm" style="max-width: 85%;">
                        <p class="small mb-0 text-dark">${textoFormateado}</p>
                    </div>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;
            
        } catch (error) {
            const loadingElement = document.getElementById(loadingId);
            if (loadingElement) loadingElement.remove();
            console.error("Error al contactar a la IA:", error);
        }
    }
</script>
</body>
</html>