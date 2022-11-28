var acak_form;
var index_row;
var id_responden='';
var peta;
var pertama = 0;
var jenis = "restoran";
var judulx = new Array();
var desx = new Array();
var i;
var url;
var gambar_tanda;
var markersArray=[];
function export_data(type,mod,acak){
	var param={};
	param["mod"]=mod;
	param["type"]=type;
	switch (mod){
		case "input":
			
		break;
	}
	openWindowWithPost(host+'modul/export_excel',param);
	
}
function loadUrl(urls){
	//$("#konten").empty();
    $("#konten").empty().addClass("loading");
   // $("#konten").html("").addClass("loading");
	$.get(urls,function (html){
	    $("#konten").html(html).removeClass("loading");
    });
}

function getClientHeight(){
	var theHeight;
	if (window.innerHeight)
		theHeight=window.innerHeight;
	else if (document.documentElement && document.documentElement.clientHeight) 
		theHeight=document.documentElement.clientHeight;
	else if (document.moduldy) 
		theHeight=document.moduldy.clientHeight;
	
	return theHeight;
}

var divcontainer;
function windowFormPanel(html,judul,width,height){
	divcontainer = $('#jendela');
	$(divcontainer).unbind();
	$('#isiJendela').html(html);
    $(divcontainer).window({
		title:judul,
		width:width,
		height:height,
		autoOpen:false,
		top: Math.round(frmHeight/2)-(height/2),
		left: Math.round(frmWidth/2)-(width/2),
		modal:true,
		maximizable:false,
		minimizable: false,
		collapsible: false,
		closable: true,
		resizable: false,
	    onBeforeClose:function(){	   
			$(divcontainer).window("close",true);
			//$(divcontainer).window("destroy",true);
			//$(divcontainer).window('refresh');
			return true;
	    }		
    });
    $(divcontainer).window('open');       
}
function windowFormClosePanel(){
    $(divcontainer).window('close');
	//$(divcontainer).window('refresh');
}

var container;
function windowForm(html,judul,width,height){
    container = "win"+Math.floor(Math.random()*9999);
    $("<div id="+container+"></div>").appendTo("body");
    container = "#"+container;
    $(container).html(html);
    $(container).css('padding','5px');
    $(container).window({
       title:judul,
       width:width,
       height:height,
       autoOpen:false,
       maximizable:false,
       minimizable: false,
	   collapsible: false,
       resizable: false,
       closable:true,
       modal:true,
	   onBeforeClose:function(){	   
			$(container).window("close",true);
			$(container).window("destroy",true);
			return true;
	   }
    });
    $(container).window('open');        
}
function closeWindow(){
    $(container).window('close');
    $(container).html("");
}


function getClientWidth(){
	var theWidth;
	if (window.innerWidth) 
		theWidth=window.innerWidth;
	else if (document.documentElement && document.documentElement.clientWidth) 
		theWidth=document.documentElement.clientWidth;
	else if (document.moduldy) 
		theWidth=document.moduldy.clientWidth;

	return theWidth;
}


