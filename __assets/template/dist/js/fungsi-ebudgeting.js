(function($){
    $.fn.extend({
        donetyping: function(callback,timeout){
            timeout = timeout || 1e3; // 1 second default timeout
            var timeoutReference,
                doneTyping = function(el){
                    if (!timeoutReference) return;
                    timeoutReference = null;
                    callback.call(el);
                };
            return this.each(function(i,el){
                var $el = $(el);
                // Chrome Fix (Use keyup over keypress to detect backspace)
                // thank you @palerdot
                $el.is(':input') && $el.on('keyup keypress',function(e){
                    // This catches the backspace button in chrome, but also prevents
                    // the event from triggering too premptively. Without this line,
                    // using tab/shift+tab will make the focused element fire the callback.
                    if (e.type=='keyup' && e.keyCode!=8) return;
                    
                    // Check if timeout has been set. If it has, "reset" the clock and
                    // start over again.
                    if (timeoutReference) clearTimeout(timeoutReference);
                    timeoutReference = setTimeout(function(){
                        // if we made it here, our timeout has elapsed. Fire the
                        // callback
                        doneTyping(el);
                    }, timeout);
                }).on('blur',function(){
                    // If we can, fire the event since we're leaving the field
                    doneTyping(el);
                });
            });
        }
    });
})(jQuery);

var grid_nya;
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
if(dd<10){dd='0'+dd} 
if(mm<10){mm='0'+mm}
today = yyyy+'-'+mm+'-'+dd;

function genPieChart(divnya, tipe, judul, data){
    Highcharts.chart(divnya, {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: judul
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: false,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false,
                    format: '<b>{point.name}</b> : {point.percentage:.1f} %',
                    style: {
                        width: '100px'
                    },
                },
                showInLegend: true
            }
        },
        series: data
    });
}

function genColumnChart(divnya, type, xxChart, yyChart, judul, pointformat){
    
    Highcharts.chart(divnya, {
        chart: {
            type: 'column'
        },
        title: {
            text: judul
        },
        xAxis: {
            categories: xxChart,
        },
        scrollbar: {
            enabled: false
        },

        rangeSelector: {
            selected: 1
        },
        yAxis: [{
            min: 0,
            title: {
                text: ''
            },
        }, {
            title: {
                text: ''
            },
            opposite: true
        }],
        legend: {
            shadow: false,
            enabled: (type == "pratut-tiga" ? false : true)
        },
        tooltip: {
            shared:true
        },
        plotOptions: {
	        column: {
	            pointPadding: 0.1,
	            borderWidth: 0
	        }
	    },
        series: yyChart
    });

}

function loadUrl(urls){
    $("#main-konten").empty().addClass("loading");
	$.get(urls,function (html){
	    $("#main-konten").html(html).removeClass("loading");
    });
}

function getClientHeight(){
	var theHeight;
	if (window.innerHeight)
		theHeight=window.innerHeight;
	else if (document.documentElement && document.documentElement.clientHeight) 
		theHeight=document.documentElement.clientHeight;
	else if (document.body) 
		theHeight=document.body.clientHeight;
	
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
		top: Math.round(getClientHeight()/2)-(height/2),
		left: Math.round(getClientWidth()/2)-(width/2),
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
	else if (document.body) 
		theWidth=document.body.clientWidth;

	return theWidth;
}


function genGrid(modnya, divnya, lebarnya, tingginya, code, par1){
	if(lebarnya == undefined){
		lebarnya = getClientWidth()-250;
	}
	if(tingginya == undefined){
		tingginya = getClientHeight()-450;
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
	var pagesizeboy = 10;
	var singleSelek = true;
	var nowrap_nya = true;
	var footer=false;
	var row_number=true;
	var paging=true;
	
	switch(modnya){
		
		case "sasaran_strategis":
			judulnya = "";
			urlglobal = host+'kl_data/?q='+code+'&tp=data';
			fitnya = true;
			nowrap=true;
			//footer=true;
			row_number=true;
			pagesizeboy=20;
			kolom[modnya] = [	
				{field:'kddept',title:'Kode Dept.',width:100, halign:'center',align:'center'},
				{field:'kdsasaran',title:'Kode Sasaran',width:100, halign:'center',align:'left'},
				{field:'nmsasaran',title:'Sasaran',width:300, halign:'center',align:'left'},
			];
		break;
		case "misi":
			judulnya = "";
			urlglobal = host+'kl_data/?q='+code+'&tp=data';
			fitnya = true;
			nowrap=true;
			//footer=true;
			row_number=true;
			pagesizeboy=20;
			kolom[modnya] = [	
				{field:'kddept',title:'Kode Dept.',width:100, halign:'center',align:'center'},
				{field:'kdmisi',title:'Kode Misi',width:100, halign:'center',align:'left'},
				{field:'nmmisi',title:'Misi',width:300, halign:'center',align:'left'},
			];
		break;
		case "visi":
			judulnya = "";
			urlglobal = host+'kl_data/?q='+code+'&tp=data';
			fitnya = true;
			nowrap=true;
			//footer=true;
			row_number=true;
			pagesizeboy=20;
			kolom[modnya] = [	
				{field:'kddept',title:'Kode Dept.',width:100, halign:'center',align:'center'},
				{field:'kdvisi',title:'Kode Visi',width:100, halign:'center',align:'left'},
				{field:'nmvisi',title:'Visi',width:300, halign:'center',align:'left'},
			];
		break;
		
		case "janji_presiden":
			judulnya = "";
			urlglobal = host+'mst_data/?q='+code+'&tp=data';
			fitnya = true;
			nowrap=true;
			//footer=true;
			row_number=true;
			pagesizeboy=20;
			kolom[modnya] = [	
				{field:'kdjanpres',title:'Kode',width:100, halign:'center',align:'center'},
				{field:'nmjanpres',title:'Janji Presiden',width:500, halign:'center',align:'left'},
				{field:'gpjanpres',title:'GP. Janji Presiden',width:200, halign:'center',align:'left'},
			];
		break;
		case "nawa_cita":
			judulnya = "";
			urlglobal = host+'mst_data/?q='+code+'&tp=data';
			fitnya = true;
			nowrap=true;
			//footer=true;
			row_number=true;
			pagesizeboy=20;
			kolom[modnya] = [	
				{field:'kdnawacita',title:'Kode',width:100, halign:'center',align:'center'},
				{field:'nmnawacita',title:'Nawa Cita',width:500, halign:'center',align:'left'},
			];
		break;
		case "tematik_apbn":
			judulnya = "";
			urlglobal = host+'mst_data/?q='+code+'&tp=data';
			fitnya = true;
			nowrap=true;
			//footer=true;
			row_number=true;
			pagesizeboy=20;
			kolom[modnya] = [	
				{field:'kdtema',title:'Kode',width:100, halign:'center',align:'center'},
				{field:'nmtema',title:'Nama Tematik APBN',width:500, halign:'center',align:'left'},
			];
		break;
		case "prioritas_kegiatan":
			judulnya = "";
			urlglobal = host+'mst_data/?q='+code+'&tp=data';
			fitnya = true;
			nowrap=true;
			//footer=true;
			row_number=true;
			pagesizeboy=20;
			kolom[modnya] = [	
				{field:'kdpn',title:'Kd. PriNas',width:100, halign:'center',align:'center'},
				{field:'kdpp',title:'Kd. PriProg',width:100, halign:'center',align:'center'},
				{field:'kdkp',title:'Kode',width:100, halign:'center',align:'center'},
				{field:'nmkp',title:'Nama Prioritas Kegiatan',width:500, halign:'center',align:'left'},
			];
		break;
		case "prioritas_program":
			judulnya = "";
			urlglobal = host+'mst_data/?q='+code+'&tp=data';
			fitnya = true;
			nowrap=true;
			//footer=true;
			row_number=true;
			pagesizeboy=20;
			kolom[modnya] = [	
				{field:'kdpn',title:'Kd. PriNas',width:100, halign:'center',align:'center'},
				{field:'kdpp',title:'Kode',width:100, halign:'center',align:'center'},
				{field:'nmpp',title:'Nama Prioritas Program',width:500, halign:'center',align:'left'},
			];
		break;
		case "prioritas_nasional":
			judulnya = "";
			urlglobal = host+'mst_data/?q='+code+'&tp=data';
			fitnya = true;
			nowrap=true;
			//footer=true;
			row_number=true;
			pagesizeboy=20;
			kolom[modnya] = [	
				{field:'kdpn',title:'Kode',width:90, halign:'center',align:'center'},
				{field:'nmpn',title:'Nama Prioritas Nasional',width:500, halign:'center',align:'left'},
			];
		break;

		case "user_group":
			judulnya = "";
			urlnya = "cl_user_group";
			fitnya = true;
			param=par1;
			row_number=true;
			urlglobal = host+'backoffice-data/'+urlnya;
			
			kolom[modnya] = [
				{field:'user_group',title:'Group User',width:250, halign:'center',align:'left'},				
				{field:'id',title:'User Role Setting',width:120, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						return '<button href="javascript:void(0)" onClick="kumpulAction(\'userrole\',\''+rowData.id+'\',\''+rowData.user_group+'\')" class="easyui-linkbutton" data-options="iconCls:\'icon-save\'">Setting</button>';
					}
				},						
			];
		break;
		case "user_mng":
			judulnya = "";
			urlnya = "tbl_user";
			fitnya = true;
			param=par1;
			row_number=true;
			urlglobal = host+'backoffice-Data/'+urlnya;
			
			kolom[modnya] = [
				{field:'username',title:'User',width:150, halign:'center',align:'left'},				
				{field:'nama_lengkap',title:'Nama',width:250, halign:'center',align:'left'},				
				{field:'user_group',title:'Group User',width:190, halign:'center',align:'left'},				
				{field:'total_input',title:'Total Input',width:120, halign:'center',align:'right'},				
			];
		break;
	}
	
	grid_nya = $("#"+divnya).datagrid({
		title:judulnya,
		height:tingginya,
		width:lebarnya,
		rownumbers:row_number,
		iconCls:'database',
		fit:fitnya,
		striped:true,
		pagination:paging,
		remoteSort: false,
		showFooter:footer,
		singleSelect:singleSelek,
		url: urlglobal,		
		nowrap: nowrap_nya,
		pageSize:pagesizeboy,
		pageList:[10,20,30,40,50,75,100,200],
		queryParams:param,
		frozenColumns:[
			frozen[modnya]
		],
		columns:[
			kolom[modnya]
		],
		onLoadSuccess:function(d){
			$('.btn_grid').linkbutton();
		},
		onClickRow:function(rowIndex,rowData){
			if(modnya=='ldap_user'){
				$('#user_ldap').val(rowData.samaccountname);
				$('#user_na').html(rowData.samaccountname);
				$('#nama_na').html(rowData.displayname);
			}
		},
		onDblClickRow:function(rowIndex,rowData){
			
		},
		toolbar: '#tb_'+modnya,
		rowStyler: function(index,row){
			if(modnya == 'billboard'){
				if (row.status_pemakaian == 1){
					return 'background-color:#C4EED3;'; // return inline style
				}
			}
			
		},
		onLoadSuccess: function(data){
			if(data.total == 0){
				var $panel = $(this).datagrid('getPanel');
				var $info = '<div class="info-empty" style="margin-top:20%;">Data Tidak Tersedia</div>';
				$($panel).find(".datagrid-view").append($info);
			}else{
				$($panel).find(".datagrid-view").append('');
				$('.info-empty').remove();
			}
		},
	});
}

