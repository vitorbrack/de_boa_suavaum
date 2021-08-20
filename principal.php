<?php
ob_start();
session_start();

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
            <div class="row" style="margin-top: 30px;">
                <?php
                if(isset($_SESSION['msg'])){
                    if($_SESSION['msg']!=""){
                        echo $_SESSION['msg'];
                        echo "<META HTTP-EQUIV='REFRESH' CONTENT=\"2;
                              URL='./principal.php'\">";
                        $_SESSION['msg'] = "";
                    }
                }
                
                ?>
                <p>Bem-vindo, <?php echo $_SESSION['nomep'];?>!</p>
                <p><?php echo $_SESSION['idp'];?></p>
                <p><?php echo $_SESSION['loginp'];?></p>
                <p><?php echo $_SESSION['perfilp'];?></p>
                <?php
                    if($_SESSION['perfilp'] == "Cliente"){
                        echo "<h3 style='color:blue';>
                        Você é nosso melhor cliente</h3>";
                    }else{
                        echo "<h3 style='color:blue';>
                        Você é nosso melhor Funcionário</h3>";
                    }
                ?>
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