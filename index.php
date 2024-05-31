<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Gerenciador de Páginas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link href="public/css/materialize.css" rel="stylesheet">
    <link href="public/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <style>
.step {
    display: none;
}
.step.active {
    display: block;
}
#splashScreen {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #rgba(0, 0, 25, 0.6);
    backdrop-filter: blur(20px);
    z-index: 9999;
  }
  
  #loading {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 200px;
    transform: translate(-50%, -50%);
    text-align: center;
  }
  
  #loadingBar {
    width: 80%;
    height: 20px;
    margin: 20px auto;
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
  }
  
  #loadingPercentage {
    width: 0%;
    height: 100%;
    background-color: yellow;
    border-top: solid 8px teal;
    border-bottom: solid 8px blue;
  }
    </style>
</head>
<body>
<?php
$results = [];
function limit_requests($ip, $limit, $time_window) {
    $requests = file_get_contents('requests.json');
    $requests = json_decode($requests, true);

    if (!isset($requests[$ip])) {
        $requests[$ip] = array(time());
    } else {
        foreach ($requests[$ip] as $key => $time) {
            if ($time < time() - $time_window) {
                unset($requests[$ip][$key]);
            }
        }
        if (count($requests[$ip]) >= $limit) {
            return false;
        }
        $requests[$ip][] = time();
    }
    file_put_contents('requests.json', json_encode($requests));
    return true;
}
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$ip = $_SERVER['REMOTE_ADDR'];
$dia=3600*24;
$details = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
if (!limit_requests($ip, 5, $dia)) {
    $results = "
    <div id='menu-container'></div>
    <div id='splashScreen' >
    <div id='loading'>
        <p>Você excedeu o limite de solicitações. </p> 
            <div class='progress'>
            <div class='indeterminate'></div>
        </div>
        <p>Por favor volte, amanhã</p>
    </div>
</div>
<script src='js/menuAndFooter.js'></script>
<div id='footer-container'></div>
    ";
    echo $results;
   exit; 
}
?>
<div id="menu-container"></div>
<div class="container">
    <h3>Gerenciador de Páginas</h3>
    <form id="multi-step-form" action="gerar_paginas.php" method="post" enctype="multipart/form-data">
        <div class="step active" id="step-1" style="height:250px">
            <div class="input-field">
                <input type="number" name="num_pages" id="num_pages" required>
                <label for="num_pages">Quantas páginas?</label>
            </div>
            <button type="button" class="btn waves-effect waves-light" onclick="nextStep()">Próximo <i class="material-icons">arrow_forward</i></button>
        </div>

        <div class="step" id="step-2">
            <div id="pages-section"></div>
            <button type="button" class="btn waves-effect waves-light" onclick="prevStep()"><i class="material-icons">arrow_back</i> Voltar</button>
            <button type="button" class="btn waves-effect waves-light" onclick="nextStep()">Próximo <i class="material-icons">arrow_forward</i></button>
        </div>

        <div class="step" id="step-3">
            <div id="sections-content"></div>
            <button type="button" class="btn waves-effect waves-light" onclick="prevStep()"><i class="material-icons">arrow_back</i> Voltar</button>
            <button type="submit" class="btn waves-effect waves-light">Gerar Páginas <i class="material-icons">cloud_download</i></button>
        </div>
    </form>
</div>

<script src="public/js/materialize.min.js"></script>
<script src="js/menuAndFooter.js"></script>
        <div id="footer-container"></div>
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
        } else if (step === 2) {
            const pagesSection = document.getElementById('pages-section');
            const inputs = pagesSection.querySelectorAll('input');
            for (let input of inputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return false;
                }
            }
        } else if (step === 3) {
            const sectionsContent = document.getElementById('sections-content');
            const inputs = sectionsContent.querySelectorAll('input, textarea, select');
            for (let input of inputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return false;
                }
            }
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
                        <select name="pagina[${pageName}][sections][${j}][type]" onchange="showSectionContentFields(this, '${pageName}', ${j})" required>
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

    function showSectionContentFields(select, pageName, sectionNumber) {
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
