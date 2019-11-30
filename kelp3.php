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
  //astri
  if( $kode=='f1' && isset($_GET['kategori']) && isset($_GET['ustad_name']) ){
    $kategori = $_GET['kategori'];
    $ustad_name = $_GET['ustad_name'];

    $sql = "select distinct worship_place.id, worship_place.name, ST_X(ST_Centroid(worship_place.geom)) AS longitude, ST_Y(ST_CENTROID(worship_place.geom)) As latitude, 'wp' as cat
    FROM worship_place
    left join detail_event on worship_place.id=detail_event.id_worship_place
    left join event on event.id=detail_event.id_event
    left JOIN ustad on ustad.id=detail_event.id_ustad
    left join category_worship_place on category_worship_place.id=worship_place.id_category
    where category_worship_place.id = $kategori and ustad.name like '%$ustad_name%'";

  }
  //astri
  else if( $kode=='f2' && isset($_GET['angkot']) ){
      $angkot = $_GET['angkot'];

      $sql = "select distinct id, name, capacity,address,ST_X(ST_Centroid(geom)) AS longitude,
      ST_Y(ST_CENTROID(geom)) As latitude, 'wp' as cat from worship_place
      join detail_worship_place on worship_place.id = detail_worship_place.id_worship_place
      where detail_worship_place.id_angkot = '$angkot'
      union
      select distinct id, name, 0 as capacity,
      address,ST_X(ST_Centroid(geom)) AS longitude, ST_Y(ST_CENTROID(geom)) As latitude ,
      'si' as cat from small_industry
      join detail_small_industry on small_industry.id = detail_small_industry.id_small_industry
      where detail_small_industry.id_angkot = '$angkot'
      union
      select distinct id, name, 0 as capacity,address,ST_X(ST_Centroid(geom)) AS longitude,
      ST_Y(ST_CENTROID(geom)) As latitude , 's' as cat from souvenir
      join detail_souvenir on souvenir.id = detail_souvenir.id_souvenir
      where detail_souvenir.id_angkot = '$angkot'";
  }
  //me
  else if( $kode=='f3' && isset($_GET['event']) && isset($_GET['fasilitas']) ){
    $fasilitas = $_GET['fasilitas'];
    $event = $_GET['event'];

    $sql = "select distinct a.id, a.name, a.address, a.capacity,ST_X(ST_Centroid(a.geom)) AS longitude, ST_Y(ST_CENTROID(a.geom)) As latitude, 'wp' as cat
            FROM worship_place as a
            join detail_facility on a.id = detail_facility.id_worship_place
            join facility on detail_facility.id_facility = facility.id
            join detail_event on detail_event.id_worship_place = a.id
            join event on detail_event.id_event = event.id
            where event.name like '%$event%'
            and facility.id in ($fasilitas)";
  }
  else if( $kode=='f4' && isset($_GET['angkot']) && isset($_GET['hotel_type'])  && isset($_GET['rad']) ){
      $angkot = $_GET['angkot'];
      $hotel_type = $_GET['hotel_type'];
      $rad = $_GET['rad'];

      $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat from worship_place
            join hotel on ST_DistanceSphere(worship_place.geom, hotel.geom) < $rad
            join hotel_type on hotel.id_type = hotel_type.id
            join detail_hotel on hotel.id = detail_hotel.id_hotel
            join detail_worship_place on worship_place.id = detail_worship_place.id_worship_place
            join angkot on detail_worship_place.id_angkot = angkot.id
            where angkot.id = '$angkot'
            and hotel_type.id = '$hotel_type'
            union
            select distinct hotel.id, hotel.name, st_x(st_centroid(hotel.geom)) as longitude,st_y(st_centroid(hotel.geom)) as latitude, 'h' as cat from hotel
            join detail_hotel on hotel.id = detail_hotel.id_hotel
            join angkot on detail_hotel.id_angkot = angkot.id
            join hotel_type on hotel.id_type = hotel_type.id
            where angkot.id = '$angkot'
            and hotel_type.id = '$hotel_type'";
  }
  else if( $kode=='f5' && isset($_GET['kategori']) && isset($_GET['ustad_name']) ){
      $kategori = $_GET['kategori'];
      $ustad_name = $_GET['ustad_name'];

      $sql = "";
  }
  //dina
  else if( $kode=='f6' && isset($_GET['kategori']) && isset($_GET['garin']) && isset($_GET['angkot']) ){
      $kategori = $_GET['kategori'];
      $garin = $_GET['garin'];
      $angkot = $_GET['angkot'];

      $sql = "select distinct worship_place.id, worship_place.name,ST_X(ST_Centroid(worship_place.geom)) AS longitude, ST_Y(ST_CENTROID(worship_place.geom)) As latitude, 'wp' as cat
      FROM worship_place
      JOIN category_worship_place on worship_place.id_category = category_worship_place.id
      JOIN administrators on worship_place.id=administrators.id_worship_place
      JOIN detail_worship_place on worship_place.id= detail_worship_place.id_worship_place
      JOIN angkot on detail_worship_place.id_angkot=angkot.id
      WHERE category_worship_place.id = $kategori
      and administrators.name like '%$garin%'
      and id_angkot = '$angkot'";
  }
  //me
  else if( $kode=='f7' && isset($_GET['garin']) && isset($_GET['kondisi']) && isset($_GET['fasilitas']) ){
    $fasilitas = $_GET['fasilitas'];
    $garin = $_GET['garin'];
    $kondisi = $_GET['kondisi'];

    $sql = "select distinct a.id, a.name,ST_X(ST_Centroid(a.geom)) AS longitude, ST_Y(ST_CENTROID(a.geom)) As latitude, 'wp' as cat
            FROM worship_place as a
            join detail_facility on a.id = detail_facility.id_worship_place
            join facility on detail_facility.id_facility = facility.id
            join facility_condition on detail_facility.id_facility_condition = facility_condition.id
            join administrators on a.id = administrators.id_worship_place and role = 'P'
            where facility.id in ($fasilitas)
            and facility_condition.id = $kondisi
            and administrators.name like '%$garin%'";
  }
  else if( $kode=='f8' && isset($_GET['waktu']) && isset($_GET['angkot'])  && isset($_GET['rad']) ){
      $waktu = $_GET['waktu'];
      $angkot = $_GET['angkot'];
      $rad = $_GET['rad'];

      $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat from worship_place
            join restaurant on ST_DistanceSphere(worship_place.geom, restaurant.geom) < $rad
            join detail_restaurant on restaurant.id = detail_restaurant.id_restaurant
            join detail_worship_place on worship_place.id = detail_worship_place.id_worship_place
            join angkot on detail_worship_place.id_angkot = angkot.id
            where '$waktu' between restaurant.open and restaurant.close
            and angkot.id = '$angkot'
            union
            select distinct restaurant.id, restaurant.name, st_x(st_centroid(restaurant.geom)) as longitude,st_y(st_centroid(restaurant.geom)) as latitude, 'r' as cat from restaurant
            join detail_restaurant on restaurant.id = detail_restaurant.id_restaurant
            join angkot on detail_restaurant.id_angkot = angkot.id
            where '$waktu' between restaurant.open and restaurant.close
            and angkot.id = '$angkot'";
  }
  else if( $kode=='f9' && isset($_GET['max']) && isset($_GET['min'])  && isset($_GET['fasilitas_culinary']) && isset($_GET['rad']) ){
      $fasilitas_culinary = $_GET['fasilitas_culinary'];
      $fasilitas_culinary = str_replace(",","','",$fasilitas_culinary);
      $max = $_GET['max'];
      $min = $_GET['min'];
      $rad = $_GET['rad'];

      $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,
              st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat
              from worship_place
              join culinary_place on ST_DistanceSphere(worship_place.geom, culinary_place.geom) < $rad
              join detail_culinary on culinary_place.id = detail_culinary.id_culinary_place
              join detail_facility_culinary as dfc on dfc.id_culinary_place = culinary_place.id
              join facility_culinary on dfc.id_facility = facility_culinary.id
              where facility_culinary.id in ('$fasilitas_culinary')
              and detail_culinary.price between $min and $max
              union
              select distinct culinary_place.id, culinary_place.name, st_x(st_centroid(culinary_place.geom)) as longitude,st_y(st_centroid(culinary_place.geom)) as latitude,
              'c' as cat
              from culinary_place
              join detail_culinary on culinary_place.id = detail_culinary.id_culinary_place
              join detail_facility_culinary as dfc on dfc.id_culinary_place = culinary_place.id
              join facility_culinary on dfc.id_facility = facility_culinary.id
              where facility_culinary.id in ('$fasilitas_culinary')
              and detail_culinary.price between $min and $max";
  }
  else if( $kode=='f10' && isset($_GET['fasilitas_tourism']) && isset($_GET['type_tourism'])  && isset($_GET['waktu']) && isset($_GET['rad']) ){
      $fasilitas_tourism = $_GET['fasilitas_tourism'];
      $fasilitas_tourism = str_replace(",","','",$fasilitas_tourism);
      $type_tourism = $_GET['type_tourism'];
      $waktu = $_GET['waktu'];
      $rad = $_GET['rad'];

      $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat from worship_place
            join tourism on ST_DistanceSphere(worship_place.geom, tourism.geom) < $rad
            join tourism_type on tourism.id_type = tourism_type.id
            join detail_facility_tourism on tourism.id = detail_facility_tourism.id_tourism
            join facility_tourism on detail_facility_tourism.id_facility = facility_tourism.id
            where tourism_type.id = '$type_tourism'
            and facility_tourism.id in ('$fasilitas_tourism')
            and '$waktu' between tourism.open and tourism.close
            union
            select distinct tourism.id, tourism.name, st_x(st_centroid(tourism.geom)) as longitude,st_y(st_centroid(tourism.geom)) as latitude, 't' as cat from tourism
            join tourism_type on tourism.id_type = tourism_type.id
            join detail_facility_tourism on tourism.id = detail_facility_tourism.id_tourism
            join facility_tourism on detail_facility_tourism.id_facility = facility_tourism.id
            where tourism_type.id = '$type_tourism'
            and facility_tourism.id in ('$fasilitas_tourism')
            and '$waktu' between tourism.open and tourism.close";
  }
  //randa
  else if( $kode=='f11' && isset($_GET['kecamatan']) && isset($_GET['angkot_color']) ){
      $kecamatan = $_GET['kecamatan'];
      $angkot_color = $_GET['angkot_color'];

      $sql = "select distinct worship_place.id, worship_place.name,ST_X(ST_Centroid(worship_place.geom)) AS longitude, ST_Y(ST_CENTROID(worship_place.geom)) As latitude, 'wp' as cat
      from worship_place
      join detail_worship_place on worship_place.id=detail_worship_place.id_worship_place
      join angkot on detail_worship_place.id_angkot=angkot.id
      where st_contains((SELECT geom from district where id = '$kecamatan'), worship_place.geom)
      and angkot.id_color = $angkot_color";
  }
  //me
  else if( $kode=='f12' && isset($_GET['type_event']) && isset($_GET['fasilitas']) ){
    $type_event = $_GET['type_event'];
    $fasilitas = $_GET['fasilitas'];

    $sql = "select distinct a.id, a.name, a.address, a.capacity,ST_X(ST_Centroid(a.geom)) AS longitude, ST_Y(ST_CENTROID(a.geom)) As latitude, 'wp' as cat
            FROM worship_place as a
            join detail_event as de on a.id = de.id_worship_place
            join event on de.id_event = event.id
            join type_event as te on event.id_type_event = te.id
            join detail_facility as df on a.id = df.id_worship_place
            join facility on df.id_facility = facility.id
            where te.id in ($type_event)
            and facility.id in ($fasilitas)";
  }
  else if( $kode=='f13' && isset($_GET['produk']) && isset($_GET['status'])  && isset($_GET['rad']) ){
      $produk = $_GET['produk'];
      $status = $_GET['status'];
      $rad = $_GET['rad'];

      $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat from worship_place
              join small_industry on ST_DistanceSphere(worship_place.geom, small_industry.geom) < $rad
              join status on small_industry.id_status = status.id
              join detail_product_small_industry as dpsi on small_industry.id = dpsi.id_small_industry
              join product_small_industry on dpsi.id_product = product_small_industry.id
              where product_small_industry.id in ($produk)
              and status.id = '$status'
              union
              select distinct small_industry.id, small_industry.name, st_x(st_centroid(small_industry.geom)) as longitude,st_y(st_centroid(small_industry.geom)) as latitude, 'si' as cat from small_industry
              join status on small_industry.id_status = status.id
              join detail_product_small_industry as dpsi on small_industry.id = dpsi.id_small_industry
              join product_small_industry on dpsi.id_product = product_small_industry.id
              where product_small_industry.id in ($produk)
              and status.id = '$status'";
  }
  else if( $kode=='f14' && isset($_GET['industry_type']) && isset($_GET['max']) && isset($_GET['min']) && isset($_GET['kecamatan']) && isset($_GET['rad']) ){
    $rad = $_GET['rad'];
    $industry_type = $_GET['industry_type'];
    $max = $_GET['max'];
    $min = $_GET['min'];
    $kecamatan = $_GET['kecamatan'];

    $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat from worship_place
            join small_industry on ST_DistanceSphere(worship_place.geom, small_industry.geom) < $rad
            join detail_product_small_industry as dpsi on small_industry.id = dpsi.id_small_industry
            join product_small_industry on dpsi.id_product = product_small_industry.id
            join industry_type on small_industry.id_industry_type = industry_type.id
            where industry_type.id = '$industry_type'
            and dpsi.price between $min and $max
            and st_contains((SELECT geom from district where id = '$kecamatan'), worship_place.geom)
            union
            select distinct small_industry.id, small_industry.name, st_x(st_centroid(small_industry.geom)) as longitude,st_y(st_centroid(small_industry.geom)) as latitude, 'si' as cat from small_industry
            join detail_product_small_industry as dpsi on small_industry.id = dpsi.id_small_industry
            join product_small_industry on dpsi.id_product = product_small_industry.id
            join industry_type on small_industry.id_industry_type = industry_type.id
            where industry_type.id = '$industry_type'
            and dpsi.price between $min and $max
            and st_contains((SELECT geom from district where id = '$kecamatan'), small_industry.geom)";
  }
  else if( $kode=='f15' && isset($_GET['max']) && isset($_GET['min']) && isset($_GET['souvenir_type']) && isset($_GET['rad']) && isset($_GET['angkot']) ){
      $max = $_GET['max'];
      $min = $_GET['min'];
      $souvenir_type = $_GET['souvenir_type'];
      $angkot = $_GET['angkot'];
      $rad = $_GET['rad'];

      $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,
              st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat
              from worship_place
              join souvenir on ST_DistanceSphere(worship_place.geom, souvenir.geom) < $rad
              join souvenir_type on souvenir.id_souvenir_type = souvenir_type.id
              join detail_product_souvenir on detail_product_souvenir.id_souvenir = souvenir.id
              join detail_souvenir on souvenir.id = detail_souvenir.id_souvenir
              join angkot on detail_souvenir.id_angkot = angkot.id
              where souvenir_type.id = '$souvenir_type'
              and detail_product_souvenir.price between $min and $max
              and angkot.id = '$angkot'
              union
              select distinct souvenir.id, souvenir.name, st_x(st_centroid(souvenir.geom)) as longitude,
              st_y(st_centroid(souvenir.geom)) as latitude, 's' as cat
              from souvenir
              join souvenir_type on souvenir.id_souvenir_type = souvenir_type.id
              join detail_product_souvenir on detail_product_souvenir.id_souvenir = souvenir.id
              join detail_souvenir on souvenir.id = detail_souvenir.id_souvenir
              join angkot on detail_souvenir.id_angkot = angkot.id
              where souvenir_type.id = '$souvenir_type'
              and detail_product_souvenir.price between $min and $max
              and angkot.id = '$angkot'";
  }
  //yola
  else if( $kode=='f16' && isset($_GET['kategori']) && isset($_GET['fasilitas']) && isset($_GET['kondisi']) ){
      $kategori = $_GET['kategori'];
      $fasilitas = $_GET['fasilitas'];
      $kondisi = $_GET['kondisi'];

      $sql = "select distinct worship_place.id, worship_place.name,ST_X(ST_Centroid(worship_place.geom)) AS longitude, ST_Y(ST_CENTROID(worship_place.geom)) As latitude, 'wp' as cat
      from worship_place
      join category_worship_place on worship_place.id_category=category_worship_place.id
      join detail_facility on worship_place.id=detail_facility.id_worship_place
      join facility on facility.id=detail_facility.id_facility
      join facility_condition on facility_condition.id=detail_facility.id_facility_condition
      WHERE category_worship_place.id= '$kategori'
      and detail_facility.id_facility in ($fasilitas)
      and facility_condition.id = '$kondisi'";
  }
  else if( $kode=='f17' && isset($_GET['angkot']) && isset($_GET['kondisi']) && isset($_GET['fasilitas']) ){
      $angkot = $_GET['angkot'];
      $kondisi = $_GET['kondisi'];
      $fasilitas = $_GET['fasilitas'];

      $sql = "select distinct worship_place.id, worship_place.name, ST_X(ST_Centroid(worship_place.geom)) AS longitude, ST_Y(ST_CENTROID(worship_place.geom)) As latitude, 'wp' as cat
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
  else if( $kode=='f18' && isset($_GET['produk']) && isset($_GET['status']) && isset($_GET['rad']) ){
      $produk = $_GET['produk'];
      $status = $_GET['status'];
      $rad = $_GET['rad'];

      $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,
              st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat
              from worship_place
              join souvenir on ST_DistanceSphere(worship_place.geom, souvenir.geom) < $rad
              join detail_product_souvenir on souvenir.id = detail_product_souvenir.id_souvenir
              join product_souvenir on detail_product_souvenir.id_product = product_souvenir.id
              join status on souvenir.id_status = status.id
              where product_souvenir.id in ($produk)
              and status.id = '$status'
              union
              select distinct souvenir.id, souvenir.name, st_x(st_centroid(souvenir.geom)) as longitude,
              st_y(st_centroid(souvenir.geom)) as latitude, 's' as cat
              from souvenir
              join detail_product_souvenir on souvenir.id = detail_product_souvenir.id_souvenir
              join product_souvenir on detail_product_souvenir.id_product = product_souvenir.id
              join status on souvenir.id_status = status.id
              where product_souvenir.id in ($produk)
              and status.id = '$status'";
  }
  else if( $kode=='f19' && isset($_GET['waktu']) && isset($_GET['culinary']) && isset($_GET['rad']) ){
      $waktu = $_GET['waktu'];
      $culinary = $_GET['culinary'];
      $rad = $_GET['rad'];

      $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,
              st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat
              from worship_place
              join culinary_place on ST_DistanceSphere(worship_place.geom, culinary_place.geom) < $rad
              join detail_culinary on culinary_place.id = detail_culinary.id_culinary_place
              join culinary on detail_culinary.id_culinary = culinary.id
              where LOWER(culinary.name) like concat('%',LOWER('$culinary'),'%')
              and '$waktu' between culinary_place.open and culinary_place.close
              union
              select distinct culinary_place.id, culinary_place.name, st_x(st_centroid(culinary_place.geom)) as longitude,st_y(st_centroid(culinary_place.geom)) as latitude,
              'c' as cat
              from culinary_place
              join detail_culinary on culinary_place.id = detail_culinary.id_culinary_place
              join culinary on detail_culinary.id_culinary = culinary.id
              where LOWER(culinary.name) like concat('%',LOWER('$culinary'),'%')
              and '$waktu' between culinary_place.open and culinary_place.close";
  }
  else if( $kode=='f20' && isset($_GET['type']) && isset($_GET['color']) && isset($_GET['rad']) ){
      $type = $_GET['type'];
      $type = str_replace(",","','",$type);
      $type = "'".$type."'";
      $color = $_GET['color'];
      $rad = $_GET['rad'];

      $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,
              st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat
              from worship_place
              join souvenir on ST_DistanceSphere(worship_place.geom, souvenir.geom) < $rad
              join status on souvenir.id_status = status.id
              join detail_souvenir on souvenir.id = detail_souvenir.id_souvenir
              join angkot on detail_souvenir.id_angkot = angkot.id
              where status.id in ($type)
              and angkot.id_color in ($color)
              union
              select distinct souvenir.id, souvenir.name, st_x(st_centroid(souvenir.geom)) as longitude,
              st_y(st_centroid(souvenir.geom)) as latitude, 's' as cat
              from souvenir
              join status on souvenir.id_status = status.id
              join detail_souvenir on souvenir.id = detail_souvenir.id_souvenir
              join angkot on detail_souvenir.id_angkot = angkot.id
              where status.id in ($type)
              and angkot.id_color in ($color)";
  }
  else if( $kode=='f21' && isset($_GET['hotel_type']) && isset($_GET['fasilitas_hotel']) && isset($_GET['rad']) ){
    $fasilitas = $_GET['fasilitas_hotel'];
    if($fasilitas==null){
      $fasilitas = 0;
    }
    $type = $_GET['hotel_type'];
    $rad = $_GET['rad'];

    $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat from worship_place
            join hotel on ST_DistanceSphere(worship_place.geom, hotel.geom) < $rad
            join hotel_type on hotel.id_type = hotel_type.id
            join detail_facility_hotel dfh on hotel.id = dfh.id_hotel
            join facility_hotel on dfh.id_facility = facility_hotel.id
            where hotel_type.id = '$type'
            and facility_hotel.id in ($fasilitas)
            union
            select distinct hotel.id, hotel.name, st_x(st_centroid(hotel.geom)) as longitude,st_y(st_centroid(hotel.geom)) as latitude, 'h' as cat from hotel
            join hotel_type on hotel.id_type = hotel_type.id
            join detail_facility_hotel dfh on hotel.id = dfh.id_hotel
            join facility_hotel on dfh.id_facility = facility_hotel.id
            where hotel_type.id = '$type'
            and facility_hotel.id in ($fasilitas)";
  }
  else if( $kode=='f22'&& isset($_GET['garin']) && isset($_GET['event']) && isset($_GET['ustad']) ){
    $garin = $_GET['garin'];
    $event = $_GET['event'];
    $ustad = $_GET['ustad'];

    $sql = "select distinct a.id, a.name, a.address, a.capacity,ST_X(ST_Centroid(a.geom)) AS longitude, ST_Y(ST_CENTROID(a.geom)) As latitude, 'wp' as cat
            FROM worship_place as a
            join administrators on a.id = administrators.id_worship_place and role = 'P'
            join detail_event as de on a.id = de.id_worship_place
            join event on de.id_event = event.id
            join ustad on de.id_ustad = ustad.id
            where administrators.name like concat('%',LOWER('$garin'),'%')
            and event.name like concat('%',LOWER('$event'),'%')
            and ustad.name like concat('%',LOWER('$ustad'),'%')";
  }
  else if( $kode=='f23' && isset($_GET['fasilitas_culinary']) && isset($_GET['angkot'])  && isset($_GET['rad']) ){
      $fasilitas_culinary = $_GET['fasilitas_culinary']; //'a','b','c'
      $fasilitas_culinary = str_replace(",","','",$fasilitas_culinary);
      $fasilitas_culinary = "'".$fasilitas_culinary."'";
      $angkot = $_GET['angkot'];
      $rad = $_GET['rad'];

      $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,
              st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat
              from worship_place
              join culinary_place on ST_DistanceSphere(worship_place.geom, culinary_place.geom) < $rad
              join detail_facility_culinary on culinary_place.id = detail_facility_culinary.id_culinary_place
              join facility_culinary on detail_facility_culinary.id_facility = facility_culinary.id
              join detail_worship_place on worship_place.id = worship_place.id
              join angkot on detail_worship_place.id_angkot = angkot.id
              where facility_culinary.id in ($fasilitas_culinary)
		          and angkot.id = '$angkot'
              union
              select distinct culinary_place.id, culinary_place.name, st_x(st_centroid(culinary_place.geom)) as longitude,st_y(st_centroid(culinary_place.geom)) as latitude,
              'c' as cat
              from culinary_place
              join detail_facility_culinary on culinary_place.id = detail_facility_culinary.id_culinary_place
              join facility_culinary on detail_facility_culinary.id_facility = facility_culinary.id
              join detail_culinary_place on culinary_place.id = detail_culinary_place.id_culinary_place
              join angkot on detail_culinary_place.id_angkot = angkot.id
              where facility_culinary.id in ($fasilitas_culinary)
		          and angkot.id = '$angkot'";
  }
  else if( $kode=='f24' && isset($_GET['min']) && isset($_GET['max']) && isset($_GET['type_hotel']) && isset($_GET['rad']) ){
      $min = $_GET['min'];
      $max = $_GET['max'];
      $type_hotel = $_GET['type_hotel'];
      $rad = $_GET['rad'];

      $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat from worship_place
            join hotel on ST_DistanceSphere(worship_place.geom, hotel.geom) < $rad
            join hotel_type on hotel.id_type = hotel_type.id
            join detail_room on hotel.id = detail_room.id_hotel
            join room on detail_room.id_room = room.id
            where room.price between $min and $max
            and hotel_type.id = '$type_hotel'
            union
            select distinct hotel.id, hotel.name, st_x(st_centroid(hotel.geom)) as longitude,st_y(st_centroid(hotel.geom)) as latitude, 'h' as cat from hotel
            join hotel_type on hotel.id_type = hotel_type.id
            join detail_room on hotel.id = detail_room.id_hotel
            join room on detail_room.id_room = room.id
            where room.price between $min and $max
            and hotel_type.id = '$type_hotel'";
  }
  else if( $kode=='f25' && isset($_GET['type']) && isset($_GET['angkot']) && isset($_GET['waktu']) && isset($_GET['rad']) ){
      $type = $_GET['type'];
      $type = str_replace(",","','",$type);
      $type = "'".$type."'";
      $angkot = $_GET['angkot'];
      $waktu = $_GET['waktu'];
      $rad = $_GET['rad'];

      $sql = "select distinct worship_place.id, worship_place.name, st_x(st_centroid(worship_place.geom)) as longitude,st_y(st_centroid(worship_place.geom)) as latitude, 'wp' as cat from worship_place
            join tourism on ST_DistanceSphere(worship_place.geom, tourism.geom) < $rad
            join tourism_type on tourism.id_type = tourism_type.id
            join detail_tourism on tourism.id = detail_tourism.id_tourism
            join angkot on detail_tourism.id_angkot = angkot.id
            where tourism_type.id in ($type)
            and angkot.id = '$angkot'
            and '$waktu' between tourism.open and tourism.close
            union
            select distinct tourism.id, tourism.name, st_x(st_centroid(tourism.geom)) as longitude,st_y(st_centroid(tourism.geom)) as latitude, 't' as cat from tourism
            join tourism_type on tourism.id_type = tourism_type.id
            join detail_tourism on tourism.id = detail_tourism.id_tourism
            join angkot on detail_tourism.id_angkot = angkot.id
            where tourism_type.id in ($type)
            and angkot.id = '$angkot'
            and '$waktu' between tourism.open and tourism.close";
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
