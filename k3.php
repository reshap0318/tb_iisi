<?php

  include("connect.php");
  $kode = "";
  $act = "";
  $query = false;

  if(isset($_GET['kode'])){
    $kode = $_GET['kode'];
  }

  if(isset($_GET['query'])){
    $query = $_GET['query'];
  }

  if(isset($_GET['act'])){
    $act = $_GET['act'];
  }

  if($kode=="1a"){
    $ustad = "";
    $kategori = "3";

    if(isset($_GET['ustad'])){
      if($_GET['ustad']){
        $ustad = $_GET['ustad'];
      }
    }

    if(isset($_GET['kategori'])){
      if($_GET['kategori']){
        $kategori = $_GET['kategori'];
      }
    }

    if($act=='data'){
      $sql = "select worship_place.name as mesjid,
      JSON_ARRAY(GROUP_CONCAT(event.name ORDER BY event.name DESC SEPARATOR ' ')) as kegiatans
      from worship_place
      join detail_event on worship_place.id=detail_event.id_worship_place
      join event on event.id=detail_event.id_event
      JOIN ustad on ustad.id=detail_event.id_ustad
      join category_worship_place on category_worship_place.id=worship_place.id_category
      where ustad.name = '$ustad' AND  worship_place.id_category = $kategori GROUP BY mesjid";

      $hasil=pg_query($sql);
      while($row = pg_fetch_array($hasil))
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
    elseif($act=='cari'){
      $sql	="select distinct a.id, a.name, a.address, a.capacity,ST_X(ST_Centroid(a.geom)) AS longitude, ST_Y(ST_CENTROID(a.geom)) As latitude
      					FROM worship_place as a
                left join detail_event on a.id=detail_event.id_worship_place
                left join event on event.id=detail_event.id_event
                left JOIN ustad on ustad.id=detail_event.id_ustad
                left join category_worship_place on category_worship_place.id=a.id_category
                where category_worship_place.id = $kategori";

      if($ustad){
          $sql .= " and ustad.name like '%$ustad%'";
      }

      if($query){
        die($sql);
      }

      $wow = pg_query( $sql);
      $dataarray = [];
      while($row = pg_fetch_array($wow))
      {
        $id=$row['id'];
        $name=$row['name'];
        $address=$row['address'];
        $capacity=$row['capacity'];
        $longitude=$row['longitude'];
        $latitude=$row['latitude'];
        $dataarray[]=array('id'=>$id,'name'=>$name, 'address'=>$address, 'capacity'=>$capacity, 'longitude'=>$longitude,'latitude'=>$latitude);
      }
      echo json_encode ($dataarray);
    }
  }
  elseif($kode=='1d'){
    $garin = "";
    $kategori = "";
    $angkot = "";

    if(isset($_GET['garin'])){
      if($_GET['garin']){
        $garin = $_GET['garin'];
      }
    }

    if(isset($_GET['angkot'])){
      if($_GET['angkot']){
        $angkot = $_GET['angkot'];
      }
    }

    if(isset($_GET['kategori'])){
      if($_GET['kategori']){
        $kategori = $_GET['kategori'];
      }
    }

    if($act=='cari'){
      $sql	="select distinct worship_place.id, worship_place.name, worship_place.address, worship_place.capacity,ST_X(ST_Centroid(worship_place.geom)) AS longitude,
      ST_Y(ST_CENTROID(worship_place.geom)) As latitude
			FROM worship_place
      JOIN category_worship_place on worship_place.id_category = category_worship_place.id
      JOIN administrators on worship_place.id=administrators.id_worship_place
      JOIN detail_worship_place on worship_place.id= detail_worship_place.id_worship_place
      JOIN angkot on detail_worship_place.id_angkot=angkot.id
      WHERE category_worship_place.id = $kategori";

      if($garin!=""){
        $sql .= " and administrators.name like '%$garin%'";
      }

      if($angkot!=""){
        $sql .= " and id_angkot = '$angkot'";
      }

      if($query){
        die($sql);
      }

      $wow = pg_query( $sql);
      $dataarray = [];
      while($row = pg_fetch_array($wow))
      {
        $id=$row['id'];
        $name=$row['name'];
        $address=$row['address'];
        $capacity=$row['capacity'];
        $longitude=$row['longitude'];
        $latitude=$row['latitude'];
        $dataarray[]=array('id'=>$id,'name'=>$name, 'address'=>$address, 'capacity'=>$capacity, 'longitude'=>$longitude,'latitude'=>$latitude);
      }
      echo json_encode ($dataarray);
    }
  }
  elseif($kode=='1ra'){
    $kecamatan = "";
    $angkot_color = "";

    if(isset($_GET['kecamatan'])){
      if($_GET['kecamatan']){
        $kecamatan = $_GET['kecamatan'];
      }
    }

    if(isset($_GET['angkot_color'])){
      if($_GET['angkot_color']){
        $angkot_color = $_GET['angkot_color'];
      }
    }

    if($act=='cari'){
      $sql	="select distinct worship_place.id, worship_place.name, worship_place.address, worship_place.capacity,ST_X(ST_Centroid(worship_place.geom)) AS longitude,
            ST_Y(ST_CENTROID(worship_place.geom)) As latitude
            from worship_place
            join detail_worship_place on worship_place.id=detail_worship_place.id_worship_place
            join angkot on detail_worship_place.id_angkot=angkot.id
            where worship_place.id > '0'";
      if($angkot_color!=""){
        $sql .= " and  angkot.id_color = $angkot_color";
      }

      if($kecamatan!=""){
        $sql .= "  and st_contains((SELECT geom from district where id = '$kecamatan'), worship_place.geom)";
      }

      if($query){
        die($sql);
      }

      $wow = pg_query( $sql);
      $dataarray = [];
      while($row = pg_fetch_array($wow))
      {
        $id=$row['id'];
        $name=$row['name'];
        $address=$row['address'];
        $capacity=$row['capacity'];
        $longitude=$row['longitude'];
        $latitude=$row['latitude'];
        $dataarray[]=array('id'=>$id,'name'=>$name, 'address'=>$address, 'capacity'=>$capacity, 'longitude'=>$longitude,'latitude'=>$latitude);
      }
      echo json_encode ($dataarray);
    }
  }
  elseif($kode=='1y'){
    $kategori = "";
    $kondisi = "";
    $lay = "";

    if(isset($_GET['kategori'])){
      if($_GET['kategori']){
        $kategori = $_GET['kategori'];
      }
    }

    if(isset($_GET['kondisi'])){
      if($_GET['kondisi']){
        $kondisi = $_GET['kondisi'];
      }
    }

    if(isset($_GET['lay'])){
      if($_GET['lay']){
        $lay = $_GET['lay'];
      }
    }

    if($act=='cari'){
      $sql	="select distinct worship_place.id, worship_place.name, worship_place.address, worship_place.capacity,ST_X(ST_Centroid(worship_place.geom)) AS longitude,
              ST_Y(ST_CENTROID(worship_place.geom)) As latitude
              from worship_place
              join category_worship_place on worship_place.id_category=category_worship_place.id
              join detail_facility on worship_place.id=detail_facility.id_worship_place
              join facility on facility.id=detail_facility.id_facility
              join facility_condition on facility_condition.id=detail_facility.id_facility_condition
              WHERE worship_place.id > '0' ";

      if($kategori!=""){
        $sql .= " and category_worship_place.id= '$kategori'";
      }

      if($lay!=""){
        $sql .= " and detail_facility.id_facility in ($lay)";
      }

      if($kondisi!=""){
        $sql .= " and facility_condition.id = '$kondisi'";
      }

      if($query){
        die($sql);
      }

      $wow = pg_query( $sql);
      $dataarray = [];
      while($row = pg_fetch_array($wow))
      {
        $id=$row['id'];
        $name=$row['name'];
        $address=$row['address'];
        $capacity=$row['capacity'];
        $longitude=$row['longitude'];
        $latitude=$row['latitude'];
        $dataarray[]=array('id'=>$id,'name'=>$name, 'address'=>$address, 'capacity'=>$capacity, 'longitude'=>$longitude,'latitude'=>$latitude);
      }
      echo json_encode ($dataarray);
    }
  }
  elseif($kode=='1re'){
    $kategori = "";
    $kecamatan = "";
    $event = "";

    if(isset($_GET['kategori'])){
      if($_GET['kategori']){
        $kategori = $_GET['kategori'];
      }
    }

    if(isset($_GET['kecamatan'])){
      if($_GET['kecamatan']){
        $kecamatan = $_GET['kecamatan'];
      }
    }

    if(isset($_GET['event'])){
      if($_GET['event']){
        $event = $_GET['event'];
      }
    }

    if($act=='cari'){
      $sql	="select distinct worship_place.id, worship_place.name, worship_place.address, worship_place.capacity,ST_X(ST_Centroid(worship_place.geom)) AS longitude,
              ST_Y(ST_CENTROID(worship_place.geom)) As latitude
              from worship_place
              join category_worship_place on worship_place.id_category=category_worship_place.id
              WHERE worship_place.id > '0'
              ";

      if($kategori){
        $sql .= " and category_worship_place.id = '$kategori'";
      }

      if($kecamatan){
        $sql .= "and st_contains((SELECT geom from district where id = '$kecamatan'), worship_place.geom)";
      }

      if($event){
        $sql .= "and worship_place.id in (select id_worship_place from detail_event where id_event in
        (SELECT id from event where id_type_event = '$event'))";
      }

      if($query){
        die($sql);
      }

      $wow = pg_query( $sql);
      $dataarray = [];
      while($row = pg_fetch_array($wow))
      {
        $id=$row['id'];
        $name=$row['name'];
        $address=$row['address'];
        $capacity=$row['capacity'];
        $longitude=$row['longitude'];
        $latitude=$row['latitude'];
        $dataarray[]=array('id'=>$id,'name'=>$name, 'address'=>$address, 'capacity'=>$capacity, 'longitude'=>$longitude,'latitude'=>$latitude);
      }
      echo json_encode ($dataarray);
    }
  }
?>
