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
include_once 'controller/ProdutoController.php';
include_once './model/Produto.php';
include_once './model/Fornecedor.php';
include_once './model/Mensagem.php';
include_once 'controller/FornecedorController.php';
$fcc = new FornecedorController();
$msg = new Mensagem();
$pr = new Produto();
$fornecedor = new Fornecedor();
$pr->setFornecedor($fornecedor);
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
    </head>
    <body>
        <?php
            include_once './nav.php';
            echo navBar();
        ?>

        <div class="container-fluid">
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-4">
                    <div class="card-header bg-dark text-center border
                         text-white"><strong>Cadastro de Produto</strong>
                    </div>
                    <div class="card-body border">
                        <?php
                        //envio dos dados para o BD
                        if (isset($_POST['cadastrarProduto'])) {
                            $nomeProduto = trim($_POST['nomeProduto']);
                            if ($nomeProduto != "") {
                                $vlrCompra = $_POST['vlrCompra'];
                                $vlrVenda = $_POST['vlrVenda'];
                                $qtdEstoque = $_POST['qtdEstoque'];
                                $fkfornecedor = $_POST['idfornecedor'];      
                                if(isset($_FILES['imagem']) && basename($_FILES["imagem"]["name"]) != ""){
                                    $target_dir = "img/";
                                    $target_file = $target_dir . basename($_FILES["imagem"]["name"]);
                                    $imagem = $target_file;
                                    $uploadOk = 1;
                                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                                    // Check if image file is a actual image or fake image
                                    $check = getimagesize($_FILES["imagem"]["tmp_name"]);
                                    if($check !== false) {
                                            $uploadOk = 1;
                                    } else {
                                            $msg->setMsg("File is not an image.");
                                            $uploadOk = 0;
                                    }

                                    // Check if file already exists
                                    if (file_exists($target_file)) {
                                        $imagem = $target_file;
                                        $uploadOk = 0;
                                    }

                                    // Check file size
                                    if ($_FILES["imagem"]["size"] > 500000) {
                                            $msg->setMsg("O arquivo excedeu o limite do tamanho permitido (500KB).");
                                            $uploadOk = 0;
                                    }

                                    // Allow certain file formats
                                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                                       && $imageFileType != "jfif" && $imageFileType != "gif" ) {
                                            $msg->setMsg("A extensão da imagem deve ser JPG, JPEG, PNG & "
                                                    . "GIF.");
                                            $uploadOk = 0;
                                    }

                                    // Check if $uploadOk is set to 0 by an error
                                    if ($uploadOk == 0) {
                                            $msg->setMsg("A imagem não foi gravada.");
                                    // if everything is ok, try to upload file
                                    } else {
                                        move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file);
                                    }
                                }else{
                                   
                                   $imagem = "img/semImagem.jpg";
                                }
                                $pc = new ProdutoController();
                                unset($_POST['cadastrarProduto']);
                                $msg = $pc->inserirProduto($nomeProduto, $vlrCompra, 
                                    $vlrVenda, $qtdEstoque, $imagem, $fkfornecedor);
                                
                                echo $msg->getMsg();
                                echo "<META HTTP-EQUIV='REFRESH' CONTENT=\"2;
                                    URL='cadastroProduto.php'\">";
                            }
                        }
                        
                        //método para atualizar dados do produto no BD
                        if (isset($_POST['atualizarProduto'])) {
                            $nomeProduto = trim($_POST['nomeProduto']);
                            if ($nomeProduto != "") {
                                $id = $_POST['idproduto'];
                                $vlrCompra = $_POST['vlrCompra'];
                                $vlrVenda = $_POST['vlrVenda'];
                                $qtdEstoque = $_POST['qtdEstoque'];
                                $fkfornecedor = $_POST['idfornecedor']; 
                                $img =  $_POST['img'];
                                if(isset($_FILES['imagem']) && basename($_FILES["imagem"]["name"]) != ""){
                                    $target_dir = "img/";
                                    $target_file = $target_dir . basename($_FILES["imagem"]["name"]);
                                    $imagem = $target_file;
                                    $uploadOk = 1;
                                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                                    // Check if image file is a actual image or fake image
                                    $check = getimagesize($_FILES["imagem"]["tmp_name"]);
                                    if($check !== false) {
                                            $uploadOk = 1;
                                    } else {
                                            $msg->setMsg("File is not an image.");
                                            $uploadOk = 0;
                                    }

                                    // Check if file already exists
                                    if (file_exists($target_file)) {
                                        $imagem = $target_file;
                                        $uploadOk = 0;
                                    }

                                    // Check file size
                                    if ($_FILES["imagem"]["size"] > 500000) {
                                            $msg->setMsg("O arquivo excedeu o limite do tamanho permitido (500KB).");
                                            $uploadOk = 0;
                                    }

                                    // Allow certain file formats
                                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                                       && $imageFileType != "jfif" && $imageFileType != "gif" ) {
                                            $msg->setMsg("A extensão da imagem deve ser JPG, JPEG, PNG & "
                                                    . "GIF.");
                                            $uploadOk = 0;
                                    }

                                    // Check if $uploadOk is set to 0 by an error
                                    if ($uploadOk == 0) {
                                        $imagem = "img/semImagem.jpg";
                                    // if everything is ok, try to upload file
                                    } else {
                                        move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file);
                                    }
                                }else{
                                   if($img != "img/semImagem.jpg"){
                                       $imagem = $img;
                                   }else{
                                        $imagem = "img/semImagem.jpg";
                                   }
                                }
                                $pc = new ProdutoController();
                                unset($_POST['atualizarProduto']);
                                $msg = $pc->atualizarProduto($id, $nomeProduto, $vlrCompra, 
                                    $vlrVenda, $qtdEstoque, $imagem, $fkfornecedor);
                                echo $msg->getMsg();
                                $pr = null;
                                echo "<META HTTP-EQUIV='REFRESH' CONTENT=\"2;
                                    URL='cadastroProduto.php'\">";
                            }
                        }
                        
                        if (isset($_POST['excluir'])) {
                            if ($pr != null) {
                                $id = $_POST['ide'];
                                
                                $pc = new ProdutoController();
                                unset($_POST['excluir']);
                                $msg = $pc->excluirProduto($id);
                                echo $msg->getMsg();
                                echo "<META HTTP-EQUIV='REFRESH' CONTENT=\"2;
                                    URL='cadastroProduto.php'\">";
                            }
                        }
                        
                        if (isset($_POST['excluirProduto'])) {
                            if ($pr != null) {
                                $id = $_POST['idproduto'];
                                unset($_POST['excluirProduto']);
                                $pc = new ProdutoController();
                                $msg = $pc->excluirProduto($id);
                                echo $msg->getMsg();
                                echo "<META HTTP-EQUIV='REFRESH' CONTENT=\"2;
                                    URL='cadastroProduto.php'\">";
                            }
                        }

                        if (isset($_POST['limpar'])) {
                            $pr = null;
                            unset($_GET['id']);
                            header("Location: cadastroProduto.php");
                        }
                        if (isset($_GET['id'])) {
                            $btEnviar = TRUE;
                            $btAtualizar = TRUE;
                            $btExcluir = TRUE;
                            $id = $_GET['id'];
                            $pc = new ProdutoController();
                            $pr = $pc->pesquisarProdutoId($id);
                        }
                        ?>
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>Código: <label style="color:red;">
                                            <?php
                                            if ($pr != null) {
                                                echo $pr->getIdProduto();
                                                ?>
                                            </label></strong>
                                        <input type="hidden" name="idproduto" 
                                               value="<?php echo $pr->getIdProduto(); ?>">
                                        <input type="hidden" name="img" 
                                               value="<?php echo $pr->getImagem(); ?>">
                                        <br>
                                        
                                               <?php
                                           }
                                           ?>     
                                    <label>Produto</label>  
                                    <input class="form-control" type="text" 
                                           name="nomeProduto" 
                                           value="<?php echo $pr->getNomeProduto(); ?>">
                                    <label>Valor de Compra</label>  
                                    <input class="form-control" type="text" 
                                           value="<?php echo $pr->getVlrCompra(); ?>" name="vlrCompra">  
                                    <label>Valor de Venda</label>  
                                    <input class="form-control" type="text" 
                                           value="<?php echo $pr->getVlrVenda(); ?>" name="vlrVenda"> 
                                    <label>Qtde em Estoque</label>  
                                    <input class="form-control" type="number" 
                                           value="<?php echo $pr->getQtdEstoque(); ?>" name="qtdEstoque">
                                    <label>Imagem</label>  
                                    <input class="form-control" type="file" name="imagem">
                                    
                                    <label>Fornecedor</label>  
                                    <select class="form-control"  name="idfornecedor">
                                        <option>[--SELECIONE--]</option>
                                        <?php
                                          $listaFornecedores = $fcc->listarFornecedores();
                                          if($listaFornecedores != null){
                                              foreach ($listaFornecedores as $lf){
                                                  ?>
                                            <option value="<?php echo $lf->getIdfornecedor();?>"
                                            <?php
                                            $fk = $pr->getFornecedor()->getIdfornecedor();
                                            if($pr->getFornecedor()->getIdfornecedor() != ""){
                                                if($lf->getIdfornecedor() == 
                                                        $pr->getFornecedor()->getIdfornecedor()){
                                                    echo "selected = 'selected'";
                                                }
                                            }
                                            ?>
                                            >
                                                    <?php echo $lf->getNomeFornecedor();?></option>
                                        <?php
                                              }
                                          }
                                        ?>
                                    </select>
                                    <input type="submit" name="cadastrarProduto"
                                           class="btn btn-success btInput" value="Enviar"
                                           <?php if($btEnviar == TRUE) echo "disabled"; ?>>
                                    <input type="submit" name="atualizarProduto"
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
                                                    <input type="submit" name="excluirProduto"
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
                                    &nbsp;&nbsp;
                                    <input type="submit" 
                                           class="btn btn-light btInput" 
                                           name="limpar" value="Limpar">
                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-responsive"
                               style="border-radius: 3px; overflow:hidden;">
                            <thead class="table-dark">
                                <tr><th>Código</th>
                                    <th>Produto</th>
                                    <th>Imagem</th>
                                    <th>Compra (R$)</th>
                                    <th>Venda (R$)</th>
                                    <th>Estoque</th>
                                    <th>Fornecedor</th>
                                    <th>Representante</th>
                                    <th colspan="2" style="text-align:center;">Ações</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                $pcTable = new ProdutoController();
                                $listaProdutos = $pcTable->listarProdutos();
                                $a = 0;
                                if ($listaProdutos != null) {
                                    foreach ($listaProdutos as $lp) {
                                        $a++;
                                        ?>
                                        <tr>
                                            <td><?php print_r($lp->getIdProduto()); ?></td>
                                            <td><?php print_r($lp->getNomeProduto()); ?></td>
                                            <td><img src="<?php print_r($lp->getImagem()); ?>" 
                                                     width="48"></td>
                                            <td><?php print_r($lp->getVlrCompra()); ?></td>
                                            <td><?php print_r($lp->getVlrVenda()); ?></td>
                                            <td><?php print_r($lp->getQtdEstoque()); ?></td>
                                            <td><?php print_r($lp->getFornecedor()->getNomefornecedor()); ?></td>
                                            <td><?php print_r($lp->getFornecedor()->getRepresentante()); ?></td>
                                            <td><a href="cadastroProduto.php?id=<?php echo $lp->getIdProduto(); ?>"
                                                   class="btn btn-light">
                                                    <img src="img/edita.png" width="24"></a></td>
                                                </form>
                                                <td><button type="button" 
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
                                                        <label><strong>Deseja excluir o produto 
                                                                <?php echo $lp->getNomeProduto(); ?>?</strong></label>
                                                        <input type="hidden" name="ide" 
                                                               value="<?php echo $lp->getIdProduto(); ?>">
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
    <script>
        var myModal = document.getElementById('myModal')
        var myInput = document.getElementById('myInput')

        myModal.addEventListener('shown.bs.modal', function () {
            myInput.focus()
        })
    </script> 
</body>
</html>
<?php ob_end_flush();?>
