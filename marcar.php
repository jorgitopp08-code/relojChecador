<?php include("db.php"); ?>
<form method="POST">
<input name="empleado_id" placeholder="ID">
<button name="accion" value="ingreso">Entrada</button>
<button name="accion" value="inicio">Inicio</button>
<button name="accion" value="fin">Fin</button>
<button name="accion" value="salida">Salida</button>
</form>
<?php
if($_POST){
$id=$_POST['empleado_id'];
$accion=$_POST['accion'];
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$q=$conn->query("SELECT * FROM jornadas WHERE empleado_id=$id AND fecha='$fecha'");
if($q->num_rows==0){
$conn->query("INSERT INTO jornadas (empleado_id,fecha,hora_ingreso)
VALUES ($id,'$fecha','$hora')");
}else{
if($accion=="inicio"){
$conn->query("UPDATE jornadas SET inicio_refrigerio='$hora' WHERE empleado_id=$id AND fecha='$fecha'");
}
if($accion=="fin"){
$conn->query("UPDATE jornadas SET fin_refrigerio='$hora' WHERE empleado_id=$id AND fecha='$fecha'");
}
if($accion=="salida"){
$conn->query("UPDATE jornadas SET hora_salida='$hora' WHERE empleado_id=$id AND fecha='$fecha'");
}
}
echo "OK";
}
?>