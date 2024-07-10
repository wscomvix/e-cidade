<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
    #MesReferencia, #ExibirHistoricoDoEmpenho, #ordenar, #iTipo, #iRestosPagar {
        width: 100px;
    }
</style>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table valign="top" marginwidth="0" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <form name='form1'>
        <fieldset>
          <legend><b>Exame Aritm�tico</b></legend>
          <table>
           	<tr>
          	 <td><b>M�s Refer�ncia: </b></td>
          	 <td>
          	 <select id="MesReferencia" name="MesReferencia" >
          	  <option value="01">Janeiro</option>
          	  <option value="02">Fevereiro</option>
          	  <option value="03">Mar�o</option>
          	  <option value="04">Abril</option>
          	  <option value="05">Maio</option>
          	  <option value="06">Junho</option>
          	  <option value="07">Julho</option>
          	  <option value="08">Agosto</option>
          	  <option value="09">Setembro</option>
          	  <option value="10">Outubro</option>
          	  <option value="11">Novembro</option>
          	  <option value="12">Dezembro</option>
          	 </select>
          	 </td>
          	</tr>

          	<tr>
             <td><b>Exibir hist�rico do empenho:</b></td>
             <td>
                 <select name="ExibirHistoricoDoEmpenho" id="ExibirHistoricoDoEmpenho">
                     <option value="01">Sim</option>
                     <option value="02">N�o</option>
                 </select>
             </td>

            </tr>
              <tr>
                  <td>
                      <strong>Ordernar por:</strong>
                  </td>
                  <td>
                      <select name="ordenar" id="ordenar">
                          <option value="01">�rg�o</option>
                          <option value="02">Dota��o</option>
                          <option value="03">Reduzido</option>
                      </select> 
                  </td>
              </tr>
            <tr>
                <td>
                    <strong>Tipo:</strong>
                </td>
                <td>
                    <select name="iTipo" id="iTipo">
                        <option value="">Todos</option>
                        <option value="1">Sa�de 15%</option>
                        <option value="9">Vinculados � Sa�de</option>
                        <option value="2">Educa��o 25%</option>
                        <option value="11">Vinculados � Educa��o</option>                        
                        <option value="5">Fundeb 70%</option>
                        <option value="6">Fundeb 30%</option>
                        <option value="8">Cide</option>
                        <option value="7">Multas de Transito</option>
                        <option value="10">Vinculados � Assistencia</option>
                        <option value="4">Geral</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Restos a pagar:</strong>
                </td>
                <td>
                    <select name="iRestosPagar" id="iRestosPagar">
                        <option value="">Sim</option>
                        <option value="1">N�o</option>
                        <option value="2">Somente RP</option>
                    </select>
                </td>
            </tr>
            <tr>
             <!--  <td><b>Tipos de Pastas:</b></td>-->
             <td>
              		<?
					      $oFiltroRecursos = new cl_arquivo_auxiliar;
						  $oFiltroRecursos->cabecalho            = "<strong>Recursos</strong>";
						  $oFiltroRecursos->codigo               = "o15_codigo";
						  $oFiltroRecursos->descr                = "o15_descr";
						  $oFiltroRecursos->nomeobjeto           = 'recursos';
						  $oFiltroRecursos->funcao_js            = 'js_mostraRecurso';
						  $oFiltroRecursos->funcao_js_hide       = 'js_mostraRecursoHide';
						  $oFiltroRecursos->func_arquivo         = "func_orctiporec.php";
						  $oFiltroRecursos->nomeiframe           = "db_iframe_orctiporec";
						  $oFiltroRecursos->vwidth 				 = '400';
						  $oFiltroRecursos->db_opcao             = 2;
						  $oFiltroRecursos->tipo                 = 2;
						  $oFiltroRecursos->top 				 = 0;
						  $oFiltroRecursos->linhas 				 = 5;
						  $oFiltroRecursos->nome_botao           = 'lancarRecurso';
						  $oFiltroRecursos->lFuncaoPersonalizada = true;
						  $oFiltroRecursos->obrigarselecao       = false;
						  $oFiltroRecursos->localjan			 = '';
						  $oFiltroRecursos->funcao_gera_formulario();
				    ?>
             </td>
            </tr>

          </table>
        </fieldset>
      </form>
    </td>
  </tr>
  <tr>
    <td align='center'>
      <input name='pesquisar' type='button' value='Consultar' onclick='js_abre();'>
    </td>
  </tr>
</table>

</body>
</html>
<script>

function js_abre(){

	   obj = document.form1;
	   query  = "MesReferencia="+obj.MesReferencia.value;
	  // query += "&tipoExame="+obj.tipoExame.value;
	  // query += "&tipoPasta="+obj.tipoPasta.value;
	   query += "&recursos="+js_campo_recebe_valores_recursos ();
       query += "&ExibirHistoricoDoEmpenho="+obj.ExibirHistoricoDoEmpenho.value;
       query += "&ordenar="+obj.ordenar.value;
       query += "&iTipo="+obj.iTipo.value;
       query += "&filtros="+parent.iframe_filtro.js_atualiza_variavel_retorno();
       query += "&iRestosPagar="+obj.iRestosPagar.value;

	   jan = window.open('emp2_examearitmetico002.php?'+query,
	                 '',
	                   'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	   jan.moveTo(0,0);

	}

function mostra_tipo_pasta() {

  if (document.getElementById("tipoExame").selectedIndex == 1) {

	  document.getElementById("tipoPasta").options[1].style.visibility = "hidden";
	  document.getElementById("tipoPasta").options[2].style.visibility = "hidden";
	  document.getElementById("tipoPasta").options[3].style.visibility = "hidden";
	  document.getElementById("tipoPasta").options[4].style.visibility = "hidden";
	  document.getElementById("tipoPasta").options[5].style.visibility = "hidden";
	  parent.document.formaba.db_slip.disabled = 1;

	} else {

	  document.getElementById("tipoPasta").options[1].style.visibility = "visible";
		document.getElementById("tipoPasta").options[2].style.visibility = "visible";
		document.getElementById("tipoPasta").options[3].style.visibility = "visible";
		document.getElementById("tipoPasta").options[4].style.visibility = "visible";
		document.getElementById("tipoPasta").options[5].style.visibility = "visible";
		parent.document.formaba.db_slip.disabled = 0;

  }


}

</script>