function genGrid(modnya, divnya, lebarnya, tingginya){
	if(lebarnya == undefined){
		lebarnya = (getClientWidth()-250);
	}
	if(tingginya == undefined){
		tingginya = (getClientHeight()-300);
	}

	var kolom ={};
	var frozen ={};
	var judulnya;
	var param={};
	var urlnya;
	var urlglobal="";
	var url_detil="";
	var post_detil={};
	var fitnya;
	var klik=false;
	var doble_klik=false;
	var pagesizemoduly = 15;
	var singleSelek = true;
	var nowrap_nya = true;
	var footer=false;
	
	switch(modnya){
		case "cl_vendor":
			judulnya = "";
			urlnya = "cl_vendor";
			fitnya = true;
			urlglobal = host+'modul/getdata/'+urlnya;
			kolom[modnya] = [	
				{field:'t_produk',title:'Produk',width:100, halign:'center',align:'center',
					formatter: function(value,row,index){
						return "<a href='javascript:void(0);' class='btn btn-sm btn-danger' onclick='get_katalog(\""+modnya+"\","+row.id+")'>"+value+"</a>";
					}
				},
				{field:'nama_vendor',title:'Nama Vendor',width:350, halign:'left',align:'left'},
				{field:'alamat',title:'Alamat',width:300, halign:'left',align:'left'},
				{field:'no_tlp',title:'No. Tlp',width:100, halign:'left',align:'left'},
				{field:'pic',title:'PIC',width:100, halign:'left',align:'left'},
				/*{field:'create_date',title:'Tgl. Pembuatan',width:150, halign:'center',align:'center'},
				{field:'create_by',title:'Dibuat Oleh',width:150, halign:'left',align:'left'},
				*/
			];
		break;
		case "histori_pengajuan_po":
			judulnya = "";
			urlnya = "histori_pengajuan_po";
			fitnya = true;
			nowrap_nya=false;
			urlglobal = host+'modul/getdata/'+urlnya;
			frozen[modnya]=[
				{field:'file',title:'Bukti Bayar',width:150, halign:'center',align:'center',
					formatter: function(value,row,index){
						if(cl_jabatan_id==1){//MANAGER
							if(cl_departemen==3){//KEUANGAN
								if(value){
									return "<a href='javascript:void(0);' class='btn btn-sm btn-danger' onclick='upl_bukti(\"pengajuan_po\","+row.id+")'>"+value+"</a>";
								}else{
									return "<a href='javascript:void(0);' class='btn btn-sm btn-danger' onclick='upl_bukti(\"pengajuan_po\","+row.id+")'>Upload</a>";
								}
							}else{
								if(value){
									return "<a href='"+host+"__repo/bukti_pembayaran/"+value+"' >"+value+"</a>";
								}
								else{
									return '-';
								}
							}
						}else{
							if(value){
								return "<a href='"+host+"__repo/bukti_pembayaran/"+value+"' >"+value+"</a>";
							}
							else{
								return '-';
							}
						}
					}
					
				},
				{field:'flag',title:'Cetak',width:120, halign:'center',align:'center',
					formatter: function(value,row,index){
						return "<a href='javascript:void(0);' class='btn btn-sm btn-danger' onclick='cetak(\"pengajuan_po\","+row.id+")'>Cetak PO</a>";
					}
					
				},
				{field:'no_pengajuan',title:'No PEngajuan',width:120, halign:'center',align:'center'},
				{field:'no_po',title:'No PO',width:120, halign:'center',align:'center'},
				{field:'nama_vendor',title:'Vendor',width:120, halign:'center',align:'center'},
			];
			kolom[modnya] = [
				{field:'nama_project',title:'Nama Project',width:400, halign:'left',align:'left'},
				{field:'status_appr',title:'Sts Persetujuan',width:150, halign:'center',align:'center',
					formatter: function(value,row,index){
						return 'Closed';
					}
				},
				{field:'grand_total',title:'Total',width:100, halign:'right',align:'right',
					formatter: function(value,row,index){
						return NumberFormat(value)
					}
				},
				{field:'tgl',title:'Tgl. PO',width:150, halign:'center',align:'center'},
				{field:'nama_lengkap',title:'Diajukan Oleh',width:150, halign:'left',align:'left'},
				
			];
		break;
		case "pengajuan_po":
			judulnya = "";
			urlnya = "pengajuan_po";
			fitnya = true;
			nowrap_nya=false;
			urlglobal = host+'modul/getdata/'+urlnya;
			frozen[modnya]=[
				{field:'tbl_tahapan_sop_id',title:'Approve',width:100, halign:'center',align:'center',hidden:(cl_jabatan_id==3 ? true : false),
					formatter: function(value,row,index){
						if(row.nama_user==nama_user){
							if(row.cl_departemen==row.tbl_tahapan_sop_id){
								if(row.status_appr=='P'){
									return "<a href='javascript:void(0);' class='btn btn-sm btn-warning' onclick='get_detil(\"pengajuan_po\","+row.id+")'>Approve</a>";
								}else if(row.status_appr=='R'){//PENDING
									return "<a href='javascript:void(0);' class='btn btn-sm btn-warning' onclick='get_detil(\"pengajuan_po\","+row.id+")'>Pending</a>";
								}
								else {
									return 'Pengajuan Dalam Revisi';
								}
								//return "<a href='javascript:void(0);' class='btn btn-sm btn-warning' onclick='get_detil(\"pengajuan_anggaran\","+row.id+")'>Approve</a>";
							}else{
								return '';
							}
						}else{
							if(row.status_appr=='P' || row.status_appr=='RU')
								return "<a href='javascript:void(0);' class='btn btn-sm btn-warning' onclick='get_detil(\"pengajuan_po\","+row.id+")'>Approve</a>";
							else if(row.status_appr=='R'){//PENDING
									return "<a href='javascript:void(0);' class='btn btn-sm btn-warning' onclick='get_detil(\"pengajuan_po\","+row.id+")'>Pending</a>";
							}
							else return 'Pengajuan Dalam Revisi';
						}
					}
				},
				{field:'flag',title:'Cetak',width:120, halign:'center',align:'center',
					formatter: function(value,row,index){
						return "<a href='javascript:void(0);' class='btn btn-sm btn-danger' onclick='cetak(\"pengajuan_po\","+row.id+")'>Cetak Kwitansi</a>";
					}
					
				},
				{field:'no_pengajuan',title:'No Pengajuan',width:120, halign:'center',align:'center'},
				{field:'no_po',title:'No. PO',width:120, halign:'center',align:'center'},
			];
			kolom[modnya] = [
				
				{field:'nama_project',title:'Nama Project',width:400, halign:'left',align:'left'},
				
				{field:'status_appr',title:'Sts Persetujuan',width:150, halign:'center',align:'center',
					formatter: function(value,row,index){
						if(row.nama_user==nama_user){
							if(value=='P')return row.posisi+' <br>(Menunggu Persetujuan)';
							else if(value=='R')return '<a href="javascript:void(0);" style="color:#ffffff" onclick="get_revisi('+row.id+');">'+row.posisi+' <br>(Pending)</a>';
							else if(value=='RU')return row.posisi+' <br>(Menunggu Persetujuan Revisi)';
							else return row.posisi+' <br>(Disetujui)';
						}else{
							if(value=='P')return row.posisi+' <br>(Menunggu Persetujuan)';
							else if(value=='R')return row.posisi+' <br>(Pending)';
							else if(value=='RU')return row.posisi+' <br>(Menunggu Persetujuan Revisi)';
							else return row.posisi+' <br>(Disetujui)';
						}
					},
					styler: function(value,row,index){
						if(value=='R')return "background:red;color:#ffffff";
						if(value=='RU')return "background:#0a9107;color:#ffffff";
					}
				},
				{field:'grand_total',title:'Total',width:100, halign:'right',align:'right',
					formatter: function(value,row,index){
						return NumberFormat(value)
					}
				},
				{field:'tgl',title:'Tgl. Pengajuan',width:150, halign:'center',align:'center'},
				{field:'nama_lengkap',title:'Diajukan Oleh',width:150, halign:'left',align:'left'},
				
			];
		break;
		case "histori_po":
			judulnya = "";
			urlnya = "histori_po";
			fitnya = true;
			nowrap_nya=false;
			urlglobal = host+'modul/getdata/'+urlnya;
			frozen[modnya]=[
				{field:'flag',title:'Cetak',width:120, halign:'center',align:'center',
					formatter: function(value,row,index){
						return "<a href='javascript:void(0);' class='btn btn-sm btn-danger' onclick='cetak(\"po\","+row.id+")'>Cetak PO</a>";
					}
					
				},
				{field:'no_po',title:'No PO',width:120, halign:'center',align:'center'},
				{field:'nama_vendor',title:'Vendor',width:120, halign:'center',align:'center'},
			];
			kolom[modnya] = [
				{field:'nama_project',title:'Nama Project',width:400, halign:'left',align:'left'},
				{field:'status_appr',title:'Sts Persetujuan',width:150, halign:'center',align:'center',
					formatter: function(value,row,index){
						return 'Closed';
					}
				},
				{field:'grand_total_ppn',title:'Total',width:100, halign:'right',align:'right',
					formatter: function(value,row,index){
						return NumberFormat(value)
					}
				},
				{field:'tgl',title:'Tgl. PO',width:150, halign:'center',align:'center'},
				{field:'nama_lengkap',title:'Diajukan Oleh',width:150, halign:'left',align:'left'},
				
			];
		break;
		case "po":
			judulnya = "";
			urlnya = "po";
			fitnya = true;
			nowrap_nya=false;
			urlglobal = host+'modul/getdata/'+urlnya;
			frozen[modnya]=[
				{field:'tbl_tahapan_sop_id',title:'Approve',width:100, halign:'center',align:'center',hidden:(cl_jabatan_id==3 ? true : false),
					formatter: function(value,row,index){
						if(row.nama_user==nama_user){
							if(row.cl_departemen==row.tbl_tahapan_sop_id){
								if(row.status_appr=='P'){
									return "<a href='javascript:void(0);' class='btn btn-sm btn-warning' onclick='get_detil(\"po\","+row.id+")'>Approve</a>";
								}else if(row.status_appr=='R'){//PENDING
									return "<a href='javascript:void(0);' class='btn btn-sm btn-warning' onclick='get_detil(\"po\","+row.id+")'>Pending</a>";
								}
								else {
									return 'Pengajuan Dalam Revisi';
								}
								//return "<a href='javascript:void(0);' class='btn btn-sm btn-warning' onclick='get_detil(\"pengajuan_anggaran\","+row.id+")'>Approve</a>";
							}else{
								return '';
							}
						}else{
							if(row.status_appr=='P' || row.status_appr=='RU')
								return "<a href='javascript:void(0);' class='btn btn-sm btn-warning' onclick='get_detil(\"po\","+row.id+")'>Approve</a>";
							else if(row.status_appr=='R'){//PENDING
									return "<a href='javascript:void(0);' class='btn btn-sm btn-warning' onclick='get_detil(\"po\","+row.id+")'>Pending</a>";
							}
							else return 'Pengajuan Dalam Revisi';
						}
					}
				},
				{field:'flag',title:'Cetak',width:120, halign:'center',align:'center',
					formatter: function(value,row,index){
						return "<a href='javascript:void(0);' class='btn btn-sm btn-danger' onclick='cetak(\"po\","+row.id+")'>Cetak PO</a>";
					}
					
				},
				{field:'no_po',title:'No PO',width:120, halign:'center',align:'center'},
				{field:'nama_vendor',title:'Vendor',width:120, halign:'center',align:'center'},
			];
			kolom[modnya] = [
				
				{field:'nama_project',title:'Nama Project',width:400, halign:'left',align:'left'},
				
				{field:'status_appr',title:'Sts Persetujuan',width:150, halign:'center',align:'center',
					formatter: function(value,row,index){
						if(row.nama_user==nama_user){
							if(value=='P')return row.posisi+' <br>(Menunggu Persetujuan)';
							else if(value=='R')return '<a href="javascript:void(0);" style="color:#ffffff" onclick="get_revisi('+row.id+');">'+row.posisi+' <br>(Pending)</a>';
							else if(value=='RU')return row.posisi+' <br>(Menunggu Persetujuan Revisi)';
							else return row.posisi+' <br>(Disetujui)';
						}else{
							if(value=='P')return row.posisi+' <br>(Menunggu Persetujuan)';
							else if(value=='R')return row.posisi+' <br>(Pending)';
							else if(value=='RU')return row.posisi+' <br>(Menunggu Persetujuan Revisi)';
							else return row.posisi+' <br>(Disetujui)';
						}
					},
					styler: function(value,row,index){
						if(value=='R')return "background:red;color:#ffffff";
						if(value=='RU')return "background:#0a9107;color:#ffffff";
					}
				},
				{field:'grand_total_ppn',title:'Total',width:100, halign:'right',align:'right',
					formatter: function(value,row,index){
						return NumberFormat(value)
					}
				},
				{field:'tgl',title:'Tgl. PO',width:150, halign:'center',align:'center'},
				{field:'nama_lengkap',title:'Diajukan Oleh',width:150, halign:'left',align:'left'},
				
			];
		break;
		case "pemasukan_anggaran":
			judulnya = "";
			urlnya = "pemasukan_anggaran";
			fitnya = true;
			urlglobal = host+'modul/getdata/'+urlnya;
			kolom[modnya] = [	
				{field:'nama_project',title:'Nama Project',width:500, halign:'left',align:'left',
					formatter: function(value,row,index){
						return '('+row.kode_project+') '+ value;
					}
				},
				{field:'tgl_pemasukan',title:'Tgl. Pemasukan',width:120, halign:'center',align:'center'},
				{field:'jumlah',title:'Jumlah',width:200, halign:'right',align:'right',
					formatter: function(value,row,index){
						return NumberFormat(value);
					}
				},
				{field:'create_date',title:'Tgl. Pembuatan',width:150, halign:'center',align:'center'},
				{field:'create_by',title:'Dibuat Oleh',width:150, halign:'left',align:'left'},
			];
		break;
		case "user":
			judulnya = "";
			urlnya = "user";
			fitnya = true;
			urlglobal = host+'modul/getdata/'+urlnya;
			kolom[modnya] = [	
				{field:'nama_user',title:'User ID',width:120, halign:'left',align:'left'},
				{field:'nama_lengkap',title:'Nama Lengkap',width:320, halign:'left',align:'left'},
				{field:'user_group',title:'Group',width:150, halign:'left',align:'left'},
				{field:'email',title:'Email',width:120, halign:'left',align:'left'},
				{field:'jabatan',title:'Jabatan',width:120, halign:'left',align:'left'},
				{field:'departemen',title:'Departemen',width:120, halign:'left',align:'left'},
				{field:'status',title:'Status',width:120, halign:'center',align:'center',
					formatter: function(value,row,index){
						if(value=='A')return 	'Aktif';
						else return 'Tidak Aktif'
					}
				}
			];
		break;
		case "user_group":
			judulnya = "";
			urlnya = "user_group";
			fitnya = true;
			urlglobal = host+'modul/getdata/'+urlnya;
			kolom[modnya] = [	
				
				{field:'user_group',title:'Departemen',width:400, halign:'left',align:'left'},
				{field:'deskripsi',title:'Deskripsi',width:300, halign:'left',align:'left'},
				{field:'create_date',title:'Tgl. Pembuatan',width:150, halign:'center',align:'center'},
				{field:'create_by',title:'Dibuat Oleh',width:150, halign:'left',align:'left'},
			];
		break;
		case "lokasi":
			judulnya = "";
			urlnya = "lokasi";
			fitnya = true;
			urlglobal = host+'modul/getdata/'+urlnya;
			kolom[modnya] = [	
				
				{field:'kode_lokasi',title:'Kode Lokasi',width:200, halign:'left',align:'left'},
				{field:'lokasi',title:'Lokasi',width:500, halign:'left',align:'left'},
				{field:'create_date',title:'Tgl. Pembuatan',width:150, halign:'center',align:'center'},
				{field:'create_by',title:'Dibuat Oleh',width:150, halign:'left',align:'left'},
			];
		break;
		case "departemen":
			judulnya = "";
			urlnya = "departemen";
			fitnya = true;
			urlglobal = host+'modul/getdata/'+urlnya;
			kolom[modnya] = [	
				
				{field:'departemen',title:'Departemen',width:400, halign:'left',align:'left'},
				{field:'deskripsi',title:'Deskripsi',width:200, halign:'left',align:'left'},
				{field:'status',title:'Status',width:120, halign:'center',align:'center',
					formatter: function(value,row,index){
						if(value=='A')return 	'Aktif';
						else return 'Tidak Aktif'
					}
				},
				{field:'create_date',title:'Tgl. Pembuatan',width:150, halign:'center',align:'center'},
				{field:'create_by',title:'Dibuat Oleh',width:150, halign:'left',align:'left'},
			];
		break;
		case "jabatan":
			judulnya = "";
			urlnya = "jabatan";
			fitnya = true;
			urlglobal = host+'modul/getdata/'+urlnya;
			kolom[modnya] = [	
				
				{field:'jabatan',title:'Jabatan',width:400, halign:'left',align:'left'},
				{field:'status',title:'Status',width:120, halign:'center',align:'center',
					formatter: function(value,row,index){
						if(value=='A')return 	'Aktif';
						else return 'Tidak Aktif'
					}
				},
				{field:'create_date',title:'Tgl. Pembuatan',width:150, halign:'center',align:'center'},
				{field:'create_by',title:'Dibuat Oleh',width:150, halign:'left',align:'left'},
			];
		break;
		case "barang":
			judulnya = "";
			urlnya = "barang";
			fitnya = true;
			urlglobal = host+'modul/getdata/'+urlnya;
			kolom[modnya] = [	
				
				{field:'nama_barang',title:'Nama Barang',width:600, halign:'left',align:'left'},
				{field:'satuan',title:'Satuan',width:100, halign:'center',align:'center',hidden:true},
				{field:'harga_satuan',title:'Harga Satuan',width:120, halign:'right',align:'right',hidden:true,
					formatter: function(value,row,index){
						return NumberFormat(value);
					}
				},
				{field:'status',title:'Status',width:120, halign:'center',align:'center',
					formatter: function(value,row,index){
						if(value=='A')return 	'Aktif';
						else return 'Tidak Aktif'
					}
				},
				{field:'create_date',title:'Tgl. Pembuatan',width:150, halign:'center',align:'center'},
				{field:'create_by',title:'Dibuat Oleh',width:150, halign:'left',align:'left'},
			];
		break;
		case "project":
			judulnya = "";
			urlnya = "project";
			fitnya = true;
			urlglobal = host+'modul/getdata/'+urlnya;
			kolom[modnya] = [	
				
				{field:'kode_project',title:'Kode Project',width:200, halign:'center',align:'center'},
				{field:'nama_project',title:'Nama Project',width:400, halign:'left',align:'left'},
				{field:'status',title:'Status',width:120, halign:'center',align:'center',
					formatter: function(value,row,index){
						if(value=='A')return 	'Aktif';
						else return 'Tidak Aktif'
					}
				},
				{field:'create_date',title:'Tgl. Pembuatan',width:150, halign:'center',align:'center'},
				{field:'create_by',title:'Dibuat Oleh',width:150, halign:'left',align:'left'},
			];
		break;
		
		case "pengajuan_anggaran":
			judulnya = "";
			urlnya = "pengajuan_anggaran";
			fitnya = true;
			nowrap_nya=false;
			urlglobal = host+'modul/getdata/'+urlnya;
			frozen[modnya]=[
				{field:'tbl_tahapan_sop_id',title:'Approve',width:100, halign:'center',align:'center',hidden:(cl_jabatan_id==3 ? true : false),
					formatter: function(value,row,index){
						if(row.nama_user==nama_user){
							if(row.cl_departemen==row.tbl_tahapan_sop_id){
								if(row.status_appr=='P' || row.status_appr=='RU'){
									return "<a href='javascript:void(0);' class='btn btn-sm btn-warning' onclick='get_detil(\"pengajuan_anggaran\","+row.id+")'>Approve</a>";
								}
								else {
									return 'Pengajuan Dalam Revisi';
								}
								//return "<a href='javascript:void(0);' class='btn btn-sm btn-warning' onclick='get_detil(\"pengajuan_anggaran\","+row.id+")'>Approve</a>";
							}else{
								return '';
							}
						}else{
							if(row.status_appr=='P' || row.status_appr=='RU')
								return "<a href='javascript:void(0);' class='btn btn-sm btn-warning' onclick='get_detil(\"pengajuan_anggaran\","+row.id+")'>Approve</a>";
							else return 'Pengajuan Dalam Revisi';
						}
					}
				},
				{field:'flag',title:'Cetak',width:120, halign:'center',align:'center',
					formatter: function(value,row,index){
						return "<a href='javascript:void(0);' class='btn btn-sm btn-danger' onclick='cetak(\"pengajuan_anggaran\","+row.id+")'>Cetak Kwitansi</a>";
					}
					
				},
				{field:'no_pengajuan',title:'No Pengajuan',width:120, halign:'center',align:'center'},
				{field:'kode_project',title:'Kode Project',width:120, halign:'center',align:'center'},
			];
			kolom[modnya] = [
				
				{field:'nama_project',title:'Nama Project',width:400, halign:'left',align:'left'},
				
				{field:'status_appr',title:'Sts Persetujuan',width:150, halign:'center',align:'center',
					formatter: function(value,row,index){
						if(row.nama_user==nama_user){
							if(value=='P')return row.posisi+' <br>(Menunggu Persetujuan)';
							else if(value=='R')return '<a href="javascript:void(0);" style="color:#ffffff" onclick="get_revisi('+row.id+');">'+row.posisi+' <br>(Revisi)</a>';
							else if(value=='RU')return row.posisi+' <br>(Menunggu Persetujuan Revisi)';
							else return row.posisi+' <br>(Disetujui)';
						}else{
							if(value=='P')return row.posisi+' <br>(Menunggu Persetujuan)';
							else if(value=='R')return row.posisi+' <br>(Revisi)';
							else if(value=='RU')return row.posisi+' <br>(Menunggu Persetujuan Revisi)';
							else return row.posisi+' <br>(Disetujui)';
						}
					},
					styler: function(value,row,index){
						if(value=='R')return "background:red;color:#ffffff";
						if(value=='RU')return "background:#0a9107;color:#ffffff";
					}
				},
				{field:'tgl',title:'Tgl. Pengajuan',width:150, halign:'center',align:'center'},
				{field:'nama_lengkap',title:'Diajukan Oleh',width:150, halign:'left',align:'left'},
				{field:'grand_total',title:'Total',width:100, halign:'right',align:'right',
					formatter: function(value,row,index){
						return NumberFormat(value)
					}
				},
			];
		break;
		case "histori_pengajuan":
			judulnya = "";
			urlnya = "histori_pengajuan";
			fitnya = true;
			nowrap_nya=false;
			urlglobal = host+'modul/getdata/'+urlnya;
			frozen[modnya]=[
				{field:'flag',title:'Cetak',width:120, halign:'center',align:'center',
					formatter: function(value,row,index){
						return "<a href='javascript:void(0);' class='btn btn-sm btn-danger' onclick='cetak(\"pengajuan_anggaran\","+row.id+")'>Cetak Kwitansi</a>";
					}
					
				},
				{field:'no_pengajuan',title:'No Pengajuan',width:120, halign:'center',align:'center'},
				{field:'kode_project',title:'Kode Project',width:120, halign:'center',align:'center'},
			];
			kolom[modnya] = [
				{field:'nama_project',title:'Nama Project',width:400, halign:'left',align:'left'},
				{field:'status_appr',title:'Sts Persetujuan',width:150, halign:'center',align:'center',
					formatter: function(value,row,index){
						return 'Closed';
					}
				},
				{field:'tgl',title:'Tgl. Pengajuan',width:150, halign:'center',align:'center'},
				{field:'nama_lengkap',title:'Diajukan Oleh',width:150, halign:'left',align:'left'},
				{field:'grand_total',title:'Total',width:100, halign:'right',align:'right',
					formatter: function(value,row,index){
						return NumberFormat(value)
					}
				},
			];
		break;
	}
	
	grid_nya=$("#"+divnya).datagrid({
		title:judulnya,
        height:tingginya,
        width:lebarnya,
		rownumbers:true,
		iconCls:'database',
        fit:fitnya,
        striped:true,
        pagination:true,
        remoteSort: false,
		showFooter:footer,
		singleSelect:singleSelek,
        url: urlglobal,		
		nowrap: nowrap_nya,
		pageSize:pagesizemoduly,
		pageList:[15,25,50,75,100,200],
		queryParams:param,
		frozenColumns:[
            frozen[modnya]
        ],
		columns:[
            kolom[modnya]
        ],
		onLoadSuccess:function(d){
			//gridVRList.datagrid('selectRow', 0);
			$('.yes').linkbutton({  
					iconCls: 'icon-cancel'  
			});
			$('.no').linkbutton({  
					iconCls: 'icon-ok'  
			});
			
		},
		onClickRow:function(rowIndex,row){
			
        },
		onDblClickRow:function(rowIndex,row){
			
		},
		toolbar: '#tb_'+modnya,
		rowStyler: function(index,row){
			
			
		},
		onLoadSuccess: function(data){
			if(data.total == 0){
				var $panel = $(this).datagrid('getPanel');
				var $info = '<div class="info-empty" style="margin-top:20%;">Data Tidak Tersedia</div>';
				$($panel).find(".datagrid-view").append($info);
				$('.menu_'+modnya).removeClass('bg-green');
				$('.menu_'+modnya).addClass('bg-red');
				//$('#edit').linkbutton({disabled:true});
				//$('#del').linkbutton({disabled:true});
			}else{
				//$($panel).find(".datagrid-view").append('');
				$('.info-empty').hide();
				$('.menu_'+modnya).removeClass('bg-red');
				$('.menu_'+modnya).addClass('bg-green');
			}
		},
	});
}
function cetak(mod,id){
	var params={};
	params["id"]=id;
	openWindowWithPost(host+'modul/cetak/'+mod+'/pdf',params);
}
function get_detil(mod,id){
	$('#grid_nya_'+mod).hide();
	$('#detil_nya_'+mod).empty().show().addClass("loading");
	$.post(host+'modul/appr/'+mod, {id:id}, function(resp){
		$('#detil_nya_'+mod).show();
		$('#detil_nya_'+mod).html(resp).removeClass("loading");
	});
}
function get_revisi(id){
	$('#grid_nya_pengajuan_anggaran').hide();
	$('#detil_nya_pengajuan_anggaran').empty().show().addClass("loading");
	$.post(host+'modul/revisi', {id:id}, function(resp){
		$('#detil_nya_pengajuan_anggaran').show();
		$('#detil_nya_pengajuan_anggaran').html(resp).removeClass("loading");
	});
}
function genform(type, modulnya, submodulnya, stswindow, tabel){
	var urlpost = host+'modul/get_konten/'+submodulnya;
	
	var id_tambahan = "";
	
	switch(submodulnya){
		case "project":table="cl_project";break;
		case "barang":table="cl_item_barang";break;
		case "departemen":table="cl_departemen";break;
		case "jabatan":table="cl_jabatan";break;
		case "user":table="tbl_user";break;
		case "user_group":table="cl_user_group";break;
		case "lokasi":table="cl_lokasi";break;
		case "pengajuan_anggaran":table="tbl_h_pengajuan";break;
		case "pengajuan_po":table="tbl_h_pengajuan_po";break;
		case "pemasukan_anggaran":table="tbl_pemasukan_anggaran";break;
		case "po":table="tbl_h_po";break;
		case "cl_vendor":table="cl_vendor";break;
	}
	var urldelete = host+'modul/cruddata/'+table;
	switch(type){
		case "add":
			if(stswindow == undefined){
				$('#grid_nya_'+submodulnya).hide();
				$('#detil_nya_'+submodulnya).empty().show().addClass("loading");
			}
			$.post(urlpost, {'editstatus':'add', 'id_tambahan':id_tambahan }, function(resp){
				if(stswindow == 'windowform'){
					windowForm(resp, judulwindow, lebar, tinggi);
				}else if(stswindow == 'windowpanel'){
					windowFormPanel(resp, judulwindow, lebar, tinggi);
				}else{
					$('#detil_nya_'+submodulnya).show();
					$('#detil_nya_'+submodulnya).html(resp).removeClass("loading");
				}
			});
		break;
		case "edit":
		case "delete":
			var row = $("#grid_"+submodulnya).datagrid('getSelected');
			if(row){
				if(type=='edit'){
					if(submodulnya=='pengajuan_anggaran' || submodulnya=='po' || submodulnya=='pengajuan_po'){
						if(row.nama_user!=nama_user){
							$.messager.alert('BSR',"Maaf Bukan Kewenangan Anda Mengubah Data Ini",'warning');
							return false;
						}
						if(row.flag=='R'){
							$.messager.alert('BSR',"Maaf Pengajuan Anda Dalam Proses Pengajuan Revisi Silahkan Click Revisi!!",'warning');
							return false;
						}
					}
					if(stswindow == undefined){
						$('#grid_nya_'+submodulnya).hide();
						$('#detil_nya_'+submodulnya).show().addClass("loading");	
					}
					$.post(urlpost, { 'editstatus':'edit', id:(submodulnya=='project'? row.kode_project :row.id), 'ts':table, 'submodul':submodulnya, 'bulan':row.bulan, 'tahun':row.tahun, 'id_tambahan':id_tambahan }, function(resp){
						if(stswindow == 'windowform'){
							windowForm(resp, judulwindow, lebar, tinggi);
						}else if(stswindow == 'windowpanel'){
							windowFormPanel(resp, judulwindow, lebar, tinggi);
						}else{
							$('#detil_nya_'+submodulnya).show();
							$('#detil_nya_'+submodulnya).html(resp).removeClass("loading");
						}
					});
				}else if(type=='delete'){
					//if(confirm("Anda Yakin Menghapus Data Ini ?")){
					if(submodulnya=='pengajuan_anggaran' || submodulnya=='po' || submodulnya=='pengajuan_po'){
						if(row.nama_user!=nama_user){
							$.messager.alert('BSR',"Maaf Bukan Kewenangan Anda Menghapus Data Ini",'warning');
							return false;
						}
						if(row.flag=='R'){
							$.messager.alert('BSR',"Maaf Pengajuan Anda Dalam Proses Pengajuan Revisi Silahkan Click Revisi!!",'warning');
							return false;
						}
					}
					$.messager.confirm('BSR','Anda Yakin Menghapus Data Ini ?',function(re){
						if(re){
							loadingna();
							$.post(urldelete, {id:(submodulnya=='project'? row.kode_project :row.id), 'sts_crud':'delete'}, function(r){
								if(r==1){
									winLoadingClose();
									$.messager.alert('BSR',"Data Terhapus",'info');
									$('#grid_'+submodulnya).datagrid('reload');								
								}else{
									winLoadingClose();
									console.log(r)
									$.messager.alert('BSR',"Gagal Menghapus Data",'error');
								}
							});	
						}
					});	
					//}
				}
				
			}
			else{
				$.messager.alert('BSR',"Select Row In Grid",'error');
			}
		break;
		
	}
}

