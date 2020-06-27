<?
/**
 * @author Eduardo S. Luz
 */

	include_once('svni.class.php');


	// nome do projeto
	$alias = 'projeto_teste';

	// Diretorio onde ficam os arquivos para alteraзгo
	$root = '/projetos';

	$if = new SVNInterface();
	$if->pathBinSVN = '/subversion/bin';
	$if->pathRepositorio = '/subversion/repositorios';
	$if->pathWorkSpace = $root;
	$if->urlRepositorio = 'file:///subversion/repositorios/' . $alias;

	try {
		$if->criaRepositorio($alias);
		$msg[] = "Repositorio criado com sucesso";
	} catch (Exception $e) {
		$msg[] = "Erro ao criar o repositуrio: " . $e->getMessage() ;
	}

	try {
		$if->checkout($alias);
		$msg[] = 'Efetuando o checkout inicial';
	} catch (Exception $e) {
		$msg[] = "Erro ao efetuar o checkout inicial: " . $e->getMessage();
	}


	try {
		$if->addAll($alias);
		$msg[] = 'Efetuando o add inicial';
	} catch (Exception $e) {
		$msg[] = "Erro ao efetuar o add inicial: " . $e->getMessage();
	}

?>