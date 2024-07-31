$(document).ready(function() {
    function updateTarifInfo() {
        var selectedProduct = $('#product option:selected');
        var tarif = selectedProduct.data('tarif');
        var tarifInfoDiv = $('#tarif-info');

        if (tarif) {
            var tarifArray = Object.entries(tarif).sort((a, b) => a[0] - b[0]);
            var tarifHtml = '<h5>Тарифы:</h5><ul>';

            tarifArray.forEach(function(t) {
                tarifHtml += '<li>От ' + t[0] + ' дней: ' + t[1] + ' руб.</li>';
            });

            tarifHtml += '</ul>';
            tarifInfoDiv.html(tarifHtml);
        } else {
            tarifInfoDiv.html('<p>Тарифы не указаны, используется стандартная цена.</p>');
        }
    }

    $('#product').change(function() {
        updateTarifInfo();
    });

    $('#form').on('submit', function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: 'calculate.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    alert('Ошибка: ' + response.error);
                } else {
                    $('#result-text').text('Итоговая стоимость: ' + response.total + ' руб.');
                    console.log('Отладочная информация:', response); // Выводим отладочную информацию в консоль
                    var resultModal = new bootstrap.Modal(document.getElementById('resultModal'), {
                        keyboard: true
                    });
                    resultModal.show();
                }
            },
            error: function() {
                alert('Ошибка при отправке запроса.');
            }
        });
    });

    // Initialize the tarif info on page load
    updateTarifInfo();
});