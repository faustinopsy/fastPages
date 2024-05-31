<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Gerenciador de Páginas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
                <link href="public/css/materialize.min.css" rel="stylesheet">
                <link href="public/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
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
<nav class="teal lighten-1" role="navigation">
                  <div class="nav-wrapper container">
                  <a id="logo-container" href="#" class="brand-logo"><i class="material-icons">all_inclusive</i>FastPages</a>
                  <ul class="right hide-on-med-and-down">
                  </ul>
                </div></nav>
    <div class="container">
        <h3>Gerenciador de Páginas</h3>
        <form id="multi-step-form" action="gerar_paginas.php" method="post" enctype="multipart/form-data">
            <div class="step active" id="step-1">
                <div class="input-field">
                    <input type="number" name="num_pages" id="num_pages" required>
                    <label for="num_pages">Quantas páginas?</label>
                </div>
                <button type="button" class="btn waves-effect waves-light" onclick="nextStep()">Próximo</button>
            </div>

            <div class="step" id="step-2">
                <div id="pages-section"></div>
                <button type="button" class="btn waves-effect waves-light" onclick="prevStep()">Voltar</button>
                <button type="button" class="btn waves-effect waves-light" onclick="nextStep()">Próximo</button>
            </div>

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
                console.log(pageName)
                const numSections = formData.get(`pagina[${i}][num_sections]`);
                sectionsContent.innerHTML += `<h4>${pageName}</h4>`;
                for (let j = 1; j <= numSections; j++) {
                    sectionsContent.innerHTML += `
                        <div class="input-field">
                            <select name="pagina[${pageName}][sections][${j}][type]" onchange="mostrarSecaoConteudo(this, '${pageName}', ${j})" required>
                                <option value="" disabled selected>Escolha o tipo de conteúdo</option>
                                <option value="text">Texto</option>
                                <option value="image">Imagem</option>
                                <option value="form">Formulário de Contato</option>
                            </select>
                            <label for="section_${pageName}_${j}_type">Tipo de Conteúdo para Seção ${j}</label>
                        </div>
                        <div id="section_${pageName}_${j}_content"></div>
                    `;
                }
            }
            M.updateTextFields();
            M.FormSelect.init(document.querySelectorAll('select'));
        }

        function mostrarSecaoConteudo(select, pageName, sectionNumber) {
            console.log(pageName)
            const sectionContentDiv = document.getElementById(`section_${pageName}_${sectionNumber}_content`);
            sectionContentDiv.innerHTML = '';
            if (select.value === 'text') {
                sectionContentDiv.innerHTML = `
                    <div class="input-field">
                        <textarea name="pagina[${pageName}][sections][${sectionNumber}][content]" class="materialize-textarea" required></textarea>
                        <label for="section_${pageName}_${sectionNumber}_content">Conteúdo da Seção ${sectionNumber}</label>
                    </div>
                `;
                M.updateTextFields();
            } else if (select.value === 'image') {
                sectionContentDiv.innerHTML = `
                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Upload</span>
                            <input type="file" name="pagina[${pageName}][sections][${sectionNumber}][content]" accept="image/*" required>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Envie uma imagem">
                        </div>
                    </div>
                `;
            } else if (select.value === 'form') {
                sectionContentDiv.innerHTML = `
                    <p>Formulário de Contato Padrão será gerado.</p>
                `;
            }
        }

    </script>
</body>
</html>
