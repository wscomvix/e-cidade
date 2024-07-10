<?
//MODULO: sicom
//CLASSE DA ENTIDADE homolic102014
class cl_homolic102014 { 
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
   var $si63_sequencial = 0; 
   var $si63_tiporegistro = 0; 
   var $si63_codorgao = null; 
   var $si63_codunidadesub = null; 
   var $si63_exerciciolicitacao = 0; 
   var $si63_nroprocessolicitatorio = null; 
   var $si63_tipodocumento = 0; 
   var $si63_nrodocumento = null; 
   var $si63_nrolote = 0; 
   var $si63_coditem = 0; 
   var $si63_quantidade = 0; 
   var $si63_vlunitariohomologado = 0; 
   var $si63_mes = 0; 
   var $si63_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 si63_sequencial = int8 = sequencial 
                 si63_tiporegistro = int8 = Tipo do  registro 
                 si63_codorgao = varchar(2) = C�digo do �rg�o 
                 si63_codunidadesub = varchar(8) = C�digo da unidade 
                 si63_exerciciolicitacao = int8 = Exerc�cio em que  foi instaurado 
                 si63_nroprocessolicitatorio = varchar(12) = N�mero sequencial do processo 
                 si63_tipodocumento = int8 = Tipo do  documento 
                 si63_nrodocumento = varchar(14) = N�mero do  documento 
                 si63_nrolote = int8 = N�mero do Lote 
                 si63_coditem = int8 = C�digo do item 
                 si63_quantidade = float8 = Quantidade do item 
                 si63_vlunitariohomologado = float8 = Valor unit�rio  homologado 
                 si63_mes = int8 = M�s 
                 si63_instit = int8 = Institui��o 
                 ";
   //funcao construtor da classe 
   function cl_homolic102014() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("homolic102014"); 
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
       $this->si63_sequencial = ($this->si63_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_sequencial"]:$this->si63_sequencial);
       $this->si63_tiporegistro = ($this->si63_tiporegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_tiporegistro"]:$this->si63_tiporegistro);
       $this->si63_codorgao = ($this->si63_codorgao == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_codorgao"]:$this->si63_codorgao);
       $this->si63_codunidadesub = ($this->si63_codunidadesub == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_codunidadesub"]:$this->si63_codunidadesub);
       $this->si63_exerciciolicitacao = ($this->si63_exerciciolicitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_exerciciolicitacao"]:$this->si63_exerciciolicitacao);
       $this->si63_nroprocessolicitatorio = ($this->si63_nroprocessolicitatorio == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_nroprocessolicitatorio"]:$this->si63_nroprocessolicitatorio);
       $this->si63_tipodocumento = ($this->si63_tipodocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_tipodocumento"]:$this->si63_tipodocumento);
       $this->si63_nrodocumento = ($this->si63_nrodocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_nrodocumento"]:$this->si63_nrodocumento);
       $this->si63_nrolote = ($this->si63_nrolote == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_nrolote"]:$this->si63_nrolote);
       $this->si63_coditem = ($this->si63_coditem == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_coditem"]:$this->si63_coditem);
       $this->si63_quantidade = ($this->si63_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_quantidade"]:$this->si63_quantidade);
       $this->si63_vlunitariohomologado = ($this->si63_vlunitariohomologado == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_vlunitariohomologado"]:$this->si63_vlunitariohomologado);
       $this->si63_mes = ($this->si63_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_mes"]:$this->si63_mes);
       $this->si63_instit = ($this->si63_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_instit"]:$this->si63_instit);
     }else{
       $this->si63_sequencial = ($this->si63_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si63_sequencial"]:$this->si63_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($si63_sequencial){ 
      $this->atualizacampos();
     if($this->si63_tiporegistro == null ){ 
       $this->erro_sql = " Campo Tipo do  registro nao Informado.";
       $this->erro_campo = "si63_tiporegistro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si63_exerciciolicitacao == null ){ 
       $this->si63_exerciciolicitacao = "0";
     }
     if($this->si63_tipodocumento == null ){ 
       $this->si63_tipodocumento = "0";
     }
     if($this->si63_nrolote == null ){ 
       $this->si63_nrolote = "0";
     }
     if($this->si63_coditem == null ){ 
       $this->si63_coditem = "0";
     }
     if($this->si63_quantidade == null ){ 
       $this->si63_quantidade = "0";
     }
     if($this->si63_vlunitariohomologado == null ){ 
       $this->si63_vlunitariohomologado = "0";
     }
     if($this->si63_mes == null ){ 
       $this->erro_sql = " Campo M�s nao Informado.";
       $this->erro_campo = "si63_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si63_instit == null ){ 
       $this->erro_sql = " Campo Institui��o nao Informado.";
       $this->erro_campo = "si63_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($si63_sequencial == "" || $si63_sequencial == null ){
       $result = db_query("select nextval('homolic102014_si63_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: homolic102014_si63_sequencial_seq do campo: si63_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->si63_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from homolic102014_si63_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $si63_sequencial)){
         $this->erro_sql = " Campo si63_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->si63_sequencial = $si63_sequencial; 
       }
     }
     if(($this->si63_sequencial == null) || ($this->si63_sequencial == "") ){ 
       $this->erro_sql = " Campo si63_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into homolic102014(
                                       si63_sequencial 
                                      ,si63_tiporegistro 
                                      ,si63_codorgao 
                                      ,si63_codunidadesub 
                                      ,si63_exerciciolicitacao 
                                      ,si63_nroprocessolicitatorio 
                                      ,si63_tipodocumento 
                                      ,si63_nrodocumento 
                                      ,si63_nrolote 
                                      ,si63_coditem 
                                      ,si63_quantidade 
                                      ,si63_vlunitariohomologado 
                                      ,si63_mes 
                                      ,si63_instit 
                       )
                values (
                                $this->si63_sequencial 
                               ,$this->si63_tiporegistro 
                               ,'$this->si63_codorgao' 
                               ,'$this->si63_codunidadesub' 
                               ,$this->si63_exerciciolicitacao 
                               ,'$this->si63_nroprocessolicitatorio' 
                               ,$this->si63_tipodocumento 
                               ,'$this->si63_nrodocumento' 
                               ,$this->si63_nrolote 
                               ,$this->si63_coditem 
                               ,$this->si63_quantidade 
                               ,$this->si63_vlunitariohomologado 
                               ,$this->si63_mes 
                               ,$this->si63_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "homolic102014 ($this->si63_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "homolic102014 j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "homolic102014 ($this->si63_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->si63_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->si63_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2010119,'$this->si63_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2010292,2010119,'','".AddSlashes(pg_result($resaco,0,'si63_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010292,2010120,'','".AddSlashes(pg_result($resaco,0,'si63_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010292,2010121,'','".AddSlashes(pg_result($resaco,0,'si63_codorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010292,2010122,'','".AddSlashes(pg_result($resaco,0,'si63_codunidadesub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010292,2010123,'','".AddSlashes(pg_result($resaco,0,'si63_exerciciolicitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010292,2010124,'','".AddSlashes(pg_result($resaco,0,'si63_nroprocessolicitatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010292,2010125,'','".AddSlashes(pg_result($resaco,0,'si63_tipodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010292,2010126,'','".AddSlashes(pg_result($resaco,0,'si63_nrodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010292,2010127,'','".AddSlashes(pg_result($resaco,0,'si63_nrolote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010292,2010128,'','".AddSlashes(pg_result($resaco,0,'si63_coditem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010292,2010129,'','".AddSlashes(pg_result($resaco,0,'si63_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010292,2010130,'','".AddSlashes(pg_result($resaco,0,'si63_vlunitariohomologado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010292,2010131,'','".AddSlashes(pg_result($resaco,0,'si63_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010292,2011575,'','".AddSlashes(pg_result($resaco,0,'si63_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($si63_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update homolic102014 set ";
     $virgula = "";
     if(trim($this->si63_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_sequencial"])){ 
        if(trim($this->si63_sequencial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["si63_sequencial"])){ 
           $this->si63_sequencial = "0" ; 
        } 
       $sql  .= $virgula." si63_sequencial = $this->si63_sequencial ";
       $virgula = ",";
     }
     if(trim($this->si63_tiporegistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_tiporegistro"])){ 
       $sql  .= $virgula." si63_tiporegistro = $this->si63_tiporegistro ";
       $virgula = ",";
       if(trim($this->si63_tiporegistro) == null ){ 
         $this->erro_sql = " Campo Tipo do  registro nao Informado.";
         $this->erro_campo = "si63_tiporegistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si63_codorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_codorgao"])){ 
       $sql  .= $virgula." si63_codorgao = '$this->si63_codorgao' ";
       $virgula = ",";
     }
     if(trim($this->si63_codunidadesub)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_codunidadesub"])){ 
       $sql  .= $virgula." si63_codunidadesub = '$this->si63_codunidadesub' ";
       $virgula = ",";
     }
     if(trim($this->si63_exerciciolicitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_exerciciolicitacao"])){ 
        if(trim($this->si63_exerciciolicitacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["si63_exerciciolicitacao"])){ 
           $this->si63_exerciciolicitacao = "0" ; 
        } 
       $sql  .= $virgula." si63_exerciciolicitacao = $this->si63_exerciciolicitacao ";
       $virgula = ",";
     }
     if(trim($this->si63_nroprocessolicitatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_nroprocessolicitatorio"])){ 
       $sql  .= $virgula." si63_nroprocessolicitatorio = '$this->si63_nroprocessolicitatorio' ";
       $virgula = ",";
     }
     if(trim($this->si63_tipodocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_tipodocumento"])){ 
        if(trim($this->si63_tipodocumento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["si63_tipodocumento"])){ 
           $this->si63_tipodocumento = "0" ; 
        } 
       $sql  .= $virgula." si63_tipodocumento = $this->si63_tipodocumento ";
       $virgula = ",";
     }
     if(trim($this->si63_nrodocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_nrodocumento"])){ 
       $sql  .= $virgula." si63_nrodocumento = '$this->si63_nrodocumento' ";
       $virgula = ",";
     }
     if(trim($this->si63_nrolote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_nrolote"])){ 
        if(trim($this->si63_nrolote)=="" && isset($GLOBALS["HTTP_POST_VARS"]["si63_nrolote"])){ 
           $this->si63_nrolote = "0" ; 
        } 
       $sql  .= $virgula." si63_nrolote = $this->si63_nrolote ";
       $virgula = ",";
     }
     if(trim($this->si63_coditem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_coditem"])){ 
        if(trim($this->si63_coditem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["si63_coditem"])){ 
           $this->si63_coditem = "0" ; 
        } 
       $sql  .= $virgula." si63_coditem = $this->si63_coditem ";
       $virgula = ",";
     }
     if(trim($this->si63_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_quantidade"])){ 
        if(trim($this->si63_quantidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["si63_quantidade"])){ 
           $this->si63_quantidade = "0" ; 
        } 
       $sql  .= $virgula." si63_quantidade = $this->si63_quantidade ";
       $virgula = ",";
     }
     if(trim($this->si63_vlunitariohomologado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_vlunitariohomologado"])){ 
        if(trim($this->si63_vlunitariohomologado)=="" && isset($GLOBALS["HTTP_POST_VARS"]["si63_vlunitariohomologado"])){ 
           $this->si63_vlunitariohomologado = "0" ; 
        } 
       $sql  .= $virgula." si63_vlunitariohomologado = $this->si63_vlunitariohomologado ";
       $virgula = ",";
     }
     if(trim($this->si63_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_mes"])){ 
       $sql  .= $virgula." si63_mes = $this->si63_mes ";
       $virgula = ",";
       if(trim($this->si63_mes) == null ){ 
         $this->erro_sql = " Campo M�s nao Informado.";
         $this->erro_campo = "si63_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si63_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si63_instit"])){ 
       $sql  .= $virgula." si63_instit = $this->si63_instit ";
       $virgula = ",";
       if(trim($this->si63_instit) == null ){ 
         $this->erro_sql = " Campo Institui��o nao Informado.";
         $this->erro_campo = "si63_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($si63_sequencial!=null){
       $sql .= " si63_sequencial = $this->si63_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->si63_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2010119,'$this->si63_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_sequencial"]) || $this->si63_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2010119,'".AddSlashes(pg_result($resaco,$conresaco,'si63_sequencial'))."','$this->si63_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_tiporegistro"]) || $this->si63_tiporegistro != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2010120,'".AddSlashes(pg_result($resaco,$conresaco,'si63_tiporegistro'))."','$this->si63_tiporegistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_codorgao"]) || $this->si63_codorgao != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2010121,'".AddSlashes(pg_result($resaco,$conresaco,'si63_codorgao'))."','$this->si63_codorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_codunidadesub"]) || $this->si63_codunidadesub != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2010122,'".AddSlashes(pg_result($resaco,$conresaco,'si63_codunidadesub'))."','$this->si63_codunidadesub',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_exerciciolicitacao"]) || $this->si63_exerciciolicitacao != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2010123,'".AddSlashes(pg_result($resaco,$conresaco,'si63_exerciciolicitacao'))."','$this->si63_exerciciolicitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_nroprocessolicitatorio"]) || $this->si63_nroprocessolicitatorio != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2010124,'".AddSlashes(pg_result($resaco,$conresaco,'si63_nroprocessolicitatorio'))."','$this->si63_nroprocessolicitatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_tipodocumento"]) || $this->si63_tipodocumento != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2010125,'".AddSlashes(pg_result($resaco,$conresaco,'si63_tipodocumento'))."','$this->si63_tipodocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_nrodocumento"]) || $this->si63_nrodocumento != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2010126,'".AddSlashes(pg_result($resaco,$conresaco,'si63_nrodocumento'))."','$this->si63_nrodocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_nrolote"]) || $this->si63_nrolote != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2010127,'".AddSlashes(pg_result($resaco,$conresaco,'si63_nrolote'))."','$this->si63_nrolote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_coditem"]) || $this->si63_coditem != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2010128,'".AddSlashes(pg_result($resaco,$conresaco,'si63_coditem'))."','$this->si63_coditem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_quantidade"]) || $this->si63_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2010129,'".AddSlashes(pg_result($resaco,$conresaco,'si63_quantidade'))."','$this->si63_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_vlunitariohomologado"]) || $this->si63_vlunitariohomologado != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2010130,'".AddSlashes(pg_result($resaco,$conresaco,'si63_vlunitariohomologado'))."','$this->si63_vlunitariohomologado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_mes"]) || $this->si63_mes != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2010131,'".AddSlashes(pg_result($resaco,$conresaco,'si63_mes'))."','$this->si63_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si63_instit"]) || $this->si63_instit != "")
           $resac = db_query("insert into db_acount values($acount,2010292,2011575,'".AddSlashes(pg_result($resaco,$conresaco,'si63_instit'))."','$this->si63_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "homolic102014 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->si63_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "homolic102014 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->si63_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->si63_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($si63_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($si63_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2010119,'$si63_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2010292,2010119,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010292,2010120,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010292,2010121,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_codorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010292,2010122,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_codunidadesub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010292,2010123,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_exerciciolicitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010292,2010124,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_nroprocessolicitatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010292,2010125,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_tipodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010292,2010126,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_nrodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010292,2010127,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_nrolote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010292,2010128,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_coditem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010292,2010129,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010292,2010130,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_vlunitariohomologado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010292,2010131,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010292,2011575,'','".AddSlashes(pg_result($resaco,$iresaco,'si63_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from homolic102014
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($si63_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " si63_sequencial = $si63_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "homolic102014 nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$si63_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "homolic102014 nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$si63_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$si63_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:homolic102014";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $si63_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from homolic102014 ";
     $sql2 = "";
     if($dbwhere==""){
       if($si63_sequencial!=null ){
         $sql2 .= " where homolic102014.si63_sequencial = $si63_sequencial "; 
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
   function sql_query_file ( $si63_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from homolic102014 ";
     $sql2 = "";
     if($dbwhere==""){
       if($si63_sequencial!=null ){
         $sql2 .= " where homolic102014.si63_sequencial = $si63_sequencial "; 
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
