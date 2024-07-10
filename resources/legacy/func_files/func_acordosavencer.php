<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordo_classe.php");
require_once("classes/db_parametroscontratos_classe.php");
$lAtivo      = '';
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clacordo = new cl_acordo;
$clparametroscontratos = new cl_parametroscontratos;
$clacordo->rotulo->label();
$iInstituicaoSessao = db_getsession('DB_instit');

?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAncora.widget.js"></script>

  <link href="estilos.css" rel="stylesheet" type="text/css">

</head>

<body style="background-color: #CCCCCC">

  <div class="container">

  </div>

  <!-- <fieldset style="width: 98%">
    <legend class="bold">Registros</legend> -->



  <table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td align="center" valign="top">
      <h2 style="margin-bottom: 10px;color:red;">Acordos a vencer</h2>
        <?php

        $sWhere  = " 1 = 1 ";

        $sWhere .= " and ac16_instit = {$iInstituicaoSessao} ";

        if (isset($campos) == false) {

          $campos  = "distinct acordo.ac16_sequencial, ";
          $campos .= "CASE WHEN ac16_semvigencia='t' THEN ('-')::varchar ELSE (ac16_numeroacordo || '/' || ac16_anousu)::varchar END dl_N�_Acordo, ";
          $campos .= "contratado.z01_numcgm, ";
          $campos .= "contratado.z01_nome, ";
          $campos .= "acordo.ac16_resumoobjeto::text, ";
          $campos .= "CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datainicio END ac16_datainicio, ";
          $campos .= "CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datafim END ac16_datafim, ";
          $campos .= "CAST(ac16_datafim AS date) - date '" . date("Y-m-d") . "'||' dias' as dl_Prazo, ";
          $campos .= "(CASE WHEN ac16_providencia is null THEN 'Pendente' ELSE providencia.descricao END) as dl_Providencia";
        }

        $sWhere = " cast((case WHEN ac16_datafim > ac26_data THEN ac16_datafim ELSE ac26_data END) AS date) - date '" . date("Y-m-d") . "' between 0 and 60 ";
        $sWhere .= " and  ac16_instit = " . db_getsession('DB_instit');
        $sWhere .= " and (ac16_providencia is null OR ac16_providencia in (2)) ";
        $sWhere .= " and ac16_acordosituacao = 4 ";
        $sql = $clacordo->sql_query_completo(null, $campos, 'ac16_datafim', $sWhere);

        $repassa = array();
        //die($sql);
        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);

        ?>
      </td>
    </tr>
  </table>
  <!-- </fieldset> -->
</body>

</html>

<script>
  let aTr = document.querySelectorAll('td.DBLovrotRegistrosRetornados');

  for (let count = 0; count < aTr.length; count++) {

    let idCampo = parseInt(aTr[count].id.replace('I', ''));
    let idCampoInicial = idCampo == 8 ? '00' : parseInt(idCampo.toString().substr(0, idCampo.toString().length - 1));

    if (aTr[count].cellIndex == 8) {

      let status = aTr[count].innerText.replace(/\./g, '').trim();

      /**
       * Adiciona o fundo vermelho para a primeira linha dos registros
       */

      if (idCampoInicial == '00') {

        // Pega o c�digo do acordo que est� na primeira c�lula da primeira linha
        let codigoAcordo = document.getElementById(`I${idCampoInicial}`).innerText.trim();

        for (let counta = 0; counta <= 8; counta++) {

          let campo = document.getElementById(`I0${counta}`);
          campo.bgColor = 'red';
          campo.onclick = (e) => {
            js_OpenJanelaIframe('', 'db_iframe_providencia', `aco1_providencia.php?codigo=${codigoAcordo}`, 'Provid�ncia', true, null, 550, 280, 143);
            e.preventDefault();
          }
        }

      } else {

        // Pega o c�digo do acordo que est� na primeira c�lula de cada linha
        let codigoAcordo = document.getElementById(`I${idCampoInicial}0`).innerText.trim();

        /**
         * Adiciona o fundo vermelho a partir da segunda linha
         */

         for (let counta = 0; counta <= 8; counta++) {
          let campo = document.getElementById(`I${idCampoInicial.toString() + counta}`);

          campo.bgColor = 'red';
          campo.onclick = (e) => {
            js_OpenJanelaIframe('', 'db_iframe_providencia', `aco1_providencia.php?codigo=${codigoAcordo}`, 'Provid�ncia', true, null, 550, 250, 180);
            e.preventDefault();
          }
        }

      }
    }

  }
</script>