function kumpulAction(type, p1, p2, p3, p4, p5){
	switch(type){
		case "reservation":
			grid = $('#grid_reservasi').datagrid('getSelected');
			$.post(host+'backend/simpan_data/tbl_reservasi_confirm', { 'id':grid.id, 'confirm':p1 }, function(rsp){
				if(rsp == 1){
					$.messager.alert('Roger Salon',"Confirm OK",'info');
				}else{
					$.messager.alert('Roger Salon',"Failed Confirm",'error');
				}
				$('#grid_reservasi').datagrid('reload');	
			} );
		break;
		case "banner":
			grid = $('#grid_banner').datagrid('getSelected');
			$.post(host+'backend/simpan_data/tbl_banner_confirm', { 'id':grid.id, 'confirm':p1 }, function(rsp){
				if(rsp == 1){
					$.messager.alert('Roger Salon',"OK",'info');
				}else{
					$.messager.alert('Roger Salon',"Gagal",'error');
				}
				$('#grid_banner').datagrid('reload');	
			} );
		break;
		case "hapus_produk":
			$('#detail_fotonya').empty().addClass("loading");
			$.post(host+'backend/hapusfoto_detail/produk', { 'id':p1, 'nama_file':p2, 'id_header':p3  }, function(rsp){
				$('#detail_fotonya').html(rsp).removeClass("loading");
			} );
		break;
		case "hapus_service":
			$('#detail_fotonya').empty().addClass("loading");
			$.post(host+'backend/hapusfoto_detail/service', { 'id':p1, 'nama_file':p2, 'id_header':p3  }, function(rsp){
				$('#detail_fotonya').html(rsp).removeClass("loading");
			} );
		break;
	}
}	

