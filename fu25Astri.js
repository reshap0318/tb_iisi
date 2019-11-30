// f2
var se_angkot_f2_a = $('#se_angkot_f2_a');

function cari_f2a() {
  var inp_angkot = se_angkot_f2_a.children("option:selected").val();

  var link = 'fu25Astri.php?kode=f2&angkot='+inp_angkot;
  var souvenir = [];
  var small_industry = [];
  var worship_place = [];

  console.log(link);
  $.ajax({ url: link, data: "", dataType: 'json', success: function (rows){
      var row = rows;
      // if(row.type == 'si'){
      //   small_industry.push(row);
      // }
      // else if(row.type == 's'){
      //   souvenir.push(row);
      // }
      // else if(row.type == 'wp'){
      //   worship_place.push(row);
      // }
      data_astri(rows);

      aktiv_rad_k3 = false;
  }})
}


function data_astri(rows) {
    $('#hasilcari1').show();
    $('#hasilcari').empty();
    hapusInfo();
    clearroute2();
    clearroute();
    clearangkot();
    // hapusRadius();
    hapusMarkerTerdekat();

    if(rows.length <= 0){
      alert('Datas Not found');
    }
    else{
      for (var i in rows){
        var row     = rows[i];
        var id   = row.id;
        var nama   = row.name;
        var latitude  = row.latitude ;
        var longitude = row.longitude ;
        var icon = 'assets/ico/marker_masjid.png';

        if(row.type == 's'){
          icon = 'assets/ico/shopping.png';
        }
        else if(row.type == 'si'){
          icon = 'assets/ico/industries.png';
        }
        console.log('type='+row.type+';icon='+icon);
        centerBaru = new google.maps.LatLng(latitude, longitude);
        marker = new google.maps.Marker({
          position: centerBaru,
          icon:icon,
          map: map,
          animation: google.maps.Animation.DROP,
        });
        markersDua.push(marker);
        map.setCenter(centerBaru);
        clickinfowindowsk3(id);
        map.setZoom(14);
        if(row.type=='s'){
          $('#hasilcari').append("<tr><td>"+nama+"</td><td><a role='button' title='info' class='btn btn-default fa fa-info' onclick='detailoleh(\""+id+"\");info1();'></a></td><td><a role='button' class='btn btn-default fa fa-bus' title='jalur angkot' onclick='angkotsouvenir(\""+id+"\",\""+latitude+"\",\""+longitude+"\");info12();'></a></td></tr>");
        }
        else if(row.type=='si'){
          $('#hasilcari').append("<tr><td>"+nama+"</td><td><a role='button' title='info' class='btn btn-default fa fa-info' onclick='detailik(\""+id+"\");info1();'></a></td><td><a role='button' class='btn btn-default fa fa-bus' title='jalur angkot' onclick='ngkotindustri(\""+id+"\",\""+latitude+"\",\""+longitude+"\");info12();'></a></td></tr>");
        }
        else if(row.type=='wp'){
          $('#hasilcari').append("<tr><td>"+nama+"</td><td><a role='button' title='info' class='btn btn-default fa fa-info' onclick='detailmasjid(\""+id+"\");info1();'></a></td><td><a role='button' class='btn btn-default fa fa-bus' title='jalur angkot' onclick='angkotmesjid(\""+id+"\",\""+latitude+"\",\""+longitude+"\");info12();'></a></td></tr>");
        }
      }
    }
}
