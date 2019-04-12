var contex_menu = null;
var dong_tree = null;
var jing_tree = null;
var project_name = "";
var editing_file = "";
var file_changed = false;
var is_staticfile = false;
var editor_arr = [];
var reg = /^[a-zA-Z]{3,15}$/;
var tempid = "";
var bfw_sys_tag_list= [];
var bfw_sys_method_list=[];
var bfw_tag_list= [];
var bfw_method_list=[];
var _bfw_config = {
	baseurl : "",
	routetype : "",
	jsbaseurl : "?webide=1&getstatic=/",
	cssbaseurl : "?webide=1&getstatic=/"
};


var langTools = ace.require("ace/ext/language_tools");
ace.require("ace/ext/language_tools");
contex_menu = {
	'context2' : {
		opennode : function(node) {

				openfile(getfilepath(node)+"\\"+node.tag, project_name);

		},
		foldermenu : [ {
			type : "Common",
			text : '新建',
			// icon : '/?getstatic=/folder.png',
			action : function(node) {
				
				if(node.tag=="html"||node.tag=="css"||node.tag=="js"){
					    var fname=prompt("请输入文件名，不能包含后缀","test"); 
						if (fname != null && fname != "" && namecheck(fname)) {
							var url = "";
							if (is_staticfile) {
								url = "?webide=1&isstatic=1&createfiles="
										+ fname + "&parent=" + project_name
										+ "&pfolder=" + node.tag+"&ftype="+node.tag;
							} else {
								url = "?webide=1&createfiles=" + fname
										+ "&parent=" + project_name
										+ "&pfolder=" + node.tag;
							}
					    	ajax(url, function(data) {
					    		var file = fname + "."+node.tag;
						        var newnode = node.createChildNode( file, false,
								'?webide=1&getstatic=/file.png',file,
								'context2');
						        jing_tree.selectNode(newnode);
						        
						        openfile( getfilepath(newnode)+"\\"+file, project_name);
		
							});
					    } 
				}

			},
		} ],
		filemenu : [ {
			type : "Common",
			text : '删除',
			// icon : '/?getstatic=/folder.png',
			action : function(node) {
				if (window.confirm("您确定删除吗?")) {
					var url = "";
					if (is_staticfile) {
						url = "?webide=1&isstatic=1&delfiles="+
							getfilepath(node)+"\\" +node.tag + "&parent=" + project_name;
					} else {
						url = "?webide=1&delfiles=" + getfilepath(node)+"\\" +node.tag 
								+ "&parent=" + project_name;
					}
					ajax(url, function(data) {
						jing_tree.removeNode(node);
						//editor.setValue("");
						// refleshdir(project_name);
					});
				}
			}
		}, {
			type : "Common",
			text : '重命名',
			// icon : '/?getstatic=/folder.png',
			action : function(node) {
				var pathinfo=node.tag.split('.');
				if(pathinfo.length>=2){
					$("#filename").val(pathinfo[0]);
					$("#parentpathname").val(getfilepath(node));
					$("#oldfilename").val(pathinfo[0]);
					$("#oldfiletype").val(pathinfo[1]);
					
					popup($('#rename'));
				}

			}

		} , {
			type : "Common",
			text : '查看效果',
			// icon : '/?getstatic=/folder.png',
			action : function(node) {
				ajax("?webide=1&getstaticurl=" + node.tag + "&parent="
						+ project_name, function(data) {
					var inhtml="";

						inhtml+="<p><a href='"+data+"/"+project_name+getfilepath(node)+"/"+node.tag+"' target='_blank'>查看</a></p>";
					//}
				    $("#previewbtn").html(inhtml);
					popup($('#previewhtml'));

				});
				console.log(node);
			}

		} ]
	},
	'context1' : {
		opennode : function(node) {
			console.log(node.tag);
			openfile(getfilepath(node)+"\\"+node.tag, project_name);
			
		},
		foldermenu : [
				{
					type : "Service",
					text : '新建服务',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {
						 createmodule(node,'context1');
					},
				},
				{
					type : "Widget",
					text : '新建widget',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {
						 createmodule(node,'context1');
					},

				},
				{
					type : "Points",
					text : '新建切面',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {
						 createmodule(node,'context1');
					},

				},
				{
					type : "View",
					text : '新建视图',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {
						ajax("?webide=1&getfiles=" + node.tag + "&parent="
								+ project_name, function(data) {
							editing_file = node.tag;
							editor.setValue(data);
						});
					},

				},
				{
					type : "Validate",
					text : '新建验证器',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {
						 createmodule(node,'context1');
					},

				},
				{
					type : "Client",
					text : '新建调用端',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {
						 createmodule(node,'context1');
					},

				},
				{
					type : "Config",
					text : '新建配置项',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {
						ajax("?webide=1&getfiles=" + node.tag + "&parent="
								+ project_name, function(data) {
							editing_file = node.tag;
							editor.setValue(data);
						});
					},

				},
				{
					type : "Model",
					text : '新建实体',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {
						 createmodule(node,'context1');
					},

				},
				{
					type : "Controler",
					text : '新建控制器',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {
						popup($('#addcontroler'));
						return;
						var fname = prompt("请输入名称", "Home");
						if (fname != null && fname != "" && namecheck(fname)) {
							var url = "";
							if (is_staticfile) {
								url = "?webide=1&isstatic=1&createfiles="
										+ fname + "&parent=" + project_name
										+ "&pfolder=" + node.tag;
							} else {
								url = "?webide=1&createfiles=" + fname
										+ "&parent=" + project_name
										+ "&pfolder=" + node.tag;
							}
							ajax(url, function(data) {
								var file = node.tag + "\\" + "Controler_"
										+ fname + ".php";
								var newnode = node.createChildNode("Controler_"
										+ fname + ".php", false,
										'?webide=1&getstatic=/file.png', file,
										'context1');
								dong_tree.selectNode(newnode);
								openfile(file, project_name);
								// refleshdir(project_name);
							});
						} else {
							alert("请输入4-15位的英文字母命名");
						}

					},

				}, {
					type : "Common",
					text : '改名',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {

					},
				} ],
		filemenu : [
				{
					type : "Controler",
					text : '运行',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {
						
						var url = "?webide=1&getcontrolact=" + node.parent.tag+"\\"+node.tag
								+ "&parent=" + project_name;
						ajax(url, function(data) {
							var inhtml="";
							var obj = eval('(' + data + ')');
							for (var i = 0; i < obj.length; i++) {
								inhtml+="<p><a href='"+obj[i].url+"' target='_blank'>"+obj[i].name+"</a></p>";
							}
						    $("#actionlist").html(inhtml);
							popup($('#runaction'));
							
							// refleshdir(project_name);
						});
					},

				},
				{
					type : "Common",
					text : '重命名',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {
						var pathinfo=node.tag.split('.');
						if(pathinfo.length>=2){
							$("#filename").val(pathinfo[0]);
							$("#parentpathname").val(getfilepath(node));
							$("#oldfilename").val(pathinfo[0]);
							$("#oldfiletype").val(pathinfo[1]);
							
							popup($('#rename'));
						}
					},
				},
				{
					type : "Common",
					text : '删除',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {
						if (window.confirm("您确定删除吗?")) {
							var url = "";
							if (is_staticfile) {
								url = "?webide=1&isstatic=1&delfiles="+
									getfilepath(node)+"\\" +node.tag + "&parent=" + project_name;
							} else {
								url = "?webide=1&delfiles=" + getfilepath(node)+"\\" +node.tag 
										+ "&parent=" + project_name;
							}
							ajax(url, function(data) {
								dong_tree.removeNode(node);
								//editor.setValue("");
								// refleshdir(project_name);
							});
						}
					},
				} ]
	}
};
function createmodule(node,cont){
	  var fname=prompt("请输入文件名，不能前缀","Home"); 
		if (fname != null && fname != "" && namecheck(fname)) {
			var url = "";
			if (is_staticfile) {
				url = "?webide=1&isstatic=1&createfiles="
						+ fname + "&parent=" + project_name
						+ "&pfolder=" + node.tag+"&ftype="+node.tag;
			} else {
				url = "?webide=1&createfiles=" + fname
						+ "&parent=" + project_name
						+ "&pfolder=" + node.tag;
			}
	    	ajax(url, function(data) {
	    		var file =node.tag+"_"+ fname + ".php";
		        var newnode = node.createChildNode( file, false,
				'?webide=1&getstatic=/file.png',file,
				cont);
		        openfile( getfilepath(newnode)+"\\"+file, project_name);

			});
	    } 
}
function RunOnBeforeUnload() {
	window.onbeforeunload = function() {
		return '将丢失未保存的数据!';
	}
};
function namecheck(str) {
	var reg = /^[a-zA-Z]{4,15}$/;
	if (!reg.test(str)) {
		return false;
	}
	return true;
};
function getfilename(pathname) {
	return pathname.substring(pathname.lastIndexOf("\\") + 1, pathname.length);// 后缀名
};
function reset() {
	project_name = "";
	editing_file = "";
	file_changed = false;
	is_staticfile = false;
	editor_arr = [];

};
function rename(){
	var newfilename=$("#filename").val();
	if(newfilename!=""){
		var url = "";
		if (is_staticfile) {
			url = "?webide=1&isstatic=1&renamefile="
					+ $("#parentpathname").val()+"\\"+$("#oldfilename").val() + "&parent=" + project_name+"&newname="+$("#parentpathname").val()+"\\"+newfilename+"&filetype="+$("#oldfiletype").val();
		} else {
			url = "?webide=1&renamefile=" 
				+ $("#parentpathname").val()+"\\"+$("#oldfilename").val() + "&parent=" + project_name+"&newname="+$("#parentpathname").val()+"\\"+newfilename+"&filetype="+$("#oldfiletype").val();
		}
		ajax(url, function(data) {
			
			// 关闭打开的旧文件
			// tree.removeNode(node);
			// editor.setValue("");
			popclose('rename');
			// refleshdir(project_name);
		});
	}
};
function init_complete(){

 var myList = [
 "$this->Alert('')",
 "$this->Success('')",
 ];
	
};
function openeditor(file, filedata) {

	var ids = uniqid();
	$("#file_tab ul").prepend(
			"<li id='tab" + ids + "' class='file_selected' title='" + file + "'><span id='filechanged_"+ids+"'></span>"
					+ getfilename(file) + "<span class='tab_close' id='close"
					+ ids + "'>×</span></li>");
	$("#tab" + ids).click(function() {
		$("#editor pre").hide();
		$("#file_tab ul li").removeClass("file_selected");
		for (var i = 0; i < editor_arr.length; i++) {
			if ($(this).attr("id") == editor_arr[i].tabid) {
				// editor_arr[i].editor.hide();
				$(this).addClass("file_selected");
				if($("#file_tab ul li").eq(0).attr("id")!=$(this).attr("id")){
					  $("#file_tab ul li").eq(0).before($(this));
				}			  
				$("#" + editor_arr[i].editorid).show();
				return;
			}
		}

	});
	$("#close" + ids).click(function() {
		console.log("click");
		closefile($(this).parent('li').attr("title"));
	});
	$("#tab" + ids).hover(function() {
		if ($(this).attr("class") != "file_selected") {
			$(this).addClass("tab_hoveon");
		}
		$(this).children("span").eq(1).show();
	}, function() {
		if ($(this).attr("class") != "file_selected") {
			$(this).removeClass("tab_hoveon");
		}
		$(this).children("span").eq(1).hide();
	});
	var stuff = getstuff(file);
	var allowext = [ '.php', '.js', '.css', '.html', '.java', '.log',".bfw" ];
	var editorid = "edi" + ids;
	if (allowext.indexOf(stuff) >= 0) {

		$("#editor").append("<pre id='" + editorid + "' ></pre>");
		var editor = ace.edit(editorid);
		editor.$blockScrolling = Infinity;
		editor.setTheme("ace/theme/chaos");
		// editor.session.setMode("ace/mode/php");
		editor.setFontSize(18);
		editor.setOption("wrap", "free");
		
		editor.commands.addCommand({
			name : "showKeyboardShortcuts",
			bindKey : {
				win : "Ctrl-Alt-h",
				mac : "Command-Alt-h"
			},
			exec : function(editor) {
				ace.config.loadModule("ace/ext/keybinding_menu", function(
						module) {
					module.init(editor);
					// editor.showKeyboardShortcuts()
				})
			}
		});
		editor.commands.addCommand({
			name : "savefileShortcuts",
			bindKey : {
				win : "Ctrl-s",
				mac : "Command-s"
			},
			exec : function(editor) {
				if (editor_arr.length > 0) {
					for (var i = 0; i < editor_arr.length; i++) {
						if (editor == editor_arr[i].editor) {
							savefile(editor_arr[i]);
							break;
						}
					}
				}

			}
		});
		editor.execCommand("savefileShortcuts");
		editor.execCommand("showKeyboardShortcuts");
		editor.setOptions({
			enableBasicAutocompletion : true,
			enableSnippets : true,
			enableLiveAutocompletion : true
		});
		editor.getSession().selection.on('changeCursor', function(e) {
			console.log("chagge cuse");
		});
		editor.getSession().selection.on('changeSelection', function(e) {
			var selecttext=editor.session.getTextRange(editor.getSelectionRange());
			console.log(selecttext);
		});
		editor.on("change", function(e) {
			if (editor_arr.length > 0) {
				for (var i = 0; i < editor_arr.length; i++) {
					if (editor == editor_arr[i].editor) {
						editor_arr[i].filechanged = true;
						console.log(editor_arr[i].id+"filechanged");
						$("#filechanged_"+ editor_arr[i].id).html("*");
						break;
					}
				}
			}

		});
		if (stuff == ".php") {
			editor.session.setMode("ace/mode/php");
			var myCompleter = {
					identifierRegexps: [/[^\s]+/],
					getCompletions: function(editor, session, pos, prefix, callback) {
						console.info("myCompleter prefix:", prefix);
						
						var lastpr=prefix.substring(prefix.length-2);
						console.info("myCompleter prefix:", lastpr);
		                if(lastpr=="::"){
		            		var entrynew = prefix.substring(0,prefix.indexOf("::"));
		            		if (typeof bfw_method_list[entrynew] == "undefined") { 
								console.log(entrynew+"不存在");
							}else{
								addns(editor,bfw_method_list[entrynew].method.namespace);
								console.log("namspace::"+bfw_method_list[entrynew].method.namespace);
		    				callback(
									null,
									bfw_method_list[entrynew].method.staticname.map((entry,index) => {
										
										
										return {
											docHTML:bfw_method_list[entrynew].method.staticdoc[index],
											value:prefix+entry,
											meta: "bfw class function"
										};
									})
								);
							}
		                }else
						if(lastpr=="->"){
							var entrynew="";
							if(prefix=="$this->"){
								 entrynew = getfilename(editing_file).replace(/.php/g, "");
							}else{
								 entrynew = prefix.substring(0,prefix.indexOf("::"));
							}
							console.log(bfw_method_list);
							console.log(entrynew);
							if (typeof bfw_method_list[entrynew] == "undefined") { 
								console.log(entrynew+"不存在");
							}else{
								//console.log(bfw_method_list[entrynew].method.name);
								addns(editor,bfw_method_list[entrynew].method.namespace);
								console.log("namspace::"+bfw_method_list[entrynew].method.namespace);
								callback(
										null,
										bfw_method_list[entrynew].method.name.map((entry,index) => {
											return {
												docHTML:bfw_method_list[entrynew].method.doc[index],
												value:prefix+entry,
												meta: "bfw class function"
											};
										})
									);
							}
							
						}else{
							var lindex=prefix.indexOf("(");
							var newprefix=prefix;
							if(lindex>0){
								newprefix = prefix.substring(lindex+1);
							}
							console.log(bfw_tag_list);
							console.log("new"+newprefix);
							callback(
									null,
									bfw_tag_list.filter(entry => {
										return entry.includes(newprefix);
									}).map(entry => {
										var entrynew=entry;
										if(entry.indexOf("::")>=0){
											 entrynew = entry.substring(0,entry.indexOf("::"));
								     	}
										var doc_c="";
										if (typeof bfw_method_list[entrynew] == "undefined") { 
											console.log(entrynew+"不存在");
										}else{
											doc_c=bfw_method_list[entrynew].doc;
										}
										return {
											   docHTML:doc_c,
												value:entry,
												meta: "bfw class"
					
										};
									})
								);
						}
						
					}
				};
			    //editor.completers = [myCompleter];
				langTools.addCompleter(myCompleter);
		} else if (stuff == ".css") {
			editor.session.setMode("ace/mode/css");
		} else if (stuff == ".js") {
			editor.session.setMode("ace/mode/javascript");
		} else if (stuff == ".html") {
			editor.session.setMode("ace/mode/html");
		} else if (stuff == ".bfw") {
			editor.session.setMode("ace/mode/ini");
		} else {
			editor.session.setMode("ace/mode/php");
		}
		editor.setValue(filedata);
		//editor.focus();
	} else if (stuff == '.png' || stuff == '.jpeg' || stuff == '.jpg'
			|| stuff == '.gif') {
		$("#editor").append(
				"<pre   id='" + editorid + "'  style='text-align:center;'><img  class='img_show'  src='"
						+ filedata + "' /></pre>");
	} else if (getfilename(file) == 'welcome.bfw') {
	//	$("#editor")
		//		.append(
			//			"<pre  id='"
				//				+ editorid
				//				+ "' ><div class='wellcome_show' >全球首款支持webide的开发及运行框架，</br>支持单机，伪集群，集群，SOA部署，</br>支持code first，db first，template first开发模式，支持java php net多种流行语言，</br>云端保存，多处开发，支持企业云部署，一个账号，随时随地打开浏览器即可开发应用，丰富的在线插件及文档，</br>社群问题回答，模板涵盖电商、企业官网、在线教育、企业erp，oa等热门系统，</br>支持在线模板交易，让优秀的程序员收获自己的财富</div></pre>");
	} else {
		
	}

	editor_arr.push({
		"file" : file,
		"editor" : editor,
		"editorid" : editorid,
		"tabid" : "tab" + ids,
		"id":ids,
		"filechanged" : false,
		"type" : is_staticfile ? 2 : 1,
		"namespace":[]
	});
};
function addns(editor,ns){
	if (editor_arr.length > 0) {
		for (var i = 0; i < editor_arr.length; i++) {
			if (editor == editor_arr[i].editor) {
				if($.inArray(ns, editor_arr[i].namespace)>=0){
					
				}else{
					editor_arr[i].namespace.push(ns);
				}
				
				console.log(editor_arr[i]);
			
				break;
			}
		}
	}	
};
function closefile(f) {
	if (editor_arr.length > 0) {
		for (var i = 0; i < editor_arr.length; i++) {
			if (f == editor_arr[i].file) {
				if(editor_arr[i].filechanged){
					if (confirm("文件未保存，是否先保存再关闭项目？")) {
						savefile(editor_arr[i]);
					}
				}
				editor_arr[i].editor = null;
				$("#" + editor_arr[i].editorid).remove();
				$("#" + editor_arr[i].tabid).remove();
				editor_arr.splice(i, 1);
				break;
			}
		}

		if (editor_arr.length > 0) {
			showeditor(editor_arr[editor_arr.length - 1].file);
		}
	}
};
function getpro() {
	var pro_html = "<li  class='pro_item'  data='.'>新建项目</li>";
	ajax("?getpro=1&webide=1", function(data) {
		var obj = eval('(' + data + ')');
		for (var i = 0; i < obj.length; i++) {
			if (obj[i] != "." && obj[i] != ".."&&obj[i] != "Config.php") {
				pro_html += "	<li  class='pro_item'  data='" + obj[i] + "'>"
						+ obj[i] + " </li>";
			}
		}
		$("#latest_pro").html(pro_html);
	}, "get", "");
};
function getcloudpro() {
	var pro_html = "";
	ajax("?getcloudpro=1&webide=1", function(data) {
		var obj = eval('(' + data + ')');
		if (obj.err) {
			alert(obj.data);
		} else {
			for (var i = 0; i < obj.data.length; i++) {
				if (obj.data[i] != "." && obj.data[i] != ".."&&obj.data[i] != "Config.php") {
					pro_html += "	<li  class='pro_item'  data='" + obj.data[i]
							+ "'>" + obj.data[i] + " </li>";
				}
			}
			$("#cloud_pro").html(pro_html);
		}
	}, "get", "");
};
function openfile(f, p) {
	var stuff = getstuff(f);
	var allowext = [ '.php', '.js', '.css', '.html', '.java', '.log', ".png",
			".jpeg", ".jpg", ".gif", ".bfw" ];
	if (allowext.indexOf(stuff) >= 0) {
		var file = p  + f;
		if (showeditor(file) == 1) {
			return;
		}
		console.log("openfile:"+file);
		if (stuff == ".png" || stuff == ".jpeg" || stuff == ".jpg"
				|| stuff == ".gif") {
			openeditor(file, "?isstatic=1&webide=1&getfiles=" + f + "&parent="
					+ p);
		} else {
			if (is_staticfile) {
				ajax("?webide=1&isstatic=1&getfiles=" + f + "&parent=" + p,
						function(data) {
							openeditor(file, data);
						});
			} else {
				ajax("?webide=1&getfiles=" + f + "&parent=" + p,
						function(data) {
							openeditor(file, data);
						});
			}
		}
	} else {
		$.Bfw.toastshow("未知文件格式，无法打开");
	}
};
function showeditor(file) {
	editing_file = file;
	$("#editor pre").hide();
	$("#file_tab ul li").removeClass("file_selected");
	if (editor_arr.length > 0) {
		for (var i = 0; i < editor_arr.length; i++) {
			if (file == editor_arr[i].file) {
				$("#" + editor_arr[i].tabid).addClass("file_selected");
				$("#" + editor_arr[i].editorid).show();
				return 1;
			}
		}
	}
	return 2;
};
function uniqid(randomLength) {
	return Number(Math.random().toString().substr(3, randomLength) + Date.now())
			.toString(36);
};
function getfilepath(node){
	if(typeof node.parent.tag != "undefined"){
	          return getfilepath(node.parent)+"\\"+node.parent.tag;
	}else{
		return "";
	}
};
function getstuff(filename) {
	return filename.substring(filename.lastIndexOf("."), filename.length)
			.toLowerCase();// 后缀名
};

