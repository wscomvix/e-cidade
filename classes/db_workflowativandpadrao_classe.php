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

//MODULO: configuracoes
//CLASSE DA ENTIDADE workflowativandpadrao
class cl_workflowativandpadrao { 
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
   var $db115_sequencial = 0; 
   var $db115_workflowativ = 0; 
   var $db115_codigo = 0; 
   var $db115_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db115_sequencial = int4 = C�digo Sequencial 
                 db115_workflowativ = int4 = C�digo Work Flow Atividade 
                 db115_codigo = int4 = C�digo Andamento Padr�o 
                 db115_ordem = int4 = Ordem Andamento Padr�o 
                 ";
   //funcao construtor da classe 
   function cl_workflowativandpadrao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("workflowativandpadrao"); 
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
       $this->db115_sequencial = ($this->db115_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db115_sequencial"]:$this->db115_sequencial);
       $this->db115_workflowativ = ($this->db115_workflowativ == ""?@$GLOBALS["HTTP_POST_VARS"]["db115_workflowativ"]:$this->db115_workflowativ);
       $this->db115_codigo = ($this->db115_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db115_codigo"]:$this->db115_codigo);
       $this->db115_ordem = ($this->db115_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["db115_ordem"]:$this->db115_ordem);
     }else{
       $this->db115_sequencial = ($this->db115_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db115_sequencial"]:$this->db115_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db115_sequencial){ 
      $this->atualizacampos();
     if($this->db115_workflowativ == null ){ 
       $this->erro_sql = " Campo C�digo Work Flow Atividade nao Informado.";
       $this->erro_campo = "db115_workflowativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db115_codigo == null ){ 
       $this->erro_sql = " Campo C�digo Andamento Padr�o nao Informado.";
       $this->erro_campo = "db115_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db115_ordem == null ){ 
       $this->erro_sql = " Campo Ordem Andamento Padr�o nao Informado.";
       $this->erro_campo = "db115_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db115_sequencial == "" || $db115_sequencial == null ){
       $result = db_query("select nextval('workflowativandpadrao_db115_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: workflowativandpadrao_db115_sequencial_seq do campo: db115_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db115_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from workflowativandpadrao_db115_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db115_sequencial)){
         $this->erro_sql = " Campo db115_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db115_sequencial = $db115_sequencial; 
       }
     }
     if(($this->db115_sequencial == null) || ($this->db115_sequencial == "") ){ 
       $this->erro_sql = " Campo db115_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into workflowativandpadrao(
                                       db115_sequencial 
                                      ,db115_workflowativ 
                                      ,db115_codigo 
                                      ,db115_ordem 
                       )
                values (
                                $this->db115_sequencial 
                               ,$this->db115_workflowativ 
                               ,$this->db115_codigo 
                               ,$this->db115_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "workflowativandpadrao ($this->db115_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "workflowativandpadrao j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "workflowativandpadrao ($this->db115_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db115_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db115_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17891,'$this->db115_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3159,17891,'','".AddSlashes(pg_result($resaco,0,'db115_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3159,17892,'','".AddSlashes(pg_result($resaco,0,'db115_workflowativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3159,17894,'','".AddSlashes(pg_result($resaco,0,'db115_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3159,17893,'','".AddSlashes(pg_result($resaco,0,'db115_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db115_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update workflowativandpadrao set ";
     $virgula = "";
     if(trim($this->db115_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db115_sequencial"])){ 
       $sql  .= $virgula." db115_sequencial = $this->db115_sequencial ";
       $virgula = ",";
       if(trim($this->db115_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "db115_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db115_workflowativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db115_workflowativ"])){ 
       $sql  .= $virgula." db115_workflowativ = $this->db115_workflowativ ";
       $virgula = ",";
       if(trim($this->db115_workflowativ) == null ){ 
         $this->erro_sql = " Campo C�digo Work Flow Atividade nao Informado.";
         $this->erro_campo = "db115_workflowativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db115_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db115_codigo"])){ 
       $sql  .= $virgula." db115_codigo = $this->db115_codigo ";
       $virgula = ",";
       if(trim($this->db115_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo Andamento Padr�o nao Informado.";
         $this->erro_campo = "db115_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db115_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db115_ordem"])){ 
       $sql  .= $virgula." db115_ordem = $this->db115_ordem ";
       $virgula = ",";
       if(trim($this->db115_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem Andamento Padr�o nao Informado.";
         $this->erro_campo = "db115_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db115_sequencial!=null){
       $sql .= " db115_sequencial = $this->db115_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db115_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17891,'$this->db115_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db115_sequencial"]) || $this->db115_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3159,17891,'".AddSlashes(pg_result($resaco,$conresaco,'db115_sequencial'))."','$this->db115_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db115_workflowativ"]) || $this->db115_workflowativ != "")
           $resac = db_query("insert into db_acount values($acount,3159,17892,'".AddSlashes(pg_result($resaco,$conresaco,'db115_workflowativ'))."','$this->db115_workflowativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db115_codigo"]) || $this->db115_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3159,17894,'".AddSlashes(pg_result($resaco,$conresaco,'db115_codigo'))."','$this->db115_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db115_ordem"]) || $this->db115_ordem != "")
           $resac = db_query("insert into db_acount values($acount,3159,17893,'".AddSlashes(pg_result($resaco,$conresaco,'db115_ordem'))."','$this->db115_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "workflowativandpadrao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db115_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "workflowativandpadrao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db115_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db115_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db115_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db115_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17891,'$db115_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3159,17891,'','".AddSlashes(pg_result($resaco,$iresaco,'db115_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3159,17892,'','".AddSlashes(pg_result($resaco,$iresaco,'db115_workflowativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3159,17894,'','".AddSlashes(pg_result($resaco,$iresaco,'db115_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3159,17893,'','".AddSlashes(pg_result($resaco,$iresaco,'db115_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from workflowativandpadrao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db115_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db115_sequencial = $db115_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "workflowativandpadrao nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db115_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "workflowativandpadrao nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db115_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db115_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:workflowativandpadrao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db115_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from workflowativandpadrao ";
     $sql .= "      inner join andpadrao  on  andpadrao.p53_codigo = workflowativandpadrao.db115_codigo and  andpadrao.p53_ordem = workflowativandpadrao.db115_ordem";
     $sql .= "      inner join workflowativ  on  workflowativ.db114_sequencial = workflowativandpadrao.db115_workflowativ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = andpadrao.p53_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = andpadrao.p53_codigo";
     $sql .= "      inner join workflow  as a on   a.db112_sequencial = workflowativ.db114_workflow";
     $sql2 = "";
     if($dbwhere==""){
       if($db115_sequencial!=null ){
         $sql2 .= " where workflowativandpadrao.db115_sequencial = $db115_sequencial "; 
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
   function sql_query_file ( $db115_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from workflowativandpadrao ";
     $sql2 = "";
     if($dbwhere==""){
       if($db115_sequencial!=null ){
         $sql2 .= " where workflowativandpadrao.db115_sequencial = $db115_sequencial "; 
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