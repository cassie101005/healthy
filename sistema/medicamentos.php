<!DOCTYPE html>
<html lang="es">

<head>
    <?php include "cabecera.php"; ?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "sideBar.php"; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include "topBar.php"; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Content Row -->
                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h4 class="m-0 font-weight-bold text-primary">Mis Medicamentos</h4>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <!-- Botón para mostrar el formulario -->
                                        <button class="btn btn-primary mb-3" type="button" data-toggle="collapse"
                                            data-target="#formMedicamento" aria-expanded="false"
                                            aria-controls="formMedicamento">
                                            <i class="fas fa-pills"></i> Registrar Medicamento
                                        </button>

                                        <!-- Formulario dentro del collapse -->
                                        <div class="collapse mb-4" id="formMedicamento">
                                            <div class="card card-body">
                                                <form id="frmMedicamento">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label>Nombre del Medicamento</label>
                                                            <input type="text" name="vNombre" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label>Dosis (ej: 500mg)</label>
                                                            <input type="text" name="vDosis" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label>Frecuencia (ej: Cada 8 horas)</label>
                                                            <input type="text" name="vFrecuencia" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label>Fecha Inicio</label>
                                                            <input type="date" name="dFechaInicio" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label>Fecha Fin (Opcional)</label>
                                                            <input type="date" name="dFechaFin" class="form-control">
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Guardar
                                                        Medicamento</button>
                                                </form>
                                                <div id="mensaje" class="mt-2"></div>
                                            </div>
                                        </div>

                                        <!-- Tabla de Medicamentos -->
                                        <?php
                                        require_once "../modelo/consultar/obtener_medicamentos.php";
                                        
                                        // Verificar si la tabla existe
                                        try {
                                            $pdo->query("SELECT 1 FROM tbl_medicamentos LIMIT 1");
                                        } catch (Exception $e) {
                                            echo '<div class="alert alert-danger" role="alert">
                                                    <strong>¡Atención!</strong> La tabla de medicamentos no existe. 
                                                    <a href="db_create_medicamentos.php" class="btn btn-warning btn-sm ml-2">
                                                        <i class="fas fa-database"></i> Crear Base de Datos
                                                    </a>
                                                  </div>';
                                        }

                                        // Asegurar que solo pacientes vean esto o filtrar por paciente
                                        $idPaciente = $_SESSION['idUsuario'];
                                        $medicamentos = obtenerMedicamentos($idPaciente);
                                        // Asegurarse de que sea un array
                                        if (!is_array($medicamentos)) {
                                            $medicamentos = [];
                                        }
                                        ?>

                                        <table class="table table-bordered table-striped" id="tablaMedicamentos">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Dosis</th>
                                                    <th>Frecuencia</th>
                                                    <th>Inicio</th>
                                                    <th>Fin</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($medicamentos)): ?>
                                                    <?php foreach ($medicamentos as $med): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($med['vNombre']); ?></td>
                                                            <td><?php echo htmlspecialchars($med['vDosis']); ?></td>
                                                            <td><?php echo htmlspecialchars($med['vFrecuencia']); ?></td>
                                                            <td><?php echo htmlspecialchars($med['dFechaInicio']); ?></td>
                                                            <td><?php echo htmlspecialchars($med['dFechaFin'] ?? 'Indefinido'); ?>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-danger btn-sm"
                                                                    onclick="eliminarMedicamento(<?php echo $med['idMedicamento']; ?>)">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center">No tienes medicamentos
                                                            registrados.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include "cabeceraInferior.php"; ?>

    <script>
        document.getElementById('frmMedicamento').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('../modelo/registrar/registro_medicamento.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    const msgDiv = document.getElementById('mensaje');
                    if (data.status === 'success') {
                        msgDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        msgDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        function eliminarMedicamento(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este medicamento?')) {
                const formData = new FormData();
                formData.append('idMedicamento', id);

                fetch('../modelo/eliminar/eliminar_medicamento.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === 'success') {
                            location.reload();
                        } else {
                            alert('Error al eliminar: ' + data);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    </script>

</body>

</html>