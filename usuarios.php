<?php
session_start();
include_once('config.php');

// Removemos as credenciais de admin e a verificação de login
// $usuario_admin = "admin";
// $senha_admin = "senha123";
// if (!isset($_SESSION['admin'])) { ... }

// Caso seja necessário, você pode manter o tratamento do formulário para inserir dados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $mensagem = $_POST['mensagem'];
    $valor = isset($_POST['valor']) && !empty($_POST['valor']) ? $_POST['valor'] : null;

    // Preparar e executar a consulta SQL para inserir os dados
    $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, telefone, mensagem, valor, data_cadastro) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $nome, $email, $telefone, $mensagem, $valor);

    if ($stmt->execute()) {
        $insercao_sucesso = "Usuário cadastrado com sucesso!";
    } else {
        $insercao_erro = "Erro ao cadastrar usuário: " . $stmt->error;
    }

    $stmt->close();
}

// Consultas para exibir dados no painel
$result_informado = $conexao->query("SELECT COUNT(*) as total FROM usuarios WHERE valor IS NOT NULL AND valor <> '' AND valor > 0");
$row_informado = $result_informado->fetch_assoc();
$count_informado = $row_informado['total'];

$result_nao = $conexao->query("SELECT COUNT(*) as total FROM usuarios WHERE valor IS NULL OR valor = '' OR valor <= 0");
$row_nao = $result_nao->fetch_assoc();
$count_nao = $row_nao['total'];

$dados_mensais = [
    'Jan' => 2,
    'Fev' => 5,
    'Mar' => 8,
    'Abr' => 12,
    'Mai' => 15,
    'Jun' => 10
];

