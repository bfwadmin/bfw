var contex_menu = null;
var dong_tree = null;
var jing_tree = null;
var project_name = "";
var editing_file = "";
var editing_editor = {};
var file_changed = false;
var is_staticfile = false;
var editor_arr = [];
var reg = /^[a-zA-Z]{3,15}$/;
var tempid = "";
var bfw_sys_tag_list= [];
var bfw_sys_method_list=[];
var bfw_tag_list= [];
var bfw_method_list=[];
var loadingstartshow=false;
var loadingendshow=false;
var syswait=null;
var debug_timeintval=null;
var debug_data=null;
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
						// editor.setValue("");
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
					// }
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

					text : '查看版本',
					// icon : '/?getstatic=/folder.png',
					action : function(node) {
						getlog(project_name);
						return;
						var url = "";
						if (is_staticfile) {
							url = "?webide=1&isstatic=1&getcommitlog="+
								getfilepath(node)+"\\" +node.tag + "&parent=" + project_name+"&targetappname="+project_name;
						} else {
							url = "?webide=1&getcommitlog=" + getfilepath(node)+"\\" +node.tag
									+ "&parent=" + project_name+"&targetappname="+project_name;
						}
						ajax(url, function(data) {
							getlog(p);
						});
					}
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
								// editor.setValue("");
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
function showwiki(){
	ajax("?webide=1&getwikiclass=1", function(data) {
		var obj = eval('(' + data + ')');
		var pro_html="";
		var classname="";
		var classarr=[];
		for (var i = 0; i < obj.length; i++) {
			if(classname!=obj[i].classname){
				classarr.push(obj[i].classname);
			}
			classname=obj[i].classname;
		}
		console.log(classarr);
		for (var i = 0; i < classarr.length; i++) {
			pro_html += "<li class='parent-menu'>"+classarr[i]+"<span class='add_btn' onclick=\"showaddwikipage('"+classarr[i]+"');\">+</span></li>";
			for (var j = 0; j < obj.length; j++) {
				if(classarr[i]==obj[j].classname){
					pro_html += "<li id='sel_li"+obj[j].id+"' onclick='openwikipage("+obj[j].id+")'>"+obj[j].title+"<span class='del_btn' onclick=\"deldwikipage("+obj[j].id+")\">-</span></li>";
				}
			}

		}
		$("#wikimenu").html(pro_html);
		console.log(obj);
		$('#wikipannel').show();

	});

};
function showaddwikipage(p){
	$("#wikiclassname").val(p);
	popup($('#addwikipage'));
};
function openwikipage(id){
	$('#wikipannel li').removeClass('selected_li');

	$("#sel_li"+id).addClass('selected_li');
	ajax("?webide=1&getwikipage="+id, function(data) {
		var obj = eval('(' + data + ')');
		$("#wikibodydetail").html(obj[0]['cont']);
	});


};
function addwikipage(){
	var wikiname=$("#wikipagename").val();
	var wikiclass=$("#wikiclassname").val();
	var wikibody=$("#wikibodytext").val();
	if(wikiname==""||wikiclass==""||wikibody==""){
		alert("请填写完整后提交");
		return;
	}
	ajax("?webide=1&addwikipage=1", function(data) {
		var obj = eval('(' + data + ')');
		if(obj.err){
			alert(obj.data);
		}else{
			popclose('addwikipage');
			showwiki();
		}

	}, "post", "title=" +encodeURIComponent(wikiname)+"&classname="+encodeURIComponent(wikiclass)+"&cont="+encodeURIComponent(wikibody));
};
function deldwikipage(id){
	if(confirm('确定删除？')){
	ajax("?webide=1&addwikipage=1", function(data) {
		alert(addwikipage);
		popclose('addwikipage');

	});
	}
};
function hidewiki(){
	$("#wikipannel").hide();
};
function hidejob(){
	$("#jobpannel").hide();
};
function addjob(){
	popup($("#addjobpage"));
};