function treenode(obj,lnode,context){
	var objk = eval(obj);
	for (var i = 0; i < obj.length; i++) {
		var objk = eval(obj[i]);
		if (objk.type == 1) {
			treenode(eval(objk.data),lnode.createChildNode(objk.name, false,
					'?webide=1&getstatic=/folder.png', objk.name,
					context),context);
		} else {
			 lnode.createChildNode(objk.name, false,
					'?webide=1&getstatic=/file.png', objk.data,
					context);
		}
	}
};
function initdongdir(url) {
	var tree_id = "dong_tree";
	var cont = "context1";

	ajax(url, function(data) {
		dong_tree = createTree(tree_id, 'black', contex_menu);
		var obj = eval('(' + data + ')');
		for (var i = 0; i < obj.length; i++) {
			var objt = eval(obj[i]);
			if (objt.type == 1) {
				treenode(objt.data,dong_tree.createNode(objt.name, false,
						'?webide=1&getstatic=/folder.png', null, objt.name,
						cont),cont);
			} else {
				dong_tree.createNode(objt.name, false,
								'?webide=1&getstatic=/file.png', null,
								objt.data, cont);
			}
		}

		dong_tree.drawTree();
	}, "get", "");
};
function initjingdir(url) {

	var tree_id = "jing_tree";
	var cont = "context2";
	ajax(url, function(data) {
		jing_tree = createTree("jing_tree", 'black', contex_menu);
		var obj = eval('(' + data + ')');
		for (var i = 0; i < obj.length; i++) {
			var objt = eval(obj[i]);
			if (objt.type == 1) {
	
				treenode(objt.data,jing_tree.createNode(objt.name, false,
						'?webide=1&getstatic=/folder.png', null, objt.name,
						cont),cont);

			} else {
			 jing_tree
						.createNode(objt.name, false,
								'?webide=1&getstatic=/file.png', null,
								objt.data, cont);
			}
		}

		jing_tree.drawTree();
	}, "get", "");
};
function refleshdir(p) {
	project_name = p;
	document.getElementById("project_name").innerHTML = p;
	initdongdir("?webide=1&getappdir=" + p);
	initjingdir("?webide=1&isstatic=1&getappdir=" + p);
	$("#editorpannel").show();

};
function savefile(editor) {
	var url = "";
	if (editor.type == 1) {
		url = "?webide=1&savefiles=" + editor.file;
	} else {
		url = "?webide=1&isstatic=1&savefiles=" + editor.file
	}
	
	var nowpos=editor.editor.selection.getCursor();
	console.log(nowpos);
	if (editor.namespace.length > 0) {
		editor.editor.gotoLine(3);
		for (var i = 0; i < editor.namespace.length; i++) {
			if(editor.editor.getValue().indexOf(editor.namespace[i])>=0){
				
			}else{
				editor.editor.insert("use "+editor.namespace[i]+";");
			}
			
		}
		editor.namespace=[];
	}
	editor.editor.moveCursorTo(nowpos.row, nowpos.column);
	console.log(editor.namespace);
	//return;
	ajax(url, function(data) {
		if (data == "ok") {
			editor.filechanged = false;
			$("#filechanged_"+ editor.id).html("");
			$.Bfw.toastshow("保存成功");
			getbfwclassfunc(project_name);
		}
	}, "post", "data=" +encodeURIComponent(editor.editor.getValue()) );
};
function proreset() {
	project_name = "";
	editing_file = "";
	is_staticfile = false;
	if (editor_arr.length > 0) {
		for (var i = 0; i < editor_arr.length; i++) {

			editor_arr[i].editor = null;
			$("#" + editor_arr[i].editorid).remove();
			$("#" + editor_arr[i].tabid).remove();
			// editor_arr.splice(i, 1);

		}
	}
	editor_arr = [];
	$("#php_tree_tab").addClass("dir_s_tab");
	$("#php_tree_tab").removeClass("dir_tab");
	$("#static_tree_tab").removeClass("dir_s_tab");
	$("#static_tree_tab").addClass("dir_tab");
	$("#dong_tree").show();
	$("#jing_tree").hide();
};
function hideediter() {
	if (editor_arr.length > 0) {
		for (var i = 0; i < editor_arr.length; i++) {
			if (editor_arr[i].filechanged) {
				if (confirm("文件未保存，是否先保存再关闭项目？")) {
					savefile(editor_arr[i]);
				}
			}
		}
	}
	$("#editorpannel").hide();
	$(".popup_dia").hide();
	proreset();
};
function dbconfshow() {
	$("#mysqlhost").val(localStorage.getItem("host"));
	$("#mysqlport").val(localStorage.getItem("port"));
	$("#mysqlname").val(localStorage.getItem("user"));
	$("#mysqlpwd").val(localStorage.getItem("pwd"));
	popup($('#mysqlconf'));
};
function login() {
	var username = $("#loginusername").val();
	var pwd = $("#loginpwd").val();
	if (username == "" || pwd == "") {
		alert("用户名密码不能为空");
		return;
	}
	ajax('/?webide=1&login=' + username + "|" + pwd, function(str) {
		//alert(str)
		var ret = eval('(' + str + ')');
		if(ret.err){
			alert(ret.data);
		}else{
			$("#logined").show();
			$("#unlogin").hide();
			popclose("login");
		}
	});
};
function register() {
	var username = $("#regusername").val();
	var pwd = $("#regpwd").val();
	if (username == "" || pwd == "") {
		alert("用户名密码不能为空");
		return;
	}
	ajax('/?webide=1&register=' + username + "|" + pwd, function(str) {
		var ret = eval('(' + str + ')');
		if(ret.err){
			alert(ret.data);
		}else{
			$("#logined").show();
			$("#unlogin").hide();
			popclose("register");
		}
	});
};
function logout(){
	ajax('/?webide=1&logout=1', function(str) {
		if(str=="ok"){
			$("#logined").hide();
			$("#unlogin").show();
		}
		//alert(str)
	});
};
function mysqlconf() {
	if ($("#mysqlhost").val() != "") {
		localStorage.setItem("host", $("#mysqlhost").val());
	}
	if ($("#mysqlport").val() != "") {
		localStorage.setItem("port", $("#mysqlport").val());
	}
	if ($("#mysqlname").val() != "") {
		localStorage.setItem("user", $("#mysqlname").val());
	}
	if ($("#mysqlpwd").val() != "") {
		localStorage.setItem("pwd", $("#mysqlpwd").val());
	}
	alert("设置成功");
};
function openpro(p) {
	if (p == ".") {
		popup($("#newprodia"));
	} else {
		refleshdir(p);
		getbfwclassfunc(p);
		openfile("\\readme.bfw", p);
	}
};
function getbfwclassfunc(p){
	ajax('?webide=1&getclass=' + p, function(data) {
		// alert(str);
		var obj = eval('(' + data + ')');
		if(obj instanceof Object){
			console.log(obj['class']);
			bfw_tag_list=bfw_sys_tag_list.concat(obj['class']);
			//omerge(bfw_tag_list,obj['class']);
			console.log(bfw_tag_list);
			bfw_method_list=$.extend(bfw_sys_method_list,obj['method']);
			console.log(bfw_method_list);
		}
		//bfw_tag_list=bfw_tag_list.concat(obj['class']);

		//bfw_method_list=bfw_method_list.concat(obj['method']);
	});
	
};
function omerge(o,n){
	   for (var p in n){
	        if(n.hasOwnProperty(p) && (!o.hasOwnProperty(p) ))
	            o[p]=n[p];
	    }
};   
function getsysclassfunc(){
	ajax('?webide=1&getsysclass', function(data) {
		var obj = eval('(' + data + ')');
		bfw_sys_tag_list=obj['class'];
		//console.log(bfw_tag_list);
		bfw_sys_method_list=obj['method'];
	});
};
function popup(popupName) {
	var _scrollHeight = $(document).scrollTop(), // 获取当前窗口距离页面顶部高度
	_windowHeight = $(window).height(), // 获取当前窗口高度
	_windowWidth = $(window).width(), // 获取当前窗口宽度
	_popupHeight = popupName.height(), // 获取弹出层高度
	_popupWeight = popupName.width();// 获取弹出层宽度
	_posiTop = (_windowHeight - _popupHeight) / 2 + _scrollHeight;
	_posiLeft = (_windowWidth - _popupWeight) / 2;
	popupName.css({
		"left" : _posiLeft + "px",
		"top" : _posiTop + "px",
		"display" : "block"
	});// 设置position
};
function popclose(popupName) {
	$("#" + popupName).hide();
};

