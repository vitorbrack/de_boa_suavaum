<?php
ob_start();
if (!isset($_SESSION)) {
    session_start();
}
if((!isset($_SESSION['loginp']) || !isset($_SESSION['nomep'])) ||
        !isset($_SESSION['perfilp']) || !isset($_SESSION['nr']) ||
        ($_SESSION['nr'] != $_SESSION['confereNr'])) { 
    // Usuário não logado! Redireciona para a página de login 
    header("Location: sessionDestroy.php");
    exit;
}
include_once './controller/PessoaController.php';
include_once './model/Pessoa.php';
include_once './model/Endereco.php';
include_once './model/Mensagem.php';
$msg = new Mensagem();
$en = new Endereco();
$pe = new Pessoa();
$pe->setFkendereco($en);
$btEnviar = FALSE;
$btAtualizar = FALSE;
$btExcluir = FALSE;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cadastro</title>
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            .btInput{
                margin-top: 20px;
            }
            .pad15{
                padding-bottom: 15px; padding-top: 15px;
            }
        </style>
        <script>
            function mascara(t, mask){
                var i = t.value.length;
                var saida = mask.substring(1,0);
                var texto = mask.substring(i)
                
                if (texto.substring(0,1) != saida){
                    t.value += texto.substring(0,1);
                }
            }
        </script>
    </head>
    <body>
        <?php
            include_once './nav.php';
            echo navBar();
        ?>
        <div class="container">
        <label id="cepErro" style="color:red;"></label>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card-header bg-dark text-center border
                         text-white"><strong>Cadastro de Pessoa</strong>
                    </div>
                    <div class="card-body border">
                        <?php
                        //envio dos dados para o BD
                        if (isset($_POST['cadastrarPessoa'])) {
                            $nomePessoa = trim($_POST['nomePessoa']);
                            if ($nomePessoa != "") {
                                $logradouro = $_POST['logradouro'];
                                $complemento = $_POST['complemento'];
                                $bairro = $_POST['bairro'];
                                $cidade = $_POST['cidade'];
                                $uf = $_POST['uf'];
                                $cep = $_POST['cep'];
                                $dtNasc = $_POST['dtNasc'];
                                $perfil = $_POST['perfil'];
                                $email = $_POST['email'];
                                $login = $_POST['login'];
                                $senha = $_POST['senha'];
                                $cpf = $_POST['cpf'];

                                $fc = new PessoaController();
                                unset($_POST['cadastrarPessoa']);
                                $msg = $fc->inserirPessoa($nomePessoa, $logradouro, 
                                    $complemento, $bairro, $cidade, $uf, $cep,
                                    $dtNasc, $login, $senha, $perfil, $email, $cpf);
                                echo $msg->getMsg();
                                echo "<META HTTP-EQUIV='REFRESH' CONTENT=\"2;
                                    URL='cadastro.php'\">";
                            }
                        }
                        
                        //método para atualizar dados do produto no BD
                        if (isset($_POST['atualizarPessoa'])) {
                            $nomePessoa = trim($_POST['nomePessoa']);
                            if ($nomePessoa != "") {
                                $idpessoa = $_POST['idpessoa'];
                                $logradouro = $_POST['logradouro'];
                                $complemento = $_POST['complemento'];
                                $bairro = $_POST['bairro'];
                                $cidade = $_POST['cidade'];
                                $uf = $_POST['uf'];
                                $cep = $_POST['cep'];
                                $perfil = $_POST['perfil'];
                                $dtNasc = $_POST['dtNasc'];
                                $email = $_POST['email'];
                                $login = $_POST['login'];
                                $senha = $_POST['senha'];
                                $cpf = $_POST['cpf'];

                                $fc = new PessoaController();
                                unset($_POST['atualizarPessoa']);
                                $msg = $fc->atualizarPessoa($idpessoa, $nomePessoa, 
                                    $logradouro, $complemento, $bairro, $cidade, $uf, $cep,
                                    $dtNasc, $login, $senha, $perfil, $email, $cpf);
                                echo $msg->getMsg();
                                /*echo "<META HTTP-EQUIV='REFRESH' CONTENT=\"2;
                                    URL='cadastro.php'\">";*/
                            }
                        }
                        
                        if (isset($_POST['excluir'])) {
                            if ($pe != null) {
                                $id = $_POST['ide'];
                                
                                $fc = new PessoaController();
                                unset($_POST['excluir']);
                                $msg = $fc->excluirPessoa($id);
                                echo $msg->getMsg();
                                echo "<META HTTP-EQUIV='REFRESH' CONTENT=\"2;
                                    URL='cadastro.php'\">";
                            }
                        }
                        
                        if (isset($_POST['excluirPessoa'])) {
                            if ($pe != null) {
                                $id = $_POST['idpessoa'];
                                unset($_POST['excluirPessoa']);
                                $fc = new PessoaController();
                                $msg = $fc->excluirPessoa($id);
                                echo $msg->getMsg();
                                echo "<META HTTP-EQUIV='REFRESH' CONTENT=\"2;
                                    URL='cadastro.php'\">";
                            }
                        }

                        if (isset($_POST['limpar'])) {
                            $pe = null;
                            unset($_GET['id']);
                            header("Location: cadastro.php");
                        }
                        if (isset($_GET['id'])) {
                            $btEnviar = TRUE;
                            $btAtualizar = TRUE;
                            $btExcluir = TRUE;
                            $id = $_GET['id'];
                            $fc = new PessoaController();
                            $pe = $fc->pesquisarPessoaId($id);
                        }
                        ?>
                        <form method="post" action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Código: <label style="color:red;">
                                            <?php
                                            if ($pe != null) {
                                                echo $pe->getIdpessoa();
                                                ?>
                                            </label></strong>
                                        <input type="hidden" name="idpessoa" 
                                               value="<?php echo $pe->getIdpessoa(); ?>"><br>
                                               <?php
                                           }
                                           ?>     
                                    <label>Pessoa</label>  
                                    <input class="form-control" type="text" 
                                           name="nomePessoa" 
                                           value="<?php echo $pe->getNome(); ?>">
                                    <label>CPF</label>  
                                    <label style="color: red; font-size: 11px;" id="valCpf"></label>
                                    <input class="form-control" type="text" id="cpf"
                                           onkeypress="mascara(this, '###.###.###-##')" maxlength="14"
                                           value="<?php echo $pe->getCpf(); ?>" name="cpf">
                                    <label>E-Mail</label>  
                                    <input class="form-control" type="email" 
                                           value="<?php echo $pe->getEmail(); ?>" name="email">
                                    <label>Dt. Nasc.</label>  
                                    <input class="form-control" type="date" 
                                           value="<?php echo $pe->getDtNasc(); ?>" name="dtNasc">
                                    <label>Usuário</label>  
                                    <input class="form-control" type="text" 
                                           value="<?php echo $pe->getLogin(); ?>" name="login">
                                    <label>Senha</label>  
                                    <input class="form-control" type="password" name="senha">
                                    <label>Conf. Senha</label>  
                                    <input class="form-control" type="password" name="senha2">
                                </div>
                                <div class="col-md-6">
                                    <br>
                                    <label>Perfil</label> 
                                    <label id="valCep" style="color: red; font-size: 11px;"></label>
                                    <select class="form-select" name="perfil">
                                        <option>[--Selecione--]</option>
                                        <option
                                            <?php
                                            if($pe->getPerfil() == "Cliente"){
                                                echo "selected = 'selected'";
                                            }
                                            ?>
                                            >Cliente</option>
                                        <option
                                            <?php
                                            if($pe->getPerfil() == "Funcionário"){
                                                echo "selected = 'selected'";
                                            }
                                            ?>
                                            >Funcionário</option>
                                    </select>  
                                    <label>CEP</label>  
                                    <label style="color: red; font-size: 11px;" id="valCep"></label>
                                    <input class="form-control" type="text" id="cpf"
                                           onkeypress="mascara(this, '#####-###')" maxlength="9"
                                           value="<?php echo $pe->getFkendereco()->getCep(); ?>" name="cep">
                                    <label>Rua/Logradouro</label>  
                                    <input class="form-control" type="text" id="rua"
                                           value="<?php echo $pe->getFkEndereco()->getLogradouro(); ?>" name="logradouro">   
                                    <label>Complemento</label>  
                                    <input class="form-control" type="text" id="complemento"
                                           value="<?php echo $pe->getFkEndereco()->getComplemento(); ?>" name="complemento">
                                    <label>Bairro</label>  
                                    <input class="form-control" type="text" id="bairro"
                                           value="<?php echo $pe->getFkEndereco()->getBairro(); ?>" name="bairro">
                                    <label>Cidade</label>  
                                    <input class="form-control" type="text" id="cidade"
                                           value="<?php echo $pe->getFkEndereco()->getCidade(); ?>" name="cidade">
                                    <label>UF</label>  
                                    <input class="form-control" type="text" id="uf"
                                           value="<?php echo $pe->getFkEndereco()->getUf(); ?>" name="uf">
                                </div>
                                <div class="row">
                                    <div class="col-6 offset-md-3">
                                    <input type="submit" name="cadastrarPessoa"
                                           class="btn btn-success btInput" value="Enviar"
                                           <?php if($btEnviar == TRUE) echo "disabled"; ?>>
                                    <input type="submit" name="atualizarPessoa"
                                           class="btn btn-secondary btInput" value="Atualizar"
                                           <?php if($btAtualizar == FALSE) echo "disabled"; ?>>
                                    <button type="button" class="btn btn-warning btInput" 
                                            data-bs-toggle="modal" data-bs-target="#ModalExcluir"
                                            <?php if($btExcluir == FALSE) echo "disabled"; ?>>
                                        Excluir
                                    </button>
                                    <!-- Modal para excluir -->
                                    <div class="modal fade" id="ModalExcluir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" 
                                                        id="exampleModalLabel">
                                                        Confirmar Exclusão</h5>
                                                    <button type="button" 
                                                            class="btn-close" 
                                                            data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h5>Deseja Excluir?</h5>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="submit" name="excluirPessoa"
                                                           class="btn btn-success "
                                                           value="Sim">
                                                    <input type="submit" 
                                                        class="btn btn-light btInput" 
                                                        name="limpar" value="Não">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- fim do modal para excluir -->
                                    
                                    <input type="submit" 
                                           class="btn btn-light btInput" 
                                           name="limpar" value="Limpar">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <br>
        <div class="container">
                <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped"
                               style="border-radius: 3px; overflow:hidden;">
                            <thead class="table-dark">
                                <tr><th>Código</th>
                                    <th>Pessoa</th>
                                    <th>CPF</th>
                                    <th>E-Mail</th>
                                    <th>Perfil</th>
                                    <th>Logradouro</th>
                                    <th>Complemento</th>
                                    <th>Bairro</th>
                                    <th>Cidade</th>
                                    <th>UF</th>
                                    <th colspan="2">Ações</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                $fcTable = new PessoaController();
                                $listaPessoas = $fcTable->listarPessoaes();
                                $a = 0;
                                if ($listaPessoas != null) {
                                    foreach ($listaPessoas as $lf) {
                                        $a++;
                                        ?>
                                        <tr>
                                            <td><?php print_r($lf->getIdpessoa()); ?></td>
                                            <td><?php print_r($lf->getNome()); ?></td>
                                            <td><?php print_r($lf->getCpf()); ?></td>
                                            <td><?php print_r($lf->getEmail()); ?></td>
                                            <td><?php print_r($lf->getPerfil()); ?></td>
                                            <td><?php print_r($lf->getFkendereco()->getLogradouro()); ?></td>
                                            <td><?php print_r($lf->getFkendereco()->getComplemento()); ?></td>
                                            <td><?php print_r($lf->getFkendereco()->getBairro()); ?></td>
                                            <td><?php print_r($lf->getFkendereco()->getCidade()); ?></td>
                                            <td><?php print_r($lf->getFkendereco()->getUf()); ?></td>
                                            <td><a href="cadastro.php?id=<?php echo $lf->getIdpessoa(); ?>"
                                                   class="btn btn-light">
                                                    <img src="img/edita.png" width="24"></a>
                                                </form>
                                                <button type="button" 
                                                        class="btn btn-light" data-bs-toggle="modal" 
                                                        data-bs-target="#exampleModal<?php echo $a; ?>">
                                                    <img src="img/delete.png" width="24"></button></td>
                                        </tr>
                                        <!-- Modal -->
                                    <div class="modal fade" id="exampleModal<?php echo $a; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                    <button type="button" class="btn-close" 
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" action="">
                                                        <label><strong>Deseja excluir o fornecedor 
                                                                <?php echo $lf->getNome(); ?>?</strong></label>
                                                        <input type="hidden" name="ide" 
                                                               value="<?php echo $lf->getIdpessoa(); ?>">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="excluir" class="btn btn-primary">Sim</button>
                                                            <button type="reset" class="btn btn-secondary" 
                                                                    data-bs-dismiss="modal">Não</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>     
    </div>
     

    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jQuery.js"></script>
    <script src="js/jQuery.min.js"></script>
    <script>
        var myModal = document.getElementById('myModal')
        var myInput = document.getElementById('myInput')

        myModal.addEventListener('shown.bs.modal', function () {
            myInput.focus()
        })
    </script> 
    <!-- controle de endereço (ViaCep) -->
    <script>

        $(document).ready(function() {

            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#rua").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#uf").val("");
                $("#cepErro").val("");
            }
            
            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {

                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {

                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#rua").val("...");
                        $("#bairro").val("...");
                        $("#cidade").val("...");
                        $("#uf").val("...");

                        //Consulta o webservice viacep.com.br/
                        $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#rua").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
                                $("#uf").val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulário_cep();
                                document.getElementById("valCep").innerHTML = "* CEP não encontrado";
                            }
                        });
                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        document.getElementById("valCep").innerHTML = "* Formato inválido";

                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
        });

    </script>
</body>
</html>
<?php ob_end_flush();?>
