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

//MODULO: fiscal
//CLASSE DA ENTIDADE fiscarquivos
class cl_fiscarquivos { 
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
   var $y26_codnoti = 0; 
   var $y26_idparag = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y26_codnoti = int8 = C�digo da Notifica��o 
                 y26_idparag = int4 = C�dido do par�grafo 
                 ";
   //funcao construtor da classe 
   function cl_fiscarquivos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("fiscarquivos"); 
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
       $this->y26_codnoti = ($this->y26_codnoti == ""?@$GLOBALS["HTTP_POST_VARS"]["y26_codnoti"]:$this->y26_codnoti);
       $this->y26_idparag = ($this->y26_idparag == ""?@$GLOBALS["HTTP_POST_VARS"]["y26_idparag"]:$this->y26_idparag);
     }else{
       $this->y26_codnoti = ($this->y26_codnoti == ""?@$GLOBALS["HTTP_POST_VARS"]["y26_codnoti"]:$this->y26_codnoti);
       $this->y26_idparag = ($this->y26_idparag == ""?@$GLOBALS["HTTP_POST_VARS"]["y26_idparag"]:$this->y26_idparag);
     }
   }
   // funcao para inclusao
   function incluir ($y26_codnoti,$y26_idparag){ 
      $this->atualizacampos();
       $this->y26_codnoti = $y26_codnoti; 
       $this->y26_idparag = $y26_idparag; 
     if(($this->y26_codnoti == null) || ($this->y26_codnoti == "") ){ 
       $this->erro_sql = " Campo y26_codnoti nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->y26_idparag == null) || ($this->y26_idparag == "") ){ 
       $this->erro_sql = " Campo y26_idparag nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into fiscarquivos(
                                       y26_codnoti 
                                      ,y26_idparag 
                       )
                values (
                                $this->y26_codnoti 
                               ,$this->y26_idparag 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cadastro dos artigos da notifica��o ($this->y26_codnoti."-".$this->y26_idparag) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cadastro dos artigos da notifica��o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cadastro dos artigos da notifica��o ($this->y26_codnoti."-".$this->y26_idparag) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y26_codnoti."-".$this->y26_idparag;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y26_codnoti,$this->y26_idparag));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5187,'$this->y26_codnoti','I')");
       $resac = db_query("insert into db_acountkey values($acount,5188,'$this->y26_idparag','I')");
       $resac = db_query("insert into db_acount values($acount,746,5187,'','".AddSlashes(pg_result($resaco,0,'y26_codnoti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,746,5188,'','".AddSlashes(pg_result($resaco,0,'y26_idparag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y26_codnoti=null,$y26_idparag=null) { 
      $this->atualizacampos();
     $sql = " update fiscarquivos set ";
     $virgula = "";
     if(trim($this->y26_codnoti)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y26_codnoti"])){ 
       $sql  .= $virgula." y26_codnoti = $this->y26_codnoti ";
       $virgula = ",";
       if(trim($this->y26_codnoti) == null ){ 
         $this->erro_sql = " Campo C�digo da Notifica��o nao Informado.";
         $this->erro_campo = "y26_codnoti";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y26_idparag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y26_idparag"])){ 
       $sql  .= $virgula." y26_idparag = $this->y26_idparag ";
       $virgula = ",";
       if(trim($this->y26_idparag) == null ){ 
         $this->erro_sql = " Campo C�dido do par�grafo nao Informado.";
         $this->erro_campo = "y26_idparag";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y26_codnoti!=null){
       $sql .= " y26_codnoti = $this->y26_codnoti";
     }
     if($y26_idparag!=null){
       $sql .= " and  y26_idparag = $this->y26_idparag";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y26_codnoti,$this->y26_idparag));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5187,'$this->y26_codnoti','A')");
         $resac = db_query("insert into db_acountkey values($acount,5188,'$this->y26_idparag','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y26_codnoti"]))
           $resac = db_query("insert into db_acount values($acount,746,5187,'".AddSlashes(pg_result($resaco,$conresaco,'y26_codnoti'))."','$this->y26_codnoti',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y26_idparag"]))
           $resac = db_query("insert into db_acount values($acount,746,5188,'".AddSlashes(pg_result($resaco,$conresaco,'y26_idparag'))."','$this->y26_idparag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro dos artigos da notifica��o nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y26_codnoti."-".$this->y26_idparag;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro dos artigos da notifica��o nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y26_codnoti."-".$this->y26_idparag;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y26_codnoti."-".$this->y26_idparag;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y26_codnoti=null,$y26_idparag=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y26_codnoti,$y26_idparag));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5187,'$y26_codnoti','E')");
         $resac = db_query("insert into db_acountkey values($acount,5188,'$y26_idparag','E')");
         $resac = db_query("insert into db_acount values($acount,746,5187,'','".AddSlashes(pg_result($resaco,$iresaco,'y26_codnoti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,746,5188,'','".AddSlashes(pg_result($resaco,$iresaco,'y26_idparag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from fiscarquivos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y26_codnoti != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y26_codnoti = $y26_codnoti ";
        }
        if($y26_idparag != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y26_idparag = $y26_idparag ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro dos artigos da notifica��o nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y26_codnoti."-".$y26_idparag;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro dos artigos da notifica��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y26_codnoti."-".$y26_idparag;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y26_codnoti."-".$y26_idparag;
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
        $this->erro_sql   = "Record Vazio na Tabela:fiscarquivos";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y26_codnoti=null,$y26_idparag=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscarquivos ";
     $sql .= "      inner join db_paragrafo  on  db_paragrafo.db02_idparag = fiscarquivos.y26_idparag";
     $sql .= "      inner join fiscal  on  fiscal.y30_codnoti = fiscarquivos.y26_codnoti";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscal.y30_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($y26_codnoti!=null ){
         $sql2 .= " where fiscarquivos.y26_codnoti = $y26_codnoti "; 
       } 
       if($y26_idparag!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " fiscarquivos.y26_idparag = $y26_idparag "; 
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
   function sql_query_file ( $y26_codnoti=null,$y26_idparag=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscarquivos ";
     $sql2 = "";
     if($dbwhere==""){
       if($y26_codnoti!=null ){
         $sql2 .= " where fiscarquivos.y26_codnoti = $y26_codnoti "; 
       } 
       if($y26_idparag!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " fiscarquivos.y26_idparag = $y26_idparag "; 
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