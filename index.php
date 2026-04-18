<?php include('layouts/header.php'); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Título Principal -->
            <div class="text-center mb-5">
                <h1 class="display-4 text-gradient mb-3">Descubra seu signo!</h1>
                <p class="lead text-muted">Insira sua data de nascimento e descubra tudo sobre seu signo zodiacal</p>
            </div>

            <!-- Card do Formulário -->
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body p-5">
                    <form id="signo-form" method="POST" action="show_zodiac_sign.php" class="needs-validation" novalidate>
                        <!-- Campo de Data -->
                        <div class="mb-4">
                            <label for="data_nascimento" class="form-label fw-bold">Data de Nascimento:</label>
                            <input 
                                type="date" 
                                class="form-control form-control-lg" 
                                id="data_nascimento" 
                                name="data_nascimento"
                                required
                                placeholder="dd/mm/aaaa"
                            >
                            <small class="form-text text-muted d-block mt-2">
                                Ex: 21/05/1992
                            </small>
                        </div>

                        <!-- Botão de Envio -->
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                            Descobrir meu signo
                        </button>
                    </form>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="alert alert-info mt-4" role="alert">
                <strong>💡 Dica:</strong> A data deve estar dentro do intervalo válido do seu signo zodiacal.
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlQZfl6FeBfiYgjYvMZEJ/BmqQme+3Y+p8gNuP+IlRH9sENBO"
    crossorigin="anonymous"></script>

<!-- Script de Validação -->
<script>
    // Validação do formulário
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    }());

    // Validação adicional de data
    document.getElementById('signo-form').addEventListener('submit', function(e) {
        const dataNascimento = document.getElementById('data_nascimento').value;
        
        if (!dataNascimento) {
            e.preventDefault();
            alert('Por favor, insira uma data válida!');
            return false;
        }

        // Validar se a data não é no futuro
        const data = new Date(dataNascimento);
        const hoje = new Date();
        
        if (data > hoje) {
            e.preventDefault();
            alert('A data de nascimento não pode ser no futuro!');
            return false;
        }
    });
</script>
</body>
</html>
