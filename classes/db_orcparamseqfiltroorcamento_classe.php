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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcparamseqfiltroorcamento
class cl_orcparamseqfiltroorcamento { 
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
   var $o133_sequencial = 0; 
   var $o133_orcparamrel = 0; 
   var $o133_orcparamseq = 0; 
   var $o133_anousu = 0; 
   var $o133_filtro = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o133_sequencial = int4 = C�digo Sequencial 
                 o133_orcparamrel = int4 = C�digo do Relat�rio 
                 o133_orcparamseq = int4 = C�digo da Linha 
                 o133_anousu = int4 = Ano da Configura��o 
                 o133_filtro = text = Filtro 
                 ";
   //funcao construtor da classe 
   function cl_orcparamseqfiltroorcamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamseqfiltroorcamento"); 
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
       $this->o133_sequencial = ($this->o133_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o133_sequencial"]:$this->o133_sequencial);
       $this->o133_orcparamrel = ($this->o133_orcparamrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o133_orcparamrel"]:$this->o133_orcparamrel);
       $this->o133_orcparamseq = ($this->o133_orcparamseq == ""?@$GLOBALS["HTTP_POST_VARS"]["o133_orcparamseq"]:$this->o133_orcparamseq);
       $this->o133_anousu = ($this->o133_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o133_anousu"]:$this->o133_anousu);
       $this->o133_filtro = ($this->o133_filtro == ""?@$GLOBALS["HTTP_POST_VARS"]["o133_filtro"]:$this->o133_filtro);
     }else{
       $this->o133_sequencial = ($this->o133_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o133_sequencial"]:$this->o133_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o133_sequencial){ 
      $this->atualizacampos();
     if($this->o133_orcparamrel == null ){ 
       $this->erro_sql = " Campo C�digo do Relat�rio nao Informado.";
       $this->erro_campo = "o133_orcparamrel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o133_orcparamseq == null ){ 
       $this->erro_sql = " Campo C�digo da Linha nao Informado.";
       $this->erro_campo = "o133_orcparamseq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o133_anousu == null ){ 
       $this->erro_sql = " Campo Ano da Configura��o nao Informado.";
       $this->erro_campo = "o133_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o133_sequencial == "" || $o133_sequencial == null ){
       $result = db_query("select nextval('orcparamseqfiltroorcamento_o133_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcparamseqfiltroorcamento_o133_sequencial_seq do campo: o133_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o133_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcparamseqfiltroorcamento_o133_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o133_sequencial)){
         $this->erro_sql = " Campo o133_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o133_sequencial = $o133_sequencial; 
       }
     }
     if(($this->o133_sequencial == null) || ($this->o133_sequencial == "") ){ 
       $this->erro_sql = " Campo o133_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamseqfiltroorcamento(
                                       o133_sequencial 
                                      ,o133_orcparamrel 
                                      ,o133_orcparamseq 
                                      ,o133_anousu 
                                      ,o133_filtro 
                       )
                values (
                                $this->o133_sequencial 
                               ,$this->o133_orcparamrel 
                               ,$this->o133_orcparamseq 
                               ,$this->o133_anousu 
                               ,'$this->o133_filtro' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculacao das linhas dos relatorios com orcamento ($this->o133_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculacao das linhas dos relatorios com orcamento j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculacao das linhas dos relatorios com orcamento ($this->o133_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o133_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o133_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15446,'$this->o133_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2711,15446,'','".AddSlashes(pg_result($resaco,0,'o133_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2711,15447,'','".AddSlashes(pg_result($resaco,0,'o133_orcparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2711,15448,'','".AddSlashes(pg_result($resaco,0,'o133_orcparamseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2711,15449,'','".AddSlashes(pg_result($resaco,0,'o133_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2711,15450,'','".AddSlashes(pg_result($resaco,0,'o133_filtro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o133_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcparamseqfiltroorcamento set ";
     $virgula = "";
     if(trim($this->o133_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o133_sequencial"])){ 
       $sql  .= $virgula." o133_sequencial = $this->o133_sequencial ";
       $virgula = ",";
       if(trim($this->o133_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "o133_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o133_orcparamrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o133_orcparamrel"])){ 
       $sql  .= $virgula." o133_orcparamrel = $this->o133_orcparamrel ";
       $virgula = ",";
       if(trim($this->o133_orcparamrel) == null ){ 
         $this->erro_sql = " Campo C�digo do Relat�rio nao Informado.";
         $this->erro_campo = "o133_orcparamrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o133_orcparamseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o133_orcparamseq"])){ 
       $sql  .= $virgula." o133_orcparamseq = $this->o133_orcparamseq ";
       $virgula = ",";
       if(trim($this->o133_orcparamseq) == null ){ 
         $this->erro_sql = " Campo C�digo da Linha nao Informado.";
         $this->erro_campo = "o133_orcparamseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o133_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o133_anousu"])){ 
       $sql  .= $virgula." o133_anousu = $this->o133_anousu ";
       $virgula = ",";
       if(trim($this->o133_anousu) == null ){ 
         $this->erro_sql = " Campo Ano da Configura��o nao Informado.";
         $this->erro_campo = "o133_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o133_filtro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o133_filtro"])){ 
       $sql  .= $virgula." o133_filtro = '$this->o133_filtro' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o133_sequencial!=null){
       $sql .= " o133_sequencial = $this->o133_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o133_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15446,'$this->o133_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o133_sequencial"]) || $this->o133_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2711,15446,'".AddSlashes(pg_result($resaco,$conresaco,'o133_sequencial'))."','$this->o133_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o133_orcparamrel"]) || $this->o133_orcparamrel != "")
           $resac = db_query("insert into db_acount values($acount,2711,15447,'".AddSlashes(pg_result($resaco,$conresaco,'o133_orcparamrel'))."','$this->o133_orcparamrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o133_orcparamseq"]) || $this->o133_orcparamseq != "")
           $resac = db_query("insert into db_acount values($acount,2711,15448,'".AddSlashes(pg_result($resaco,$conresaco,'o133_orcparamseq'))."','$this->o133_orcparamseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o133_anousu"]) || $this->o133_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2711,15449,'".AddSlashes(pg_result($resaco,$conresaco,'o133_anousu'))."','$this->o133_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o133_filtro"]) || $this->o133_filtro != "")
           $resac = db_query("insert into db_acount values($acount,2711,15450,'".AddSlashes(pg_result($resaco,$conresaco,'o133_filtro'))."','$this->o133_filtro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculacao das linhas dos relatorios com orcamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o133_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vinculacao das linhas dos relatorios com orcamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o133_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o133_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o133_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o133_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15446,'$o133_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2711,15446,'','".AddSlashes(pg_result($resaco,$iresaco,'o133_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2711,15447,'','".AddSlashes(pg_result($resaco,$iresaco,'o133_orcparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2711,15448,'','".AddSlashes(pg_result($resaco,$iresaco,'o133_orcparamseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2711,15449,'','".AddSlashes(pg_result($resaco,$iresaco,'o133_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2711,15450,'','".AddSlashes(pg_result($resaco,$iresaco,'o133_filtro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparamseqfiltroorcamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o133_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o133_sequencial = $o133_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculacao das linhas dos relatorios com orcamento nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o133_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vinculacao das linhas dos relatorios com orcamento nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o133_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o133_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamseqfiltroorcamento";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o133_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamseqfiltroorcamento ";
     $sql .= "      inner join orcparamseq  on  orcparamseq.o69_codparamrel = orcparamseqfiltroorcamento.o133_orcparamrel and  orcparamseq.o69_codseq = orcparamseqfiltroorcamento.o133_orcparamseq";
     $sql .= "      inner join orcparamrel  on  orcparamrel.o42_codparrel = orcparamseq.o69_codparamrel";
     $sql2 = "";
     if($dbwhere==""){
       if($o133_sequencial!=null ){
         $sql2 .= " where orcparamseqfiltroorcamento.o133_sequencial = $o133_sequencial "; 
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
   function sql_query_file ( $o133_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamseqfiltroorcamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($o133_sequencial!=null ){
         $sql2 .= " where orcparamseqfiltroorcamento.o133_sequencial = $o133_sequencial "; 
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