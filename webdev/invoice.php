<?php
  session_start();
  if(!isset($_SESSION['name'])) {
    echo("<h2>Вы не вошли в аккаунт! <br /> Через 3 секунды вы будете перенаправлены на страницу логина</h2> <br />");
    echo("<a href='login.html'>Войти в аккаунт</a>");
    echo("<meta http-equiv='refresh' content='3; url=login.html'></meta>");
    die();
  }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Счет за услуги</title>
    <style>
        td, th {
            border: 1px solid white;
            text-align: center;
            padding: 5px;
            background-color: #ebafd7;
        }
        td {
            background-color: #dadada;
        }

        .filters {
            margin-block: 10px;
        }

        th {
            cursor: pointer;
        }

        th:hover {
            background: #689cfc;
        }

        th.asc, th.desc {
            background: #fcf268;
        }

        th.asc::after {
            content: ' ↑';
        }

        th.desc::after {
            content: ' ↓';
        }

    </style>
    <script>
        let sort = 'operation_date';
        let dir = 'DESC';

        let page = 1;

        function sortBy(column) {
            if (sort === column) {
                if(dir == 'ASC') {
                    dir = 'DESC';
                }
                else {
                    dir = 'ASC';
                }
            }
            else {
                sort = column;
                dir = 'ASC';
            }
            showSort();
            loadData();
        }

        function showSort() {
            const headers = document.querySelectorAll('th');
            console.log(headers);
            headers.forEach((th) => {
                th.classList = [];
            });

            const curHeader = document.getElementById("header_" + sort);
            if(dir === 'ASC') {
                curHeader.className = 'asc'
            }
            else {
                curHeader.className = 'desc'
            }

        }

        function prevPage() {
            page = page - 1;
            loadData();
        }

        function nextPage() {
            page = page + 1;
            loadData()
        }

        function showPages(calcs) {
            document.getElementById('page-info').textContent = `Страница ${page}`;
            document.getElementById('prev').disabled = page === 1;
            document.getElementById('next').disabled = calcs.length === 0;
        }
        

        function loadData() {
            document.getElementById("calcs").innerHTML = '<tr><td colspan="5">Загрузка...</td></tr>';

            const date_from = document.getElementById('date_from').value;
            const date_to = document.getElementById('date_to').value;
            const z_max = document.getElementById('z_max').value;
            const z_min = document.getElementById('z_min').value;

            const params = new URLSearchParams({
                date_from: date_from,
                date_to: date_to,
                z_max: z_max,
                z_min: z_min,
                sort: sort,
                dir: dir,
                page: page,
            })

            fetch('api/invoice_service.php?' + params.toString())
            .then((response) => {
                if (response.status === 401) {
                    window.location.href = "login.html";
                }
                else if (response.status === 200) {
                    return response.json();
                }
                else {
                    throw new Error(`Ошибка ${response.status}: ${response.statusText}`);
                }
            })
            .then((calcs) => {
                showCalcs(calcs);
            })
            .catch((error) => {
                console.log(error);
            })

        }

        function showCalcs(calcs){
            if(calcs.length === 0) {
                document.getElementById("calcs").innerHTML = '<tr><td colspan="5">Ничего не найдено</td></tr>';
            }
            else {
                const html = calcs.map((row) => `<tr>
                <td>${row.operation_date}</td>
                <td>${row.x}</td>
                <td>${row.y}</td>
                <td>${row.operation}</td>
                <td>${row.z}</td>
                </tr>`).join('');
                document.getElementById("calcs").innerHTML = html;
            }

            showPages(calcs);
        }

        function resetFilter() {
            document.getElementById('date_from').value = '';
            document.getElementById('date_to').value = '';
            document.getElementById('z_max').value = '';
            document.getElementById('z_min').value = '';

            loadData();
        }
        window.onload = loadData;

        // function loadData() {
        //     // Ожидание ответа
        //     document.getElementById("calcs").innerHTML = '<tr><td colspan="5">Загрузка...</td></tr>';

        //     const date_from = document.getElementById('date_from').value;
        //     const date_to = document.getElementById('date_to').value;

        //     const params = new URLSearchParams({
        //         date_from: date_from,
        //         date_to: date_to
        //     })

        //     // Запрашиваем данные

        //     const xhr = new XMLHttpRequest();

        //     xhr.open('GET', 'api/invoice_service.php?' + params.toString(), true);
        //     xhr.onload = function () {
        //         if (xhr.status === 401) {
        //             window.location.href = "login.html";
        //         }
                
        //         else if (xhr.status === 200) {
        //             const calcs = JSON.parse(xhr.responseText);
        //             showCalcs(calcs);
        //         }
        //         else {
        //             alert(`Ошибка ${xhr.status}: ${xhr.responseText}`)
        //         }
        //     }

        //     xhr.send();
        // }
    </script>
</head>
<body>
    <h1>За прошедший период вы совершили следующие операции</h1>

    <div class="filters">
        <h3>Фильтры</h3>
        <label>C: </label>
        <input type="date" id="date_from"/>
        <label>По: </label>
        <input type="date" id="date_to" />

        <label>Результат не больше: </label>
        <input type="number" step="any" id="z_max" />
        <label>Результат не меньше: </label>
        <input type="number" step="any" id="z_min" />

        <button onclick="loadData()">Применить</button>
        <button onclick="resetFilter()">Сбросить</button>
    </div>
    
    <table>
        <thead>
            <tr>
                <th id="header_operation_date" onclick="sortBy('operation_date')">Время</th>
                <th id="header_x" onclick="sortBy('x')">x</th>
                <th id="header_y" onclick="sortBy('y')">y</th>
                <th id="header_operation" onclick="sortBy('operation')">Операция</th>
                <th id="header_z" onclick="sortBy('z')">Результат</th>
            </tr>
        </thead>
        <tbody id="calcs">
            <tr>
                <td colspan="5">Загрузка...</td>
            </tr>
        </tbody>
    </table>
    <div>
        <button id="prev" onclick="prevPage()">Назад</button>
        <span id="page-info"></span>
        <button id="next" onclick="nextPage()">Вперед</button>
    </div>
</body>
</html>