function notify(msg) {
    showMsgNotification('You have new information',msg);

};
function showMsgNotification(title, msg, icon) {
        var options = {
            body: msg,
            icon: icon||"image_url"
        };
        var Notification = window.Notification || window.mozNotification || window.webkitNotification;
        if (Notification && Notification.permission === "granted") {
            var instance = new Notification(title, options);
            instance.onclick = function() {
                // Something to do
            };
            instance.onerror = function() {
                // Something to do
            };
            instance.onshow = function() {
                // Something to do
// setTimeout(instance.close, 3000);
                setTimeout(function () {
                    instance.close();
                },3000)
                console.log(instance.body)
            };
            instance.onclose = function() {
                // Something to do
            };
            console.log(instance)
        } else if (Notification && Notification.permission !== "denied") {
            Notification.requestPermission(function(status) {
                if (Notification.permission !== status) {
                    Notification.permission = status;
                }
                // If the user said okay
                if (status === "granted") {
                    var instance = new Notification(title, options);
                    instance.onclick = function() {
                        // Something to do
                    };
                    instance.onerror = function() {
                        // Something to do
                    };
                    instance.onshow = function() {
                        // Something to do
                        setTimeout(instance.close, 3000);
                    };
                    instance.onclose = function() {
                        // Something to do
                    };
                } else {
                    return false
                }
            });
        } else {
            return false;
        }
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
function setbreakpointer(file,line,isclear){
	var url="";
	if(isclear==1){
		url = "?webide=1&clearbreak=1&filename="+ file+"&parent=" + project_name+"&line="+line;
	}else{
		url = "?webide=1&addbreak=1&filename="+ file+"&parent=" + project_name+"&line="+line;
	}
	ajax(url, function(data) {
		console.log(data);
	});
};
function opendebug(){
	ajax("?webide=1&contdebug=go", function(data) {
		if(data=="ok"){
			$("#debug_control_pan").fadeIn(300);
			debug_timeintval=setInterval(getdebuginfo,1000);
		}
	});
};
function closedebug(){
	ajax("?webide=1&contdebug=exit", function(data) {
		if(data=="ok"){
			$("#debug_control_pan").fadeOut(300);
			clearInterval(debug_timeintval);
		}
	});
};
function getdebuginfo(){
	var url = "?webide=1&getdebuginfo=1&filename="+ editing_file+"&parent=" + project_name;
	ajax(url, function(data) {
		if(project_name!=""){
			if(debug_data!=data){
				debug_data=data;
				 try{
				    var json_data = eval('(' + data + ')');
				 }catch (error) {
				    return alert("Cannot eval JSON: " + error);
				 }
				 $('#json-renderer').jsonViewer(json_data.var, {collapsed:false,withQuotes:true});
				// stvar result = json_data.file.replace('\\'+project_name, '');
				var editor = openfile(json_data.file.replace('\\'+project_name, ''),project_name);

				setTimeout(function () {
					if(editor!=null){
						editor.renderer.scrollCursorIntoView({row: json_data.line, column: 1}, 0.5);
						editor.getSession().setBreakpoint(json_data.line-1,"ace_coderunstatus");
					}
	            },500);



			}
		}

	});
}
function init_complete(){

 var myList = [
 "$this->Alert('')",
 "$this->Success('')",
 ];

};

function openeditor(file, filedata,hash,breakline) {

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
			// var rows = editor.$getSelectedRows();
			// editor.getSession().setBreakpoint(rows);
			// console.log(editor.getSession().getBreakpoints());
			// console.log(rows);
			// console.log("changeCursor");
		});
		editor.getSession().selection.on('changeSelection', function(e) {
			console.log("changeSelection");
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
			editor.on("guttermousedown", function(e){
			    var target = e.domEvent.target;
			    if (target.className.indexOf("ace_gutter-cell") == -1)
			        return;
			    if (!editor.isFocused())
			        return;
			    if (e.clientX > 25 + target.getBoundingClientRect().left)
			        return;
			    var row = e.getDocumentPosition().row;
			    console.log(row);
			    var _linedata=e.editor.session.getLine(row);
			    console.log(_linedata);
			    var _allbreaks=e.editor.session.getBreakpoints();
			    console.log(_allbreaks);

			    if(_linedata!="{"&&_linedata!="}"){
			    	if(!_allbreaks[row]){
			    		// addbreak
			    		setbreakpointer(file,row,0);
			    		e.editor.session.setBreakpoint(row);
			    	}else{
			    		setbreakpointer(file,row,1);
			    		e.editor.session.clearBreakpoint(row);
			    		// e.editor.session.documentToScreenRow(row,1);
			    	}

			    }
			    // editor.session.setBreakpoint(pos.row,"ace_coderunstatus");
			    // e.editor.session.clearBreakpoint(row);
			    e.stop();

			});
			for (var i = 0; i < breakline.length; i++) {
				editor.session.setBreakpoint(breakline[i]);
			}
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
								// 锁定变量
								if(prefix.indexOf("$")>=0){
									// prefix+"="
									// editor.getValue().
								}else{
									entrynew = prefix.substring(0,prefix.indexOf("::"));
								}

							}
							console.log(bfw_method_list);
							console.log(entrynew);
							if (typeof bfw_method_list[entrynew] == "undefined") {
								console.log(entrynew+"不存在");
							}else{
								// console.log(bfw_method_list[entrynew].method.name);
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
			    // editor.completers = [myCompleter];
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
		editor.renderer.scrollCursorIntoView({row: 141, column: 1}, 0.5);
		//editor.moveCursorTo(143,1);
		//editor.blur();
	} else if (stuff == '.png' || stuff == '.jpeg' || stuff == '.jpg'
			|| stuff == '.gif') {
		$("#editor").append(
				"<pre   id='" + editorid + "'  style='text-align:center;'><img  class='img_show'  src='"
						+ filedata + "' /></pre>");
	} else if (getfilename(file) == 'welcome.bfw') {
	// $("#editor")
		// .append(
			// "<pre id='"
				// + editorid
				// + "' ><div class='wellcome_show'
				// >全球首款支持webide的开发及运行框架，</br>支持单机，伪集群，集群，SOA部署，</br>支持code
				// first，db first，template first开发模式，支持java php
				// net多种流行语言，</br>云端保存，多处开发，支持企业云部署，一个账号，随时随地打开浏览器即可开发应用，丰富的在线插件及文档，</br>社群问题回答，模板涵盖电商、企业官网、在线教育、企业erp，oa等热门系统，</br>支持在线模板交易，让优秀的程序员收获自己的财富</div></pre>");
	} else {

	}

	editor_arr.push({
		"filehash":hash,
		"filetype":stuff,
		"file" : file,
		"editor" : editor,
		"editorid" : editorid,
		"tabid" : "tab" + ids,
		"id":ids,
		"filechanged" : false,
		"type" : is_staticfile ? 2 : 1,
		"namespace":[],
		"confict":{"resolvemethod":"","serverdata":"","serverhash":""}
	});
	return editor;
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
function showapppower(p){
	$("#apppower_appname").val(p);
	ajax("?getapppower="+p+"&webide=1&targetappname="+p, function(data) {
		$("#apppower_text").val(data);
		popup($("#apppower"));
	});
};
function setapppower(){
	var powers=$("#apppower_text").val();
	var p=$("#apppower_appname").val();
	// $("#apppower_appname").val(p);
	ajax("?setapppower="+p+"&webide=1&powers="+powers+"&targetappname="+p, function(data) {
		popclose("apppower");
	});
};
function getpro() {
	var pro_html = "<li  class='pro_item'  data='.'><span data='.' class='pron_item_name'>新建项目</span></li>";
	ajax("?getpro=1&webide=1", function(data) {
		var obj = eval('(' + data + ')');
		for (var i = 0; i < obj.length; i++) {
		    console.log(obj[i]);
			if (obj[i].type=="self") {
				pro_html += "	<li  class='pro_item'  ><span data='" + obj[i].name + "' class='pron_item_name'>"
						+obj[i].name+ "</span> " +
								"" +
								"<p><a onclick='getlog(\""+obj[i].name+"\")'>日志</a><a onclick='showapppower(\""+obj[i].name+"\");'>权限</a><a onclick='versoncontrol(\""+obj[i].name+"\");'>版本</a><a onclick='delpro(\""+obj[i].name+"\");'>删除</a><a>部署</a></p></li>";
			}
			if (obj[i].type=="team") {
				pro_html += "	<li  class='pro_item team_item'  ><span>"
						+obj[i].name+ "</span> " +
								"" +
								"<p><a href='/Cloud/"+obj[i].url+"/?webide=1&targetappname="+ obj[i].name+"' target='_blank'>进入</a></p></li>";
			}
		}
		$("#latest_pro").html(pro_html);
	}, "get", "");
};
function getlog(p){
	var pro_html = "";
	ajax("?getcommitlog="+filename+"&webide=1&targetappname="+p, function(data) {
		var obj = eval('(' + data + ')');
		if(obj.length>0){
			for (var i = 0; i < obj.length; i++) {
				pro_html += "<li><p>" + obj[i].atime + "</p><p>"+obj[i].file+":"+ obj[i].memo+" </p> </li>";

			}
		}

		$("#commitloglist").html(pro_html);
		popup($("#commitlog"));

	});
}
function versoncontrol(p){
	var pro_html = "";
	$("#appversion_appname").val(p);
	ajax("?getappversion="+p+"&webide=1&targetappname="+p, function(data) {
		var obj = eval('(' + data + ')');
		if(obj.length>0){
			for (var i = 0; i < obj.length; i++) {
				pro_html += "<li><p>" + obj[i].atime + "</p><p>"+ obj[i].memo+" <input onclick='rollback("+obj[i].id+");' type='button' value='回滚到此版本' /></p> </li>";

			}
		}


		$("#appversonlist").html(pro_html);
		popup($("#appversoncontrol"));

	});

};
function addversion(){
	ajax("?addappversion="+$("#appversion_appname").val()+"&webide=1&memo=123123&targetappname="+$("#appversion_appname").val(), function(data) {
		if(data=="ok"){
			alert("提交成功");
			popclose("appversoncontrol");
		}

	});
};
function rollback(v){
	if(confirm("确定回滚到此版本")){
		ajax("?setappversion="+$("#appversion_appname").val()+"&webide=1&v="+v+"&targetappname="+$("#appversion_appname").val(), function(data) {
			if(data=="ok"){
				alert("提交成功");
				popclose("appversoncontrol");
			}

		});
	}
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
	//console.log(f);
	var stuff = getstuff(f);
	var allowext = [ '.php', '.js', '.css', '.html', '.java', '.log', ".png",
			".jpeg", ".jpg", ".gif", ".bfw" ];
	if (allowext.indexOf(stuff) >= 0) {
		var file = p  + f;
		var editor=showeditor(file);
		if (editor !=null) {
			return editor;
		}
		console.log("openfile:"+file);
		if (stuff == ".png" || stuff == ".jpeg" || stuff == ".jpg"
				|| stuff == ".gif") {
			return openeditor(file, "?isstatic=1&webide=1&getfiles=" + f + "&parent="
					+ p+"&targetappname="+p,"","");
		} else {
			if (is_staticfile) {
				ajax("?webide=1&isstatic=1&getfiles=" + f + "&parent=" + p,
						function(data) {
							var obj = eval('(' + data + ')');
							if(obj.err){
								alert(obj.data);
								return;
							}
							return openeditor(file, obj.data,obj.filehash,obj.breakline);
						});
			} else {
				ajax("?webide=1&getfiles=" + f + "&parent=" + p,
						function(data) {
							var obj = eval('(' + data + ')');
							if(obj.err){
								alert(obj.data);
								return;
							}
							return openeditor(file, obj.data,obj.filehash,obj.breakline);
						});
			}
		}
	} else {
		$.Bfw.toastshow("未知文件格式，无法打开");
	}
	return null;
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
				return editor_arr[i].editor;
			}
		}
	}
	return null;
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
function resolvebyserverv(){
	var filepath=$("#confictfilepath").val();
	if (editor_arr.length > 0) {
		for (var i = 0; i < editor_arr.length; i++) {
			if (editor_arr[i].file==filepath) {
				editor_arr[i].confict.resolvemethod="server";
				// savefile(editor_arr[i]);
				break;
			}
		}
	}

};
function resolvebymev(){
	var filepath=$("#confictfilepath").val();
	if (editor_arr.length > 0) {
		for (var i = 0; i < editor_arr.length; i++) {
			if (editor_arr[i].file==filepath) {
				editor_arr[i].confict.resolvemethod="me";

				// savefile(editor_arr[i]);

				break;
			}
		}
	}
};
function checkmodify(editor){
	console.log("check modify");
	console.log(editor_arr);
	console.log(editor);
	var url = "";
	if (editor.type == 1) {
		url = "?webide=1&checkfilesmod=" + editor.file+"&filehash="+editor.filehash;
	} else {
		url = "?webide=1&isstatic=1&checkfilesmod=" + editor.file+"&filehash="+editor.filehash;
	}
	ajax(url, function(data) {
		var obj = eval('(' + data + ')');
		if(obj.err){
			alert(obj.data);
		}else{
			if(obj.data){
				$("#confictfilepath").val(editor.file);
				$("#confictnotice").html(obj.diff);
				editor.confict.serverdata=obj.serverdata;
				editor.confict.serverhash=obj.serverhash;
				popup($("#confictshow"));
			}else{
				editor.confict.resolvemethod="me";
			}
		}
	}, "post", "data=" +encodeURIComponent(editor.editor.getValue()) );

};

