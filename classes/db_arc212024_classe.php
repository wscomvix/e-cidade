<?
//MODULO: sicom
//CLASSE DA ENTIDADE arc212024
class cl_arc212024
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
  var $si32_sequencial = 0;
  var $si32_tiporegistro = 0;
  var $si32_codestorno = 0;
  var $si32_codfonteestornada = 0;
  var $si32_codigocontroleorcamentario = null;
  var $si32_tipodocumento = 0;
  var $si32_nrodocumento = "";
  var $si32_nroconvenio = "";
  var $si32_dataassinatura_dia = null;
  var $si32_dataassinatura_mes = null;
  var $si32_dataassinatura_ano = null;
  var $si32_nrocontratoop = null;
  var $si32_dataassinaturacontratoop = null;
  var $si32_dataassinaturacontratoop_dia = null;
  var $si32_dataassinaturacontratoop_mes = null;
  var $si32_dataassinaturacontratoop_ano = null;
  var $si32_dataassinatura = null;
  var $si32_vlestornadofonte = 0;
  var $si32_reg20 = 0;
  var $si32_instit = 0;
  var $si32_mes = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si32_sequencial = int8 = sequencial
                 si32_tiporegistro = int8 = Tipo do  registro
                 si32_codestorno = int8 = C�digo identificador
                 si32_codfonteestornada = int8 = C�digo da fonte
                 si32_codigocontroleorcamentario = varchar = C�digo da fonte
                 si32_tipodocumento = int8 = Tipo do documento
                 si32_nrodocumento = varchar(14) = N�mero do documento
                 si32_nroconvenio = varchar(30) = N�mero do conv�nio
                 si32_dataassinatura = date = Data da assinatura
                 si32_nrocontratoop = varchar(30) = N�mero do contrato
                 si32_dataassinaturacontratoop = data = Data Assinatura do Contrato
                 si32_vlestornadofonte = float8 = Valor estornado
                 si32_reg20 = int8 = reg20
                 si32_instit = int8 = Institui��o
                 si31_mes = int8 = M�s
                 ";

  //funcao construtor da classe
  function cl_arc212024()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("arc212024");
    $this->pagina_retorno = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }

  //funcao erro
  function erro($mostra, $retorna)
  {
    if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {
      echo "<script>alert(\"" . $this->erro_msg . "\");</script>";
      if ($retorna == true) {
        echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
      }
    }
  }

  // funcao para atualizar campos
  function atualizacampos($exclusao = false)
  {
    if ($exclusao == false) {
      $this->si32_sequencial = ($this->si32_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_sequencial"] : $this->si32_sequencial);
      $this->si32_tiporegistro = ($this->si32_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_tiporegistro"] : $this->si32_tiporegistro);
      $this->si32_codestorno = ($this->si32_codestorno == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_codestorno"] : $this->si32_codestorno);
      $this->si32_codfonteestornada = ($this->si32_codfonteestornada == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_codfonteestornada"] : $this->si32_codfonteestornada);
      $this->si32_codigocontroleorcamentario = ($this->si32_codigocontroleorcamentario == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_codigocontroleorcamentario"] : $this->si32_codigocontroleorcamentario);
  	  $this->si32_tipodocumento = ($this->si32_tipodocumento == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_tipodocumento"] : $this->si32_tipodocumento);
  	  $this->si32_nrodocumento = ($this->si32_nrodocumento == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_nrodocumento"] : $this->si32_nrodocumento);
  	  $this->si32_nroconvenio = ($this->si32_nroconvenio == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_nroconvenio"] : $this->si32_nroconvenio);
   	  if($this->si32_dataassinatura == ""){
  	  	$this->si32_dataassinatura_dia = ($this->si32_dataassinatura_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_dataassinatura_dia"] : $this->si32_dataassinatura_dia);
    		$this->si32_dataassinatura_mes = ($this->si32_dataassinatura_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_dataassinatura_mes"] : $this->si32_dataassinatura_mes);
    		$this->si32_dataassinatura_ano = ($this->si32_dataassinatura_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_dataassinatura_ano"] : $this->si32_dataassinatura_ano);
    		if ($this->si32_dataassinatura_dia != "") {
    			$this->si32_dataassinatura = $this->si32_dataassinatura_ano . "-" . $this->si32_dataassinatura_mes . "-" . $this->si32_dataassinatura_dia;
  		  }
  	  }
        $this->si32_nrocontratoop = ($this->si32_nrocontratoop == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_nrocontratoop"] : $this->si32_nrocontratoop);
        if($this->si32_dataassinaturacontratoop == ""){
          $this->si32_dataassinaturacontratoop_dia = ($this->si32_dataassinaturacontratoop_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["si32_dataassinaturacontratoop_dia"]:$this->si32_dataassinaturacontratoop_dia);
          $this->si32_dataassinaturacontratoop_mes = ($this->si32_dataassinaturacontratoop_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si32_dataassinaturacontratoop_mes"]:$this->si32_dataassinaturacontratoop_mes);
          $this->si32_dataassinaturacontratoop_ano = ($this->si32_dataassinaturacontratoop_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["si32_dataassinaturacontratoop_ano"]:$this->si32_dataassinaturacontratoop_ano);
          if($this->si32_dataassinaturacontratoop_dia != ""){
            $this->si32_dataassinaturacontratoop = $this->si32_dataassinaturacontratoop_ano."-".$this->si32_dataassinaturacontratoop_mes."-".$this->si32_dataassinaturacontratoop_dia;
          }
        }
        $this->si32_vlestornadofonte = ($this->si32_vlestornadofonte == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_vlestornadofonte"] : $this->si32_vlestornadofonte);
        $this->si32_reg20 = ($this->si32_reg20 == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_reg20"] : $this->si32_reg20);
        $this->si32_mes = ($this->si32_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_mes"] : $this->si32_mes);
        $this->si32_instit = ($this->si32_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_instit"] : $this->si32_instit);
      } else {
        $this->si32_sequencial = ($this->si32_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si32_sequencial"] : $this->si32_sequencial);
      }

  }

  // funcao para inclusao
  function incluir($si32_sequencial)
  {
    $this->atualizacampos();

    if ($this->si32_tiporegistro == null) {
      $this->erro_sql = " Campo Tipo do  registro nao Informado.";
      $this->erro_campo = "si32_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si32_codestorno == null) {
    	$this->erro_sql = " Campo C�digo Identificador do Estorno da Receita n�o Informado.";
      $this->erro_campo = "si32_codestorno";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si32_codfonteestornada == null) {
    	$this->erro_sql = " Campo C�digo da Fonte de Recursos Estornada n�o Informado.";
      $this->erro_campo = "si32_codfonteestornada";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }

    if ($this->si32_codigocontroleorcamentario == null) {
    	$this->si32_codigocontroleorcamentario = "0000";
    }

  	if (!$this->si32_tipodocumento) {
  		$this->si32_tipodocumento = 0;
  	}


    if ($this->si32_vlestornadofonte == null) {
      $this->erro_sql = " Campo Valor Estornado para a Fonte de Recursos n�o Informado.";
      $this->erro_campo = "si32_vlestornadofonte";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
    }
    // if ($this->si32_reg20 == null) {
    //   $this->si32_reg20 = "0";
    // }
    if ($this->si32_instit == null) {
      $this->erro_sql = " Campo Institui��o nao Informado.";
      $this->erro_campo = "si32_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($si32_sequencial == "" || $si32_sequencial == null) {
      $result = db_query("select nextval('arc212024_si32_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: arc212024_si32_sequencial_seq do campo: si32_sequencial";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
      $this->si32_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from arc212024_si32_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si32_sequencial)) {
        $this->erro_sql = " Campo si32_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      } else {
        $this->si32_sequencial = $si32_sequencial;
      }
    }
    if (($this->si32_sequencial == null) || ($this->si32_sequencial == "")) {
      $this->erro_sql = " Campo si32_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $sql = "insert into arc212024(
                                       si32_sequencial
                                      ,si32_tiporegistro
                                      ,si32_codestorno
                                      ,si32_codfonteestornada
                                      ,si32_codigocontroleorcamentario
                                      ,si32_tipodocumento
                                      ,si32_nrodocumento
                                      ,si32_nroconvenio
                                      ,si32_dataassinatura
                                      ,si32_nrocontratoop
                                      ,si32_dataassinaturacontratoop
                                      ,si32_vlestornadofonte
                                      ,si32_reg20
                                      ,si32_mes
                                      ,si32_instit
                       )
                values (
                                $this->si32_sequencial
                               ,$this->si32_tiporegistro
                               ,$this->si32_codestorno
                               ,$this->si32_codfonteestornada
                               ,'$this->si32_codigocontroleorcamentario'
                               ,$this->si32_tipodocumento
                               ,'$this->si32_nrodocumento'
                               ,'$this->si32_nroconvenio'
                               ," . ($this->si32_dataassinatura == "null" || $this->si32_dataassinatura == "" ? "null" : "'" . $this->si32_dataassinatura . "'") . "
                               ,'$this->si32_nrocontratoop'
                               ,".($this->si32_dataassinaturacontratoop == "null" || $this->si32_dataassinaturacontratoop == ""?"null":"'".$this->si32_dataassinaturacontratoop."'")."
                               ,$this->si32_vlestornadofonte
                               ,$this->si32_reg20
                               ,$this->si32_mes
                               ,$this->si32_instit
                      )";



    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "arc212024 ($this->si32_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_banco = "arc212024 j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      } else {
        $this->erro_sql = "arc212024 ($this->si32_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;

      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : " . $this->si32_sequencial;
    $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->si32_sequencial));
//    if (($resaco != false) || ($this->numrows != 0)) {
//      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//      $acount = pg_result($resac, 0, 0);
//      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
//      $resac = db_query("insert into db_acountkey values($acount,2009724,'$this->si32_sequencial','I')");
//      $resac = db_query("insert into db_acount values($acount,2010260,2009724,'','" . AddSlashes(pg_result($resaco, 0, 'si32_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010260,2009725,'','" . AddSlashes(pg_result($resaco, 0, 'si32_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010260,2009726,'','" . AddSlashes(pg_result($resaco, 0, 'si32_codestorno')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010260,2009727,'','" . AddSlashes(pg_result($resaco, 0, 'si32_codfonteestornada')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010260,2009728,'','" . AddSlashes(pg_result($resaco, 0, 'si32_vlestornadofonte')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010260,2009729,'','" . AddSlashes(pg_result($resaco, 0, 'si32_reg20')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010260,2011547,'','" . AddSlashes(pg_result($resaco, 0, 'si32_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//    }

    return true;
  }

  // funcao para alteracao
  function alterar($si32_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update arc212024 set ";
    $virgula = "";
    if (trim($this->si32_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si32_sequencial"])) {
      if (trim($this->si32_sequencial) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si32_sequencial"])) {
        $this->si32_sequencial = "0";
      }
      $sql .= $virgula . " si32_sequencial = $this->si32_sequencial ";
      $virgula = ",";
    }
    if (trim($this->si32_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si32_tiporegistro"])) {
      $sql .= $virgula . " si32_tiporegistro = $this->si32_tiporegistro ";
      $virgula = ",";
      if (trim($this->si32_tiporegistro) == null) {
        $this->erro_sql = " Campo Tipo do  registro nao Informado.";
        $this->erro_campo = "si32_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si32_codestorno) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si32_codestorno"])) {
      if (trim($this->si32_codestorno) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si32_codestorno"])) {
        $this->si32_codestorno = "0";
      }
      $sql .= $virgula . " si32_codestorno = $this->si32_codestorno ";
      $virgula = ",";
    }
    if (trim($this->si32_codfonteestornada) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si32_codfonteestornada"])) {
      if (trim($this->si32_codfonteestornada) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si32_codfonteestornada"])) {
        $this->si32_codfonteestornada = "0";
      }
      $sql .= $virgula . " si32_codfonteestornada = $this->si32_codfonteestornada ";
      $virgula = ",";
    }
    if (trim($this->si32_codigocontroleorcamentario) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si32_codigocontroleorcamentario"])) {
      if (trim($this->si32_codigocontroleorcamentario) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si32_codigocontroleorcamentario"])) {
        $this->si32_codigocontroleorcamentario = "0";
      }
      $sql .= $virgula . " si32_codigocontroleorcamentario = $this->si32_codigocontroleorcamentario ";
      $virgula = ",";
    }
	  if (trim($this->si32_tipodocumento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si32_tipodocumento"])) {
		  if (trim($this->si32_tipodocumento) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si32_tipodocumento"])) {
			  $this->si32_tipodocumento = "";
		  }
		  $sql .= $virgula . " si32_tipodocumento = $this->si32_tipodocumento ";
		  $virgula = ",";
	  }

	  if (trim($this->si32_nrodocumento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si32_nrodocumento"])) {
		  if (trim($this->si32_nrodocumento) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si32_nrodocumento"])) {
			  $this->si32_nrodocumento = "0";
		  }
		  $sql .= $virgula . " si32_nrodocumento = $this->si32_nrodocumento ";
		  $virgula = ",";
	  }

	  if (trim($this->si32_nroconvenio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si32_nroconvenio"])) {
		  if (trim($this->si32_nroconvenio) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si32_nroconvenio"])) {
			  $this->si32_nroconvenio = "0";
		  }
		  $sql .= $virgula . " si32_nroconvenio = $this->si32_nroconvenio ";
		  $virgula = ",";
	  }


	  if (trim($this->si32_dataassinatura) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si32_dataassinatura_dia"]) && ($GLOBALS["HTTP_POST_VARS"]["si32_dataassinatura_dia"] != "")) {
		  $sql .= $virgula . " si32_dataassinatura = '$this->si32_dataassinatura' ";
		  $virgula = ",";
	  }else {
		  if (isset($GLOBALS["HTTP_POST_VARS"]["si32_dataassinatura"])) {
			  $sql .= $virgula . " si32_dataassinatura = null ";
			  $virgula = ",";
		  }
	  }
    if (trim($this->si32_vlestornadofonte) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si32_vlestornadofonte"])) {
      if (trim($this->si32_vlestornadofonte) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si32_vlestornadofonte"])) {
        $this->si32_vlestornadofonte = "0";
      }
      $sql .= $virgula . " si32_vlestornadofonte = $this->si32_vlestornadofonte ";
      $virgula = ",";
    }
    if (trim($this->si32_reg20) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si32_reg20"])) {
      if (trim($this->si32_reg20) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si32_reg20"])) {
        $this->si32_reg20 = "0";
      }
      $sql .= $virgula . " si32_reg20 = $this->si32_reg20 ";
      $virgula = ",";
    }
    // if (trim($this->si32_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si32_mes"])) {
    //   if (trim($this->si32_mes) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si32_mes"])) {
    //     $this->si32_mes = "0";
    //   }
    //   $sql .= $virgula . " si32_mes = $this->si32_mes ";
    //   $virgula = ",";
    // },
    if (trim($this->si32_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si32_instit"])) {
      $sql .= $virgula . " si32_instit = $this->si32_instit ";
      $virgula = ",";
      if (trim($this->si32_instit) == null) {
        $this->erro_sql = " Campo Institui��o nao Informado.";
        $this->erro_campo = "si32_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    $sql .= " where ";
    if ($si32_sequencial != null) {
      $sql .= " si32_sequencial = $this->si32_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->si32_sequencial));
//    if ($this->numrows > 0) {
//      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
//        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//        $acount = pg_result($resac, 0, 0);
//        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
//        $resac = db_query("insert into db_acountkey values($acount,2009724,'$this->si32_sequencial','A')");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si32_sequencial"]) || $this->si32_sequencial != "")
//          $resac = db_query("insert into db_acount values($acount,2010260,2009724,'" . AddSlashes(pg_result($resaco, $conresaco, 'si32_sequencial')) . "','$this->si32_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si32_tiporegistro"]) || $this->si32_tiporegistro != "")
//          $resac = db_query("insert into db_acount values($acount,2010260,2009725,'" . AddSlashes(pg_result($resaco, $conresaco, 'si32_tiporegistro')) . "','$this->si32_tiporegistro'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si32_codestorno"]) || $this->si32_codestorno != "")
//          $resac = db_query("insert into db_acount values($acount,2010260,2009726,'" . AddSlashes(pg_result($resaco, $conresaco, 'si32_codestorno')) . "','$this->si32_codestorno'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si32_codfonteestornada"]) || $this->si32_codfonteestornada != "")
//          $resac = db_query("insert into db_acount values($acount,2010260,2009727,'" . AddSlashes(pg_result($resaco, $conresaco, 'si32_codfonteestornada')) . "','$this->si32_codfonteestornada'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si32_vlestornadofonte"]) || $this->si32_vlestornadofonte != "")
//          $resac = db_query("insert into db_acount values($acount,2010260,2009728,'" . AddSlashes(pg_result($resaco, $conresaco, 'si32_vlestornadofonte')) . "','$this->si32_vlestornadofonte'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si32_reg20"]) || $this->si32_reg20 != "")
//          $resac = db_query("insert into db_acount values($acount,2010260,2009729,'" . AddSlashes(pg_result($resaco, $conresaco, 'si32_reg20')) . "','$this->si32_reg20'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si32_instit"]) || $this->si32_instit != "")
//          $resac = db_query("insert into db_acount values($acount,2010260,2011547,'" . AddSlashes(pg_result($resaco, $conresaco, 'si32_instit')) . "','$this->si32_instit'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      }
//    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("", "", @pg_last_error());
      $this->erro_sql = "arc212024 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : " . $this->si32_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "arc212024 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : " . $this->si32_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si32_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);

        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir($si32_sequencial = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($si32_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
//    if (($resaco != false) || ($this->numrows != 0)) {
//      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
//        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//        $acount = pg_result($resac, 0, 0);
//        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
//        $resac = db_query("insert into db_acountkey values($acount,2009724,'$si32_sequencial','E')");
//        $resac = db_query("insert into db_acount values($acount,2010260,2009724,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si32_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010260,2009725,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si32_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010260,2009726,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si32_codestorno')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010260,2009727,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si32_codfonteestornada')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010260,2009728,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si32_vlestornadofonte')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010260,2009729,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si32_reg20')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010260,2011547,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si32_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      }
//    }
    $sql = " delete from arc212024
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($si32_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " si32_sequencial = $si32_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "arc212024 nao Exclu�do. Exclus�o Abortada.\n";
      $this->erro_sql .= "Valores : " . $si32_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "arc212024 nao Encontrado. Exclus�o n�o Efetuada.\n";
        $this->erro_sql .= "Valores : " . $si32_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $si32_sequencial;
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
      $this->erro_sql = "Record Vazio na Tabela:arc212024";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }

    return $result;
  }

  // funcao do sql
  function sql_query($si32_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from arc212024 ";
    $sql .= "      left  join arc202020  on  arc202020.si31_sequencial = arc212024.si32_reg20";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si32_sequencial != null) {
        $sql2 .= " where arc212024.si32_sequencial = $si32_sequencial ";
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
  function sql_query_file($si32_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from arc212024 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si32_sequencial != null) {
        $sql2 .= " where arc212024.si32_sequencial = $si32_sequencial ";
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

?>
