<?php

  include 'connect.php';

  $kode = '';
  $query = false;
  $sql = "";
  $nsql = "";
  $dataarray = [];
  if(isset($_GET['kode'])){
    $kode = $_GET['kode'];
  }

  if(isset($_GET['query'])){
    $query = $_GET['query'];
  }

  if( $kode=='f2' && isset($_GET['hotel_type']) && isset($_GET['fasilitas_hotel']) && isset($_GET['rad']) ){
    $fasilitas = $_GET['fasilitas_hotel'];
    $type = $_GET['hotel_type'];
    $rad = $_GET['rad'];

    $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as lng,st_y(st_centroid(worship_place.geom)) as lat, 'wp' as cat from worship_place
            join hotel on ST_DistanceSphere(worship_place.geom, hotel.geom) < $rad
            join hotel_type on hotel.id_type = hotel_type.id
            join detail_facility_hotel dfh on hotel.id = dfh.id_hotel
            join facility_hotel on dfh.id_facility = facility_hotel.id
            where hotel_type.id = '$type'
            and facility_hotel.id in ($fasilitas)
            union
            select distinct hotel.id, hotel.name, st_x(st_centroid(hotel.geom)) as lng,st_y(st_centroid(hotel.geom)) as lat, 'h' as cat from hotel
            join hotel_type on hotel.id_type = hotel_type.id
            join detail_facility_hotel dfh on hotel.id = dfh.id_hotel
            join facility_hotel on dfh.id_facility = facility_hotel.id
            where hotel_type.id = '$type'
            and facility_hotel.id in ($fasilitas)";
  }
  else if( $kode=='f3' && isset($_GET['garin']) && isset($_GET['event']) && isset($_GET['ustad']) ){
      $garin = $_GET['garin'];
      $event = $_GET['event'];
      $ustad = $_GET['ustad'];

      $sql = "select distinct a.id, a.name, a.address, a.capacity,ST_X(ST_Centroid(a.geom)) AS longitude, ST_Y(ST_CENTROID(a.geom)) As latitude
              FROM worship_place as a
              join administrators on a.id = administrators.id_worship_place and role = 'P'
              join detail_event as de on a.id = de.id_worship_place
              join event on de.id_event = event.id
              join ustad on de.id_ustad = ustad.id
              where administrators.name like '%$garin%'
              and event.name like '%$event%'
              and ustad.name like '%$ustad%'";
  }
  else if( $kode=='f4' && isset($_GET['hotel_type']) && isset($_GET['fasilitas_hotel']) && isset($_GET['rad']) ){
    $fasilitas = $_GET['fasilitas_hotel'];
    $type = $_GET['hotel_type'];
    $rad = $_GET['rad'];

    $sql = "";
  }
  else if( $kode=='f5' && isset($_GET['hotel_type']) && isset($_GET['fasilitas_hotel']) && isset($_GET['rad']) ){
    $fasilitas = $_GET['fasilitas_hotel'];
    $type = $_GET['hotel_type'];
    $rad = $_GET['rad'];

    $sql = "";
  }






  if($query){
    die($sql);
  }

  if($sql != ''){
    $eks = pg_query($sql);
    while($row = pg_fetch_array($eks))
    {
      $id=$row['id'];
      $name=$row['name'];
      $longitude=$row['longitude'];
      $latitude=$row['latitude'];
      $cat = $row['cat'];
      $dataarray[]=array('id'=>$id,'name'=>$name, 'longitude'=>$longitude,'latitude'=>$latitude, 'cat'=>$cat);
    }
  }
  echo json_encode ($dataarray);

?>
