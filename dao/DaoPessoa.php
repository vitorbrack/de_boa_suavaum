<?php

require_once 'C:/xampp/htdocs/PHPMatutinoPDO2Proc/bd/Conecta.php';
require_once 'C:/xampp/htdocs/PHPMatutinoPDO2Proc/model/Pessoa.php';
require_once 'C:/xampp/htdocs/PHPMatutinoPDO2Proc/model/Endereco.php';
require_once 'C:/xampp/htdocs/PHPMatutinoPDO2Proc/model/Mensagem.php';

class DaoPessoa {

    public function inserir(Pessoa $pessoa)
    {
        $conn = new Conecta();
        $msg = new Mensagem();
        $conecta = $conn->conectadb();
        if ($conecta) {
            $nomePessoa = $pessoa->getNome();
            $logradouro = $pessoa->getFkEndereco()->getLogradouro();
            $complemento = $pessoa->getFkEndereco()->getComplemento();
            $bairro = $pessoa->getFkEndereco()->getBairro();
            $cidade = $pessoa->getFkEndereco()->getCidade();
            $uf = $pessoa->getFkEndereco()->getUf();
            $cep = $pessoa->getFkEndereco()->getCep();
            $cpf = $pessoa->getCpf();
            $dtNasc = $pessoa->getDtNasc();
            $email = $pessoa->getEmail();
            $login = $pessoa->getLogin();
            $senha = $pessoa->getSenha();
            $perfil = $pessoa->getPerfil();

            //$msg->setMsg("$logradouro, $complemento, $cep");
            try {
                $conecta->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //processo para pegar o idendereco da tabela endereco, conforme 
                //o cep, o logradouro e o complemento informado.
                $st = $conecta->prepare("call iCadastroPessoa(
                    ?,?,?,?,?,?,?,?,?,?,?,?,?,@)");
                $st->bindParam(1, $nomePessoa);
                $st->bindParam(2, $dtNasc);
                $st->bindParam(3, $login);
                $st->bindParam(4, $senha);
                $st->bindParam(5, $perfil);
                $st->bindParam(6, $email);
                $st->bindParam(7, $cpf);
                $st->bindParam(8, $cep);
                $st->bindParam(9, $logradouro);
                $st->bindParam(10, $complemento);
                $st->bindParam(11, $bairro);
                $st->bindParam(12, $cidade);
                $st->bindParam(13, $uf);
                
                if ($st->execute()) {
                    $linha = $st->fetch(PDO::FETCH_OBJ);
                    $msg->setMsg("<p style='color: green;'>"
                    .$linha->msg."</p>");
                }else{
                    $msg->setMsg("<p style='color: red;'>Erro no cadastramento.</p>");
                }
            } catch (PDOException $ex) {
                $msg->setMsg(var_dump($ex->errorInfo));
            }
        } else {
            $msg->setMsg("<p style='color: red;'>"
                . "Erro na conexão com o banco de dados.</p>");
        }
        $conn = null;

        return $msg;
    }

    //método para atualizar dados da tabela produto
    public function atualizarPessoaDAO(Pessoa $pessoa)
    {
        $conn = new Conecta();
        $msg = new Mensagem();
        $conecta = $conn->conectadb();
        if ($conecta) {
            $idpessoa = $pessoa->getIdpessoa();
            $nomePessoa = $pessoa->getNome();
            $logradouro = $pessoa->getFkEndereco()->getLogradouro();
            $complemento = $pessoa->getFkEndereco()->getComplemento();
            $bairro = $pessoa->getFkEndereco()->getBairro();
            $cidade = $pessoa->getFkEndereco()->getCidade();
            $uf = $pessoa->getFkEndereco()->getUf();
            $cep = $pessoa->getFkEndereco()->getCep();
            $cpf = $pessoa->getCpf();
            $dtNasc = $pessoa->getDtNasc();
            $email = $pessoa->getEmail();
            $login = $pessoa->getLogin();
            $senha = $pessoa->getSenha();
            $perfil = $pessoa->getPerfil();
           // $msg->setMsg($cep);
            try {
                $conecta->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //processo para pegar o idendereco da tabela endereco, conforme 
                //o cep, o logradouro e o complemento informado.
                $st = $conecta->prepare("select idendereco "
                    . "from endereco where cep = ? and "
                    . "logradouro = ? and complemento = ? limit 1");
                $st->bindParam(1, $cep);
                $st->bindParam(2, $logradouro);
                $st->bindParam(3, $complemento);
                $fkEnd = "";
                if ($st->execute()) {
                    if ($st->rowCount() > 0) {
                        //$msg->setMsg("".$st->rowCount());
                        while ($linha = $st->fetch(PDO::FETCH_OBJ)) {
                            $fkEnd = $linha->idendereco;
                        }
                        //$msg->setMsg("$fkEnd");
                    } else {
                        $st2 = $conecta->prepare("insert into "
                            . "endereco values (null,?,?,?,?,?,?)");
                        $st2->bindParam(1, $logradouro);
                        $st2->bindParam(2, $complemento);
                        $st2->bindParam(3, $bairro);
                        $st2->bindParam(4, $cidade);
                        $st2->bindParam(5, $uf);
                        $st2->bindParam(6, $cep);
                        $st2->execute();

                        $st3 = $conecta->prepare("select idendereco "
                            . "from endereco where cep = ? and "
                            . "logradouro = ? and complemento = ? limit 1");
                        $st3->bindParam(1, $cep);
                        $st3->bindParam(2, $logradouro);
                        $st3->bindParam(3, $complemento);
                        if ($st3->execute()) {
                            if ($st3->rowCount() > 0) {
                                $linha = $st3->fetch(PDO::FETCH_OBJ);
                                $fkEnd = $linha->idendereco;
                            }
                        }
                    }
                }
                $stmt = $conecta->prepare("update pessoa set "
                    . "nome = ?,"
                    . "dtNasc = ?, "
                    . "login = ?, "
                    . "senha = ?, "
                    . "perfil = ?, "
                    . "email = ?, "
                    . "cpf = ?, "
                    . "fkendereco = ? "
                    . "where idpessoa = ?");
                $stmt->bindParam(1, $nomePessoa);
                $stmt->bindParam(2, $dtNasc);
                $stmt->bindParam(3, $login);
                $stmt->bindParam(4, $senha);
                $stmt->bindParam(5, $perfil);
                $stmt->bindParam(6, $email);
                $stmt->bindParam(7, $cpf);
                $stmt->bindParam(8, $fkEnd);
                $stmt->bindParam(9, $idpessoa);
                $stmt->execute();
                $msg->setMsg("<p style='color: blue;'>"
                    . "Dados atualizados com sucesso</p>");
            } catch (PDOException $ex) {
                $msg->setMsg(var_dump($ex->errorInfo));
            }
        } else {
            $msg->setMsg("<p style='color: red;'>"
                . "Erro na conexão com o banco de dados.</p>");
        }
        $conn = null;
        return $msg;
    }

