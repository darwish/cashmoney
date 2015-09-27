<?php

require __DIR__ . '/bootstrap.php';

$data = new CashMoney\Data\Data();
$action = isset($_GET['action']) ? $_GET['action'] : null;

$expense = $data->getExpenseByID($_POST['expenseId']);
$users = $expense->getUsedBy();
switch ($action) {
    case "add":
        $users[] = $data->getUserByID($_POST['debtorID']);
        break;
    case "remove":
        $users = array_filter($users, function($el) {return $el->getID()!=$_POST['debtorID'];});
        break;
}
$expense->setUsedBy($users);
$data->save();

// super fragile
$expenses = $data->getExpenses();
header("content-type:application/json");
echo json_encode([$data->getExpenses(), $data->getUsers(), $data->splitExpenses($expenses)]);
