<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancamdoc
class cl_conlancamdoc {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $c71_codlan = 0;
   var $c71_coddoc = 0;
   var $c71_data_dia = null;
   var $c71_data_mes = null;
   var $c71_data_ano = null;
   var $c71_data = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c71_codlan = int4 = C�digo Lan�amento
                 c71_coddoc = int4 = C�digo
                 c71_data = date = Data
                 ";
   //funcao construtor da classe
   function cl_conlancamdoc() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancamdoc");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->c71_codlan = ($this->c71_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c71_codlan"]:$this->c71_codlan);
       $this->c71_coddoc = ($this->c71_coddoc == ""?@$GLOBALS["HTTP_POST_VARS"]["c71_coddoc"]:$this->c71_coddoc);
       if($this->c71_data == ""){
         $this->c71_data_dia = ($this->c71_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c71_data_dia"]:$this->c71_data_dia);
         $this->c71_data_mes = ($this->c71_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c71_data_mes"]:$this->c71_data_mes);
         $this->c71_data_ano = ($this->c71_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c71_data_ano"]:$this->c71_data_ano);
         if($this->c71_data_dia != ""){
            $this->c71_data = $this->c71_data_ano."-".$this->c71_data_mes."-".$this->c71_data_dia;
         }
       }
     }else{
       $this->c71_codlan = ($this->c71_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c71_codlan"]:$this->c71_codlan);
     }
   }
   // funcao para inclusao
   function incluir ($c71_codlan){
      $this->atualizacampos();
     if($this->c71_coddoc == null ){
       $this->erro_sql = " Campo C�digo nao Informado.";
       $this->erro_campo = "c71_coddoc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c71_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "c71_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->c71_codlan = $c71_codlan;
     if(($this->c71_codlan == null) || ($this->c71_codlan == "") ){
       $this->erro_sql = " Campo c71_codlan nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancamdoc(
                                       c71_codlan
                                      ,c71_coddoc
                                      ,c71_data
                       )
                values (
                                $this->c71_codlan
                               ,$this->c71_coddoc
                               ,".($this->c71_data == "null" || $this->c71_data == ""?"null":"'".$this->c71_data."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Documento Autom�tico Lan�amento ($this->c71_codlan) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Documento Autom�tico Lan�amento j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Documento Autom�tico Lan�amento ($this->c71_codlan) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c71_codlan;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->c71_codlan));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5213,'$this->c71_codlan','I')");
         $resac = db_query("insert into db_acount values($acount,764,5213,'','".AddSlashes(pg_result($resaco,0,'c71_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,764,5214,'','".AddSlashes(pg_result($resaco,0,'c71_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,764,5898,'','".AddSlashes(pg_result($resaco,0,'c71_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($c71_codlan=null) {
      $this->atualizacampos();
     $sql = " update conlancamdoc set ";
     $virgula = "";
     if(trim($this->c71_codlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c71_codlan"])){
       $sql  .= $virgula." c71_codlan = $this->c71_codlan ";
       $virgula = ",";
       if(trim($this->c71_codlan) == null ){
         $this->erro_sql = " Campo C�digo Lan�amento nao Informado.";
         $this->erro_campo = "c71_codlan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c71_coddoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c71_coddoc"])){
       $sql  .= $virgula." c71_coddoc = $this->c71_coddoc ";
       $virgula = ",";
       if(trim($this->c71_coddoc) == null ){
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "c71_coddoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c71_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c71_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c71_data_dia"] !="") ){
       $sql  .= $virgula." c71_data = '$this->c71_data' ";
       $virgula = ",";
       if(trim($this->c71_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "c71_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["c71_data_dia"])){
         $sql  .= $virgula." c71_data = null ";
         $virgula = ",";
         if(trim($this->c71_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "c71_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($c71_codlan!=null){
       $sql .= " c71_codlan = $this->c71_codlan";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->c71_codlan));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5213,'$this->c71_codlan','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c71_codlan"]))
             $resac = db_query("insert into db_acount values($acount,764,5213,'".AddSlashes(pg_result($resaco,$conresaco,'c71_codlan'))."','$this->c71_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c71_coddoc"]))
             $resac = db_query("insert into db_acount values($acount,764,5214,'".AddSlashes(pg_result($resaco,$conresaco,'c71_coddoc'))."','$this->c71_coddoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c71_data"]))
             $resac = db_query("insert into db_acount values($acount,764,5898,'".AddSlashes(pg_result($resaco,$conresaco,'c71_data'))."','$this->c71_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documento Autom�tico Lan�amento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c71_codlan;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Documento Autom�tico Lan�amento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c71_codlan;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c71_codlan;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($c71_codlan=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($c71_codlan));
       }else{
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5213,'$c71_codlan','E')");
           $resac = db_query("insert into db_acount values($acount,764,5213,'','".AddSlashes(pg_result($resaco,$iresaco,'c71_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,764,5214,'','".AddSlashes(pg_result($resaco,$iresaco,'c71_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,764,5898,'','".AddSlashes(pg_result($resaco,$iresaco,'c71_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from conlancamdoc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c71_codlan != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c71_codlan = $c71_codlan ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documento Autom�tico Lan�amento nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c71_codlan;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Documento Autom�tico Lan�amento nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c71_codlan;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c71_codlan;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:conlancamdoc";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c71_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from conlancamdoc ";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancamdoc.c71_codlan";
     $sql .= "      inner join conhistdoc  on  conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc";
     $sql2 = "";
     if($dbwhere==""){
       if($c71_codlan!=null ){
         $sql2 .= " where conlancamdoc.c71_codlan = $c71_codlan ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $c71_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from conlancamdoc ";
     $sql2 = "";
     if($dbwhere==""){
       if($c71_codlan!=null ){
         $sql2 .= " where conlancamdoc.c71_codlan = $c71_codlan ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_process ( $c71_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from conlancamdoc ";
     $sql .= "      inner join conhistdoc    on  conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc";
     $sql .= "      inner join conlancam     on  conlancam.c70_codlan = conlancamdoc.c71_codlan";
     $sql .= "      inner join conlancamemp  on  conlancamemp.c75_codlan = conlancamdoc.c71_codlan";
     $sql .= "      inner join empempenho    on  empempenho.e60_numcgm = conlancamemp.c75_numemp";
     $sql .= "      inner join cgm           on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      left  join conlancamord  on  conlancamord.c80_codlan = conlancamdoc.c71_codlan";
     $sql2 = "";
if($dbwhere==""){
       if($c71_codlan!=null ){
         $sql2 .= " where conlancamdoc.c71_codlan = $c71_codlan ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

  function sql_query_reduzidos ( $c71_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $iAnoSessao = db_getsession("DB_anousu");
    $sql .= " from conlancamdoc ";
    $sql .= "      inner join conlancam     on  conlancam.c70_codlan = conlancamdoc.c71_codlan";
    $sql .= "      inner join conhistdoc    on  conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc";
    $sql .= "      inner join conlancamval  on  conlancamval.c69_codlan = conlancam.c70_codlan";
    $sql .= "      inner join conplanoreduz as conta_debito  on conta_debito.c61_reduz = conlancamval.c69_debito";
    $sql .= "                                               and conta_debito.c61_anousu = {$iAnoSessao}";
    $sql .= "      inner join conplanoreduz as conta_credito on conta_credito.c61_reduz = conlancamval.c69_credito";
    $sql .= "                                               and conta_credito.c61_anousu = {$iAnoSessao}";
    $sql2 = "";
    if($dbwhere==""){
      if($c71_codlan!=null ){
        $sql2 .= " where conlancamdoc.c71_codlan = $c71_codlan ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  /**
   * query at� a conlancam emp, para verificar na funcao de RP
   * @param string $c71_codlan
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */

  function sql_queryEmpenhoRP ( $c71_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from conlancamdoc ";
    $sql .= "      inner join conlancam     on  conlancam.c70_codlan = conlancamdoc.c71_codlan";
    $sql .= "      inner join conhistdoc    on  conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc";
    $sql .= "      inner join conlancamemp  on  conlancam.c70_codlan = conlancamemp.c75_codlan";
    $sql2 = "";
    if($dbwhere==""){
      if($c71_codlan!=null ){
        $sql2 .= " where conlancamdoc.c71_codlan = $c71_codlan ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }



  public function classificacao($nMes = null)
  {
    $nAnoUsu = db_getsession('DB_anousu');
    $nInstit = db_getsession('DB_instit');
    $aFontesNovas = array('100' => 15000000,'101' => 15000001,'102' => 15000002,'103' => 18000000,'104' => 18010000,'105' => 18020000,
                        '106' => 15760010,'107' => 15440000,'108' => 17080000,'112' => 16590020,'113' => 15990030,'116' => 17500000,
                        '117' => 17510000,'118' => 15400007,'119' => 15400000,'120' => 15760000,'121' => 16220000,'122' => 15700000,
                        '123' => 16310000,'124' => 17000000,'129' => 16600000,'130' => 18990040,'131' => 17590050,'132' => 16040000,
                        '142' => 16650000,'143' => 15510000,'144' => 15520000,'145' => 15530000,'146' => 15690000,'147' => 15500000,
                        '153' => 16010000,'154' => 16590000,'155' => 16210000,'156' => 16610000,'157' => 17520000,'158' => 18990060,
                        '159' => 16000000,'160' => 17040000,'161' => 17070000,'162' => 17490120,'163' => 17130070,'164' => 17060000,
                        '165' => 18990000,'166' => 15420007,'167' => 15420000,'168' => 17100100,'169' => 17100000,'170' => 15010000,
                        '171' => 15710000,'172' => 15720000,'173' => 15750000,'174' => 15740000,'175' => 15730000,'176' => 16320000,
                        '177' => 16330000,'178' => 16360000,'179' => 16340000,'180' => 16350000,'181' => 17010000,'182' => 17020000,
                        '183' => 17030000,'184' => 17090000,'185' => 17530000,'186' => 17040000,'187' => 17050000,'188' => 15000080,
                        '189' => 15000090,'190' => 17540000,'191' => 17540000,'192' => 17550000,'193' => 18990130, '132' => 16040000,
                        '133' => 17150000, '134' => 17160000, '135' => 17170000, '136' => 17180000);

                       
              $case = " substring(o56_elemento,2,6) AS natureza,
                        substring(o56_elemento,8,2) AS subelemento ";
             
              if($nAnoUsu > 2022){
                $case =" CASE
                            when o56_elemento in ('3319004020000') then '319004'
                            when o56_elemento in ('3319004020100') then '319004'
                            when o56_elemento in ('3319004020200') then '319004'
                            when o56_elemento in ('3319011020100') then '319011'
                            when o56_elemento in ('3319011030000') then '319011'
                            when o56_elemento in ('3319011040000') then '319011'
                            when o56_elemento in ('3319011050000') then '319011'
                            when o56_elemento in ('3319011020200') then '319011'
                            when o56_elemento in ('3319011020000') then '319011'
                        else substring(o56_elemento,2,6)                  
                        end AS natureza,
                        CASE
                            when o56_elemento in ('3319004020000') then '01'
                            when o56_elemento in ('3319004020100') then '01'
                            when o56_elemento in ('3319004020200') then '01'
                            
                            when o56_elemento in ('3319011020100') then '01'
                            when o56_elemento in ('3319011030000') then '01'
                            
                            when o56_elemento in ('3319011040000') then '01'
                            when o56_elemento in ('3319011050000') then '01'
                            when o56_elemento in ('3319011020200') then '01'
                            when o56_elemento in ('3319011020000') then '01'
                        else  substring(o56_elemento,8,2)        
                        end AS subelemento ";
              }
             
              $sSql = "
                SELECT lpad(o58_funcao,2,'0') AS funcao,
                       lpad(o58_subfuncao,3,'0') AS subfuncao,
                       $case,
                       o15_codigo AS fonte,
             round(sum(CASE
                     WHEN c71_coddoc = 6 THEN c70_valor*-1
                     ELSE c70_valor
                 END),2) AS emp,
             0 AS empanulado,
             round(sum(CASE
                     WHEN c71_coddoc = 6 THEN c70_valor*-1
                     ELSE c70_valor
                 END),2) AS lqd,
             0 AS lqdanulado,
             round(sum(CASE
                     WHEN c71_coddoc = 6 THEN c70_valor*-1
                     ELSE c70_valor
                 END),2) AS pag,
             0 AS paganulado
      FROM conlancamdoc
      INNER JOIN conhistdoc on c53_coddoc=c71_coddoc
      INNER JOIN conlancam ON c70_codlan=c71_codlan
      INNER JOIN conlancamele ON c67_codlan=c71_codlan
      INNER JOIN conlancaminstit ON c71_codlan=c02_codlan
      INNER JOIN conlancamemp ON c75_codlan=c71_codlan
      INNER JOIN empempenho on c75_numemp=e60_numemp
      INNER JOIN orcelemento ON c67_codele=o56_codele AND e60_anousu=o56_anousu
      INNER JOIN orcdotacao ON e60_coddot = o58_coddot AND e60_anousu=o58_anousu
      INNER JOIN orctiporec ON o15_codigo = o58_codigo
      INNER JOIN orcprojativ ON o58_projativ = o55_projativ AND o58_anousu=o55_anousu
      WHERE c53_tipo IN (30,
                           31)
          AND DATE_PART('YEAR',c71_data) = '{$nAnoUsu}'
          AND DATE_PART('MONTH',c71_data) = '{$nMes}'
          AND c02_instit = '{$nInstit}'
          AND o55_rateio = true
      GROUP BY 1,
               2,
               3,
               4,
               5
    ";
             
    $rsConLancamDoc = $this->sql_record($sSql);

    $aConLancamDoc = db_utils::getCollectionByRecord($rsConLancamDoc);

    $aRetorno = array();

    foreach ($aConLancamDoc as $oDotacao) {

      $fonte = $aFontesNovas[$oDotacao->fonte] != Null ? $aFontesNovas[$oDotacao->fonte] : $oDotacao->fonte;
      $sHash = ""
        . $oDotacao->funcao
        . $oDotacao->subfuncao
        . $oDotacao->natureza
        . $oDotacao->subelemento
        . $fonte
      ;
      if(!isset($aRetorno[$sHash])){
          $oMovimento = new stdClass();
          $oMovimento->funcao      = $oDotacao->funcao;
          $oMovimento->subfuncao   = $oDotacao->subfuncao;
          $oMovimento->natureza    = $oDotacao->natureza;
          $oMovimento->subelemento = $oDotacao->subelemento;
          $oMovimento->fonte       = $fonte;
          $oMovimento->emp         = $oDotacao->emp;
          $oMovimento->empanulado  = $oDotacao->empanulado;
          $oMovimento->lqd         = $oDotacao->lqd;
          $oMovimento->lqdanulado  = $oDotacao->lqdanulado;
          $oMovimento->pag         = $oDotacao->pag;
          $oMovimento->paganulado  = $oDotacao->paganulado;
          $aRetorno[$sHash] = $oMovimento;
      }else {
          $aRetorno[$sHash]->emp        = $aRetorno[$sHash]->emp         + $oDotacao->emp;
          $aRetorno[$sHash]->empanulado = $aRetorno[$sHash]->empanulado  + $oDotacao->empanulado;
          $aRetorno[$sHash]->lqd        = $aRetorno[$sHash]->lqd         + $oDotacao->lqd;
          $aRetorno[$sHash]->lqdanulado = $aRetorno[$sHash]->lqdanulado  + $oDotacao->lqdanulado;
          $aRetorno[$sHash]->pag        = $aRetorno[$sHash]->pag         + $oDotacao->pag;
          $aRetorno[$sHash]->paganulado = $aRetorno[$sHash]->paganulado  + $oDotacao->paganulado;
      }

    }
 //echo "<pre>";print_r($aRetorno);exit;
    return $aRetorno;
  }


  public function classificacaoAteDezembro()
  {
    $nAnoUsu  = db_getsession('DB_anousu');
    $nInstit  = db_getsession('DB_instit');
    $nMes     = 12;

    $sSql = "SELECT lpad(o58_funcao,2,'0') AS funcao,
                 lpad(o58_subfuncao,3,'0') AS subfuncao,
                 substring(o56_elemento,2,6) AS natureza,
                 substring(o56_elemento,8,2) AS subelemento,
                 o15_codtri AS fonte,
                 sum(CASE
                         WHEN c71_coddoc = 6 THEN c70_valor*-1
                         ELSE c70_valor
                     END) AS emp,
                 0 AS empanulado,
                 sum(CASE
                         WHEN c71_coddoc = 6 THEN c70_valor*-1
                         ELSE c70_valor
                     END) AS lqd,
                 0 AS lqdanulado,
                 sum(CASE
                         WHEN c71_coddoc = 6 THEN c70_valor*-1
                         ELSE c70_valor
                     END) AS pag,
                 0 AS paganulado
          FROM conlancamdoc
          INNER JOIN conhistdoc on c53_coddoc=c71_coddoc
          INNER JOIN conlancam ON c70_codlan=c71_codlan
          INNER JOIN conlancamele ON c67_codlan=c71_codlan
          INNER JOIN conlancaminstit ON c71_codlan=c02_codlan
          INNER JOIN conlancamemp ON c75_codlan=c71_codlan
          INNER JOIN empempenho on c75_numemp=e60_numemp
          INNER JOIN orcelemento ON c67_codele=o56_codele AND e60_anousu=o56_anousu
          INNER JOIN orcdotacao ON e60_coddot = o58_coddot AND e60_anousu=o58_anousu
          INNER JOIN orctiporec ON o15_codigo = o58_codigo
          INNER JOIN orcprojativ ON o58_projativ = o55_projativ AND o58_anousu=o55_anousu
          WHERE c53_tipo IN (30,
                           31)
              AND DATE_PART('YEAR',c71_data) = '{$nAnoUsu}'
              AND c02_instit = '{$nInstit}'
              AND o55_rateio = true
          GROUP BY o58_funcao,
                   o58_subfuncao,
                   o15_codtri,
                   o56_elemento
        ";

    $rsConLancamDoc = $this->sql_record($sSql);
    $aConLancamDoc = db_utils::getCollectionByRecord($rsConLancamDoc);

    $aRetorno = array();

    foreach ($aConLancamDoc as $oDotacao) {

      $sHash = ""
        . $oDotacao->funcao
        . $oDotacao->subfuncao
        . $oDotacao->natureza
        . $oDotacao->subelemento
        . $oDotacao->fonte
      ;

      $aRetorno[$sHash] = $oDotacao;

    }

    return $aRetorno;
  }
  /* Retornar o total das despesas que j� foram para o rateio */
  public function totalClassificacao($hash){
    $sql = "SELECT sum(c217_valorpago) as valor
              FROM despesarateioconsorcio
              WHERE c217_mes<12
                AND c217_anousu=". db_getsession('DB_anousu') ."
                AND c217_enteconsorciado||c217_funcao||c217_subfuncao||c217_natureza||c217_subelemento||c217_fonte='{$hash}' ";

     $rsValor = db_query($sql);
     return db_utils::fieldsMemory($rsValor, 0)->valor;
  }

  public function aplicaPercentDotacoes($aClassificacoes = array(), $aEntes = array())
  {
    $aRetorno = array();

    foreach ($aEntes as $nId => $nPercentual) {

      $oEnte = new stdClass();
      $oEnte->dotacoes          = array();
      $oEnte->enteconsorciado   = intval($nId);
      $oEnte->percentualrateio  = floatval($nPercentual);

      foreach ($aClassificacoes as $sHash => $oDotacao) {

        $oNovaDotacao = new stdClass();

        $oNovaDotacao->funcao                 = $oDotacao->funcao;
        $oNovaDotacao->subfuncao              = $oDotacao->subfuncao;
        $oNovaDotacao->natureza               = $oDotacao->natureza;
        $oNovaDotacao->subelemento            = $oDotacao->subelemento;
        $oNovaDotacao->fonte                  = $oDotacao->fonte;
        $oNovaDotacao->valorempenhado         = round($oDotacao->emp,2);
        $oNovaDotacao->valorempenhadoanulado  = isset($oDotacao->empanulado) ? $oDotacao->empanulado : 0;
        $oNovaDotacao->valorliquidado         = round($oDotacao->lqd,2);
        $oNovaDotacao->valorliquidadoanulado  = isset($oDotacao->lqdanulado) ? $oDotacao->lqdanulado : 0;
        $oNovaDotacao->valorpago              = isset($oDotacao->pag) ? round($oDotacao->pag,2) : 0;
        $oNovaDotacao->valorpagoanulado       = isset($oDotacao->paganulado) ? $oDotacao->paganulado : 0;

        $oNovaDotacao->valorempenhado = round(($oNovaDotacao->valorempenhado * $nPercentual) / 100,2);
        $oNovaDotacao->valorliquidado = round(($oNovaDotacao->valorliquidado * $nPercentual) / 100,2);
        $oNovaDotacao->valorpago      = round(($oNovaDotacao->valorpago * $nPercentual) / 100,2);

        $oEnte->dotacoes[$sHash] = $oNovaDotacao;

      }

      $aRetorno[$nId] = $oEnte;

    }

    return $aRetorno;

  }


}
