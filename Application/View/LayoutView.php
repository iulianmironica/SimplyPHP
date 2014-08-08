<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Iulian Mironica">
        <link rel="shortcut icon" href="<?php echo $this->baseUrl('/image/favicon.png') ?>">

        <title>SimplyPHP Framework Demo</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo $this->baseUrl('/css/bootstrap/bootstrap.css') ?>" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="<?php echo $this->baseUrl('/css/bootstrap/dashboard.css') ?>" rel="stylesheet">
        <link href="<?php echo $this->baseUrl('/css/style.css') ?>" rel="stylesheet">
        <!-- Typeahead plugin -->
        <link href="<?php echo $this->baseUrl('/css/typeahead/typeahead.css') ?>" rel="stylesheet">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style id="holderjs-style" type="text/css"></style>
    </head>

    <body>
        <div id="loader" style="display: none;">
            <img src="<?php echo $this->baseUrl('/image/loader.gif') ?>">
        </div>

        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo $this->baseUrl() ?>">SimplyPHP Framework Demo</a>
                </div>
                <div class="navbar-collapse collapse">
                    <form class="navbar-form text-right">
                        <div class="form-group">
                            <input type="text" id="search-form" class="form-control" placeholder="Product search...">
                        </div>
                        <button id="search-form-button" class=" btn btn-default btn-xs">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">
                        <li class="<?= $this->controller === 'MainController' && $this->action === 'index' ? 'active' : '' ?>"><a href="<?php echo $this->baseUrl() ?>">Products</a></li>
                        <li class="<?= $this->controller === 'MainController' && $this->action === 'about' ? 'active' : '' ?>"><a href="<?php echo $this->baseUrl('/main/about') ?>">Documentation</a></li>
                        <li class="nav-divider"></li>
                    </ul>
                    <table class="table table-striped" id="basket-nav-table" style="margin-bottom: 0">
                        <h4><a href="" data-toggle="modal" data-target="#myModal">
                                <span class="glyphicon glyphicon-shopping-cart"></span> Basket
                            </a></h4>
                        <thead>
                            <tr>
                                <th width="80%">Product</th>
                                <th> $ </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- View Logic -->
                            <?
                            $price = 0;
                            if (!is_null($this->session->basket)):
                                foreach ($this->session->basket as $key => $val) :
                                    ?>
                                    <tr class="item-<?= $val['productId'] ?>">
                                        <td><?= $val['product'] ?></td>
                                        <td class="basket-item-price"><?= $val['price'] ?></td>
                                    </tr>
                                    <?
                                    $price += $val['price'];
                                endforeach;
                            endif;
                            ?>
                            <!-- View Logic -->
                        </tbody>
                    </table>
                    <table class="table">
                        <tr>
                            <td width="80%"><b>Total</b></td>
                            <td class="total-amount"><?= $price ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <!--Content Here-->

                    <?= $this->render(null, $this->content) ?>

                    <!--Content Here-->
                </div>
            </div>
        </div>

        <!-- Basket Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-shopping-cart"></span> Basket items</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped" id="basket-modal-table" style="margin-bottom: 0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="70%">Product</th>
                                    <th>Price</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $total = 0;
                                if (!is_null($this->session->basket)):
                                    $i = 1;
                                    foreach ($this->session->basket as $key => $val) :
                                        ?>
                                        <tr class="item-<?= $val['productId'] ?>">
                                            <td><?= $i ?></td>
                                            <td><?= $val['product'] ?></td>
                                            <td class="basket-item-price"><?= $val['price'] ?></td>
                                            <td><a class="remove-product" >
                                                    <span class="glyphicon glyphicon-remove" data-bind='{ "productId" : "<?= $val['productId'] ?>" }'></span>
                                                </a>
                                            </td>
                                        </tr>
                                        <?
                                        $i++;
                                        $total += $val['price'];
                                    endforeach;
                                    ?>
                                <? endif;
                                ?>
                            </tbody>
                        </table>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td colspan="4" width="20%" align="right" style="padding-right: 25px;">
                                        <a id="clear-basket">
                                            Clear all <span class="glyphicon glyphicon-remove"></span>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="divider" id="total">
                            <h4 class="text-center"> Total: <span class="total-amount"> <?= $total ?></span> $ </h4>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Continue shopping</button>
                        <button type="button" class="btn btn-info" id="checkout">Ckeckout</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?php echo $this->baseUrl('/javascript/jquery-1.11.1.min.js') ?>"></script>
        <script src="<?php echo $this->baseUrl('/javascript/bootstrap/bootstrap.min.js') ?>"></script>
        <script src="<?php echo $this->baseUrl('/javascript/bootstrap/docs.min.js') ?>"></script>
        <!--Typeahead plugin-->
        <script src="<?php echo $this->baseUrl('/javascript/typeahead/handlebars.js') ?>"></script>
        <script src="<?php echo $this->baseUrl('/javascript/typeahead/typeahead.bundle.js') ?>"></script>

        <script>
            var baseUrl = '<?php echo $this->baseUrl() ?>';
        </script>

        <!-- Custom Javascript
        ====================== -->
        <script src="<?php echo $this->baseUrl('/javascript/custom/util.js') ?>"></script>
        <script src="<?php echo $this->baseUrl('/javascript/custom/script.js') ?>"></script>
    </body>
</html>