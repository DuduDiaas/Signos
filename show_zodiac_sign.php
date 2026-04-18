<?php include('layouts/header.php'); ?>

<?php
    // 1. RECEBER A DATA DO FORMULÁRIO
    $data_nascimento = $_POST['data_nascimento'] ?? null;

    // Validações iniciais
    if (!$data_nascimento) {
        echo '<div class="container mt-5"><div class="alert alert-danger">Erro: Nenhuma data foi fornecida!</div></div>';
        exit;
    }

    // 2. CARREGAR O ARQUIVO XML
    $signos = simplexml_load_file("signos.xml");
    
    if (!$signos) {
        echo '<div class="container mt-5"><div class="alert alert-danger">Erro: Não foi possível carregar o arquivo XML!</div></div>';
        exit;
    }

    // 3. FUNÇÕES AUXILIARES PARA MANIPULAÇÃO DE DATAS
    
    /**
     * Função para converter uma data no formato "dd/mm" para um número comparável
     * Exemplo: "21/03" se torna 0321 (março 21)
     * Isso permite comparação numérica mesmo sem o ano
     */
    function dataToDayMonth($data) {
        $partes = explode('/', $data);
        if (count($partes) == 2) {
            return intval($partes[1] . str_pad($partes[0], 2, '0', STR_PAD_LEFT));
        }
        return 0;
    }

    /**
     * Função para converter uma data no formato "yyyy-mm-dd" (do input date)
     * para formato "dd/mm" para comparação
     */
    function extrairDiaeMes($dataISO) {
        $partes = explode('-', $dataISO);
        if (count($partes) == 3) {
            $ano = $partes[0];
            $mes = $partes[1];
            $dia = $partes[2];
            return $dia . '/' . $mes;
        }
        return null;
    }

    // 4. PROCESSAR A DATA DE NASCIMENTO
    $diaeMes = extrairDiaeMes($data_nascimento);
    $dataNumerica = dataToDayMonth($diaeMes);

    // Extrair o ano para exibição
    $partes = explode('-', $data_nascimento);
    $ano_nascimento = $partes[0] ?? 'Desconhecido';

    // 5. PROCURAR O SIGNO CORRESPONDENTE
    $signo_encontrado = null;

    foreach ($signos->signo as $signo) {
        $dataInicio = (string)$signo->dataInicio;
        $dataFim = (string)$signo->dataFim;
        
        $inicioNumerica = dataToDayMonth($dataInicio);
        $fimNumerica = dataToDayMonth($dataFim);

        // NOTA: Esta lógica assume que nenhum signo ultrapassa o ano
        // (Capricórnio passa de 22/12 a 20/01, então usamos lógica especial)
        
        if ($signo->signoNome == 'Capricórnio') {
            // Capricórnio é especial, atravessa o ano
            if ($dataNumerica >= $inicioNumerica || $dataNumerica <= $fimNumerica) {
                $signo_encontrado = $signo;
                break;
            }
        } else {
            // Para outros signos, comparação normal
            if ($dataNumerica >= $inicioNumerica && $dataNumerica <= $fimNumerica) {
                $signo_encontrado = $signo;
                break;
            }
        }
    }

    // 6. VALIDAR SE UM SIGNO FOI ENCONTRADO
    if (!$signo_encontrado) {
        echo '<div class="container mt-5"><div class="alert alert-danger">Erro: Não foi possível encontrar um signo correspondente!</div></div>';
        exit;
    }
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Botão para voltar -->
            <div class="mb-4">
                <a href="index.php" class="btn btn-secondary">
                    ← Voltar
                </a>
            </div>

            <!-- Card principal do signo -->
            <div class="card shadow-xl border-0 rounded-lg overflow-hidden signo-card">
                <!-- Cabeçalho com gradiente -->
                <div class="signo-header p-5 text-white text-center">
                    <h1 class="display-3 mb-2">
                        <?php echo (string)$signo_encontrado->simbolo; ?>
                    </h1>
                    <h2 class="display-5 fw-bold mb-3">
                        <?php echo (string)$signo_encontrado->signoNome; ?>
                    </h2>
                    <p class="lead mb-0">
                        Seu signo zodiacal
                    </p>
                </div>

                <!-- Corpo do card -->
                <div class="card-body p-5">
                    <!-- Informações do usuário -->
                    <div class="alert alert-info mb-4" role="alert">
                        <strong>Data de Nascimento:</strong> <?php echo date('d/m/Y', strtotime($data_nascimento)); ?>
                        (<?php echo $ano_nascimento; ?>)
                    </div>

                    <!-- Descrição do signo -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold mb-3">Sobre <?php echo (string)$signo_encontrado->signoNome; ?></h3>
                        <p class="text-justify lead">
                            <?php echo (string)$signo_encontrado->descricao; ?>
                        </p>
                    </div>

                    <!-- Grid de informações -->
                    <div class="row g-4 mb-5">
                        <!-- Período -->
                        <div class="col-md-6">
                            <div class="info-box p-4 rounded-lg bg-light">
                                <h5 class="fw-bold text-primary mb-2">📅 Período</h5>
                                <p class="mb-0">
                                    <strong>
                                        <?php echo (string)$signo_encontrado->dataInicio; ?> 
                                        a 
                                        <?php echo (string)$signo_encontrado->dataFim; ?>
                                    </strong>
                                </p>
                            </div>
                        </div>

                        <!-- Elemento -->
                        <div class="col-md-6">
                            <div class="info-box p-4 rounded-lg bg-light">
                                <h5 class="fw-bold text-success mb-2">⚡ Elemento</h5>
                                <p class="mb-0">
                                    <strong><?php echo (string)$signo_encontrado->elemento; ?></strong>
                                </p>
                            </div>
                        </div>

                        <!-- Planeta Regente -->
                        <div class="col-md-6">
                            <div class="info-box p-4 rounded-lg bg-light">
                                <h5 class="fw-bold text-warning mb-2">🌙 Planeta Regente</h5>
                                <p class="mb-0">
                                    <strong><?php echo (string)$signo_encontrado->regente; ?></strong>
                                </p>
                            </div>
                        </div>

                        <!-- Cor de Sorte -->
                        <div class="col-md-6">
                            <div class="info-box p-4 rounded-lg bg-light">
                                <h5 class="fw-bold text-danger mb-2">🎨 Cor Representativa</h5>
                                <p class="mb-0">
                                    <strong><?php echo (string)$signo_encontrado->cor; ?></strong>
                                </p>
                            </div>
                        </div>

                        <!-- Número de Sorte -->
                        <div class="col-md-6">
                            <div class="info-box p-4 rounded-lg bg-light">
                                <h5 class="fw-bold text-info mb-2">✨ Número de Sorte</h5>
                                <p class="mb-0">
                                    <strong><?php echo (string)$signo_encontrado->numeroSorte; ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de ação -->
                    <div class="d-grid gap-2 d-sm-flex justify-content-center">
                        <a href="index.php" class="btn btn-primary btn-lg">
                            Consultar outro signo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlQZfl6FeBfiYgjYvMZEJ/BmqQme+3Y+p8gNuP+IlRH9sENBO"
    crossorigin="anonymous"></script>
</body>
</html>
