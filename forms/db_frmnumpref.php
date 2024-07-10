<?php
use App\Models\InstituicaoFinanceiraApiPix;

$clnumpref->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k06_descr");
$clrotulo->label("k00_descr");
$clrotulo->label("nomeinst");
$clrotulo->label("z01_nome");
$clrotulo->label("rh37_descr");
$clrotulo->label("k03_receitapadraocredito");
$clrotulo->label("k02_drecei");
$clrotulo->label("k03_diasvalidadecertidao");
$clrotulo->label("k03_diasreemissaocertidao");

if (!DBNumber::isInteger( trim($k03_diasreemissaocertidao))) {
	$k03_diasreemissaocertidao = '';
}

if (!DBNumber::isInteger( trim($k03_diasvalidadecertidao))) {
	$k03_diasvalidadecertidao = '';
}
?>
<form name="form1" method="post" action="" onSubmit="return validaCampo();">
<!-- financeiro -->
<table width="100%">
	<tr>
		<td>
		  <fieldset>
        <legend>
          <b>Financeiro</b>
        </legend>
    		<table border="0" width="100%">

    			<tr>
    				<td title="<?=@$Tk03_anousu?>" width="35%">
    				  <?=@$Lk03_anousu?>
    				</td>
    				<td colspan='4'>
                        <?php
        				$k03_anousu = db_getsession('DB_anousu');
        				db_input('k03_anousu',15,$Ik03_anousu,true,'text',3,"");

        				db_input('k03_instit',10,$Ik03_instit,true,'hidden',$db_opcao);
        				db_input('k03_numpre',10,$Ik03_numpre,true,'hidden',$db_opcao);
    				  ?>
            </td>
    			</tr>

    			<tr>
    				<td title="<?=@$Tk03_defope?>" width="35%">
    				  <?=@$Lk03_defope?>
    				</td>
    				<td width="20%">
                        <?php
        				db_input('k03_defope',15,$Ik03_defope,true,'text',$db_opcao,"");
      				?>
            </td>
    				<td title="<?=@$Tk03_numsli?>" width="25%">
    				  <?=@$Lk03_numsli?>
    				</td>
    				<td width="20%">
                        <?php
    				    db_input('k03_numsli',15,$Ik03_numsli,true,'text',$db_opcao,"");
    				  ?>
            </td>
    			</tr>
    			<tr>
    				<td title="<?=@$Tk03_recjur?>">
    				  <?=@$Lk03_recjur?>
            </td>
    				<td>
                        <?php
    				    db_input('k03_recjur',15,$Ik03_recjur,true,'text',$db_opcao,"");
    				  ?>
            </td>
    				<td title="<?=@$Tk03_recmul?>">
    				  <?=@$Lk03_recmul?>
            </td>
    				<td >
                        <?php
    				    db_input('k03_recmul',15,$Ik03_recmul,true,'text',$db_opcao,"");
    				  ?>
            </td>
    			</tr>
    			<tr>
    				<td title="<?=@$Tk03_codbco?>">
    				  <?=@$Lk03_codbco?>
            </td>
    				<td>
                        <?php
    				    db_input('k03_codbco',15,$Ik03_codbco,true,'text',$db_opcao,"");
      				?>
            </td>
    				<td title="<?=@$Tk03_codage?>">
    				  <?=@$Lk03_codage?>
            </td>
    				<td>
                        <?php
    				    db_input('k03_codage',15,$Ik03_codage,true,'text',$db_opcao,"");
    				  ?>
            </td>
    			</tr>
    			<tr>
    				<td title="<?=@$Tk03_calrec?>">
    				  <?=@$Lk03_calrec?>
            </td>
    				<td>
                        <?php
      			  	$x = array("f"=>"N�O","t"=>"SIM");
      			  	db_select('k03_calrec',$x,true,$db_opcao,"style='width:115px'");
      				?>
            </td>
    				<td>
              <b>Tipo Recibo Protocolo:</b>
            </td>
    				<td>
                        <?php
    				    db_input('k03_reciboprot',15,$Ik03_reciboprot,true,'text',$db_opcao,"");
    				  ?>
            </td>
          </tr>
   				<tr>
   					<td>
              <b>
                  <?php
                  db_ancora("Tipo Recibo Reten��o: ","js_consultareciboretencao(true);",1);
                ?>
              </b>
  					</td>
   					<td colspan='4'>
                        <?php
     				 	  db_input('k03_reciboprotretencao',15,$Ik03_reciboprotretencao,true,'text',$db_opcao," onchange=js_consultareciboretencao(false);");
     					  db_input('k00_descr',48,$Ik00_descr,true,'text',3);
   					  ?>
            </td>
   			  </tr>
          <tr>
            <td>
              <b><?=@$Lk03_pgtoparcial?></b>
            </td>
            <td colspan='4'>
                <?php

                if (isset($k03_pgtoparcial) ) {
                  if ($k03_pgtoparcial == 't') {
                    $k03_pgtoparcial = "true";
                  } else {
                    $k03_pgtoparcial = "false";
                  }
                }

                $aPgtoParcial = array('true' =>'SIM',
                                      'false'=>'N�O' );

                db_select("k03_pgtoparcial",$aPgtoParcial,true,$db_opcao,"style='width:115px'");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <b><?=@$Lk03_toleranciapgtoparc?></b>
            </td>
            <td colspan='4'>
                <?php
                db_input('k03_toleranciapgtoparc',15,$Ik03_toleranciapgtoparc,true,'text',$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td  title="<?=@$Tk03_toleranciacredito?>">
              <?=@$Lk03_toleranciacredito?>
            </td>
            <td colspan='3'>
                <?php
                db_input('k03_toleranciacredito',15,$Ik03_toleranciacredito, true, 'text', $db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <b><?=@$Lk03_agrupadorarquivotxtbaixabanco?></b>
            </td>
            <td colspan='4'>
                <?php
                $aAgrupadorArquivoTxtBaixaBancoValores = getValoresPadroesCampo("k03_agrupadorarquivotxtbaixabanco");
                db_select("k03_agrupadorarquivotxtbaixabanco",$aAgrupadorArquivoTxtBaixaBancoValores,true,$db_opcao,"style='width:350px'");
              ?>
            </td>
          </tr>
        </table>
 		  </fieldset>
    </td>
 	</tr>

		<!-- final financeiro -->

    <!-- tributario -->

	<tr>
		<td>
			<fieldset align="left">
        <legend>
          <b>Tribut�rio</b>
        </legend>
			  <table border='0' width="100%">
  				<tr>
  					<td title="<?=@$Tk03_impend?>" width="35%">
  					  <?=@$Lk03_impend?>
  					</td>
  					<td width="20%">
                        <?php
    					  $x = array("f"=>"N�O","t"=>"SIM");
    					  db_select('k03_impend',$x,true,$db_opcao,"style='width:125px'");
    					?>
            </td>
  					<td title="<?=@$Tk03_unipri?>" width="25%">
  					  <?=@$Lk03_unipri?>
  					</td>
  					<td width="20%">
                        <?php
    					  $x = array("f"=>"N�O","t"=>"SIM");
    				  	db_select('k03_unipri',$x,true,$db_opcao,"style='width:125px'");
    					?>
            </td>
  				</tr>
  				<tr>
  					<td  title="<?=@$Tk03_certissvar?>">
  					  <?=@$Lk03_certissvar?>
            </td>
  					<td>
                        <?php
    				  	$x = array("f"=>"N�O","t"=>"SIM");
    				  	db_select('k03_certissvar',$x,true,$db_opcao,"style='width:125px'");
    					?>
            </td>
  					<td  title="<?=@$Tk03_diasjust?>">
  					  <?=@$Lk03_diasjust?>
            </td>
  					<td>
                        <?php
  					    db_input('k03_diasjust',16,$Ik03_diasjust,true,'text',$db_opcao,"")
  					  ?>
            </td>
  				</tr>
  				<tr>
  					<td  title="<?=@$Tk03_separajurmulparc?>">
  					  <?=@$Lk03_separajurmulparc?>
  					</td>
  					<td colspan='3'>
                        <?php
    				  	$x = array('2'=>'N�O','1'=>'SIM');
    				  	db_select('k03_separajurmulparc',$x,true,$db_opcao,"style='width:125px'");
    					?>
            </td>
          </tr>
          <tr>
  					<td title="<?=@$Tk03_respcgm?>" >
                        <?php
      					db_ancora(@$Lk03_respcgm, "js_pesquisak03_respcgm(true);", $db_opcao);
  	   				?>
            </td>
  					<td colspan="3">
                        <?php
    					  db_input('k03_respcgm', 15, $Ik03_respcgm, true, 'text', $db_opcao, " onchange='js_pesquisak03_respcgm(false)' ");
    					  db_input('z01_nome', 48, $Iz01_nome, true, 'text', 3);
    					?>
            </td>
  				</tr>
  				<tr>
  					<td ="" title="<?=@$Tk03_respcargo?>">
                    <?php
      					db_ancora(@$Lk03_respcargo, "js_pesquisak03_respcargo(true)", $db_opcao);
  	   				?>
            </td>
  					<td colspan="3">
                        <?php
    					  db_input('k03_respcargo', 15, $Ik03_respcargo, true, 'text', $db_opcao, " onchange='js_pesquisak03_respcargo(false)'" );
    					  db_input('rh37_descr', 48, $Irh37_descr, true, 'text', 3)
    					?>
            </td>
  				</tr>
  				<tr>
  					<td  title="<?=@$Tk03_taxagrupo?>">
                        <?php
  					    db_ancora(@$Lk03_taxagrupo,"js_pesquisak03_taxagrupo(true);",$db_opcao);
  					  ?>
            </td>
  					<td colspan='3'>
                        <?php
    					  db_input('k03_taxagrupo',15,$Ik03_taxagrupo,true,'text',$db_opcao," onchange='js_pesquisak03_taxagrupo(false);'");
    					  db_input('k06_descr',48,$Ik06_descr,true,'text',3,'');
    					?>
            </td>
  				</tr>
          <tr>
            <td wrap title="<?=@$Tk03_reemissaorecibo?>">
              <?=@$Lk03_reemissaorecibo?>
            </td>
            <td colspan="3">
                <?php
    					  $aReemiteRecibo = array(
           					    								'f'=>"N�o",
    					                          't'=>"Sim"
    					                         );
    					  db_select('k03_reemissaorecibo',$aReemiteRecibo,true,$db_opcao, "style='width:125px'");
    					?>
            </td>
          </tr>
						<tr>
	    				<td title="<?=@$Tk03_diasvalidadecertidao?>">
	    				  <?=@$Lk03_diasvalidadecertidao?>
	            </td>
    				<td>
                        <?php
    				    db_input('k03_diasvalidadecertidao',2,$Ik03_diasvalidadecertidao,true,'text',$db_opcao,"",'','','',2);
    				  ?>
            </td>
					 </tr>
					<tr>
	    				<td title="<?=@$Tk03_diasreemissaocertidao?>">
	    				  <?=@$Lk03_diasreemissaocertidao?>
	            </td>
    				<td>
                        <?php
    				    db_input('k03_diasreemissaocertidao',2,$Ik03_diasreemissaocertidao,true,'text',$db_opcao," onchange=js_teste();",'','','',2);
    				  ?>
            </td>
					 </tr>

          <tr>
  				  <td  title="<?=@$Tk03_tipocertidao?>" >
              <?=@$Lk03_tipocertidao?>
            </td>
            <td colspan='3'>
                <?php
                $x = array("1"=>"Conjunta","2"=>"Individualizada", "3"=>"Sele&ccedil;&atilde;o Usu&aacute;rio");
                db_select('k03_tipocertidao',$x,true,$db_opcao,"style='width:125px'");
              ?>
            </td>
          </tr>
          <tr>
  					<td  title="<?=@$Tk03_reccert?>">
  					  <?=@$Lk03_reccert?>
            </td>
  					<td colspan='3'>
                        <?php
    					  $x = array("f"=>"N�O","t"=>"SIM");
    					  db_select('k03_reccert',$x,true,$db_opcao,"style='width:125px'");
    					?>
            </td>
  				</tr>
          <tr>
            <td title="<?=@$Tk03_tipocodcert?>" >
  					  <?=@$Lk03_tipocodcert?>
  					</td>
  					<td colspan="3">
                        <?php
      					$x = array( '0'=>'N�o codifica',
                						'1'=>'Sequencial geral',
                						'2'=>'Sequencial por Institui��o',
                						'3'=>'Sequencial por Tipo de Certid�o Geral',
                						'4'=>'Sequencial por Tipo de Certid�o por Institui��o',
                						'5'=>'C�digo do Processo de Protocolo e Exerc�cio' );

      					db_select('k03_tipocodcert',$x,true,$db_opcao,"style='width:350px'");
    				  ?>
            </td>
          </tr>
  				<tr>
  					<td title="<?=@$Tk03_regracnd?>">
  					  <?=@$Lk03_regracnd?>
            </td>
  					<td colspan="3">
                        <?php
    					  $aRegraCND = array("1"=>"Verifica os d�bitos dos im�veis por matr�cula",
    					    								 "2"=>"Verifica os d�bitos dos im�veis por lote",
    							    						 "3"=>"Verifica os d�bitos dos im�veis por idbql");
    					  db_select('k03_regracnd',$aRegraCND,true,$db_opcao,"style='width:350px'");
    					?>
            </td>
  				</tr>

  				<tr>
  				  <td>
  				    <?php
  				      db_ancora($Lk03_receitapadraocredito, 'js_pesquisaReceita(true)', $db_opcao)
  				    ?>
  				  </td>
  				  <td colspan="3">
  				    <?php
  				      db_input(
                            'k03_receitapadraocredito',
                            15,
                            $Ik03_receitapadraocredito,
                            true,
                            'text',
                            $db_opcao,
                            "onchange='js_pesquisaReceita(false)'"
                      );
  				      db_input('k02_drecei', 48, $Ik02_drecei, true, 'text', 3);
  				    ?>
  				  </td>
  				</tr>
  			</table>
			</fieldset>
	  </td>
	</tr>
</table>

<!-- final tributario -->
    <?php if (isDBSellerUser()) { ?>
    <fieldset>
        <legend>Integra��o PIX - Gera��o QR Code</legend>
        <table style="width: 100%">
            <tr>
                <th style="width: 50%"></th>
                <th style="width: 50%"></th>
            </tr>
            <tr>
                <td title="<?=$Tk03_ativo_integracao_pix?>">
                    <?=$Lk03_ativo_integracao_pix?>
                </td>
                <td>
                    <?php
                    db_select(
                        'k03_ativo_integracao_pix',
                        ["f" => "N�o", "t" => "Sim"],
                        true,
                        $db_opcao,
                        "onChange='showInstituicaoFinanceira(this)'"
                    );
                    ?>
                </td>
            </tr>
            <tr
                id="instituicaoFinanceira"
                style="display: <?=$k03_ativo_integracao_pix === 'f' ? 'none' : 'table-row' ?>"
            >
                <td title="<?=$Tk03_instituicao_financeira?>">
                    <?=$Lk03_instituicao_financeira?>
                </td>
                <td>
                    <?php
                    db_select(
                        'k03_instituicao_financeira',
                        InstituicaoFinanceiraApiPix::INSTITUICOES_FINANCEIRAS_OPTION,
                        true,
                        $db_opcao,
                        "onChange='showApiSettings(this)'"
                    );
                    ?>
                </td>
            </tr>
        </table>
        <?php
        require_once 'forms/arrecadacao/numpref/partials/db_frmbbpixsettings.php';
        ?>
    </fieldset>
    <?php } ?>
<!-- Inicio mensagens-->
      <fieldset id="field_mensagem" name="field_mensagem">
        <legend>Mensagens</legend>
        <table>
          <tr>
            <td title="<?=@$Tk03_msg?>" colspan="4" align="center">
              <fieldset>
              <legend><?=@$Lk03_msg?></legend>
                  <?php
                db_textarea('k03_msg',0,100,$Ik03_msg,true,'text',$db_opcao,"");
              ?>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td title="<?=@$Tk03_msgcarne?>" colspan='4' align="center">
              <fieldset>
               <legend><?=@$Lk03_msgcarne?></legend>
                  <?php
                db_textarea('k03_msgcarne',0,100,$Ik03_msgcarne,true,'text',$db_opcao,"");
              ?>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td title="<?=@$Tk03_msgbanco?>" colspan="4" align="center">
              <fieldset>
              <legend><?=@$Lk03_msgbanco?></legend>
                  <?php
                db_textarea('k03_msgbanco',0,100,$Ik03_msgbanco,true,'text',$db_opcao,"");
              ?>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td title="<?=@$Tk03_msgautent?>" colspan="4" align="center">
              <fieldset>
              <legend><?=@$Lk03_msgautent?></legend>
                  <?php
                 db_textarea('k03_msgautent',0,100,$Ik03_msgautent,true,'text',$db_opcao,"");
               ?>
              </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
<!-- Fim mensagens-->


<input
	name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
	type="submit" id="db_opcao"
	value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
	<?=($db_botao==false?"disabled":"")?> /> <input name="pesquisar"
	type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
</form>
<script>

oMensagem = new DBToogle("field_mensagem", false);
const MENSAGENS = 'tributario.arrecadacao.db_frmnumpref.';
const CODIGO_BANCO_DO_BRASIL = 1;

function validaCampo() {

  if($F('k03_defope').trim() == "") {

    alert(_M( MENSAGENS + "campo_nao_informado", {sCampo : "Opera��o"}));
    $('k03_defope').focus();
    return false;
  }

  if($F('k03_recjur').trim() == "") {

    alert(_M( MENSAGENS + "campo_nao_informado", {sCampo : "Receita Juros"}));
    $('k03_recjur').focus();
    return false;
  }

  if($F('k03_codbco').trim() == "") {

    alert(_M( MENSAGENS + "campo_nao_informado", {sCampo : "Banco"}));
    $('k03_codbco').focus();
    return false;
  }

  if($F('k03_numsli').trim() == "") {

    alert(_M( MENSAGENS + "campo_nao_informado", {sCampo : "Slip"}));
    $('k03_numsli').focus();
    return false;
  }

  if($F('k03_recmul').trim() == "") {

    alert(_M( MENSAGENS + "campo_nao_informado", {sCampo : "Receita Multa"}));
    $('k03_recmul').focus();
    return false;
  }

  if($F('k03_codage').trim() == "") {

    alert(_M( MENSAGENS + "campo_nao_informado", {sCampo : "Ag�ncia"}));
    $('k03_codage').focus();
    return false;
  }

  if($F('k03_reciboprot').trim() == "") {

    alert(_M( MENSAGENS + "campo_nao_informado", {sCampo : "Tipo do Recibo Protocolo"}));
    $('k03_reciboprot').focus();
    return false;
  }

  if($F('k03_toleranciapgtoparc').trim() == "") {

    alert(_M( MENSAGENS + "campo_nao_informado", {sCampo : "Valor Toler�ncia Diferen�a Pagamento"}));
    $('k03_toleranciapgtoparc').focus();
    return false;
  }

  if($F('k03_toleranciacredito').trim() == "") {

    alert(_M( MENSAGENS + "campo_nao_informado", {sCampo : "Toler�ncia para Cr�dito"}));
    $('k03_toleranciacredito').focus();
    return false;
  }

  if($F('k03_diasjust').trim() == "") {

    alert(_M( MENSAGENS + "campo_nao_informado", {sCampo : "Dias Justif."}));
    $('k03_diasjust').focus();
    return false;
  }

  if($F('k03_taxagrupo').trim() == "") {

    alert(_M( MENSAGENS + "campo_nao_informado", {sCampo : "C�digo do grupo de taxas"}));
    $('k03_taxagrupo').focus();
    return false;
  }

  if($F('k03_diasvalidadecertidao').trim() == "") {

    alert(_M( MENSAGENS + "campo_nao_informado", {sCampo : "Dias para vencimentos das Certid�es"}));
    $('k03_diasvalidadecertidao').focus();
    return false;
  }

  if($F('k03_diasreemissaocertidao').trim() == "") {

    alert(_M( MENSAGENS + "campo_nao_informado", {sCampo : "Dias para reemiss�o das Certid�es"}));
    $('k03_diasreemissaocertidao').focus();
    return false;
  }
    if (
        $F('k03_ativo_integracao_pix').trim() === 't'
        && parseInt($F('k03_instituicao_financeira').trim()) === CODIGO_BANCO_DO_BRASIL
    ) {
        return validaCamposBB();
    }
}
var chave_k03_tipo = 14;

function js_pesquisak03_respcargo(mostra) {
	if(mostra==true) {
		js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_respcargo','func_rhfuncao.php?funcao_js=parent.js_mostrarespcargo1|rh37_funcao|rh37_descr','Pesquisa',true);
	}else {
		if(document.form1.k03_respcargo.value != ''){
			js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_respcargo','func_rhfuncao.php?pesquisa_chave='+document.form1.k03_respcargo.value+'&funcao_js=parent.js_mostrarespcargo2','Pesquisa',false);
		} else {
		  document.form1.rh37_descr.value = '';
		}
	}
}

function js_mostrarespcargo1(chave1, chave2) {

	document.form1.k03_respcargo.value = chave1;
	document.form1.rh37_descr.value = chave2;
	db_iframe_respcargo.hide();

}

function js_mostrarespcargo2(chave1, erro) {

	document.form1.rh37_descr.value = chave1;
	if(erro == true) {
		document.form1.k03_respccargo.focus();
		document.form1.k03_respccargo.value = '';
	}

}

function js_pesquisak03_respcgm(mostra) {
  if(mostra==true) {
	  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_respcgm','func_cgm.php?funcao_js=parent.js_mostranomecgm1|z01_numcgm|z01_nome','Pesquisa',true);
	}else {
		if(document.form1.k03_respcgm.value != ''){
		 js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_respcgm','func_cgm.php?pesquisa_chave='+document.form1.k03_respcgm.value+'&funcao_js=parent.js_mostranomecgm2','Pesquisa',false);
		} else {
		  document.form1.z01_nome.value = "";
		}
	}
}

function js_mostranomecgm1(chave1, chave2) {
	document.form1.k03_respcgm.value = chave1;
	document.form1.z01_nome.value = chave2;
	db_iframe_respcgm.hide();
}

function js_mostranomecgm2(erro, chave1) {

	document.form1.z01_nome.value = chave1;
	if(erro == true) {
		document.form1.k03_respcgm.focus();
		document.form1.k03_respcgm.value = '';
	}
}

function js_pesquisak03_taxagrupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_taxagrupo','func_taxagrupo.php?funcao_js=parent.js_mostrataxagrupo1|k06_taxagrupo|k06_descr','Pesquisa',true);
  }else{
     if(document.form1.k03_taxagrupo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_taxagrupo','func_taxagrupo.php?pesquisa_chave='+document.form1.k03_taxagrupo.value+'&funcao_js=parent.js_mostrataxagrupo','Pesquisa',false);
     }else{
       document.form1.k06_descr.value = '';
     }
  }
}
function js_mostrataxagrupo(chave,erro){
  document.form1.k06_descr.value = chave;
  if(erro==true){
    document.form1.k03_taxagrupo.focus();
    document.form1.k03_taxagrupo.value = '';
  }
}
function js_mostrataxagrupo1(chave1,chave2){
  document.form1.k03_taxagrupo.value = chave1;
  document.form1.k06_descr.value = chave2;
  db_iframe_taxagrupo.hide();
}
function js_pesquisak03_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.k03_instit.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.k03_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = '';
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave;
  if(erro==true){
    document.form1.k03_instit.focus();
    document.form1.k03_instit.value = '';
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.k03_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_numpref','func_numpref.php?funcao_js=parent.js_preenchepesquisa|k03_anousu|k03_instit','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_numpref.hide();
    <?php
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
function js_consultareciboretencao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_reciboprotretencao',
                        'func_reciboprotretencao.php?k03_tipo='+chave_k03_tipo+'&funcao_js=parent.js_mostrareciboretencao1|k00_tipo|k00_descr',
                        'Pesquisa',true);
  }else{
     if(document.form1.k03_reciboprotretencao.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo',
                            'db_iframe_reciboprotretencao',
                            'func_reciboprotretencao.php?k03_tipo='+chave_k03_tipo+'&pesquisa_chave='+document.form1.k03_reciboprotretencao.value+'&funcao_js=parent.js_mostrareciboretencao',
                            'Pesquisa',false);
     }else{
       document.form1.k03_reciboprotretencao.value = '';
       document.form1.k00_descr.value = '';
     }
  }
}
function js_mostrareciboretencao(chave,erro){
  document.form1.k00_descr.value = chave;
  db_iframe_reciboprotretencao.hide();
  if(erro==true){
    document.form1.k00_descr.focus();
    document.form1.k03_reciboprotretencao.value = '';
  }
}
function js_mostrareciboretencao1(chave1,chave2){
  document.form1.k03_reciboprotretencao.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_reciboprotretencao.hide();
}

