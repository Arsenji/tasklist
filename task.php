<?php
session_start();
// Обработка добавления новой задачи
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once("includes/connection.php");

    $user_id = $_SESSION['user_id'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $created_at = date('Y-m-d');
    $status = 'Невыполнено';

    if (!empty($user_id) && !empty($description)) {
        $sql = "INSERT INTO tasklist.tasks (user_id, description, created_at, status) VALUES ('$user_id', '$description', '$created_at', '$status')";
        mysqli_query($conn, $sql);
    }

    if (isset($_POST['delete_all']) && !empty($description)) {
        $sql = "DELETE FROM tasks WHERE user_id = '$user_id'";
        mysqli_query($conn, $sql);
    }
}

// Обработка изменения статуса задачи
if (isset($_GET['task_id']) && isset($_GET['status'])) {
    require_once("includes/connection.php");

    $task_id = $_GET['task_id'];
    $status = $_GET['status'];

    $sql = "UPDATE tasks SET status = '$status' WHERE id = '$task_id'";
    mysqli_query($conn, $sql);
}

// Обработка удаления задачи
if (isset($_GET['delete_task_id'])) {
    require_once("includes/connection.php");

    $task_id = $_GET['delete_task_id'];

    $sql = "DELETE FROM tasks WHERE id = '$task_id'";
    mysqli_query($conn, $sql);
}

// Обработка удаления всех задач
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_all'])) {
    require_once("includes/connection.php");

    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM tasks WHERE user_id = '$user_id'";
    mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="includes/style.css" rel="stylesheet">
    <title>Task List</title>
</head>
<body>
<div class="container">
    <h2>Мои задачи</h2>

    <form method="POST" action="">
        <input type="text" name="description" placeholder="Введите задачу">
        <button type="submit" name="addTask" id="addTask">Добавить задачу</button>
        <button type="submit" name="delete_all">Удалить все</button>
    </form>


    <table>
        <tr>
            <th>Задача</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>

        <?php
        require_once("includes/connection.php");

        $user_id = $_SESSION['user_id'];

        $sql = "SELECT * FROM tasks WHERE user_id = '$user_id'";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>";
            echo ($row['status'] == 'Выполнено') ? '<span class="status-circle completed"></span>' : '<span class="status-circle not-completed"></span>';
            echo $row['status'];
            echo "</td>";
            echo "<td>";
            echo '<a class="btn" href="task.php?task_id=' . $row['id'] . '&status=' . (($row['status'] == 'Выполнено') ? 'Невыполнено' : 'Выполнено') . '">' . (($row['status'] == 'Выполнено') ? 'Невыполнено' : 'Выполнено') . '</a>';
            echo '<a class="delete" href="task.php?delete_task_id=' . $row['id'] . '">Удалить</a>';
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
