/**
 * script.js — AJAX-запросы для AutoWorld
 * Лабораторная работа №5
 * Используются: jQuery.ajax, jQuery.get, jQuery.post, jQuery.getJSON
 */

// GET-запрос через jQuery.getJSON: получить все заявки и обновить на странице
function loadRequests() {
    $.getJSON('ajax.php', { action: 'get_requests' })
        .done(function(response) {
            if (response.success) {
                let html = '';
                response.data.forEach(function(req) {
                    html += `
                        <tr>
                            <td>${req.name}</td>
                            <td>${req.email}</td>
                            <td>${req.phone}</td>
                            <td>${req.car_model}</td>
                            <td>${req.date}</td>
                            <td>${req.time}</td>
                            <td>${req.created_at}</td>
                        </tr>
                    `;
                });
                $('#requestsTableBody').html(html);
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Ошибка загрузки заявок:', textStatus, errorThrown);
            $('#requestsTableBody').html('<tr><td colspan="7" class="text-center text-danger">Ошибка загрузки данных</td></tr>');
        });
}

// POST-запрос через jQuery.ajax: отправить форму без перезагрузки
function submitForm() {
    const formData = {
        name: $('#name').val(),
        email: $('#email').val(),
        phone: $('#phone').val(),
        car_model: $('#car').val(),
        date: $('#date').val(),
        time: $('#time').val(),
        action: 'save_request'
    };

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function() {
            // Показываем индикатор загрузки
            $('#submitBtn').prop('disabled', true).text('⏳ Отправка...');
        },
        success: function(response) {
            if (response.success) {
                $('#successMessage').text(response.message).show();
                $('#errorMessage').hide();
                $('#testDriveForm')[0].reset();
                // Обновляем список заявок
                loadRequests();
                // Показываем модальное окно
                $('#successModal').modal('show');
            } else {
                $('#errorMessage').text(response.message).show();
                $('#successMessage').hide();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Ошибка отправки:', textStatus, errorThrown);
            $('#errorMessage').text('❌ Ошибка соединения с сервером').show();
        },
        complete: function() {
            // Возвращаем кнопку
            $('#submitBtn').prop('disabled', false).text('✉️ Записаться');
        }
    });
}

// Дополнительно: загрузка заявок через jQuery.get (для демонстрации)
function loadRequestsViaGet() {
    $.get('ajax.php', { action: 'get_requests' }, function(response) {
        console.log('GET-запрос выполнен успешно');
    }, 'json');
}

// Дополнительно: загрузка заявок через jQuery.post (для демонстрации)
function loadRequestsViaPost() {
    $.post('ajax.php', { action: 'get_requests' }, function(response) {
        console.log('POST-запрос выполнен успешно');
    }, 'json');
}

// Загружаем заявки при открытии страницы
$(document).ready(function() {
    loadRequests();
    
    // Обновление каждые 30 секунд
    setInterval(loadRequests, 30000);
    
    // Обработка отправки формы
    $('#testDriveForm').on('submit', function(e) {
        e.preventDefault();
        submitForm();
    });
});