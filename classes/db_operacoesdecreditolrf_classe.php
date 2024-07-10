
<?
//MODULO: contabilidade
//CLASSE DA ENTIDADE operacoesdecreditolrf
class cl_operacoesdecreditolrf {
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
   var $c219_dadoscomplementareslrf = 0;
   var $c219_contopcredito = 0;
   var $c219_dsccontopcredito = 0;
   var $c219_realizopcredito = 0;
   var $c219_tiporealizopcreditocapta = 0;
   var $c219_tiporealizopcreditoreceb = 0;
   var $c219_tiporealizopcreditoassundir = 0;
   var $c219_tiporealizopcreditoassunobg = 0;
   var $c219_dscnumeroinst = 0;
   
   
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c219_dadoscomplementareslrf = int4 = Sequencial DCLRF
                 c219_contopcredito = int4 = Contrata��o de opera��o de cr�dito
                 c219_dsccontopcredito = int4 = Descri��o da ocorr�ncia
                 c219_realizopcredito = int4 = opera��es de cr�dito vedadas
                 c219_tiporealizopcreditocapta = int4 = Tipo da realiza��o de opera��es de cr�d
                 c219_tiporealizopcreditoreceb = int4 = Tipo da realiza��o de opera��es de cr�d
                 c219_tiporealizopcreditoassundir = int4 = Tipo da realiza��o de opera��es de cr�d
                 c219_tiporealizopcreditoassunobg = int4 = Tipo da realiza��o de opera��es de cr�d
                 c219_dscnumeroinst = varchar(3) = Descri��o do n�mero da institui��o financeira da opera��o de cr�dito contratada
                 ";
   //funcao construtor da classe
   function cl_operacoesdecreditolrf() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("operacoesdecreditolrf");
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
       $this->c219_dadoscomplementareslrf = ($this->c219_dadoscomplementareslrf == ""?@$GLOBALS["HTTP_POST_VARS"]["c219_dadoscomplementareslrf"]:$this->c219_dadoscomplementareslrf);
       $this->c219_contopcredito = ($this->c219_contopcredito == ""?@$GLOBALS["HTTP_POST_VARS"]["c219_contopcredito"]:$this->c219_contopcredito);
       $this->c219_dsccontopcredito = ($this->c219_dsccontopcredito == ""?@$GLOBALS["HTTP_POST_VARS"]["c219_dsccontopcredito"]:$this->c219_dsccontopcredito);
       $this->c219_realizopcredito = ($this->c219_realizopcredito == ""?@$GLOBALS["HTTP_POST_VARS"]["c219_realizopcredito"]:$this->c219_realizopcredito);
       $this->c219_tiporealizopcreditocapta = ($this->c219_tiporealizopcreditocapta == ""?@$GLOBALS["HTTP_POST_VARS"]["c219_tiporealizopcreditocapta"]:$this->c219_tiporealizopcreditocapta);
       $this->c219_tiporealizopcreditoreceb = ($this->c219_tiporealizopcreditoreceb == ""?@$GLOBALS["HTTP_POST_VARS"]["c219_tiporealizopcreditoreceb"]:$this->c219_tiporealizopcreditoreceb);
       $this->c219_tiporealizopcreditoassundir = ($this->c219_tiporealizopcreditoassundir == ""?@$GLOBALS["HTTP_POST_VARS"]["c219_tiporealizopcreditoassundir"]:$this->c219_tiporealizopcreditoassundir);
       $this->c219_tiporealizopcreditoassunobg = ($this->c219_tiporealizopcreditoassunobg == ""?@$GLOBALS["HTTP_POST_VARS"]["c219_tiporealizopcreditoassunobg"]:$this->c219_tiporealizopcreditoassunobg);
       $this->c219_dscnumeroinst = ($this->c219_dscnumeroinst  == ""?@$GLOBALS["HTTP_POST_VARS"]["c219_dscnumeroinst"]:$this->c219_dscnumeroinst);
      
      }else{
     }
   }
  
   // funcao para inclusao
   function incluir (){
      $this->atualizacampos();
     if($this->c219_dadoscomplementareslrf == null ){
       $this->erro_sql = " Campo Sequencial DCLRF nao Informado.";
       $this->erro_campo = "c219_dadoscomplementareslrf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c219_contopcredito == null ){
       $this->erro_sql = " Campo Contrata��o de opera��o de cr�dito nao Informado.";
       $this->erro_campo = "c219_contopcredito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c219_realizopcredito == null ){
       $this->erro_sql = " Campo opera��es de cr�dito vedadas nao Informado.";
       $this->erro_campo = "c219_realizopcredito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c219_tiporealizopcreditocapta == null ){
       $this->erro_sql = " Campo Tipo da realiza��o de opera��es de cr�d nao Informado.";
       $this->erro_campo = "c219_tiporealizopcreditocapta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c219_tiporealizopcreditoreceb == null ){
       $this->erro_sql = " Campo Tipo da realiza��o de opera��es de cr�d nao Informado.";
       $this->erro_campo = "c219_tiporealizopcreditoreceb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c219_tiporealizopcreditoassundir == null ){
       $this->erro_sql = " Campo Tipo da realiza��o de opera��es de cr�d nao Informado.";
       $this->erro_campo = "c219_tiporealizopcreditoassundir";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c219_tiporealizopcreditoassunobg == null ){
       $this->erro_sql = " Campo Tipo da realiza��o de opera��es de cr�d nao Informado.";
       $this->erro_campo = "c219_tiporealizopcreditoassunobg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into operacoesdecreditolrf(
                                       c219_dadoscomplementareslrf
                                      ,c219_contopcredito
                                      ,c219_dsccontopcredito
                                      ,c219_realizopcredito
                                      ,c219_tiporealizopcreditocapta
                                      ,c219_tiporealizopcreditoreceb
                                      ,c219_tiporealizopcreditoassundir
                                      ,c219_tiporealizopcreditoassunobg
                                      ,c219_dscnumeroinst 
                       )
                values (
                                $this->c219_dadoscomplementareslrf
                               ,$this->c219_contopcredito
                               ,'$this->c219_dsccontopcredito'
                               ,$this->c219_realizopcredito
                               ,$this->c219_tiporealizopcreditocapta
                               ,$this->c219_tiporealizopcreditoreceb
                               ,$this->c219_tiporealizopcreditoassundir
                               ,$this->c219_tiporealizopcreditoassunobg
                               ,'$this->c219_dscnumeroinst' 
                      )");
               
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Informa��es Sobre Opera��es de Cr�dito () nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Informa��es Sobre Opera��es de Cr�dito j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Informa��es Sobre Opera��es de Cr�dito () nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     return true;
   }
   // funcao para alteracao
   function alterar ( $c219_dadoscomplementareslrf=null ) {
      $this->atualizacampos();
     $sql = " update operacoesdecreditolrf set ";
     $virgula = "";
     if(trim($this->c219_dadoscomplementareslrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c219_dadoscomplementareslrf"])){
        if(trim($this->c219_dadoscomplementareslrf)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c219_dadoscomplementareslrf"])){
           $this->c219_dadoscomplementareslrf = "0" ;
        }
       $sql  .= $virgula." c219_dadoscomplementareslrf = $this->c219_dadoscomplementareslrf ";
       $virgula = ",";
       if(trim($this->c219_dadoscomplementareslrf) == null ){
         $this->erro_sql = " Campo Sequencial DCLRF nao Informado.";
         $this->erro_campo = "c219_dadoscomplementareslrf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c219_contopcredito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c219_contopcredito"])){
        if(trim($this->c219_contopcredito)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c219_contopcredito"])){
           $this->c219_contopcredito = "0" ;
        }
       $sql  .= $virgula." c219_contopcredito = $this->c219_contopcredito ";
       $virgula = ",";
       if(trim($this->c219_contopcredito) == null ){
         $this->erro_sql = " Campo Contrata��o de opera��o de cr�dito nao Informado.";
         $this->erro_campo = "c219_contopcredito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c219_contopcredito)==1){
     if(trim($this->c219_dsccontopcredito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c219_dsccontopcredito"])){
        if(trim($this->c219_dsccontopcredito)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c219_dsccontopcredito"])){
           $this->c219_dsccontopcredito = "0" ;
        }
       $sql  .= $virgula." c219_dsccontopcredito = '$this->c219_dsccontopcredito' ";
       $virgula = ",";

     }
    }else{
      $sql  .= $virgula." c219_dsccontopcredito = '' ";
      $virgula = ",";
    }
     if(trim($this->c219_realizopcredito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c219_realizopcredito"])){
        if(trim($this->c219_realizopcredito)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c219_realizopcredito"])){
           $this->c219_realizopcredito = "0" ;
        }
       $sql  .= $virgula." c219_realizopcredito = $this->c219_realizopcredito ";
       $virgula = ",";
       if(trim($this->c219_realizopcredito) == null ){
         $this->erro_sql = " Campo opera��es de cr�dito vedadas nao Informado.";
         $this->erro_campo = "c219_realizopcredito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c219_tiporealizopcreditocapta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c219_tiporealizopcreditocapta"])){
        if(trim($this->c219_tiporealizopcreditocapta)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c219_tiporealizopcreditocapta"])){
           $this->c219_tiporealizopcreditocapta = "0" ;
        }
       $sql  .= $virgula." c219_tiporealizopcreditocapta = $this->c219_tiporealizopcreditocapta ";
       $virgula = ",";
       if(trim($this->c219_tiporealizopcreditocapta) == null ){
         $this->erro_sql = " Campo Tipo da realiza��o de opera��es de cr�d nao Informado.";
         $this->erro_campo = "c219_tiporealizopcreditocapta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c219_tiporealizopcreditoreceb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c219_tiporealizopcreditoreceb"])){
        if(trim($this->c219_tiporealizopcreditoreceb)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c219_tiporealizopcreditoreceb"])){
           $this->c219_tiporealizopcreditoreceb = "0" ;
        }
       $sql  .= $virgula." c219_tiporealizopcreditoreceb = $this->c219_tiporealizopcreditoreceb ";
       $virgula = ",";
       if(trim($this->c219_tiporealizopcreditoreceb) == null ){
         $this->erro_sql = " Campo Tipo da realiza��o de opera��es de cr�d nao Informado.";
         $this->erro_campo = "c219_tiporealizopcreditoreceb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c219_tiporealizopcreditoassundir)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c219_tiporealizopcreditoassundir"])){
        if(trim($this->c219_tiporealizopcreditoassundir)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c219_tiporealizopcreditoassundir"])){
           $this->c219_tiporealizopcreditoassundir = "0" ;
        }
       $sql  .= $virgula." c219_tiporealizopcreditoassundir = $this->c219_tiporealizopcreditoassundir ";
       $virgula = ",";
       if(trim($this->c219_tiporealizopcreditoassundir) == null ){
         $this->erro_sql = " Campo Tipo da realiza��o de opera��es de cr�d nao Informado.";
         $this->erro_campo = "c219_tiporealizopcreditoassundir";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c219_tiporealizopcreditoassunobg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c219_tiporealizopcreditoassunobg"])){
        if(trim($this->c219_tiporealizopcreditoassunobg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c219_tiporealizopcreditoassunobg"])){
           $this->c219_tiporealizopcreditoassunobg = "0" ;
        }
       $sql  .= $virgula." c219_tiporealizopcreditoassunobg = $this->c219_tiporealizopcreditoassunobg ";
       $virgula = ",";
       if(trim($this->c219_tiporealizopcreditoassunobg) == null ){
         $this->erro_sql = " Campo Tipo da realiza��o de opera��es de cr�d nao Informado.";
         $this->erro_campo = "c219_tiporealizopcreditoassunobg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     
    if(trim($this->c219_contopcredito)==1){
        if(trim($this->c219_dscnumeroinst)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c219_dscnumeroinst "])){
          if(trim($this->c219_dscnumeroinst )=="" && isset($GLOBALS["HTTP_POST_VARS"]["c219_dscnumeroinst "])){
            $this->c219_dscnumeroinst= "" ;
          }
        $sql  .= $virgula." c219_dscnumeroinst  = '$this->c219_dscnumeroinst'  ";
        $virgula = ",";
          }
    }else{
        $sql  .= $virgula." c219_dscnumeroinst  = null  ";
        $virgula = ",";
    }     

     $sql .= " where c219_dadoscomplementareslrf = $c219_dadoscomplementareslrf ";
     $result = @pg_exec($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informa��es Sobre Opera��es de Cr�dito nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Informa��es Sobre Opera��es de Cr�dito nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ( $c219_dadoscomplementareslrf=null ) {
     $this->atualizacampos(true);
     $sql = " delete from operacoesdecreditolrf
                    where ";
     $sql2 = "";
     $sql2 = "c219_dadoscomplementareslrf = $c219_dadoscomplementareslrf";
     $result = @pg_exec($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informa��es Sobre Opera��es de Cr�dito nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Informa��es Sobre Opera��es de Cr�dito nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $c219_dadoscomplementareslrf = null,$campos="operacoesdecreditolrf.c219_dadoscomplementareslrf,*",$ordem=null,$dbwhere=""){
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
     $sql .= " from operacoesdecreditolrf ";
     $sql2 = "";
     if($dbwhere==""){
       if( $c219_dadoscomplementareslrf != "" && $c219_dadoscomplementareslrf != null){
          $sql2 = " where operacoesdecreditolrf.c219_dadoscomplementareslrf = $c219_dadoscomplementareslrf";
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
   function sql_query_file ( $c219_dadoscomplementareslrf = null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from operacoesdecreditolrf ";
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
