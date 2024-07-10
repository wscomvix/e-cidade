
performsAutoComplete = function(oAjax) 
{
    
    var oRetorno = JSON.parse(oAjax.responseText);
    "use strict"; 
   
    // Obter elementos do DOM com base nas propriedades do objeto oRetorno
    let inputFieldDescription = document.getElementById(oRetorno.inputField);
    let inputFieldCode        = document.getElementById(oRetorno.inputCodigo);
    let ulFieldDiv            = document.getElementById(oRetorno.ulField);
  
    inputFieldDescription.addEventListener('input', changeAutoComplete);
    ulFieldDiv.addEventListener('click', selectItem);
  
    function changeAutoComplete({ target }) {
      let data = target.value;
      var divfechar = ulFieldDiv.style;
      ulFieldDiv.innerHTML = ``;
  
      if (data.length > 2) {
      
        let autoCompleteValues = autoComplete(data);
        autoCompleteValues.forEach(value => { addItem(value); });
        divfechar.display = 'block';
      }
  
      if (data.length == 0) {
        divfechar.display = 'none';
        inputFieldCode.value = '';
      }
    }
    function autoComplete(inputValue) {
      
      inputValue = removerAcentos(inputValue);
     
      let destination = [];
      for (var i = 0; i < oRetorno.oDados.length; i++) {
        if (oRetorno.oDados[i].campo3) {
          destination.push(oRetorno.oDados[i].campo1 + "  -  " + oRetorno.oDados[i].campo2 + "  -  " + oRetorno.oDados[i].campo3);
        } else {
          destination.push(oRetorno.oDados[i].campo1 + "  -  " + oRetorno.oDados[i].campo2);
        }
      }
      return destination.filter(
        (value) => value.toLowerCase().includes(inputValue.toLowerCase())
      );
    }
  
    function addItem(value) {
      ulFieldDiv.innerHTML = ulFieldDiv.innerHTML + `<li>${value}</li>`;
    }
  
    // Fun��o para selecionar um item da ulFieldDiv quando o usu�rio clica nele
    function selectItem({ target }) {
      if (target.tagName === 'LI') {
        var dados = target.textContent.split("  -  ");
        inputFieldDescription.value = dados[1]; // Atualizar o valor do inputFieldDescription com o segundo campo
        inputFieldCode.value = dados[0]; // Atualizar o valor do inputFieldCode com o primeiro campo
  
        // Limpar a ulFieldDiv e ocult�-la ap�s a sele��o do item
        ulFieldDiv.innerHTML = ``;
        var divfechar = ulFieldDiv.style;
        divfechar.display = 'none'; 
      }
      
    }

    function removerAcentos(str) {
      const acentosMap = {
        '�': 'a', '�': 'a', '�': 'a', '�': 'a', '�': 'a', '�': 'e', '�': 'e', '�': 'e', '�': 'e',
        '�': 'i', '�': 'i', '�': 'i', '�': 'i', '�': 'o', '�': 'o', '�': 'o', '�': 'o', '�': 'o',
        '�': 'u', '�': 'u', '�': 'u', '�': 'u', '�': 'c', '�': 'A', '�': 'A', '�': 'A', '�': 'A',
        '�': 'A', '�': 'E', '�': 'E', '�': 'E', '�': 'E', '�': 'I', '�': 'I', '�': 'I', '�': 'I',
        '�': 'O', '�': 'O', '�': 'O', '�': 'O', '�': 'O', '�': 'U', '�': 'U', '�': 'U', '�': 'U',
        '�': 'C'
      };
    
       return str.replace(/[����������������������������������������������]/g, (match) => acentosMap[match]);
    }
     
  };
  