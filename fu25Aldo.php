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

  if( $kode=='f2' && isset($_GET['event']) && isset($_GET['facilitas']) ){
    $facilitas = $_GET['facilitas'];
    $event = $_GET['event'];

    $sql = "select distinct a.id, a.name, a.address, a.capacity,ST_X(ST_Centroid(a.geom)) AS longitude, ST_Y(ST_CENTROID(a.geom)) As latitude
            FROM worship_place as a
            join detail_facility on a.id = detail_facility.id_worship_place
            join facility on detail_facility.id_facility = facility.id
            join detail_event on detail_event.id_worship_place = a.id
            join event on detail_event.id_event = event.id
            where event.name like '%$event%'
            and facility.id in ($facilitas)";
  }
  else if( $kode=='f3' && isset($_GET['garin']) && isset($_GET['kondisi']) && isset($_GET['facilitas']) ){
    $facilitas = $_GET['facilitas'];
    $garin = $_GET['garin'];
    $kondisi = $_GET['kondisi'];

    $sql = "select distinct a.id, a.name, a.address, a.capacity,ST_X(ST_Centroid(a.geom)) AS longitude, ST_Y(ST_CENTROID(a.geom)) As latitude,
            facility.name as name_facilitas, facility.id as fac_id, facility_condition.id as id_fc, facility_condition.condition as name_fc, administrators.name as garin
            FROM worship_place as a
            join detail_facility on a.id = detail_facility.id_worship_place
            join facility on detail_facility.id_facility = facility.id
            join facility_condition on detail_facility.id_facility_condition = facility_condition.id
            join administrators on a.id = administrators.id_worship_place and role = 'P'
            where facility.id in ($facilitas)
            and facility_condition.id = $kondisi
            and administrators.name like '%$garin%'";
    }
    else if( $kode=='f4' && isset($_GET['garin']) && isset($_GET['event']) && isset($_GET['ustad']) ){
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
      else if( $kode=='f5' && isset($_GET['type_event']) && isset($_GET['facility']) ){
        $type_event = $_GET['type_event'];
        $facility = $_GET['facility'];

        $sql = "select distinct a.id, a.name, a.address, a.capacity,ST_X(ST_Centroid(a.geom)) AS longitude, ST_Y(ST_CENTROID(a.geom)) As latitude
                FROM worship_place as a
                join detail_event as de on a.id = de.id_worship_place
                join event on de.id_event = event.id
                join type_event as te on event.id_type_event = te.id
                join detail_facility as df on a.id = df.id_worship_place
                join facility on df.id_facility = facility.id
                where te.id in ($type_event)
                and facility.id in ($facility)";
        }
        else if($kode=='type_event'){
          $sql = 'select * from type_event';
          $eks = pg_query($sql);
          while($row = pg_fetch_array($eks)){
            $id = $row['id'];
            $name = $row['name'];
            $dataarray[] = ['id'=>$id,'name'=>$name];
          }
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
