<?php

  include 'connect.php';

  $kode = '';
  $query = false;
  $sql = "";
  $dataarray = [];
  if(isset($_GET['kode'])){
    $kode = $_GET['kode'];
  }

  if(isset($_GET['query'])){
    $query = $_GET['query'];
  }

  if( $kode=='f2' && isset($_GET['angkot']) ){
    $angkot = $_GET['angkot'];

    $sql = "select distinct id, name, capacity,address,ST_X(ST_Centroid(geom)) AS longitude, ST_Y(ST_CENTROID(geom)) As latitude,
            'wp'  as type from worship_place join detail_worship_place on worship_place.id = detail_worship_place.id_worship_place where detail_worship_place.id_angkot = '$angkot'
            union
            select distinct id, name, 0 as capacity,address,ST_X(ST_Centroid(geom)) AS longitude, ST_Y(ST_CENTROID(geom)) As latitude
            , 'si'  as type from small_industry join detail_small_industry on small_industry.id = detail_small_industry.id_small_industry where detail_small_industry.id_angkot = '$angkot'
            union
            select distinct id, name, 0 as capacity,address,ST_X(ST_Centroid(geom)) AS longitude, ST_Y(ST_CENTROID(geom)) As latitude
            , 's'  as type from souvenir join detail_souvenir on souvenir.id = detail_souvenir.id_souvenir where detail_souvenir.id_angkot = '$angkot'";
  }

  if($query){
    die($sql);
  }

  if($sql != '' && $kode != 'type_event'){
    $eks = pg_query($sql);
    while($row = pg_fetch_array($eks))
    {
      $id=$row['id'];
      $name=$row['name'];
      $address=$row['address'];
      $capacity=$row['capacity'];
      $longitude=$row['longitude'];
      $latitude=$row['latitude'];
      $type = 0;
      if($row['type']){
        $type = $row['type'];
      }
      $dataarray[]=array('id'=>$id,'name'=>$name, 'address'=>$address, 'capacity'=>$capacity, 'longitude'=>$longitude,'latitude'=>$latitude,'type'=>$type);
    }
  }
  echo json_encode ($dataarray);

?>
