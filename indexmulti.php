<!DOCTYPE html>
<html>
<head>
    <title>Gerenciador de Páginas</title>
    <link href="public/css/materialize.min.css" rel="stylesheet">
    <style>
        .step {
            display: none;
        }
        .step.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Gerenciador de Páginas</h3>
        <form id="multi-step-form" action="gerar_paginas.php" method="post">
            <!-- Step 1: Quantas Páginas -->
            <div class="step active" id="step-1">
                <div class="input-field">
                    <input type="number" name="num_pages" id="num_pages" required>
                    <label for="num_pages">Quantas páginas?</label>
                </div>
                <button type="button" class="btn waves-effect waves-light" onclick="nextStep()">Próximo</button>
            </div>

            <!-- Step 2: Definir Páginas e Seções -->
            <div class="step" id="step-2">
                <div id="pages-section"></div>
                <button type="button" class="btn waves-effect waves-light" onclick="prevStep()">Voltar</button>
                <button type="button" class="btn waves-effect waves-light" onclick="nextStep()">Próximo</button>
            </div>

            <!-- Step 3: Inserir Conteúdo das Seções -->
            <div class="step" id="step-3">
                <div id="sections-content"></div>
                <button type="button" class="btn waves-effect waves-light" onclick="prevStep()">Voltar</button>
                <button type="submit" class="btn waves-effect waves-light">Gerar Páginas</button>
            </div>
        </form>
    </div>
    <script src="public/js/materialize.min.js"></script>
    <script>
        let currentStep = 1;
        const form = document.getElementById('multi-step-form');

        function nextStep() {
            if (validateStep(currentStep)) {
                currentStep++;
                showStep(currentStep);
            }
        }

        function prevStep() {
            currentStep--;
            showStep(currentStep);
        }

        function showStep(step) {
            const steps = document.querySelectorAll('.step');
            steps.forEach(step => step.classList.remove('active'));
            document.getElementById('step-' + step).classList.add('active');
            if (step === 2) {
                createPagesForm();
            }
            if (step === 3) {
                createSectionsForm();
            }
        }

        function validateStep(step) {
            if (step === 1) {
                const numPages = document.getElementById('num_pages').value;
                return numPages > 0;
            }
            return true;
        }

        function createPagesForm() {
            const numPages = document.getElementById('num_pages').value;
            const pagesSection = document.getElementById('pages-section');
            pagesSection.innerHTML = '';
            for (let i = 1; i <= numPages; i++) {
                pagesSection.innerHTML += `
                    <div class="input-field">
                        <input type="text" name="pagina[${i}][name]" required>
                        <label for="page_name_${i}">Nome da Página ${i}</label>
                    </div>
                    <div class="input-field">
                        <input type="number" name="pagina[${i}][num_sections]" required>
                        <label for="num_sections_${i}">Quantas Seções na Página ${i}?</label>
                    </div>
                `;
            }
            M.updateTextFields();
        }

        function createSectionsForm() {
            const formData = new FormData(form);
            const numPages = formData.get('num_pages');
            const sectionsContent = document.getElementById('sections-content');
            sectionsContent.innerHTML = '';
            for (let i = 1; i <= numPages; i++) {
                const pageName = formData.get(`pagina[${i}][name]`);
                const numSections = formData.get(`pagina[${i}][num_sections]`);
                sectionsContent.innerHTML += `<h4>${pageName}</h4>`;
                for (let j = 1; j <= numSections; j++) {
                    sectionsContent.innerHTML += `
                        <div class="input-field">
                            <textarea name="pagina[${pageName}][sections][${j}]" class="materialize-textarea" required></textarea>
                            <label for="section_${pageName}_${j}">Conteúdo da Seção ${j}</label>
                        </div>
                    `;
                }
            }
            M.updateTextFields();
            M.textareaAutoResize(document.querySelectorAll('.materialize-textarea'));
        }
    </script>
</body>
</html>
