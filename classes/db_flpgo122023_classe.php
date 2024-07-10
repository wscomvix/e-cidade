<?
//MODULO: sicom
//CLASSE DA ENTIDADE \d flpgo122023
class cl_flpgo122023 {
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
    var $si197_sequencial = 0;
    var $si197_tiporegistro = 0;
    var $si197_indtipopagamento = 0;
    var $si197_codvinculopessoa = 0;
    var $si197_codrubricadesconto = 0;
    var $si197_desctiporubricadesconto = 0;
    var $si197_vlrdescontodetalhado = 0;
    var $si197_mes = 0;
    var $si197_inst = 0;
    var $si197_reg10 = 0;

    // cria propriedade com as variaveis do arquivo
    var $campos = "
                 si197_sequencial = int8 = si197_sequencial
                 si197_tiporegistro = int8 = Tipo registro
                 si197_indtipopagamento = varchar(1) = Tipo de pagamento
                 si197_codvinculopessoa = bigint = C�digo do vinculo do agente p�blico
                 si197_codrubricadesconto = int8 = C�digo da rubrica das parcelas de desconto
                 si197_desctiporubricadesconto = varchar(150) = Descri��o para as rubricas de desconto.
                 si197_vlrdescontodetalhado = float8 = Valor dos rendimentos por tipo
                 si197_mes = int8 = si197_mes
                 si197_inst = int8 = si197_inst
                 si197_reg10 = int8 = si197_reg10
                 ";
    //funcao construtor da classe
    function cl_flpgo122023() {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("flpgo122023");
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
            $this->si197_sequencial = ($this->si197_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si197_sequencial"]:$this->si197_sequencial);
            $this->si197_tiporegistro = ($this->si197_tiporegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["si197_tiporegistro"]:$this->si197_tiporegistro);
            $this->si197_indtipopagamento = ($this->si197_indtipopagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["si197_indtipopagamento"]:$this->si197_indtipopagamento);
            $this->si197_codvinculopessoa = ($this->si197_codvinculopessoa == ""?@$GLOBALS["HTTP_POST_VARS"]["si197_codvinculopessoa"]:$this->si197_codvinculopessoa);
            $this->si197_codrubricadesconto = ($this->si197_codrubricadesconto == ""?@$GLOBALS["HTTP_POST_VARS"]["si197_codrubricadesconto"]:$this->si197_codrubricadesconto);
            $this->si197_desctiporubricadesconto = ($this->si197_desctiporubricadesconto == ""?@$GLOBALS["HTTP_POST_VARS"]["si197_desctiporubricadesconto"]:$this->si197_desctiporubricadesconto);
            $this->si197_vlrdescontodetalhado = ($this->si197_vlrdescontodetalhado == ""?@$GLOBALS["HTTP_POST_VARS"]["si197_vlrdescontodetalhado"]:$this->si197_vlrdescontodetalhado);
            $this->si197_mes = ($this->si197_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si197_mes"]:$this->si197_mes);
            $this->si197_inst = ($this->si197_inst == ""?@$GLOBALS["HTTP_POST_VARS"]["si197_inst"]:$this->si197_inst);
            $this->si197_reg10 = ($this->si197_reg10 == ""?@$GLOBALS["HTTP_POST_VARS"]["si197_reg10"]:$this->si197_reg10);
        }else{
            $this->si197_sequencial = ($this->si197_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si197_sequencial"]:$this->si197_sequencial);
        }
    }
    // funcao para inclusao
    function incluir ($si197_sequencial){
        $this->atualizacampos();
        if($this->si197_tiporegistro == null ){
            $this->erro_sql = " Campo Tipo registro n�o informado.";
            $this->erro_campo = "si197_tiporegistro";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->si197_vlrdescontodetalhado == null ){
            $this->erro_sql = " Campo Valor dos rendimentos por tipo n�o informado.";
            $this->erro_campo = "si197_vlrdescontodetalhado";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->si197_mes == null ){
            $this->erro_sql = " Campo si197_mes n�o informado.";
            $this->erro_campo = "si197_mes";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->si197_inst == null ){
            $this->erro_sql = " Campo si197_inst n�o informado.";
            $this->erro_campo = "si197_inst";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->si197_reg10 == null ){
            $this->erro_sql = " Campo si197_reg10 n�o informado.";
            $this->erro_campo = "si197_reg10";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($si197_sequencial == "" || $si197_sequencial == null ){
            $result = db_query("select nextval('flpgo122023_si197_sequencial_seq')");
            if($result==false){
                $this->erro_banco = str_replace("
","",@pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: flpgo122023_si197_sequencial_seq do campo: si197_sequencial";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
            $this->si197_sequencial = pg_result($result,0,0);
        }else{
            $result = db_query("select last_value from flpgo122023_si197_sequencial_seq");
            if(($result != false) && (pg_result($result,0,0) < $si197_sequencial)){
                $this->erro_sql = " Campo si197_sequencial maior que �ltimo n�mero da sequencia.";
                $this->erro_banco = "Sequencia menor que este n�mero.";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }else{
                $this->si197_sequencial = $si197_sequencial;
            }
        }
        if(($this->si197_sequencial == null) || ($this->si197_sequencial == "") ){
            $this->erro_sql = " Campo si197_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into flpgo122023(
                                       si197_sequencial
                                      ,si197_tiporegistro
                                      ,si197_indtipopagamento
                                      ,si197_codvinculopessoa
                                      ,si197_codrubricadesconto
                                      ,si197_desctiporubricadesconto
                                      ,si197_vlrdescontodetalhado
                                      ,si197_mes
                                      ,si197_inst
                                      ,si197_reg10
                       )
                values (
                                $this->si197_sequencial
                               ,$this->si197_tiporegistro
                               ,'$this->si197_indtipopagamento'
                               ,'$this->si197_codvinculopessoa'
                               ,'$this->si197_codrubricadesconto'
                               ,'$this->si197_desctiporubricadesconto'
                               ,$this->si197_vlrdescontodetalhado
                               ,$this->si197_mes
                               ,$this->si197_inst
                               ,$this->si197_reg10
                      )";
        $result = db_query($sql);
        if($result==false){
            $this->erro_banco = str_replace("
","",@pg_last_error());
            if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
                $this->erro_sql   = "flpgo122023 ($this->si197_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_banco = "flpgo122023 j� Cadastrado";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            }else{
                $this->erro_sql   = "flpgo122023 ($this->si197_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir= 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : ".$this->si197_sequencial;
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "1";
        $this->numrows_incluir= pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->si197_sequencial  ));
            if(($resaco!=false)||($this->numrows!=0)){

                /*$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac,0,0);
                $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                $resac = db_query("insert into db_acountkey values($acount,1009299,'$this->si197_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010196,1009299,'','".AddSlashes(pg_result($resaco,0,'si197_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010196,1009300,'','".AddSlashes(pg_result($resaco,0,'si197_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010196,1009301,'','".AddSlashes(pg_result($resaco,0,'si197_nrodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010196,1009302,'','".AddSlashes(pg_result($resaco,0,'si197_tipodesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010196,1009305,'','".AddSlashes(pg_result($resaco,0,'si197_vlrdescontodetalhado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010196,1009306,'','".AddSlashes(pg_result($resaco,0,'si197_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010196,1009307,'','".AddSlashes(pg_result($resaco,0,'si197_inst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010196,1009317,'','".AddSlashes(pg_result($resaco,0,'si197_reg10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
            }
        }
        return true;
    }
    // funcao para alteracao
    function alterar ($si197_sequencial=null) {
        $this->atualizacampos();
        $sql = " update flpgo122023 set ";
        $virgula = "";
        if(trim($this->si197_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si197_sequencial"])){
            $sql  .= $virgula." si197_sequencial = $this->si197_sequencial ";
            $virgula = ",";
            if(trim($this->si197_sequencial) == null ){
                $this->erro_sql = " Campo si197_sequencial n�o informado.";
                $this->erro_campo = "si197_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si197_tiporegistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si197_tiporegistro"])){
            $sql  .= $virgula." si197_tiporegistro = $this->si197_tiporegistro ";
            $virgula = ",";
            if(trim($this->si197_tiporegistro) == null ){
                $this->erro_sql = " Campo Tipo registro n�o informado.";
                $this->erro_campo = "si197_tiporegistro";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if(trim($this->si197_vlrdescontodetalhado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si197_vlrdescontodetalhado"])){
            $sql  .= $virgula." si197_vlrdescontodetalhado = $this->si197_vlrdescontodetalhado ";
            $virgula = ",";
            if(trim($this->si197_vlrdescontodetalhado) == null ){
                $this->erro_sql = " Campo Valor dos rendimentos por tipo n�o informado.";
                $this->erro_campo = "si197_vlrdescontodetalhado";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si197_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si197_mes"])){
            $sql  .= $virgula." si197_mes = $this->si197_mes ";
            $virgula = ",";
            if(trim($this->si197_mes) == null ){
                $this->erro_sql = " Campo si197_mes n�o informado.";
                $this->erro_campo = "si197_mes";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si197_inst)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si197_inst"])){
            $sql  .= $virgula." si197_inst = $this->si197_inst ";
            $virgula = ",";
            if(trim($this->si197_inst) == null ){
                $this->erro_sql = " Campo si197_inst n�o informado.";
                $this->erro_campo = "si197_inst";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si197_reg10)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si197_reg10"])){
            $sql  .= $virgula." si197_reg10 = $this->si197_reg10 ";
            $virgula = ",";
            if(trim($this->si197_reg10) == null ){
                $this->erro_sql = " Campo si197_reg10 n�o informado.";
                $this->erro_campo = "si197_reg10";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if($si197_sequencial!=null){
            $sql .= " si197_sequencial = $this->si197_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->si197_sequencial));
            if($this->numrows>0){

                for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

                    /*$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac,0,0);
                    $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                    $resac = db_query("insert into db_acountkey values($acount,1009299,'$this->si197_sequencial','A')");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["si197_sequencial"]) || $this->si197_sequencial != "")
                      $resac = db_query("insert into db_acount values($acount,1010196,1009299,'".AddSlashes(pg_result($resaco,$conresaco,'si197_sequencial'))."','$this->si197_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["si197_tiporegistro"]) || $this->si197_tiporegistro != "")
                      $resac = db_query("insert into db_acount values($acount,1010196,1009300,'".AddSlashes(pg_result($resaco,$conresaco,'si197_tiporegistro'))."','$this->si197_tiporegistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["si197_nrodocumento"]) || $this->si197_nrodocumento != "")
                      $resac = db_query("insert into db_acount values($acount,1010196,1009301,'".AddSlashes(pg_result($resaco,$conresaco,'si197_nrodocumento'))."','$this->si197_nrodocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["si197_tipodesconto"]) || $this->si197_tipodesconto != "")
                      $resac = db_query("insert into db_acount values($acount,1010196,1009302,'".AddSlashes(pg_result($resaco,$conresaco,'si197_tipodesconto'))."','$this->si197_tipodesconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["si197_vlrdescontodetalhado"]) || $this->si197_vlrdescontodetalhado != "")
                      $resac = db_query("insert into db_acount values($acount,1010196,1009305,'".AddSlashes(pg_result($resaco,$conresaco,'si197_vlrdescontodetalhado'))."','$this->si197_vlrdescontodetalhado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["si197_mes"]) || $this->si197_mes != "")
                      $resac = db_query("insert into db_acount values($acount,1010196,1009306,'".AddSlashes(pg_result($resaco,$conresaco,'si197_mes'))."','$this->si197_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["si197_inst"]) || $this->si197_inst != "")
                      $resac = db_query("insert into db_acount values($acount,1010196,1009307,'".AddSlashes(pg_result($resaco,$conresaco,'si197_inst'))."','$this->si197_inst',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["si197_reg10"]) || $this->si197_reg10 != "")
                      $resac = db_query("insert into db_acount values($acount,1010196,1009317,'".AddSlashes(pg_result($resaco,$conresaco,'si197_reg10'))."','$this->si197_reg10',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
                }
            }
        }
        $result = db_query($sql);
        if($result==false){
            $this->erro_banco = str_replace("
","",@pg_last_error());
            $this->erro_sql   = "flpgo122023 nao Alterado. Alteracao Abortada.\n";
            $this->erro_sql .= "Valores : ".$this->si197_sequencial;
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = "flpgo122023 nao foi Alterado. Alteracao Executada.\n";
                $this->erro_sql .= "Valores : ".$this->si197_sequencial;
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\n";
                $this->erro_sql .= "Valores : ".$this->si197_sequencial;
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao
    function excluir ($si197_sequencial=null,$dbwhere=null) {

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if ($dbwhere==null || $dbwhere=="") {

                $resaco = $this->sql_record($this->sql_query_file($si197_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
            }
            if (($resaco != false) || ($this->numrows!=0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    /*$resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac,0,0);
                    $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                    $resac  = db_query("insert into db_acountkey values($acount,1009299,'$si197_sequencial','E')");
                    $resac  = db_query("insert into db_acount values($acount,1010196,1009299,'','".AddSlashes(pg_result($resaco,$iresaco,'si197_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,1010196,1009300,'','".AddSlashes(pg_result($resaco,$iresaco,'si197_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,1010196,1009301,'','".AddSlashes(pg_result($resaco,$iresaco,'si197_nrodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,1010196,1009302,'','".AddSlashes(pg_result($resaco,$iresaco,'si197_tipodesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,1010196,1009305,'','".AddSlashes(pg_result($resaco,$iresaco,'si197_vlrdescontodetalhado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,1010196,1009306,'','".AddSlashes(pg_result($resaco,$iresaco,'si197_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,1010196,1009307,'','".AddSlashes(pg_result($resaco,$iresaco,'si197_inst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,1010196,1009317,'','".AddSlashes(pg_result($resaco,$iresaco,'si197_reg10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
                }
            }
        }
        $sql = " delete from flpgo122023
                    where ";
        $sql2 = "";
        if($dbwhere==null || $dbwhere ==""){
            if($si197_sequencial != ""){
                if($sql2!=""){
                    $sql2 .= " and ";
                }
                $sql2 .= " si197_sequencial = $si197_sequencial ";
            }
        }else{
            $sql2 = $dbwhere;
        }
        $result = db_query($sql.$sql2);
        if($result==false){
            $this->erro_banco = str_replace("
","",@pg_last_error());
            $this->erro_sql   = "flpgo122023 nao Exclu�do. Exclus�o Abortada.\n";
            $this->erro_sql .= "Valores : ".$si197_sequencial;
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = "flpgo122023 nao Encontrado. Exclus�o n�o Efetuada.\n";
                $this->erro_sql .= "Valores : ".$si197_sequencial;
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
                $this->erro_sql .= "Valores : ".$si197_sequencial;
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
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
            $this->erro_banco = str_replace("
","",@pg_last_error());
            $this->erro_sql   = "Erro ao selecionar os registros.";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_numrows($result);
        if($this->numrows==0){
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:flpgo122023";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    // funcao do sql
    function sql_query ( $si197_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from flpgo122023 ";
        $sql .= "      inner join flpgo102023  on  flpgo102023.si195_sequencial = flpgo122023.si197_reg10";
        $sql2 = "";
        if($dbwhere==""){
            if($si197_sequencial!=null ){
                $sql2 .= " where flpgo122023.si197_sequencial = $si197_sequencial ";
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
    function sql_query_file ( $si197_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from flpgo122023 ";
        $sql2 = "";
        if($dbwhere==""){
            if($si197_sequencial!=null ){
                $sql2 .= " where flpgo122023.si197_sequencial = $si197_sequencial ";
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
