<?
//MODULO: sicom
//CLASSE DA ENTIDADE bpdcasp102022
class cl_bpdcasp102022 { 
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
   var $si208_sequencial = 0;
   var $si208_tiporegistro = 0;
   var $si208_vlativocircucaixaequicaixa = 0;
   var $si208_vlativocircucredicurtoprazo = 0;
   var $si208_vlativocircuinvestapliccurtoprazo = 0;
   var $si208_vlativocircudemaiscredicurtoprazo = 0;
   var $si208_vlativocircuestoques = 0;
   var $si208_vlativonaocircumantidovenda = 0;
   var $si208_vlativocircuativobio = 0;
   var $si208_vlativocircuvpdantecipada = 0;
   var $si208_vlativonaocircurlp = 0;
   var $si208_vlativonaocircuinvestimentos = 0;
   var $si208_vlativonaocircuimobilizado = 0;
   var $si208_vlativonaocircuintagivel = 0;
   var $si208_vltotalativo = 0;
   var $si208_ano = 0;
   var $si208_periodo = 0;
   var $si208_institu = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 si208_sequencial = int4 = si208_sequencial
                 si208_tiporegistro = int4 = si208_tiporegistro
                 si208_vlativocircucaixaequicaixa = float4 = si208_vlativocircucaixaequicaixa
                 si208_vlativocircucredicurtoprazo = float4 = si208_vlativocircucredicurtoprazo
                 si208_vlativocircudemaiscredicurtoprazo = float4 = si208_vlativocircudemaiscredicurtoprazo
                 si208_vlativocircuinvestapliccurtoprazo = float4 = si208_vlativocircuinvestapliccurtoprazo
                 si208_vlativocircuestoques = float4 = si208_vlativocircuestoques
                 si208_vlativonaocircumantidovenda = float4 = si208_vlativonaocircumantidovenda
                 si208_vlativocircuativobio = float4 = si208_vlativocircuativobio
                 si208_vlativocircuvpdantecipada = float4 = si208_vlativocircuvpdantecipada
                 si208_vlativonaocircurlp = float4 = si208_vlativonaocircurlp
                 si208_vlativonaocircuinvestimentos = float4 = si208_vlativonaocircuinvestimentos
                 si208_vlativonaocircuimobilizado = float4 = si208_vlativonaocircuimobilizado
                 si208_vlativonaocircuintagivel = float4 = si208_vlativonaocircuintagivel
                 si208_vltotalativo = float4 = si208_vltotalativo
                 ";
   //funcao construtor da classe 
   function cl_bpdcasp102022() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bpdcasp102022"); 
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
      $this->si208_sequencial = ($this->si208_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_sequencial"]:$this->si208_sequencial);
      $this->si208_tiporegistro = ($this->si208_tiporegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_tiporegistro"]:$this->si208_tiporegistro);
      $this->si208_vlativocircucaixaequicaixa = ($this->si208_vlativocircucaixaequicaixa == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_vlativocircucaixaequicaixa"]:$this->si208_vlativocircucaixaequicaixa);
      $this->si208_vlativocircucredicurtoprazo = ($this->si208_vlativocircucredicurtoprazo == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_vlativocircucredicurtoprazo"]:$this->si208_vlativocircucredicurtoprazo);
      $this->si208_vlativocircuinvestapliccurtoprazo = ($this->si208_vlativocircuinvestapliccurtoprazo == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_vlativocircuinvestapliccurtoprazo"]:$this->si208_vlativocircuinvestapliccurtoprazo);
      $this->si208_vlativocircuestoques = ($this->si208_vlativocircuestoques == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_vlativocircuestoques"]:$this->si208_vlativocircuestoques);
      $this->si208_vlativonaocircumantidovenda = ($this->si208_vlativonaocircumantidovenda == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_vlativonaocircumantidovenda"]:$this->si208_vlativonaocircumantidovenda);
      $this->si208_vlativocircuativobio = ($this->si208_vlativocircuativobio == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_vlativocircuativobio"]:$this->si208_vlativocircuativobio);
      $this->si208_vlativocircuvpdantecipada = ($this->si208_vlativocircuvpdantecipada == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_vlativocircuvpdantecipada"]:$this->si208_vlativocircuvpdantecipada);
      $this->si208_vlativonaocircurlp = ($this->si208_vlativonaocircurlp == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_vlativonaocircurlp"]:$this->si208_vlativonaocircurlp);
      $this->si208_vlativonaocircuinvestimentos = ($this->si208_vlativonaocircuinvestimentos == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_vlativonaocircuinvestimentos"]:$this->si208_vlativonaocircuinvestimentos);
      $this->si208_vlativonaocircuimobilizado = ($this->si208_vlativonaocircuimobilizado == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_vlativonaocircuimobilizado"]:$this->si208_vlativonaocircuimobilizado);
      $this->si208_vlativonaocircuintagivel = ($this->si208_vlativonaocircuintagivel == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_vlativonaocircuintagivel"]:$this->si208_vlativonaocircuintagivel);
      $this->si208_vltotalativo = ($this->si208_vltotalativo == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_vltotalativo"]:$this->si208_vltotalativo);
      $this->si208_ano = ($this->si208_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_ano"]:$this->si208_ano);
      $this->si208_periodo = ($this->si208_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_periodo"]:$this->si208_periodo);
      $this->si208_institu = ($this->si208_institu == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_institu"]:$this->si208_institu);
     }else{
       $this->si208_sequencial = ($this->si208_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si208_sequencial"]:$this->si208_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($si208_sequencial){ 
      $this->atualizacampos();
     if($this->si208_tiporegistro == null ){
       $this->erro_sql = " Campo si208_tiporegistro n�o informado.";
       $this->erro_campo = "si208_tiporegistro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si208_vlativocircucaixaequicaixa == null ){
      $this->si208_vlativocircucaixaequicaixa = 0;
  }
  if($this->si208_vlativocircucredicurtoprazo == null ){
      $this->si208_vlativocircucredicurtoprazo = 0;
  }
  if($this->si208_vlativocircuinvestapliccurtoprazo == null ){
      $this->si208_vlativocircuinvestapliccurtoprazo = 0;
  }
  if($this->si208_vlativocircuestoques == null ){
      $this->si208_vlativocircuestoques = 0;
  }
  if($this->si208_vlativonaocircumantidovenda == null ){
   $this->si208_vlativonaocircumantidovenda = 0;
  }
  if($this->si208_vlativocircuativobio == null ){
   $this->si208_vlativocircuativobio = 0;
  }
  if($this->si208_vlativocircuvpdantecipada == null ){
      $this->si208_vlativocircuvpdantecipada = 0;
  }
  if($this->si208_vlativonaocircurlp == null ){
   $this->si208_vlativonaocircurlp = 0;
  }
  if($this->si208_vlativonaocircuinvestimentos == null ){
      $this->si208_vlativonaocircuinvestimentos = 0;
  }
  if($this->si208_vlativonaocircuimobilizado == null ){
      $this->si208_vlativonaocircuimobilizado = 0;
  }
  if($this->si208_vlativonaocircuintagivel == null ){
      $this->si208_vlativonaocircuintagivel = 0;
  }
  if($this->si208_vltotalativo == null ){
      $this->si208_vltotalativo = 0;
  }
  if($this->si208_vlativocircudemaiscredicurtoprazo == null){
     $this->si208_vlativocircudemaiscredicurtoprazo = 0;
  }
   if($si208_sequencial == "" || $si208_sequencial == null ){
       $result = db_query("select nextval('bpdcasp102022_si208_sequencial_seq')");
       if($result==false){
           $this->erro_banco = str_replace("
","",@pg_last_error());
           $this->erro_sql   = "Verifique o cadastro da sequencia: bpdcasp102022_si208_sequencial_seq do campo: si208_sequencial";
           $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
           $this->erro_status = "0";
           return false;
       }
       $this->si208_sequencial = pg_result($result,0,0);
   }else{
       $result = db_query("select last_value from bpdcasp102022_si208_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $si208_sequencial)){
           $this->erro_sql = " Campo si208_sequencial maior que �ltimo n�mero da sequencia.";
           $this->erro_banco = "Sequencia menor que este n�mero.";
           $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
           $this->erro_status = "0";
           return false;
       }else{
           $this->si208_sequencial = $si208_sequencial;
       }
   }
     $sql = "insert into bpdcasp102022(
                                        si208_sequencial
                                        ,si208_tiporegistro
                                        ,si208_vlativocircucaixaequicaixa
                                        ,si208_vlativocircudemaiscredicurtoprazo
                                        ,si208_vlativocircucredicurtoprazo
                                        ,si208_vlativocircuinvestapliccurtoprazo
                                        ,si208_vlativocircuestoques
                                        ,si208_vlativonaocircumantidovenda
                                        ,si208_vlativocircuativobio
                                        ,si208_vlativocircuvpdantecipada
                                        ,si208_vlativonaocircurlp
                                        ,si208_vlativonaocircuinvestimentos
                                        ,si208_vlativonaocircuimobilizado
                                        ,si208_vlativonaocircuintagivel
                                        ,si208_vltotalativo
                                        ,si208_ano
                                        ,si208_periodo
                                        ,si208_institu
                       )
                values (
                                        $this->si208_sequencial
                                        ,$this->si208_tiporegistro
                                        ,$this->si208_vlativocircucaixaequicaixa
                                        ,$this->si208_vlativocircudemaiscredicurtoprazo
                                        ,$this->si208_vlativocircucredicurtoprazo
                                        ,$this->si208_vlativocircuinvestapliccurtoprazo
                                        ,$this->si208_vlativocircuestoques
                                        ,$this->si208_vlativonaocircumantidovenda
                                        ,$this->si208_vlativocircuativobio
                                        ,$this->si208_vlativocircuvpdantecipada
                                        ,$this->si208_vlativonaocircurlp
                                        ,$this->si208_vlativonaocircuinvestimentos
                                        ,$this->si208_vlativonaocircuimobilizado
                                        ,$this->si208_vlativonaocircuintagivel
                                        ,$this->si208_vltotalativo
                                        ,$this->si208_ano
                                        ,$this->si208_periodo
                                        ,$this->si208_institu
                      )";
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("
","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "bpdcasp102022 ($this->si208_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_banco = "bpdcasp102022 j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       }else{
         $this->erro_sql   = "bpdcasp102022 ($this->si208_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\n";
         $this->erro_sql .= "Valores : ".$this->si208_sequencial;
     $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ($si208_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update bpdcasp102022 set ";
     $virgula = "";
     if(trim($this->si208_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_sequencial"])){
      $sql  .= $virgula." si208_sequencial = $this->si208_sequencial ";
      $virgula = ",";
      if(trim($this->si208_sequencial) == null ){
        $this->erro_sql = " Campo si208_sequencial n�o informado.";
        $this->erro_campo = "si208_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si208_tiporegistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_tiporegistro"])){
      $sql  .= $virgula." si208_tiporegistro = $this->si208_tiporegistro ";
      $virgula = ",";
      if(trim($this->si208_tiporegistro) == null ){
        $this->erro_sql = " Campo si208_tiporegistro n�o informado.";
        $this->erro_campo = "si208_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si208_vlativocircucaixaequicaixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativocircucaixaequicaixa"])){
      $sql  .= $virgula." si208_vlativocircucaixaequicaixa = $this->si208_vlativocircucaixaequicaixa ";
      $virgula = ",";
      if(trim($this->si208_vlativocircucaixaequicaixa) == null ){
        $this->erro_sql = " Campo si208_vlativocircucaixaequicaixa n�o informado.";
        $this->erro_campo = "si208_vlativocircucaixaequicaixa";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si208_vlativocircucredicurtoprazo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativocircucredicurtoprazo"])){
      $sql  .= $virgula." si208_vlativocircucredicurtoprazo = $this->si208_vlativocircucredicurtoprazo ";
      $virgula = ",";
      if(trim($this->si208_vlativocircucredicurtoprazo) == null ){
        $this->erro_sql = " Campo si208_vlativocircucredicurtoprazo n�o informado.";
        $this->erro_campo = "si208_vlativocircucredicurtoprazo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si208_vlativocircuinvestapliccurtoprazo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativocircuinvestapliccurtoprazo"])){
      $sql  .= $virgula." si208_vlativocircuinvestapliccurtoprazo = $this->si208_vlativocircuinvestapliccurtoprazo ";
      $virgula = ",";
      if(trim($this->si208_vlativocircuinvestapliccurtoprazo) == null ){
        $this->erro_sql = " Campo si208_vlativocircuinvestapliccurtoprazo n�o informado.";
        $this->erro_campo = "si208_vlativocircuinvestapliccurtoprazo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si208_vlativocircuestoques)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativocircuestoques"])){
      $sql  .= $virgula." si208_vlativocircuestoques = $this->si208_vlativocircuestoques ";
      $virgula = ",";
      if(trim($this->si208_vlativocircuestoques) == null ){
        $this->erro_sql = " Campo si208_vlativocircuestoques n�o informado.";
        $this->erro_campo = "si208_vlativocircuestoques";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si208_vlativonaocircumantidovenda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativonaocircumantidovenda"])){
     $sql  .= $virgula." si208_vlativonaocircumantidovenda = $this->si208_vlativonaocircumantidovenda ";
     $virgula = ",";
     if(trim($this->si208_vlativonaocircumantidovenda) == null ){
       $this->erro_sql = " Campo si208_vlativonaocircumantidovenda n�o informado.";
       $this->erro_campo = "si208_vlativonaocircumantidovenda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       return false;
     }
    }
    if(trim($this->si208_vlativocircuativobio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativocircuativobio"])){
     $sql  .= $virgula." si208_vlativocircuativobio = $this->si208_vlativocircuativobio ";
     $virgula = ",";
     if(trim($this->si208_vlativocircuativobio) == null ){
       $this->erro_sql = " Campo si208_vlativocircuativobio n�o informado.";
       $this->erro_campo = "si208_vlativocircuativobio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       return false;
     }
    }
    if(trim($this->si208_vlativocircuvpdantecipada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativocircuvpdantecipada"])){
      $sql  .= $virgula." si208_vlativocircuvpdantecipada = $this->si208_vlativocircuvpdantecipada ";
      $virgula = ",";
      if(trim($this->si208_vlativocircuvpdantecipada) == null ){
        $this->erro_sql = " Campo si208_vlativocircuvpdantecipada n�o informado.";
        $this->erro_campo = "si208_vlativocircuvpdantecipada";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si208_vlativonaocircurlp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativonaocircurlp"])){
     $sql  .= $virgula." si208_vlativonaocircurlp = $this->si208_vlativonaocircurlp ";
     $virgula = ",";
     if(trim($this->si208_vlativonaocircurlp) == null ){
       $this->erro_sql = " Campo si208_vlativonaocircurlp n�o informado.";
       $this->erro_campo = "si208_vlativonaocircurlp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       return false;
     }
    }
    if(trim($this->si208_vlativonaocircuinvestimentos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativonaocircuinvestimentos"])){
      $sql  .= $virgula." si208_vlativonaocircuinvestimentos = $this->si208_vlativonaocircuinvestimentos ";
      $virgula = ",";
      if(trim($this->si208_vlativonaocircuinvestimentos) == null ){
        $this->erro_sql = " Campo si208_vlativonaocircuinvestimentos n�o informado.";
        $this->erro_campo = "si208_vlativonaocircuinvestimentos";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si208_vlativonaocircuimobilizado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativonaocircuimobilizado"])){
      $sql  .= $virgula." si208_vlativonaocircuimobilizado = $this->si208_vlativonaocircuimobilizado ";
      $virgula = ",";
      if(trim($this->si208_vlativonaocircuimobilizado) == null ){
        $this->erro_sql = " Campo si208_vlativonaocircuimobilizado n�o informado.";
        $this->erro_campo = "si208_vlativonaocircuimobilizado";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si208_vlativonaocircuintagivel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativonaocircuintagivel"])){
      $sql  .= $virgula." si208_vlativonaocircuintagivel = $this->si208_vlativonaocircuintagivel ";
      $virgula = ",";
      if(trim($this->si208_vlativonaocircuintagivel) == null ){
        $this->erro_sql = " Campo si208_vlativonaocircuintagivel n�o informado.";
        $this->erro_campo = "si208_vlativonaocircuintagivel";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si208_vltotalativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si208_vltotalativo"])){
      $sql  .= $virgula." si208_vltotalativo = $this->si208_vltotalativo ";
      $virgula = ",";
      if(trim($this->si208_vltotalativo) == null ){
        $this->erro_sql = " Campo si208_vltotalativo n�o informado.";
        $this->erro_campo = "si208_vltotalativo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
     $sql .= " where ";
     if($si208_sequencial!=null){
       $sql .= " si208_sequencial = $this->si208_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->si208_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1009368,'$this->si208_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["si208_sequencial"]) || $this->si208_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010202,1009368,'".AddSlashes(pg_result($resaco,$conresaco,'si208_sequencial'))."','$this->si208_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["si208_tiporegistro"]) || $this->si208_tiporegistro != "")
             $resac = db_query("insert into db_acount values($acount,1010202,1009369,'".AddSlashes(pg_result($resaco,$conresaco,'si208_tiporegistro'))."','$this->si208_tiporegistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativocircucaixaequicaixa"]) || $this->si208_vlativocircucaixaequicaixa != "")
             $resac = db_query("insert into db_acount values($acount,1010202,1009371,'".AddSlashes(pg_result($resaco,$conresaco,'si208_vlativocircucaixaequicaixa'))."','$this->si208_vlativocircucaixaequicaixa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativocircucredicurtoprazo"]) || $this->si208_vlativocircucredicurtoprazo != "")
             $resac = db_query("insert into db_acount values($acount,1010202,1009372,'".AddSlashes(pg_result($resaco,$conresaco,'si208_vlativocircucredicurtoprazo'))."','$this->si208_vlativocircucredicurtoprazo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativocircuinvestapliccurtoprazo"]) || $this->si208_vlativocircuinvestapliccurtoprazo != "")
             $resac = db_query("insert into db_acount values($acount,1010202,1009373,'".AddSlashes(pg_result($resaco,$conresaco,'si208_vlativocircuinvestapliccurtoprazo'))."','$this->si208_vlativocircuinvestapliccurtoprazo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativocircuestoques"]) || $this->si208_vlativocircuestoques != "")
             $resac = db_query("insert into db_acount values($acount,1010202,1009374,'".AddSlashes(pg_result($resaco,$conresaco,'si208_vlativocircuestoques'))."','$this->si208_vlativocircuestoques',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativocircuvpdantecipada"]) || $this->si208_vlativocircuvpdantecipada != "")
             $resac = db_query("insert into db_acount values($acount,1010202,1009375,'".AddSlashes(pg_result($resaco,$conresaco,'si208_vlativocircuvpdantecipada'))."','$this->si208_vlativocircuvpdantecipada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativonaocircuinvestimentos"]) || $this->si208_vlativonaocircuinvestimentos != "")
             $resac = db_query("insert into db_acount values($acount,1010202,1009380,'".AddSlashes(pg_result($resaco,$conresaco,'si208_vlativonaocircuinvestimentos'))."','$this->si208_vlativonaocircuinvestimentos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativonaocircuimobilizado"]) || $this->si208_vlativonaocircuimobilizado != "")
             $resac = db_query("insert into db_acount values($acount,1010202,1009381,'".AddSlashes(pg_result($resaco,$conresaco,'si208_vlativonaocircuimobilizado'))."','$this->si208_vlativonaocircuimobilizado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["si208_vlativonaocircuintagivel"]) || $this->si208_vlativonaocircuintagivel != "")
             $resac = db_query("insert into db_acount values($acount,1010202,1009382,'".AddSlashes(pg_result($resaco,$conresaco,'si208_vlativonaocircuintagivel'))."','$this->si208_vlativonaocircuintagivel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["si208_vltotalativo"]) || $this->si208_vltotalativo != "")
             $resac = db_query("insert into db_acount values($acount,1010202,1009383,'".AddSlashes(pg_result($resaco,$conresaco,'si208_vltotalativo'))."','$this->si208_vltotalativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("
","",@pg_last_error());
       $this->erro_sql   = "bpdcasp102022 nao Alterado. Alteracao Abortada.\n";
         $this->erro_sql .= "Valores : ".$this->si208_sequencial;
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "bpdcasp102022 nao foi Alterado. Alteracao Executada.\n";
         $this->erro_sql .= "Valores : ".$this->si208_sequencial;
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\n";
         $this->erro_sql .= "Valores : ".$this->si208_sequencial;
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($si208_sequencial=null,$dbwhere=null) { 

     $sql = " delete from bpdcasp102022
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($si208_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " si208_sequencial = $si208_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("
","",@pg_last_error());
       $this->erro_sql   = "bpdcasp102022 nao Exclu�do. Exclus�o Abortada.\n";
       $this->erro_sql .= "Valores : ".$si208_sequencial;
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "bpdcasp102022 nao Encontrado. Exclus�o n�o Efetuada.\n";
         $this->erro_sql .= "Valores : ".$si208_sequencial;
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
         $this->erro_sql .= "Valores : ".$si208_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:bpdcasp102022";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $si208_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bpdcasp102022 ";
     $sql2 = "";
     if($dbwhere==""){
       if($si208_sequencial!=null ){
         $sql2 .= " where bpdcasp102022.si208_sequencial = $si208_sequencial "; 
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
   function sql_query_file ( $si208_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bpdcasp102022 ";
     $sql2 = "";
     if($dbwhere==""){
       if($si208_sequencial!=null ){
         $sql2 .= " where bpdcasp102022.si208_sequencial = $si208_sequencial "; 
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