$valores_mensais = [
    'Jan' => 1000,
    'Fev' => 2500,
    'Mar' => 3200,
    'Abr' => 4800,
    'Mai' => 6000,
    'Jun' => 4000
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css" rel="stylesheet">
    <style>
        /* Seu CSS permanece inalterado */
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(180deg, #343a40, #212529);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        .content.expanded {
            margin-left: 70px;
        }
        .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            text-align: center;
        }
        .sidebar-menu {
            padding: 0;
            list-style: none;
        }
        .sidebar-menu li {
            padding: 10px 20px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar-menu li:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: #ffc107;
        }
        .sidebar-menu li.active {
            background: rgba(255,255,255,0.1);
            border-left-color: #ffc107;
        }
        .sidebar-menu a {
            color: #adb5bd;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .sidebar-menu a:hover {
            color: white;
        }
        .sidebar-menu .active a {
            color: white;
        }
        .sidebar-menu .icon {
            width: 30px;
            text-align: center;
            margin-right: 10px;
        }
        .sidebar-menu .menu-text {
            transition: all 0.3s;
        }
        .sidebar.collapsed .menu-text {
            display: none;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid #f1f1f1;
            font-weight: bold;
            border-radius: 10px 10px 0 0 !important;
        }
        .stats-card {
            text-align: center;
            padding: 20px 10px;
        }
        .stats-card i {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
        }
        .stats-text {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .table {
            color: #343a40;
        }
        .table th {
            border-top: none;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }
        .badge-status {
            padding: 8px 12px;
            border-radius: 50px;
            font-weight: normal;
        }
        .toggle-sidebar {
            position: fixed;
            left: 250px;
            top: 10px;
            background: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1001;
            cursor: pointer;
            transition: all 0.3s;
        }
        .toggle-sidebar.collapsed {
            left: 70px;
        }
        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        .user-dropdown img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .search-box {
            position: relative;
        }
        .search-box input {
            border-radius: 50px;
            padding-left: 40px;
            background-color: #f8f9fa;
            border: none;
        }
        .search-box i {
            position: absolute;
            left: 15px;
            top: 12px;
            color: #adb5bd;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            .content {
                margin-left: 70px;
            }
            .sidebar .menu-text {
                display: none;
            }
            .toggle-sidebar {
                left: 70px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4 class="sidebar-brand" id="sidebar-brand">AdminPanel</h4>
            <div id="sidebar-brand-small" style="display:none;"><i class="fas fa-tachometer-alt"></i></div>
        </div>
        <ul class="sidebar-menu mt-4">
            <li class="active">
                <a href="#">
                    <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="icon"><i class="fas fa-users"></i></span>
                    <span class="menu-text">Usuários</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="icon"><i class="fas fa-chart-bar"></i></span>
                    <span class="menu-text">Relatórios</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="icon"><i class="fas fa-cog"></i></span>
                    <span class="menu-text">Configurações</span>
                </a>
            </li>
            <li>
                <a href="logout.php">
                    <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="menu-text">Sair</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Toggle Sidebar Button -->
    <div class="toggle-sidebar" id="toggle-sidebar">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Main Content -->
    <div class="content" id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">Dashboard</span>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="search-box ml-auto mr-3">
                        <i class="fas fa-search"></i>
                        <input type="text" class="form-control" placeholder="Pesquisar...">
                    </div>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                                <img src="https://via.placeholder.com/35" alt="Admin">
                                <span>Admin</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="fas fa-user mr-2"></i> Perfil</a>
                                <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i> Configurações</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i> Sair</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <?php if (isset($insercao_sucesso)): ?>
            <div class="alert alert-success"><?php echo $insercao_sucesso; ?></div>
        <?php endif; ?>
        <?php if (isset($insercao_erro)): ?>
            <div class="alert alert-danger"><?php echo $insercao_erro; ?></div>
        <?php endif; ?>

        <!-- Dashboard Overview -->
        <div class="row">
            <div class="col-md-3">
                <div class="card stats-card">
                    <i class="fas fa-users text-primary"></i>
                    <div class="stats-number"><?php echo $count_informado + $count_nao; ?></div>
                    <div class="stats-text">Total de Usuários</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <i class="fas fa-chart-line text-success"></i>
                    <div class="stats-number">R$ <?php // Você precisaria calcular o faturamento total do banco de dados ?></div>
                    <div class="stats-text">Faturamento Total</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <i class="fas fa-tasks text-warning"></i>
                    <div class="stats-number"><?php echo $count_nao; ?></div>
                    <div class="stats-text">Orçamentos Sem Valor</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <i class="fas fa-check-circle text-info"></i>
                    <div class="stats-number"><?php echo $count_informado; ?></div>
                    <div class="stats-text">Orçamentos Com Valor</div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Novos Usuários</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                                Este Ano
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Este Mês</a>
                                <a class="dropdown-item" href="#">Últimos 3 Meses</a>
                                <a class="dropdown-item" href="#">Este Ano</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="userChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Análise de Orçamentos (Barra)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="budgetChartBar" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Table -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Listagem de Usuários</h5>
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addUserModal">
                    <i class="fas fa-plus mr-1"></i> Adicionar Usuário
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Valor</th>
                            <th>Mensagem</th>
                            <th>Data Cadastro</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sql_usuarios = "SELECT * FROM usuarios ORDER BY id DESC";
                        $result_usuarios = $conexao->query($sql_usuarios);

                        if ($result_usuarios->num_rows > 0) {
                            while ($row_usuario = $result_usuarios->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row_usuario['id'] . "</td>";
                                echo "<td>" . $row_usuario['nome'] . "</td>";
                                echo "<td>" . $row_usuario['email'] . "</td>";
                                echo "<td>" . $row_usuario['telefone'] . "</td>";
                                echo "<td>" . ($row_usuario['valor'] !== null ? 'R$ ' . number_format($row_usuario['valor'], 2, ',', '.') : 'Não Informado') . "</td>";
                                echo "<td>" . $row_usuario['mensagem'] . "</td>";
                                echo "<td>";
                                echo '<div class="btn-group">';
                                echo '<button type="button" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>';
                                echo '<button type="button" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash-alt"></i></button>';
                                echo '<button type="button" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></button>';
                                echo '</div>';
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>Nenhum usuário cadastrado.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Anterior</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Próximo</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <footer class="mt-5 text-center text-muted">
            <p>© <?php echo date('Y'); ?> Sistema Administrativo. Todos os direitos reservados.</p>
        </footer>
    </div>

    <!-- Modal de Adição de Usuário -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Adicionar Novo Usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="nome">Nome:</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="telefone">Telefone:</label>
                            <input type="tel" class="form-control" id="telefone" name="telefone">
                        </div>
                        <div class="form-group">
                            <label for="valor">Valor (Opcional):</label>
                            <input type="number" class="form-control" id="valor" name="valor" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="mensagem">Mensagem:</label>
                            <textarea class="form-control" id="mensagem" name="mensagem"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Usuário</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#toggle-sidebar').click(function() {
                $('#sidebar').toggleClass('collapsed');
                $('#content').toggleClass('expanded');
                $('#toggle-sidebar').toggleClass('collapsed');

                if ($('#sidebar').hasClass('collapsed')) {
                    $('#sidebar-brand').hide();
                    $('#sidebar-brand-small').show();
                } else {
                    $('#sidebar-brand').show();
                    $('#sidebar-brand-small').hide();
                }
            });

            // Gráfico de Novos Usuários
            var ctx1 = document.getElementById('userChart').getContext('2d');
            var userChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_keys($dados_mensais)); ?>,
                    datasets: [{
                        label: 'Novos Usuários',
                        data: <?php echo json_encode(array_values($dados_mensais)); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

            // Gráfico de Análise de Orçamentos (Barra)
            var ctxBudgetBar = document.getElementById('budgetChartBar').getContext('2d');
            var budgetChartBar = new Chart(ctxBudgetBar, {
                type: 'bar',
                data: {
                    labels: ['Com Valor', 'Sem Valor/Zero'],
                    datasets: [{
                        label: 'Quantidade de Orçamentos',
                        data: [<?php echo $count_informado; ?>, <?php echo $count_nao; ?>],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(255, 99, 132, 0.7)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        });
    </script>
</body>
</html>
