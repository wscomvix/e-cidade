<?
//MODULO: contabilidade
//CLASSE DA ENTIDADE consdispcaixaano
class cl_consdispcaixaano { 
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
   var $c203_sequencial = 0; 
   var $c203_consconsorcios = 0; 
   var $c203_codfontrecursos = 0; 
   var $c203_valor = 0; 
   var $c203_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c203_sequencial = int8 = C�digo Sequencial 
                 c203_consconsorcios = int8 = C�digo Cons�rcio 
                 c203_codfontrecursos = int8 = C�digo da fonte de recursos
                 c203_valor = float8 = Disponibilidade de Caixa 31/12 do ano corrente 
                 c203_anousu = int8 = Ano 
                 ";
   //funcao construtor da classe 
   function cl_consdispcaixaano() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("consdispcaixaano"); 
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
       $this->c203_sequencial = ($this->c203_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c203_sequencial"]:$this->c203_sequencial);
       $this->c203_consconsorcios = ($this->c203_consconsorcios == ""?@$GLOBALS["HTTP_POST_VARS"]["c203_consconsorcios"]:$this->c203_consconsorcios);
       $this->c203_codfontrecursos = ($this->c203_codfontrecursos == ""?@$GLOBALS["HTTP_POST_VARS"]["c203_codfontrecursos"]:$this->c203_codfontrecursos);
       $this->c203_valor = ($this->c203_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["c203_valor"]:$this->c203_valor);
       $this->c203_anousu = ($this->c203_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c203_anousu"]:$this->c203_anousu);
     }else{
       $this->c203_sequencial = ($this->c203_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c203_sequencial"]:$this->c203_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c203_sequencial){ 
      $this->atualizacampos();
     if($this->c203_consconsorcios == null ){ 
       $this->erro_sql = " Campo C�digo Cons�rcio nao Informado.";
       $this->erro_campo = "c203_consconsorcios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c203_valor == null ){ 
       $this->erro_sql = " Campo Disponibilidade de Caixa 31/12 do ano corrente nao Informado.";
       $this->erro_campo = "c203_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c203_codfontrecursos == null ){ 
       $this->erro_sql = " Campo Fonte de recursos nao Informado.";
       $this->erro_campo = "c203_codfontrecursos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c203_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "c203_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c203_sequencial == "" || $c203_sequencial == null ){
       $result = db_query("select nextval('consdispcaixaano_c203_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: consdispcaixaano_c203_sequencial_seq do campo: c203_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c203_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from consdispcaixaano_c203_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c203_sequencial)){
         $this->erro_sql = " Campo c203_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c203_sequencial = $c203_sequencial; 
       }
     }
     if(($this->c203_sequencial == null) || ($this->c203_sequencial == "") ){ 
       $this->erro_sql = " Campo c203_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into consdispcaixaano(
                                       c203_sequencial 
                                      ,c203_consconsorcios 
                                      ,c203_codfontrecursos
                                      ,c203_valor 
                                      ,c203_anousu 
                       )
                values (
                                $this->c203_sequencial 
                               ,$this->c203_consconsorcios 
                               ,$this->c203_codfontrecursos
                               ,$this->c203_valor 
                               ,$this->c203_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Disponibilidade de Caixa 31/12 do ano ($this->c203_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Disponibilidade de Caixa 31/12 do ano j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Disponibilidade de Caixa 31/12 do ano ($this->c203_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c203_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c203_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2009428,'$this->c203_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2010220,2009428,'','".AddSlashes(pg_result($resaco,0,'c203_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010220,2009429,'','".AddSlashes(pg_result($resaco,0,'c203_consconsorcios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010220,2009431,'','".AddSlashes(pg_result($resaco,0,'c203_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010220,2009432,'','".AddSlashes(pg_result($resaco,0,'c203_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c203_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update consdispcaixaano set ";
     $virgula = "";
     if(trim($this->c203_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c203_sequencial"])){ 
       $sql  .= $virgula." c203_sequencial = $this->c203_sequencial ";
       $virgula = ",";
       if(trim($this->c203_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "c203_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c203_consconsorcios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c203_consconsorcios"])){ 
       $sql  .= $virgula." c203_consconsorcios = $this->c203_consconsorcios ";
       $virgula = ",";
       if(trim($this->c203_consconsorcios) == null ){ 
         $this->erro_sql = " Campo C�digo Cons�rcio nao Informado.";
         $this->erro_campo = "c203_consconsorcios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c203_codfontrecursos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c203_codfontrecursos"])){ 
       $sql  .= $virgula." c203_codfontrecursos = $this->c203_codfontrecursos ";
       $virgula = ",";
       if(trim($this->c203_codfontrecursos) == null ){ 
         $this->erro_sql = " Campo Fonte de recursos nao Informado.";
         $this->erro_campo = "c203_codfontrecursos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c203_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c203_valor"])){ 
       $sql  .= $virgula." c203_valor = $this->c203_valor ";
       $virgula = ",";
       if(trim($this->c203_valor) == null ){ 
         $this->erro_sql = " Campo Disponibilidade de Caixa 31/12 do ano corrente nao Informado.";
         $this->erro_campo = "c203_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c203_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c203_anousu"])){ 
       $sql  .= $virgula." c203_anousu = $this->c203_anousu ";
       $virgula = ",";
       if(trim($this->c203_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "c203_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c203_sequencial!=null){
       $sql .= " c203_sequencial = $this->c203_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c203_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2009428,'$this->c203_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c203_sequencial"]) || $this->c203_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2010220,2009428,'".AddSlashes(pg_result($resaco,$conresaco,'c203_sequencial'))."','$this->c203_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c203_consconsorcios"]) || $this->c203_consconsorcios != "")
           $resac = db_query("insert into db_acount values($acount,2010220,2009429,'".AddSlashes(pg_result($resaco,$conresaco,'c203_consconsorcios'))."','$this->c203_consconsorcios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c203_valor"]) || $this->c203_valor != "")
           $resac = db_query("insert into db_acount values($acount,2010220,2009431,'".AddSlashes(pg_result($resaco,$conresaco,'c203_valor'))."','$this->c203_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c203_anousu"]) || $this->c203_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2010220,2009432,'".AddSlashes(pg_result($resaco,$conresaco,'c203_anousu'))."','$this->c203_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Disponibilidade de Caixa 31/12 do ano nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c203_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Disponibilidade de Caixa 31/12 do ano nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c203_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c203_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c203_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c203_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2009428,'$c203_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2010220,2009428,'','".AddSlashes(pg_result($resaco,$iresaco,'c203_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010220,2009429,'','".AddSlashes(pg_result($resaco,$iresaco,'c203_consconsorcios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010220,2009431,'','".AddSlashes(pg_result($resaco,$iresaco,'c203_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010220,2009432,'','".AddSlashes(pg_result($resaco,$iresaco,'c203_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from consdispcaixaano
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c203_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c203_sequencial = $c203_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Disponibilidade de Caixa 31/12 do ano nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c203_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Disponibilidade de Caixa 31/12 do ano nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c203_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c203_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:consdispcaixaano";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c203_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from consdispcaixaano ";
     $sql .= "      inner join consconsorcios  on  consconsorcios.c200_sequencial = consdispcaixaano.c203_consconsorcios";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = consconsorcios.c200_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($c203_sequencial!=null ){
         $sql2 .= " where consdispcaixaano.c203_sequencial = $c203_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $c203_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from consdispcaixaano ";
     $sql2 = "";
     if($dbwhere==""){
       if($c203_sequencial!=null ){
         $sql2 .= " where consdispcaixaano.c203_sequencial = $c203_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
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
