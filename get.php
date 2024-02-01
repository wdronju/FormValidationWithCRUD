<?php
echo $_GET['q'] ?? null;

?>

<form method="get" action="<?php $_SERVER['PHP_SELF' ] ?>">
    <input type="text" name="q" placeholder="Your name" id="">
    <input type="submit" value="Submit">
</form>