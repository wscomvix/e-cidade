<?
//MODULO: sicom
$cloperacoesdecreditolrf->rotulo->label();
?>
<form name="form1" method="post" action="">

  <table border="0" align="left" >
    <tr>
      <td>

        <table>

          <tr>
            <td nowrap >
             <b>Contrata��o de Opera��o que n�o atendeu limites Art. 33 LC 101/2000:</b>
           </td>
           <td>
            <?
            $x = array("0"=>"Selecione","1"=>"SIM","2"=>"N�O");
            db_select('c219_contopcredito',$x,true,1,"onchange='js_habilitaDescricao()'");
            ?>
          </td>
        </tr>
        <? if(db_getsession('DB_anousu')>=2021){ ?>
        <tr id="descricaoInstituicao" style="display:none;">
            <td nowrap >
             <b>Descri��o do n�mero da institui��o financeira da opera��o de cr�dito contratada:</b>
           </td>
           <td>
            <?
            db_input('c219_dscnumeroinst', 3,1,true,'text',1, "", "", "", "", 3);

            ?>
          </td>
        </tr>
       <? } ?> 
        <tr id="descricaoOcorrencia" style="display:none;">
          <td colspan="2" >
            <fieldset><legend><b>Descri��o da Ocorr�ncia:</b></legend>
              <?
              db_textarea('c219_dsccontopcredito',8,60,0,true,'text',$db_opcao,"","","",4000)
              ?>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td nowrap >
           <b>Realiza��o de Opera��es de cr�dito vedadas pelo Art. 37 LC 101/2000:</b>
         </td>
         <td>
          <?

          db_select('c219_realizopcredito',$x,true,1,"");
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap >
         <b>Tipo de realiza��o de opera��es de cr�dito vedada (Capta��o):</b>
       </td>
       <td>
        <?

        db_select('c219_tiporealizopcreditocapta',$x,true,1,"");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap>
       <b>Tipo de realiza��o de opera��es de cr�dito vedada (Recebimento):</b>
     </td>
     <td>
      <?

      db_select('c219_tiporealizopcreditoreceb',$x,true,1,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap>
      <b>Tipo de realiza��o de opera��es de cr�dito vedada (Assu��o direta):</b>
    </td>
    <td>
      <?

      db_select('c219_tiporealizopcreditoassundir',$x,true,1,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap>
      <b>Tipo de realiza��o de opera��es de cr�dito vedada (Assu��o de obriga��o):</b>
    </td>
    <td>
      <?

      db_select('c219_tiporealizopcreditoassunobg',$x,true,1,"");
      ?>
    </td>
  </tr>


</table>
<center>
  <br>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="button" id="db_opcao" value="<?=($db_opcao==1?"Pr�ximo":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="js_incluirDados();" >

</center>
</td>
</tr>
</table>
</form>
<script>
  function js_habilitaDescricao(){
    var ano = "<?php echo db_getsession('DB_anousu'); ?>";
    if(document.form1.c219_contopcredito.value == 1){
      document.getElementById("descricaoOcorrencia").style.display = "";
      if(ano>=2021){
        document.getElementById("descricaoInstituicao").style.display = "";
      }  
    }else{
      document.getElementById("descricaoOcorrencia").style.display = "none";
      if(ano>=2021){
        document.getElementById("descricaoInstituicao").style.display = "none";
      }
    }
    }
  
  function js_incluirDados(){
    var ano = "<?php echo db_getsession('DB_anousu'); ?>";
    var opcao = "<?php echo $db_opcao; ?>";
   /*VALIDA��ES*/
   if(document.form1.c219_contopcredito.value == "0"){
    alert('O campo "Contrata��o de Opera��o que n�o atendeu limites Art. 33 LC 101/2000" n�o foi preenchido.');
    return false;
   }
   if(ano>=2021 & opcao!= 3){
      if(document.form1.c219_contopcredito.value == "1" & document.form1.c219_dscnumeroinst.value == ""){
        alert('O campo "Descri��o do n�mero da institui��o financeira da opera��o de cr�dito contratada" n�o foi preenchido.');
        return false;
      }
      if(document.form1.c219_contopcredito.value == "1" & document.form1.c219_dscnumeroinst.value.length < 3){
        alert('O campo "Descri��o do n�mero da institui��o financeira da opera��o de cr�dito contratada" deve conter 3 n�meros.'); 
        return false;
      }
  }
   if(document.form1.c219_realizopcredito.value == "0"){
    alert('O campo "Realiza��o de Opera��es de cr�dito vedadas pelo Art. 37 LC 101/2000" n�o foi preenchido.');
    return false;
   }
   if(document.form1.c219_tiporealizopcreditocapta.value == "0"){
    alert('O campo "Tipo de realiza��o de opera��es de cr�dito vedada (Capta��o)" n�o foi preenchido.');
    return false;
   }
   if(document.form1.c219_tiporealizopcreditoreceb.value == "0"){
    alert('O campo "Tipo de realiza��o de opera��es de cr�dito vedada (Recebimento)" n�o foi preenchido.');
    return false;
   }
   if(document.form1.c219_tiporealizopcreditoassundir.value == "0"){
    alert('O campo "Tipo de realiza��o de opera��es de cr�dito vedada (Assu��o direta)" n�o foi preenchido.');
    return false;
   }
   if(document.form1.c219_tiporealizopcreditoassunobg.value == "0"){
    alert('O campo "Tipo de realiza��o de opera��es de cr�dito vedada (Assu��o de obriga��o)" n�o foi preenchido.');
    return false;
   }
   top.corpo.operacoesdecredito.c219_contopcredito = document.form1.c219_contopcredito.value;
  //  alert(document.form1.c219_contopcredito.value);
  //  if(document.form1.c219_contopcredito.value == 1){ 
  //   alert(document.form1.c219_contopcredito.value);
  //     // top.corpo.operacoesdecredito.c219_dscnumeroinst = "1";
  //     // top.corpo.operacoesdecredito.c219_dsccontopcredito = "2";
  //   }else{
  //     alert(document.form1.c219_contopcredito.value);
  //     // top.corpo.operacoesdecredito.c219_dscnumeroinst = document.form1.c219_dscnumeroinst.value;
  //     // top.corpo.operacoesdecredito.c219_dsccontopcredito = document.form1.c219_dsccontopcredito.value;
  //   }_
   top.corpo.operacoesdecredito.c219_dscnumeroinst = document.form1.c219_dscnumeroinst.value;
   top.corpo.operacoesdecredito.c219_dsccontopcredito = document.form1.c219_dsccontopcredito.value;
   top.corpo.operacoesdecredito.c219_realizopcredito = document.form1.c219_realizopcredito.value;
   top.corpo.operacoesdecredito.c219_tiporealizopcreditocapta = document.form1.c219_tiporealizopcreditocapta.value;
   top.corpo.operacoesdecredito.c219_tiporealizopcreditoreceb = document.form1.c219_tiporealizopcreditoreceb.value;
   top.corpo.operacoesdecredito.c219_tiporealizopcreditoassundir = document.form1.c219_tiporealizopcreditoassundir.value;
   top.corpo.operacoesdecredito.c219_tiporealizopcreditoassunobg = document.form1.c219_tiporealizopcreditoassunobg.value;

   parent.mo_camada('publicacaoeperiodicidaderreo');
 }
 function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_dadoscomplementareslrf','func_dadoscomplementareslrf.php?funcao_js=parent.js_preenchepesquisa|si170_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_dadoscomplementareslrf.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