    //método para carregar lista de produtos do banco de dados
    public function listarPessoasDAO()
    {
        $conn = new Conecta();
        $conecta = $conn->conectadb();
        if ($conecta) {
            try {
                $conecta->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $rs = $conecta->query("select * from pessoa inner join endereco "
                    . " on pessoa.fkendereco = endereco.idendereco");
                $lista = array();
                $a = 0;
                if ($rs->execute()) {
                    if ($rs->rowCount() > 0) {
                        while ($linha = $rs->fetch(PDO::FETCH_OBJ)) {
                            $endereco = new Endereco();
                            $endereco->setLogradouro($linha->logradouro);
                            $endereco->setComplemento($linha->complemento);
                            $endereco->setBairro($linha->bairro);
                            $endereco->setCidade($linha->cidade);
                            $endereco->setUf($linha->uf);
                            $endereco->setCep($linha->cep);

                            $pessoa = new Pessoa();
                            $pessoa->setIdpessoa($linha->idpessoa);
                            $pessoa->setNome($linha->nome);
                            $pessoa->setDtNasc($linha->dtNasc);
                            $pessoa->setLogin($linha->login);
                            $pessoa->setSenha($linha->senha);
                            $pessoa->setPerfil($linha->perfil);
                            $pessoa->setEmail($linha->email);
                            $pessoa->setCpf($linha->cpf);
                            $pessoa->setFkendereco($endereco);
                            $lista[$a] = $pessoa;
                            $a++;
                        }
                    }
                }
            } catch (PDOException $ex) {
                $msg->setMsg(var_dump($ex->errorInfo));
            }
            $conn = null;
            return $lista;
        }
    }

    //método para excluir produto na tabela produto
    public function excluirPessoaDAO($id)
    {
        $conn = new Conecta();
        $conecta = $conn->conectadb();
        $msg = new Mensagem();
        if ($conecta) {
            try {
                $conecta->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conecta->prepare("delete from pessoa "
                    . "where idpessoa = ?");
                $stmt->bindParam(1, $id);
                $stmt->execute();
                $msg->setMsg("<p style='color: #d6bc71;'>"
                    . "Dados excluídos com sucesso.</p>");
            } catch (PDOException $ex) {
                $msg->setMsg(var_dump($ex->errorInfo));
            }
        } else {
            $msg->setMsg("<p style='color: red;'>'Banco inoperante!'</p>");
        }
        $conn = null;
        return $msg;
    }

    //método para os dados de produto por id
    public function pesquisarPessoaIdDAO($id)
    {
        $conn = new Conecta();
        $conecta = $conn->conectadb();
        $pessoa = new Pessoa();
        if ($conecta) {
            try {
                $conecta->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $rs = $conecta->prepare("select * from pessoa inner join endereco "
                    . " on pessoa.fkendereco = endereco.idendereco where "
                    . "idpessoa = ? limit 1");
                $rs->bindParam(1, $id);
                if ($rs->execute()) {
                    if ($rs->rowCount() > 0) {
                        while ($linha = $rs->fetch(PDO::FETCH_OBJ)) {

                            $endereco = new Endereco();
                            $endereco->setLogradouro($linha->logradouro);
                            $endereco->setComplemento($linha->complemento);
                            $endereco->setBairro($linha->bairro);
                            $endereco->setCidade($linha->cidade);
                            $endereco->setUf($linha->uf);
                            $endereco->setCep($linha->cep);

                            $pessoa->setIdpessoa($linha->idpessoa);
                            $pessoa->setNome($linha->nome);
                            $pessoa->setDtNasc($linha->dtNasc);
                            $pessoa->setLogin($linha->login);
                            $pessoa->setSenha($linha->senha);
                            $pessoa->setPerfil($linha->perfil);
                            $pessoa->setEmail($linha->email);
                            $pessoa->setCpf($linha->cpf);
                            $pessoa->setFkendereco($endereco);
                        }
                    }
                }
            } catch (PDOException $ex) {
                $msg->setMsg(var_dump($ex->errorInfo));
            }
            $conn = null;
        } else {
            echo "<script>alert('Banco inoperante!')</script>";
            echo "<META HTTP-EQUIV='REFRESH' CONTENT=\"0;
			 URL='../PHPMatutino01/cadastroPessoa.php'\">";
        }
        return $pessoa;
    }

    

}
