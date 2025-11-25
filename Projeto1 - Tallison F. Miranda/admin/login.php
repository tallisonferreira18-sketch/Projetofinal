<?php
    echo "<h1>Painel Administrativo</h1>";
?>

<?php
    echo "<h2>Longin:<h2>";
?>

<body>
  <form action="processaLogin.php" method="POST">
    <p>Username: <input type="text" name="username" /></p>
    <p>Password: <input type="password" name="password" /></p>
    <p><input type="submit" name="submit" value="Login" /></p>
  </form>
</body>
</html>