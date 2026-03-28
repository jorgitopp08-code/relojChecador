<?php include("db.php"); ?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
<div class="container mt-5">
<h2>Ingreso al Sistema</h2>
<form method="POST">
<input class="form-control mb-2" name="usuario" placeholder="Usuario">
<input class="form-control mb-2" name="pin" type="password" placeholder="PIN">
<button class="btn btn-primary">Ingresar</button>
</form>
</div>
<?php
if($_POST){
$u=$_POST['usuario'];
$p=$_POST['pin'];
$res=$conn->query("SELECT * FROM usuarios WHERE usuario='$u' AND pin='$p'");
if($res->num_rows>0){
$_SESSION['user']=$res->fetch_assoc();
header("Location: dashboard.php");
}else{
echo "<div class='text-danger'>Error</div>";
}
}
?>
</body>
</html>