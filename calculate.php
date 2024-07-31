<?php
require_once 'backend/sdbh.php';
$dbh = new sdbh();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productID = $_POST['product'];
    $days = $_POST['days'];
    $selectedServices = isset($_POST['services']) ? $_POST['services'] : [];

    // Проверка входных данных
    if (empty($productID) || empty($days)) {
        echo json_encode(['error' => 'Необходимо выбрать продукт и указать количество дней.']);
        exit;
    }

    // Получаем информацию о продукте
    $product = $dbh->mselect_rows('a25_products', ['ID' => $productID], 0, 1, 'ID')[0];

    if (!$product) {
        echo json_encode(['error' => 'Продукт не найден.']);
        exit;
    }

    $price = $product['PRICE'];
    $tarif = unserialize($product['TARIFF']);

    // Вывод промежуточных значений
    $debugInfo = [
        'productID' => $productID,
        'days' => $days,
        'selectedServices' => $selectedServices,
        'initialPrice' => $price,
        'tarif' => $tarif
    ];

    // Проверка тарифов
    if ($tarif) {
        foreach ($tarif as $daysRequired => $tarifPrice) {
            if ($days >= $daysRequired) {
                $price = $tarifPrice;
            }
        }
    }

    $total = $price * $days;

    // Вывод промежуточного значения
    $debugInfo['finalPrice'] = $total;

    $services = unserialize($dbh->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'ID')[0]['set_value']);

    foreach ($selectedServices as $service) {
        if (isset($services[$service])) {
            $total += $services[$service] * $days;
        }
    }

    // Добавляем итоговую стоимость к отладочной информации
    $debugInfo['total'] = $total;

    // Возвращаем отладочную информацию вместе с итоговой стоимостью
    echo json_encode($debugInfo);
    exit;
}