function submit_form(frm,func){
	var url = jQuery('#'+frm).attr("url");
	loadingna();
	if ($('#'+frm).form('validate')){
		jQuery('#'+frm).form('submit',{
				url:url,
				onSubmit: function(){
					  return $(this).form('validate');
				},
				success:function(data){
					//$.unblockUI();
					if (func == undefined ){
						 if (data == "1"){
							pesan('Data Sudah Disimpan ','Sukses');
						}else{
							 pesan(data,'Result');
						}
					}else{
						func(data);
					}
				},
				error:function(data){
					//$.unblockUI();
					 if (func == undefined ){
						 pesan(data,'Error');
					}else{
						func(data);
					}
				}
		});
	}else{
		winLoadingClose();
		$.messager.alert('BSR','Harap Isi Data Yang Kosong', 'error');
	}
}

function fillCombo(url, SelID, value, value2, value3, value4){
	//if(Ext.get(SelID).innerHTML == "") return false;
	if (value == undefined) value = "";
	if (value2 == undefined) value2 = "";
	if (value3 == undefined) value3 = "";
	if (value4 == undefined) value4 = "";
	
	$('#'+SelID).empty();
	$.post(url, {"v": value, "v2": value2, "v3": value3, "v4": value4},function(data){
		$('#'+SelID).append(data);
	});

}
function formatDate(date) {
	var bulan=date.getMonth() +1;
	var tgl=date.getDate();
	if(bulan < 10){
		bulan='0'+bulan;
	}
	
	if(tgl < 10){
		tgl='0'+tgl;
	}
	return date.getFullYear() + "-" + bulan + "-" + tgl;
}


