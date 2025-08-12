jQuery(document).ready(function() { 
	$('.valor').bind('keyup', function(e){
		var soma = 0;
		$('.valor').each(function( indice, item ){
			var valor = $(item).val();
			valor = valor.replace('.','');
			valor = valor.replace(',','.');
			valor = parseFloat(valor);
			if ( !isNaN( valor ) ) {
				soma += valor;
			}
		});
		soma = soma.toFixed(2); 
		var resultado = soma.toString()
		resultado = 'R$ ' + resultado.replace('.',',');
		$('#valor_total').val( resultado );
	});
	$('.valor').bind('keypress', function(e){
		var sep = 0;  
		var SeparadorMilesimo = '.';  
		var SeparadorDecimal = ',';  
		var key = '';  
		var i = j = 0;  
		var len = len2 = 0;  
		var strCheck = '0123456789';  
		var aux = aux2 = '';  
		var whichCode = (window.Event) ? e.which : e.keyCode;  
		if (whichCode == 13) return true;  
		key = String.fromCharCode(whichCode); // Valor para o código da Chave  
		if (strCheck.indexOf(key) == -1) return false; // Chave inválida  
		len = this.value.length;  
		if(len < 10)
		{
			for(i = 0; i < len; i++)  
				if ((this.value.charAt(i) != '0') && (this.value.charAt(i) != SeparadorDecimal)) break;  
			aux = '';  
			for(; i < len; i++)  
				if (strCheck.indexOf(this.value.charAt(i))!=-1) aux += this.value.charAt(i);  
			aux += key;  
			len = aux.length;  
			if (len == 0) this.value = '';  
			if (len == 1) this.value = '0'+ SeparadorDecimal + '0' + aux;  
			if (len == 2) this.value = '0'+ SeparadorDecimal + aux;  
			if (len > 2) {  
				aux2 = '';  
				for (j = 0, i = len - 3; i >= 0; i--) {  
					if (j == 3) {  
						aux2 += SeparadorMilesimo;  
						j = 0;  
					}  
					aux2 += aux.charAt(i);  
					j++;  
				}  
				this.value = '';  
				len2 = aux2.length;  
				for (i = len2 - 1; i >= 0; i--)  
				this.value += aux2.charAt(i);  
				this.value += SeparadorDecimal + aux.substr(len - 2, len);  
			}  
		}
		return false;
		});
});