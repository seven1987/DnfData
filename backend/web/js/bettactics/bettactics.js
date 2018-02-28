(function(window, $, undefined) {
	
	var config = {
		pagesize: 30,
		data:{
			defaultData:{
				agent_id:'',
				han_id: '',
				ruletype: '',
				rulevalue: "",
				dealtype: "",
				createtime:"",
			},
		},
	};

	var param = {
		agent: '',
		dealtype:"",
		merge: '',
	};
	
	var curPagenum = 1;
	var load = function(pagenum) {

		if(!pagenum) {
			return;
		}
		param.agent = $('#agent').val();
		param.rulestatus = $('#rulestatus').val();
		// param.merge = $('#merge').val();// 暂时不需要
		
		var loadingEle = $("#betPortlet .betPortlet");

		var initPage = function(pagesize, pagenum, total, loadData) {
			
			new ktadmin.UI.paginator({
					loadData: loadData,
					totalPage: Math.ceil(total / pagesize),
					page: pagenum,
					rows: pagesize,
					pageViews: 10,
					ulCls: 'pagination',
					pageType: 'normal',
					pageWrapId: '#pagewrap-all'
			}).init();
		};

		//显示加载状态
		// App.blockUI(loadingEle);

		$.ajax({
			url: config.url_list,
			data: {
				'agent': param.agent,
				'rulestatus': param.rulestatus,
				'pagenum': config.pagesize,
				'pagesize': pagenum,
				'merge': param.merge
			},
			dataType: "json",
			success: function(response) {

				if (response.ret == 1) {

					var html = ktadmin.Utils.tmpl("#tmpl-initquery", {
						list: response.data.data,
					});
					
					$("#table-content").html(html);
					
					initPage(config.pagesize, pagenum, response.data.count, load);
					
					curPagenum = pagenum;
				}

			},
			// complete: function() {
			// 	App.unblockUI(loadingEle);
			// }
		});
	};


	var betAdd = function(style) {

		var form = $('#betTacticsAdd'),
			modal = $("#modal_add");

		var formData = ktadmin.Page.getFormData(form);
		// console.log(formData);
		$.ajax({
			url: config.url_add,
			data: formData,
			type: "post",
			dataType: "json",
			success: function(response) {
				console.log(response);
				if (response.ret == 1) {
					modal.modal("hide");
					load(curPagenum);
				}else{
					modal.modal("hide");
					alert(response.msg);
					//ktadmin.UI.tips.success(response.msg);/*显示错误*/
				}
			},
		});	
	};


	var betEdit = function() {

		/*获取表单值与模态框id 编辑可能会有ID 来做where条件*/
		var form = $('#betTacticsEdit'),
			modal = $("#modal_edit");
	
		var formData = ktadmin.Page.getFormData(form);	
		console.log(formData);
		$.ajax({
			url: config.url_edit,
			data: formData,
			type: "post",
			dataType: "json",
			success: function(response) {
				console.log(response);
				if (response.ret == 1) {
					modal.modal("hide");
					load(curPagenum);
				}else{
					modal.modal("hide");
					console.log(response.msg);
					alert(response.msg);
					//ktadmin.UI.tips.success(response.msg);/*显示错误*/
				}
			},
		});	
	}

	var bindEvent = function() {

		$("#btnSearch").click(function(){
			load(1);
		});

		$('#btnbetadd').click(function() {
			betAdd();
		});
		
		$("#btnbetedit").click(function() {
			betEdit();
		});
		$("#table-content").on("click", ".btnEditBet", function() {
			/*获取hr邻近的data的值*/
			var link = $(this),
				tr = link.closest("tr"),
				list = tr.data("list");

			list = decodeURI(list);
			list = JSON.parse(list);
			console.log(list);
			$('#agent_edit').val(list.agent_id);
			$("#ruletype_edit").val(list.ruletype);
			$("#rulevalue_edit").val(list.rulevalue);
			$("#rulestatus_edit").val(list.status);
			var ridaolen = document.berform.dealtype.length;
	        for(var i=0;i<ridaolen;i++){
	            if(list.dealtype==document.berform.dealtype[i].value){
	                document.berform.dealtype[i].checked=true
	            }
	        }

		});
	
	};

	var init = function(settings) {
		
		$.extend(config, settings);
		
		bindEvent();
		
		load(1);
	};

	$.extend(ktadmin.Page, {
		initDataEntryBetTactics: init
	});
}(this, jQuery));