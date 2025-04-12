<?php
if(isset($_POST['submit'])) {
    include_once('config.php');
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $valor = $_POST['valor'];
    $mensagem = $_POST['mensagem'];
    
    $result = mysqli_query($conexao, "INSERT INTO usuarios(nome, email, telefone, valor, mensagem) 
    VALUES ('$nome', '$email', '$telefone', '$valor', '$mensagem')");
    
    if($result) {
        $success = "Orçamento enviado com sucesso! A equipe TRF Ferreira Junior entrará em contato em breve.";
    } else {
        $error = "Erro ao enviar. Por favor, tente novamente ou entre em contato diretamente conosco.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Orçamento | TRF Ferreira Junior</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" />
    <style>
        :root {
            --primary-yellow: #FFD700;
            --dark-yellow: #E6C200;
            --lighter-yellow: #FFEB85;
            --black: #121212;
            --dark-gray: #222222;
            --light-gray: #F2F2F2;
            --white: #FFFFFF;
        }
        
        body {
            background-color: var(--black);
            color: var(--white);
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 10px;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-yellow);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
            padding: 15px 0;
            border-bottom: 2px solid var(--primary-yellow);
            display: inline-block;
        }
        
        .form-container {
            background-color: var(--dark-gray);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 40px;
            margin-top: 30px;
            margin-bottom: 50px;
            border: 1px solid var(--primary-yellow);
        }
        
        .header {
            position: relative;
            margin-bottom: 40px;
            padding-bottom: 20px;
            text-align: center;
        }
        
        .header:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--primary-yellow);
        }
        
        h1 {
            color: var(--white);
            font-weight: 700;
            font-size: 2.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        h1 span {
            color: var(--primary-yellow);
        }
        
        label {
            color: var(--primary-yellow);
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        
        .form-control {
            border-radius: 6px;
            border: 2px solid rgba(255, 215, 0, 0.3);
            padding: 12px 15px;
            height: auto;
            font-size: 14px;
            background-color: var(--black);
            color: var(--white);
            transition: all 0.3s;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
            border-color: var(--primary-yellow);
            background-color: rgba(0, 0, 0, 0.4);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .btn-trf {
            background: var(--primary-yellow);
            border: none;
            color: var(--black);
            font-weight: 700;
            padding: 14px 35px;
            border-radius: 50px;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-trf:hover {
            background: var(--dark-yellow);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4);
            color: var(--black);
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 15px;
            top: 15px;
            color: var(--primary-yellow);
        }
        
        .input-icon .form-control {
            padding-left: 45px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .alert-success {
            background-color: rgba(0, 128, 0, 0.1);
            border-color: rgba(0, 128, 0, 0.2);
            color: #98FB98;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-color: rgba(220, 53, 69, 0.2);
            color: #FF6B6B;
        }
        
        .required-field::after {
            content: ' *';
            color: var(--primary-yellow);
            font-weight: bold;
        }
        
        .section-divider {
            height: 2px;
            background: linear-gradient(to right, rgba(255, 215, 0, 0), rgba(255, 215, 0, 0.7), rgba(255, 215, 0, 0));
            margin: 30px 0;
        }
        
        .contact-info {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            border-radius: 6px;
            background-color: rgba(255, 215, 0, 0.1);
        }
        
        .contact-info p {
            margin-bottom: 5px;
            color: var(--light-gray);
        }
        
        .contact-info i {
            color: var(--primary-yellow);
            margin-right: 8px;
        }
        
        .floating-label {
            position: absolute;
            top: -10px;
            left: 15px;
            background-color: var(--dark-gray);
            padding: 0 10px;
            font-size: 0.75rem;
        }
        
        @media (max-width: 767px) {
            .form-container {
                padding: 25px 15px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
        }
        .form-control {
            border-radius: 6px;
            border: 2px solid rgba(255, 215, 0, 0.3);
            padding: 12px 15px;
            height: auto;
            font-size: 14px;
            background-color: var(--black);
            color: var(--white);
            transition: all 0.3s;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
            border-color: var(--primary-yellow);
            background-color: rgba(0, 0, 0, 0.4);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .btn-trf {
            background: var(--primary-yellow);
            border: none;
            color: var(--black);
            font-weight: 700;
            padding: 14px 35px;
            border-radius: 50px;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-trf:hover {
            background: var(--dark-yellow);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4);
            color: var(--black);
        }
        
        .btn-whatsapp {
            background-color: #25D366;
            color: white;
            border: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .btn-whatsapp:hover {
            background-color: #128C7E;
            transform: translateY(-2px);
            color: white;
        }
        
        .btn-email {
            background-color: #D44638;
            color: white;
            border: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-email:hover {
            background-color: #B23121;
            transform: translateY(-2px);
            color: white;
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 15px;
            top: 15px;
            color: var(--primary-yellow);
        }
        
        .input-icon .form-control {
            padding-left: 45px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .alert-success {
            background-color: rgba(0, 128, 0, 0.1);
            border-color: rgba(0, 128, 0, 0.2);
            color: #98FB98;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-color: rgba(220, 53, 69, 0.2);
            color: #FF6B6B;
        }
        
        .required-field::after {
            content: ' *';
            color: var(--primary-yellow);
            font-weight: bold;
        }
        
        .section-divider {
            height: 2px;
            background: linear-gradient(to right, rgba(255, 215, 0, 0), rgba(255, 215, 0, 0.7), rgba(255, 215, 0, 0));
            margin: 30px 0;
        }
        
        .contact-info {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            border-radius: 6px;
            background-color: rgba(255, 215, 0, 0.1);
        }
        
        .contact-info p {
            margin-bottom: 5px;
            color: var(--light-gray);
        }
        
        .contact-info i {
            color: var(--primary-yellow);
            margin-right: 8px;
        }
        
        .floating-label {
            position: absolute;
            top: -10px;
            left: 15px;
            background-color: var(--dark-gray);
            padding: 0 10px;
            font-size: 0.75rem;
        }
        
        .contact-preference {
            background-color: rgba(255, 215, 0, 0.05);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px dashed rgba(255, 215, 0, 0.3);
        }
        
        .contact-preference h5 {
            color: var(--primary-yellow);
            margin-bottom: 15px;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .custom-control-label {
            color: var(--white);
            text-transform: none;
            font-weight: normal;
            font-size: 0.9rem;
        }
        
        .custom-radio .custom-control-input:checked ~ .custom-control-label::before {
            background-color: var(--primary-yellow);
            border-color: var(--primary-yellow);
        }
        
        .alt-contact {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            margin-top: 20px;
        }
        
        .alt-contact p {
            color: var(--light-gray);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        @media (max-width: 767px) {
            .form-container {
                padding: 25px 15px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .btn-whatsapp, .btn-email {
                width: 100%;
                justify-content: center;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="logo-container">
                    <h2 class="logo">TFJ Ferreira Junior</h2>
                </div>
                
                <div class="form-container">
                    <div class="header">
                        <h1>Solicite um <span>Orçamento</span></h1>
                        <p class="text-white-50 text-center">Preencha os campos abaixo e nossa equipe entrará em contato com você em breve.</p>
                    </div>
                    
                    <?php if(isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i><?php echo $success; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php endif; ?>
                    
                    <form action="formulario.php" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nome" class="required-field">Nome Completo</label>
                                    <div class="input-icon">
                                        <i class="fas fa-user"></i>
                                        <input
                                            type="text"
                                            name="nome"
                                            id="nome"
                                            class="form-control"
                                            placeholder="Seu nome completo"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="required-field">E-mail</label>
                                    <div class="input-icon">
                                        <i class="fas fa-envelope"></i>
                                        <input
                                            type="email"
                                            name="email"
                                            id="email"
                                            class="form-control"
                                            placeholder="seuemail@exemplo.com"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefone" class="required-field">Telefone/WhatsApp</label>
                                    <div class="input-icon">
                                        <i class="fas fa-phone-alt"></i>
                                        <input
                                            type="text"
                                            name="telefone"
                                            id="telefone"
                                            class="form-control"
                                            placeholder="Ex.: (11) 99999-9999"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valor">Valor Estimado (R$)</label>
                                    <div class="input-icon">
                                        <i class="fas fa-dollar-sign"></i>
                                        <input
                                            type="number"
                                            name="valor"
                                            id="valor"
                                            class="form-control"
                                            step="0.01"
                                            placeholder="Valor aproximado do projeto"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="section-divider"></div>
                        
                        <div class="form-group">
                            <label for="mensagem">Detalhes do Projeto</label>
                            <div class="input-icon">
                                <i class="fas fa-clipboard-list"></i>
                                <textarea
                                    name="mensagem"
                                    id="mensagem"
                                    class="form-control"
                                    rows="5"
                                    placeholder="Descreva o serviço desejado com o máximo de detalhes possível"
                                ></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group text-center mt-4">
                            <button name="submit" type="submit" class="btn btn-trf btn-lg">
                                <i class="fas fa-paper-plane mr-2"></i>Solicitar Orçamento
                            </button>
                        </div>
                    </form>
                    <div class="section-divider"></div>
                    
                    <div class="alt-contact">
                        <p>Prefere entrar em contato diretamente? Escolha uma das opções abaixo:</p>
                        <div class="row justify-content-center">
                            <div class="col-md-5">
                                <a href="https://wa.me/551194088-1851" target="_blank" class="btn btn-whatsapp btn-block">
                                    <i class="fab fa-whatsapp mr-2"></i> Enviar WhatsApp
                                </a>
                            </div>
                            <div class="col-md-5">
                                <a href="mailto:transpfj@gmail.com" class="btn btn-email btn-block">
                                    <i class="fas fa-envelope mr-2"></i> Enviar E-mail
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-divider"></div>
                    
                    <div class="contact-info">
                        <p><i class="fas fa-map-marker-alt"></i> TFJ Ferreira Junior</p>
                        <p><i class="fas fa-phone"></i> (11) 94088-1851

</p>
                        <p><i class="fas fa-envelope"></i> transpfj@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <!-- Input Mask -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            // Aplicar máscara ao telefone
            $('#telefone').mask('(00) 00000-0000');
            
            // Efeito de foco nos campos
            $('.form-control').focus(function() {
                $(this).parent().addClass('input-focus');
            }).blur(function() {
                $(this).parent().removeClass('input-focus');
            });
            
            // Validação de formulário
            $('form').submit(function(e) {
                let isValid = true;
                
                // Verificar campos obrigatórios
                $(this).find('[required]').each(function() {
                    if ($(this).val() === '') {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    $('<div class="alert alert-danger mt-3">Por favor, preencha todos os campos obrigatórios.</div>')
                        .insertBefore($(this).find('button[type="submit"]').parent())
                        .fadeOut(5000);
                }
            });
        });
    </script>
</body>
</html>