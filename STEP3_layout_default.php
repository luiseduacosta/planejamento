<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($this->fetch('title') ?: 'Planejamento ESS/UFRJ') ?></title>
    
    <?= $this->Html->meta('icon') ?>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YcnS/flrSBu1HjK6H6yBnBU2WBIpU1FCS+8" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <?= $this->Html->css('custom') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <?= $this->Html->link('📚 Planejamento ESS', ['controller' => 'Pages', 'action' => 'display', 'home'], [
                'class' => 'navbar-brand fw-bold'
            ]) ?>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <?= $this->Html->link('<i class="bi bi-calendar-week"></i> Planejamentos', ['controller' => 'Planejamentos', 'action' => 'listar'], [
                            'class' => 'nav-link',
                            'escape' => false
                        ]) ?>
                    </li>
                    <li class="nav-item">
                        <?= $this->Html->link('<i class="bi bi-people"></i> Docentes', ['controller' => 'Docentes', 'action' => 'index'], [
                            'class' => 'nav-link',
                            'escape' => false
                        ]) ?>
                    </li>
                    <li class="nav-item">
                        <?= $this->Html->link('<i class="bi bi-book"></i> Disciplinas', ['controller' => 'Disciplinas', 'action' => 'index'], [
                            'class' => 'nav-link',
                            'escape' => false
                        ]) ?>
                    </li>
                    <li class="nav-item">
                        <?= $this->Html->link('<i class="bi bi-building"></i> Salas', ['controller' => 'Salas', 'action' => 'index'], [
                            'class' => 'nav-link',
                            'escape' => false
                        ]) ?>
                    </li>
                    <li class="nav-item">
                        <?= $this->Html->link('<i class="bi bi-clock"></i> Horários', ['controller' => 'Horarios', 'action' => 'index'], [
                            'class' => 'nav-link',
                            'escape' => false
                        ]) ?>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear"></i> Configurações
                        </a>
                        <ul class="dropdown-menu">
                            <li><?= $this->Html->link('Semestres', ['controller' => 'Configuraplanejamentos', 'action' => 'index'], ['class' => 'dropdown-item']) ?></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><?= $this->Html->link('Ementas', ['controller' => 'Ementas', 'action' => 'index'], ['class' => 'dropdown-item']) ?></li>
                            <li><?= $this->Html->link('Optativas', ['controller' => 'Optativas', 'action' => 'index'], ['class' => 'dropdown-item']) ?></li>
                        </ul>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php
                    $identity = $this->request->getAttribute('identity');
                    if ($identity):
                    ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> 
                                <span class="d-none d-lg-inline"><?= h($identity->username) ?></span>
                                <?php if ($identity->role === 'admin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?= h($identity->role) ?></span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <?= $this->Html->link('<i class="bi bi-person"></i> Meu Perfil', ['controller' => 'Users', 'action' => 'profile'], [
                                        'class' => 'dropdown-item',
                                        'escape' => false
                                    ]) ?>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <?= $this->Html->link('<i class="bi bi-box-arrow-right"></i> Sair', ['controller' => 'Users', 'action' => 'logout'], [
                                        'class' => 'dropdown-item text-danger',
                                        'escape' => false
                                    ]) ?>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="bi bi-box-arrow-in-right"></i> Login', ['controller' => 'Users', 'action' => 'login'], [
                                'class' => 'nav-link',
                                'escape' => false
                            ]) ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container-fluid container-lg mt-3">
        <?= $this->Flash->render() ?>
        
        <!-- Breadcrumb (optional) -->
        <?php if ($this->fetch('breadcrumb')): ?>
        <nav aria-label="breadcrumb" class="mb-3">
            <?= $this->fetch('breadcrumb') ?>
        </nav>
        <?php endif; ?>
        
        <!-- Main Content -->
        <main class="my-4">
            <?= $this->fetch('content') ?>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center text-muted py-4 mt-auto border-top">
        <div class="container">
            <p class="mb-1">&copy; <?= date('Y') ?> <strong>ESS/UFRJ</strong> - Sistema de Planejamento Acadêmico</p>
            <small class="text-muted">Escola de Serviço Social - Universidade Federal do Rio de Janeiro</small>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <?= $this->fetch('script') ?>
</body>
</html>
