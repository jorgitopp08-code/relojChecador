<?php include("db.php"); ?>
<form method="POST">
<input name="cedula" placeholder="Cedula">
<input name="nombre" placeholder="Nombre">
<input name="cargo" placeholder="Cargo">
<button>Guardar</button>
</form>
<?php
if($_POST){
$conn->query("INSERT INTO empleados (cedula,nombre,cargo)
VALUES ('$_POST[cedula]','$_POST[nombre]','$_POST[cargo]')");
}
$res=$conn->query("SELECT * FROM empleados");
while($r=$res->fetch_assoc()){
echo $r['nombre']."<br>";
}
?>