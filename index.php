<?php
session_start(); // начинаем сессию

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once("includes/connection.php");

// получаем логин и пароль из формы
    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $registrationDate = date('Y-m-d');

    $sql = "SELECT * FROM tasklist.users WHERE login = '$login'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $stored_hash = $row['password'];

        if (password_verify($password, $stored_hash)) {
// Авторизация успешна
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $stored_hash;
            header("location: task.php");
            exit();
        } else {
// Неправильный пароль
            echo "Неправильный пароль.";
        }
    } else {
// Пользователь не найден, регистрируем нового пользователя
        $hash_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO tasklist.users (login, password, created_at) VALUES ('$login', '$hash_password', '$registrationDate')";
        mysqli_query($conn, $sql);

// Получаем user_id только что созданного пользователя
        $user_id = mysqli_insert_id($conn);

// Сохраняем user_id в сессии
        $_SESSION['user_id'] = $user_id;

        header("location: task.php");
        exit();
    }
    $authenticated =true;
    $_SESSION['authenticated'] = $authenticated;

    mysqli_close($conn); // закрываем соединение с базой данных
}
?>

<?php include("includes/header.php") ?>
<form method="POST" id="signin" action="">
    <input type="text" id="login" name="login" placeholder="login" required autocomplete="current-login">
    <input type="password" id="password" name="password" placeholder="password" required
           autocomplete="current-password">
    <button type="submit">Отправить</button>

</form>
</div>
</body>
</html>