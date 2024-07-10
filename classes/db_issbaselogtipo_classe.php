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

//MODULO: ISSQN
//CLASSE DA ENTIDADE issbaselogtipo
class cl_issbaselogtipo { 
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
   var $q103_sequencial = 0; 
   var $q103_descricao = null; 
   var $q103_dataini_dia = null; 
   var $q103_dataini_mes = null; 
   var $q103_dataini_ano = null; 
   var $q103_dataini = null; 
   var $q103_datafin_dia = null; 
   var $q103_datafin_mes = null; 
   var $q103_datafin_ano = null; 
   var $q103_datafin = null; 
   var $q103_ativo = 'f'; 
   var $q103_automatico = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q103_sequencial = int4 = Sequencial 
                 q103_descricao = varchar(100) = Descri��o 
                 q103_dataini = date = Data Inicial 
                 q103_datafin = date = Data Final 
                 q103_ativo = bool = Ativo 
                 q103_automatico = bool = Autom�tico 
                 ";
   //funcao construtor da classe 
   function cl_issbaselogtipo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issbaselogtipo"); 
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
       $this->q103_sequencial = ($this->q103_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q103_sequencial"]:$this->q103_sequencial);
       $this->q103_descricao = ($this->q103_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["q103_descricao"]:$this->q103_descricao);
       if($this->q103_dataini == ""){
         $this->q103_dataini_dia = ($this->q103_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q103_dataini_dia"]:$this->q103_dataini_dia);
         $this->q103_dataini_mes = ($this->q103_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q103_dataini_mes"]:$this->q103_dataini_mes);
         $this->q103_dataini_ano = ($this->q103_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q103_dataini_ano"]:$this->q103_dataini_ano);
         if($this->q103_dataini_dia != ""){
            $this->q103_dataini = $this->q103_dataini_ano."-".$this->q103_dataini_mes."-".$this->q103_dataini_dia;
         }
       }
       if($this->q103_datafin == ""){
         $this->q103_datafin_dia = ($this->q103_datafin_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q103_datafin_dia"]:$this->q103_datafin_dia);
         $this->q103_datafin_mes = ($this->q103_datafin_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q103_datafin_mes"]:$this->q103_datafin_mes);
         $this->q103_datafin_ano = ($this->q103_datafin_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q103_datafin_ano"]:$this->q103_datafin_ano);
         if($this->q103_datafin_dia != ""){
            $this->q103_datafin = $this->q103_datafin_ano."-".$this->q103_datafin_mes."-".$this->q103_datafin_dia;
         }
       }
       $this->q103_ativo = ($this->q103_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["q103_ativo"]:$this->q103_ativo);
       $this->q103_automatico = ($this->q103_automatico == "f"?@$GLOBALS["HTTP_POST_VARS"]["q103_automatico"]:$this->q103_automatico);
     }else{
       $this->q103_sequencial = ($this->q103_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q103_sequencial"]:$this->q103_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q103_sequencial){ 
      $this->atualizacampos();
     if($this->q103_descricao == null ){ 
       $this->erro_sql = " Campo Descri��o nao Informado.";
       $this->erro_campo = "q103_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q103_dataini == null ){ 
       $this->q103_dataini = "null";
     }
     if($this->q103_datafin == null ){ 
       $this->q103_datafin = "null";
     }
     if($this->q103_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "q103_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q103_automatico == null ){ 
       $this->erro_sql = " Campo Autom�tico nao Informado.";
       $this->erro_campo = "q103_automatico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q103_sequencial == "" || $q103_sequencial == null ){
       $result = db_query("select nextval('issbaselogtipo_q103_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issbaselogtipo_q103_sequencial_seq do campo: q103_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q103_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issbaselogtipo_q103_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q103_sequencial)){
         $this->erro_sql = " Campo q103_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q103_sequencial = $q103_sequencial; 
       }
     }
     if(($this->q103_sequencial == null) || ($this->q103_sequencial == "") ){ 
       $this->erro_sql = " Campo q103_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issbaselogtipo(
                                       q103_sequencial 
                                      ,q103_descricao 
                                      ,q103_dataini 
                                      ,q103_datafin 
                                      ,q103_ativo 
                                      ,q103_automatico 
                       )
                values (
                                $this->q103_sequencial 
                               ,'$this->q103_descricao' 
                               ,".($this->q103_dataini == "null" || $this->q103_dataini == ""?"null":"'".$this->q103_dataini."'")." 
                               ,".($this->q103_datafin == "null" || $this->q103_datafin == ""?"null":"'".$this->q103_datafin."'")." 
                               ,'$this->q103_ativo' 
                               ,'$this->q103_automatico' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "ISS Base Log Tipo ($this->q103_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "ISS Base Log Tipo j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "ISS Base Log Tipo ($this->q103_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q103_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q103_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15883,'$this->q103_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2787,15883,'','".AddSlashes(pg_result($resaco,0,'q103_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2787,15884,'','".AddSlashes(pg_result($resaco,0,'q103_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2787,15885,'','".AddSlashes(pg_result($resaco,0,'q103_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2787,15886,'','".AddSlashes(pg_result($resaco,0,'q103_datafin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2787,15887,'','".AddSlashes(pg_result($resaco,0,'q103_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2787,15888,'','".AddSlashes(pg_result($resaco,0,'q103_automatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q103_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issbaselogtipo set ";
     $virgula = "";
     if(trim($this->q103_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q103_sequencial"])){ 
       $sql  .= $virgula." q103_sequencial = $this->q103_sequencial ";
       $virgula = ",";
       if(trim($this->q103_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q103_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q103_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q103_descricao"])){ 
       $sql  .= $virgula." q103_descricao = '$this->q103_descricao' ";
       $virgula = ",";
       if(trim($this->q103_descricao) == null ){ 
         $this->erro_sql = " Campo Descri��o nao Informado.";
         $this->erro_campo = "q103_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q103_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q103_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q103_dataini_dia"] !="") ){ 
       $sql  .= $virgula." q103_dataini = '$this->q103_dataini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q103_dataini_dia"])){ 
         $sql  .= $virgula." q103_dataini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q103_datafin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q103_datafin_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q103_datafin_dia"] !="") ){ 
       $sql  .= $virgula." q103_datafin = '$this->q103_datafin' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q103_datafin_dia"])){ 
         $sql  .= $virgula." q103_datafin = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q103_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q103_ativo"])){ 
       $sql  .= $virgula." q103_ativo = '$this->q103_ativo' ";
       $virgula = ",";
       if(trim($this->q103_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "q103_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q103_automatico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q103_automatico"])){ 
       $sql  .= $virgula." q103_automatico = '$this->q103_automatico' ";
       $virgula = ",";
       if(trim($this->q103_automatico) == null ){ 
         $this->erro_sql = " Campo Autom�tico nao Informado.";
         $this->erro_campo = "q103_automatico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q103_sequencial!=null){
       $sql .= " q103_sequencial = $this->q103_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q103_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15883,'$this->q103_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q103_sequencial"]) || $this->q103_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2787,15883,'".AddSlashes(pg_result($resaco,$conresaco,'q103_sequencial'))."','$this->q103_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q103_descricao"]) || $this->q103_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2787,15884,'".AddSlashes(pg_result($resaco,$conresaco,'q103_descricao'))."','$this->q103_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q103_dataini"]) || $this->q103_dataini != "")
           $resac = db_query("insert into db_acount values($acount,2787,15885,'".AddSlashes(pg_result($resaco,$conresaco,'q103_dataini'))."','$this->q103_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q103_datafin"]) || $this->q103_datafin != "")
           $resac = db_query("insert into db_acount values($acount,2787,15886,'".AddSlashes(pg_result($resaco,$conresaco,'q103_datafin'))."','$this->q103_datafin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q103_ativo"]) || $this->q103_ativo != "")
           $resac = db_query("insert into db_acount values($acount,2787,15887,'".AddSlashes(pg_result($resaco,$conresaco,'q103_ativo'))."','$this->q103_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q103_automatico"]) || $this->q103_automatico != "")
           $resac = db_query("insert into db_acount values($acount,2787,15888,'".AddSlashes(pg_result($resaco,$conresaco,'q103_automatico'))."','$this->q103_automatico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ISS Base Log Tipo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q103_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ISS Base Log Tipo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q103_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q103_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q103_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q103_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15883,'$q103_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2787,15883,'','".AddSlashes(pg_result($resaco,$iresaco,'q103_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2787,15884,'','".AddSlashes(pg_result($resaco,$iresaco,'q103_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2787,15885,'','".AddSlashes(pg_result($resaco,$iresaco,'q103_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2787,15886,'','".AddSlashes(pg_result($resaco,$iresaco,'q103_datafin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2787,15887,'','".AddSlashes(pg_result($resaco,$iresaco,'q103_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2787,15888,'','".AddSlashes(pg_result($resaco,$iresaco,'q103_automatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issbaselogtipo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q103_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q103_sequencial = $q103_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ISS Base Log Tipo nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q103_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ISS Base Log Tipo nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q103_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q103_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issbaselogtipo";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q103_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issbaselogtipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($q103_sequencial!=null ){
         $sql2 .= " where issbaselogtipo.q103_sequencial = $q103_sequencial "; 
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
   function sql_query_file ( $q103_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issbaselogtipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($q103_sequencial!=null ){
         $sql2 .= " where issbaselogtipo.q103_sequencial = $q103_sequencial "; 
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