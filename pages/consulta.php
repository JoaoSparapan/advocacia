<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";

if (AuthController::getUser() == null) {
    header("location:./login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css">
    
    <style>
.navbar-logo {
    height: 70px;        /* mesma altura da navbar */
    overflow: hidden;    /* corta só o logo */
    display: flex;
    align-items: center;
}
</style>
    <link rel="stylesheet" href="../styles/css/consulta-processual.css">
    <link rel="stylesheet" href="../styles/css/navbar.css">
    <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
    <title>Consulta Processual</title>
</head>

<body>
    <?php include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/components/navbar.php"; ?>

    <main class="consulta-main">
        <div class="hero">
            <h2>Acompanhe o processo<br>em qualquer tribunal</h2>
            <p>Informe o número CNJ — o sistema identifica o tribunal competente e abre o portal oficial com o número já copiado para colar.</p>
        </div>

        <div class="search-card">
            <div class="card">
                <div class="card-title">Identificação do Processo</div>
                <label class="lbl" for="cnj-inp">Número CNJ do Processo</label>
                <div class="inp-wrap">
                    <input id="cnj-inp" class="cnj-inp" type="text"
                        placeholder="0000000-00.0000.0.00.0000"
                        maxlength="25" autocomplete="off" spellcheck="false" inputmode="numeric">
                    <span id="sico" class="sico"></span>
                </div>
                <div class="hint">
                    Formato: <strong>NNNNNNN-DD.AAAA.J.TT.OOOO</strong>
                    &middot; Ex: <strong>5003632-91.2018.4.03.6112</strong>
                </div>
                <div class="frow">
                    <div class="fwrap">
                        <label>Segmento de Justiça</label>
                        <select id="seg-sel">
                            <option value="">Todos os segmentos</option>
                            <option value="1">STF (J1)</option>
                            <option value="9">Superior — STJ (J9)</option>
                            <option value="4">Federal — TRF / JF (J4)</option>
                            <option value="5">Trabalhista — TRT (J5)</option>
                            <option value="8">Estadual — TJ (J8)</option>
                            <option value="2">Eleitoral — TSE / TRE (J2)</option>
                            <option value="6">Militar Federal — STM (J6)</option>
                            <option value="7">Militar Estadual (J7)</option>
                        </select>
                    </div>
                    <div class="fwrap">
                        <label>Tribunal Específico</label>
                        <select id="tri-sel">
                            <option value="">Todos os tribunais</option>
                        </select>
                    </div>
                </div>
                <button id="btn-search" class="btn-search" disabled>
                    <span id="btxt">Consultar Processo</span>
                    <div id="spin" class="spinner"></div>
                </button>
            </div>
        </div>

        <div id="prog-wrap" class="prog-wrap">
            <div class="prog-bg"><div id="prog-fill" class="prog-fill"></div></div>
            <div id="prog-txt" class="prog-txt"></div>
        </div>

        <div class="psec" id="portal-panel">
            <div class="sechead">
                <div class="sechead__group">
                    <div class="sectitle">Portais de Consulta</div>
                    <div id="pcnt" class="pcnt">—</div>
                </div>
                <button id="portal-toggle" class="portal-toggle" type="button" aria-expanded="false">
                    <span class="label">Mostrar portais</span>
                    <span class="chevron">▾</span>
                </button>
            </div>
            <div class="portal-content collapsed" id="portal-content">
                <div class="acts">
                    <button class="bsmall" type="button" onclick="selAll()">&#10003; Todos</button>
                    <button class="bsmall" type="button" onclick="selNone()">&#10005; Nenhum</button>
                    <button class="bsmall" type="button" onclick="selSeg()">&#128269; Filtrar segmento</button>
                    <button class="bsmall" type="button" onclick="clearRes()">&#128465; Limpar resultados</button>
                </div>
                <div id="pgrid" class="pgrid"></div>
            </div>
        </div>

        <div id="rsec" style="display:none;padding-bottom:3rem">
            <div style="max-width:1100px;margin:0 auto 1rem;padding:0 1.2rem;display:flex;align-items:center;justify-content:space-between">
                <div class="sectitle">Resultado da Consulta</div>
            </div>
            <div id="rlist" style="max-width:1100px;margin:0 auto;padding:0 1.2rem"></div>
        </div>

        <div id="toast"></div>
    </main>

    <script src="../js/consulta.js" defer></script>
</body>

</html>
