$(document).ready(() => {

	$('#documentacao').on('click', () => {

		//$('#pagina').load('documentacao.html ')
		/*
		$.get('documentacao.html', data => {
			$('#pagina').html(data)
		})
		*/
		$.post('documentacao.html', data => {
			$('#pagina').html(data)
		})
	})

	$('#suporte').on('click', () => {
		$.post('suporte.html', data => {
			$('#pagina').html(data)
		})
		
	})

	$('#nova_venda').on('click', () => {
		$.post('nova_venda.html', data => {
			$('#pagina').html(data)
		})
		
	})

	$('#nova_despesa').on('click', () => {
		$.post('nova_despesa.html', data => {
			$('#pagina').html(data)
		})
		
	})

	//ajax
/*	
	$('#competencia').on('change', e => {

		let competencia = $(e.target).val()	

		$.ajax({
			type: 'GET',
			url: 'app.php',
			data: `competencia=${competencia}`,
			success: dados => {console.log(dados)},
			error: erro => {console.log(erro)}
		})
})
*/	


	$('#pesquisa').on('click', e => {

		let data_inicio = $('#competencia_inicio').val()
		let data_fim = $('#competencia_fim').val()		

		$.ajax({
			type: 'GET',
			url: 'app.php',
			data: `competencia_inicio=${data_inicio}&competencia_fim=${data_fim}`,
			dataType: 'json',
			success: dados => {
				$('#card_numero_vendas').html(dados.numeroVendas)
				$('#card_total_vendas').html(`R$ ${dados.totalVendas}`)
				$('#card_clientes_ativos').html(dados.clientesAtivos)
				$('#card_clientes_inativos').html(dados.clientesInativos)
				$('#card_reclamacoes').html(dados.reclamacoes)
				$('#card_elogios').html(dados.elogios)
				$('#card_sugestoes').html(dados.sugestoes)
				$('#card_despesas').html(`R$ ${dados.despesas}`)
				$('#card_faturamento_liquido').html('R$ ' + (dados.totalVendas - dados.despesas))
			},
			error: erro => {console.log(erro)}
		})
})
	
})