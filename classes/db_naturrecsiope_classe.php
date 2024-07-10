<?php
//MODULO: contabilidade
//CLASSE DA ENTIDADE naturrecsiope/
class cl_naturrecsiope {
    // cria variaveis de erro
    public $rotulo     = null;
    public $query_sql  = null;
    public $numrows    = 0;
    public $numrows_incluir = 0;
    public $numrows_alterar = 0;
    public $numrows_excluir = 0;
    public $erro_status= null;
    public $erro_sql   = null;
    public $erro_banco = null;
    public $erro_msg   = null;
    public $erro_campo = null;
    public $pagina_retorno = null;
    // cria variaveis do arquivo
    public $c224_natrececidade = null;
    public $c224_natrecsiope = null;
    public $c224_anousu = null;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 c224_natrececidade = varchar(15) = E-Cidade
                 c224_natrecsiope = varchar(15) = Siope
                 c224_anousu = int4 = Ano
                 ";

    //funcao construtor da classe
    function __construct() {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("naturrecsiope");
        $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
    }

    //funcao erro
    function erro($mostra,$retorna) {
        if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )) {
            echo "<script>alert(\"".$this->erro_msg."\");</script>";
            if ($retorna==true) {
                echo "<script>location.href='".$this->pagina_retorno."'</script>";
            }
        }
    }

    // funcao para atualizar campos
    function atualizacampos($exclusao=false) {
        if ($exclusao==false) {
            $this->c224_natrececidade = ($this->c224_natrececidade == ""?@$GLOBALS["HTTP_POST_VARS"]["c224_natrececidade"]:$this->c224_natrececidade);
            $this->c224_natrecsiope = ($this->c224_natrecsiope == ""?@$GLOBALS["HTTP_POST_VARS"]["c224_natrecsiope"]:$this->c224_natrecsiope);
            $this->c224_anousu = ($this->c224_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c224_anousu"]:$this->c224_anousu);
        } else {
            $this->c224_natrececidade = ($this->c224_natrececidade == ""?@$GLOBALS["HTTP_POST_VARS"]["c224_natrececidade"]:$this->c224_natrececidade);
            $this->c224_natrecsiope = ($this->c224_natrecsiope == ""?@$GLOBALS["HTTP_POST_VARS"]["c224_natrecsiope"]:$this->c224_natrecsiope);
            $this->c224_anousu = ($this->c224_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c224_anousu"]:$this->c224_anousu);
        }
    }

    // funcao para inclusao
    function incluir ($c224_natrececidade,$c224_natrecsiope) {
        $this->atualizacampos();
        $this->c224_natrececidade = $c224_natrececidade;
        $this->c224_natrecsiope = $c224_natrecsiope;
        if (($this->c224_natrececidade == null) || ($this->c224_natrececidade == "") ) {
            $this->erro_sql = " Campo c224_natrececidade nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if (($this->c224_natrecsiope == null) || ($this->c224_natrecsiope == "") ) {
            $this->erro_sql = " Campo c224_natrecsiope nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into naturrecsiope(
                                       c224_natrececidade
                                      ,c224_natrecsiope
                                      ,c224_anousu
                       )
                values (
                                '$this->c224_natrececidade'
                               ,'$this->c224_natrecsiope'
                               ,'$this->c224_anousu'
                      )";
        $result = db_query($sql);
        if ($result==false) {
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            if ( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ) {
                $this->erro_sql   = "Natureza da despesa Siope ($this->c224_natrececidade."-".$this->c224_natrecsiope) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_banco = "Natureza da despesa Siope j� Cadastrado";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            } else {
                $this->erro_sql   = "Elemento da receita Siope ($this->c224_natrececidade."-".$this->c224_natrecsiope) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir= 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->c224_natrececidade."-".$this->c224_natrecsiope;
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir= pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        return true;
    }

    // funcao para alteracao
    function alterar ($c224_natrececidade=null,$c224_natrecsiope=null) {
        $this->atualizacampos();
        $sql = " update naturrecsiope set ";
        $virgula = "";
        if (trim($this->c224_natrececidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c224_natrececidade"])) {
            $sql  .= $virgula." c224_natrececidade = '$this->c224_natrececidade' ";
            $virgula = ",";
            if (trim($this->c224_natrececidade) == null ) {
                $this->erro_sql = " Campo Natureza receita E-cidade n�o informado.";
                $this->erro_campo = "c224_natrececidade";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->c224_natrecsiope)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c224_natrecsiope"])) {
            $sql  .= $virgula." c224_natrecsiope = '$this->c224_natrecsiope' ";
            $virgula = ",";
            if (trim($this->c224_natrecsiope) == null ) {
                $this->erro_sql = " Campo Natureza receita Siope n�o informado.";
                $this->erro_campo = "c224_natrecsiope";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " c224_anousu = ".$this->c224_anousu." ";
        $sql .= " where ";
        if ($c224_natrececidade!=null) {
            $sql .= " c224_natrececidade = '$this->c224_natrececidade'";
        }
        if ($c224_natrecsiope!=null) {
            $sql .= " and  c224_natrecsiope = '$this->c224_natrecsiope'";
        }

        $result = db_query($sql);
        if ($result==false) {
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "Natureza da receita Siope nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : ".$this->c224_natrececidade."-".$this->c224_natrecsiope;
            $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result)==0) {
                $this->erro_banco = "";
                $this->erro_sql = "Natureza da receita Siope nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : ".$this->c224_natrececidade."-".$this->c224_natrecsiope;
                $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : ".$this->c224_natrececidade."-".$this->c224_natrecsiope;
                $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    // funcao para exclusao
    function excluir ($c224_natrececidade=null,$c224_natrecsiope=null,$dbwhere=null) {

        $sql = " delete from naturrecsiope
                    where ";
        $sql2 = "";
        if ($dbwhere==null || $dbwhere =="") {
            if ($c224_natrececidade != "") {
                if ($sql2!="") {
                    $sql2 .= " and ";
                }
                $sql2 .= " c224_natrececidade = '$c224_natrececidade' ";
            }
            if ($c224_natrecsiope != "") {
                if ($sql2!="") {
                    $sql2 .= " and ";
                }
                $sql2 .= " c224_natrecsiope = '$c224_natrecsiope' ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql.$sql2);
        if ($result==false) {
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "Elemento da receita Siope nao Exclu�do. Exclus�o Abortada.\\n";
            $this->erro_sql .= "Valores : ".$c224_natrececidade."-".$c224_natrecsiope;
            $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result)==0) {
                $this->erro_banco = "";
                $this->erro_sql = "Elemento da receita Siope nao Encontrado. Exclus�o n�o Efetuada.\\n";
                $this->erro_sql .= "Valores : ".$c224_natrececidade."-".$c224_natrecsiope;
                $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : ".$c224_natrececidade."-".$c224_natrecsiope;
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
        if ($result==false) {
            $this->numrows    = 0;
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "Erro ao selecionar os registros.";
            $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_numrows($result);
        if ($this->numrows==0) {
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:naturrecsiope";
            $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    // funcao do sql
    function sql_query ( $c224_natrececidade=null,$c224_natrecsiope=null,$campos="*",$ordem=null,$dbwhere="") {
        $sql = "select ";
        if ($campos != "*" ) {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++) {
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from naturrecsiope ";
        $sql2 = "";
        if ($dbwhere=="") {
            if ($c224_natrececidade!=null ) {
                $sql2 .= " where naturrecsiope.c224_natrececidade = '$c224_natrececidade' ";
            }
            if ($c224_natrecsiope!=null ) {
                if ($sql2!="") {
                    $sql2 .= " and ";
                } else {
                    $sql2 .= " where ";
                }
                $sql2 .= " naturrecsiope.c224_natrecsiope = '$c224_natrecsiope' ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null ) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++) {
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    // funcao do sql
    function sql_query_file ( $c224_natrececidade=null,$c224_natrecsiope=null,$campos="*",$ordem=null,$dbwhere="",$c224_anousu="") {
        $sql = "select ";
        if ($campos != "*" ) {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++) {
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from naturrecsiope ";
        $sql2 = "";
        if ($dbwhere=="") {
            if ($c224_natrececidade!=null ) {
                $sql2 .= " where naturrecsiope.c224_natrececidade = '$c224_natrececidade' ";
            }
            if ($c224_natrecsiope!=null ) {
                if ($sql2!="") {
                    $sql2 .= " and ";
                } else {
                    $sql2 .= " where ";
                }
                $sql2 .= " naturrecsiope.c224_natrecsiope = '$c224_natrecsiope' ";
            }
            if ($c224_anousu!=null) {
                if ($sql2!="") {
                    $sql2 .= " and ";
                } else {
                    $sql2 .= " where ";
                }
                $sql2 .= " naturrecsiope.c224_anousu = '$c224_anousu' ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null ) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++) {
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    function sql_query_siope ( $c224_natrececidade=null, $c224_natrecsiope="",$c224_anousu="",$campos="*",$ordem=null,$dbwhere="") {
        $sql = "select ";
        if ($campos != "*" ) {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++) {
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from naturrecsiope ";
        $sql .= ' inner join elerecsiope on substr(naturrecsiope.c224_natrecsiope, 1, 11) = elerecsiope.c225_natrecsiope and naturrecsiope.c224_anousu = elerecsiope.c225_anousu';
        $sql2 = "";
        if ($dbwhere=="") {
            if ($c224_natrececidade!=null ) {
                $sql2 .= " where naturrecsiope.c224_natrececidade = '$c224_natrececidade' ";
            }
            if ($c224_natrecsiope!=null ) {
                if ($sql2!="") {
                    $sql2 .= " and ";
                } else {
                    $sql2 .= " where ";
                }
                $sql2 .= " naturrecsiope.c224_natrecsiope = '$c224_natrecsiope' ";
            }
            if ($c224_anousu!=null) {
                if ($sql2!="") {
                    $sql2 .= " and ";
                } else {
                    $sql2 .= " where ";
                }
                $sql2 .= " naturrecsiope.c224_anousu = '$c224_anousu' ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null ) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++) {
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }

        if ($c224_anousu > 2022) {
            $sql = "SELECT  CASE 
                                WHEN c224_natrececidade IS NOT NULL THEN substr(c224_natrececidade,1,12)
                                ELSE substr(o57_fonte,1,12)
                            END AS c225_natrecsiope,
                            CASE 
                                WHEN c224_natrececidade IS NOT NULL THEN c225_descricao
                                ELSE o57_descr
                            END AS c225_descricao
                    FROM (SELECT '{$c224_natrececidade}'::varchar AS o57_fonte, '{$campos}'::varchar AS o57_descr) AS principal
                    LEFT JOIN naturrecsiope ON o57_fonte = c224_natrececidade AND c224_anousu = {$c224_anousu}
                    LEFT JOIN elerecsiope ON substr(naturrecsiope.c224_natrecsiope, 1, 11) = elerecsiope.c225_natrecsiope AND naturrecsiope.c224_anousu = elerecsiope.c225_anousu";

        }
        return $sql;
    }
}
?>
