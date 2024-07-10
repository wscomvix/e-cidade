<?php
	/**
	 * Created by PhpStorm.
	 * User: contass
	 * Date: 24/01/19
	 * Time: 13:40
	 */

	//MODULO: sicom
	//CLASSE DA ENTIDADE conv312023
	class cl_conv312023
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
		var $si204_sequencial = 0;
		var $si204_tiporegistro = 0;
		var $si204_codreceita = null;
		var $si204_prevorcamentoassin = null;
		var $si204_nroconvenio = null;
		var $si204_dataassinatura_dia = null;
		var $si204_dataassinatura_mes = null;
		var $si204_dataassinatura_ano = null;
		var $si204_dataassinatura = null;
		var $si204_vlprevisaoconvenio = 0;
		var $si204_mes = 0;
		var $si204_instit = 0;
		// cria propriedade com as variaveis do arquivo
		var $campos = "
                 si204_sequencial = int8 = sequencial 
                 si204_tiporegistro = int8 = Tipo do  registro 
                 si204_codreceita = int8 = C�digo da receita 
                 si204_prevorcamentoassin = int8 = Previs�o no or�amento
                 si204_nroconvenio = varchar(30) = N�mero do conv�nio 
                 si204_dataassinatura = date = Data da assinatura
                 si204_vlprevisaoconvenio = float8 = Valor da previs�o 
                 si204_mes = int8 = M�s 
                 si204_instit = int8 = Institui��o 
                 ";

		//funcao construtor da classe
		function cl_conv312023()
		{
			//classes dos rotulos dos campos
			$this->rotulo = new rotulo("conv312023");
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
				$this->si204_sequencial = ($this->si204_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si204_sequencial"] : $this->si204_sequencial);
				$this->si204_tiporegistro = ($this->si204_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si204_tiporegistro"] : $this->si204_tiporegistro);
				$this->si204_codreceita = ($this->si204_codreceita == "" ? @$GLOBALS["HTTP_POST_VARS"]["si204_codreceita"] : $this->si204_codreceita);
				$this->si204_prevorcamentoassin = ($this->si204_prevorcamentoassin == "" ? @$GLOBALS["HTTP_POST_VARS"]["si204_prevorcamentoassin"] : $this->si204_prevorcamentoassin);
				$this->si204_nroconvenio = ($this->si204_nroconvenio == "" ? @$GLOBALS["HTTP_POST_VARS"]["si204_nroconvenio"] : $this->si204_nroconvenio);
				if($this->si204_dataassinatura == ""){
					$this->si204_dataassinatura_dia = ($this->si204_dataassinatura_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["si204_dataassinatura_dia"]:$this->si204_dataassinatura_dia);
					$this->si204_dataassinatura_mes = ($this->si204_dataassinatura_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si204_dataassinatura_mes"]:$this->si204_dataassinatura_mes);
					$this->si204_dataassinatura_ano = ($this->si204_dataassinatura_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["si204_dataassinatura_ano"]:$this->si204_dataassinatura_ano);
					if($this->si204_dataassinatura_dia != ""){
						$this->si204_dataassinatura = $this->si204_dataassinatura_ano."-".$this->si204_dataassinatura_mes."-".$this->si204_dataassinatura_dia;
					}
				}
				$this->si204_vlprevisaoconvenio = ($this->si204_vlprevisaoconvenio == "" ? @$GLOBALS["HTTP_POST_VARS"]["si204_vlprevisaoconvenio"] : $this->si204_vlprevisaoconvenio);
				$this->si204_mes = ($this->si204_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si204_mes"] : $this->si204_mes);
				$this->si204_instit = ($this->si204_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si204_instit"] : $this->si204_instit);
			} else {
				$this->si204_sequencial = ($this->si204_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si204_sequencial"] : $this->si204_sequencial);
			}
		}

		// funcao para inclusao
		function incluir($si204_sequencial)
		{
			$this->atualizacampos();
			if ($this->si204_tiporegistro == null) {
				$this->erro_sql = " Campo Tipo do  registro nao Informado.";
				$this->erro_campo = "si204_tiporegistro";
				$this->erro_banco = "";
				$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
				$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
				$this->erro_status = "0";

				return false;
			}
			if ($this->si204_codreceita == null) {
				$this->si204_codreceita = "null";
			}
			if ($this->si204_prevorcamentoassin == null) {
				$this->si204_prevorcamentoassin = "null";
			}
			if ($this->si204_nroconvenio == "") {
				$this->si204_nroconvenio = "null";
			}
			if ($this->si204_dataassinatura == "") {
				$this->si204_dataassinatura = "null";
			}
			if ($this->si204_vlprevisaoconvenio == null) {
				$this->si204_vlprevisaoconvenio = "0";
			}
			if ($this->si204_mes == null) {
				$this->erro_sql = " Campo M�s nao Informado.";
				$this->erro_campo = "si204_mes";
				$this->erro_banco = "";
				$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
				$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
				$this->erro_status = "0";

				return false;
			}
			if ($this->si204_instit == null) {
				$this->erro_sql = " Campo Institui��o nao Informado.";
				$this->erro_campo = "si204_instit";
				$this->erro_banco = "";
				$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
				$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
				$this->erro_status = "0";

				return false;
			}
			if ($si204_sequencial == "" || $si204_sequencial == null) {
				$result = db_query("select nextval('conv312023_si204_sequencial_seq')");
				if ($result == false) {
					$this->erro_banco = str_replace("", "", @pg_last_error());
					$this->erro_sql = "Verifique o cadastro da sequencia: conv312023_si204_sequencial_seq do campo: si204_sequencial";
					$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
					$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
					$this->erro_status = "0";

					return false;
				}
				$this->si204_sequencial = pg_result($result, 0, 0);
			} else {
				$result = db_query("select last_value from conv312023_si204_sequencial_seq");
				if (($result != false) && (pg_result($result, 0, 0) < $si204_sequencial)) {
					$this->erro_sql = " Campo si204_sequencial maior que �ltimo n�mero da sequencia.";
					$this->erro_banco = "Sequencia menor que este n�mero.";
					$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
					$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
					$this->erro_status = "0";

					return false;
				} else {
					$this->si204_sequencial = $si204_sequencial;
				}
			}
			if (($this->si204_sequencial == null) || ($this->si204_sequencial == "")) {
				$this->erro_sql = " Campo si204_sequencial nao declarado.";
				$this->erro_banco = "Chave Primaria zerada.";
				$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
				$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
				$this->erro_status = "0";

				return false;
			}
			$sql = "insert into conv312023(
                               si204_sequencial 
                               ,si204_tiporegistro
                               ,si204_codreceita 
                               ,si204_prevorcamentoassin
                               ,si204_nroconvenio 
                               ,si204_dataassinatura 
                               ,si204_vlprevisaoconvenio 
                               ,si204_mes 
                               ,si204_instit 
                       )
                values (
                                $this->si204_sequencial 
                               ,$this->si204_tiporegistro
                               ,$this->si204_codreceita 
                               ,'$this->si204_prevorcamentoassin'
,$this->si204_nroconvenio 
                               ,$this->si204_dataassinatura 
                               ,$this->si204_vlprevisaoconvenio 
                               ,$this->si204_mes 
                               ,$this->si204_instit 
                      )";
			$result = db_query($sql);
			if ($result == false) {
				$this->erro_banco = str_replace("", "", @pg_last_error());
				if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
					$this->erro_sql = "conv312023 ($this->si204_sequencial) nao Inclu�do. Inclusao Abortada.";
					$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
					$this->erro_banco = "conv312023 j� Cadastrado";
					$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
				} else {
					$this->erro_sql = "conv312023 ($this->si204_sequencial) nao Inclu�do. Inclusao Abortada.";
					$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
					$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
				}
				$this->erro_status = "0";
				$this->numrows_incluir = 0;

				return false;
			}
			$this->erro_banco = "";
			$this->erro_sql = "Inclusao efetuada com Sucesso\n";
			$this->erro_sql .= "Valores : " . $this->si204_sequencial;
			$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
			$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
			$this->erro_status = "1";
			$this->numrows_incluir = pg_affected_rows($result);
			$resaco = $this->sql_record($this->sql_query_file($this->si204_sequencial));
//    if (($resaco != false) || ($this->numrows != 0)) {
//      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//      $acount = pg_result($resac, 0, 0);
//      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
//      $resac = db_query("insert into db_acountkey values($acount,2010533,'$this->si204_sequencial','I')");
//      $resac = db_query("insert into db_acount values($acount,2010323,2010533,'','" . AddSlashes(pg_result($resaco, 0, 'si204_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010323,2010534,'','" . AddSlashes(pg_result($resaco, 0, 'si204_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010323,2010535,'','" . AddSlashes(pg_result($resaco, 0, 'si204_prevorcamentoassin')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010323,2010536,'','" . AddSlashes(pg_result($resaco, 0, 'si204_nroconvenio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010323,2010537,'','" . AddSlashes(pg_result($resaco, 0, 'si204_dtassinaturaconvoriginal')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010323,2010538,'','" . AddSlashes(pg_result($resaco, 0, 'si204_nroseqtermoaditivo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010323,2010539,'','" . AddSlashes(pg_result($resaco, 0, 'si204_dscalteracao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010323,2010540,'','" . AddSlashes(pg_result($resaco, 0, 'si204_dtassinaturatermoaditivo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010323,2010541,'','" . AddSlashes(pg_result($resaco, 0, 'si204_datafinalvigencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010323,2010542,'','" . AddSlashes(pg_result($resaco, 0, 'si204_valoratualizadoconvenio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010323,2010543,'','" . AddSlashes(pg_result($resaco, 0, 'si204_valoratualizadocontrapartida')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010323,2010544,'','" . AddSlashes(pg_result($resaco, 0, 'si204_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010323,2011606,'','" . AddSlashes(pg_result($resaco, 0, 'si204_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//    }

			return true;
		}

		// funcao para alteracao
		function alterar($si204_sequencial = null)
		{
			$this->atualizacampos();
			$sql = " update conv312023 set ";
			$virgula = "";
			if (trim($this->si204_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si204_sequencial"])) {
				if (trim($this->si204_sequencial) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si204_sequencial"])) {
					$this->si204_sequencial = "0";
				}
				$sql .= $virgula . " si204_sequencial = $this->si204_sequencial ";
				$virgula = ",";
			}
			if (trim($this->si204_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si204_tiporegistro"])) {
				$sql .= $virgula . " si204_tiporegistro = $this->si204_tiporegistro ";
				$virgula = ",";
				if (trim($this->si204_tiporegistro) == null) {
					$this->erro_sql = " Campo Tipo do  registro nao Informado.";
					$this->erro_campo = "si204_tiporegistro";
					$this->erro_banco = "";
					$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
					$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
					$this->erro_status = "0";

					return false;
				}
			}
			if (trim($this->si204_codreceita) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si204_codreceita"])) {
				$sql .= $virgula . " si204_codreceita = '$this->si204_codreceita' ";
				$virgula = ",";
			}
			if (trim($this->si204_prevorcamentoassin) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si204_prevorcamentoassin"])) {
				$sql .= $virgula . " si204_prevorcamentoassin = '$this->si204_prevorcamentoassin' ";
				$virgula = ",";
			}
			if (trim($this->si204_nroconvenio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["$this->si204_nroconvenio"])) {
				$sql .= $virgula . " $this->si204_nroconvenio = '$this->si204_nroconvenio' ";
				$virgula = ",";
			}
			if(trim($this->si204_dataassinatura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si204_dataassinatura_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["si204_dataassinatura_dia"] !="") ){
				$sql  .= $virgula." si204_dataassinatura = '$this->si204_dataassinatura' ";
				$virgula = ",";
			}     else{
				if(isset($GLOBALS["HTTP_POST_VARS"]["si204_dataassinatura_dia"])){
					$sql  .= $virgula." si204_dataassinatura = null ";
					$virgula = ",";
				}
			}
			if (trim($this->si204_vlprevisaoconvenio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si204_vlprevisaoconvenio"])) {
				$sql .= $virgula . " si204_vlprevisaoconvenio = '$this->si204_vlprevisaoconvenio' ";
				$virgula = ",";
			}

			if (trim($this->si204_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si204_mes"])) {
				$sql .= $virgula . " si204_mes = $this->si204_mes ";
				$virgula = ",";
				if (trim($this->si204_mes) == null) {
					$this->erro_sql = " Campo M�s nao Informado.";
					$this->erro_campo = "si204_mes";
					$this->erro_banco = "";
					$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
					$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
					$this->erro_status = "0";
					return false;
				}
			}
			if (trim($this->si204_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si204_instit"])) {
				$sql .= $virgula . " si204_instit = $this->si204_instit ";
				$virgula = ",";
				if (trim($this->si204_instit) == null) {
					$this->erro_sql = " Campo Institui��o nao Informado.";
					$this->erro_campo = "si204_instit";
					$this->erro_banco = "";
					$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
					$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
					$this->erro_status = "0";

					return false;
				}
			}
			$sql .= " where ";
			if ($si204_sequencial != null) {
				$sql .= " si204_sequencial = $this->si204_sequencial";
			}
			$resaco = $this->sql_record($this->sql_query_file($this->si204_sequencial));
//    if ($this->numrows > 0) {
//      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
//        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//        $acount = pg_result($resac, 0, 0);
//        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
//        $resac = db_query("insert into db_acountkey values($acount,2010533,'$this->si204_sequencial','A')");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si204_sequencial"]) || $this->si204_sequencial != "")
//          $resac = db_query("insert into db_acount values($acount,2010323,2010533,'" . AddSlashes(pg_result($resaco, $conresaco, 'si204_sequencial')) . "','$this->si204_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si204_tiporegistro"]) || $this->si204_tiporegistro != "")
//          $resac = db_query("insert into db_acount values($acount,2010323,2010534,'" . AddSlashes(pg_result($resaco, $conresaco, 'si204_tiporegistro')) . "','$this->si204_tiporegistro'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si204_prevorcamentoassin"]) || $this->si204_prevorcamentoassin != "")
//          $resac = db_query("insert into db_acount values($acount,2010323,2010535,'" . AddSlashes(pg_result($resaco, $conresaco, 'si204_prevorcamentoassin')) . "','$this->si204_prevorcamentoassin'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si204_nroconvenio"]) || $this->si204_nroconvenio != "")
//          $resac = db_query("insert into db_acount values($acount,2010323,2010536,'" . AddSlashes(pg_result($resaco, $conresaco, 'si204_nroconvenio')) . "','$this->si204_nroconvenio'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si204_dtassinaturaconvoriginal"]) || $this->si204_dtassinaturaconvoriginal != "")
//          $resac = db_query("insert into db_acount values($acount,2010323,2010537,'" . AddSlashes(pg_result($resaco, $conresaco, 'si204_dtassinaturaconvoriginal')) . "','$this->si204_dtassinaturaconvoriginal'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si204_nroseqtermoaditivo"]) || $this->si204_nroseqtermoaditivo != "")
//          $resac = db_query("insert into db_acount values($acount,2010323,2010538,'" . AddSlashes(pg_result($resaco, $conresaco, 'si204_nroseqtermoaditivo')) . "','$this->si204_nroseqtermoaditivo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si204_dscalteracao"]) || $this->si204_dscalteracao != "")
//          $resac = db_query("insert into db_acount values($acount,2010323,2010539,'" . AddSlashes(pg_result($resaco, $conresaco, 'si204_dscalteracao')) . "','$this->si204_dscalteracao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si204_dtassinaturatermoaditivo"]) || $this->si204_dtassinaturatermoaditivo != "")
//          $resac = db_query("insert into db_acount values($acount,2010323,2010540,'" . AddSlashes(pg_result($resaco, $conresaco, 'si204_dtassinaturatermoaditivo')) . "','$this->si204_dtassinaturatermoaditivo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si204_datafinalvigencia"]) || $this->si204_datafinalvigencia != "")
//          $resac = db_query("insert into db_acount values($acount,2010323,2010541,'" . AddSlashes(pg_result($resaco, $conresaco, 'si204_datafinalvigencia')) . "','$this->si204_datafinalvigencia'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si204_valoratualizadoconvenio"]) || $this->si204_valoratualizadoconvenio != "")
//          $resac = db_query("insert into db_acount values($acount,2010323,2010542,'" . AddSlashes(pg_result($resaco, $conresaco, 'si204_valoratualizadoconvenio')) . "','$this->si204_valoratualizadoconvenio'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si204_valoratualizadocontrapartida"]) || $this->si204_valoratualizadocontrapartida != "")
//          $resac = db_query("insert into db_acount values($acount,2010323,2010543,'" . AddSlashes(pg_result($resaco, $conresaco, 'si204_valoratualizadocontrapartida')) . "','$this->si204_valoratualizadocontrapartida'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si204_mes"]) || $this->si204_mes != "")
//          $resac = db_query("insert into db_acount values($acount,2010323,2010544,'" . AddSlashes(pg_result($resaco, $conresaco, 'si204_mes')) . "','$this->si204_mes'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si204_instit"]) || $this->si204_instit != "")
//          $resac = db_query("insert into db_acount values($acount,2010323,2011606,'" . AddSlashes(pg_result($resaco, $conresaco, 'si204_instit')) . "','$this->si204_instit'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      }
//    }
			$result = db_query($sql);
			if ($result == false) {
				$this->erro_banco = str_replace("", "", @pg_last_error());
				$this->erro_sql = "conv312023 nao Alterado. Alteracao Abortada.\n";
				$this->erro_sql .= "Valores : " . $this->si204_sequencial;
				$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
				$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
				$this->erro_status = "0";
				$this->numrows_alterar = 0;

				return false;
			} else {
				if (pg_affected_rows($result) == 0) {
					$this->erro_banco = "";
					$this->erro_sql = "conv312023 nao foi Alterado. Alteracao Executada.\n";
					$this->erro_sql .= "Valores : " . $this->si204_sequencial;
					$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
					$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
					$this->erro_status = "1";
					$this->numrows_alterar = 0;

					return true;
				} else {
					$this->erro_banco = "";
					$this->erro_sql = "Altera��o efetuada com Sucesso\n";
					$this->erro_sql .= "Valores : " . $this->si204_sequencial;
					$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
					$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
					$this->erro_status = "1";
					$this->numrows_alterar = pg_affected_rows($result);

					return true;
				}
			}
		}

		// funcao para exclusao
		function excluir($si204_sequencial = null, $dbwhere = null)
		{
			if ($dbwhere == null || $dbwhere == "") {
				$resaco = $this->sql_record($this->sql_query_file($si204_sequencial));
			} else {
				$resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
			}
//    if (($resaco != false) || ($this->numrows != 0)) {
//      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
//        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//        $acount = pg_result($resac, 0, 0);
//        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
//        $resac = db_query("insert into db_acountkey values($acount,2010533,'$si204_sequencial','E')");
//        $resac = db_query("insert into db_acount values($acount,2010323,2010533,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si204_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010323,2010534,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si204_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010323,2010535,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si204_prevorcamentoassin')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010323,2010536,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si204_nroconvenio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010323,2010537,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si204_dtassinaturaconvoriginal')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010323,2010538,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si204_nroseqtermoaditivo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010323,2010539,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si204_dscalteracao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010323,2010540,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si204_dtassinaturatermoaditivo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010323,2010541,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si204_datafinalvigencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010323,2010542,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si204_valoratualizadoconvenio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010323,2010543,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si204_valoratualizadocontrapartida')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010323,2010544,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si204_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010323,2011606,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si204_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      }
//    }
			$sql = " delete from conv312023
                    where ";
			$sql2 = "";
			if ($dbwhere == null || $dbwhere == "") {
				if ($si204_sequencial != "") {
					if ($sql2 != "") {
						$sql2 .= " and ";
					}
					$sql2 .= " si204_sequencial = $si204_sequencial ";
				}
			} else {
				$sql2 = $dbwhere;
			}
			$result = db_query($sql . $sql2);
			if ($result == false) {
				$this->erro_banco = str_replace("", "", @pg_last_error());
				$this->erro_sql = "conv312023 nao Exclu�do. Exclus�o Abortada.\n";
				$this->erro_sql .= "Valores : " . $si204_sequencial;
				$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
				$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
				$this->erro_status = "0";
				$this->numrows_excluir = 0;

				return false;
			} else {
				if (pg_affected_rows($result) == 0) {
					$this->erro_banco = "";
					$this->erro_sql = "conv312023 nao Encontrado. Exclus�o n�o Efetuada.\n";
					$this->erro_sql .= "Valores : " . $si204_sequencial;
					$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
					$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
					$this->erro_status = "1";
					$this->numrows_excluir = 0;

					return true;
				} else {
					$this->erro_banco = "";
					$this->erro_sql = "Exclus�o efetuada com Sucesso\n";
					$this->erro_sql .= "Valores : " . $si204_sequencial;
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
				$this->erro_sql = "Record Vazio na Tabela:conv312023";
				$this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
				$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
				$this->erro_status = "0";

				return false;
			}

			return $result;
		}

		// funcao do sql
		function sql_query($si204_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
			$sql .= " from conv312023 ";
			$sql2 = "";
			if ($dbwhere == "") {
				if ($si204_sequencial != null) {
					$sql2 .= " where conv312023.si204_sequencial = $si204_sequencial ";
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
		function sql_query_file($si204_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
			$sql .= " from conv312023 ";
			$sql2 = "";
			if ($dbwhere == "") {
				if ($si204_sequencial != null) {
					$sql2 .= " where conv312023.si204_sequencial = $si204_sequencial ";
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
