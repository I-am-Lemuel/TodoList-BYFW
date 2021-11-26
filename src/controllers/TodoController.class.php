<?php

use \RedBeanPHP\R as R;

class TodoController
{

    public function index($twig)
    {
        require_once "services/UserService.class.php";
        $services = (new UserService())->validateLoggedIn();
        $todoItems = R::find('todoitems', ' ORDER BY pos ASC');
        echo $twig->render("todoIndex.html", ['todoItems' => $todoItems]);
    }
    public function logout()
    {
        session_destroy();
        session_unset();
        R::exec('DELETE FROM sessions WHERE 1=1');
        header('Location: /user/login');
        echo "gg";
    }

    public function add($twig)
    {
        require_once "services/UserService.class.php";
        $services = (new UserService())->validateLoggedIn();
        echo $twig->render("todoAdd.html");
    }

    public function addPost()
    {
        $_POST;
        $todoItems = R::dispense('todoitems');
        $todoItems->item = $_POST['item'];
        $todoItems->pos = R::getCell("SELECT MAX(pos) FROM todoitems") + 1;
        $todoItems->status = 'notdone';
        R::store($todoItems);
        header("Location:/todo/index");
    }

    public function movePost()
    {
        $oldPos = intval($_POST['index']);
        $newPos = intval($_POST['indexDrop']);
        echo $oldPos . PHP_EOL;
        echo $newPos . PHP_EOL;
        if ($newPos > $oldPos) {
            $delta = -1;
            $query = 'pos > ? AND pos <= ?';
        } else {
            $delta = 1;
            $query = 'pos < ? AND pos >= ?';
        }
        $movedTodoItem = R::findone('todoitems', 'pos = ?', [$oldPos]);
        $nextItems = R::find('todoitems', $query, [$oldPos, $newPos]);
        $delta = $newPos > $oldPos ? -1 : 1;
        foreach ($nextItems as $nextItem) {
            $nextItem->pos += $delta;
            R::store($nextItem);
        }
        $movedTodoItem["pos"] = $newPos;
        R::store($movedTodoItem);
    }

    public function updatePost()
    {
        $pos = $_POST['pos'];
        $title = $_POST['title'];
        $updatedTodoItem = R::findone('todoitems', 'pos = ?', [$pos]);
        $updatedTodoItem->item = $title;
        R::store($updatedTodoItem);
        
    }
    public function togglePost()
    {
        $pos = $_POST['pos'];
        $statusTodoItem = R::findone('todoitems', 'pos = ?', [$pos]);
        if ($statusTodoItem->status == 'notdone') {
            $statusTodoItem->status = 'done';
            R::store($statusTodoItem);
            die();
        }
        if ($statusTodoItem->status == 'done') {
            $statusTodoItem->status = 'notdone';
            R::store($statusTodoItem);
            die();
        }
    }
    public function removePost()
    {
        $pos = $_POST['pos'];
        $removeTodoItem = R::findone('todoitems', 'pos = ?', [$pos]);
        R::trash( $removeTodoItem );
        R::exec( 'update todoitems set pos = pos - 1 where pos > ?',[$pos] );

        
    }
}