function clear_form(id){
	$('#'+id).find("input[type=text], textarea,select").val("");
	//$('.angka').numbermodulx('setValue',0);
}

var divcontainerz;
function windowLoading(html,judul,width,height){
    divcontainerz = "win"+Math.floor(Math.random()*9999);
    $("<div id="+divcontainerz+"></div>").appendTo("body");
    divcontainerz = "#"+divcontainerz;
    $(divcontainerz).html(html);
    $(divcontainerz).css('padding','5px');
    $(divcontainerz).window({
       title:judul,
       width:width,
       height:height,
       autoOpen:false,
       modal:true,
       maximizable:false,
       resizable:false,
       minimizable:false,
       closable:false,
       collapsible:false,  
    });
    $(divcontainerz).window('open');        
}
function winLoadingClose(){
    $(divcontainerz).window('close');
    //$(divcontainer).html('');
}
function loadingna(){
	windowLoading("<img src='"+host+"__assets/img/loading.gif' style='position: fixed;top: 50%;left: 50%;margin-top: -10px;margin-left: -25px;'/>","Please Wait",200,100);
}

function NumberFormat(value) {
	
    var jml= new String(value);
    if(jml=="null" || jml=="NaN") jml ="0";
    jml1 = jml.split("."); 
    jml2 = jml1[0];
    amount = jml2.split("").reverse();

    var output = "";
    for ( var i = 0; i <= amount.length-1; i++ ){
        output = amount[i] + output;
        if ((i+1) % 3 == 0 && (amount.length-1) !== i)output = '.' + output;
    }
    //if(jml1[1]===undefined) jml1[1] ="00";
   // if(isNaN(output))  output = "0";
    return output; // + "." + jml1[1];
}

function showErrorAlert (reason, detail) {
		var msg='';
		if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
		else {
			console.log("error uploading file", reason, detail);
		}
		$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
		 '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
	}
function konversi_pwd_text(id){
	if($('input#'+id)[0].type=="password")$('input#'+id)[0].type = 'text';
	else $('input#'+id)[0].type = 'password';
}
function hapus_file(mod,id,id_list){
	loadingna();
	$.post(host+'HapusFile',{mod:mod,id:id},function(r){
		if(r==1){
			winLoadingClose();
			$('#'+id_list).remove();
		}else{
			console.log(r);
			winLoadingClose();
			$.messager.alert('Aldeaz',"Gagal Menghapus File",'error');
		}
	});
}
function initMap() {
	  var myLatlng = new google.maps.LatLng(-6.381631, 120.382690);
	  var myOptions = {
		  zoom: 7,
		  center: myLatlng,
		  gestureHandling: 'greedy'
	  };
	  peta = new google.maps.Map(document.getElementById("map"), myOptions);
	
}
function ceklks(lang){
	$.post(host+'lihat-lokasi', { 'valnya':$('#lokasi').val(), 'lang':lang }, function(resp){
		var respon=JSON.parse(resp);
		console.log(respon.lat);
		console.log(respon.longi);
		$('#lokasinya').html(respon.cetak);
		var myLatlng = new google.maps.LatLng(respon.longi,respon.lat);
        var myOptions = {
            zoom: 13,
            center: myLatlng
        };
              
//              menampilkan output pada element
        var map = new google.maps.Map(document.getElementById("map"), myOptions);
              
//              menambahkan marker
        var marker = new google.maps.Marker({
             position: myLatlng,
             map: map,
             title:"Monas"
        });

	} );
}

function chart_na(id_selector,type,title,subtitle,title_y,data_x,data_y,satuan){
	switch(type){
	case "column":
	$('#'+id_selector).highcharts({
			chart: {
				type: type
			},
			title: {
				text: title
			},
			subtitle: {
				text: subtitle
			},
			xAxis:{
				type: 'category'
			},
			yAxis: {
				title: {
					text: title_y
				}

			},
			legend: {
				enabled: false
			},
			plotOptions: {
				series: {
					modulrderWidth: 0,
					dataLabels: {
						enabled: true,
						format: '{point.y:.1f}'
					}
				}
			},

			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> of total<br/>'
			},

			series: data_x
			
			
			
        });
		break;
		case "line" :
			$('#'+id_selector).highcharts({
				title: {
				text: title,
				x: -20 //center
				},
				subtitle: {
					text: subtitle,
					x: -20
				},
				xAxis: {
					categories: data_y
				},
				yAxis: {
					title: {
						text: 'Jumlah Dukungan'
					},
					plotLines: [{
						value: 0,
						width: 1,
						color: '#808080'
					}]
				},
				tooltip: {
					valueSuffix: 'Total'
				},
				legend: {
					layout: 'vertical',
					align: 'right',
					verticalAlign: 'middle',
					modulrderWidth: 0
				},
				series: data_x
			});
		break;
		case "pie":
			 $('#'+id_selector).highcharts({
				chart: {
					plotBackgroundColor: null,
					plotmodulrderWidth: null,
					plotShadow: false,
					type: 'pie'
				},
				title: {
					text: title
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: false
						},
						showInLegend: true
					}
				},
				series: data_x
				/*series: [{
					name: 'Brands',
					colorByPoint: true,
					data: [{
						name: 'Chrome',
						y: 61.41,
						sliced: true,
						selected: true
					}, {
						name: 'Internet Explorer',
						y: 11.84
					}, {
						name: 'Firefox',
						y: 10.85
					}, {
						name: 'Edge',
						y: 4.67
					}, {
						name: 'Safari',
						y: 4.18
					}, {
						name: 'Sogou Explorer',
						y: 1.64
					}, {
						name: 'Opera',
						y: 1.6
					}, {
						name: 'QQ',
						y: 1.2
					}, {
						name: 'Other',
						y: 2.61
					}]
				}]*/
			});
		break;
	}
}
function get_lokasi(wil){
	var ex={};
	var zoom;
	 //alert(wil);
	switch(wil){
		case 'modulGOR':
			ex['long']=106.79981460668944;
			ex['lat']=-6.599092201665239;
			zoom=14;
		break;
		
		case 'All':
			ex['long']=117.19912325500002;
			ex['lat']=-0.6333576479565592;
			zoom=5;
		break;
		case 'SULSEL':
			ex['long']=119.975703;
			ex['lat']=-3.668300;
			zoom=7;
		break;
		
		case 'DKI':
			ex['long']=106.82822456457518;
			ex['lat']= -6.177210450737989;
			zoom=14;
		break;
		
	}
	
	peta.setZoom(zoom);
	peta.setCenter(new google.maps.LatLng(ex['lat'],ex['long']));
	
}

