<?
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("db_calendario.php");
include("dbforms/db_funcoes.php");
include("classes/db_calend_classe.php");
include("classes/db_clientes_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_tarefacadsituacao_classe.php");
include("classes/db_db_proced_classe.php");


$clclientes          = new cl_clientes;
$cldb_usuarios       = new cl_db_usuarios;
$cltarefacadsituacao = new cl_tarefacadsituacao;
$clrotulo            = new rotulocampo; 
$cldb_proced         = new cl_db_proced;
$cale                = new db_calendario;
$cale->pagina_alvo = "con2_calendario002.php";
$cale->pagina_alvo_relatorio = "con2_calendario003.php";
$cale->monta_javascript("js_troca_cliente",""," location.href = 'ate3_calendariovisita001.php?cliente='+document.form1.cliente.value+'&tecnico='+document.form1.tecnico.value+'&situacao='+document.form1.situacao.value+'&at40_sequencial='+document.form1.at40_sequencial.value+'&tipo_pesquisa='+document.form1.tipo_pesquisa.value+'&at30_codigo='+document.form1.at30_codigo.value;");
$cale->monta_javascript("js_pesquisa_tarefa",""," js_OpenJanelaIframe('','db_iframe_tarefa','func_tarefa.php?funcao_js=parent.js_mostra_tarefa|at40_sequencial','Pesquisa',true) ");
$cale->monta_javascript("js_mostra_tarefa","tarefa"," document.form1.at40_sequencial.value = tarefa; db_iframe_tarefa.hide(); js_troca_cliente();");
$cale->monta_inicio_pagina(true);
if(isset($cliente)){
  global $cliente,$tecnico,$situacao,$at40_sequencial,$tipo_pesquisa,$at30_codigo;	
}
$result          = $clclientes->sql_record($clclientes->sql_query_file());
$result_tecnico  = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(null,'*','nome'));
$result_situacao = $cltarefacadsituacao->sql_record($cltarefacadsituacao->sql_query_file());
$result_proced   = $cldb_proced->sql_record($cldb_proced->sql_query_file());
echo "<form name='form1' method='post' ><table><tr>
      <td><strong>Cliente:</strong></td><td>";
db_selectrecord("cliente",$result,true,2,"","","","0"," js_troca_cliente(); ");
db_selectrecord("tecnico",$result_tecnico,true,2,"","","","0"," js_troca_cliente(); ");
db_selectrecord("situacao",$result_situacao,true,2,"","","","0"," js_troca_cliente(); ");
echo "</td></tr>";
$clrotulo->label('at40_sequencial');
echo "<tr><td>";
db_ancora("$Lat40_sequencial"," js_pesquisa_tarefa() ",2);
echo "</td><td>";
db_input("at40_sequencial",10,$Iat40_sequencial,true,'text',2," onchange='js_troca_cliente()'");
$mata = array("1"=>"Tarefas n�o finalizadas","2"=>"Tarefas executadas","3"=>"Tarefas encerradas");
db_select("tipo_pesquisa",$mata,true,2," onchange='js_troca_cliente()'");
db_selectrecord("at30_codigo",$result_proced,true,2,"","","","0"," js_troca_cliente(); ");
echo "</td></tr>";
echo "</table></form>";
if(!isset($cliente)){
  global $cliente;	
//  $cliente = pg_result($result,0,0);
}

  $cale->sql_cruzamento = " 
  select DISTINCT  case when at01_nomecli is null then '999-Outros' else at01_codcli||'-'||at01_nomecli end as at01_nomecli,
       at13_dia as dl_datacalend,
       case when substr(at13_horaini,1,2)::integer < 12 then 'm' else 't' end as turno,
       nome::text  
  from tarefa 
     left join tarefaclientes    on at70_tarefa = at40_sequencial 
     left join clientes          on at01_codcli = at70_cliente 
     left join tarefasituacao    on at47_tarefa = at40_sequencial 
     left join tarefacadsituacao on at46_codigo = at47_situacao
     left join tarefaenvol       on at45_tarefa = at40_sequencial and at45_perc = 100
     left join db_usuarios       on id_usuario = at45_usuario 
     left join tarefaproced      on at41_tarefa = at40_sequencial
     left join tarefa_agenda     on at13_tarefa = at40_sequencial

  where at41_proced in ( 9 , 16)   
  ";
  $cale->sql_segundoacesso = " 
   select DISTINCT at01_codcli,at13_dia  as dl_datacalend,at01_nomecli,at40_diaini,at40_sequencial,
                   at40_obs,at46_descr,nome::text,at45_perc,at40_progresso  
  from tarefa 
     left join tarefaclientes    on at70_tarefa = at40_sequencial 
     left join clientes          on at01_codcli = at70_cliente 
     left join tarefasituacao    on at47_tarefa = at40_sequencial 
     left join tarefacadsituacao on at46_codigo = at47_situacao
     left join tarefaenvol       on at45_tarefa = at40_sequencial and at45_perc = 100
     left join db_usuarios       on id_usuario = at45_usuario 
     left join tarefaproced      on at41_tarefa = at40_sequencial
     left join tarefa_agenda     on at13_tarefa = at40_sequencial
  where at41_proced in (9,16)   
  ";
  $filtro = "";
  if(isset($cliente) ){
    if( $at40_sequencial > 0 ){
      $filtro .= " and at40_sequencial = $at40_sequencial";
    }else{
      if($tipo_pesquisa == 3){
        $filtro .= " and at47_situacao = 3 ";
      }else{
        $filtro .= " and at47_situacao <> 3 ";
      }
      if(isset($cliente) && $cliente > 0 ){
        $filtro .= " and at70_cliente = $cliente";
      }
      if(isset($tecnico) && $tecnico > 0 ){
        $filtro .= " and at45_usuario = $tecnico";
      }
      if(isset($situacao) && $situacao > 0 ){
        $filtro .= " and at46_codigo = $situacao";
      }
      if(isset($at30_codigo) && $at30_codigo > 0 ){
        $v .= " and at41_proced = $at30_codigo";
      }
    }
  }

  $cale->sql_segundoacesso .= $filtro;
  $cale->sql_cruzamento    .= $filtro;

$cale->monta_calendario_semanal_turno(@$exercicio,@$metodo,@$data);
$cale->monta_fim_pagina(false);
