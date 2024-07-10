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

//MODULO: protocolo
//CLASSE DA ENTIDADE bemfoto
class cl_bemfoto {
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
   var $t54_sequencial = 0;
   var $t54_numbem = 0;
   var $t54_id_usuario = 0;
   var $t54_data_dia = null;
   var $t54_data_mes = null;
   var $t54_data_ano = null;
   var $t54_data = null;
   var $t54_hora = null;
   var $t54_fotoativa = 'f';
   var $t54_principal = 'f';
   var $t54_arquivofoto = 0;
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t54_sequencial = int4 = C�digo da Foto 
                 t54_numbem = int4 = N�mero do Bem 
                 t54_id_usuario = int4 = Usu�rio 
                 t54_data = date = Data de Inclus�o 
                 t54_hora = char(5) = Hora da Incluis�o 
                 t54_fotoativa = bool = Foto Ativa 
                 t54_principal = bool = Foto Principal 
                 t54_arquivofoto = oid = Arquivo da Foto 
                 ";
   //funcao construtor da classe 
   function cl_bemfoto() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bemfoto");
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
       $this->t54_sequencial = ($this->t54_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_sequencial"]:$this->t54_sequencial);
       $this->t54_numbem = ($this->t54_numbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_numbem"]:$this->t54_numbem);
       $this->t54_id_usuario = ($this->t54_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_id_usuario"]:$this->t54_id_usuario);
       if($this->t54_data == ""){
         $this->t54_data_dia = ($this->t54_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_data_dia"]:$this->t54_data_dia);
         $this->t54_data_mes = ($this->t54_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_data_mes"]:$this->t54_data_mes);
         $this->t54_data_ano = ($this->t54_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_data_ano"]:$this->t54_data_ano);
         if($this->t54_data_dia != ""){
            $this->t54_data = $this->t54_data_ano."-".$this->t54_data_mes."-".$this->t54_data_dia;
         }
       }
       $this->t54_hora = ($this->t54_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_hora"]:$this->t54_hora);
       $this->t54_fotoativa = ($this->t54_fotoativa == "f"?@$GLOBALS["HTTP_POST_VARS"]["t54_fotoativa"]:$this->t54_fotoativa);
       $this->t54_principal = ($this->t54_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["t54_principal"]:$this->t54_principal);
       $this->t54_arquivofoto = ($this->t54_arquivofoto == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_arquivofoto"]:$this->t54_arquivofoto);
     }else{
       $this->t54_sequencial = ($this->t54_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_sequencial"]:$this->t54_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t54_sequencial){
      $this->atualizacampos();
     if($this->t54_numbem == null ){
       $this->erro_sql = " Campo N�mero do Bem nao Informado.";
       $this->erro_campo = "t54_numbem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t54_id_usuario == null ){
       $this->erro_sql = " Campo Usu�rio nao Informado.";
       $this->erro_campo = "t54_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t54_data == null ){
       $this->erro_sql = " Campo Data de Inclus�o nao Informado.";
       $this->erro_campo = "t54_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t54_hora == null ){
       $this->erro_sql = " Campo Hora da Incluis�o nao Informado.";
       $this->erro_campo = "t54_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t54_fotoativa == null ){
       $this->t54_fotoativa = "true";
     }
     if($this->t54_principal == null ){
       $this->erro_sql = " Campo Foto Principal nao Informado.";
       $this->erro_campo = "t54_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t54_arquivofoto == null ){
       $this->erro_sql = " Campo Arquivo da Foto nao Informado.";
       $this->erro_campo = "t54_arquivofoto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t54_sequencial == "" || $t54_sequencial == null ){
       $result = db_query("select nextval('bemfoto_t54_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bemfoto_t54_sequencial_seq do campo: t54_sequencial";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t54_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from bemfoto_t54_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t54_sequencial)){
         $this->erro_sql = " Campo t54_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t54_sequencial = $t54_sequencial;
       }
     }
     if(($this->t54_sequencial == null) || ($this->t54_sequencial == "") ){
       $this->erro_sql = " Campo t54_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bemfoto(
                                       t54_sequencial 
                                      ,t54_numbem 
                                      ,t54_id_usuario 
                                      ,t54_data 
                                      ,t54_hora 
                                      ,t54_fotoativa 
                                      ,t54_principal 
                                      ,t54_arquivofoto 
                       )
                values (
                                $this->t54_sequencial 
                               ,$this->t54_numbem 
                               ,$this->t54_id_usuario 
                               ,".($this->t54_data == "null" || $this->t54_data == ""?"null":"'".$this->t54_data."'")." 
                               ,'$this->t54_hora' 
                               ,'$this->t54_fotoativa' 
                               ,'$this->t54_principal' 
                               ,$this->t54_arquivofoto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fotos do Bem ($this->t54_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fotos do Bem j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fotos do Bem ($this->t54_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t54_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t54_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17428,'$this->t54_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3082,17428,'','".AddSlashes(pg_result($resaco,0,'t54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17429,'','".AddSlashes(pg_result($resaco,0,'t54_numbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17430,'','".AddSlashes(pg_result($resaco,0,'t54_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17431,'','".AddSlashes(pg_result($resaco,0,'t54_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17432,'','".AddSlashes(pg_result($resaco,0,'t54_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17433,'','".AddSlashes(pg_result($resaco,0,'t54_fotoativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17434,'','".AddSlashes(pg_result($resaco,0,'t54_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17435,'','".AddSlashes(pg_result($resaco,0,'t54_arquivofoto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t54_sequencial=null) {
      $this->atualizacampos();
     $sql = " update bemfoto set ";
     $virgula = "";
     if(trim($this->t54_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t54_sequencial"])){
       $sql  .= $virgula." t54_sequencial = $this->t54_sequencial ";
       $virgula = ",";
       if(trim($this->t54_sequencial) == null ){
         $this->erro_sql = " Campo C�digo da Foto nao Informado.";
         $this->erro_campo = "t54_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t54_numbem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t54_numbem"])){
       $sql  .= $virgula." t54_numbem = $this->t54_numbem ";
       $virgula = ",";
       if(trim($this->t54_numbem) == null ){
         $this->erro_sql = " Campo N�mero do Bem nao Informado.";
         $this->erro_campo = "t54_numbem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t54_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t54_id_usuario"])){
       $sql  .= $virgula." t54_id_usuario = $this->t54_id_usuario ";
       $virgula = ",";
       if(trim($this->t54_id_usuario) == null ){
         $this->erro_sql = " Campo Usu�rio nao Informado.";
         $this->erro_campo = "t54_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t54_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t54_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t54_data_dia"] !="") ){
       $sql  .= $virgula." t54_data = '$this->t54_data' ";
       $virgula = ",";
       if(trim($this->t54_data) == null ){
         $this->erro_sql = " Campo Data de Inclus�o nao Informado.";
         $this->erro_campo = "t54_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t54_data_dia"])){
         $sql  .= $virgula." t54_data = null ";
         $virgula = ",";
         if(trim($this->t54_data) == null ){
           $this->erro_sql = " Campo Data de Inclus�o nao Informado.";
           $this->erro_campo = "t54_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t54_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t54_hora"])){
       $sql  .= $virgula." t54_hora = '$this->t54_hora' ";
       $virgula = ",";
       if(trim($this->t54_hora) == null ){
         $this->erro_sql = " Campo Hora da Incluis�o nao Informado.";
         $this->erro_campo = "t54_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t54_fotoativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t54_fotoativa"])){
       $sql  .= $virgula." t54_fotoativa = '$this->t54_fotoativa' ";
       $virgula = ",";
     }
     if(trim($this->t54_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t54_principal"])){
       $sql  .= $virgula." t54_principal = '$this->t54_principal' ";
       $virgula = ",";
       if(trim($this->t54_principal) == null ){
         $this->erro_sql = " Campo Foto Principal nao Informado.";
         $this->erro_campo = "t54_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t54_arquivofoto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t54_arquivofoto"])){
       $sql  .= $virgula." t54_arquivofoto = $this->t54_arquivofoto ";
       $virgula = ",";
       if(trim($this->t54_arquivofoto) == null ){
         $this->erro_sql = " Campo Arquivo da Foto nao Informado.";
         $this->erro_campo = "t54_arquivofoto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t54_sequencial!=null){
       $sql .= " t54_sequencial = $this->t54_sequencial";
     }
//     $resaco = $this->sql_record($this->sql_query_file($this->t54_sequencial));
//     if($this->numrows>0){
//       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
//         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//         $acount = pg_result($resac,0,0);
//         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
//         $resac = db_query("insert into db_acountkey values($acount,17428,'$this->t54_sequencial','A')");
//         if(isset($GLOBALS["HTTP_POST_VARS"]["t54_sequencial"]) || $this->t54_sequencial != "")
//           $resac = db_query("insert into db_acount values($acount,3082,17428,'".AddSlashes(pg_result($resaco,$conresaco,'t54_sequencial'))."','$this->t54_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         if(isset($GLOBALS["HTTP_POST_VARS"]["t54_numbem"]) || $this->t54_numbem != "")
//           $resac = db_query("insert into db_acount values($acount,3082,17429,'".AddSlashes(pg_result($resaco,$conresaco,'t54_numbem'))."','$this->t54_numbem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         if(isset($GLOBALS["HTTP_POST_VARS"]["t54_id_usuario"]) || $this->t54_id_usuario != "")
//           $resac = db_query("insert into db_acount values($acount,3082,17430,'".AddSlashes(pg_result($resaco,$conresaco,'t54_id_usuario'))."','$this->t54_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         if(isset($GLOBALS["HTTP_POST_VARS"]["t54_data"]) || $this->t54_data != "")
//           $resac = db_query("insert into db_acount values($acount,3082,17431,'".AddSlashes(pg_result($resaco,$conresaco,'t54_data'))."','$this->t54_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         if(isset($GLOBALS["HTTP_POST_VARS"]["t54_hora"]) || $this->t54_hora != "")
//           $resac = db_query("insert into db_acount values($acount,3082,17432,'".AddSlashes(pg_result($resaco,$conresaco,'t54_hora'))."','$this->t54_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         if(isset($GLOBALS["HTTP_POST_VARS"]["t54_fotoativa"]) || $this->t54_fotoativa != "")
//           $resac = db_query("insert into db_acount values($acount,3082,17433,'".AddSlashes(pg_result($resaco,$conresaco,'t54_fotoativa'))."','$this->t54_fotoativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         if(isset($GLOBALS["HTTP_POST_VARS"]["t54_principal"]) || $this->t54_principal != "")
//           $resac = db_query("insert into db_acount values($acount,3082,17434,'".AddSlashes(pg_result($resaco,$conresaco,'t54_principal'))."','$this->t54_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         if(isset($GLOBALS["HTTP_POST_VARS"]["t54_arquivofoto"]) || $this->t54_arquivofoto != "")
//           $resac = db_query("insert into db_acount values($acount,3082,17435,'".AddSlashes(pg_result($resaco,$conresaco,'t54_arquivofoto'))."','$this->t54_arquivofoto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//       }
//     }

     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fotos do Bem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t54_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fotos do Bem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t54_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t54_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t54_sequencial=null,$dbwhere=null) {
//     if($dbwhere==null || $dbwhere==""){
//       $resaco = $this->sql_record($this->sql_query_file($t54_sequencial));
//     }else{
//       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
//     }
//     if(($resaco!=false)||($this->numrows!=0)){
//       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
//         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//         $acount = pg_result($resac,0,0);
//         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
//         $resac = db_query("insert into db_acountkey values($acount,17428,'$t54_sequencial','E')");
//         $resac = db_query("insert into db_acount values($acount,3082,17428,'','".AddSlashes(pg_result($resaco,$iresaco,'t54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         $resac = db_query("insert into db_acount values($acount,3082,17429,'','".AddSlashes(pg_result($resaco,$iresaco,'t54_numbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         $resac = db_query("insert into db_acount values($acount,3082,17430,'','".AddSlashes(pg_result($resaco,$iresaco,'t54_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         $resac = db_query("insert into db_acount values($acount,3082,17431,'','".AddSlashes(pg_result($resaco,$iresaco,'t54_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         $resac = db_query("insert into db_acount values($acount,3082,17432,'','".AddSlashes(pg_result($resaco,$iresaco,'t54_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         $resac = db_query("insert into db_acount values($acount,3082,17433,'','".AddSlashes(pg_result($resaco,$iresaco,'t54_fotoativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         $resac = db_query("insert into db_acount values($acount,3082,17434,'','".AddSlashes(pg_result($resaco,$iresaco,'t54_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         $resac = db_query("insert into db_acount values($acount,3082,17435,'','".AddSlashes(pg_result($resaco,$iresaco,'t54_arquivofoto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//       }
//     }
     $sql = " delete from bemfoto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t54_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t54_sequencial = $t54_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fotos do Bem nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t54_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fotos do Bem nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t54_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t54_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:bemfoto";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t54_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from bemfoto ";
     $sql .= "      inner join bens  on  bens.t52_bem = bemfoto.t54_numbem";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = bemfoto.t54_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($t54_sequencial!=null ){
         $sql2 .= " where bemfoto.t54_sequencial = $t54_sequencial ";
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
   function sql_query_file ( $t54_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from bemfoto ";
     $sql2 = "";
     if($dbwhere==""){
       if($t54_sequencial!=null ){
         $sql2 .= " where bemfoto.t54_sequencial = $t54_sequencial ";
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