<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <ul class="navbar-nav ml-auto">

        <!-- 🔔 NOTIFICACIONES -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown">
                <i class="fas fa-bell fa-fw"></i>
                <span class="badge badge-danger badge-counter" id="cantNotif"></span>
            </a>

            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow">
                <div class="dropdown-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Notificaciones</h6>
                    <button id="btnLimpiarNotif" class="btn btn-sm btn-light" onclick="limpiarNotificaciones()"
                        style="font-size: 13px; padding: 2px 8px;" disabled>
                        Limpiar
                    </button>
                </div>

                <div id="listaNotificaciones" style="max-height: 350px; overflow-y: auto;"></div>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Perfil -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    <?= htmlspecialchars($_SESSION['usuario']['vNombre'] ?? 'Usuario') ?>
                </span>
                <?php
                $fotoPerfil = 'img/undraw_profile.svg';
                if (!empty($_SESSION['usuario']['vFoto'])) {
                    $tipoUsuario = $_SESSION['tipoUsuario'] ?? 'paciente';
                    $rutaFoto = 'uploads/' . ($tipoUsuario === 'medico' ? 'medicos/' : 'pacientes/') . $_SESSION['usuario']['vFoto'];
                    if (file_exists($rutaFoto)) {
                        $fotoPerfil = $rutaFoto;
                    }
                }
                ?>
                <img class="img-profile rounded-circle" src="<?php echo $fotoPerfil; ?>" style="object-fit: cover;">
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="perfil.php">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="cerrar.php">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Cerrar Sesión
                </a>
            </div>
        </li>

    </ul>
</nav>