function js_pesquisaReceita(lMostra) {

  if (lMostra) {
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_receita', 'func_tabrec.php?funcao_js=parent.js_mostraReceita|k02_codigo|k02_drecei', 'Pesquisa', true);
  } else {
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_receita', 'func_tabrec.php?funcao_js=parent.js_mostraReceitaHide&pesquisa_chave='+document.getElementById('k03_receitapadraocredito').value, 'Pesquisa', false);
  }

}

function js_mostraReceita(iCodigoReceita, sDescricao) {

  document.getElementById('k03_receitapadraocredito').value = iCodigoReceita;
  document.getElementById('k02_drecei').value                = sDescricao;
  db_iframe_receita.hide();

}

function js_mostraReceitaHide(sDescricao, lErro) {

  if (lErro) {
    document.getElementById('k03_receitapadraocredito').value = '';
  }

  document.getElementById('k02_drecei').value = sDescricao;

}

function showInstituicaoFinanceira(element) {
    const instituicaoFinanceiraRow = document.getElementById('instituicaoFinanceira');
    const selectInstituicaoFinanceira = document.getElementById('k03_instituicao_financeira');
    const event = new Event('change');

    selectInstituicaoFinanceira.dispatchEvent(event);
    if (element.value === 't') {
        instituicaoFinanceiraRow.removeAttribute('style');
        return;
    }
    instituicaoFinanceiraRow.style.display = 'none';
}

function showApiSettings(element) {
    const settingsForms = ['apiSettings1'];
    const formNameSelected = `apiSettings${element.value}`;
    const selectAtivoIntegracao = document.getElementById('k03_ativo_integracao_pix');
    settingsForms.each(function (form) {
        const formToShow = document.getElementById(form);
        if (form === formNameSelected && selectAtivoIntegracao.value === 't') {
            formToShow.removeAttribute('style');
            return;
        }
        formToShow.style.display = 'none';
    });
}
</script>
