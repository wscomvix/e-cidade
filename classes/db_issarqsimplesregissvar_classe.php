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

//MODULO: issqn
//CLASSE DA ENTIDADE issarqsimplesregissvar
class cl_issarqsimplesregissvar { 
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
   var $q68_sequencial = 0; 
   var $q68_issvar = 0; 
   var $q68_issarqsimplesreg = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q68_sequencial = int4 = C�digo Sequencial 
                 q68_issvar = int4 = C�digo do Issqn 
                 q68_issarqsimplesreg = int4 = C�digo do Registro 
                 ";
   //funcao construtor da classe 
   function cl_issarqsimplesregissvar() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issarqsimplesregissvar"); 
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
       $this->q68_sequencial = ($this->q68_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q68_sequencial"]:$this->q68_sequencial);
       $this->q68_issvar = ($this->q68_issvar == ""?@$GLOBALS["HTTP_POST_VARS"]["q68_issvar"]:$this->q68_issvar);
       $this->q68_issarqsimplesreg = ($this->q68_issarqsimplesreg == ""?@$GLOBALS["HTTP_POST_VARS"]["q68_issarqsimplesreg"]:$this->q68_issarqsimplesreg);
     }else{
       $this->q68_sequencial = ($this->q68_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q68_sequencial"]:$this->q68_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q68_sequencial){ 
      $this->atualizacampos();
     if($this->q68_issvar == null ){ 
       $this->erro_sql = " Campo C�digo do Issqn nao Informado.";
       $this->erro_campo = "q68_issvar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q68_issarqsimplesreg == null ){ 
       $this->erro_sql = " Campo C�digo do Registro nao Informado.";
       $this->erro_campo = "q68_issarqsimplesreg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q68_sequencial == "" || $q68_sequencial == null ){
       $result = db_query("select nextval('issarqsimplesregissvar_q68_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issarqsimplesregissvar_q68_sequencial_seq do campo: q68_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q68_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issarqsimplesregissvar_q68_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q68_sequencial)){
         $this->erro_sql = " Campo q68_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q68_sequencial = $q68_sequencial; 
       }
     }
     if(($this->q68_sequencial == null) || ($this->q68_sequencial == "") ){ 
       $this->erro_sql = " Campo q68_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issarqsimplesregissvar(
                                       q68_sequencial 
                                      ,q68_issvar 
                                      ,q68_issarqsimplesreg 
                       )
                values (
                                $this->q68_sequencial 
                               ,$this->q68_issvar 
                               ,$this->q68_issarqsimplesreg 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "issarqsimplesregissvar ($this->q68_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "issarqsimplesregissvar j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "issarqsimplesregissvar ($this->q68_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q68_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q68_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10734,'$this->q68_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1848,10734,'','".AddSlashes(pg_result($resaco,0,'q68_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1848,10735,'','".AddSlashes(pg_result($resaco,0,'q68_issvar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1848,10736,'','".AddSlashes(pg_result($resaco,0,'q68_issarqsimplesreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q68_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issarqsimplesregissvar set ";
     $virgula = "";
     if(trim($this->q68_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q68_sequencial"])){ 
       $sql  .= $virgula." q68_sequencial = $this->q68_sequencial ";
       $virgula = ",";
       if(trim($this->q68_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "q68_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q68_issvar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q68_issvar"])){ 
       $sql  .= $virgula." q68_issvar = $this->q68_issvar ";
       $virgula = ",";
       if(trim($this->q68_issvar) == null ){ 
         $this->erro_sql = " Campo C�digo do Issqn nao Informado.";
         $this->erro_campo = "q68_issvar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q68_issarqsimplesreg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q68_issarqsimplesreg"])){ 
       $sql  .= $virgula." q68_issarqsimplesreg = $this->q68_issarqsimplesreg ";
       $virgula = ",";
       if(trim($this->q68_issarqsimplesreg) == null ){ 
         $this->erro_sql = " Campo C�digo do Registro nao Informado.";
         $this->erro_campo = "q68_issarqsimplesreg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q68_sequencial!=null){
       $sql .= " q68_sequencial = $this->q68_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q68_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10734,'$this->q68_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q68_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1848,10734,'".AddSlashes(pg_result($resaco,$conresaco,'q68_sequencial'))."','$this->q68_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q68_issvar"]))
           $resac = db_query("insert into db_acount values($acount,1848,10735,'".AddSlashes(pg_result($resaco,$conresaco,'q68_issvar'))."','$this->q68_issvar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q68_issarqsimplesreg"]))
           $resac = db_query("insert into db_acount values($acount,1848,10736,'".AddSlashes(pg_result($resaco,$conresaco,'q68_issarqsimplesreg'))."','$this->q68_issarqsimplesreg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "issarqsimplesregissvar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q68_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "issarqsimplesregissvar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q68_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q68_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q68_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q68_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10734,'$q68_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1848,10734,'','".AddSlashes(pg_result($resaco,$iresaco,'q68_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1848,10735,'','".AddSlashes(pg_result($resaco,$iresaco,'q68_issvar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1848,10736,'','".AddSlashes(pg_result($resaco,$iresaco,'q68_issarqsimplesreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issarqsimplesregissvar
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q68_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q68_sequencial = $q68_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "issarqsimplesregissvar nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q68_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "issarqsimplesregissvar nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q68_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q68_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issarqsimplesregissvar";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q68_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issarqsimplesregissvar ";
     $sql .= "      inner join issvar  on  issvar.q05_codigo = issarqsimplesregissvar.q68_issvar";
     $sql .= "      inner join issarqsimplesreg  on  issarqsimplesreg.q23_sequencial = issarqsimplesregissvar.q68_issarqsimplesreg";
     $sql .= "      inner join issarqsimples  as a on   a.q17_sequencial = issarqsimplesreg.q23_issarqsimples";
     $sql2 = "";
     if($dbwhere==""){
       if($q68_sequencial!=null ){
         $sql2 .= " where issarqsimplesregissvar.q68_sequencial = $q68_sequencial "; 
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
   function sql_query_file ( $q68_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issarqsimplesregissvar ";
     $sql2 = "";
     if($dbwhere==""){
       if($q68_sequencial!=null ){
         $sql2 .= " where issarqsimplesregissvar.q68_sequencial = $q68_sequencial "; 
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