<?php

//MODULO: itbi
$clparitbi->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j32_descr");
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
require_once 'model/itbi/Paritbi.model.php';
?>
<fieldset>
  <legend>
    <strong>Par�metros de ITBI</strong>
  </legend>
  <table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
    <tr>
      <td colspan="2">
        <table border="0" width="100%">
          <tr>
            <td align="left" title="<?=@$Tit24_anousu?>" width="45%"><?=@$Lit24_anousu?></td>
            <td>
                <?php
			          db_input('it24_anousu',10,$Iit24_anousu,true,'text',3," style=' width: 210px; background-color: rgb(222, 184, 135);'")
			        ?>
            </td>
          </tr>
          <tr>
            <td align="left" title="<?=@$Tit24_diasvctoitbi?>" width="45%"><?=@$Lit24_diasvctoitbi?></td>
            <td>
                <?php
			          db_input('it24_diasvctoitbi',10,$Iit24_diasvctoitbi,true,'text',$db_opcao," style=' width: 210px;'");
			        ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tit24_impsituacaodeb?>"><?=@$Lit24_impsituacaodeb?></td>
            <td width="96%" align="left">
                <?php
               $aImpSituacaoDeb = array("t"=>"Sim",
                                        "f"=>"N�o");
               db_select('it24_impsituacaodeb',$aImpSituacaoDeb,true,1," style=' width: 210px;'");
             ?>
            </td>
          </tr>
          <tr>
            <td align="left" title="<?=@$Tit24_alteraguialib?>"><?=@$Lit24_alteraguialib?></td>
            <td width="96%" align="left">
                <?php
               $aAlteraGuiaLib = array("1"=>"Somente Datas",
                                       "2"=>"Somente Dados Cadastrais",
                                       "3"=>"Ambos");
               db_select('it24_alteraguialib',$aAlteraGuiaLib,true,1," style=' width: 210px;'");
             ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tit24_cgmobrigatorio?>"><?=@$Lit24_cgmobrigatorio?></td>
            <td width="96%" align="left">
                <?php
               $aCgmObrigatorio = array("t"=>"Sim",
                                         "f"=>"N�o");
               db_select('it24_cgmobrigatorio',$aCgmObrigatorio,true,1," style='width: 210px;'");
             ?>
            </td>
          </tr>
          <tr>
          	<td align="left" title="<?php echo $Tit24_taxabancaria; ?>">
          		<?php echo $Lit24_taxabancaria; ?>
          	</td>
          	<td>
          	   <?php db_input('it24_taxabancaria', 10, $Iit24_taxabancaria, true, 'text', $db_opcao); ?>
          	</td>
          </tr>
          <tr>
            <td align="left" title="<?php echo $Tit24_grupopadraoconstrutivobenurbana; ?>" nowrap>
              <?php
                db_ancora($Lit24_grupopadraoconstrutivobenurbana, "js_pesquisait24_grupopadraoconstrutivobenurbana(true);", $db_opcao);
              ?>
            </td>
            <td colspan="2" nowrap>
              <?php
                db_input('it24_grupopadraoconstrutivobenurbana', 10, $Iit24_grupopadraoconstrutivobenurbana, true, 'text', $db_opcao, " onchange='js_pesquisait24_grupopadraoconstrutivobenurbana(false);'");
                db_input('nomegrupopadraoconstrutivourbana', 35, $Ij32_descr, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tit24_transfautomatica?>"><?=@$Lit24_transfautomatica?></td>
            <td width="96%" align="left">
                <?php
              $aTransfAutomatica = array("t"=>"Sim",
                  "f"=>"N�o");
              db_select('it24_transfautomatica',$aTransfAutomatica,true,1," style='width: 210px;'");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tit24_inclusaoautomaticapromitente?>"><?=@$Lit24_inclusaoautomaticapromitente?></td>
            <td width="96%" align="left">
                <?php
              $aInclusaoAutomaticaPromitente = array("t"=>"Sim",
                  "f"=>"N�o");
              db_select('it24_inclusaoautomaticapromitente',$aInclusaoAutomaticaPromitente,true,1," style='width: 210px;'");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tit24_devedor?>"><?=@$Lit24_devedor?></td>
            <td width="96%" align="left">
                <?php
              $aOptions = array(\Model\Itbi\Paritbi::DEVEDOR_PRINCIPAL_ADQUIRENTE=>"Adquirente",
                  \Model\Itbi\Paritbi::DEVEDOR_PRINCIPAL_TRANSMITENTE=>"Transmitente");
              db_select('it24_devedor',$aOptions,true,1," style='width: 210px;'");
              ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td colspan="2">
        <fieldset>
          <legend>
            <strong>Filtros</strong>
          </legend>
          <table border="0" width="100%">
            <tr>
              <td><strong>Hist. Calc. :</strong></td>
              <td colspan="2">
                  <input type="text" name="hist_calc" id="hist_calc" style=' width:80px; background-color: rgb(222, 184, 135);'
                   value='707' readonly="readonly" title='Hist�rico do Calculo Fixo ITBI' >
              </td>
            </tr>
            <tr>
              <td title="C�digo da Receita" width="28%">
                 <strong><?php db_ancora("Codigo da Receita",'js_pesquisa(true);',1); ?></strong>
              </td>
              <td>
                  <?php db_input('k02_codigo',10,"",true,'text',1,'onchange="js_pesquisa(false);"');  ?>
              </td>
              <td>
                  <?php db_input('k02_descr' ,40,"",true,'text',3);  ?>
              </td>
            </tr>
              <tr>
              <td title="Proced�ncia D.A" width="28%">
                 <strong><?php db_ancora("Proced�ncia D.A",'js_pesquisaProcedencia(true);',1); ?></strong>
              </td>
              <td>
                  <?php db_input('it24_proced',10,"",true,'text',1,'onchange="js_pesquisaProcedencia(false);"');  ?>
              </td>
              <td>
                  <?php db_input('v03_descr' , 40, "", true, 'text', 3 , '');  ?>
              </td>
            </tr>
              <tr>
                <td nowrap title="<?=@$Tz01_numcgm?>" align='left'>
                  <strong>
                      <?php
                      db_ancora("CGM :","js_pesquisaz01_numcgm(true);",1);
                    ?>
                  </strong>
                </td>
                <td>
                    <?php
                    db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',1," onchange='js_pesquisaz01_numcgm(false);'");
                  ?>
                </td>
                <td>
                    <?php
                    db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
                  ?>
                </td>
         </table>
        </fieldset>
      </td>
    </tr>

    <tr>
      <td colspan="2">
        <fieldset>
          <legend>
            <strong>Urbana</strong>
          </legend>
          <table border="0" width="100%">
            <tr>
              <td title="<?=@$Tit24_grupoespbenfurbana?>" width="28%">
                  <?php
                  db_ancora(@$Lit24_grupoespbenfurbana,"js_pesquisait24_grupoespbenfurbana(true);",$db_opcao);
                ?>
              </td>
              <td>
                  <?php
                  db_input('it24_grupoespbenfurbana',10,$Iit24_grupoespbenfurbana,true,'text',$db_opcao," onchange='js_pesquisait24_grupoespbenfurbana(false);'");
                ?>
              </td>
              <td>
                  <?php
                  db_input('nomeespbenfurbana',40,$Ij32_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td title="<?=@$Tit24_grupotipobenfurbana?>">
                  <?php
                  db_ancora(@$Lit24_grupotipobenfurbana,"js_pesquisait24_grupotipobenfurbana(true);",$db_opcao);
                ?>
              </td>
              <td>
                  <?php
                  db_input('it24_grupotipobenfurbana',10,$Iit24_grupotipobenfurbana,true,'text',$db_opcao," onchange='js_pesquisait24_grupotipobenfurbana(false);'");
                ?>
              </td>
              <td>
                  <?php
                  db_input('nometipobenfurbana',40,$Ij32_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
         </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <fieldset>
          <legend>
            <strong>Rural</strong>
          </legend>
          <table border="0" width="100%">
            <tr>
              <td title="<?=@$Tit24_grupoespbenfrural?>" width="28%">
                  <?php
                  db_ancora(@$Lit24_grupoespbenfrural,"js_pesquisait24_grupoespbenfrural(true);",$db_opcao);
                ?>
              </td>
              <td>
                  <?php
                  db_input('it24_grupoespbenfrural',10,$Iit24_grupoespbenfrural,true,'text',$db_opcao," onchange='js_pesquisait24_grupoespbenfrural(false);'");
                ?>
              </td>
              <td>
                  <?php
                  db_input('nomeespbenfrural',40,$Ij32_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td title="<?=@$Tit24_grupotipobenfrural?>">
                  <?php
                  db_ancora(@$Lit24_grupotipobenfrural,"js_pesquisait24_grupotipobenfrural(true);",$db_opcao);
                ?>
              </td>
              <td>
                  <?php
                  db_input('it24_grupotipobenfrural',10,$Iit24_grupotipobenfrural,true,'text',$db_opcao," onchange='js_pesquisait24_grupotipobenfrural(false);'");
                ?>
              </td>
              <td>
                  <?php
                  db_input('nometipobenfrural',40,$Ij32_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td title="<?=@$Tit24_grupoutilterrarural?>">
                  <?php
                  db_ancora(@$Lit24_grupoutilterrarural,"js_pesquisait24_grupoutilterrarural(true);",$db_opcao);
                ?>
              </td>
              <td>
                  <?php
                  db_input('it24_grupoutilterrarural',10,$Iit24_grupoutilterrarural,true,'text',$db_opcao," onchange='js_pesquisait24_grupoutilterrarural(false);'");
                ?>
              </td>
              <td>
                  <?php
                  db_input('nomeutilterrarural',40,$Ij32_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td title="<?=@$Tit24_grupodistrterrarural?>">
                  <?php
                  db_ancora(@$Lit24_grupodistrterrarural,"js_pesquisait24_grupodistrterrarural(true);",$db_opcao);
                ?>
              </td>
              <td>
                  <?php
                  db_input('it24_grupodistrterrarural',10,$Iit24_grupodistrterrarural,true,'text',$db_opcao," onchange='js_pesquisait24_grupodistrterrarural(false);'");
                ?>
              </td>
              <td>
                  <?php
                  db_input('nomedistrterrarural',40,$Ij32_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
</fieldset>

<input name="<?=($db_opcao==1?"incluir":"alterar")?>" onclick="return js_valida();"
       type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":"Alterar")?>"/>

<script type="text/javascript">
    function js_pesquisait24_grupoespbenfurbana(mostra){

        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_mostracargrupespbenfurbana1|j32_grupo|j32_descr','Pesquisa',true);
        }else{
            if(document.form1.it24_grupoespbenfurbana.value != ''){
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup.php?pesquisa_chave='+document.form1.it24_grupoespbenfurbana.value+'&funcao_js=parent.js_mostracargrupespbenfurbana','Pesquisa',false);
            }else{
                document.form1.nomeespbenfurbana.value = '';
            }
        }
    }
    function js_mostracargrupespbenfurbana(chave,erro){

        document.form1.nomeespbenfurbana.value = chave;
        if(erro==true){

            document.form1.it24_grupoespbenfurbana.focus();
            document.form1.it24_grupoespbenfurbana.value = '';
        }
    }
    function js_mostracargrupespbenfurbana1(chave1,chave2){

        document.form1.it24_grupoespbenfurbana.value = chave1;
        document.form1.nomeespbenfurbana.value	     = chave2;
        db_iframe_cargrup.hide();
    }

    function js_pesquisait24_grupotipobenfurbana(mostra){

        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_mostracargruptipobenfurbana1|j32_grupo|j32_descr','Pesquisa',true);
        }else{

            if(document.form1.it24_grupotipobenfurbana.value != ''){
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup.php?pesquisa_chave='+document.form1.it24_grupotipobenfurbana.value+'&funcao_js=parent.js_mostracargruptipobenfurbana','Pesquisa',false);
            }else{
                document.form1.nometipobenfurbana.value = '';
            }
        }
    }

    function js_mostracargruptipobenfurbana(chave,erro){

        document.form1.nometipobenfurbana.value = chave;
        if(erro==true){

            document.form1.it24_grupotipobenfurbana.focus();
            document.form1.it24_grupotipobenfurbana.value = '';
        }
    }
    function js_mostracargruptipobenfurbana1(chave1,chave2){

        document.form1.it24_grupotipobenfurbana.value = chave1;
        document.form1.nometipobenfurbana.value       = chave2;
        db_iframe_cargrup.hide();
    }


    function js_pesquisait24_grupoespbenfrural(mostra){

        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_mostracargrupespbenfrural1|j32_grupo|j32_descr','Pesquisa',true);
        }else{

            if(document.form1.it24_grupoespbenfrural.value != ''){
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup.php?pesquisa_chave='+document.form1.it24_grupoespbenfrural.value+'&funcao_js=parent.js_mostracargrupespbenfrural','Pesquisa',false);
            }else{
                document.form1.nomeespbenfrural.value = '';
            }
        }
    }

    function js_mostracargrupespbenfrural(chave,erro){

        document.form1.nomeespbenfrural.value = chave;
        if(erro==true){

            document.form1.it24_grupoespbenfrural.focus();
            document.form1.it24_grupoespbenfrural.value = '';
        }
    }

    function js_mostracargrupespbenfrural1(chave1,chave2){

        document.form1.it24_grupoespbenfrural.value = chave1;
        document.form1.nomeespbenfrural.value       = chave2;
        db_iframe_cargrup.hide();
    }


    function js_pesquisait24_grupotipobenfrural(mostra){

        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_mostracargruptipobenfrural1|j32_grupo|j32_descr','Pesquisa',true);
        }else{

            if(document.form1.it24_grupotipobenfrural.value != ''){
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup.php?pesquisa_chave='+document.form1.it24_grupotipobenfrural.value+'&funcao_js=parent.js_mostracargruptipobenfrural','Pesquisa',false);
            }else{
                document.form1.nometipobenfrural.value = '';
            }
        }
    }
    function js_mostracargruptipobenfrural(chave,erro){

        document.form1.nometipobenfrural.value = chave;
        if(erro==true){

            document.form1.it24_grupotipobenfrural.focus();
            document.form1.it24_grupotipobenfrural.value = '';
        }
    }
    function js_mostracargruptipobenfrural1(chave1,chave2){

        document.form1.it24_grupotipobenfrural.value = chave1;
        document.form1.nometipobenfrural.value       = chave2;
        db_iframe_cargrup.hide();
    }


    function js_pesquisait24_grupoutilterrarural(mostra){

        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_mostracargruputilterrarural1|j32_grupo|j32_descr','Pesquisa',true);
        }else{

            if(document.form1.it24_grupoutilterrarural.value != ''){
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup.php?pesquisa_chave='+document.form1.it24_grupoutilterrarural.value+'&funcao_js=parent.js_mostracargruputilterrarural','Pesquisa',false);
            }else{
                document.form1.nomeutilterrarural.value = '';
            }
        }
    }

    function js_mostracargruputilterrarural(chave,erro){

        document.form1.nomeutilterrarural.value = chave;
        if(erro==true){

            document.form1.it24_grupoutilterrarural.focus();
            document.form1.it24_grupoutilterrarural.value = '';
        }
    }

    function js_mostracargruputilterrarural1(chave1,chave2){

        document.form1.it24_grupoutilterrarural.value = chave1;
        document.form1.nomeutilterrarural.value       = chave2;
        db_iframe_cargrup.hide();
    }


    function js_pesquisait24_grupodistrterrarural(mostra){

        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_mostracargrupdistrterrarural1|j32_grupo|j32_descr','Pesquisa',true);
        }else{

            if(document.form1.it24_grupodistrterrarural.value != ''){
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup.php?pesquisa_chave='+document.form1.it24_grupodistrterrarural.value+'&funcao_js=parent.js_mostracargrupdistrterrarural','Pesquisa',false);
            }else{
                document.form1.nomedistrterrarural.value = '';
            }
        }
    }
    function js_mostracargrupdistrterrarural(chave,erro){

        document.form1.nomedistrterrarural.value = chave;
        if(erro==true){
            document.form1.it24_grupodistrterrarural.focus();
            document.form1.it24_grupodistrterrarural.value = '';
        }
    }
    function js_mostracargrupdistrterrarural1(chave1,chave2){

        document.form1.it24_grupodistrterrarural.value = chave1;
        document.form1.nomedistrterrarural.value 	 = chave2;
        db_iframe_cargrup.hide();
    }

    /*
     * Pesquisa para CGM
    */
    function js_pesquisaz01_numcgm(mostra){

        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_nome','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
        }else{

            if(document.form1.z01_numcgm.value != ''){
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
            }else{
                document.form1.kz01_numcgm.value = '';
            }
        }
    }

    function js_mostracgm(erro,chave){

        document.form1.z01_nome.value = chave;
        if(erro==true){

            document.form1.z01_numcgm.focus();
            document.form1.z01_numcgm.value = '';
        }
    }
    function js_mostracgm1(chave1,chave2){

        document.form1.z01_numcgm.value = chave1;
        document.form1.z01_nome.value   = chave2;
        db_iframe_nome.hide();
    }

    /**
     * Grupo de Padr�o Construtivo
     */
    function js_pesquisait24_grupopadraoconstrutivobenurbana(mostra){

        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup_rel.php?grupo=I&funcao_js=parent.js_mostracargruptipopadraocontrutivo1|j32_grupo|j32_descr','Pesquisa',true);
        }else{

            if(document.form1.it24_grupopadraoconstrutivobenurbana.value != ''){
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup_rel.php?grupo=I&pesquisa_chave='+document.form1.it24_grupopadraoconstrutivobenurbana.value+'&funcao_js=parent.js_mostracargruptipopadraocontrutivo','Pesquisa',false);
            }else{
                document.form1.nomegrupopadraoconstrutivourbana.value = '';
            }
        }
    }

    function js_mostracargruptipopadraocontrutivo(chave, erro) {

        document.form1.nomegrupopadraoconstrutivourbana.value = chave;
        if ( erro == true ) {
            document.form1.it24_grupopadraoconstrutivobenurbana.focus();
            document.form1.it24_grupopadraoconstrutivobenurbana.value = '';
        }
    }

    function js_mostracargruptipopadraocontrutivo1(chave1, chave2) {

        document.form1.it24_grupopadraoconstrutivobenurbana.value = chave1;
        document.form1.nomegrupopadraoconstrutivourbana.value     = chave2;
        db_iframe_cargrup.hide();
    }

    /*
     * PESQUISA PARA RECEITA
    */
    function js_pesquisa(mostra){

        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_conlancamrec','func_tabrec.php?funcao_js=parent.js_preenchepesquisa|k02_codigo|k02_descr','Pesquisa',true);
        }else{

            if(document.form1.k02_codigo != ''){
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_conlancamrec','func_tabrec.php?pesquisa_chave='+document.form1.k02_codigo.value+'&funcao_js=parent.js_mostrarec','Pesquisa', false);
            }else{
                document.form1.k02_codigo.value = '';
            }
        }
    }

    function js_preenchepesquisa(chave,chave2){

        db_iframe_conlancamrec.hide();
        document.form1.k02_codigo.value=chave;
        document.form1.k02_descr.value=chave2;
    }
    function js_mostrarec(chave2, erro, chave3) {

        document.form1.k02_descr.value=chave2;
        if(erro==true){

            document.form1.k02_codigo.focus();
            document.form1.k02_codigo.value = '';
        }
    }

    function js_valida(){

        if( $F('k02_codigo') == '' ) {

            alert('Campo C�digo da Receita  de preenchimento obrigat�rio.');
            $('k02_codigo').focus();
            return false;
        }

        if( $F('z01_numcgm') == '' ) {

            alert('Campo CGM  de preenchimento obrigat�rio.');
            $('z01_numcgm').focus();
            return false;
        }

        return true;
    }

    function js_pesquisaProcedencia( mostra ) {

        if( mostra === true ) {
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_proced','func_proced.php?funcao_js=parent.js_mostraproced1|v03_codigo|v03_descr','Pesquisa',true,'0');
        }else{

            if ( document.form1.dv09_proced.value !== '' ) {
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_proced','func_proced.php?pesquisa_chave='+document.form1.dv09_proced.value+'&funcao_js=parent.js_mostraproced','Pesquisa',false,'0','1','775','390');
            }else{
                document.form1.v03_descr.value = '';
            }
        }
    }

    function js_mostraproced( chave, erro ) {

        document.form1.v03_descr.value = chave;

        if( erro === true ) {

            document.form1.it24_proced.focus();
            document.form1.it24_proced.value = '';
        }
    }

    function js_mostraproced1( chave1, chave2 ) {

        document.form1.it24_proced.value = chave1;
        document.form1.v03_descr.value = chave2;
        db_iframe_proced.hide();
    }
</script>
