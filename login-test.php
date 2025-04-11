<?php
// Simple test form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Form submitted with username: ".htmlspecialchars($_POST['username']);
    exit;
}
?>
<form method="post">
    <input type="text" name="username">
    <input type="submit" value="Test">
</form>