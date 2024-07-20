<?
//MODULO: escola_ecidade
//CLASSE DA ENTIDADE alunos_cursos
class cl_alunos_cursos { 
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
   var $alu01_codigo = 0; 
   var $cur01_codigo = 0; 
   var $ac01_data_matricula_dia = null; 
   var $ac01_data_matricula_mes = null; 
   var $ac01_data_matricula_ano = null; 
   var $ac01_data_matricula = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 alu01_codigo = int4 = Código do aluno 
                 cur01_codigo = int8 = Código do curso 
                 ac01_data_matricula = date = Data da matricula 
                 ";
   //funcao construtor da classe 
   function cl_alunos_cursos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("alunos_cursos"); 
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
       $this->alu01_codigo = ($this->alu01_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["alu01_codigo"]:$this->alu01_codigo);
       $this->cur01_codigo = ($this->cur01_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cur01_codigo"]:$this->cur01_codigo);
       if($this->ac01_data_matricula == ""){
         $this->ac01_data_matricula_dia = @$GLOBALS["HTTP_POST_VARS"]["ac01_data_matricula_dia"];
         $this->ac01_data_matricula_mes = @$GLOBALS["HTTP_POST_VARS"]["ac01_data_matricula_mes"];
         $this->ac01_data_matricula_ano = @$GLOBALS["HTTP_POST_VARS"]["ac01_data_matricula_ano"];
         if($this->ac01_data_matricula_dia != ""){
            $this->ac01_data_matricula = $this->ac01_data_matricula_ano."-".$this->ac01_data_matricula_mes."-".$this->ac01_data_matricula_dia;
         }
       }
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->alu01_codigo == null ){ 
       $this->erro_sql = " Campo Código do aluno nao Informado.";
       $this->erro_campo = "alu01_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cur01_codigo == null ){ 
       $this->erro_sql = " Campo Código do curso nao Informado.";
       $this->erro_campo = "cur01_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac01_data_matricula == null ){ 
       $this->erro_sql = " Campo Data da matricula nao Informado.";
       $this->erro_campo = "ac01_data_matricula_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into alunos_cursos(
                                       alu01_codigo 
                                      ,cur01_codigo 
                                      ,ac01_data_matricula 
                       )
                values (
                                $this->alu01_codigo 
                               ,$this->cur01_codigo 
                               ,".($this->ac01_data_matricula == "null" || $this->ac01_data_matricula == ""?"null":"'".$this->ac01_data_matricula."'")." 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Alunos cursos () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Alunos cursos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Alunos cursos () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update alunos_cursos set ";
     $virgula = "";
     if(trim($this->alu01_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["alu01_codigo"])){ 
        if(trim($this->alu01_codigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["alu01_codigo"])){ 
           $this->alu01_codigo = "0" ; 
        } 
       $sql  .= $virgula." alu01_codigo = $this->alu01_codigo ";
       $virgula = ",";
       if(trim($this->alu01_codigo) == null ){ 
         $this->erro_sql = " Campo Código do aluno nao Informado.";
         $this->erro_campo = "alu01_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
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
     if(trim($this->ac01_data_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac01_data_matricula_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac01_data_matricula_dia"] !="") ){ 
       $sql  .= $virgula." ac01_data_matricula = '$this->ac01_data_matricula' ";
       $virgula = ",";
       if(trim($this->ac01_data_matricula) == null ){ 
         $this->erro_sql = " Campo Data da matricula nao Informado.";
         $this->erro_campo = "ac01_data_matricula_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac01_data_matricula_dia"])){ 
         $sql  .= $virgula." ac01_data_matricula = null ";
         $virgula = ",";
         if(trim($this->ac01_data_matricula) == null ){ 
           $this->erro_sql = " Campo Data da matricula nao Informado.";
           $this->erro_campo = "ac01_data_matricula_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where oid = $oid ";
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alunos cursos nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alunos cursos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ) { 
     $this->atualizacampos(true);
     $sql = " delete from alunos_cursos
                    where ";
     $sql2 = "";
     $sql2 = "oid = $oid";
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alunos cursos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alunos cursos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
   function sql_query ( $oid = null,$campos="alunos_cursos.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunos_cursos ";
     $sql .= "      inner join alunos  on  alunos.alu01_codigo = alunos_cursos.alu01_codigo";
     $sql .= "      inner join cursos  on  cursos.cur01_codigo = alunos_cursos.cur01_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where alunos_cursos.oid = $oid";
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunos_cursos ";
     $sql2 = "";
     if($dbwhere==""){
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
