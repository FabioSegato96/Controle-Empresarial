<?php

//classe app


class Dashboard{

	public $data_inicio;
	public $data_fim;
	public $numeroVendas;
	public $totalVendas;
	public $clientesAtivos;
	public $clientesInativos;
	public $reclamacoes;
	public $elogios;
	public $sugestoes;
	public $despesas;
	
	public function __get($atributo){
		return $this->$atributo;
	}

	public function __set($atributo, $valor){
		$this->$atributo = $valor;
		return $this;
	}	
}

//classe conexao

class Conexao{

	private $host = 'localhost';
	private $dbname = 'dashboard';
	private $user = 'root';
	private $pass = '';

	public function conectar(){
		try{

			$conexao = new PDO(
				"mysql:host=$this->host;dbname=$this->dbname",
				"$this->user",
				"$this->pass"
			);

			$conexao->exec('set charset utf8');
			return $conexao;

		} catch (PDOException $e) {
			echo '<p>' . $e->getMessage() . '</p>';
		}
	}
}

class Bd {

	private $conexao;
	private $dashboard;

	public function __construct(Conexao $conexao, Dashboard $dashboard){
		$this->conexao = $conexao->conectar();
		$this->dashboard = $dashboard;
	}

	public function getNumeroVendas(){
		$query = "select count(*) as numero_vendas from tb_vendas where data_venda between :data_inicio and :data_fim";
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC)['numero_vendas'];
	}
	public function getTotalVendas(){
		$query = "select sum(total) as total_vendas from tb_vendas where data_venda between :data_inicio and :data_fim";
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC)['total_vendas'];
	}
	public function getClienteAtivo(){
		$query = "select count(cliente_ativo) from tb_clientes where cliente_ativo = 1";
		$stmt = $this->conexao->prepare($query);				
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC)['count(cliente_ativo)'];
	}
	public function getClienteInativo(){
		$query = "select count(cliente_ativo) from tb_clientes where cliente_ativo = 0";
		$stmt = $this->conexao->prepare($query);				
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC)['count(cliente_ativo)'];
	}
	public function getReclamacoes(){
		$query = "select count(tipo_contato) from tb_contatos where tipo_contato = 1";
		$stmt = $this->conexao->prepare($query);				
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC)['count(tipo_contato)'];
	}
	public function getElogios(){
		$query = "select count(tipo_contato) from tb_contatos where tipo_contato = 2";
		$stmt = $this->conexao->prepare($query);				
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC)['count(tipo_contato)'];
	}
	public function getSugestoes(){
		$query = "select count(tipo_contato) from tb_contatos where tipo_contato = 3";
		$stmt = $this->conexao->prepare($query);				
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC)['count(tipo_contato)'];
	}
	public function getDespesas(){
		$query = "select sum(total) as total_despesas from tb_despesas where data_despesa between :data_inicio and :data_fim";
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC)['total_despesas'];
	}
}

//logica do script
$dashboard = new Dashboard();

$conexao = new Conexao();

$competencia_inicio = $_GET['competencia_inicio'];
$competencia_fim = $_GET['competencia_fim'];


$dashboard->__set('data_inicio', $competencia_inicio);
$dashboard->__set('data_fim', $competencia_fim);

$bd = new Bd($conexao, $dashboard);

$dashboard->__set('numeroVendas', $bd->getNumeroVendas());
$dashboard->__set('totalVendas', $bd->getTotalVendas());
$dashboard->__set('clientesAtivos', $bd->getClienteAtivo());
$dashboard->__set('clientesInativos', $bd->getClienteInativo());
$dashboard->__set('reclamacoes', $bd->getReclamacoes());
$dashboard->__set('elogios', $bd->getElogios());
$dashboard->__set('sugestoes', $bd->getSugestoes());
$dashboard->__set('despesas', $bd->getDespesas());


//print_r($dashboard)
echo json_encode($dashboard);







?>