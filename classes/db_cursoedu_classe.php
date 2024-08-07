<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: escola
//CLASSE DA ENTIDADE cursoedu
class cl_curso { 
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
   var $ed29_i_codigo = 0; 
   var $ed29_i_ensino = 0; 
   var $ed29_c_descr = null; 
   var $ed29_c_historico = null; 
   var $ed29_i_avalparcial = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed29_i_codigo = int8 = C�digo 
                 ed29_i_ensino = int8 = N�vel de Ensino 
                 ed29_c_descr = char(40) = Nome do Curso 
                 ed29_c_historico = char(1) = Incluir no Hist�rico 
                 ed29_i_avalparcial = int4 = Habilita Aprova��o Parcial 
                 ";
   //funcao construtor da classe 
   function cl_curso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cursoedu"); 
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
       $this->ed29_i_codigo = ($this->ed29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed29_i_codigo"]:$this->ed29_i_codigo);
       $this->ed29_i_ensino = ($this->ed29_i_ensino == ""?@$GLOBALS["HTTP_POST_VARS"]["ed29_i_ensino"]:$this->ed29_i_ensino);
       $this->ed29_c_descr = ($this->ed29_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed29_c_descr"]:$this->ed29_c_descr);
       $this->ed29_c_historico = ($this->ed29_c_historico == ""?@$GLOBALS["HTTP_POST_VARS"]["ed29_c_historico"]:$this->ed29_c_historico);
       $this->ed29_i_avalparcial = ($this->ed29_i_avalparcial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed29_i_avalparcial"]:$this->ed29_i_avalparcial);
     }else{
       $this->ed29_i_codigo = ($this->ed29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed29_i_codigo"]:$this->ed29_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed29_i_codigo){ 
      $this->atualizacampos();
     if($this->ed29_i_ensino == null ){ 
       $this->erro_sql = " Campo N�vel de Ensino nao Informado.";
       $this->erro_campo = "ed29_i_ensino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed29_c_descr == null ){ 
       $this->erro_sql = " Campo Nome do Curso nao Informado.";
       $this->erro_campo = "ed29_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed29_c_historico == null ){ 
       $this->erro_sql = " Campo Incluir no Hist�rico nao Informado.";
       $this->erro_campo = "ed29_c_historico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed29_i_avalparcial == null ){ 
       $this->erro_sql = " Campo Habilita Aprova��o Parcial nao Informado.";
       $this->erro_campo = "ed29_i_avalparcial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed29_i_codigo == "" || $ed29_i_codigo == null ){
       $result = db_query("select nextval('cursoedu_ed29_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cursoedu_ed29_i_codigo_seq do campo: ed29_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed29_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cursoedu_ed29_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed29_i_codigo)){
         $this->erro_sql = " Campo ed29_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed29_i_codigo = $ed29_i_codigo; 
       }
     }
     if(($this->ed29_i_codigo == null) || ($this->ed29_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed29_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cursoedu(
                                       ed29_i_codigo 
                                      ,ed29_i_ensino 
                                      ,ed29_c_descr 
                                      ,ed29_c_historico 
                                      ,ed29_i_avalparcial 
                       )
                values (
                                $this->ed29_i_codigo 
                               ,$this->ed29_i_ensino 
                               ,'$this->ed29_c_descr' 
                               ,'$this->ed29_c_historico' 
                               ,$this->ed29_i_avalparcial 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Cursos ($this->ed29_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Cursos j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Cursos ($this->ed29_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed29_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed29_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008276,'$this->ed29_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010048,1008276,'','".AddSlashes(pg_result($resaco,0,'ed29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010048,1008277,'','".AddSlashes(pg_result($resaco,0,'ed29_i_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010048,1008278,'','".AddSlashes(pg_result($resaco,0,'ed29_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010048,1008279,'','".AddSlashes(pg_result($resaco,0,'ed29_c_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010048,18436,'','".AddSlashes(pg_result($resaco,0,'ed29_i_avalparcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed29_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update cursoedu set ";
     $virgula = "";
     if(trim($this->ed29_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed29_i_codigo"])){ 
       $sql  .= $virgula." ed29_i_codigo = $this->ed29_i_codigo ";
       $virgula = ",";
       if(trim($this->ed29_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ed29_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed29_i_ensino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed29_i_ensino"])){ 
       $sql  .= $virgula." ed29_i_ensino = $this->ed29_i_ensino ";
       $virgula = ",";
       if(trim($this->ed29_i_ensino) == null ){ 
         $this->erro_sql = " Campo N�vel de Ensino nao Informado.";
         $this->erro_campo = "ed29_i_ensino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed29_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed29_c_descr"])){ 
       $sql  .= $virgula." ed29_c_descr = '$this->ed29_c_descr' ";
       $virgula = ",";
       if(trim($this->ed29_c_descr) == null ){ 
         $this->erro_sql = " Campo Nome do Curso nao Informado.";
         $this->erro_campo = "ed29_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed29_c_historico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed29_c_historico"])){ 
       $sql  .= $virgula." ed29_c_historico = '$this->ed29_c_historico' ";
       $virgula = ",";
       if(trim($this->ed29_c_historico) == null ){ 
         $this->erro_sql = " Campo Incluir no Hist�rico nao Informado.";
         $this->erro_campo = "ed29_c_historico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed29_i_avalparcial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed29_i_avalparcial"])){ 
       $sql  .= $virgula." ed29_i_avalparcial = $this->ed29_i_avalparcial ";
       $virgula = ",";
       if(trim($this->ed29_i_avalparcial) == null ){ 
         $this->erro_sql = " Campo Habilita Aprova��o Parcial nao Informado.";
         $this->erro_campo = "ed29_i_avalparcial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed29_i_codigo!=null){
       $sql .= " ed29_i_codigo = $this->ed29_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed29_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008276,'$this->ed29_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed29_i_codigo"]) || $this->ed29_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1010048,1008276,'".AddSlashes(pg_result($resaco,$conresaco,'ed29_i_codigo'))."','$this->ed29_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed29_i_ensino"]) || $this->ed29_i_ensino != "")
           $resac = db_query("insert into db_acount values($acount,1010048,1008277,'".AddSlashes(pg_result($resaco,$conresaco,'ed29_i_ensino'))."','$this->ed29_i_ensino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed29_c_descr"]) || $this->ed29_c_descr != "")
           $resac = db_query("insert into db_acount values($acount,1010048,1008278,'".AddSlashes(pg_result($resaco,$conresaco,'ed29_c_descr'))."','$this->ed29_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed29_c_historico"]) || $this->ed29_c_historico != "")
           $resac = db_query("insert into db_acount values($acount,1010048,1008279,'".AddSlashes(pg_result($resaco,$conresaco,'ed29_c_historico'))."','$this->ed29_c_historico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed29_i_avalparcial"]) || $this->ed29_i_avalparcial != "")
           $resac = db_query("insert into db_acount values($acount,1010048,18436,'".AddSlashes(pg_result($resaco,$conresaco,'ed29_i_avalparcial'))."','$this->ed29_i_avalparcial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Cursos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed29_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Cursos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed29_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed29_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed29_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed29_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008276,'$ed29_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010048,1008276,'','".AddSlashes(pg_result($resaco,$iresaco,'ed29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010048,1008277,'','".AddSlashes(pg_result($resaco,$iresaco,'ed29_i_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010048,1008278,'','".AddSlashes(pg_result($resaco,$iresaco,'ed29_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010048,1008279,'','".AddSlashes(pg_result($resaco,$iresaco,'ed29_c_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010048,18436,'','".AddSlashes(pg_result($resaco,$iresaco,'ed29_i_avalparcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cursoedu
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed29_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed29_i_codigo = $ed29_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Cursos nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed29_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Cursos nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed29_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed29_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cursoedu";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cursoedu ";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql2 = "";
     if($dbwhere==""){
       if($ed29_i_codigo!=null ){
         $sql2 .= " where cursoedu.ed29_i_codigo = $ed29_i_codigo "; 
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
   function sql_query_file ( $ed29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cursoedu ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed29_i_codigo!=null ){
         $sql2 .= " where cursoedu.ed29_i_codigo = $ed29_i_codigo "; 
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
   function sql_query_cursoescola ( $ed29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     
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
     $sql .= " from cursoedu ";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql .= "      inner join cursoescola  on  cursoescola.ed71_i_curso = cursoedu.ed29_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed29_i_codigo!=null ){
         $sql2 .= " where cursoedu.ed29_i_codigo = $ed29_i_codigo ";
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
  
  public function sql_query_calendarios($ed29_i_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
    
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = explode("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    
    $sql .= " from cursoedu ";
    $sql .= "       inner join cursoescola         on ed71_i_curso = ed29_i_codigo ";
    $sql .= "       inner join base                on ed31_i_curso = ed29_i_codigo ";
    $sql .= "       inner join turma               on ed57_i_base = ed31_i_codigo ";
    $sql .= '       left  join turmaserieregimemat on ed220_i_turma = turma.ed57_i_codigo ';
    $sql .= '       left  join serieregimemat      on ed223_i_codigo = ed220_i_serieregimemat ';
    $sql .= '       left  join serie               on ed11_i_codigo = ed223_i_serie ';
    $sql .= "       inner join matricula           on ed60_i_turma = ed57_i_codigo ";
    $sql .= "       inner join ensino              on ed10_i_codigo = ed29_i_ensino ";
    $sql2 = "";
    if($dbwhere==""){
      if($ed29_i_codigo!=null ){
        $sql2 .= " where cursoedu.ed29_i_codigo = $ed29_i_codigo ";
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
  
  /**
   * Busca as series/etapas de um curso
   * @param string $ed29_i_codigo
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_query_curso_serie ($ed29_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
  
    $sql = "select ";
    if ($campos != "*") {
  
      $campos_sql = explode("#", $campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
  
        $sql     .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from cursoedu ";
    $sql .= "      inner join ensino      on ensino.ed10_i_codigo     = cursoedu.ed29_i_ensino ";
    $sql .= "      inner join cursoescola on cursoescola.ed71_i_curso = cursoedu.ed29_i_codigo ";
    $sql .= "      inner join serie       on ed11_i_ensino            = ed10_i_codigo          ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($ed29_i_codigo != null) {
        $sql2 .= " where cursoedu.ed29_i_codigo = $ed29_i_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  } 
}
?>