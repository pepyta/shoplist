<?php
require 'functions.php';
require_once 'template.php';

if(!isUserLoggedIn($_SESSION['gid'], $_SESSION['ssid'])){
    redirect("auth.php");
}

if (isset($_GET['add'])) {
	$sql = "SELECT * FROM lists WHERE owner='" . encrypt($id) . "' AND name = '" . $_POST['name'] . "'";
	$result = $conn->query($sql);
	if ($result->num_rows == 0) {
		$sql = "INSERT INTO lists (name, owner)
                    VALUES ('" . $_POST['name'] . "', '" . encrypt($id) . "')";
		if ($conn->query($sql) === TRUE) {
			$sql = "UPDATE users SET tutorialComplete = 1 WHERE name = '" . $_SESSION['name'] . "'";
			if ($conn->query($sql)) {
				redirect("index.php");
			}
		}
		else {
			die("Error: " . $sql . "<br />" . $conn->error);
		}
	}
}

if (isset($_GET['additem'])) {
	if ($name !== "") {
		$listid = $_POST['listid'];
		$sql = "SELECT * FROM lists WHERE owner='" . encrypt($id) . "' AND id=$listid";
		$result = $conn->query($sql);
		if ($result->num_rows == 1) {
			$sql = "SELECT * FROM items WHERE name = '" . $_POST['name'] . "' AND inListById = '" . encrypt($listid) . "'";
			$result = $conn->query($sql);
			if ($result->num_rows == 1) {
				$sql = "UPDATE items SET quantity = quantity+" . $_POST['quantity'] . " WHERE inListById='" . encrypt($listid) . "' AND name = '" . $_POST['name'] . "'";
				if ($conn->query($sql) === TRUE) {
					redirect("index.php");
				}
				else {
					die("Error: " . $sql . "<br />" . $conn->error);
				}
			}
			else {
				$sql = "INSERT INTO items (name, quantity, inListById)
                            VALUES ('" . $_POST['name'] . "', '" . $_POST["quantity"] . "', '" . encrypt($listid) . "')";
				if ($conn->query($sql) === TRUE) {
					redirect("index.php");
				}
				else {
					die("Error: " . $sql . "<br />" . $conn->error);
				}
			}
		}
	}
}
$template->parse('html/head.html');
$template->parse('html/navbar.html');
$template->parse('html/index.html');
$template->parse('html/scripts.html');
?>
