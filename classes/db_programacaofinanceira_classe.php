<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: Caixa
//CLASSE DA ENTIDADE programacaofinanceira
class cl_programacaofinanceira { 
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
   var $k117_sequencial = 0; 
   var $k117_id_usuario = 0; 
   var $k117_data_dia = null; 
   var $k117_data_mes = null; 
   var $k117_data_ano = null; 
   var $k117_data = null; 
   var $k117_periodicidade = 0; 
   var $k117_valortotal = 0; 
   var $k117_diapagamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k117_sequencial = int4 = C�digo Sequencial 
                 k117_id_usuario = int4 = C�digo do Usu�ro 
                 k117_data = date = Data Inclus�o 
                 k117_periodicidade = int4 = Periodicidade 
                 k117_valortotal = float8 = Valor Total 
                 k117_diapagamento = int4 = Dia Padr�o do Pagamento 
                 ";
   //funcao construtor da classe 
   function cl_programacaofinanceira() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("programacaofinanceira"); 
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
       $this->k117_sequencial = ($this->k117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_sequencial"]:$this->k117_sequencial);
       $this->k117_id_usuario = ($this->k117_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_id_usuario"]:$this->k117_id_usuario);
       if($this->k117_data == ""){
         $this->k117_data_dia = ($this->k117_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_data_dia"]:$this->k117_data_dia);
         $this->k117_data_mes = ($this->k117_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_data_mes"]:$this->k117_data_mes);
         $this->k117_data_ano = ($this->k117_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_data_ano"]:$this->k117_data_ano);
         if($this->k117_data_dia != ""){
            $this->k117_data = $this->k117_data_ano."-".$this->k117_data_mes."-".$this->k117_data_dia;
         }
       }
       $this->k117_periodicidade = ($this->k117_periodicidade == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_periodicidade"]:$this->k117_periodicidade);
       $this->k117_valortotal = ($this->k117_valortotal == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_valortotal"]:$this->k117_valortotal);
       $this->k117_diapagamento = ($this->k117_diapagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_diapagamento"]:$this->k117_diapagamento);
     }else{
       $this->k117_sequencial = ($this->k117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_sequencial"]:$this->k117_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k117_sequencial){ 
      $this->atualizacampos();
     if($this->k117_id_usuario == null ){ 
       $this->erro_sql = " Campo C�digo do Usu�ro nao Informado.";
       $this->erro_campo = "k117_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k117_data == null ){ 
       $this->erro_sql = " Campo Data Inclus�o nao Informado.";
       $this->erro_campo = "k117_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k117_periodicidade == null ){ 
       $this->erro_sql = " Campo Periodicidade nao Informado.";
       $this->erro_campo = "k117_periodicidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k117_valortotal == null ){ 
       $this->erro_sql = " Campo Valor Total nao Informado.";
       $this->erro_campo = "k117_valortotal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k117_diapagamento == null ){ 
       $this->erro_sql = " Campo Dia Padr�o do Pagamento nao Informado.";
       $this->erro_campo = "k117_diapagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k117_sequencial == "" || $k117_sequencial == null ){
       $result = db_query("select nextval('programacaofinanceira_k117_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: programacaofinanceira_k117_sequencial_seq do campo: k117_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k117_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from programacaofinanceira_k117_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k117_sequencial)){
         $this->erro_sql = " Campo k117_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k117_sequencial = $k117_sequencial; 
       }
     }
     if(($this->k117_sequencial == null) || ($this->k117_sequencial == "") ){ 
       $this->erro_sql = " Campo k117_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into programacaofinanceira(
                                       k117_sequencial 
                                      ,k117_id_usuario 
                                      ,k117_data 
                                      ,k117_periodicidade 
                                      ,k117_valortotal 
                                      ,k117_diapagamento 
                       )
                values (
                                $this->k117_sequencial 
                               ,$this->k117_id_usuario 
                               ,".($this->k117_data == "null" || $this->k117_data == ""?"null":"'".$this->k117_data."'")." 
                               ,$this->k117_periodicidade 
                               ,$this->k117_valortotal 
                               ,$this->k117_diapagamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Programa��o Financeira ($this->k117_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Programa��o Financeira j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Programa��o Financeira ($this->k117_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k117_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k117_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17125,'$this->k117_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3025,17125,'','".AddSlashes(pg_result($resaco,0,'k117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3025,17126,'','".AddSlashes(pg_result($resaco,0,'k117_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3025,17127,'','".AddSlashes(pg_result($resaco,0,'k117_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3025,17128,'','".AddSlashes(pg_result($resaco,0,'k117_periodicidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3025,17129,'','".AddSlashes(pg_result($resaco,0,'k117_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3025,17130,'','".AddSlashes(pg_result($resaco,0,'k117_diapagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k117_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update programacaofinanceira set ";
     $virgula = "";
     if(trim($this->k117_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k117_sequencial"])){ 
       $sql  .= $virgula." k117_sequencial = $this->k117_sequencial ";
       $virgula = ",";
       if(trim($this->k117_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "k117_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k117_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k117_id_usuario"])){ 
       $sql  .= $virgula." k117_id_usuario = $this->k117_id_usuario ";
       $virgula = ",";
       if(trim($this->k117_id_usuario) == null ){ 
         $this->erro_sql = " Campo C�digo do Usu�ro nao Informado.";
         $this->erro_campo = "k117_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k117_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k117_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k117_data_dia"] !="") ){ 
       $sql  .= $virgula." k117_data = '$this->k117_data' ";
       $virgula = ",";
       if(trim($this->k117_data) == null ){ 
         $this->erro_sql = " Campo Data Inclus�o nao Informado.";
         $this->erro_campo = "k117_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k117_data_dia"])){ 
         $sql  .= $virgula." k117_data = null ";
         $virgula = ",";
         if(trim($this->k117_data) == null ){ 
           $this->erro_sql = " Campo Data Inclus�o nao Informado.";
           $this->erro_campo = "k117_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k117_periodicidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k117_periodicidade"])){ 
       $sql  .= $virgula." k117_periodicidade = $this->k117_periodicidade ";
       $virgula = ",";
       if(trim($this->k117_periodicidade) == null ){ 
         $this->erro_sql = " Campo Periodicidade nao Informado.";
         $this->erro_campo = "k117_periodicidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k117_valortotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k117_valortotal"])){ 
       $sql  .= $virgula." k117_valortotal = $this->k117_valortotal ";
       $virgula = ",";
       if(trim($this->k117_valortotal) == null ){ 
         $this->erro_sql = " Campo Valor Total nao Informado.";
         $this->erro_campo = "k117_valortotal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k117_diapagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k117_diapagamento"])){ 
       $sql  .= $virgula." k117_diapagamento = $this->k117_diapagamento ";
       $virgula = ",";
       if(trim($this->k117_diapagamento) == null ){ 
         $this->erro_sql = " Campo Dia Padr�o do Pagamento nao Informado.";
         $this->erro_campo = "k117_diapagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k117_sequencial!=null){
       $sql .= " k117_sequencial = $this->k117_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k117_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17125,'$this->k117_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k117_sequencial"]) || $this->k117_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3025,17125,'".AddSlashes(pg_result($resaco,$conresaco,'k117_sequencial'))."','$this->k117_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k117_id_usuario"]) || $this->k117_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3025,17126,'".AddSlashes(pg_result($resaco,$conresaco,'k117_id_usuario'))."','$this->k117_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k117_data"]) || $this->k117_data != "")
           $resac = db_query("insert into db_acount values($acount,3025,17127,'".AddSlashes(pg_result($resaco,$conresaco,'k117_data'))."','$this->k117_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k117_periodicidade"]) || $this->k117_periodicidade != "")
           $resac = db_query("insert into db_acount values($acount,3025,17128,'".AddSlashes(pg_result($resaco,$conresaco,'k117_periodicidade'))."','$this->k117_periodicidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k117_valortotal"]) || $this->k117_valortotal != "")
           $resac = db_query("insert into db_acount values($acount,3025,17129,'".AddSlashes(pg_result($resaco,$conresaco,'k117_valortotal'))."','$this->k117_valortotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k117_diapagamento"]) || $this->k117_diapagamento != "")
           $resac = db_query("insert into db_acount values($acount,3025,17130,'".AddSlashes(pg_result($resaco,$conresaco,'k117_diapagamento'))."','$this->k117_diapagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programa��o Financeira nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k117_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Programa��o Financeira nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k117_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k117_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k117_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k117_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17125,'$k117_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3025,17125,'','".AddSlashes(pg_result($resaco,$iresaco,'k117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3025,17126,'','".AddSlashes(pg_result($resaco,$iresaco,'k117_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3025,17127,'','".AddSlashes(pg_result($resaco,$iresaco,'k117_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3025,17128,'','".AddSlashes(pg_result($resaco,$iresaco,'k117_periodicidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3025,17129,'','".AddSlashes(pg_result($resaco,$iresaco,'k117_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3025,17130,'','".AddSlashes(pg_result($resaco,$iresaco,'k117_diapagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from programacaofinanceira
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k117_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k117_sequencial = $k117_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programa��o Financeira nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k117_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Programa��o Financeira nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k117_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k117_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:programacaofinanceira";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k117_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from programacaofinanceira ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = programacaofinanceira.k117_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($k117_sequencial!=null ){
         $sql2 .= " where programacaofinanceira.k117_sequencial = $k117_sequencial "; 
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
   function sql_query_file ( $k117_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from programacaofinanceira ";
     $sql2 = "";
     if($dbwhere==""){
       if($k117_sequencial!=null ){
         $sql2 .= " where programacaofinanceira.k117_sequencial = $k117_sequencial "; 
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