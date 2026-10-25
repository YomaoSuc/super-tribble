<?php
  session_start();
  // echo $_SESSION['name'];
  if(!isset($_SESSION['name'])) {
    echo("<h2>Вы не вошли в аккаунт! <br /> Через 3 секунды вы будете перенаправлены на страницу логина</h2> <br />");
    echo("<a href='login.html'>Войти в аккаунт</a>");
    // Правильный вариант
    // header('Location: login.html');
    // Ещё одинн способ
    echo("<meta http-equiv='refresh' content='3; url=login.html'></meta>");
    die();
  }
?>
<!doctype html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Калькулятор</title>
    <style>
      label {
        font-size: 18px;
        text-align: right;
        width: 90%;
      }
      input {
        width: 90%;
        margin-bottom: 10px;
        background-color: #ffffff;
        border: 1px black solid;
        padding-block: 5px;
      }
      .number {
      }
      #z {
      }
      .field {
        display: flex;
        flex-direction: column;
        width: 200px;
      }
      button {
        width: 180px;
        padding: 5px;
        background-color: #52ccff;
        border: none;
        border-radius: 5px;
      }

      button:disabled {
        background-color: #d8d8d8;
      }
    </style>
    <script>
      function setDisabled(disabled) {
        document.getElementById('plus').disabled = disabled;
        document.getElementById('minus').disabled = disabled;
        document.getElementById('mult').disabled = disabled;
      }

      function calculate(operation) {
        setDisabled(true);
        document.getElementById('z').value = 'Производим расчет...';

        let x = parseFloat(document.getElementById("x").value);
        let y = parseFloat(document.getElementById("y").value);

        const xhr = new XMLHttpRequest();
        const url = `api/operations_service.php?operation=${operation}&x=${x}&y=${y}`;

        xhr.open('GET', url);

        xhr.onload = function () {
          setDisabled(false);

          if(xhr.status === 200) {
            document.getElementById("z").value = xhr.responseText;
          }
          else if(xhr.status === 401) {
            window.location.href = "login.html";
          }
          else {
            document.getElementById("z").value = '';
            alert(`Ошибка ${xhr.status}: ${xhr.responseText}`);
          }
        }

        xhr.send();
      }
    </script>
  </head>
  <body>
    <a href='logout.php'>Выйти из системы</a>
    <h1>Калькулятор</h1>
    <div class="field">
      <label for="x">X</label>
      <input class="number" id="x" />
    </div>
    <div class="field">
      <label for="y">Y</label>
      <input class="number" id="y" />
    </div>
    <button id="plus" onclick="calculate('plus')">+</button>
    <button id="minus" onclick="calculate('minus')">-</button>
    <button id="mult" onclick="calculate('mult')">*</button>
    <div class="field">
      <label for="z">Z</label>
      <input id="z" />
    </div>
  </body>
</html>
