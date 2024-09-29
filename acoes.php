<?php
	session_start();
	require 'conexao.php';

	// FUNCIONÁRIO
	if (isset($_POST['create_funcionario'])) {
		$cpf = $_POST['cpf'];
		$nome = $_POST['nome'];
		$datanascimento = $_POST['datanascimento'];
		$endereco = $_POST['endereco'];
		$sexo = $_POST['sexo'];
		$salario = $_POST['salario'];
		$email = $_POST['email'];
		$senha = md5($_POST['senha']);
		
		$sql = "INSERT INTO funcionario 
					(Cpf, Nome, DataNascimento, Endereco, Sexo, Salario, Email, Senha) 
				VALUES ('{$cpf}', '{$nome}', '{$datanascimento}', 
					'{$endereco}', '{$sexo}', '{$salario}', '{$email}', '{$senha}')";
		
		//print($sql);
		
		$res = $conn->query($sql);
		if ($res==true) {
			header('Location: home.php?page=funcionario-listar');
		} else {
			print "<script>alert('Não foi possível cadastrar o funcionário');</script>";
			print "<script>location.href='?page=funcionario-create';</script>";
		}
		exit;
		
	}
	//Editar funcionario
	if (isset($_POST['edit_funcionario'])) {
		$cpf = $_POST['id'];
		$nome = $_POST['nome'];
		$datanascimento = $_POST['datanascimento'];
		$endereco = $_POST['email'];
		$sexo = $_POST['sexo'];
		$salario = $_POST['salario'];
		$email = $_POST['email'];
		$senha = md5($_POST['senha']);
		
		$sql = "UPDATE funcionario SET 
					Nome = '{$nome}', 
					DataNascimento = '{$datanascimento}',
					Endereco = '{$endereco}', 
					Sexo = '{$sexo}', 
					Salario = '{$salario}', 
					Email = '{$email}', 
					Senha = '{$senha}' 
				WHERE Cpf = '{$cpf}';";
		
		//print_r($_POST);
		//print('CPF: '. $_POST['cpf']);
		//print($sql);
		
		$res = $conn->query($sql);
		if ($res==true) {
			header('Location: home.php?page=funcionario-listar');
		} else {
			print "<script>alert('Não foi possível editar o cadastro do funcionário');</script>";
			print "<script>location.href='?page=funcionario-edit';</script>";
		}
		exit;
	}
?>