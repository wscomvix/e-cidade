<?php

$campos  = " c240_sequencial, ";
$campos .= " c240_coddipr, ";
$campos .= " c240_tipoente, ";
$campos .= " c240_datasicom, ";
$campos .= " (ARRAY['Janeiro', 'Fevereiro', 'Mar�o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'])[c240_mescompetencia] AS c240_mescompetencia, ";
$campos .= " c240_exerciciocompetencia, ";
$campos .= " (ARRAY['Fundo em Capitaliza��o (Plano Previdenci�rio)', 'Fundo em Reparti��o (Plano Financeiro)', 'Responsabilidade do tesouro municipal'])[c240_tipofundo] as c240_tipofundo, ";
$campos .= " (ARRAY['Aporte para amortiza��o d�ficit atuarial', 'Transfer�ncia para cobertura insufici�ncia financeiro', 'Transfer�ncia de recursos para pagamento de despesas administrativas', 'Transfer�ncia para pagamento de beneficios de responsabilidade do tesouro', 'Outros aportes ou transfer�ncias'])[c240_tipoaporte] as c240_tipoaporte, ";
$campos .= " c240_descricao, ";
$campos .= " c240_atonormativo, ";
$campos .= " c240_exercicioatonormativo, ";
$campos .= " c240_valoraporte ";