function creatpro() {
	if (!reg.test($("#proname").val())) {
		alert("项目名称必须为3-15个字母");
		return;
	}
	if (!reg.test(tempid)) {
		alert("请选择模板");
		return;
	}
// var uname = localStorage.getItem("user");
// var dhost = localStorage.getItem("host");
// var dport = localStorage.getItem("port");
// var dpwd = localStorage.getItem("pwd");
// if (uname == null) {
// uname = "root";
// // localStorage.setItem(cmdarr[2],cmdarr[3]);
// }
// if (dhost == null) {
// dhost = "127.0.0.1";
// // localStorage.setItem(cmdarr[2],cmdarr[3]);
// // alert("请先设置数据库连接信息");
// }
// if (dport == null) {
// dport = 3306;
// }
// if (dpwd == null) {
// dpwd = "";
// }
// var dbinfo = dhost + "|" + dport + "|" + uname + "|" + dpwd;
	if (tempid != "empty") {
		ajax('?webide=1&initapp=' + $("#proname").val() + "&tempid=" + tempid, function(str) {
			if (str == "ok") {
				getpro();
				popclose("newprodia");
			} else {
				alert(str)
			}
		}, "get", "");
	} else {
		ajax('?webide=1&initapp=' + $("#proname").val(),
				function(str) {
					if (str == "ok") {
						getpro();
						popclose("newprodia");
					} else {
						alert(str)
					}
				}, "get", "");
	}
};
function ajax(url, fnSucc, method, data) {
	if (window.XMLHttpRequest) {
		var oAjax = new XMLHttpRequest();
	} else {
		var oAjax = new ActiveXObject("Microsoft.XMLHTTP");// IE6浏览器创建ajax对象
	}
	if (method == "post") {
		oAjax.open("POST", url, true);// 把要读取的参数的传过来。
		oAjax.setRequestHeader("Content-type",
				"application/x-www-form-urlencoded");
	} else {
		oAjax.open("GET", url, true);// 把要读取的参数的传过来。
	}
	oAjax.setRequestHeader("bfwajax", "v<?=VERSION?>"); // 可以定义请求头带给后端
	if (method == "post") {
		oAjax.send(data);
	} else {
		oAjax.send();
	}

	oAjax.onreadystatechange = function() {
		if (oAjax.readyState == 4) {
			if (oAjax.status == 200) {
				fnSucc(oAjax.responseText);// 成功的时候调用这个方法
			} else {
				if (fnfiled) {
					fnField(oAjax.status);
				}
			}
		}
	};
};

