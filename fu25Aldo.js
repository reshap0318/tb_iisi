//f2
var txt_event_f2_re = $('#txt_event_f2_re');
var co_fasilitas_f2_re = $('#co_fasilitas_f2_re');

//f3
var txt_garin_f3_re = $('#txt_garin_f3_re');
var se_kondisi_f3_re = $('#se_kondisi_f3_re');
var co_fasilitas_f3_re = $('#co_fasilitas_f3_re');
var aktiv_rad_k3 = false;
var rad_k3;

//f4
var txt_garin_f4_re = $('#txt_garin_f4_re');
var txt_event_f4_re = $('#txt_event_f4_re');
var txt_ustad_f4_re = $('#txt_ustad_f4_re');

function cari_f2re() {
    var arrFac=[];
    for(i=0;i<$("input[name=fasilitas_r_f2]:checked").length;i++){
      arrFac.push($("input[name=fasilitas_r_f2]:checked")[i].value);
    }

    var inp_event = txt_event_f2_re.val();
    var link = 'fu25Aldo.php?kode=f2&event='+inp_event+'&facilitas='+arrFac;
    console.log(link);
    $.ajax({ url: link, data: "", dataType: 'json', success: function (rows){
      data(rows);
    }});
}

function cari_f3re() {
  var inp_facility=[];
  for(i=0;i<$("input[name=fasilitas_r_f3]:checked").length;i++){
    inp_facility.push($("input[name=fasilitas_r_f3]:checked")[i].value);
  }

  var inp_garin = txt_garin_f3_re.val();
  var inp_kondisi = se_kondisi_f3_re.children("option:selected").val();
  var link = 'fu25Aldo.php?kode=f3&garin='+inp_garin+'&facilitas='+inp_facility+'&kondisi='+inp_kondisi;
  console.log(link);
  $.ajax({ url: link, data: "", dataType: 'json', success: function (rows){
      data(rows);
      aktiv_rad_k3 = false;
  }})
}

function cari_f4re() {
  var inp_event = txt_event_f4_re.val();
  var inp_garin = txt_garin_f4_re.val();
  var inp_ustad = txt_ustad_f4_re.val();
  var link = 'fu25Aldo.php?kode=f4&event='+inp_event+'&garin='+inp_garin+'&ustad='+inp_ustad;
  console.log(link);
  $.ajax({ url: link, data: "", dataType: 'json', success: function (rows){
    data(rows);
  }});
}

function cari_f5re() {
  var inp_facility=[];
  for(i=0;i<$("input[name=fasilitas_r_f5]:checked").length;i++){
    inp_facility.push($("input[name=fasilitas_r_f5]:checked")[i].value);
  }

  var inp_event=[];
  for(i=0;i<$("input[name=type_event_f5]:checked").length;i++){
    inp_event.push($("input[name=type_event_f5]:checked")[i].value);
  }


  var link = 'fu25Aldo.php?kode=f5&type_event='+inp_event+'&facility='+inp_facility;
  console.log(link);
  $.ajax({ url: link, data: "", dataType: 'json', success: function (rows){
    data(rows);
  }});
}

function radiusk3() {
    if (pos == 'null'){
        alert ('Click button current position or manual position first !');
    }
    else {
        aktiv_rad_k3 = true;
        hapusRadius();
        var inputradiusangkot=document.getElementById("inputradiusk3").value;
        rad_k3 = inputradiusangkot*100;
        console.log(inputradiusangkot);
        var circle = new google.maps.Circle({
          center: pos,
          radius: parseFloat(inputradiusangkot*100),
          map: map,
          strokeColor: "red",
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: "red",
          fillOpacity: 0.35
        });
        map.setZoom(14);
        map.setCenter(pos);
        circles.push(circle);
        cekRadiusStatus = 'on';
    }
}

function data(rows) {
    $('#hasilcari1').show();
    $('#hasilcari').empty();
    hapusInfo();
    clearroute2();
    clearroute();
    clearangkot();
    // hapusRadius();
    hapusMarkerTerdekat();

    if(rows.length <= 0){
      alert('Mosque Not found');
    }
    else{
      for (var i in rows){
        var row     = rows[i];
        var id   = row.id;
        var nama   = row.name;
        var latitude  = row.latitude ;
        var longitude = row.longitude ;
        centerBaru = new google.maps.LatLng(latitude, longitude);
        marker = new google.maps.Marker({
          position: centerBaru,
          icon:'assets/ico/marker_masjid.png',
          map: map,
          animation: google.maps.Animation.DROP,
        });
        markersDua.push(marker);
        map.setCenter(centerBaru);
        clickinfowindowsk3(id);
        map.setZoom(14);
        $('#hasilcari').append("<tr><td>"+nama+"</td><td><a role='button' title='info' class='btn btn-default fa fa-info' onclick='detailmasjid(\""+id+"\");info1();'></a></td><td><a role='button' class='btn btn-default fa fa-bus' title='jalur angkot' onclick='angkotmesjid(\""+id+"\",\""+latitude+"\",\""+longitude+"\");info12();'></a></td></tr>");
      }
    }
}

function type_event() {
    $('#co_type_event_f5_re .checkbox').remove();
    var v=fasilitaslist.value;
    $.ajax({ url: 'fu25Aldo.php?kode=type_event', data: "", dataType: 'json', success: function(rows){
      for (var i in rows){
        var row = rows[i];
        var id=row.id;
        var name=row.name;
        $('#co_type_event_f5_re').append('<div class="checkbox" style="color: #f3fff4"><label><input type="checkbox" name="type_event_f5" value="'+id+'">'+name+'</label></div>');
      }
    }});
}
