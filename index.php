<?php
require_once 'backend/sdbh.php';
$dbh = new sdbh();
$products = $dbh->mselect_rows('a25_products', [], 0, 100, 'id');
$services = unserialize($dbh->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'id')[0]['set_value']);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <link href="assets/css/style.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <div class="row row-header">
                <div class="col-12">
                    <img src="assets/img/logo.png" alt="logo" style="max-height:50px"/>
                    <h1>Прокат</h1>
                </div>
            </div>
            <!-- TODO: реализовать форму расчета -->
            <div class="row row-form">
                <div class="col-12">
                    <h4>Форма расчета:</h4>
                    <form id="form">
                        <label class="form-label" for="product">Выберите продукт:</label>
                        <select class="form-select" name="product" id="product">
                            <?php foreach($products as $product) { ?>
                                <option value="<?= $product['ID'] ?>" data-tarif='<?= json_encode(unserialize($product['TARIFF'])) ?>' data-price="<?= $product['PRICE'] ?>">
                                    <?= htmlspecialchars($product['NAME']) ?> за <?= htmlspecialchars($product['PRICE']) ?> руб.
                                </option>
                            <?php } ?>
                        </select>
                        <div id="tarif-info" class="mt-3"></div>
                        <label for="days" class="form-label">Количество дней:</label>
                        <input type="number" class="form-control" id="days" name="days" min="1" max="30">

                        <label for="services" class="form-label">Дополнительно:</label>
                        <?php foreach($services as $service => $price) { ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?=$service?>" id="service_<?=$service?>" name="services[]">
                                <label class="form-check-label" for="service_<?=$service?>">
                                    <?=$service?>: <?=$price?> руб.
                                </label>
                            </div>
                        <?php }
                        ?>
                        <button type="submit" class="btn btn-primary mt-2" id="calculate-btn">Рассчитать</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resultModalLabel">Итоговая стоимость</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="result-text"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>


        <script src="assets/js/rental-calc.js"></script>
    </body>
</html>