$(function() {
	if (window.navigator.userAgent.indexOf("Chrome") !== -1) {

	} else {
		alert("请用基于Chrome内核的浏览器打开");
		return;
	}
	getpro();
	getsysclassfunc();
	$("#loadding").hide();
	$("#pro_nav_tab li").live("click", function() {
		$("#pro_nav_tab li").removeClass("tab_selected");
		$(this).addClass("tab_selected");
		if ($(this).index() == 0) {
			$("#latest_pro").show();
			$("#cloud_pro").hide();
		}
		if ($(this).index() == 1) {
			getcloudpro();
			$("#latest_pro").hide();
			$("#cloud_pro").show();
		}
	});
	$("#choose_temp li").live("click", function() {
		$("#choose_temp li").removeClass("tempselected");
		$(this).addClass("tempselected");
		tempid = $(this).attr("tempid");
	});
	$(".pro_item").live("click", function() {
		openpro($(this).attr("data"));
	});
	$("#php_tree_tab").click(function(e) {
		is_staticfile = false;
		$("#php_tree_tab").addClass("dir_s_tab");
		$("#php_tree_tab").removeClass("dir_tab");
		$("#static_tree_tab").removeClass("dir_s_tab");
		$("#static_tree_tab").addClass("dir_tab");
		$("#dong_tree").show();
		$("#jing_tree").hide();
	});
	$("#static_tree_tab").click(function(e) {
		is_staticfile = true;
		$("#php_tree_tab").addClass("dir_tab");
		$("#php_tree_tab").removeClass("dir_s_tab");
		$("#static_tree_tab").addClass("dir_s_tab");
		$("#static_tree_tab").removeClass("dir_tab");
		$("#dong_tree").hide();
		$("#jing_tree").show();
	});
});

// setInterval("autosave()",2000);
