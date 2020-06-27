<?php

/**
 * Classe que faz interface com cliente Subversion (linha de comando).
 *
 * @author Eduardo S. Luz
 * @created 2008-10-05
 * @version 0.4
 */
class SVNInterface {
	var $pathBinSVN;
	var $pathRepositorio;
	var $pathWorkSpace;
	var $urlRepositorio;


	const SVN_ERR_REPOSITORIO_EXISTENTE 		= 1;
	const SVN_ERR_PATH_INCORRETA 				= 2;
	const SVN_ERR_WORKSPACE_NAO_INICIALIZADA 	= 3;
	const SVN_ERR_REPOSITORIO_NAO_ENCONTRADO 	= 4;
	const SVN_ERR_COMMIT 						= 5;


	function executa($cmd,$pathInicial=null) {
		$resultado = array();

		$handle = popen("$cmd 2>&1", 'r');
		while ($read = fread($handle, 20096)) {
			$resultado[] = $read;
		}
		pclose($handle);
		flush();

		return $resultado;
	}


	/**
	 * Criar a estrutura de diretórios e inicializá-la como repositório do svn.
	 *
	 * @param string $nomeDir
	 */
	function criaRepositorio($nomeDir) {

		if (!file_exists($this->pathRepositorio . '/' . $nomeDir)) {
			if (!mkdir($this->pathRepositorio . '/' . $nomeDir)) {
				die	('Erro ao criar o diretório '. $this->pathRepositorio . '/' . $nomeDir);
			}
		}

		$comando = $this->pathBinSVN . '/svnadmin.exe';
		$comando .= ' create ' . $this->pathRepositorio . '/' . $nomeDir;

		$resultado = join("\n",$this->executa($comando));
		$i = strpos($resultado,'exists and is non-empty');
		if ($i !== false) {
			throw new Exception('Repositorio ja existe',SVNInterface::SVN_ERR_REPOSITORIO_EXISTENTE);
		}
		return true;

	} // eof criaRepositorio


	function checkDir($pathCompleta) {
		if (!file_exists($pathCompleta)) {
			throw new Exception('Path inexistente: ' . $pathCompleta,SVNInterface::SVN_ERR_PATH_INCORRETA);
		}
	}


	/**
	 * Verifica se o repositório existe e está inicializado.
	 *
	 * @param string $nomeDir
	 * @return boolean - true se o repositório existe e estpa inicializado.
	 * @throws Exception - caso o diretório não exista
	 */
	function checkRepositorio($nomeDir) {
		$this->checkDir($this->pathRepositorio . '/' . $nomeDir);

		try {
			$this->checkDir($this->pathRepositorio . '/' . $nomeDir . '/locks');
			return true;
		} catch (Exception $e) {
			return false;
		}
	}



	function checkWorkspace($nomeDir) {
		$this->checkDir($this->pathWorkSpace . '/' . $nomeDir);

		chdir($this->pathWorkSpace . '/' . $nomeDir);

		$comando = $this->pathBinSVN . '/svn.exe status ' . $this->pathWorkSpace . '/' . $nomeDir;

		$resultado = join("\n",$this->executa($comando));
		$i = strpos($resultado,'is not a working copy');
		if ($i !== false) {
			throw new Exception('Workspace nao-inicializada.',SVNInterface::SVN_ERR_WORKSPACE_NAO_INICIALIZADA);
		}
		return true;
	}


	function checkout($nomeDir) {
		$this->checkDir($this->pathWorkSpace . '/' . $nomeDir);
		$comando = $this->pathBinSVN . '/svn.exe checkout ' . $this->urlRepositorio . ' ' . $this->pathWorkSpace . '/' . $nomeDir;
		$resultado = join("\n",$this->executa($comando));
		$i = strpos($resultado,'Unable to open');
		if ($i !== false) {
			throw new Exception('Repositorio nao encontrado: ' . $this->urlRepositorio , SVNInterface::SVN_ERR_REPOSITORIO_NAO_ENCONTRADO);
		}
		return true;
	}


	function commit($nomeDir,$mensagem='') {
		$this->checkDir($this->pathWorkSpace . '/' . $nomeDir);
		$comando = $this->pathBinSVN . '/svn.exe commit -m '.  $mensagem.' ' . $this->pathWorkSpace . '/' . $nomeDir;
		$resultado = join("\n",$this->executa($comando));
		$i = strpos($resultado,'Committed revision');
		if ($i === false) {
			throw new Exception('Erro ao commitar: ' . $resultado , SVNInterface::SVN_ERR_COMMIT);
		}
		return true;
	}

	function update($nomeDir) {
		$this->checkDir($this->pathWorkSpace . '/' . $nomeDir);
		$comando = $this->pathBinSVN . '/svn.exe update '.$this->pathWorkSpace . '/' . $nomeDir;
		$resultado = join("\n",$this->executa($comando));
/*		$i = strpos($resultado,'Committed revision');
		if ($i === false) {
			throw new Exception('Erro ao commitar: ' . $resultado , SVNInterface::SVN_ERR_COMMIT);
		}
*/
		return true;
	}

	function addAll($nomeDir) {
       	$resultado = array();

		$this->checkDir($this->pathWorkSpace . '/' . $nomeDir);
		$comando = $this->pathBinSVN . '/svn.exe add '.$this->pathWorkSpace . '/' . $nomeDir.'/*.* ';
		$resultado = join("\n",$this->executa($comando));
		return $resultado;
	}


} // eof SVNInterface


?>