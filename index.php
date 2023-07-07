<?php
session_start(); // начинаем сессию
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once("includes/connection.php");

    // получаем email и пароль из формы, указываем дату
    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $registrationDate = date('Y-m-d');

    $sql = "SELECT * FROM tasklist.users WHERE login = '$login' AND password = '$password'";


    $result = mysqli_query($conn, $sql);

    $_SESSION['login'] = $_POST['login'];
    $_SESSION['password'] = $_POST['password'];

    if (mysqli_num_rows($result) == 1) {
        // авторизуем пользователя
        $_SESSION['login'] = $login;
        $_SESSION['password'] = $password;
        header("location: task.php"); // переходим на страницу task
    } else {
        $sql = "INSERT INTO tasklist.users (login, password, created_at) VALUES ('$login', '$password', '$registrationDate')";
        //header("location: task.php"); // переходим на страницу task
    }
    var_dump($sql);
    mysqli_close($conn); // закрываем соединение с базой данных
}


?>

<?php include("includes/header.php")?>
<form method="POST" id="signin" action="">
    <input type="text" id="login" name="login" placeholder="login" required autocomplete="current-login">
    <input type="password" id="password" name="password" placeholder="password" required autocomplete="current-password">
    <button type="submit">Отправить</button>

</form>
</div>
<?php include("includes/footer.php")?>