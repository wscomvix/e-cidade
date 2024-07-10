<?
//MODULO: caixa
//CLASSE DA ENTIDADE concmanupendeextrato
class cl_concmanupendeextrato { 
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
   var $k200_datapendencia_dia = null; 
   var $k200_datapendencia_mes = null; 
   var $k200_datapendencia_ano = null; 
   var $k200_datapendencia = null; 
   var $k200_valor = 0; 
   var $k200_descricao = null; 
   var $k200_sequencial = 0; 
   var $k200_conciliacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k200_datapendencia = date =  
                 k200_valor = float8 =  
                 k200_descricao = varchar(30) =  
                 k200_sequencial = int8 = C�digo  sequencial 
                 k200_conciliacao = int8 =  
                 ";
   //funcao construtor da classe 
   function cl_concmanupendeextrato() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("concmanupendeextrato"); 
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
       if($this->k200_datapendencia == ""){
         $this->k200_datapendencia_dia = ($this->k200_datapendencia_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k200_datapendencia_dia"]:$this->k200_datapendencia_dia);
         $this->k200_datapendencia_mes = ($this->k200_datapendencia_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k200_datapendencia_mes"]:$this->k200_datapendencia_mes);
         $this->k200_datapendencia_ano = ($this->k200_datapendencia_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k200_datapendencia_ano"]:$this->k200_datapendencia_ano);
         if($this->k200_datapendencia_dia != ""){
            $this->k200_datapendencia = $this->k200_datapendencia_ano."-".$this->k200_datapendencia_mes."-".$this->k200_datapendencia_dia;
         }
       }
       $this->k200_valor = ($this->k200_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k200_valor"]:$this->k200_valor);
       $this->k200_descricao = ($this->k200_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["k200_descricao"]:$this->k200_descricao);
       $this->k200_sequencial = ($this->k200_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k200_sequencial"]:$this->k200_sequencial);
       $this->k200_conciliacao = ($this->k200_conciliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k200_conciliacao"]:$this->k200_conciliacao);
     }else{
       $this->k200_sequencial = ($this->k200_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k200_sequencial"]:$this->k200_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k200_sequencial){ 
      $this->atualizacampos();
     if($this->k200_datapendencia == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "k200_datapendencia_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k200_valor == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "k200_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k200_descricao == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "k200_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k200_conciliacao == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "k200_conciliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k200_sequencial = $k200_sequencial; 
     if(($this->k200_sequencial == null) || ($this->k200_sequencial == "") ){ 
       $this->erro_sql = " Campo k200_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into concmanupendeextrato(
                                       k200_datapendencia 
                                      ,k200_valor 
                                      ,k200_descricao 
                                      ,k200_sequencial 
                                      ,k200_conciliacao 
                       )
                values (
                                ".($this->k200_datapendencia == "null" || $this->k200_datapendencia == ""?"null":"'".$this->k200_datapendencia."'")." 
                               ,$this->k200_valor 
                               ,'$this->k200_descricao' 
                               ,$this->k200_sequencial 
                               ,$this->k200_conciliacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->k200_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->k200_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k200_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k200_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009251,'$this->k200_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2010213,2009346,'','".AddSlashes(pg_result($resaco,0,'k200_datapendencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010213,2009349,'','".AddSlashes(pg_result($resaco,0,'k200_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010213,2009347,'','".AddSlashes(pg_result($resaco,0,'k200_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010213,2009348,'','".AddSlashes(pg_result($resaco,0,'k200_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010213,2009345,'','".AddSlashes(pg_result($resaco,0,'k200_conciliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k200_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update concmanupendeextrato set ";
     $virgula = "";
     if(trim($this->k200_datapendencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k200_datapendencia_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k200_datapendencia_dia"] !="") ){ 
       $sql  .= $virgula." k200_datapendencia = '$this->k200_datapendencia' ";
       $virgula = ",";
       if(trim($this->k200_datapendencia) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "k200_datapendencia_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k200_datapendencia_dia"])){ 
         $sql  .= $virgula." k200_datapendencia = null ";
         $virgula = ",";
         if(trim($this->k200_datapendencia) == null ){ 
           $this->erro_sql = " Campo  nao Informado.";
           $this->erro_campo = "k200_datapendencia_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k200_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k200_valor"])){ 
       $sql  .= $virgula." k200_valor = $this->k200_valor ";
       $virgula = ",";
       if(trim($this->k200_valor) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "k200_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k200_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k200_descricao"])){ 
       $sql  .= $virgula." k200_descricao = '$this->k200_descricao' ";
       $virgula = ",";
       if(trim($this->k200_descricao) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "k200_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k200_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k200_sequencial"])){ 
       $sql  .= $virgula." k200_sequencial = $this->k200_sequencial ";
       $virgula = ",";
       if(trim($this->k200_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo  sequencial nao Informado.";
         $this->erro_campo = "k200_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k200_conciliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k200_conciliacao"])){ 
       $sql  .= $virgula." k200_conciliacao = $this->k200_conciliacao ";
       $virgula = ",";
       if(trim($this->k200_conciliacao) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "k200_conciliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k200_sequencial!=null){
       $sql .= " k200_sequencial = $this->k200_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k200_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009251,'$this->k200_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k200_datapendencia"]) || $this->k200_datapendencia != "")
           $resac = db_query("insert into db_acount values($acount,2010213,2009346,'".AddSlashes(pg_result($resaco,$conresaco,'k200_datapendencia'))."','$this->k200_datapendencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k200_valor"]) || $this->k200_valor != "")
           $resac = db_query("insert into db_acount values($acount,2010213,2009349,'".AddSlashes(pg_result($resaco,$conresaco,'k200_valor'))."','$this->k200_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k200_descricao"]) || $this->k200_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2010213,2009347,'".AddSlashes(pg_result($resaco,$conresaco,'k200_descricao'))."','$this->k200_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k200_sequencial"]) || $this->k200_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2010213,2009348,'".AddSlashes(pg_result($resaco,$conresaco,'k200_sequencial'))."','$this->k200_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k200_conciliacao"]) || $this->k200_conciliacao != "")
           $resac = db_query("insert into db_acount values($acount,2010213,2009345,'".AddSlashes(pg_result($resaco,$conresaco,'k200_conciliacao'))."','$this->k200_conciliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k200_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k200_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k200_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k200_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k200_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009251,'$k200_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2010213,2009346,'','".AddSlashes(pg_result($resaco,$iresaco,'k200_datapendencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010213,2009349,'','".AddSlashes(pg_result($resaco,$iresaco,'k200_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010213,2009347,'','".AddSlashes(pg_result($resaco,$iresaco,'k200_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010213,2009348,'','".AddSlashes(pg_result($resaco,$iresaco,'k200_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010213,2009345,'','".AddSlashes(pg_result($resaco,$iresaco,'k200_conciliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from concmanupendeextrato
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k200_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k200_sequencial = $k200_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k200_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k200_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k200_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:concmanupendeextrato";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k200_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from concmanupendeextrato ";
     $sql .= "      inner join conciliacao  on  conciliacao.k199_sequencial = concmanupendeextrato.k200_conciliacao";
     $sql .= "      inner join contabancaria  on  contabancaria.db83_sequencial = conciliacao.k199_codconta";
     $sql2 = "";
     if($dbwhere==""){
       if($k200_sequencial!=null ){
         $sql2 .= " where concmanupendeextrato.k200_sequencial = $k200_sequencial "; 
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
   function sql_query_file ( $k200_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from concmanupendeextrato ";
     $sql2 = "";
     if($dbwhere==""){
       if($k200_sequencial!=null ){
         $sql2 .= " where concmanupendeextrato.k200_sequencial = $k200_sequencial "; 
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
