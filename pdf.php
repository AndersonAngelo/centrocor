<?php
    session_start();
    include_once('conexao.php');
    // print_r($_SESSION);
    if((!isset($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true))
    {
        unset($_SESSION['usuario']);
        unset($_SESSION['senha']);
        header('Location: index.php');
    }
    $logado = $_SESSION['usuario'];
    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $sql = "SELECT * FROM arquivos WHERE id LIKE '%$data%' or nome LIKE '%$data%' or prontuario LIKE '%$data%' or nome_documento LIKE '%$data%' ORDER BY id DESC";
    }
    else
    {
        $sql = "SELECT * FROM arquivos ORDER BY id DESC";
    }
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>consulta paciente</title>
    <style>
        body{
            background: linear-gradient(to right, rgb(20, 147, 220), rgb(17, 54, 71));
            color: white;
            text-align: center;
            
        }
        .table-bg{
            background: rgba(0, 0, 0, 0.3);
            border-radius: 15px 15px 0 0;
        }

        .box-search{
            display: flex;
            justify-content: center;
            gap: .1%;
        }

        .Input{
            position: absolute;
            color: white;
            top: 26%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.3);
            padding: 10px;
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="sistema.php">Consulta paciente</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="d-flex">
        <a href="resultado.php" class="btn btn-warning me-5">Vizualizar</a>
        <a href="home.php" class="btn btn-warning me-5">Voltar</a>
        <a href="importar.php" class="btn btn-danger me-5">Importar</a>
        <a href="formulario.php" class="btn btn-light me-5">Adicionar</a>
        <a href="sair.php" class="btn btn-danger me-5">Sair</a>
            
        </div>
    </nav>
    <br>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Upload</title>
</head>

<body>

    <h1>Enviar exame para sistema</h1><br><br>

    <?php
        // Receber os dados do formulario
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Acessa IF quando o usuário clica no botao
        if(!empty($dados['CadArquivoPdf'])){
            //var_dump($dados);

            // Receber o arquivo PDF do formulario
            $arquivo_pdf = $_FILES['arquivo_pdf'];
            //var_dump($arquivo_pdf);

            // validar se he arquivo PDF
            if($arquivo_pdf['type'] == "application/pdf"){
                // Converter o arquivo para blob
                $arquivo_pdf_blob = file_get_contents($arquivo_pdf['tmp_name']);

                $query_arquivo = "INSERT INTO arquivos (prontuario, nome_documento, arquivo_pdf) VALUES (:prontuario, :nome_documento, :arquivo_pdf)";
                $cad_arquivo = $conn->prepare($query_arquivo);
                $cad_arquivo->bindParam(':prontuario', $dados['prontuario']);
                $cad_arquivo->bindParam(':nome_documento', $arquivo_pdf['name']);
                $cad_arquivo->bindParam(':arquivo_pdf', $arquivo_pdf_blob);
                $cad_arquivo->execute();

                if($cad_arquivo->rowCount()){
                    echo "<p style='color: white;'>Arquivo cadastrado com sucesso!</p>";
                }else{
                    echo "<p style='color: #f00;'>Erro: Arquivo não cadastrado com sucesso!</p>";
                }

            }else{
                echo "<p style='color: #f00;'>Erro: Extensão do arquivo inválido. Necessário enviar arquivo PDF!</p>";
            }

        }
    ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label>Número do Prontuario:</label>
        <input type="text" name="prontuario" placeholder="prontuario"><br><br>

        <label>Procurar Arquivo PDF: </label>
        <input type="file" name="arquivo_pdf"><br><br>

        <input type="submit" name="CadArquivoPdf" value="Enviar"><br><br>
    </form>

</body>

</html>