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

  if( $kode=='f2' && isset($_GET['angkot']) && isset($_GET['fasilitas']) && isset($_GET['kondisi']) ){
    $angkot = $_GET['angkot'];
    $kondisi = $_GET['kondisi'];
    $fasilitas = $_GET['fasilitas'];

    $sql = "select distinct worship_place.id, worship_place.name, worship_place.address, worship_place.capacity,ST_X(ST_Centroid(worship_place.geom)) AS longitude, ST_Y(ST_CENTROID(worship_place.geom)) As latitude
            from worship_place
            join detail_worship_place as dwp on worship_place.id = dwp.id_worship_place
            join angkot on dwp.id_angkot = angkot.id
            join detail_facility as df on worship_place.id = df.id_worship_place
            join facility on df.id_facility = facility.id
            join facility_condition on df.id_facility_condition = facility_condition.id
            where angkot.id = '$angkot'
            and facility_condition.id in ($kondisi)
            and facility.id in ($fasilitas)";
  }
  else if( $kode=='f3' && isset($_GET['ustad']) && isset($_GET['event']) && isset($_GET['warna']) ){
    $ustad = $_GET['ustad'];
    $event = $_GET['event'];
    $warna = $_GET['warna'];

    $sql = "select * from worship_place
    join detail_event on worship_place.id=detail_event.id_worship_place
    join ustad on detail_event.id_ustad=ustad.id
    join event on detail_event.id_event=event.id
    join detail_worship_place on worship_place.id=detail_worship_place.id_worship_place
    join angkot on detail_worship_place.id_angkot=angkot.id
    join angkot_color on angkot.id_color=angkot_color.id
    where ustad.name like '%$ustad%'
    AND angkot_color.color= '$warna'
    AND event.name like '%$event%'";
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
      $dataarray[]=array('id'=>$id,'name'=>$name, 'address'=>$address, 'capacity'=>$capacity, 'longitude'=>$longitude,'latitude'=>$latitude);
    }
  }
  echo json_encode ($dataarray);

?>
