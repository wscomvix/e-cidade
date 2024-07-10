<?
//MODULO: sicom
//CLASSE DA ENTIDADE redispi102023
class cl_redispi102023
{
  // cria variaveis de erro
  var $rotulo = null;
  var $query_sql = null;
  var $numrows = 0;
  var $numrows_incluir = 0;
  var $numrows_alterar = 0;
  var $numrows_excluir = 0;
  var $erro_status = null;
  var $erro_sql = null;
  var $erro_banco = null;
  var $erro_msg = null;
  var $erro_campo = null;
  var $pagina_retorno = null;
  // cria variaveis do arquivo
  var $si183_sequencial = 0;
  var $si183_tiporegistro = 0;
  var $si183_codorgaoresp = null;
  var $si183_codunidadesubresp = null;
  var $si183_codunidadesubrespestadual = null;
  var $si183_exercicioprocesso = 0;
  var $si183_nroprocesso = null;
  var $si183_tipoprocesso = 0;
  var $si183_tipocadastradodispensainexigibilidade = 0;
  var $si183_dsccadastrolicitatorio = 0;
  var $si183_dtabertura_dia = null;
  var $si183_dtabertura_mes = null;
  var $si183_dtabertura_ano = null;
  var $si183_dtabertura = null;
  var $si183_naturezaobjeto = 0;
  var $si183_objeto = null;
  var $si183_justificativa = null;
  var $si183_razao = null;
  var $si183_vlrecurso = 0;
  var $si183_bdi = 0;
  var $si183_mes = 0;
  var $si183_instit = 0;
  var $si183_link = '';
  var $si183_leidalicitacao = 0;
  var $si183_regimeexecucaoobras = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si183_sequencial = int8 = sequencial 
                 si183_tiporegistro = int8 = Tipo do  registro 
                 si183_codorgaoresp = varchar(2) = C�digo do �rg�o respons�vel 
                 si183_codunidadesubresp = varchar(8) = C�digo da unidade 
                 si183_codunidadesubrespestadual = varchar(4) = C�digo da unidade respons�vel 
                 si183_exercicioprocesso = int8 = Exerc�cio em que foi instaurado 
                 si183_nroprocesso = varchar(12) = N�mero sequencial  do processo 
                 si183_tipoprocesso = int8 = Tipo de processo 
                 si183_tipocadastradodispensainexigibilidade = int8 = Tipo de processo 
                 si183_dsccadastrolicitatorio = varchar(150) = Motivo da anula��o ou revoga��o 
                 si183_dtabertura = date = Data de abertura 
                 si183_naturezaobjeto = int8 = Natureza do objeto 
                 si183_objeto = varchar(500) = Objeto da  contrata��o 
                 si183_justificativa = varchar(250) = Justificativa 
                 si183_razao = varchar(250) = Raz�o 
                 si183_vlrecurso =real = Valor do Recurso Previsto 
                 si183_bdi = real = Bonifica��o e Despesas indiretas 
                 si183_link = varchar(200) = Link de publica��o
                 si183_mes = int8 = M�s 
                 si183_instit = int8 = Institui��o 
                 si183_leidalicitacao = Lei da Licita��o
                 si183_regimeexecucaoobras = Regime execu��o obras
                 ";

