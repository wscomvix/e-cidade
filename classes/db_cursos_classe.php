<?
//MODULO: escola_ecidade
//CLASSE DA ENTIDADE cursos
class cl_cursos { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $cur01_codigo = 0; 
   var $cur01_nome = null; 
   var $cur01_data_inicio_dia = null; 
   var $cur01_data_inicio_mes = null; 
   var $cur01_data_inicio_ano = null; 
   var $cur01_data_inicio = null; 
   var $cur01_carga_horaria = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cur01_codigo = int8 = Código do curso 
                 cur01_nome = varchar(50) = Nome do curso 
                 cur01_data_inicio = date = Data de inicio 
                 cur01_carga_horaria = int4 = Carga horária 
                 ";
   //funcao construtor da classe 
   function cl_cursos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cursos"); 
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
       $this->cur01_codigo = ($this->cur01_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cur01_codigo"]:$this->cur01_codigo);
       $this->cur01_nome = ($this->cur01_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["cur01_nome"]:$this->cur01_nome);
       if($this->cur01_data_inicio == ""){
         $this->cur01_data_inicio_dia = @$GLOBALS["HTTP_POST_VARS"]["cur01_data_inicio_dia"];
         $this->cur01_data_inicio_mes = @$GLOBALS["HTTP_POST_VARS"]["cur01_data_inicio_mes"];
         $this->cur01_data_inicio_ano = @$GLOBALS["HTTP_POST_VARS"]["cur01_data_inicio_ano"];
         if($this->cur01_data_inicio_dia != ""){
            $this->cur01_data_inicio = $this->cur01_data_inicio_ano."-".$this->cur01_data_inicio_mes."-".$this->cur01_data_inicio_dia;
         }
       }
       $this->cur01_carga_horaria = ($this->cur01_carga_horaria == ""?@$GLOBALS["HTTP_POST_VARS"]["cur01_carga_horaria"]:$this->cur01_carga_horaria);
     }else{
       $this->cur01_codigo = ($this->cur01_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cur01_codigo"]:$this->cur01_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cur01_codigo){ 
      $this->atualizacampos();
     if($this->cur01_nome == null ){ 
       $this->erro_sql = " Campo Nome do curso nao Informado.";
       $this->erro_campo = "cur01_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cur01_data_inicio == null ){ 
       $this->erro_sql = " Campo Data de inicio nao Informado.";
       $this->erro_campo = "cur01_data_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cur01_carga_horaria == null ){ 
       $this->erro_sql = " Campo Carga horária nao Informado.";
       $this->erro_campo = "cur01_carga_horaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cur01_codigo == "" || $cur01_codigo == null ){
       $result = @pg_query("select nextval('cursos_cur01_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cursos_cur01_codigo_seq do campo: cur01_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cur01_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from cursos_cur01_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cur01_codigo)){
         $this->erro_sql = " Campo cur01_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cur01_codigo = $cur01_codigo; 
       }
     }
     if(($this->cur01_codigo == null) || ($this->cur01_codigo == "") ){ 
       $this->erro_sql = " Campo cur01_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into cursos(
                                       cur01_codigo 
                                      ,cur01_nome 
                                      ,cur01_data_inicio 
                                      ,cur01_carga_horaria 
                       )
                values (
                                $this->cur01_codigo 
                               ,'$this->cur01_nome' 
                               ,".($this->cur01_data_inicio == "null" || $this->cur01_data_inicio == ""?"null":"'".$this->cur01_data_inicio."'")." 
                               ,$this->cur01_carga_horaria 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cursos ($this->cur01_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cursos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cursos ($this->cur01_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cur01_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->cur01_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2014140,'$this->cur01_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1010193,2014140,'','".pg_result($resaco,0,'cur01_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1010193,2014141,'','".pg_result($resaco,0,'cur01_nome')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1010193,2014142,'','".pg_result($resaco,0,'cur01_data_inicio')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1010193,2014143,'','".pg_result($resaco,0,'cur01_carga_horaria')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cur01_codigo=null) { 
      $this->atualizacampos();
     $sql = " update cursos set ";
     $virgula = "";
     if(trim($this->cur01_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cur01_codigo"])){ 
        if(trim($this->cur01_codigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["cur01_codigo"])){ 
           $this->cur01_codigo = "0" ; 
        } 
       $sql  .= $virgula." cur01_codigo = $this->cur01_codigo ";
       $virgula = ",";
       if(trim($this->cur01_codigo) == null ){ 
         $this->erro_sql = " Campo Código do curso nao Informado.";
         $this->erro_campo = "cur01_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cur01_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cur01_nome"])){ 
       $sql  .= $virgula." cur01_nome = '$this->cur01_nome' ";
       $virgula = ",";
       if(trim($this->cur01_nome) == null ){ 
         $this->erro_sql = " Campo Nome do curso nao Informado.";
         $this->erro_campo = "cur01_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cur01_data_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cur01_data_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cur01_data_inicio_dia"] !="") ){ 
       $sql  .= $virgula." cur01_data_inicio = '$this->cur01_data_inicio' ";
       $virgula = ",";
       if(trim($this->cur01_data_inicio) == null ){ 
         $this->erro_sql = " Campo Data de inicio nao Informado.";
         $this->erro_campo = "cur01_data_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cur01_data_inicio_dia"])){ 
         $sql  .= $virgula." cur01_data_inicio = null ";
         $virgula = ",";
         if(trim($this->cur01_data_inicio) == null ){ 
           $this->erro_sql = " Campo Data de inicio nao Informado.";
           $this->erro_campo = "cur01_data_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cur01_carga_horaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cur01_carga_horaria"])){ 
        if(trim($this->cur01_carga_horaria)=="" && isset($GLOBALS["HTTP_POST_VARS"]["cur01_carga_horaria"])){ 
           $this->cur01_carga_horaria = "0" ; 
        } 
       $sql  .= $virgula." cur01_carga_horaria = $this->cur01_carga_horaria ";
       $virgula = ",";
       if(trim($this->cur01_carga_horaria) == null ){ 
         $this->erro_sql = " Campo Carga horária nao Informado.";
         $this->erro_campo = "cur01_carga_horaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  cur01_codigo = $this->cur01_codigo
";
     $resaco = $this->sql_record($this->sql_query_file($this->cur01_codigo));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2014140,'$this->cur01_codigo','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["cur01_codigo"]))
         $resac = pg_query("insert into db_acount values($acount,1010193,2014140,'".pg_result($resaco,0,'cur01_codigo')."','$this->cur01_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["cur01_nome"]))
         $resac = pg_query("insert into db_acount values($acount,1010193,2014141,'".pg_result($resaco,0,'cur01_nome')."','$this->cur01_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["cur01_data_inicio"]))
         $resac = pg_query("insert into db_acount values($acount,1010193,2014142,'".pg_result($resaco,0,'cur01_data_inicio')."','$this->cur01_data_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["cur01_carga_horaria"]))
         $resac = pg_query("insert into db_acount values($acount,1010193,2014143,'".pg_result($resaco,0,'cur01_carga_horaria')."','$this->cur01_carga_horaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cursos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cur01_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cursos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cur01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cur01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cur01_codigo=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->cur01_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2014140,'$this->cur01_codigo','E')");
       $resac = pg_query("insert into db_acount values($acount,1010193,2014140,'','".pg_result($resaco,0,'cur01_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1010193,2014141,'','".pg_result($resaco,0,'cur01_nome')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1010193,2014142,'','".pg_result($resaco,0,'cur01_data_inicio')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1010193,2014143,'','".pg_result($resaco,0,'cur01_carga_horaria')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from cursos
                    where ";
     $sql2 = "";
      if($this->cur01_codigo != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " cur01_codigo = $this->cur01_codigo ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cursos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->cur01_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cursos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->cur01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cur01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cur01_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cursos ";
     $sql2 = "";
     if($dbwhere==""){
       if($cur01_codigo!=null ){
         $sql2 .= " where cursos.cur01_codigo = $cur01_codigo "; 
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
   function sql_query_file ( $cur01_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cursos ";
     $sql2 = "";
     if($dbwhere==""){
       if($cur01_codigo!=null ){
         $sql2 .= " where cursos.cur01_codigo = $cur01_codigo "; 
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
