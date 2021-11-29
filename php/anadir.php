<?php
  class Anadir{
    function __construct(){
      include_once 'conexion.php';
      $this->conexion = new Conexion();

      $this->anadirPuntuacion();
    }
    function anadirPuntuacion(){
      //CONSULTA LA PUNTUACIÓN MÍNIMA EN LA BASE DE DATOS DE UN JUEGO ESPECÍFICO
      $sql = "select puntuacion from partida where idJuego = ".$_POST['idJuego']." and puntuacion = (select min(puntuacion) from partida where idJuego = ".$_POST['idJuego'].");";
      $resultado = $this->conexion->consultar($sql);
      $fila = mysqli_fetch_assoc($resultado);

      //SI LA PUNTUACIÓN MÍNIMA ES INFERIOR A LA PUNTUACIÓN NUEVA CONSULTA EL NUMERO DE PUNTUACIONES GUARDADAS DE ESE JUEGO
      if($fila['puntuacion']<$_POST['puntuacion']){
        $sql = "select * from partida where idJuego=".$_POST['idJuego'].";";
        $resultado = $this->conexion->consultar($sql);

        //SI EL NUMERO DE PUNTUACIONES ES MAYOR O IGUAL A 10 ELIMINA LA PUNTUACIÓN MAS BAJA
        if($resultado->num_rows>=10){
          $sql = "delete from partida
          where idJuego = ".$_POST['idJuego']." and puntuacion = (select min(puntuacion) from partida where idJuego = ".$_POST['idJuego'].");";
          $resultado = $this->conexion->consultar($sql);
        }
        
        //GUARDA LA NUEVA PUNTUACIÓN
        $sql = "insert into partida(nombreUsuario, idJuego, puntuacion) values ('".$_POST['nombreUsuario']."', ".$_POST['idJuego'].", ".$_POST['puntuacion'].")";
        $resultado = $this->conexion->consultar($sql);
      }
    }
  }

  new Anadir();
