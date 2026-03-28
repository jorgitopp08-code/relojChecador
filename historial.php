<?php include("db.php");
$res=$conn->query("SELECT e.nombre,j.* FROM jornadas j JOIN empleados e ON e.id=j.empleado_id");
while($r=$res->fetch_assoc()){
echo $r['nombre']." ".$r['fecha']."<br>";
}
?>