function set_icon(jenisnya){
    switch(jenisnya){
        case "jual":
            gambar_tanda = host+'asset/img/lap_laba.png';
            break;
        case "beli":
            gambar_tanda = host+'asset/img/kredit_jual.png';
            break;
        case "stock":
            gambar_tanda = host+'asset/img/database.png';
            break;
		case "point":
            gambar_tanda = host+'__assets/img/zone.png';
            break;
    }
}

function setjenis(obj){
	//var restoranChk,airportChk,mesjidChk;
		objId=obj.id;
    	jenis = objId;
		//alert(jenis)
		class_na(objId);
		//alert(objId);
		switch(objId){
			case 'jual':
				$('#beli').addClass('legend-hide');
				$('#stock').addClass('legend-hide');
				clearOverlays() 
				ambildatabase('jual');
			break;	
				
			case 'beli':
				$('#jual').addClass('legend-hide');
				$('#stock').addClass('legend-hide');
				clearOverlays() 
				ambildatabase('beli');
			break;	
				
			case 'stock':
				$('#beli').addClass('legend-hide');
				$('#jual').addClass('legend-hide');	
				clearOverlays() 
				ambildatabase('stock');
			break;	
		}	
}
function setinfo(petak, nomor){
    google.maps.event.addListener(petak, 'click', function() {
		//alert (nomor);
		$.post(host+'backend/getdisplay/get_info/',{kec:nomor},function(resp){
			windowForm(resp,'INFORMASI HASIL SURVEY',(getClientWidth()-200),500);
		});
        //$("#jendelainfo").fadeIn();
        //$("#teksjudul").html(judulx[nomor]);
        //$("#teksdes").html(desx[nomor]);
    });
}
function ambildatabase(){
	url = host+"backend/get_point/";
    $.post(host+"backend/get_point/",function(r){
			var a=JSON.parse(r);
			$.each(a.wilayah, function(idx,grp){
				//console.log(grp.x);
				judulx[i] = grp.kec;
                set_icon('point');
                var point = new google.maps.LatLng(
                    parseFloat(grp.x),
                    parseFloat(grp.y)
				);
                tanda = new google.maps.Marker({
                    position: point,
					animation: google.maps.Animation.DROP,
                    map: peta,
					label:{
						text: grp.kec,
						fontSize: "8px"
						//color: 'white'
					},
                    icon: host+'__assets/img/marker.png'
                });
				markersArray.push(tanda);
                setinfo(tanda,grp.id);
			});
    });
}
function kasihtanda(lokasi){
    set_icon(jenis);
    tanda = new google.maps.Marker({
            position: lokasi,
            map: peta,
            icon: gambar_tanda
    });
}
function clearOverlays() {
  for (var i = 0; i < markersArray.length; i++ ) {
	//  console.log(markersArray[i]);
    markersArray[i].setMap(null);
  }
}
function cariData(mod,acak){
	var _post={};
	_post['key']=$('#key_'+acak).val();
	if($('#pending_'+acak).is(":checked")==true){
		_post['pending']=$('#pending_'+acak).val();
		console.log(_post['pending']);
	}
	grid_nya.datagrid('reload',_post);
}
function get_alat_peraga(acak){
	var _post={};
	_post["kel"]=$('#cl_kelurahan_id_alat_peraga_'+acak).val();
	_post["id_responden"]=id_responden;
	$('#list_alat_peraga').html('').addClass('loading');
	$.post(host+'backend/getdisplay/alat_peraga',_post,function(r){
		$('#list_alat_peraga').removeClass('loading').html(r);
	});
}
var newWindow;
function openWindowWithPost(url,params)
{
    var x = Math.floor((Math.random() * 10) + 1);
	
	if (!newWindow || typeof(newWindow)=="undefined"){
		newWindow = window.open(url, 'winpost'); 
	}else{
		newWindow.close();
		newWindow = window.open(url, 'winpost'); 
		//return false;
	}
	
	var formid= "formid"+x;
    var html = "";
    html += "<html><head></head><moduldy><form  id='"+formid+"' method='post' action='" + url + "'>";

    $.each(params, function(key, value) {
        if (value instanceof Array || value instanceof Object) {
            $.each(value, function(key1, value1) { 
                html += "<input type='hidden' name='" + key + "["+key1+"]' value='" + value1 + "'/>";
            });
        }else{
            html += "<input type='hidden' name='" + key + "' value='" + value + "'/>";
        }
    });
   
    html += "</form><script type='text/javascript'>document.getElementById(\""+formid+"\").submit()</script></moduldy></html>";
    newWindow.document.write(html);
    return newWindow;
}
function tambah_row(mod,param){
	var tr_table;
	
	switch(mod){
		case "bd_tanya":
			idx_row++;
			tr_table +='<tr class="tr_tanya" id="tanya_'+idx_row+'" idx=1>';
			tr_table +='<td valign="top"><input type="text" name="pilihan[]" id="pilihan_{$acak}" class="form-control" placeholder="Isi Option/Pilihan Pertanyaan"></td>';
			tr_table +='<td valign="top"><a href="javascript:void(0);" class="btn btn-xs btn-danger" onclick="$(this).parents(\'tr\').first().remove();">-</a></td></tr>';
		break;
		case "bd_peng_po":
			idx_row++;
			tr_table +='<tr class="tr_em" id="tm_'+idx_row+'" idx='+idx_row+'>';
			tr_table +='<td>';
			tr_table +='<input type="text" name="ket[]" onKeyup="this.value = this.value.toUpperCase()"  class="validasi autoo" style="width:100%">';
			tr_table +='</td>';
			tr_table +='<td style="text-align:center;;vertical-align:top;">';
			tr_table +='<input type="text" name="qty[]" id="qty_'+idx_row+'_'+param+'" idx="'+idx_row+'" class="angka4 au_'+idx_row+'" style="width:50px;height:26px;">';
			tr_table +='</td>';
			tr_table +='<td style="text-align:right;;vertical-align:top;">';
			tr_table +='Rp. <span id="t_tot_'+idx_row+'_'+param+'"></span>';
			tr_table +='<input type="hidden" name="total[]" id="tot_'+idx_row+'_'+param+'" class="h_tot" style="width:100%">';
			tr_table +='</td>';
			tr_table +='<td style="text-align:center;vertical-align:middle;"><a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="$(this).parents(\'tr\').first().remove();hitung_total_po();">-</a></td>';
			tr_table +='</tr>';
		break;
		case "bd_email":
			idx_row++;
			tr_table +='<tr class="tr_em" id="tm_'+idx_row+'" idx=1>';
			tr_table +='<td>';
			tr_table +='<input type="hidden" name="cl_produk_id[]" id="cl_produk_id_'+idx_row+'" class="" style="width:100%" value="">';
			tr_table +='<input type="text" name="produk[]" onKeyup="this.value = this.value.toUpperCase()"  class="validasi autoo" style="width:100%">';
			tr_table +='</td>';
			tr_table +='<td><input type="text" name="qty[]" id="qty_'+idx_row+'_'+param+'" class="angka" style="width:50px;height:26px;">';
			tr_table +='<input type="hidden" name="t_qty[]" id="t_qty_'+idx_row+'_'+param+'" class="h_tot_qty" style="width:100%">';
			tr_table +='</td>';
			tr_table +='<td><input type="text" name="satuan[]" class="validasi" style="width:100%"></td>';
			tr_table +='<td><input type="text" name="harga[]" idx="'+idx_row+'" id="harga_'+idx_row+'_'+param+'"  class="angka2" style="width:100%;height:26px;"></td>';
			tr_table +='<td style="text-align:right;;vertical-align:middle;"><span id="t_tot_'+idx_row+'_'+param+'"></span><input type="hidden" name="total[]" id="tot_'+idx_row+'_'+param+'" class="h_tot" style="width:100%"></td>';
			tr_table +='<td><input type="text" name="keterangan[]" class="" style="width:100%"></td>';
			tr_table +='<td style="text-align:center;;vertical-align:top;">';
			tr_table +='<div class="fileUpload btn btn-sm btn-warning">';
			tr_table +='<span>Upload</span>';
			tr_table +='<input type="file" class="upload"  id="s_file_{$acak}" name="s_file[]" onChange="$(\'.nm_file_'+idx_row+'\').html($(this).val());" >';
			tr_table +='</div>';
			tr_table +='<br><span class="nm_file_'+idx_row+'" style=""></span>';
			tr_table +='</td>';
			tr_table +='<td style="text-align:center;vertical-align:middle;"><a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="$(this).parents(\'tr\').first().remove();hitung_total();">-</a></td>';
			tr_table +='</tr>';
			
		break;
		case "bd_po":
			idx_row++;
			tr_table +='<tr class="tr_em" id="tm_'+idx_row+'" idx=1>';
			tr_table +='<td>';
			tr_table +='<input type="hidden" name="cl_produk_id[]" id="cl_produk_id_'+idx_row+'" class="" style="width:100%" value="">';
			tr_table +='<input type="text" name="produk[]" onKeyup="this.value = this.value.toUpperCase()"  class="validasi autoo" style="width:100%">';
			tr_table +='</td>';
			tr_table +='<td><input type="text" name="qty[]" idx="'+idx_row+'" id="qty_'+idx_row+'_'+param+'" class="angka" style="width:50px;height:26px;">';
			tr_table +='<input type="hidden" name="t_qty[]"  id="t_qty_'+idx_row+'_'+param+'" class="h_tot_qty" style="width:100%">';
			tr_table +='</td>';
			tr_table +='<td><input type="text" name="satuan[]" id="satuan_'+idx_row+'" class="validasi" style="width:100%"></td>';
			tr_table +='<td><input type="text" name="harga[]" idx="'+idx_row+'" id="harga_'+idx_row+'_'+param+'"  class="angka2" style="width:100%;height:26px;"></td>';
			tr_table +='<td style="text-align:right;;vertical-align:middle;"><span id="t_tot_'+idx_row+'_'+param+'"></span><input type="hidden" name="total[]" id="tot_'+idx_row+'_'+param+'" class="h_tot" style="width:100%"></td>';
			//tr_table +='<td><input type="text" name="keterangan[]" class="" style="width:100%"></td>';
			tr_table +='<td style="text-align:center;vertical-align:middle;"><a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="$(this).parents(\'tr\').first().remove();hitung_total();">-</a></td>';
			tr_table +='</tr>';
			
		break;
		case "bd_termin":
			idx_row_termin++;
			tr_table +='<tr class="tr_em" id="tm_1" idx=1>';
			tr_table +='<td>';
			tr_table +='<input type="text" name="termin_ket[]" onKeyup="this.value = this.value.toUpperCase()"  class="validasi" style="width:100%">';
			tr_table +='</td>';
			tr_table +='<td style="text-align:center;;vertical-align:top;">';
			tr_table +='<input type="text" name="termin_jml[]" id="jml_'+idx_row_termin+'_'+param+'" idx="'+idx_row_termin+'" class="angka3" style="width:50px;height:26px;">';
			tr_table +='</td>';
			tr_table +='<td style="text-align:right;;vertical-align:top;">';
			tr_table +='<span id="termin_t_tot_'+idx_row_termin+'_'+param+'"></span>';
			tr_table +='<input type="hidden" name="termin_total[]" id="termin_tot_'+idx_row_termin+'_'+param+'" class="termin_tot" style="width:100%">';
			tr_table +='</td>';		
			tr_table +='<td style="text-align:center;vertical-align:middle;"><a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="$(this).parents(\'tr\').first().remove();">-</a></td>';
			tr_table +='</tr>';
		break;
	}
	
	$('.'+mod).append(tr_table);
	if(mod=='bd_peng_po'){
		$('.autoo').autoComplete({
			minChars: 1,
			source: function(term, response){
				$.getJSON(host+'modul/getdata/auto_po',{ q: term,po:$('#tbl_h_po_id_'+param).val() },function(data){ response(data); });
			},
			renderItem: function (item, search){
				search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
				var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
				return '<div class="autocomplete-suggestion" data-id="'+item[1]+'" data-jml="'+item[2]+'" data-total="'+item[3]+'" data-val="' + item[0] + '">'
					+ item[0].replace(re, "<b>$1</b>") + '</div>';
			},
			onSelect: function(e, term, item){
				//alert('Item "'+item.data('val')+' ('+item.data('id')+')" selected by '+(e.type == 'keydown' ? 'pressing enter' : 'mouse click')+'.');
				$('.au_'+idx_row).numberbox('setValue',item.data('jml'));
				$('#t_tot_'+idx_row+'_'+param).html(NumberFormat(item.data('total')));
				$('#tot_'+idx_row+'_'+param).val(item.data('total'));
				hitung_total_po();
			}
		});
		
	}
	if(mod=='bd_email'){
		$('.autoo').autoComplete({
			minChars: 2,
			source: function(term, response){
				$.getJSON(host+'modul/getdata/auto_barang',{ q: term },function(data){ response(data); });
			},
			renderItem: function (item, search){
				search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
				var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
				return '<div class="autocomplete-suggestion" data-id="'+item[1]+'" data-val="' + item[0] + '">'
					+ item[0].replace(re, "<b>$1</b>") + '</div>';
			},
			onSelect: function(e, term, item){
				//alert('Item "'+item.data('val')+' ('+item.data('id')+')" selected by '+(e.type == 'keydown' ? 'pressing enter' : 'mouse click')+'.');
				$('#cl_produk_id_'+idx_row).val(item.data('id'));
			}
		});
		
	}
	if(mod=='bd_po'){
		$('.autoo').autoComplete({
		minChars: 2,
		source: function(term, response){
			$.getJSON(host+'modul/getdata/auto_katalog',{ q: term,vendor:$('#cl_vendor_id_'+param).val() },function(data){ response(data); });
		},
		renderItem: function (item, search){
			search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
			var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
			return '<div class="autocomplete-suggestion" data-id="'+item[1]+'" data-satuan="'+item[2]+'" data-harga="'+item[3]+'" data-val="' + item[0] + '">'
				+ item[0].replace(re, "<b>$1</b>") + '</div>';
		},
		onSelect: function(e, term, item){
			//alert('Item "'+item.data('val')+' ('+item.data('id')+')" selected by '+(e.type == 'keydown' ? 'pressing enter' : 'mouse click')+'.');
			$('#cl_produk_id_'+idx_row).val(item.data('id'));
			$('#satuan_'+idx_row).val(item.data('satuan'));
			$('#harga_'+idx_row+'_'+param).numberbox('setValue',item.data('harga'));
		}
	});
		
	}
	//$(".validasi").validatemodulx({ required:true }); 
	/*$(".angka").numbermodulx({ required:true,min:0,precision:0,groupSeparator:'.',decimalSeparator:',',
		onChange:function(){
			get_total('qty','harga_beli','total',idx_row);
		}
	}); 
	*/
	$('.angka').numberbox({
		required:true,min:0,precision:0,groupSeparator:',',decimalSeparator:'.',
		onChange:function(x,y){
			//console.log();
			var idx=($(this).attr('idx'));
			console.log(idx);
			var harga=parseFloat($('#harga_'+idx+'_'+param).val());
			//var harga=parseFloat($('#'+$(this).attr('id')).val());
			var qty=parseFloat(x);
			var t_tot=qty*harga;
			console.log(qty+'->'+harga);
			$('#t_tot_'+idx+'_'+param).html(NumberFormat(t_tot));
			$('#tot_'+idx+'_'+param).val(t_tot);
			$('#t_qty_'+idx+'_'+param).val(qty);
			hitung_total();
			if(mod=='bd_po')$('#flag_ppn_'+param).trigger('click');
			//get_total('qty','harga_beli','total',idx_row);
		}
	});
	$('.angka2').numberbox({
		required:true,min:0,precision:0,groupSeparator:',',decimalSeparator:'.',
		onChange:function(){
			var idx=($(this).attr('idx'));
			var qty=parseFloat($('#qty_'+idx+'_'+param).val());
			var harga=parseFloat($('#'+$(this).attr('id')).val());
			var t_tot=qty*harga;
			$('#t_tot_'+idx+'_'+param).html(NumberFormat(t_tot));
			$('#tot_'+idx+'_'+param).val(t_tot);
			$('#t_qty_'+idx+'_'+param).val(qty);
			hitung_total();
			if(mod=='bd_po')$('#flag_ppn_'+param).trigger('click');
		}
	});
	$('.angka3').numberbox({
		required:true,min:0,precision:0,groupSeparator:'.',decimalSeparator:',',
		onChange:function(x,y){
			//console.log();
			var idx=($(this).attr('idx'));
			var gt=parseFloat($('#grand_total_ppn_'+param).val());
			//var harga=parseFloat($('#'+$(this).attr('id')).val());
			var qty=parseFloat(x);
			var t_tot=((qty/100)*gt);
			//console.log(qty+'->'+harga);
			$('#termin_t_tot_'+idx+'_'+param).html(NumberFormat(t_tot));
			$('#termin_tot_'+idx+'_'+param).val(t_tot);
			//$('#t_qty_'+idx+'_{$acak}').val(qty);
			//hitung_total();
			//get_total('qty','harga_beli','total',idx_row);
		}
	});
	$('.angka4').numberbox({
		required:true,min:0,precision:0,groupSeparator:',',decimalSeparator:'.',
		onChange:function(x,y){
			//console.log();
			var idx=($(this).attr('idx'));
			var harga=parseFloat($('#t_po_'+param).val());
			var t_tot=((x/100) * harga);
			console.log(t_tot);
			$('#t_tot_'+idx+'_'+param).html(NumberFormat(t_tot));
			$('#tot_'+idx+'_'+param).val(t_tot);
			hitung_total_po();
			//$('#t_qty_'+idx+'_{$acak}').val(qty);
			//hitung_total();
			//get_total('qty','harga_beli','total',idx_row);
		}
	});
}
function hitung_total(){
	var tot=0;
	var tot_qty=0;
	$('.h_tot_qty').each(function(i){
		//console.log($(this).val());
		var val=$(this).val();
		tot_qty=tot_qty+parseFloat(val);
		$('#h_tot_qty').html(NumberFormat(tot_qty));
	});
	$('.h_tot').each(function(i){
		var val=$(this).val();
		tot=tot+parseFloat(val);
		$('#h_tot').html(NumberFormat(tot));
		$('#grand_total').val(tot);
	});
}
function hitung_total_po(){
	var tot=0;
	var tot_qty=0;
	$('.h_tot').each(function(i){
		var val=$(this).val();
		tot=tot+parseFloat(val);
		$('#h_tot').html(NumberFormat(tot));
		$('#grand_total').val(tot);
	});
}
function lihat_file(file,mod){
	var jdl,path;
	//windowForm('XXX','aaaa',600,650);
	switch(mod){
		case "tdp":jdl="Preview File TDP";path=host+'__repo/tdp/'+file;break;
		case "sbu":jdl="Preview File SBU";path=host+'__repo/sbu/'+file;break;
		case "surat_kuasa":jdl="Preview File Surat Kuasa";path=host+'__repo/dok/'+file;break;
		case "pakta_integritas":jdl="Preview File Pafta Integritas";path=host+'__repo/dok/'+file;break;
		case "surat_pernyataan":jdl="Preview File Surat Pernyataan";path=host+'__repo/dok/'+file;break;
		case "formulir":jdl="Preview File Formulir";path=host+'__repo/dok/'+file;break;
		case "npwp":jdl="Preview File NPWP";path=host+'__repo/pra_daftar/'+file;break;
		case "siup":jdl="Preview File SIUP";path=host+'__repo/pra_daftar/'+file;break;
		case "ktp":jdl="Preview File KTP";path=host+'__repo/pra_daftar/'+file;break;
		case "pkp":jdl="Preview File KTP";path=host+'__repo/pra_daftar/'+file;break;
		case "akta":jdl="Preview File Akta";path=host+'__repo/akta/'+file;break;
		case "akta_perubahan":jdl="Preview File KTP";path=host+'__repo/akta/'+file;break;
		case "domisili":jdl="Preview File Domisili";path=host+'__repo/domisili/'+file;break;
		case "ijin_usaha":jdl="Preview File Ijin Usaha";path=host+'__repo/ijin_usaha/'+file;break;
		case "ijin_usaha_reg":jdl="Preview File Ijin Usaha";path=host+'__repo/pra_daftar/'+file;break;
		case "tdp_reg":jdl="Preview File TDP";path=host+'__repo/pra_daftar/'+file;break;
		case "pajak":jdl="Preview File Ijin Pajak";path=host+'__repo/pajak/'+file;break;
		case "ta":jdl="Preview File Ijin Tenaga Ahli";path=host+'__repo/tenaga_ahli/'+file;break;
		case "peralatan":jdl="Preview File Ijin Tenaga Ahli";path=host+'__repo/peralatan/'+file;break;
		case "pek_berjalan":jdl="Preview File Ijin Pekerjaan Berjalan";path=host+'__repo/pekerjaan_berjalan/'+file;break;
		case "aspek_keu":jdl="Preview File Ijin Aspek Keuangan";path=host+'__repo/keuangan/'+file;break;
		case "pengalaman":jdl="Preview File Ijin Pengalaman Perusahaan";path=host+'__repo/pengalaman/'+file;break;
	}
	//console.log(file);
	var img2= '<embed src="'+path+'" width="700" height="400" type="application/pdf">';
	$('#judul_modol').html(jdl);
	$('#img').html(img2);
	$('#modol').modal('show');
	
}
function refreshCaptcha(imgCapcha){
	capcha = $('#'+imgCapcha);
	capcha.css({"background-image":"url('"+host+"webpage/genCaptcha/"+Math.random()+"')"});	
}
function getNotif(){
	if(cl_jabatan_id!=3)notif_dir('dir');
	else notif_dir('peg');
}
function notif_dir(usr){
	$.get(host+'modul/get_notif/'+usr,function(r){
		var js=JSON.parse(r);
		$('.isi_notif').html(js.html);
		if(js.jml !=0)$('.jml_notif').html(js.jml);
	});
}
function baca_notif(usr,id,mod){
	$.post(host+'modul/baca',{usr:usr,flag:'F',id:id,mod:mod},function(r){
		if(r==1){
			if(mod=='P'){return window.location.href=host+'pengajuan-anggran';}
			else if(mod=='PO'){return window.location.href=host+'po';}
			else if(mod=='PAO'){return window.location.href=host+'pengajuan-po';}
		}
	});
}
function get_ppn(acak){
	var grand_total_ppn=0;
	var ppn=0;
	if($('#flag_ppn_'+acak).is(":checked")==true){
		//alert('ok');
		ppn=(0.1 * parseFloat($('#grand_total').val()));
		grand_total_ppn=(parseFloat($('#grand_total').val()) + ppn);
		$('#h_ppn').html(NumberFormat(ppn));
		$('#h_grand_total_ppn').html(NumberFormat(grand_total_ppn));
		$('#ppn_'+acak).val(ppn);
		$('#grand_total_ppn_'+acak).val(grand_total_ppn);
		
	}else{
		grand_total_ppn=$('#grand_total').val();
		$('#h_ppn').html(ppn);
		$('#h_grand_total_ppn').html(NumberFormat(parseFloat(grand_total_ppn)));
		$('#ppn_'+acak).val(ppn);
		$('#grand_total_ppn_'+acak).val(parseFloat(grand_total_ppn));
	}
	
	$( ".angka3" ).each(function( index ) {
		var idx=index;
		var tot=((parseFloat($(this).val())/100) * parseFloat(grand_total_ppn));
	 // console.log(tot);
	  //console.log(parseFloat($(this).val()));
	  $('#termin_t_tot_'+idx+'_'+acak).html(NumberFormat(parseFloat(tot)));
	  $('#termin_tot_'+idx+'_'+acak).val(tot);
	  
	});
}
function upl_bukti(mod,id){
	loadingna();
	$.post(host+'modul/get_konten/form_upload',{mod:mod,id:id},function(r){
		winLoadingClose();
		windowForm(r,"Upload Bukti Pembayaran",800,300);
	})
	
}
function get_katalog(mod,id){
	$('#grid_nya_'+mod).hide();
	$('#detil_nya_'+mod).empty().show().addClass("loading");
	$.post(host+'modul/get_konten/katalog', {id:id}, function(resp){
		$('#detil_nya_'+mod).show();
		$('#detil_nya_'+mod).html(resp).removeClass("loading");
	});
}
function get_form_katalog(sts,id_vendor,id){
	loadingna();
	if(sts!='delete'){
		$.post(host+'modul/get_konten/form_katalog',{editstatus:sts,id_vendor:id_vendor,id:id},function(r){
			winLoadingClose();
			windowForm(r,"Form Katalog Produk",800,350);
		});
	}else{
		$.messager.confirm('BSR','Anda Yakin Menghapus Data Ini ?',function(re){
			if(re){
				$.post(host+'modul/cruddata/cl_katalog',{sts_crud:sts,id:id},function(r){
					if(r==1){
						winLoadingClose();
						$.messager.alert('BSR',"Data Terhapus",'info');
						closeWindow();
						dt.datagrid('reload');
					}else{
						winLoadingClose();
						$.messager.alert('BSR',"Data Gagal Terhapus",'error');
						console.log(r);
					}
				});
			}else{
				winLoadingClose();
			}
		});
	}
}
function ganti_pwd(){
	loadingna();
	$.post(host+'modul/get_konten/form_ganti_pwd',function(r){
		winLoadingClose();
		windowForm(r,"Ganti Password",800,300);
	})
}