function genTreeGrid(modnya, divnya, lebarnya, tingginya, code, par1){
	if(lebarnya == undefined){
		lebarnya = getClientWidth()-250;
	}
	if(tingginya == undefined){
		tingginya = getClientHeight()-110;
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
	var pagesizeboy = 10;
	var singleSelek = true;
	var nowrap_nya = true;
	var footer=false;
	var row_number=true;
	var paging=true;
	
	switch(modnya){
		
		case "eselon_satu":
		case "sesditjen":
		case "eselon_tiga":
		case "eselon_dua":
		
			if(modnya == 'eselon_dua'){
				urlglobal = host+'ebd_ed_data/?q='+code+'&tp=treedata2';
			}else if(modnya == 'sesditjen'){
				urlglobal = host+'ebd_ses_data/?q='+code+'&tp=treedata2';
			}else if(modnya == 'eselon_tiga'){
				urlglobal = host+'ebd_et_data/?q='+code+'&tp=treedata2';
			}
		
			judulnya = "";
			fitnya = true;
			param = par1;
			nowrap_nya = false;
			row_number = false;
			
			frozen[modnya] = [	
				{field:'level',title:'LEVEL',width:300, halign:'center',align:'left'},								
			];
			kolom[modnya] = [
				[
					{field:'nomenklatur',title:'NOMENKLATUR', rowspan:2, width:300, halign:'center',align:'left',
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},
					{field:'target',title:'TARGET', rowspan:2, width:150, halign:'center',align:'right',
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'output_program'
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},		
					{title:'BIAYA', colspan:3, halign:'center'},	
					{field:'pagu',title:'PERKIRAAN PAGU', rowspan:2, width:150, halign:'center',align:'right',
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran', 
									'indikator_output_program',
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},										
					{title:'KELENGKAPAN DOKUMEN', colspan:4, halign:'center'},
					//{title:'PERSETUJUAN ESELON', colspan:4, halign:'center'},
					/*
					{field:'memo',title:'Action', rowspan:2, width:100, halign:'center',align:'center',
						formatter:function(value,rowData,rowIndex){
							var htmlnya = "";
							var warnabut = "";
							
							if(rowData.tipex){
								if(rowData.memo != null){
									var warnabut = "btn-danger";
								}
								htmlnya += '<button href="javascript:void(0)" onClick="kumpulAction(\'memo_eselon_satu\',\'essat001\', \''+rowData.kdmemo+'\', \''+rowData.tipex+'\', \''+rowData.id+'\', \''+rowData.judul+'\')" title="Memo" class="btn-group '+warnabut+'"><i class="fa fa-file-text"></i></button>';
								
								if(rowData.tipex == 'output_kegiatan'){
									htmlnya += '<button href="javascript:void(0)" onClick="kumpulAction(\'upload_dokumen_kelengkapan\',\''+code+'\', \''+rowData.id+'\', \''+rowData.nomenklatur+'\')" title="Upload Kelengkapan Dokumen" class="btn-group"><i class="fa fa-cloud-upload"></i></button>';
								}
							}
							
							return htmlnya;
						}
					},		
					*/
				],[
					{field:'volume',title:'Volume', width:80, halign:'center',align:'right',
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},	
					{field:'hargasatuan',title:'Harga Satuan', width:130, halign:'center',align:'right',
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'program',
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator', 'akun',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},	
					{field:'jumlah',title:'Jumlah', width:130, halign:'center',align:'right',
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator', 
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},	
					
					//Rows Kelengkapan Dokumen
					{field:'file_tor',title:'TOR',width:50, halign:'center',align:'center',
						formatter:function(value,rowData,rowIndex){
							var htmlnya = "";
							if(value){
								htmlnya += '<a href="'+host+'__repository/tor/'+value+'" target="_blank" title="Lihat File" class="btn-group" ><i class="fa fa-file-pdf-o"></i></a>';
							}
							return htmlnya;
						},
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator', 'akun',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator', 'sub_output_kegiatan',
									'komponen_kegiatan', 'subkomponen_kegiatan', 'item',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},	
					{field:'file_cbd',title:'CBD',width:50, halign:'center',align:'center',
						formatter:function(value,rowData,rowIndex){
							var htmlnya = "";
							if(value){
								htmlnya += '<a href="'+host+'__repository/cbd/'+value+'" target="_blank" title="Lihat File" class="btn-group" ><i class="fa fa-file-pdf-o"></i></a>';
							}
							return htmlnya;
						},
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator', 'akun',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator', 'sub_output_kegiatan',
									'komponen_kegiatan', 'subkomponen_kegiatan', 'item',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},	
					{field:'file_rab',title:'RAB',width:50, halign:'center',align:'center',
						formatter:function(value,rowData,rowIndex){
							var htmlnya = "";
							if(value){
								htmlnya += '<a href="'+host+'__repository/rab/'+value+'" target="_blank" title="Lihat File" class="btn-group" ><i class="fa fa-file-pdf-o"></i></a>';
							}
							return htmlnya;
						},
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator', 'akun',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator', 'sub_output_kegiatan',
									'komponen_kegiatan', 'subkomponen_kegiatan', 'item',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},	
					{field:'file_gbs',title:'GBS',width:50, halign:'center',align:'center',
						formatter:function(value,rowData,rowIndex){
							var htmlnya = "";
							if(value){
								htmlnya += '<a href="'+host+'__repository/gbs/'+value+'" target="_blank" title="Lihat File" class="btn-group" ><i class="fa fa-file-pdf-o"></i></a>';
							}
							return htmlnya;
						},
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator', 'akun',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator', 'sub_output_kegiatan',
									'komponen_kegiatan', 'subkomponen_kegiatan', 'item',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},	
					
					/*
					//Rows Persetujuan
					{field:'persetujuan_eselon_satu',title:'I',width:50, halign:'center',align:'center',
						formatter:function(value,rowData,rowIndex){
							var htmlnya = "";
							if(rowData.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(!arrayblock.includes(rowData.tipex)){ // javascript in array bray	
									if(rowData.persetujuan_eselon_sesditjen == null){
										return '-';
									}else{
										if(rowData.persetujuan_eselon_satu == null){
											if(group == "2"){
												htmlnya += '<button href="javascript:void(0)" onClick="kumpulAction(\'persetujuan_eselon_satu\', \''+code+'\', \''+rowData.idtrx+'\')" title="Tombol Persetujuan Eselon I" class="btn-group btn-danger"><i class="fa fa-check-square-o"></i></button>';
											}else{
												htmlnya += '-';
											}
										}else{
											htmlnya += '<button title="Sudah Disetujui Oleh : '+rowData.persetujuan_eselon_satu+'" class="btn-group btn-success"><i class="fa fa-check-square-o"></i></button>';
										}
										
										return htmlnya;
									}
									
								}
							}
						},
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},		
					{field:'persetujuan_sesditjen',title:'SES',width:50, halign:'center',align:'center',
						formatter:function(value,rowData,rowIndex){
							var htmlnya = "";
							if(rowData.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(!arrayblock.includes(rowData.tipex)){ // javascript in array bray
									//if(rowData.persetujuan_eselon_dua == null && rowData.persetujuan_eselon_tiga == null){
										
									if(rowData.persetujuan_eselon_dua == null){
										return '-';
									}else{
										if(rowData.persetujuan_eselon_sesditjen == null){
											if(group == "5"){
												htmlnya += '<button href="javascript:void(0)" onClick="kumpulAction(\'persetujuan_eselon_sesditjen\', \''+code+'\', \''+rowData.idtrx+'\')" title="Tombol Persetujuan Sesditjen" class="btn-group btn-danger"><i class="fa fa-check-square-o"></i></button>';
											}else{
												htmlnya += '-';
											}
										}else{
											htmlnya += '<button title="Sudah Disetujui Oleh : '+rowData.persetujuan_eselon_sesditjen+'" class="btn-group btn-success"><i class="fa fa-check-square-o"></i></button>';
										}
										
										return htmlnya;
									}
									
								}
							}
						},
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},		
					{field:'persetujuan_eselon_dua',title:'II',width:50, halign:'center',align:'center',
						formatter:function(value,rowData,rowIndex){
							var htmlnya = "";
							if(rowData.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(!arrayblock.includes(rowData.tipex)){ // javascript in array bray	
									if(rowData.persetujuan_eselon_tiga == null){
										return '-';
									}else{
										if(rowData.persetujuan_eselon_dua == null){
											if(group == "3"){
												htmlnya += '<button href="javascript:void(0)" onClick="kumpulAction(\'persetujuan_eselon_dua\', \''+code+'\', \''+rowData.idtrx+'\')" title="Tombol Persetujuan Eselon II" class="btn-group btn-danger"><i class="fa fa-check-square-o"></i></button>';
											}else{
												htmlnya += '-';
											}
										}else{
											htmlnya += '<button title="Sudah Disetujui Oleh : '+rowData.persetujuan_eselon_dua+'" class="btn-group btn-success"><i class="fa fa-check-square-o"></i></button>';
										}
										
										return htmlnya;
									}
									
								}
							}
						},
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},		
					{field:'persetujuan_eselon_tiga',title:'III',width:50, halign:'center',align:'center',
						formatter:function(value,rowData,rowIndex){
							var htmlnya = "";
							if(rowData.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(!arrayblock.includes(rowData.tipex)){ // javascript in array bray
									var arrayaccept = [
										'subkomponen_kegiatan', 'akun', 'item'
									];
									if(arrayaccept.includes(rowData.tipex)){
										if(rowData.persetujuan_eselon_tiga == null){
											if(group == "4"){
												htmlnya += '<button href="javascript:void(0)" onClick="kumpulAction(\'persetujuan_eselon_tiga\', \''+code+'\', \''+rowData.idtrx+'\')" title="Tombol Persetujuan Eselon I" class="btn-group btn-danger"><i class="fa fa-check-square-o"></i></button>';
											}else{
												htmlnya += '-';
											}
										}else{
											htmlnya += '<button title="Sudah Disetujui Oleh : '+rowData.persetujuan_eselon_tiga+'" class="btn-group btn-success"><i class="fa fa-check-square-o"></i></button>';
										}
										
										return htmlnya;
									}
									
								}
							}
						},
						styler: function(value,row,index){
							if(row.tipex){
								var arrayblock = [
									'sasaran_kegiatan', 'indikator_sasaran_kegiatan',
									'output_kegiatan_indikator',
									'Sasaran Kegiatan', 'Output Kegiatan',
									'Sub Output', 'Indikator',
									'Sasaran', 'Output Program', 'Kegiatan',
									'sasaran', 'indikator_sasaran',
									'output_program', 'indikator_output_program',
								];
								if(arrayblock.includes(row.tipex)){
									return 'background-color:#F6D4D0;';
								}
							}
						}
					},*/
				]
			];
		break;
		
	}
	
	grid_tree_nya = $("#"+divnya).treegrid({
		title:judulnya,
		height:tingginya,
		width:lebarnya,
		url: urlglobal,
		rownumbers:row_number,
		nowrap: nowrap_nya,
		idField:'id',
		treeField:'level',
		remoteSort: false,
		singleSelect:singleSelek,
		collapsible:true,
		frozenColumns:[
			frozen[modnya]
		],
		columns:
			kolom[modnya]
		,
		onClickRow:function(row){
			//console.log(row.idskomp);
		},
		onDblClickRow:function(row){
			if(modnya == 'eselon_satu'){
				if(row.editor){
					$.post(host+'es_form/?q='+code+'&tp=form', { 'editstatus':'edit', 'form_tipe':row.tipex, 'id':row.id }, function(resp){
						$('#headernya').html("<b>Form Edit "+row.judul+"</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}
			}else if(modnya == 'eselon_dua'){
				if(row.editor){
					$.post(host+'ebd_ed_form/?q='+code+'&tp=form', { 'editstatus':'edit', 'form_tipe':row.tipex, 'id':row.id }, function(resp){
						$('#headernya').html("<b>Form Edit "+row.judul+"</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}
			}else if(modnya == 'eselon_tiga'){
				if(row.editor){
					$.post(host+'ebd_et_form/?q='+code+'&tp=form', { 'editstatus':'edit', 'form_tipe':row.tipex, 'id':row.id }, function(resp){
						$('#headernya').html("<b>Form Edit "+row.judul+"</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}
			}
		},
		rowStyler: function(row){
			if (row.memo != null){
				return 'background-color:#FBF1CC;'; // return inline style
			}
		},
	});
	
}

function genform(type, modulnya, submodulnya, stswindow, p1, p2, p3){
	var urlpost = host+'backoffice-form/'+submodulnya;
	var urldelete = host+'backoffice-simpan/'+submodulnya;
	var id_tambahan = "";
	var nama_file = "";
	var table = submodulnya;
	var adafilenya = false;
	
	switch(submodulnya){
		
		case "sasaran_strategis":
		case "misi":
		case "visi":
			urlpost = host+'kl_form/?q='+p1+'&tp=form';
		break;
		
		case "janji_presiden":
		case "nawa_cita":
		case "tematik_apbn":
		case "prioritas_kegiatan":
		case "prioritas_program":
		case "prioritas_nasional":
			urlpost = host+'mst_form/?q='+p1+'&tp=form';
		break;
		
		case "user_mng_ldap": 
			table = "tbl_user";
			submodulnya='user_mng';
		break;
	}
	urldelete = host+'backoffice-simpan/'+table;
	switch(type){
		case "add":
			if(stswindow == undefined || stswindow == ''){
				$('#grid_nya_'+submodulnya).hide();
				$('#detil_nya_'+submodulnya).empty().show().addClass("loading");
			}
			$.post(urlpost, {'editstatus':'add', 'ts':table, 'id_tambahan':id_tambahan }, function(resp){
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
			//var row = grid_nya.datagrid('getSelected');
			var row = $("#grid_"+submodulnya).datagrid('getSelected');
			if(row){
				
				if(type=='edit'){
					if(stswindow == undefined || stswindow == ''){
						$('#grid_nya_'+submodulnya).hide();
						$('#detil_nya_'+submodulnya).empty().show().addClass("loading");	
					}
					$.post(urlpost, { 'editstatus':'edit', 'ts':table, id:row.id }, function(resp){
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
					$.messager.confirm('Puskesmas Kecamatan Kemayoran','Anda Yakin Ingin Menghapus Data Ini ?',function(re){
						if(re){
							if(adafilenya){
								nama_file = row.nama_file;
							}
							$.blockUI({ message: '<h3>Proses Hapus Data</h3>' });
							$.post(urldelete, {'id':row.id, 'editstatus':'delete'}, function(r){
								if(r==1){
									$.messager.alert('Puskesmas Kecamatan Kemayoran',"Data Terhapus",'info');
									grid_nya.datagrid('reload');								
								}else{
									console.log(r)
									$.messager.alert('Puskesmas Kecamatan Kemayoran',"Gagal Menghapus Data "+r,'error');
								}
								$.unblockUI();
							});	
						}
					});	
					//}
				}
				
			}else{
				$.messager.alert('Puskesmas Kecamatan Kemayoran',"Pilih Data Yang Akan Dihapus/Diedit",'error');
			}
		break;

		//Case Tambahan
		case "history_notif":
			urlpost = host+'backoffice-form/'+type;
			var row = $("#grid_"+submodulnya).datagrid('getSelected');
			if(row){
				if(stswindow == undefined){
					$('#grid_nya_'+submodulnya).hide();
					$('#detil_nya_'+submodulnya).empty().show().addClass("loading");
				}

				$.post(urlpost, { 'editstatus':type, 'row':row }, function(resp){
					if(stswindow == 'windowform'){
						windowForm(resp, judulwindow, lebar, tinggi);
					}else if(stswindow == 'windowpanel'){
						windowFormPanel(resp, judulwindow, lebar, tinggi);
					}else{
						$('#detil_nya_'+submodulnya).show();
						$('#detil_nya_'+submodulnya).html(resp).removeClass("loading");
					}
				});
			}else{
				$.messager.alert('Puskesmas Kecamatan Kemayoran',"Pilih Data Terlebih Dahulu",'error');
			}
		break;
		case "tindakan":
			urlpost = host+'backoffice-form/'+type;
			var row = $("#grid_"+submodulnya).datagrid('getSelected');
			if(row){
				if(stswindow == undefined){
					$('#grid_nya_'+submodulnya).hide();
					$('#detil_nya_'+submodulnya).empty().show().addClass("loading");
				}
				$.post(urlpost, { 'editstatus':type, 'row':row }, function(resp){
					if(stswindow == 'windowform'){
						windowForm(resp, judulwindow, lebar, tinggi);
					}else if(stswindow == 'windowpanel'){
						windowFormPanel(resp, judulwindow, lebar, tinggi);
					}else{
						$('#detil_nya_'+submodulnya).show();
						$('#detil_nya_'+submodulnya).html(resp).removeClass("loading");
					}
				});
			}else{
				$.messager.alert('Puskesmas Kecamatan Kemayoran',"Pilih Data Terlebih Dahulu",'error');
			}
		break;
		case "print_gizi_buruk":
			alert("Under Construction");
			return false;
		break;
		case "riwayat_gizi_buruk":
			urlpost = host+'backoffice-form/'+type;
			var row = $("#grid_"+submodulnya).datagrid('getSelected');
			if(row){
				if(stswindow == undefined){
					$('#grid_nya_'+submodulnya).hide();
					$('#detil_nya_'+submodulnya).empty().show().addClass("loading");
				}
				$.post(urlpost, { 'editstatus':type, id:row.id, 'lihat_dari':'grid' }, function(resp){
					if(stswindow == 'windowform'){
						windowForm(resp, judulwindow, lebar, tinggi);
					}else if(stswindow == 'windowpanel'){
						windowFormPanel(resp, judulwindow, lebar, tinggi);
					}else{
						$('#detil_nya_'+submodulnya).show();
						$('#detil_nya_'+submodulnya).html(resp).removeClass("loading");
					}
				});
			}else{
				$.messager.alert('Puskesmas Kecamatan Kemayoran',"Pilih Data Terlebih Dahulu",'error');
			}
		break;
		
	}
}

function genTab(div, mod, tab_array, height_tab, width_tab){
	/*
	var id_sub_mod=sub_mod.split("_");
	if(typeof(div_panel)!= "undefined" || div_panel!=""){
		
		$(div_panel).panel({
			width:(typeof(width_panel) == "undefined" ? getClientWidth()-268 : width_panel),
			height:(typeof(height_panel) == "undefined" ? getClientHeight()-100 : height_panel),
			title:judul_panel,
			//fit:true,
			tools:[{
					iconCls:'icon-cancel',
					handler:function(){
						$('#grid_nya_'+id_sub_mod[1]).show();
						$('#detil_nya_'+id_sub_mod[1]).hide();
						$('#grid_'+id_sub_mod[1]).datagrid('reload');
					}
			}]
		}); 
		//
	}
	*/
	
	$(div).tabs({
		title:'AA',
		height: getClientHeight()-150,
		width: getClientWidth()-280,
		plain: false,
		fit:true,
		onSelect: function(title,index){
				var isi_tab=title.replace(/ /g,"_");
				var par={};
				console.log(isi_tab);
				$('#'+isi_tab.toLowerCase()).html('').addClass('loading');
				urlnya = host+'Basarnas-getmodul/'+mod+'/'+isi_tab.toLowerCase();
				
				switch(mod){
					case "kasir":
						var lantainya = title.split(" ");
						var lantainya = lantainya.length-1;
						
						par['posisi_lantai'] = lantainya;
						urlnya = host+'kasir-lantai/';
					break;
				}
				
				$.post(urlnya,par,function(r){
					$('#'+isi_tab.toLowerCase()).removeClass('loading').html(r);
				});
		},
		onUnselect: function(title,index){
			var isi_tab=title.replace(/ /g,"_");
			$('#'+isi_tab.toLowerCase()).html('');
		},
		selected:0
	});
	
	if(tab_array.length > 0){
		for(var x in tab_array){
			var isi_tab = tab_array[x].replace(/ /g,"_");
			$(div).tabs('add',{
				title:tab_array[x],
				index:x,
				selected:(x == 0 ? true : false),
				content:'<div style="padding: 5px;"><div id="'+isi_tab.toLowerCase()+'" style="height: 200px;">'+isi_tab.toLowerCase()+'zzzz</div></div>'
			});
		}
		var tab = $(div).tabs('select',0);
	}
}

function kumpulAction(type, p1, p2, p3, p4, p5, p6){
	var param = {};
	switch(type){
		
		/*
		case "persetujuan_eselon_satu":
		case "persetujuan_eselon_sesditjen":
		case "persetujuan_eselon_dua":
		case "persetujuan_eselon_tiga":
			$.messager.confirm('E-Government DJPK','Anda Yakin Ingin Memberikan Persetujuan Pada Data Ini ?',function(re){
				if(re){
					
					$.post(host+'et_submit/persetujuan_eselon', { 'editstatus':'edit', 'code':p1, 'idtrx':p2, 'field':type }, function(resp){
						if(resp == 1){
							$.messager.alert('E-Government DJPK',"Berhasil Menyetujui Data Ini",'info');
						}else{
							$.messager.alert('E-Government DJPK',"Gagal: "+r,'error');
						}
						
						grid_tree_nya.treegrid({
							loadFilter: function(data){
								var opts = $(this).treegrid('options');
								var originalData = $(this).treegrid('getData');
								if (originalData){
									setState(data);
								}
								return data;
								
								function setState(data){
									for(var i=0; i<data.length; i++){
										var node = data[i];
										var originalNode = findNode(node[opts.idField], originalData);
										if (originalNode){
											node.state =originalNode.state;
										}
										if (node.children){
											setState(node.children);
										}
									}
								}
								
								function findNode(id, data){
									var cc = [data];
									while(cc.length){
										var c = cc.shift();
										for(var i=0; i<c.length; i++){
											var node = c[i];
											if (node[opts.idField] == id){
												return node;
											} else if (node['children']){
												cc.push(node['children']);
											}
										}
									}
									return null;
								}
							}
						});
						
					});
				}
			});
		break;
		*/
		
		//Eselon III
		case "tambah_item":
			var row = $("#grid_eselon_tiga").datagrid('getSelected');
			if(row){
				if(row.tipex == 'akun'){
					$.post(host+'ebd_et_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'item', 'idakun':row.id }, function(resp){
						$('#headernya').html("<b>Form Tambah Item Akun SubKomponen Kegiatan</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');
					});
				}else{
					$.messager.alert('E-Government DJPK',"Pilih Data Akun SubKomponen Kegiatan Terlebih Dahulu",'error');
				}
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data Akun SubKomponen Kegiatan Terlebih Dahulu",'error');
			}
		break;
		case "tambah_akun":
			var row = $("#grid_eselon_tiga").datagrid('getSelected');
			if(row){
				if(row.tipex == 'subkomponen_kegiatan'){
					$.post(host+'ebd_et_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'akun', 'idskomponen':row.id }, function(resp){
						$('#headernya').html("<b>Form Tambah Akun SubKomponen Kegiatan</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}else{
					$.messager.alert('E-Government DJPK',"Pilih Data SubKomponen Kegiatan Terlebih Dahulu",'error');
				}
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data SubKomponen Kegiatan Terlebih Dahulu",'error');
			}
		break;
		case "tambah_subkomponen":
			var row = $("#grid_eselon_tiga").datagrid('getSelected');
			if(row){
				if(row.tipex == 'komponen_kegiatan'){
					$.post(host+'ebd_et_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'subkomponen_kegiatan', 'idkomponen':row.id }, function(resp){
						$('#headernya').html("<b>Form Tambah SubKomponen Kegiatan</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}else{
					$.messager.alert('E-Government DJPK',"Pilih Data Komponen Kegiatan Terlebih Dahulu",'error');
				}
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data Komponen Kegiatan Terlebih Dahulu",'error');
			}
		break;
		
		//Info Data Pendukung
		case "data_pendukung":
			$.post(host+'ebd_ed_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'data_pendukung', 'param':p2 }, function(resp){
				$('#headernya').html("<b>Detail Informasi Data Pendukung</b>");
				$('#modalencuk').html(resp);
				$('#pesanModal').modal('show');  
			});
		break;
		
		//Eselon II
		case "upload_dokumen_kelengkapan":
			$.post(host+'ebd_ed_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'upload_dokumen_kelengkapan', 'id':p2, 'output':p3 }, function(resp){
				$('#headernya').html("<b>Upload Dokumen Pelengkap Output Kegiatan</b>");
				$('#modalencuk').html(resp);
				$('#pesanModal').modal('show');  
			});
		break;
		case "tambah_suboutput_indikator_kegiatan":
			var row = $("#grid_eselon_dua").datagrid('getSelected');
			if(row){
				if(row.tipex == 'output_kegiatan'){
					$.post(host+'ebd_ed_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'suboutput_indikator_kegiatan', 'idoutput':row.id }, function(resp){
						$('#headernya').html("<b>Form Tambah Indikator Output Kegiatan</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}else{
					$.messager.alert('E-Government DJPK',"Pilih Data Output Kegiatan Terlebih Dahulu",'error');
				}
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data Output Kegiatan Terlebih Dahulu",'error');
			}
		break;
		case "tambah_komponen":
			var row = $("#grid_eselon_dua").datagrid('getSelected');
			if(row){
				if(row.tipex == 'suboutput_kegiatan'){
					$.post(host+'ebd_ed_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'komponen_kegiatan', 'idsoutput':row.id }, function(resp){
						$('#headernya').html("<b>Form Tambah Komponen SubOutput Kegiatan</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}else{
					$.messager.alert('E-Government DJPK',"Pilih Data Sub Output Kegiatan Terlebih Dahulu",'error');
				}
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data Sub Output Kegiatan Terlebih Dahulu",'error');
			}
		break;
		case "tambah_suboutput_kegiatan":
			var row = $("#grid_eselon_dua").datagrid('getSelected');
			if(row){
				if(row.tipex == 'output_kegiatan'){
					$.post(host+'ebd_ed_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'suboutput_kegiatan', 'idoutput':row.id }, function(resp){
						$('#headernya').html("<b>Form Tambah SubOutput Kegiatan</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}else{
					$.messager.alert('E-Government DJPK',"Pilih Data Output Kegiatan Terlebih Dahulu",'error');
				}
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data Output Kegiatan Terlebih Dahulu",'error');
			}
		break;
		case "tambah_output_kegiatan":
			var row = $("#grid_eselon_dua").datagrid('getSelected');
			if(row){
				if(row.tipex == 'kegiatan'){
					$.post(host+'ebd_ed_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'output_kegiatan', 'idgiat':row.id }, function(resp){
						$('#headernya').html("<b>Form Tambah Output Kegiatan</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}else{
					$.messager.alert('E-Government DJPK',"Pilih Data Kegiatan Terlebih Dahulu",'error');
				}
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data Kegiatan Terlebih Dahulu",'error');
			}
		break;
		case "tambah_indikator_sasaran_kegiatan":
			var row = $("#grid_eselon_dua").datagrid('getSelected');
			if(row){
				if(row.tipex == 'sasaran_kegiatan'){
					$.post(host+'ebd_ed_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'indikator_sasaran_kegiatan', 'idgiatsas':row.id }, function(resp){
						$('#headernya').html("<b>Form Tambah Indikator Sasaran Kegiatan</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}else{
					$.messager.alert('E-Government DJPK',"Pilih Data Sasaran Kegiatan Terlebih Dahulu",'error');
				}
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data Sasaran Kegiatan Terlebih Dahulu",'error');
			}
		break;
		case "tambah_sasaran_kegiatan":
			var row = $("#grid_eselon_dua").datagrid('getSelected');
			if(row){
				if(row.tipex == 'kegiatan'){
					$.post(host+'ebd_ed_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'sasaran_kegiatan', 'idgiat':row.id }, function(resp){
						$('#headernya').html("<b>Form Tambah Sasaran Kegiatan</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}else{
					$.messager.alert('E-Government DJPK',"Pilih Data Kegiatan Terlebih Dahulu",'error');
				}
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data Kegiatan Terlebih Dahulu",'error');
			}
		break;
		
		/*
		//Fungsi Action Eselon I
		case "sahkan_eplanning":
			$.messager.confirm('E-Government DJPK','Anda Yakin Untuk Sahkan Data Eplanning ?',function(re){
				if(re){
					$.blockUI({ message: '<h3>Proses Data</h3>' });
					$.post(host+'es_submit/sahkaneplanning', { 'editstatus':'ablahu', 'code':p1 }, function(resp){
						if(resp == 1){
							$.messager.alert('E-Government DJPK','Data Eplanning Berhasil Disahkan','info'); 
						}else if(r==2){ 
							$.messager.alert('E-Government DJPK','Gagal Proses Otentifikasi Kode','warning'); 
						}else if(r==4){ 
							$.messager.alert('E-Government DJPK','Masih Ada Data Yang Belum Disetujui Es. I, Harap Cek Kembali','warning'); 
						}else{
							$.messager.alert('E-Government DJPK','Proses Simpan Data Gagal '+r,'warning'); 
						}
						
						$.unblockUI();
					});
				}
			});
		break;
		
		case "edit_usulan_eselon_satu":
			var row = $("#grid_eselon_satu_usulan").datagrid('getSelected');
			if(row){
				$.post(host+'es_form/?q='+p1+'&tp=form', { 'editstatus':'edit', 'form_tipe':'usulan_rencana', 'id':row.id }, function(resp){
					$('#headernya').html("<b>Form Edit Usulan Rencana Kerja</b>");
					$('#modalencuk').html(resp);
					$('#pesanModal').modal('show');  
				});
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data Usulan Rencana Terlebih Dahulu",'error');
			}
		break;
		case "tambah_usulan_eselon_satu":
			$.post(host+'es_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'usulan_rencana' }, function(resp){
				$('#headernya').html("<b>Form Tambah Usulan Rencana Kerja</b>");
	            $('#modalencuk').html(resp);
	            $('#pesanModal').modal('show');  
			});
		break;
		case "memo_eselon_satu":
			$.post(host+'es_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'memo', 'kdmemo':p2, 'tipex':p3, 'id':p4 }, function(resp){
				$('#headernya').html("<b>Memo "+p5+"</b>");
				$('#modalencuk').html(resp);
				$('#pesanModal').modal('show');  
			});
		break;
		case "tambah_indikator_output":
			var row = $("#grid_eselon_satu").datagrid('getSelected');
			if(row){
				if(row.tipex == 'output_program'){
					$.post(host+'es_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'indikator_output_program', 'kdprogout':row.id }, function(resp){
						$('#headernya').html("<b>Form Tambah Indikator Output Program</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}else{
					$.messager.alert('E-Government DJPK',"Pilih Data Output Program Terlebih Dahulu",'error');
				}
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data Program Terlebih Dahulu",'error');
			}
		break;
		case "tambah_output":
			var row = $("#grid_eselon_satu").datagrid('getSelected');
			if(row){
				if(row.tipex == 'program'){
					$.post(host+'es_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'output_program' }, function(resp){
						$('#headernya').html("<b>Form Tambah Output Program</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}else{
					$.messager.alert('E-Government DJPK',"Pilih Data Program Terlebih Dahulu",'error');
				}
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data Program Terlebih Dahulu",'error');
			}
		break;
		case "tambah_indikator_sasaran":
			var row = $("#grid_eselon_satu").datagrid('getSelected');
			if(row){
				if(row.tipex == 'sasaran'){
					$.post(host+'es_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'indikator_sasaran', 'kdprogsas':row.id }, function(resp){
						$('#headernya').html("<b>Form Tambah Indikator Sasaran</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}else{
					$.messager.alert('E-Government DJPK',"Pilih Data Sasaran Terlebih Dahulu",'error');
				}
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data Terlebih Dahulu",'error');
			}
		break;
		case "tambah_sasaran":
			var row = $("#grid_eselon_satu").datagrid('getSelected');
			if(row){
				if(row.tipex == 'program'){
					$.post(host+'es_form/?q='+p1+'&tp=form', { 'editstatus':'add', 'form_tipe':'sasaran' }, function(resp){
						$('#headernya').html("<b>Form Tambah Sasaran</b>");
						$('#modalencuk').html(resp);
						$('#pesanModal').modal('show');  
					});
				}else{
					$.messager.alert('E-Government DJPK',"Pilih Data Program Terlebih Dahulu",'error');
				}
			}else{
				$.messager.alert('E-Government DJPK',"Pilih Data Program Terlebih Dahulu",'error');
			}
		break;
		//End Fungsi Action Eselon I
		*/
		
		case "userrole":
			$.post(host+'set_form/?q=su002&tp=form_role', {'id':p1, 'editstatus':'add'}, function(resp){
				$('#headernya').html("<b>Form User Group Role Setting - "+p2+"</b>");
	            $('#modalencuk').html(resp);
	            $('#pesanModal').modal('show');  
			});
		break;				
	}
}	

function submit_form(frm,func){
	var url = jQuery('#'+frm).attr("url");
   // $.messager.progress();
	jQuery('#'+frm).form('submit',{
            url:url,
            onSubmit: function(){
				var isValid = $(this).form('validate');
				if (!isValid){
					//$.messager.progress('close');	// hide progress bar while the form is invalid
				}
				return isValid;	
            },
            success:function(data){
                if (func == undefined ){
                     if (data == "1"){
                        pesan('Data Sudah Disimpan ','Sukses');
                    }else{
                         pesan(data,'Result');
                    }
                }else{
                    func(data);
                }
				//$.messager.progress('close');
            },
            error:function(data){
                 if (func == undefined ){
                     pesan(data,'Error');
                }else{
                    func(data);
                }
            }
    });
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
	console.log(date);
	var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
}
function parserDate(s){
	if (!s) return new Date();
    var ss = s.split('-');
    var y = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var d = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
        return new Date(y,m-1,d)
    } else {
        return new Date();
    }
}


function clear_form(id){
	$('#'+id).find("input[type=text], textarea,select").val("");
	//$('.angka').numberbox('setValue',0);
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
	windowLoading("<img src='"+host+"__assets/backend/img/loading.gif' style='position: fixed;top: 50%;left: 50%;margin-top: -10px;margin-left: -25px;'/>","Sedang Proses, Mohon Tunggu",200,100);
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
function gen_editor(id){
	tinymce.init({
		  selector: id,
		  height: 200,
		  plugins: [
				"advlist autolink lists link image charmap print preview anchor",
				"searchreplace visualblocks code fullscreen",
				"insertdatetime media table contextmenu paste jbimages"
		    ],
			
		  // ===========================================
		  // PUT PLUGIN'S BUTTON on the toolbar
		  // ===========================================
		  menubar: true,
		  toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
			
		  // ===========================================
		  // SET RELATIVE_URLS to FALSE (This is required for images to display properly)
		  // ===========================================
			
		  relative_urls: false
		});
		
		tinyMCE.execCommand('mceRemoveControl', true, id);
		tinyMCE.execCommand('mceAddControl', true, id);
	
}
function cariData(divnya, table, acak){
	var post_search = {};
	post_search['kat'] = $('#kat_'+divnya+'_'+acak).val();
	post_search['key'] = $('#key_'+divnya+'_'+acak).val();
	post_search['table'] = table;
	
	if(divnya == "gizi_buruk"){
		post_search['kelurahan'] = $('#kelurahan_'+acak).val();
		post_search['rw'] = $('#rw_'+acak).val();
		post_search['bb_u'] = $('#bb_u_'+acak).val();
		post_search['tb_u'] = $('#tb_u_'+acak).val();
		post_search['bb_tb'] = $('#bb_tb_'+acak).val();
	}
	
	$('#grid_'+divnya).datagrid('reload', post_search);
}

function proseslaporan(divnya, table, acak){
	var post_report = {};
	switch(divnya){
		case "laporan_tukin_persatker":
			post_report['periode'] = $('#periode_'+acak).val();
			post_report['satker'] = $('#filter_satker_'+acak).val();
		break;
		case "laporan_tukin_perkelas":
			post_report['periode'] = $('#periode_'+acak).val();
			post_report['kelas'] = $('#filter_kelas_'+acak).val();
		break;
		case "rekap_tukin_persatker":
			post_report['periode'] = $('#periode_'+acak).val();
		break;
		case "rekap_tukin_perkelas":
			post_report['periode'] = $('#periode_'+acak).val();
		break;
	}
	$('#grid_'+divnya).datagrid('reload', post_report);
}

function advancedSearch(divnya, table, acak, type){
	var post_search = {};
	
	if(type == 'balikin'){
		$('#no_dok_'+acak).val('');
		$('#jns_dok_'+acak).val('');
		$('#tgl_arsip_'+acak).val('');
		$('#perihal_'+acak).val('');
		$('#pengirim_'+acak).val('');
	}else{
		post_search['advanced_search'] = "advanced";
		post_search['no_dokumen'] = $('#no_dok_'+acak).val();
		post_search['jenis_dokumen'] = $('#jns_dok_'+acak).val();
		post_search['tanggal_arsip'] = $('#tgl_arsip_'+acak).val();
		post_search['perihal'] = $('#perihal_'+acak).val();
		post_search['pengirim'] = $('#pengirim_'+acak).val();
		post_search['table'] = table;
	}
	
	$('#grid_'+divnya).datagrid('reload', post_search);
	//$('#grid_'+divnya).datagrid('refreshRow');

	/*
	if($('#kat_'+acak).val()!=''){
		grid_nya.datagrid('reload',post_search);
	}else{
		$.messager.alert('Aldeaz Basarnas Tukin',"Pilih Kategori Pencarian",'error');
	}
	//$('#grid_'+typecari).datagrid('reload', post_search);
	*/

}



function simpan_form(id_form,id_cancel,msg){
	if ($('#'+id_form).form('validate')){
		loadingna();
		submit_form(id_form,function(r){
			//console.log(r)
			if(r==1){
				$.messager.alert('Basarnas Tukin',msg,'info');
				$('#'+id_cancel).trigger('click');
				grid_nya.datagrid('reload');
				winLoadingClose();
			}else{
				console.log(r);
				$.messager.alert('Basarnas Tukin',"Tidak Dapat Menyimpan Data " + r,'error');
				winLoadingClose();
			}
		});
	}else{
		$.messager.alert('Basarnas Tukin',"Isi Data Yang Kosong ",'info');
	}
}

function get_form(mod,sts,acak){
	param={};
	//if(sts=='edit_flag'){param['editstatus']='edit';}else{param['editstatus']=sts;}
	param['editstatus']=sts;
	switch(mod){
		case "pembayaran":
			if(sts=='edit' || sts=='delete'){
				var row = $("#list_voucher_"+acak).datagrid('getSelected');
				if(row){
					if(sts=='edit'){
						$('#editstatus_'+acak).val('edit');
						$('#id_'+acak).val(row.id);
						$('#jumlah_bayar'+acak).val(row.jumlah_bayar);
						$('#tgl_pembayaran'+acak).val(row.tgl_pembayaran);
						$('#modal_nya').modal('show');
						if(row.file){$('#upl_ex_'+acak).html('File Exist :'+row.file);}
					}else{
						$.messager.confirm('PT. Dienka Utama','Anda Yakin Ingin Menghapus Data Ini ?',function(re){
							if(re){
								loadingna();
								$.post(host+'backoffice-simpan/tbl_pembayaran_invoice',{editstatus:'delete',id:row.id},function(r){
									if(r==1){
										$.messager.alert('PT. Dienka Utama','Data Sudah Terhapus','info'); 
										$('#editstatus_'+acak).val('add');
										$('#id_'+acak).val('');
										$('#jumlah_bayar'+acak).val('');
										$('#tgl_pembayaran'+acak).val('');
										$('#modal_nya').modal('hide');
										$("#list_voucher_"+acak).datagrid('reload');
										
									}else{
										
										$.messager.alert('PT. Dienka Utama','Proses Simpan Data Gagal '+r,'warning');
									}
									winLoadingClose();
								});
							}
						});
					}
				}else{
					$.messager.alert('PT. Dienka Utama','Pilih Data Dalam List Grid','error');
				}
			}else{
					$('#editstatus_'+acak).val('add');
					$('#id_'+acak).val('');
					$('#jumlah_bayar'+acak).val('');
					$('#tgl_pembayaran'+acak).val('');
					$('#modal_nya').modal('show');
					$('#upl_ex_'+acak).html('');
			}
		break;
		case "voucher_management":
			if(sts=='edit' || sts=='delete'){
				var row = $("#list_voucher_"+acak).datagrid('getSelected');
				if(row){
					if(sts=='edit'){
						$('#editstatus_'+acak).val('edit');
						$('#id_'+acak).val(row.id);
						$('#nama_pengeluaran'+acak).val(row.nama_pengeluaran);
						$('#qty'+acak).val(row.qty);
						$('#jumlah'+acak).val(row.jumlah);
						$('#total'+acak).val(row.total);
						$('#modal_nya').modal('show');
						if(row.file){$('#upl_ex_'+acak).html('File Exist :'+row.file);}
						
						/*
						if(row.tipe == "DV"){
							$('#invoice_voucher').hide();
							$('#tab-2').hide();
							$('#tab-2').removeClass("active");
							
							$('#daily_voucher').show();
							$('#tab-1').show();
							$('#tab-1').addClass("active");
						}else if(row.tipe == "IV"){
							$('#daily_voucher').hide();
							$('#tab-1').hide();
							$('#tab-1').removeClass("active");
							
							$('#invoice_voucher').show();
							$('#tab-2').show();
							$('#tab-2').addClass("active");
						}
						*/
						
						//$('#invoice_voucher').hide();
						//$('#tab-2').hide();
						
					}else{
						$.messager.confirm('PT. Dienka Utama','Anda Yakin Ingin Menghapus Data Ini ?',function(re){
							if(re){
								loadingna();
								$.post(host+'backoffice-simpan/tbl_pengeluaran_invoice',{editstatus:'delete',id:row.id},function(r){
									if(r==1){
										$.messager.alert('PT. Dienka Utama','Data Sudah Terhapus','info'); 
										$('#editstatus_'+acak).val('add');
										$('#id_'+acak).val('');
										$('#nama_pengeluaran'+acak).val('');
										$('#qty'+acak).val('');
										$('#jumlah'+acak).val('');
										$('#total'+acak).val('');
										$('#modal_nya').modal('hide');
										$("#list_voucher_"+acak).datagrid('reload');
									}else{
										$.messager.alert('PT. Dienka Utama','Proses Simpan Data Gagal '+r,'warning');
									}
									winLoadingClose();
								});
							}
						});
					}
				}else{
					$.messager.alert('PT. Dienka Utama','Pilih Data Dalam List Grid','error');
				}
			}else{
				$('#editstatus_'+acak).val('add');
				$('#id_'+acak).val('');
				$('#nama_pengeluaran'+acak).val('');
				$('#qty'+acak).val('');
				$('#jumlah'+acak).val('');
				$('#total'+acak).val('');
				
				/*
				$('#daily_voucher').show();
				$('#invoice_voucher').show();
				//$('#tab-2').css({"display":"inline"});
				$('#tab-1').show();
				$('#tab-1').addClass("active");
				$('#tab-2').removeClass("active");
				*/
				
				$('#modal_nya').modal('show');
				$('#upl_ex_'+acak).html('');
			}
		break;
	}
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
function get_report(mod,acak){
	var param={};
	switch (mod){
		case "report_inv_paid":
		case "report_inv_unpaid":
			param['start_date']=$('#start_date_'+acak).datebox('getValue');
			param['end_date']=$('#end_date_'+acak).datebox('getValue');
			param['type_trans']=$('#type_transaction_'+acak).val();
		break;
	}
	$('#isi_report_'+acak).addClass('loading').html('');
	$.post(host+'Basarnas-Report/'+mod,param,function(r){
		$('#isi_report_'+acak).removeClass('loading').html(r)
	});
	
	
}
function myparser(s){
    if (!s) return new Date();
    var ss = (s.split('-'));
    var y = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var d = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
        return new Date(y,m-1,d);
    } else {
        return new Date();
    }
}
function get_detil(mod,id_data,par1){
	$('#grid_nya_'+mod).hide();
	$('#detil_nya_'+mod).html('').show().addClass("loading");
	$.post(host+'Basarnas-GetDetil',{mod:mod,id:id_data},function(r){
		$('#detil_nya_'+mod).html(r).removeClass("loading");
	});
}

function openWindowWithPost(url,params){
    var newWindow = window.open(url, 'winpost'); 
    if (!newWindow) return false;
    var html = "";
    html += "<html><head></head><body><form  id='formid' method='post' action='" + url + "'>";

    $.each(params, function(key, value) { 
		if (value instanceof Array || value instanceof Object) {
			$.each(value, function(key1, value1) { 
				html += "<input type='hidden' name='" + key + "["+key1+"]' value='" + value1 + "'/>";
			});
		}else{
			html += "<input type='hidden' name='" + key + "' value='" + value + "'/>";
		}
    });
   
    html += "</form><script type='text/javascript'>document.getElementById(\"formid\").submit()</script></body></html>";
    newWindow.document.write(html);
    return newWindow;
}
function export_data(type,mod,acak){
	var url=host+'Basarnas-Cetak'
	var params={};
	switch(mod){
		case "laporan_tukin_persatker":
			params['satker']=$('#filter_satker_'+acak).val();
		break;
		case "laporan_tukin_perkelas":
			params['kelas']=$('#filter_kelas_'+acak).val();
		break;
	}	
	params['periode']=$('#periode_'+acak).val();
	params['mod']=mod;
	params['type']=type;
	openWindowWithPost(url,params);
}

function tambah_row(mod,param){
	var tr_table;
	switch(mod){
		
		case "bd_kamus":
			idx_row++;
			tr_table +='<tr class="tr_inv" id="tr_inv_'+idx_row+'" idx='+idx_row+'>';
			tr_table +='<td><select id="kdakun_'+idx_row+'" row="'+idx_row+'" name="kdakun[]" class="select2nya akunnya" >';
			tr_table +=data_akun;
			tr_table +='</select></td>';
			
			tr_table += '<td><select id="cbitem_'+idx_row+'" name="kdsbu[]" row="'+idx_row+'" class="select3nya itemnya" >';
			tr_table += data_sbu;
			tr_table += '</select>  <input type="hidden" name="nmitem[]" id="nmitem_'+idx_row+'" /></td>';
			
			tr_table += '<td>';
			tr_table += '<span id="nama_item_'+idx_row+'"></span>';
			tr_table += '</td>';
			
			tr_table +='<td><input type="text" name="volume[]" class="form-control angka"></td>';
			tr_table +='<td><input type="text" id="harga_'+idx_row+'" name="harga[]" class="form-control angka"></td>';
			tr_table +='<td><input type="text" id="satkeg_'+idx_row+'" name="satuan[]" class="form-control"></td>';
			tr_table +='<td>';
			tr_table +='<a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="$(this).parents(\'tr\').first().remove();"><i class="fa fa-times"></i> Akun</a> &nbsp;';
			tr_table +='<a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="tambah_row_2(\'bd_kamus_item\', $(this).closest(\'tr\'), \''+idx_row+'\')"><i class="fa fa-plus"></i> Item</a>';
			tr_table +='</td>';
			
			tr_table +='</tr>';
		break;
		
		case "bd_item":
			//tr_table +='<td><input type="text" name="nmitem[]" class="form-control"></td>';
			idx_row++;
			tr_table +='<tr class="tr_inv" id="tr_inv_'+idx_row+'" idx='+idx_row+'>';
			tr_table +='<td><select id="kdakun_'+idx_row+'" row="'+idx_row+'" name="kdakun[]" class="select2nya" >';
			tr_table +=data_akun;
			tr_table +='</select></td>';
			
			tr_table += '<td><select id="cbitem_'+idx_row+'" name="kdsbu[]" row="'+idx_row+'" class="select3nya itemnya" >';
			tr_table += data_sbu;
			tr_table += '</select>  <input type="hidden" name="nmitem[]" id="nmitem_'+idx_row+'" /></td>';
			
			tr_table += '<td> <center> <input type="text" name="vol1[]" style="width:50px;" class="form-control angka" />';
			tr_table += 'x <input type="text" name="sat1[]" style="width:50px;" class="form-control" /> </center>';
			tr_table += '</td>';
			tr_table += '<td> <center><input type="text" name="vol2[]" style="width:50px;" class="form-control angka" />';
			tr_table += 'x <input type="text" name="sat2[]" style="width:50px;" class="form-control" /> </center>';
			tr_table += '</td>';
			tr_table += '<td> <center><input type="text" name="vol3[]" style="width:50px;" class="form-control angka" />';
			tr_table += 'x <input type="text" name="sat3[]" style="width:50px;" class="form-control" /></center>';
			tr_table += '</td>';
			
			tr_table +='<td><input type="text" name="volume[]" class="form-control angka"></td>';
			tr_table +='<td><input type="text" id="harga_'+idx_row+'" name="harga[]" class="form-control angka"></td>';
			tr_table +='<td><input type="text" id="satkeg_'+idx_row+'" name="satuan[]" class="form-control"></td>';
			tr_table +='	<td><a href="javascript:void(0);" class="btn btn-danger btn-circle" onclick="$(this).parents(\'tr\').first().remove();"><i class="fa fa-times"></i></a></td>';
			tr_table +='</tr>';
		break;
		case "bd_inv":
			idx_row++;
			tr_table +='<tr class="tr_inv" id="tr_inv_'+idx_row+'" idx='+idx_row+'>';
			tr_table +='<td><select id="cl_layanan_id_'+idx_row+'" row="'+idx_row+'" name="cl_layanan_id[]" required="" class="form-control validasi cek_hpp" style="padding:0;height:27px;font-size:11px;">';
			tr_table +=opt_layanan;
			tr_table +='</select></td>';
			tr_table +='	<td><input type="text" name="nama[]" style="width:100%;height:27px;" class="validasi"></td>';
			tr_table +='	<td style="text-align:right;"><input type="hidden" name="hpp[]" id="val_hpp_'+idx_row+'"><span id="hpp_'+idx_row+'"></span></td>';
			tr_table +='	<td style="text-align:right;"><input type="text" name="hpp_penawaran[]" class="angka tot" style="height:28px;"> </td>';
			tr_table +='	<td><a href="javascript:void(0);" class="btn btn-danger btn-circle" onclick="$(this).parents(\'tr\').first().remove();"><i class="fa fa-times"></i></a></td>';
			tr_table +='</tr>';
		break;
	}
	
	$('.'+mod).append(tr_table);	
	if(mod == 'bd_item'){
		$('.select2nya').chosen( { width: "200px" } );
		$('.select3nya').chosen( { width: "200px" } );
	}else if(mod == 'bd_kamus'){
		$('.select2nya').chosen( { width: "200px" } );
		$('.select3nya').chosen( { width: "200px" } );
	}else{
		$('.select2nya').chosen( { width: "100%" } );
	}
	
	$('.itemnya').change(function(){
		var biaya = $(this).find('option:selected').attr('biaya');
		var satkeg = $(this).find('option:selected').attr('satkeg');
		var nmitem = $(this).find(":selected").text();
		
		$('#harga_'+$(this).attr('row')).val(NumberFormat(biaya));
		$('#satkeg_'+$(this).attr('row')).val(satkeg);
		$('#nmitem_'+$(this).attr('row')).val(nmitem);
		$('#nama_item_'+$(this).attr('row')).html(nmitem);
	});
	
	$('.akunnya').change(function(){
		var akun = $(this).val();
		var akun_nm = $(this).find(":selected").text();
		
		$('.kdakun_'+$(this).attr('row')).val(akun);
		$('.nmakun_'+$(this).attr('row')).html(akun_nm);
	});
	
	$(".angka").maskMoney({
		precision:0,
		thousands:'.',
		allowZero: true, 
		defaultZero: false
	});	
}

function getWeeks(tipe, days) {
	if(tipe == "bulan_hari"){
		var value = {
	    	month : Math.floor(days / 30),
	    	days : ((days)%30)
		};
		return value.month+" Bulan, "+value.days+" Hari";
	}else if(tipe == "minggu_hari"){
		var value = {
	    	month : Math.floor(days / 30),
	    	days : ((days)%7)
		};
		return value.weeks+" Minggu, "+value.days+" Hari";
	}else if(tipe == "bulan"){
		var value = {
	    	month : Math.floor(days / 30),
		};
		return value.month;
	}
}

function isNumber(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	//console.log(charCode);
	if ( (charCode > 31 && charCode < 46) || charCode > 57) {
	   return false;
	}
	return true;
}

