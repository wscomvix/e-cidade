<?
//MODULO: sicom
//CLASSE DA ENTIDADE adesaoregprecos
class cl_adesaoregprecos
{
  // cria variaveis de erro
  var $rotulo     = null;
  var $query_sql  = null;
  var $numrows    = 0;
  var $numrows_incluir = 0;
  var $numrows_alterar = 0;
  var $numrows_excluir = 0;
  var $erro_status = null;
  var $erro_sql   = null;
  var $erro_banco = null;
  var $erro_msg   = null;
  var $erro_campo = null;
  var $pagina_retorno = null;
  // cria variaveis do arquivo
  var $si06_sequencial = 0;
  var $si06_orgaogerenciador = 0;
  var $si06_modalidade = null;
  var $si06_anoproc = 0;
  var $si06_numeroprc = 0;
  var $si06_numlicitacao = null;
  var $si06_dataadesao_dia = null;
  var $si06_dataadesao_mes = null;
  var $si06_dataadesao_ano = null;
  var $si06_dataadesao = null;
  var $si06_dataata_dia = null;
  var $si06_dataata_mes = null;
  var $si06_dataata_ano = null;
  var $si06_dataata = null;
  var $si06_datavalidade_dia = null;
  var $si06_datavalidade_mes = null;
  var $si06_datavalidade_ano = null;
  var $si06_datavalidade = null;
  var $si06_publicacaoaviso_dia = null;
  var $si06_publicacaoaviso_mes = null;
  var $si06_publicacaoaviso_ano = null;
  var $si06_publicacaoaviso = null;
  var $si06_objetoadesao = null;
  var $si06_orgarparticipante = 0;
  var $si06_cgm = 0;
  var $si06_descontotabela = null;
  var $si06_numeroadm = 0;
  var $si06_dataabertura_dia = null;
  var $si06_dataabertura_mes = null;
  var $si06_dataabertura_ano = null;
  var $si06_dataabertura = null;
  var $si06_processocompra = 0;
  var $si06_fornecedor = 0;
  var $si06_processoporlote = 0;
  var $si06_edital = null;
  var $si06_cadinicial = null;
  var $si06_exercicioedital = null;
  var $si06_anocadastro = null;
  var $si06_leidalicitacao = null;
  var $si06_anomodadm = null;
  var $si06_nummodadm = null;
  var $si06_departamento = null;
  var $si06_codunidadesubant = null;
  var $si06_regimecontratacao = null;
  var $si06_criterioadjudicacao = null;

  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si06_sequencial = int8 = Sequencial
                 si06_orgaogerenciador = int8 = Org�o Gerenciador
                 si06_modalidade = int8 = Modalidade
                 si06_numeroprc = int8 = N�mero do PRC
                 si06_numlicitacao = int8 = N�m. Licita��o
                 si06_dataadesao = date = Data de Ades�o
                 si06_dataata = date = Data da Ata
                 si06_datavalidade = date = Data Validade
                 si06_publicacaoaviso = date = Publica��o Aviso
                 si06_objetoadesao = text = Objeto da Ades�o
                 si06_orgarparticipante = int8 = Org�o integra a ata de Registro de Pre�o
                 si06_cgm = int8 = Respons�vel pela Aprova��o
                 si06_descontotabela = int8 = Desconto em Tabela
                 si06_numeroadm = int8 = N�mero do ADM
                 si06_dataabertura = date = Data da Abertura
                 si06_processocompra = int8 = Processo de compra
                 si06_fornecedor = int8 = Fornecedor
                 si06_processoporlote = int8 = Processo por Lote
                 si06_edital = int8 = N�mero do edital
                 si06_exercicioedital = int8 = Exerc�cio do edital
                 si06_cadinicial = int4 = Cadastro Inicial
                 si06_anocadastro = int4 = Ano cadastro
                 si06_leidalicitacao = int4 = Lei de licitacao
                 si06_anomodadm = int4 = Ano do Processo
                 si06_nummodadm = int8 = Numero Modalidade
                 si06_departamento = int8 = codigo do departamento responsavel
                 si06_regimecontratacao = int4 = Regime de contrata��o
                 si06_criterioadjudicacao = int4 = Crit�rio de Adjudica��o
                 ";
  //funcao construtor da classe
  function cl_adesaoregprecos()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("adesaoregprecos");
    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
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
      $this->si06_sequencial = ($this->si06_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_sequencial"] : $this->si06_sequencial);
      $this->si06_orgaogerenciador = ($this->si06_orgaogerenciador == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_orgaogerenciador"] : $this->si06_orgaogerenciador);
      $this->si06_modalidade = ($this->si06_modalidade == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_modalidade"] : $this->si06_modalidade);
      $this->si06_anoproc = ($this->si06_anoproc == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_anoproc"] : $this->si06_anoproc);
      $this->si06_numeroprc = ($this->si06_numeroprc == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_numeroprc"] : $this->si06_numeroprc);
      $this->si06_numlicitacao = ($this->si06_numlicitacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_numlicitacao"] : $this->si06_numlicitacao);
      if ($this->si06_dataadesao == "") {
        $this->si06_dataadesao_dia = ($this->si06_dataadesao_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_dataadesao_dia"] : $this->si06_dataadesao_dia);
        $this->si06_dataadesao_mes = ($this->si06_dataadesao_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_dataadesao_mes"] : $this->si06_dataadesao_mes);
        $this->si06_dataadesao_ano = ($this->si06_dataadesao_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_dataadesao_ano"] : $this->si06_dataadesao_ano);
        if ($this->si06_dataadesao_dia != "") {
          $this->si06_dataadesao = $this->si06_dataadesao_ano . "-" . $this->si06_dataadesao_mes . "-" . $this->si06_dataadesao_dia;
        }
      }
      if ($this->si06_dataata == "") {
        $this->si06_dataata_dia = ($this->si06_dataata_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_dataata_dia"] : $this->si06_dataata_dia);
        $this->si06_dataata_mes = ($this->si06_dataata_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_dataata_mes"] : $this->si06_dataata_mes);
        $this->si06_dataata_ano = ($this->si06_dataata_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_dataata_ano"] : $this->si06_dataata_ano);
        if ($this->si06_dataata_dia != "") {
          $this->si06_dataata = $this->si06_dataata_ano . "-" . $this->si06_dataata_mes . "-" . $this->si06_dataata_dia;
        }
      }
      if ($this->si06_datavalidade == "") {
        $this->si06_datavalidade_dia = ($this->si06_datavalidade_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_datavalidade_dia"] : $this->si06_datavalidade_dia);
        $this->si06_datavalidade_mes = ($this->si06_datavalidade_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_datavalidade_mes"] : $this->si06_datavalidade_mes);
        $this->si06_datavalidade_ano = ($this->si06_datavalidade_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_datavalidade_ano"] : $this->si06_datavalidade_ano);
        if ($this->si06_datavalidade_dia != "") {
          $this->si06_datavalidade = $this->si06_datavalidade_ano . "-" . $this->si06_datavalidade_mes . "-" . $this->si06_datavalidade_dia;
        }
      }
      if ($this->si06_publicacaoaviso == "") {
        $this->si06_publicacaoaviso_dia = ($this->si06_publicacaoaviso_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_publicacaoaviso_dia"] : $this->si06_publicacaoaviso_dia);
        $this->si06_publicacaoaviso_mes = ($this->si06_publicacaoaviso_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_publicacaoaviso_mes"] : $this->si06_publicacaoaviso_mes);
        $this->si06_publicacaoaviso_ano = ($this->si06_publicacaoaviso_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_publicacaoaviso_ano"] : $this->si06_publicacaoaviso_ano);
        if ($this->si06_publicacaoaviso_dia != "") {
          $this->si06_publicacaoaviso = $this->si06_publicacaoaviso_ano . "-" . $this->si06_publicacaoaviso_mes . "-" . $this->si06_publicacaoaviso_dia;
        }
      }
      $this->si06_objetoadesao = ($this->si06_objetoadesao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_objetoadesao"] : $this->si06_objetoadesao);
      $this->si06_orgarparticipante = ($this->si06_orgarparticipante == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_orgarparticipante"] : $this->si06_orgarparticipante);
      $this->si06_cgm = ($this->si06_cgm == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_cgm"] : $this->si06_cgm);
      $this->si06_descontotabela = ($this->si06_descontotabela == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_descontotabela"] : $this->si06_descontotabela);
      $this->si06_numeroadm = ($this->si06_numeroadm == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_numeroadm"] : $this->si06_numeroadm);
      if ($this->si06_dataabertura == "") {
        $this->si06_dataabertura_dia = ($this->si06_dataabertura_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_dataabertura_dia"] : $this->si06_dataabertura_dia);
        $this->si06_dataabertura_mes = ($this->si06_dataabertura_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_dataabertura_mes"] : $this->si06_dataabertura_mes);
        $this->si06_dataabertura_ano = ($this->si06_dataabertura_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_dataabertura_ano"] : $this->si06_dataabertura_ano);
        if ($this->si06_dataabertura_dia != "") {
          $this->si06_dataabertura = $this->si06_dataabertura_ano . "-" . $this->si06_dataabertura_mes . "-" . $this->si06_dataabertura_dia;
        }
      }
      $this->si06_processocompra = ($this->si06_processocompra == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_processocompra"] : $this->si06_processocompra);
      $this->si06_fornecedor = ($this->si06_fornecedor == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_fornecedor"] : $this->si06_fornecedor);
      $this->si06_processoporlote = ($this->si06_processoporlote == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_processoporlote"] : $this->si06_processoporlote);
      $this->si06_edital = ($this->si06_edital == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_edital"] : $this->si06_edital);
      $this->si06_exercicioedital = ($this->si06_exercicioedital == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_exercicioedital"] : $this->si06_exercicioedital);
      $this->si06_cadinicial = ($this->si06_cadinicial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_cadinicial"] : $this->si06_cadinicial);
      $this->si06_anocadastro = ($this->si06_anocadastro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_anocadastro"] : $this->si06_anocadastro);
      $this->si06_leidalicitacao = ($this->si06_leidalicitacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_leidalicitacao"] : $this->si06_leidalicitacao);
      $this->si06_anomodadm = ($this->si06_anomodadm == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_anomodadm"] : $this->si06_anomodadm);
      $this->si06_nummodadm = ($this->si06_nummodadm == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_nummodadm"] : $this->si06_nummodadm);
      $this->si06_departamento = ($this->si06_departamento == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_departamento"] : $this->si06_departamento);
      $this->si06_regimecontratacao = ($this->si06_regimecontratacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_regimecontratacao"] : $this->si06_regimecontratacao);
      $this->si06_criterioadjudicacao = ($this->si06_criterioadjudicacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_criterioadjudicacao"] : $this->si06_criterioadjudicacao);
    } else {
      $this->si06_sequencial = ($this->si06_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si06_sequencial"] : $this->si06_sequencial);
    }
  }

  // funcao para inclusao
  function incluir($si06_sequencial)
  {
    $this->atualizacampos();
    if ($this->si06_orgaogerenciador == null) {
      $this->erro_sql = " Campo Org�o Gerenciador nao Informado.";
      $this->erro_campo = "si06_orgaogerenciador";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_modalidade == null && $this->si06_regimecontratacao == 1) {
      $this->erro_sql = " Campo Modalidade nao Informado.";
      $this->erro_campo = "si06_modalidade";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if (!$this->si06_edital && db_getsession('DB_anousu') >= 2020 && $this->si06_regimecontratacao == 1) {
      $this->erro_sql = " Campo Edital n�o Informado.";
      $this->erro_campo = "si06_edital";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if (!$this->si06_exercicioedital) {
      $this->si06_exercicioedital = 'null';
    }
    if ($this->si06_anoproc == null) {
      $this->erro_sql = " Campo Ano Processo nao Informado.";
      $this->erro_campo = "si06_anoproc";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_numeroprc == null) {
      $this->erro_sql = " N�mero do Processo Licitat�rio n�o informado.";
      $this->erro_campo = "si06_numeroprc";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_numeroprc == "0") {
      $this->erro_sql = " Campo N�mero do Processo Licitat�rio n�o pode ser 0.";
      $this->erro_campo = "si06_numeroprc";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_numlicitacao == null && $this->si06_regimecontratacao == 1) {
      $this->erro_sql = " Campo N�m. Licita��o nao Informado.";
      $this->erro_campo = "si06_numlicitacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_dataadesao == null) {
      $this->erro_sql = " Campo Data de Ades�o nao Informado.";
      $this->erro_campo = "si06_dataadesao_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_dataata == null) {
      $this->erro_sql = " Campo Data da Ata nao Informado.";
      $this->erro_campo = "si06_dataata_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_datavalidade == null) {
      $this->erro_sql = " Campo Data Validade nao Informado.";
      $this->erro_campo = "si06_datavalidade_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si06_objetoadesao == null) {
      $this->erro_sql = " Campo Objeto da Ades�o nao Informado.";
      $this->erro_campo = "si06_objetoadesao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_orgarparticipante == null) {
      $this->erro_sql = " Campo Org�o integra a ata de Registro de Pre�o nao Informado.";
      $this->erro_campo = "si06_orgarparticipante";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_cgm == null) {
      $this->erro_sql = " Campo Respons�vel pela Aprova��o nao Informado.";
      $this->erro_campo = "si06_cgm";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_numeroadm == null) {
      $this->erro_sql = " N�mero do Processo de Ades�o n�o informado.";
      $this->erro_campo = "si06_numeroadm";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_numeroadm == "0") {
      $this->erro_sql = " Campo Exerc�cio do Processo de Ades�o n�o pode ser 0.";
      $this->erro_campo = "si06_numeroadm";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_dataabertura == null) {
      $this->erro_sql = " Campo Data da Abertura nao Informado.";
      $this->erro_campo = "si06_dataabertura_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_processocompra == null) {
      $this->erro_sql = " Campo Processo de compra nao Informado.";
      $this->erro_campo = "si06_processocompra";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si06_departamento == null) {
      $this->erro_sql = " Campo Departamento nao Informado.";
      $this->erro_campo = "si06_departamento";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si06_processoporlote == null) {
      $this->erro_sql = " Campo Processo por Lote nao Informado.";
      $this->erro_campo = "si06_processoporlote";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si06_leidalicitacao == null || $this->si06_leidalicitacao == '0') {
      $this->erro_sql = " Campo Lei da Licitacao nao Informado.";
      $this->erro_campo = "si06_leidalicitacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si06_anomodadm == null || $this->si06_anomodadm == '0') {
      $this->erro_sql = " Campo Exercicio do Processo nao Informado.";
      $this->erro_campo = "si06_anomodadm";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si06_nummodadm == null || $this->si06_nummodadm == '0') {
      $this->erro_sql = " Campo Numero da Modalidade nao Informado.";
      $this->erro_campo = "si06_nummodadm";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si06_regimecontratacao == null || $this->si06_regimecontratacao == '0') {
      $this->erro_sql = " Campo Regime de Contrata��o N�o Informado.";
      $this->erro_campo = "si06_regimecontratacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si06_criterioadjudicacao == null || $this->si06_criterioadjudicacao == '0') {
      $this->erro_sql = " Campo Crit�rio de Adjudica��o nao Informado.";
      $this->erro_campo = "si06_criterioadjudicacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si06_cadinicial == null && db_getsession('DB_anousu') >= 2020) {
      $this->si06_cadinicial = 1;
    } else {
      $this->si06_cadinicial = 'null';
    }

    if($this->si06_regimecontratacao != 1){
      $this->si06_edital = "null";
      $this->si06_modalidade = "null";
      $this->si06_numlicitacao = "null";
    }

    if ($si06_sequencial == "" || $si06_sequencial == null) {
      $result = db_query("select nextval('sic_adesaoregprecos_si06_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("\n", "", @pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: sic_adesaoregprecos_si06_sequencial_seq do campo: si06_sequencial";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->si06_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from sic_adesaoregprecos_si06_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si06_sequencial)) {
        $this->erro_sql = " Campo si06_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      } else {
        $this->si06_sequencial = $si06_sequencial;
      }
    }
    if (($this->si06_sequencial == null) || ($this->si06_sequencial == "")) {
      $this->erro_sql = " Campo si06_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    $this->si06_objetoadesao =  str_replace(["'",'\"',':','*','@','|','\\','/'],'',$this->si06_objetoadesao);

    $sql = "insert into adesaoregprecos(
                                       si06_sequencial
                                      ,si06_orgaogerenciador
                                      ,si06_modalidade
                                      ,si06_numeroprc
                                      ,si06_numlicitacao
                                      ,si06_dataadesao
                                      ,si06_dataata
                                      ,si06_datavalidade
                                      ,si06_publicacaoaviso
                                      ,si06_objetoadesao
                                      ,si06_orgarparticipante
                                      ,si06_cgm
                                      ,si06_descontotabela
                                      ,si06_numeroadm
                                      ,si06_dataabertura
                                      ,si06_processocompra
                                      ,si06_processoporlote
                                      ,si06_instit
                                      ,si06_anoproc
                                      ,si06_edital
                                      ,si06_exercicioedital
                                      ,si06_cadinicial
                                      ,si06_anocadastro
                                      ,si06_leidalicitacao
                                      ,si06_anomodadm
                                      ,si06_nummodadm
                                      ,si06_departamento
                                      ,si06_regimecontratacao
                                      ,si06_criterioadjudicacao
                       )
                values (
                                $this->si06_sequencial
                               ,$this->si06_orgaogerenciador
                               ,$this->si06_modalidade
                               ,$this->si06_numeroprc
                               ,$this->si06_numlicitacao
                               ," . ($this->si06_dataadesao == "null" || $this->si06_dataadesao == "" ? "null" : "'" . $this->si06_dataadesao . "'") . "
                               ," . ($this->si06_dataata == "null" || $this->si06_dataata == "" ? "null" : "'" . $this->si06_dataata . "'") . "
                               ," . ($this->si06_datavalidade == "null" || $this->si06_datavalidade == "" ? "null" : "'" . $this->si06_datavalidade . "'") . "
                               ," . ($this->si06_publicacaoaviso == "null" || $this->si06_publicacaoaviso == "" ? "null" : "'" . $this->si06_publicacaoaviso . "'") . "
                               ,'$this->si06_objetoadesao'
                               ,$this->si06_orgarparticipante
                               ,$this->si06_cgm
                               ,null
                               ,$this->si06_numeroadm
                               ," . ($this->si06_dataabertura == "null" || $this->si06_dataabertura == "" ? "null" : "'" . $this->si06_dataabertura . "'") . "
                               ,$this->si06_processocompra
                               ,$this->si06_processoporlote
                               ," . db_getsession("DB_instit") . "
                               ,$this->si06_anoproc
                               ,$this->si06_edital
                               ,$this->si06_exercicioedital
                               ,$this->si06_cadinicial
                               ,$this->si06_anocadastro
                               ,$this->si06_leidalicitacao
                               ,$this->si06_anomodadm
                               ,$this->si06_nummodadm
                               ,$this->si06_departamento
                               ,$this->si06_regimecontratacao
                               ,$this->si06_criterioadjudicacao

                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql   = "Ades�o a Registro de Pre�os ($this->si06_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_banco = "Ades�o a Registro de Pre�os j� Cadastrado";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      } else {
        $this->erro_sql   = "Ades�o a Registro de Pre�os ($this->si06_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n Deve-se acrescentar itens \\n";
    $this->erro_sql .= "Valores : " . $this->si06_sequencial;
    $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->si06_sequencial));
    if (($resaco != false) || ($this->numrows != 0)) {
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac, 0, 0);
      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
      $resac = db_query("insert into db_acountkey values($acount,2009292,'$this->si06_sequencial','I')");
      $resac = db_query("insert into db_acount values($acount,2010205,2009292,'','" . AddSlashes(pg_result($resaco, 0, 'si06_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009293,'','" . AddSlashes(pg_result($resaco, 0, 'si06_orgaogerenciador')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009294,'','" . AddSlashes(pg_result($resaco, 0, 'si06_modalidade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009295,'','" . AddSlashes(pg_result($resaco, 0, 'si06_numeroprc')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009296,'','" . AddSlashes(pg_result($resaco, 0, 'si06_numlicitacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009297,'','" . AddSlashes(pg_result($resaco, 0, 'si06_dataadesao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009298,'','" . AddSlashes(pg_result($resaco, 0, 'si06_dataata')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009299,'','" . AddSlashes(pg_result($resaco, 0, 'si06_datavalidade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009300,'','" . AddSlashes(pg_result($resaco, 0, 'si06_publicacaoaviso')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009301,'','" . AddSlashes(pg_result($resaco, 0, 'si06_objetoadesao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009302,'','" . AddSlashes(pg_result($resaco, 0, 'si06_orgarparticipante')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009307,'','" . AddSlashes(pg_result($resaco, 0, 'si06_cgm')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009308,'','" . AddSlashes(pg_result($resaco, 0, 'si06_descontotabela')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009309,'','" . AddSlashes(pg_result($resaco, 0, 'si06_numeroadm')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009310,'','" . AddSlashes(pg_result($resaco, 0, 'si06_dataabertura')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009311,'','" . AddSlashes(pg_result($resaco, 0, 'si06_processocompra')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009312,'','" . AddSlashes(pg_result($resaco, 0, 'si06_fornecedor')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009313,'','" . AddSlashes(pg_result($resaco, 0, 'si06_anoproc')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009314,'','" . AddSlashes(pg_result($resaco, 0, 'si06_anomodadm')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010205,2009315,'','" . AddSlashes(pg_result($resaco, 0, 'si06_nummodadm')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar($si06_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update adesaoregprecos set ";
    $virgula = "";
    if (trim($this->si06_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_sequencial"])) {
      $sql  .= $virgula . " si06_sequencial = $this->si06_sequencial ";
      $virgula = ",";
      if (trim($this->si06_sequencial) == null) {
        $this->erro_sql = " Campo Sequencial nao Informado.";
        $this->erro_campo = "si06_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_orgaogerenciador) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_orgaogerenciador"])) {
      $sql  .= $virgula . " si06_orgaogerenciador = $this->si06_orgaogerenciador ";
      $virgula = ",";
      if (trim($this->si06_orgaogerenciador) == null) {
        $this->erro_sql = " Campo Org�o Gerenciador nao Informado.";
        $this->erro_campo = "si06_orgaogerenciador";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_modalidade) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_modalidade"]) && $this->si06_regimecontratacao != 1) {
      $sql  .= $virgula . " si06_modalidade = $this->si06_modalidade ";
      $virgula = ",";
      if (trim($this->si06_modalidade) == null) {
        $this->erro_sql = " Campo Modalidade nao Informado.";
        $this->erro_campo = "si06_modalidade";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_numeroprc) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_numeroprc"])) {
      $sql  .= $virgula . " si06_numeroprc = $this->si06_numeroprc ";
      $virgula = ",";
      if (trim($this->si06_numeroprc) == null) {
        $this->erro_sql = " N�mero do Processo Licitat�rio n�o informado.";
        $this->erro_campo = "si06_numeroprc";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_anoproc) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_anoproc"])) {
      $sql .= $virgula . " si06_anoproc = $this->si06_anoproc ";
      $virgula = ",";
      if (trim($this->si06_anoproc) == null) {
        $this->erro_sql = " Campo Exercicio do Processo nao Informado.";
        $this->erro_campo = "si06_anoproc";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_numlicitacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_numlicitacao"]) && $this->si06_regimecontratacao == 1) {
      $sql  .= $virgula . " si06_numlicitacao = $this->si06_numlicitacao ";
      $virgula = ",";
      if (trim($this->si06_numlicitacao) == null) {
        $this->erro_sql = " Campo N�m. Licita��o nao Informado.";
        $this->erro_campo = "si06_numlicitacao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_dataadesao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_dataadesao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["si06_dataadesao_dia"] != "")) {
      $sql  .= $virgula . " si06_dataadesao = '$this->si06_dataadesao' ";
      $virgula = ",";
      if (trim($this->si06_dataadesao) == null) {
        $this->erro_sql = " Campo Data de Ades�o nao Informado.";
        $this->erro_campo = "si06_dataadesao_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["si06_dataadesao_dia"])) {
        $sql  .= $virgula . " si06_dataadesao = null ";
        $virgula = ",";
        if (trim($this->si06_dataadesao) == null) {
          $this->erro_sql = " Campo Data de Ades�o nao Informado.";
          $this->erro_campo = "si06_dataadesao_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if (trim($this->si06_dataata) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_dataata_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["si06_dataata_dia"] != "")) {
      $sql  .= $virgula . " si06_dataata = '$this->si06_dataata' ";
      $virgula = ",";
      if (trim($this->si06_dataata) == null) {
        $this->erro_sql = " Campo Data da Ata nao Informado.";
        $this->erro_campo = "si06_dataata_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["si06_dataata_dia"])) {
        $sql  .= $virgula . " si06_dataata = null ";
        $virgula = ",";
        if (trim($this->si06_dataata) == null) {
          $this->erro_sql = " Campo Data da Ata nao Informado.";
          $this->erro_campo = "si06_dataata_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if (trim($this->si06_datavalidade) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_datavalidade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["si06_datavalidade_dia"] != "")) {
      $sql  .= $virgula . " si06_datavalidade = '$this->si06_datavalidade' ";
      $virgula = ",";
      if (trim($this->si06_datavalidade) == null) {
        $this->erro_sql = " Campo Data Validade nao Informado.";
        $this->erro_campo = "si06_datavalidade_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["si06_datavalidade_dia"])) {
        $sql  .= $virgula . " si06_datavalidade = null ";
        $virgula = ",";
        if (trim($this->si06_datavalidade) == null) {
          $this->erro_sql = " Campo Data Validade nao Informado.";
          $this->erro_campo = "si06_datavalidade_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if (trim($this->si06_publicacaoaviso) != "") {
      $sql  .= $virgula . " si06_publicacaoaviso = '$this->si06_publicacaoaviso' ";
      $virgula = ",";
    }else{
        $sql  .= $virgula . " si06_publicacaoaviso = null ";
        $virgula = ",";
    }

    if (trim($this->si06_objetoadesao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_objetoadesao"])) {
      $sql  .= $virgula . " si06_objetoadesao = '$this->si06_objetoadesao' ";
      $virgula = ",";
      if (trim($this->si06_objetoadesao) == null) {
        $this->erro_sql = " Campo Objeto da Ades�o nao Informado.";
        $this->erro_campo = "si06_objetoadesao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_orgarparticipante) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_orgarparticipante"])) {
      $sql  .= $virgula . " si06_orgarparticipante = $this->si06_orgarparticipante ";
      $virgula = ",";
      if (trim($this->si06_orgarparticipante) == null) {
        $this->erro_sql = " Campo Org�o integra a ata de Registro de Pre�o nao Informado.";
        $this->erro_campo = "si06_orgarparticipante";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_cgm) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_cgm"])) {
      $sql  .= $virgula . " si06_cgm = $this->si06_cgm ";
      $virgula = ",";
      if (trim($this->si06_cgm) == null) {
        $this->erro_sql = " Campo Respons�vel pela Aprova��o nao Informado.";
        $this->erro_campo = "si06_cgm";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_numeroadm) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_numeroadm"])) {
      $sql  .= $virgula . " si06_numeroadm = $this->si06_numeroadm ";
      $virgula = ",";
      if (trim($this->si06_numeroadm) == null) {
        $this->erro_sql = " N�mero do Processo de Ades�o n�o informado.";
        $this->erro_campo = "si06_numeroadm";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_regimecontratacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_regimecontratacao"])) {
      $sql  .= $virgula . " si06_regimecontratacao = $this->si06_regimecontratacao ";
      $virgula = ",";
      if (trim($this->si06_regimecontratacao) == null || $this->si06_regimecontratacao == "0"){
        $this->erro_sql = " Campo Regime de Contrata��o nao Informado.";
        $this->erro_campo = "si06_regimecontratacao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_criterioadjudicacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_criterioadjudicacao"])) {
      $sql  .= $virgula . " si06_criterioadjudicacao = $this->si06_criterioadjudicacao ";
      $virgula = ",";
      if (trim($this->si06_criterioadjudicacao) == null || $this->si06_criterioadjudicacao == "0") {
        $this->erro_sql = " Campo Criterio de Adjudicacao nao Informado.";
        $this->erro_campo = "si06_criterioadjudicacao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_dataabertura) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_dataabertura_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["si06_dataabertura_dia"] != "")) {
      $sql  .= $virgula . " si06_dataabertura = '$this->si06_dataabertura' ";
      $virgula = ",";
      if (trim($this->si06_dataabertura) == null) {
        $this->erro_sql = " Campo Data da Abertura nao Informado.";
        $this->erro_campo = "si06_dataabertura_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["si06_dataabertura_dia"])) {
        $sql  .= $virgula . " si06_dataabertura = null ";
        $virgula = ",";
        if (trim($this->si06_dataabertura) == null) {
          $this->erro_sql = " Campo Data da Abertura nao Informado.";
          $this->erro_campo = "si06_dataabertura_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if (trim($this->si06_processocompra) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_processocompra"])) {
      $sql  .= $virgula . " si06_processocompra = $this->si06_processocompra ";
      $virgula = ",";
      if (trim($this->si06_processocompra) == null) {
        $this->erro_sql = " Campo Processo de compra nao Informado.";
        $this->erro_campo = "si06_processocompra";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_fornecedor) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_fornecedor"])) {
      $sql  .= $virgula . " si06_fornecedor = $this->si06_fornecedor ";
      $virgula = ",";
      if (trim($this->si06_fornecedor) == null) {
        $this->erro_sql = " Campo Fornecedor nao Informado.";
        $this->erro_campo = "si06_fornecedor";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_processoporlote) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_processoporlote"])) {
      $sql  .= $virgula . " si06_processoporlote = $this->si06_processoporlote ";
      $virgula = ",";
      if (trim($this->si06_processoporlote) == null) {
        $this->erro_sql = " Campo Processo por Lote nao Informado.";
        $this->erro_campo = "si06_processoporlote";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_leidalicitacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_leidalicitacao"])) {
      $sql  .= $virgula . " si06_leidalicitacao = $this->si06_leidalicitacao ";
      $virgula = ",";
      if (trim($this->si06_leidalicitacao) == null) {
        $this->erro_sql = " Campo lei de licitacao nao Informado.";
        $this->erro_campo = "si06_leidalicitacao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }

    if (trim($this->si06_anomodadm) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_anomodadm"])) {
      $sql  .= $virgula . " si06_anomodadm = $this->si06_anomodadm ";
      $virgula = ",";
      if (trim($this->si06_anomodadm) == null) {
        $this->erro_sql = " Campo Exercicio do Processo nao Informado.";
        $this->erro_campo = "si06_anomodadm";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }

    if (trim($this->si06_nummodadm) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_nummodadm"])) {
      $sql  .= $virgula . " si06_nummodadm = $this->si06_nummodadm ";
      $virgula = ",";
      if (trim($this->si06_nummodadm) == null) {
        $this->erro_sql = " Campo Numero da Modalidade nao Informado.";
        $this->erro_campo = "si06_nummodadm";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_departamento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_departamento"])) {
      $sql  .= $virgula . " si06_departamento = $this->si06_departamento ";
      $virgula = ",";
      if (trim($this->si06_departamento) == null) {
        $this->erro_sql = " Campo Departamento n�o Informado.";
        $this->erro_campo = "si06_departamento";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }

    if (trim($this->si06_edital) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_edital"]) && $this->si06_regimecontratacao == 1) {
      $sql  .= $virgula . " si06_edital = $this->si06_edital ";
      $virgula = ",";
      if (!trim($this->si06_edital)) {
        $this->erro_sql = " Campo Edital n�o Informado.";
        $this->erro_campo = "si06_edital";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si06_cadinicial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_cadinicial"])) {
      $sql  .= $virgula . " si06_cadinicial = $this->si06_cadinicial ";
      $virgula = ",";
    }
    if (trim($this->si06_exercicioedital) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_exercicioedital"])) {
      $sql  .= $virgula . " si06_exercicioedital = $this->si06_exercicioedital ";
      $virgula = ",";
    }
    if (trim($this->si06_codunidadesubant) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si06_codunidadesubant"])) {
      $sql  .= $virgula . " si06_codunidadesubant = '$this->si06_codunidadesubant'";
      $virgula = ",";
    }
    
    $this->si06_objetoadesao =  str_replace(["'",'\"',':','@','|','\\'],'',$this->si06_objetoadesao);

    $sql .= " where ";
    if ($si06_sequencial != null) {
      $sql .= " si06_sequencial = $this->si06_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->si06_sequencial));
    if ($this->numrows > 0) {
      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,1009292,'$this->si06_sequencial','A')");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_sequencial"]) || $this->si06_sequencial != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009292,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_sequencial')) . "','$this->si06_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_orgaogerenciador"]) || $this->si06_orgaogerenciador != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009293,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_orgaogerenciador')) . "','$this->si06_orgaogerenciador'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_modalidade"]) || $this->si06_modalidade != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009294,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_modalidade')) . "','$this->si06_modalidade'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_numeroprc"]) || $this->si06_numeroprc != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009295,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_numeroprc')) . "','$this->si06_numeroprc'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_numlicitacao"]) || $this->si06_numlicitacao != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009296,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_numlicitacao')) . "','$this->si06_numlicitacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_dataadesao"]) || $this->si06_dataadesao != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009297,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_dataadesao')) . "','$this->si06_dataadesao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_dataata"]) || $this->si06_dataata != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009298,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_dataata')) . "','$this->si06_dataata'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_datavalidade"]) || $this->si06_datavalidade != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009299,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_datavalidade')) . "','$this->si06_datavalidade'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_publicacaoaviso"]) || $this->si06_publicacaoaviso != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009300,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_publicacaoaviso')) . "','$this->si06_publicacaoaviso'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_objetoadesao"]) || $this->si06_objetoadesao != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009301,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_objetoadesao')) . "','$this->si06_objetoadesao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_orgarparticipante"]) || $this->si06_orgarparticipante != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009302,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_orgarparticipante')) . "','$this->si06_orgarparticipante'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_cgm"]) || $this->si06_cgm != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009307,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_cgm')) . "','$this->si06_cgm'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_descontotabela"]) || $this->si06_descontotabela != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009308,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_descontotabela')) . "','$this->si06_descontotabela'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_numeroadm"]) || $this->si06_numeroadm != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009309,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_numeroadm')) . "','$this->si06_numeroadm'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_dataabertura"]) || $this->si06_dataabertura != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009310,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_dataabertura')) . "','$this->si06_dataabertura'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_processocompra"]) || $this->si06_processocompra != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009311,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_processocompra')) . "','$this->si06_processocompra'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_fornecedor"]) || $this->si06_fornecedor != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009312,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_fornecedor')) . "','$this->si06_fornecedor'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si06_anoproc"]) || $this->si06_anoproc != "")
          $resac = db_query("insert into db_acount values($acount,2010205,1009313,'" . AddSlashes(pg_result($resaco, $conresaco, 'si06_anoproc')) . "','$this->si06_anoproc'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Ades�o a Registro de Pre�os nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->si06_sequencial;
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Ades�o a Registro de Pre�os nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : " . $this->si06_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->si06_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir($si06_sequencial = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($si06_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
    if (($resaco != false) || ($this->numrows != 0)) {
      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,1009292,'$si06_sequencial','E')");
        $resac = db_query("insert into db_acount values($acount,2010205,1009292,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009293,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_orgaogerenciador')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009294,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_modalidade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009295,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_numeroprc')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009296,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_numlicitacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009297,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_dataadesao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009298,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_dataata')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009299,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_datavalidade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009300,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_publicacaoaviso')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009301,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_objetoadesao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009302,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_orgarparticipante')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009307,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_cgm')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009308,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_descontotabela')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009309,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_numeroadm')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009310,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_dataabertura')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009311,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_processocompra')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009312,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_fornecedor')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010205,1009313,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si06_anoproc')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $sql = " delete from adesaoregprecos
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($si06_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " si06_sequencial = $si06_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Ades�o a Registro de Pre�os nao Exclu�do. Exclus�o Abortada.\\n";
      $this->erro_sql .= "Valores : " . $si06_sequencial;
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Ades�o a Registro de Pre�os nao Encontrado. Exclus�o n�o Efetuada.\\n";
        $this->erro_sql .= "Valores : " . $si06_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $si06_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
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
      $this->numrows    = 0;
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Erro ao selecionar os registros.";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:adesaoregprecos";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  function sql_query($si06_sequencial = null, $campos = "si06_sequencial, si06_orgaogerenciador, db_depart.descrdepto as descricaodepartamento,si06_modalidade, si06_numeroprc, si06_numlicitacao, si06_dataadesao, si06_dataata, si06_datavalidade, si06_publicacaoaviso, si06_objetoadesao, si06_orgarparticipante, si06_cgm, si06_descontotabela, si06_numeroadm, si06_anomodadm,si06_nummodadm, si06_dataabertura, si06_processocompra, si06_fornecedor, cgm.z01_nome as z01_nomeorg, cgm.z01_nome as z01_nomef, c.z01_nome as z01_nomeresp, pc80_data", $ordem = null, $dbwhere = "")
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
    $sql .= " from adesaoregprecos ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = adesaoregprecos.si06_orgaogerenciador";
    $sql .= "      inner join cgm c on c.z01_numcgm = adesaoregprecos.si06_cgm";
    $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = adesaoregprecos.si06_processocompra";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcproc.pc80_usuario";
    $sql .= "      left join db_depart  on  db_depart.coddepto = adesaoregprecos.si06_departamento";
    $sql .= "      left join acordo on  acordo.ac16_adesaoregpreco = adesaoregprecos.si06_sequencial";
    $sql2 = "";
    // $dbwhere = "si06_instit = " . db_getsession("DB_instit");
    if ($dbwhere == "") {
      if ($si06_sequencial != null) {
        $sql2 .= " where adesaoregprecos.si06_sequencial = $si06_sequencial ";
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
  function sql_query_file($si06_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from adesaoregprecos ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si06_sequencial != null) {
        $sql2 .= " where adesaoregprecos.si06_sequencial = $si06_sequencial ";
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
  function sql_query_completo($si06_sequencial = null, $campos = "si06_sequencial, si06_orgaogerenciador, si06_modalidade, si06_numeroprc, si06_numlicitacao, si06_dataadesao, si06_dataata, si06_datavalidade, si06_publicacaoaviso, si06_objetoadesao, si06_orgarparticipante, si06_cgm, si06_descontotabela, si06_numeroadm, si06_anomodadm,si06_nummodadm, si06_dataabertura, si06_processocompra, si06_fornecedor, cgm.z01_nome as z01_nomeorg, cgm.z01_nome as z01_nomef, c.z01_nome as z01_nomeresp, pc80_data", $ordem = null, $dbwhere = "")
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
    $sql .= " from adesaoregprecos ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = adesaoregprecos.si06_orgaogerenciador";
    $sql .= "      inner join cgm c on c.z01_numcgm = adesaoregprecos.si06_cgm";
    $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = adesaoregprecos.si06_processocompra";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcproc.pc80_usuario";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = pcproc.pc80_depto";
    $sql2 = "";
    // $dbwhere = "si06_instit = " . db_getsession("DB_instit");
    if ($dbwhere == "") {
      if ($si06_sequencial != null) {
        $sql2 .= " where adesaoregprecos.si06_sequencial = $si06_sequencial ";
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
