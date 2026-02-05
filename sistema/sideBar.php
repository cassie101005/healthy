<?php
// Asegurar que la sesión está activa SIEMPRE
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-text mx-3">Healthy</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item active">
        <a class="nav-link" href="index">
            <i class="fas fa-fw fa-home"></i>
            <span>Principal</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Interface</div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo">
            <i class="fas fa-fw fa-folder"></i><span>Generales</span>
        </a>
        <div id="collapseTwo" class="collapse">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Opciones:</h6>

                <?php if ($_SESSION['tipoUsuario'] === 'admin'): ?>
                    <a class="collapse-item" href="medicos">Médicos</a>
                    <a class="collapse-item" href="pacientes">Pacientes</a>

                <?php elseif ($_SESSION['tipoUsuario'] === 'medico'): ?>
                    <a class="collapse-item" href="citas_medicas">Ver citas pendientes</a>

                <?php elseif ($_SESSION['tipoUsuario'] === 'paciente'): ?>
                    <a class="collapse-item" href="nueva_cita">Nueva cita</a>
                    <a class="collapse-item" href="historial_citas">Historial de citas</a>

                    <a class="collapse-item" href="medicamentos">Medicamentos</a>
                <?php endif; ?>

                <?php if ($_SESSION['tipoUsuario'] === 'paciente'): ?>
                    <h6 class="collapse-header">Información:</h6>
                    <a class="collapse-item" href="glosario">
                        <i class="fas fa-book"></i> Glosario Técnico
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>