  // funcao construtor da classe
  function cl_redispi102023()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("redispi102023");
    $this->pagina_retorno = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }

  // funcao erro
  function erro($mostra, $retorna)
  {
    if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {
      echo "<script>alert(\"" . $this->erro_msg . "\");</script>";
      if ($retorna == true) {
        echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
      }
    }
  }

  //  funcao para atualizar campos
  function atualizacampos($exclusao = false)
  {
    if ($exclusao == false) {
      $this->si183_sequencial = ($this->si183_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_sequencial"] : $this->si183_sequencial);
      $this->si183_tiporegistro = ($this->si183_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_tiporegistro"] : $this->si183_tiporegistro);
      $this->si183_codorgaoresp = ($this->si183_codorgaoresp == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_codorgaoresp"] : $this->si183_codorgaoresp);
      $this->si183_codunidadesubresp = ($this->si183_codunidadesubresp == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_codunidadesubresp"] : $this->si183_codunidadesubresp);
      $this->si183_codunidadesubrespestadual = ($this->si183_codunidadesubrespestadual == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_codunidadesubrespestadual"] : $this->si183_codunidadesubrespestadual);
      $this->si183_exercicioprocesso = ($this->si183_exercicioprocesso == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_exercicioprocesso"] : $this->si183_exercicioprocesso);
      $this->si183_nroprocesso = ($this->si183_nroprocesso == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_nroprocesso"] : $this->si183_nroprocesso);
      $this->si183_tipoprocesso = ($this->si183_tipoprocesso == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_tipoprocesso"] : $this->si183_tipoprocesso);
      $this->si183_tipocadastradodispensainexigibilidade = ($this->si183_tipocadastradodispensainexigibilidade == "" ? @$GLOBALS["HTTP_POST_VARS"]["$this->si183_tipocadastradodispensainexigibilidade"] : $this->si183_tipocadastradodispensainexigibilidade);
      $this->si183_dsccadastrolicitatorio = ($this->si183_dsccadastrolicitatorio == "" ? @$GLOBALS["HTTP_POST_VARS"]["$this->si183_dsccadastrolicitatorio"] : $this->si183_dsccadastrolicitatorio);
      if ($this->si183_dtabertura == "") {
        $this->si183_dtabertura_dia = ($this->si183_dtabertura_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_dtabertura_dia"] : $this->si183_dtabertura_dia);
        $this->si183_dtabertura_mes = ($this->si183_dtabertura_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_dtabertura_mes"] : $this->si183_dtabertura_mes);
        $this->si183_dtabertura_ano = ($this->si183_dtabertura_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_dtabertura_ano"] : $this->si183_dtabertura_ano);
        if ($this->si183_dtabertura_dia != "") {
          $this->si183_dtabertura = $this->si183_dtabertura_ano . "-" . $this->si183_dtabertura_mes . "-" . $this->si183_dtabertura_dia;
        }
      }
      $this->si183_naturezaobjeto = ($this->si183_naturezaobjeto == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_naturezaobjeto"] : $this->si183_naturezaobjeto);
      $this->si183_objeto = ($this->si183_objeto == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_objeto"] : $this->si183_objeto);
      $this->si183_justificativa = ($this->si183_justificativa == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_justificativa"] : $this->si183_justificativa);
      $this->si183_razao = ($this->si183_razao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_razao"] : $this->si183_razao);
      $this->si183_vlrecurso = ($this->si183_vlrecurso == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_vlrecurso"] : $this->si183_vlrecurso);
      $this->si183_bdi = ($this->si183_bdi == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_bdi"] : $this->si183_bdi);
      $this->si183_link = ($this->si183_link == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_link"] : $this->si183_link);
      $this->si183_mes = ($this->si183_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_mes"] : $this->si183_mes);
      $this->si183_instit = ($this->si183_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_instit"] : $this->si183_instit);
      $this->si183_leidalicitacao = ($this->si183_leidalicitacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_leidalicitacao"] : $this->si183_leidalicitacao);
      $this->si183_regimeexecucaoobras = ($this->si183_regimeexecucaoobras == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_regimeexecucaoobras"] : $this->si183_regimeexecucaoobras);
    } else {
      $this->si183_sequencial = ($this->si183_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si183_sequencial"] : $this->si183_sequencial);
    }
  }

  // funcao para inclusao
  function incluir($si183_sequencial)
  {
    $this->atualizacampos();
    if ($this->si183_tiporegistro == null) {
      $this->erro_sql = " Campo Tipo do  registro nao Informado.";
      $this->erro_campo = "si183_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si183_exercicioprocesso == null) {
      $this->si183_exercicioprocesso = "0";
    }
    if ($this->si183_tipoprocesso == null) {
      $this->si183_tipoprocesso = "0";
    }
    if ($this->si183_dtabertura == null) {
      $this->si183_dtabertura = "null";
    }
    if ($this->si183_naturezaobjeto == null) {
      $this->si183_naturezaobjeto = "0";
    }
    if ($this->si183_mes == null) {
      $this->erro_sql = " Campo M�s nao Informado.";
      $this->erro_campo = "si183_mes";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si183_instit == null) {
      $this->erro_sql = " Campo Institui��o nao Informado.";
      $this->erro_campo = "si183_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si183_leidalicitacao == null) {
      $this->erro_sql = " Campo Lei da licita��o nao Informado.";
      $this->erro_campo = "si183_leidalicitacao";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si183_regimeexecucaoobras == null) {
      $this->erro_sql = " Campo Regime de obras nao Informado.";
      $this->erro_campo = "si183_regimeexecucaoobras";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      return false;
    }

    if (($this->si183_link == null) || ($this->si183_link == "")) {
      $this->erro_sql = " Campo si183_link nao declarado.";
      $this->erro_campo = "si183_link";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      return false;
    }
    if ($si183_sequencial == "" || $si183_sequencial == null) {
      $result = db_query("select nextval('redispi102023_si183_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("
", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: redispi102023_si183_sequencial_seq do campo: si183_sequencial";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        return false;
      }
      $this->si183_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from redispi102023_si183_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si183_sequencial)) {
        $this->erro_sql = " Campo si183_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        return false;
      } else {
        $this->si183_sequencial = $si183_sequencial;
      }
    }
    if (($this->si183_sequencial == null) || ($this->si183_sequencial == "")) {
      $this->erro_sql = " Campo si183_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si183_tipocadastradodispensainexigibilidade == null) {
      $this->si183_tipocadastradodispensainexigibilidade = 0;
    }
    if ($this->si183_bdi == null) {
      $this->si183_bdi = 0;
    }

    if ($this->si183_vlrecurso == null) {
      $this->si183_vlrecurso = 0;
    }

    $sql = "insert into redispi102023(
                                       si183_sequencial 
                                      ,si183_tiporegistro 
                                      ,si183_codorgaoresp 
                                      ,si183_codunidadesubresp 
                                      ,si183_codunidadesubrespestadual 
                                      ,si183_exercicioprocesso 
                                      ,si183_nroprocesso 
                                      ,si183_tipoprocesso 
                                      ,si183_tipocadastradodispensainexigibilidade 
                                      ,si183_dsccadastrolicitatorio 
                                      ,si183_dtabertura 
                                      ,si183_naturezaobjeto 
                                      ,si183_objeto 
                                      ,si183_justificativa 
                                      ,si183_razao 
                                      ,si183_vlrecurso 
                                      ,si183_bdi 
                                      ,si183_link 
                                      ,si183_mes 
                                      ,si183_instit
                                      ,si183_leidalicitacao
                                      ,si183_regimeexecucaoobras 
                       )
                values (
                                $this->si183_sequencial 
                               ,$this->si183_tiporegistro 
                               ,'$this->si183_codorgaoresp' 
                               ,'$this->si183_codunidadesubresp' 
                               ,'$this->si183_codunidadesubrespestadual' 
                               ,$this->si183_exercicioprocesso 
                               ,'$this->si183_nroprocesso' 
                               ,$this->si183_tipoprocesso 
                               ,$this->si183_tipocadastradodispensainexigibilidade 
                               ,'$this->si183_dsccadastrolicitatorio' 
                               ," . ($this->si183_dtabertura == "null" || $this->si183_dtabertura == "" ? "" : "'" . $this->si183_dtabertura . "'") . " 
                               ,$this->si183_naturezaobjeto 
                               ,'$this->si183_objeto' 
                               ,'$this->si183_justificativa' 
                               ,'$this->si183_razao' 
                               ,$this->si183_vlrecurso 
                               ,$this->si183_bdi 
                               ,'$this->si183_link'
                               ,$this->si183_mes 
                               ,$this->si183_instit
                               ,$this->si183_leidalicitacao
                               ,$this->si183_regimeexecucaoobras  
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "redispi102023 ($this->si183_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_banco = "redispi102023 j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      } else {
        $this->erro_sql = "redispi102023 ($this->si183_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : " . $this->si183_sequencial;
    $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);

    return true;
  }

  // funcao para alteracao
  function alterar($si183_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update redispi102023 set ";
    $virgula = "";
    if (trim($this->si183_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_sequencial"])) {
      if (trim($this->si183_sequencial) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si183_sequencial"])) {
        $this->si183_sequencial = "0";
      }
      $sql .= $virgula . " si183_sequencial = $this->si183_sequencial ";
      $virgula = ",";
    }
    if (trim($this->si183_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_tiporegistro"])) {
      $sql .= $virgula . " si183_tiporegistro = $this->si183_tiporegistro ";
      $virgula = ",";
      if (trim($this->si183_tiporegistro) == null) {
        $this->erro_sql = " Campo Tipo do  registro nao Informado.";
        $this->erro_campo = "si183_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si183_codorgaoresp) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_codorgaoresp"])) {
      $sql .= $virgula . " si183_codorgaoresp = '$this->si183_codorgaoresp' ";
      $virgula = ",";
    }
    if (trim($this->si183_codunidadesubresp) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_codunidadesubresp"])) {
      $sql .= $virgula . " si183_codunidadesubresp = '$this->si183_codunidadesubresp' ";
      $virgula = ",";
    }
    if (trim($this->si183_exercicioprocesso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_exercicioprocesso"])) {
      if (trim($this->si183_exercicioprocesso) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si183_exercicioprocesso"])) {
        $this->si183_exercicioprocesso = "0";
      }
      $sql .= $virgula . " si183_exercicioprocesso = $this->si183_exercicioprocesso ";
      $virgula = ",";
    }
    if (trim($this->si183_nroprocesso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_nroprocesso"])) {
      $sql .= $virgula . " si183_nroprocesso = '$this->si183_nroprocesso' ";
      $virgula = ",";
    }
    if (trim($this->si183_tipoprocesso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_tipoprocesso"])) {
      if (trim($this->si183_tipoprocesso) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si183_tipoprocesso"])) {
        $this->si183_tipoprocesso = "0";
      }
      $sql .= $virgula . " si183_tipoprocesso = $this->si183_tipoprocesso ";
      $virgula = ",";
    }
    if (trim($this->si183_dtabertura) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_dtabertura_dia"]) && ($GLOBALS["HTTP_POST_VARS"]["si183_dtabertura_dia"] != "")) {
      $sql .= $virgula . " si183_dtabertura = '$this->si183_dtabertura' ";
      $virgula = ",";
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["si183_dtabertura_dia"])) {
        $sql .= $virgula . " si183_dtabertura = null ";
        $virgula = ",";
      }
    }
    if (trim($this->si183_naturezaobjeto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_naturezaobjeto"])) {
      if (trim($this->si183_naturezaobjeto) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si183_naturezaobjeto"])) {
        $this->si183_naturezaobjeto = "0";
      }
      $sql .= $virgula . " si183_naturezaobjeto = $this->si183_naturezaobjeto ";
      $virgula = ",";
    }
    if (trim($this->si183_objeto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_objeto"])) {
      $sql .= $virgula . " si183_objeto = '$this->si183_objeto' ";
      $virgula = ",";
    }
    if (trim($this->si183_justificativa) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_justificativa"])) {
      $sql .= $virgula . " si183_justificativa = '$this->si183_justificativa' ";
      $virgula = ",";
    }
    if (trim($this->si183_razao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_razao"])) {
      $sql .= $virgula . " si183_razao = '$this->si183_razao' ";
      $virgula = ",";
    }
    if (trim($this->si183_link) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_link"])) {
      $sql .= $virgula . " si183_link = '$this->si183_link' ";
      $virgula = ",";
    }

    if (trim($this->si183_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_mes"])) {
      $sql .= $virgula . " si183_mes = $this->si183_mes ";
      $virgula = ",";
      if (trim($this->si183_mes) == null) {
        $this->erro_sql = " Campo M�s nao Informado.";
        $this->erro_campo = "si183_mes";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si183_leidalicitacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_leidalicitacao"])) {
      $sql .= $virgula . " si183_leidalicitacao = $this->si183_leidalicitacao ";
      $virgula = ",";
      if (trim($this->si183_leidalicitacao) == null) {
        $this->erro_sql = " Campo Institui��o nao Informado.";
        $this->erro_campo = "si183_leidalicitacao";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si183_regimeexecucaoobras) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_regimeexecucaoobras"])) {
      $sql .= $virgula . " si183_regimeexecucaoobras = $this->si183_regimeexecucaoobras ";
      $virgula = ",";
      if (trim($this->si183_regimeexecucaoobras) == null) {
        $this->erro_sql = " Campo Regime execucao de obras nao Informado.";
        $this->erro_campo = "si183_regimeexecucaoobras";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si183_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si183_instit"])) {
      $sql .= $virgula . " si183_instit = $this->si183_instit ";
      $virgula = ",";
      if (trim($this->si183_instit) == null) {
        $this->erro_sql = " Campo Institui��o nao Informado.";
        $this->erro_campo = "si183_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if ($si183_sequencial != null) {
      $sql .= " si183_sequencial = $this->si183_sequencial";
    }


    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "redispi102023 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : " . $this->si183_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "redispi102023 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : " . $this->si183_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si183_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir($si183_sequencial = null, $dbwhere = null)
  {

    $sql = " delete from redispi102023
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($si183_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " si183_sequencial = $si183_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "redispi102023 nao Exclu�do. Exclus�o Abortada.\n";
      $this->erro_sql .= "Valores : " . $si183_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "redispi102023 nao Encontrado. Exclus�o n�o Efetuada.\n";
        $this->erro_sql .= "Valores : " . $si183_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $si183_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao do recordset
  function sql_record($sql)
  {
    $result = db_query($sql);
    if ($result == false) {
      $this->numrows = 0;
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "Erro ao selecionar os registros.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql = "Record Vazio na Tabela:redispi102023";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }

  // funcao do sql
  function sql_query($si183_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from redispi102023 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si183_sequencial != null) {
        $sql2 .= " where redispi102023.si183_sequencial = $si183_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  // funcao do sql
  function sql_query_file($si183_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from redispi102023 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si183_sequencial != null) {
        $sql2 .= " where redispi102023.si183_sequencial = $si183_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
