//f2
var se_angkot_f2_y = $('#se_angkot_f2_y');
var se_kondisi_f2_y = $('#se_kondisi_f2_y');

// f3
var txt_ustad_f3_y = $('#txt_ustad_f3_y');
var txt_event_f3_y = $('#txt_event_f3_y');
var se_warna_f3_y = $('#se_warna_f3_y');

function cari_f2y() {
  var inp_angkot = se_angkot_f2_y.children("option:selected").val();
  var inp_kondisi = se_kondisi_f2_y.children("option:selected").val();
  var inp_fasilitas=[];
  for(i=0;i<$("input[name=fasilitas_y_f2]:checked").length;i++){
    inp_fasilitas.push($("input[name=fasilitas_y_f2]:checked")[i].value);
  }

  var link = 'fu25Yola.php?kode=f2&angkot='+inp_angkot+'&kondisi='+inp_kondisi+'&fasilitas='+inp_fasilitas;
  console.log(link);
  $.ajax({ url: link, data: "", dataType: 'json', success: function (rows){
      data(rows);
      aktiv_rad_k3 = false;
  }})
}

function cari_f3y() {
  var inp_ustad = txt_ustad_f3_y.val();
  var inp_event = txt_event_f3_y.val();
  var inp_warna = se_warna_f3_y.children("option:selected").val();

  var link = 'fu25Yola.php?kode=f3&ustad='+inp_ustad+'&event='+inp_event+'&warna='+inp_warna;
  console.log(link);
  $.ajax({ url: link, data: "", dataType: 'json', success: function (rows){
      data(rows);
      aktiv_rad_k3 = false;
  }})
}
