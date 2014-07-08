<div class="panel-group" id="accordion">
    <?
    $i = 0;
    foreach ($this->categoriesAndProducts as $category => $products):
        ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $i ?>">
                        <h4><?= $category ?></h4>
                    </a>
                </h4>
            </div>
            <div id="collapse<?= $i ?>" class="panel-collapse collapse in">
                <div class="panel-body">

                    <!-- View Logic -->
                    <? foreach ($products as $product) : ?>

                        <div class="col-sm-6 col-md-4">
                            <div class="thumbnail">
                                <!--<img data-src="holder.js/300x100/text: <?= $product['product'] ?>">-->
                                <div class="caption">

                                    <h4 style="height: 40px;"><?= $product['product'] ?></h4>
                                    <p><span class="text-muted"><?= $product['category'] ?></span></p>

                                    <p>
                                        <a class="btn btn-xs btn-info" role="button" data-bind='{ "productId" : "<?= $product['productId'] ?>", "classId" : "<?= $product['classId'] ?>", "department" : "<?= $product['department'] ?>", "product" : "<?= $product['product'] ?>", "price" : "<?= $product['price'] ?>"}'>
                                            <span class="glyphicon glyphicon-plus"> Add </span>
                                        </a>
                                        <span class="label label-success pull-right"> $ <?= $product['price'] ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <? endforeach; ?>
                    <!-- View Logic -->

                </div>
            </div>
        </div>

        <?
        $i++;
    endforeach;
    ?>
</div>