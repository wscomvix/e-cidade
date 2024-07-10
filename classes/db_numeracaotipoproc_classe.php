<?
//MODULO: protocolo
//CLASSE DA ENTIDADE numeracaotipoproc
class cl_numeracaotipoproc { 
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
   var $p200_codigo = 0; 
   var $p200_ano = 0; 
   var $p200_numeracao = 0; 
   var $p200_tipoproc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p200_codigo = int4 = C�digo 
                 p200_ano = int4 = Ano 
:                 p200_numeracao = int4 = Numera��o
                 p200_tipoproc = int4 = Tipo Proc 
                 ";
   //funcao construtor da classe 
   function cl_numeracaotipoproc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("numeracaotipoproc"); 
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
       $this->p200_codigo = ($this->p200_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p200_codigo"]:$this->p200_codigo);
       $this->p200_ano = ($this->p200_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p200_ano"]:$this->p200_ano);
       $this->p200_numeracao = ($this->p200_numeracao == ""?@$GLOBALS["HTTP_POST_VARS"]["p200_numeracao"]:$this->p200_numeracao);
       $this->p200_tipoproc = ($this->p200_tipoproc == ""?@$GLOBALS["HTTP_POST_VARS"]["p200_tipoproc"]:$this->p200_tipoproc);
     }else{
       $this->p200_codigo = ($this->p200_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p200_codigo"]:$this->p200_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($p200_codigo){
      $this->atualizacampos();
     if($this->p200_ano == null ){
       $this->erro_sql = " Campo Ano n�o informado.";
       $this->erro_campo = "p200_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p200_numeracao == null ){ 
	   $this->p200_numeracao = 0;
     }else{
       $this->p200_numeracao = $this->p200_numeracao;
     }
     if($this->p200_tipoproc == null ){ 
       $this->erro_sql = " Campo Tipo Proc n�o informado.";
       $this->erro_campo = "p200_tipoproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p200_codigo == "" || $p200_codigo == null ){
       $result = db_query("select nextval('numeracaotipoproc_p200_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicita_p200_codigo_seq do campo: p200_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p200_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from numeracaotipoproc_p200_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $p200_codigo)){
         $this->erro_sql = " Campo p200_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p200_codigo = $p200_codigo; 
       }
     }
     if(($this->p200_codigo == null) || ($this->p200_codigo == "") ){ 
       $this->erro_sql = " Campo p200_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into numeracaotipoproc(
                                       p200_codigo 
                                      ,p200_ano 
                                      ,p200_numeracao 
                                      ,p200_tipoproc 
                       )
                values (
                                $this->p200_codigo 
                               ,$this->p200_ano 
                               ,$this->p200_numeracao 
                               ,$this->p200_tipoproc 
                      )";

     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "numeracaotipoproc ($this->p200_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "numeracaotipoproc j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "numeracaotipoproc ($this->p200_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p200_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     /*$lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p200_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009255,'$this->p200_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010192,1009255,'','".AddSlashes(pg_result($resaco,0,'p200_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010192,1009256,'','".AddSlashes(pg_result($resaco,0,'p200_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010192,1009258,'','".AddSlashes(pg_result($resaco,0,'p200_numeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010192,1009257,'','".AddSlashes(pg_result($resaco,0,'p200_tipoproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }*/
     return true;
   } 
   // funcao para alteracao
   function alterar ($p200_codigo=null) { 
      $this->atualizacampos();
     $sql = " update numeracaotipoproc set ";
     $virgula = "";

     if(trim($this->p200_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p200_codigo"])){ 
       $sql  .= $virgula." p200_codigo = $this->p200_codigo ";
       $virgula = ",";
       if(trim($this->p200_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo n�o informado.";
         $this->erro_campo = "p200_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p200_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p200_ano"])){ 
       $sql  .= $virgula." p200_ano = $this->p200_ano ";
       $virgula = ",";
       if(trim($this->p200_ano) == null ){ 
         $this->erro_sql = " Campo Ano n�o informado.";
         $this->erro_campo = "p200_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p200_numeracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p200_numeracao"])){ 
       $sql  .= $virgula." p200_numeracao = $this->p200_numeracao ";
       $virgula = ",";
       if(trim($this->p200_numeracao) == null ){ 
         $this->erro_sql = " Campo Numera��o n�o informado.";
         $this->erro_campo = "p200_numeracao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p200_tipoproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p200_tipoproc"])){ 
       $sql  .= $virgula." p200_tipoproc = $this->p200_tipoproc ";
       $virgula = ",";
       if(trim($this->p200_tipoproc) == null ){ 
         $this->erro_sql = " Campo Tipo Proc n�o informado.";
         $this->erro_campo = "p200_tipoproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p200_codigo!=null){
       $sql .= " p200_codigo = $this->p200_codigo";
     }
     /*$lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p200_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1009255,'$this->p200_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p200_codigo"]) || $this->p200_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010192,1009255,'".AddSlashes(pg_result($resaco,$conresaco,'p200_codigo'))."','$this->p200_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p200_ano"]) || $this->p200_ano != "")
             $resac = db_query("insert into db_acount values($acount,1010192,1009256,'".AddSlashes(pg_result($resaco,$conresaco,'p200_ano'))."','$this->p200_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p200_numeracao"]) || $this->p200_numeracao != "")
             $resac = db_query("insert into db_acount values($acount,1010192,1009258,'".AddSlashes(pg_result($resaco,$conresaco,'p200_numeracao'))."','$this->p200_numeracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p200_tipoproc"]) || $this->p200_tipoproc != "")
             $resac = db_query("insert into db_acount values($acount,1010192,1009257,'".AddSlashes(pg_result($resaco,$conresaco,'p200_tipoproc'))."','$this->p200_tipoproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }*/
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "numeracaotipoproc nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p200_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "numeracaotipoproc nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p200_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p200_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p200_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($p200_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1009255,'$p200_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010192,1009255,'','".AddSlashes(pg_result($resaco,$iresaco,'p200_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010192,1009256,'','".AddSlashes(pg_result($resaco,$iresaco,'p200_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010192,1009258,'','".AddSlashes(pg_result($resaco,$iresaco,'p200_numeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010192,1009257,'','".AddSlashes(pg_result($resaco,$iresaco,'p200_tipoproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from numeracaotipoproc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p200_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p200_codigo = $p200_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "numeracaotipoproc nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p200_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "numeracaotipoproc nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p200_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p200_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:numeracaotipoproc";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $p200_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from numeracaotipoproc ";
     $sql2 = "";
     if($dbwhere==""){
       if($p200_codigo!=null ){
         $sql2 .= " where numeracaotipoproc.p200_codigo = $p200_codigo "; 
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
   // funcao do sql 
   function sql_query_file ( $p200_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from numeracaotipoproc ";
     $sql2 = "";
     if($dbwhere==""){
       if($p200_codigo!=null ){
         $sql2 .= " where numeracaotipoproc.p200_codigo = $p200_codigo "; 
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
}
?>
