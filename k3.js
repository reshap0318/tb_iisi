var txt_ustad_f1_a = $('#txt-ustad-f1-a');
var se_kategori_f1_a = $('#se-kategori-f1-a');
var txt_garin_f1_d = $('#txt-garin-f1-d');
var se_kategori_f1_d = $('#se-kategori-f1-d');
var se_angkot_f1_d = $('#se-angkot-f1-d');
var se_kecamatan_f1_ra = $('#se-kecamatan-f1-ra');
var se_warna_f1_ra = $('#se-warna-f1-ra');
var se_kategori_f1_y = $('#se-kategori-f1-y');
var se_kondisi_f1_y = $('#se-kondisi-f1-y');
var se_kategori_f1_re = $('#se-kategori-f1-re');
var se_kecamatan_f1_re = $('#se-kecamatan-f1-re');
var se_event_f1_re = $('#se-event-f1-re');

function setCookie(cname,cvalue,exdays)
{
    var d = new Date();
    d.setTime(d.getTime()+(exdays*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
    document.cookie = cname + "=" + JSON.stringify(cvalue) + "; " + expires;
    console.log("berhasil Menyimpan Lokasi");
}

function getCookie(cname) {
    var result = document.cookie.match(new RegExp(cname + '=([^;]+)'));
    result && (result = JSON.parse(result[1]));
    if(result){
      return result;
    }
    return "";
}

function checkCookie(cname)
{
    var username=getCookie(cname);
    if (username!=""){
      return true;
    }
    else{
      return false;
    }
}

function deleteCookie(cname) {
    var d = new Date();
    d.setTime(d.getTime()+ 0);
    var expires = "expires="+d.toGMTString();
    document.cookie = cname + "=" + "" + "; " + expires;
}

function cari_f1a(){
  var inp_ustad = txt_ustad_f1_a.val();
  var inp_kategori = se_kategori_f1_a.children("option:selected").val();
  var link = 'k3.php?kode=1a&act=cari&ustad='+inp_ustad+'&kategori='+inp_kategori;
  console.log(link);
  $.ajax({ url: link, data: "", dataType: 'json', success: function (rows){
    data_f1a(rows);
  }});
}

function cari_f1d() {
  var inp_garin = txt_garin_f1_d.val();
  var inp_kategori = se_kategori_f1_d.children("option:selected").val();
  var inp_angkot = se_angkot_f1_d.children("option:selected").val();
  var link = 'k3.php?kode=1d&act=cari&garin='+inp_garin+'&kategori='+inp_kategori+'&angkot='+inp_angkot;
  console.log(link);
  $.ajax({ url: link, data: "", dataType: 'json', success: function (rows){
    data_f1a(rows);
  }});
}

function cari_f1ra() {
  var inp_kecamatan = se_kecamatan_f1_ra.children("option:selected").val();
  var inp_warna = se_warna_f1_ra.children("option:selected").val();
  var link = 'k3.php?kode=1ra&act=cari&kecamatan='+inp_kecamatan+'&warna='+inp_warna;
  console.log(link);
  $.ajax({ url: link, data: "", dataType: 'json', success: function (rows){
    data_f1a(rows);
  }});
}

function cari_f1y() {
    var arrayLay=[];
    for(i=0;i<$("input[name=fasilitas_y]:checked").length;i++){
      arrayLay.push($("input[name=fasilitas_y]:checked")[i].value);
    }

    var inp_kategori = se_kategori_f1_y.children("option:selected").val();
    var inp_kondisi = se_kondisi_f1_y.children("option:selected").val();
    var link = 'k3.php?kode=1y&act=cari&kategori='+inp_kategori+'&kondisi='+inp_kondisi+'&lay='+arrayLay;
    console.log(link);
    $.ajax({ url: link, data: "", dataType: 'json', success: function (rows){
      data_f1a(rows);
    }});
}

function cari_f1re() {
    var inp_kategori = se_kategori_f1_re.children("option:selected").val();
    var inp_kecamatan = se_kecamatan_f1_re.children("option:selected").val();
    var inp_event = se_event_f1_re.children("option:selected").val();
    var link = 'k3.php?kode=1re&act=cari&kategori='+inp_kategori+'&kecamatan='+inp_kecamatan+'&event='+inp_event;
    console.log(link);
    $.ajax({ url: link, data: "", dataType: 'json', success: function (rows){
      data_f1a(rows);
    }});
}

function data_f1a(rows) {
    $('#hasilcari1').show();
    $('#hasilcari').empty();
    hapusInfo();
    clearroute2();
    clearroute();
    clearangkot();
    hapusRadius();
    hapusMarkerTerdekat();

    if(rows==null){
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

function clickinfowindowsk3(id) {
  google.maps.event.addListener(marker, "click", function(){
    detailmesjidk3(id);
  });
}

function detailmesjidk3(id) {
  $('#info').empty();
  hapusInfo();
  clearroute2();
  clearroute();
  clearangkot();
  $.ajax({
   url: server+'detailmasjid1.php?cari='+id,
   data: "",
   dataType: 'json',
   success: function(rows){
      for (var i in rows){
          var row = rows[i];
          var id = row.id;
          var nama = row.name_mesjid;
          var alamat=row.address;
          var image = row.image;
          var latitude  = row.latitude;
          var longitude = row.longitude ;
          centerBaru = new google.maps.LatLng(row.latitude, row.longitude);
          marker = new google.maps.Marke({
            position: centerBaru,
            icon:'assets/ico/marker_masjid.png',
            map: map,
            animation: google.maps.Animation.DROP,
          });
          markersDua.push(marker);
          map.setCenter(centerBaru);
          map.setZoom(18);
          infowindow = new google.maps.InfoWindow({
            position: centerBaru,
            content: "<span style=color:black><center><b>Information</b><br><img src='"+fotosrc+image+"' alt='image in infowindow' width='150'></center><br><i class='fa fa-home'></i> "+nama+"<br><i class='fa fa-map-marker'></i> "+alamat+"<br><a role='button' title='tracking' class='btn btn-default fa fa-car' value='Route' onclick='callRoute(centerLokasi, centerBaru);rutetampil();resetangkot();'></a>&nbsp<a role='button' title='gallery' class='btn btn-default fa fa-picture-o' value='Gallery' onclick='galeri(\""+id+"\")'></a></span>",
            pixelOffset: new google.maps.Size(0, -33)
          });
          infoDua.push(infowindow);
          hapusInfo();
          infowindow.open(map);
      }
    }
  });
}

function pinlocation() {
  if (pos == 'null'){
    alert ('Click button current position or manual position first !');
  }else{
    if(checkCookie('location')){
      if(confirm("the previous location already exists, are you sure you want to change location?")==true){
        setCookie('location',pos,365);
      }
    }else{
      setCookie('location',pos,365);
    }
  }
}

function firstloc() {
  first = getCookie('location');
  hapusposisi();
  marker = new google.maps.Marker({
    position : first,
    map: map,
    animation: google.maps.Animation.DROP,
  });
  centerLokasi = new google.maps.LatLng(first.lat, first.lng);
  markers.push(marker);
  infowindow = new google.maps.InfoWindow();
  infowindow.setContent('Current Position');
  infowindow.open(map, marker);
  usegeolocation=true;
  google.maps.event.clearListeners(map, 'click');
  console.log(first);
}

function route_to_begin() {

    var start = pos;
    var end = "";

    if (!checkCookie('location')){
      alert ('Please Set Your First Location');
    }else{
      end = getCookie('location');
    }

    if (start == 'null'){
      alert ('Click button current position or manual position first !');
    }

    if(start != 'null' && end != "")
    {
      callRoute(start, end);
      rutetampil();
      resetangkot();
    }
}
