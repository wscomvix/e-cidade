<?
//MODULO: contabilidade
$clnaturrecsiops->rotulo->label();
?>
<form name="form1" method="post" action="">
    <fieldset style="width: 350px; height: 90px; margin-bottom:10px;"><legend><b>C�digo da Receita:</b></legend>
        <table style="margin-bottom: 10px;">
            <tr>
                <td>&nbsp;</td>
                <td nowrap title="<?=@$Tc230_natrececidade?>">
                    <strong><?=@$Lc230_natrececidade?></strong>
                </td>
                <td nowrap title="<?=@$Tc230_natrecsiops?>">
                    <strong><?=@$Lc230_natrecsiops?></strong>
                </td>
            </tr>
            <tr>
                <td><strong>C�digo da Receita:</strong></td>
                <td>
                    <?
                    if(db_getsession("DB_anousu") > 2021)
                       $iMaxLen = 8;
                    db_input('c230_natrececidade',15,$Ic230_natrececidade,true,'text',1,"","","","",$iMaxLen)
                    ?>
                </td>
                <td>
                    <?
                    if(db_getsession("DB_anousu") > 2021)
                        $iMaxLen = 8;
                    db_input('c230_natrecsiops',15,$Ic230_natrecsiops,true,'text',1,"","","","",$iMaxLen)
                    ?>
                </td>
            </tr>
        </table>
        <input name="c230_anousu" value="<?= db_getsession("DB_anousu") ?>" type="hidden" >
        <input style="display:none" name="novo" type="submit" id="novo" value="Novo" onclick="novaNat();">
        <input name="incluir" type="submit" id="incluir" value="Incluir">
        <input style="display:none" name="excluir" type="submit" id="excluir" value="Excluir">
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </fieldset>
</form>
<script>
    function js_pesquisa(){
        js_OpenJanelaIframe('','db_iframe_naturrecsiops','func_naturrecsiops.php?funcao_js=parent.js_preenchepesquisa|c230_natrececidade|c230_natrecsiops|c230_anousu','Pesquisa',true,0);
    }
    function js_preenchepesquisa(chave, chave1, chave2){
        console.log(chave, chave1);
        document.form1.novo.style.display = 'inline-block';
        document.form1.excluir.style.display = 'inline-block';
        document.form1.incluir.style.display = 'none';
        document.form1.c230_natrececidade.style.background = '#DEB887';
        document.form1.c230_natrecsiops.style.background = '#DEB887';
        document.form1.c230_natrececidade.setAttribute('readonly',true);
        document.form1.c230_natrecsiops.setAttribute('readonly',true);
        document.form1.c230_natrececidade.value = chave;
        document.form1.c230_natrecsiops.value = chave1;
        document.form1.c230_anousu.value = chave2;
        db_iframe_naturrecsiops.hide();

    }

    function novaNat() {
        console.log('novo');

        document.form1.novo.style.display = 'none';
        document.form1.excluir.style.display = 'none';
        document.form1.incluir.style.display = 'inline-block';
        document.form1.c230_natrececidade.style.background = '';
        document.form1.c230_natrecsiops.style.background = '';
        document.form1.c230_natrececidade.setAttribute('readonly',false);
        document.form1.c230_natrecsiops.setAttribute('readonly',false);
        document.form1.c230_natrececidade.value = "";
        document.form1.c230_natrecsiops.value = "";

    }

</script>