function savefilereal(editor){
	if(editor.confict.resolvemethod=="server"){
		editor.filechanged = false;
		editor.editor.setValue(editor.confict.serverdata);
		$("#filechanged_"+ editor.id).html("");
		editor.confict.resolvemethod="";
		editor.filehash=editor.confict.serverhash;
		// $.Bfw.toastshow("保存成功");
		getbfwclassfunc(project_name);
		popclose("confictshow");
	}
	if(editor.confict.resolvemethod=="me"){

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
		// return;
		ajax(url, function(data) {
			var obj = eval('(' + data + ')');
			if(obj.err){
				alert(obj.data);
			}else{
				editor.filechanged = false;
				$("#filechanged_"+ editor.id).html("");
				editor.confict.resolvemethod="";
				editor.filehash=obj.data;
				$.Bfw.toastshow("保存成功");
				popclose("confictshow");
				getbfwclassfunc(project_name);
			}
		}, "post", "data=" +encodeURIComponent(editor.editor.getValue()) );

	}

};
function closeconfictshow(){
	popclose('confictshow');
	clearInterval(syswait);
};
function savefile(editor) {

	checkmodify(editor);
	editing_editor=editor;
	  syswait = setInterval(function(){
		 // console.log(editing_editor);
		  console.log("check");
	        if(editing_editor.confict.resolvemethod!=""){

	        	  clearInterval(syswait);
	        	  savefilereal(editing_editor);
	        }
	    },50);

	console.log("check after");
	console.log(editor.confict);

};
function throttle(method,delay){
    var timer=null;
    return function(){
        var context=this, args=arguments;
        clearTimeout(timer);
        timer=setTimeout(function(){
            method.apply(context,args);
        },delay);
    }
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
	$("#project-menu").hide();
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
		// alert(str)
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
		// alert(str)
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
		$("#project-menu").show();
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
			// omerge(bfw_tag_list,obj['class']);
			console.log(bfw_tag_list);
			bfw_method_list=$.extend(bfw_sys_method_list,obj['method']);
			console.log(bfw_method_list);
		}
		// bfw_tag_list=bfw_tag_list.concat(obj['class']);

		// bfw_method_list=bfw_method_list.concat(obj['method']);
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
		// console.log(bfw_tag_list);
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
	}).fadeIn(300);// 设置position
};
function popclose(popupName) {
	$("#" + popupName).fadeOut(300);
};
function delpro(p){
	if(confirm("确定删除？")){
		ajax('?webide=1&delapp=' + p, function(data) {
			// var obj = eval('(' + data + ')');
			getpro();
		});
	}
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
function addjob(){
	var jobname=$("#jobname").val();
	var jobcont=$("#jobcont").val();
	var starttime="2012-12-12 16:20:23";
	var endtime="2012-12-12 16:20:23";
	if(jobname==""||jobcont==""){
		alert("请填写完整后提交");
		return;
	}
	ajax("?webide=1&addjob=1", function(data) {
		var obj = eval('(' + data + ')');
		if(obj.err){
			alert(obj.data);
		}else{
			popclose('addjobpage');
			showjoblist();
		}

	}, "post", "title=" +encodeURIComponent(jobname)+"&starttime="+encodeURIComponent(starttime)+"&endtime="+encodeURIComponent(endtime)+"&cont="+encodeURIComponent(jobcont));
};
function showjoblist(){
	ajax("?webide=1&listjob=1", function(data) {
		var obj = eval('(' + data + ')');
		var pro_html="";
		for (var j = 0; j < obj.length; j++) {
			pro_html += "<li id='job_li"+obj[j].id+"' onclick='openjobdetail("+obj[j].id+")'><a><h2>"+obj[j].username+":</h2><p>"+obj[j].title+"</p></a></li>";
		}
		$("#joblistview").html(pro_html);
	});

};

function ajax(url, fnSucc, method, data) {
	console.log(url);
	if(url.indexOf("targetappname")>=0){

	}else{
		url=url+"&targetappname="+project_name;
	}
	throttle(function(){loadingstartshow=true;$("#mask").fadeIn(100);},1000);


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
	oAjax.setRequestHeader("bfwajax", "v1"); // 可以定义请求头带给后端
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

		// throttle(function(){$("#mask").fadeOut(30);},30);
	// if(loadingstartshow==true){
			$("#mask").fadeOut(100);
		// }

		// loadingshow=false;
		// setTimeout(function(){},3000);

	};
};
function showjoblistview(){
	showjoblist();
	$('#jobpannel').show();
};
$(function() {
	if (window.navigator.userAgent.indexOf("Chrome") !== -1) {

	} else {
		alert("请用基于Chrome内核的浏览器打开");
		return;
	}
	// var ue = UM.getEditor('wikibodytext');
	getpro();
	getsysclassfunc();

	$("#loadding").hide();
	// notify("ddddd");
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
		if($(this).index()!=0){
			$("#choose_temp li").removeClass("tempselected");
			$(this).addClass("tempselected");
			tempid = $(this).attr("tempid");
		}

	});
	$("#debug-continue-btn").live("click", function() {
		ajax("?webide=1&contdebug=go", function(data) {

		});
	});
	$("#debug-stop-btn").live("click", function() {
		ajax("?webide=1&contdebug=1", function(data) {

		});
	});

	$("#choose_d_temp li").live("click", function() {
		$("#choose_d_temp li").removeClass("tempselected");
		$(this).addClass("tempselected");
		tempid = $(this).attr("tempid");
	});
	$(".pron_item_name").live("click", function() {
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
