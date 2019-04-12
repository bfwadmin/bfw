<?php

function builderurl($c, $a, $d)
{
    $_urlarr = parse_url(URL);
    $_baseurl = "";
    if ($_urlarr) {
        $_baseurl = $_urlarr['scheme'] . "://";
        $_baseurl .= $_urlarr['host'];
        if (isset($_urlarr['port'])) {
            $_baseurl .= ":" . $_urlarr['port'];
        }
        $_baseurl .= $_urlarr['path'];
    }
    
    $routype = isset($_GET['route']) ? $_GET['route'] : 0;
    if ($routype == 0) {
        return $_baseurl . "?" . DOMIAN_NAME . "=" . $d . "&" . CONTROL_NAME . "=" . $c . "&" . ACTION_NAME . "=" . $a;
    } else 
        if ($routype == 2) {
            return $_baseurl . $c . "/" . $a;
        } else {
            return "";
        }
}
?>
<html>
<head lang="zh-CN">
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?=DOMIAN_VALUE?>接口文档</title>
<meta name="keywords" content="php开发框架,SOA框架,java开发框架" />
<meta name="description"
	content="全球首款支持webide的开发及运行框架，支持单机，伪集群，集群，SOA部署，支持code first，db first，template first开发模式，支持java php net多种流行语言，云端保存，多处开发，支持企业云部署，一个账号，随时随地打开浏览器即可开发应用，丰富的在线插件及文档，社群问题回答，模板涵盖电商、企业官网、在线教育、企业erp，oa等热门系统，支持在线模板交易，让优秀的程序员收获自己的财富" />
<link rel="shortcut icon" href="/favicon.ico" />
<script>
function copyUrl(did)
{
    var Url2=document.getElementById(did).innerText;
    var oInput = document.createElement('input');
    oInput.value = Url2;
    document.body.appendChild(oInput);
    oInput.select(); // 选择对象
    document.execCommand("Copy"); // 执行浏览器复制命令
    oInput.className = 'oInput';
    oInput.style.display='none';
    alert('复制成功');
}
function closepanel(){
	document.getElementById("apidebugpanel").style.display="none";
	  document.getElementById("responsepannel").value="";
	  document.getElementById("responsedebugdata").value="";
}
function apidebug(urlid,event){
	var url=document.getElementById(urlid).innerText;
	document.getElementById("apiurl").value=url;
	var ypos=event.pageY;
	document.getElementById("responsepannel").value="";
	document.getElementById("apidebugpanel").style.top=ypos+"px";
	document.getElementById("apidebugpanel").style.display="block";
	
}
function changeuincode(){
	  document.getElementById("responsepannel").value=unescape( document.getElementById("responsepannel").value.replace(/\\u/g, "%u"));
}
function sendapiurl(){
	var method=document.getElementById("methodselect").value;
	var url=document.getElementById("apiurl").value;
	var postpara=document.getElementById("postpara").value;;
	if(method==""||url==""){
         return;
	}
	ajax(url, function(str){
        var index=str.indexOf('~_~_~_~_~_~');
        if(index>=0){
      	  document.getElementById("responsepannel").value=unescape(str.substring(0,index).replace(/\\u/g, "%u"));
       //	 document.getElementById("responsepannel").value=str.substring(index+11,str.length);
      	  var strs=str.substring(index+11,str.length);
          var obj = eval('(' + strs+ ')');
          var output="";
          output+="调试信息:spend time:"+obj.spendtime+"S,mem cost:"+obj.totalmem/1024/1024+"M"+"\n";
        	for (var i = 0; i < obj.debug_info.length; i++) {
      		  output+=obj.debug_info[0]+"\n";
        	}
          document.getElementById("responsedebugdata").value=output;
      	  //document.getElementById("debug_info_area").innerHTML=str.substring(index,str.length-index);
        }else{
      	  document.getElementById("responsepannel").value=str;
        }
	}, method, postpara) ;
}
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
		oAjax.setRequestHeader("clientinfo",
		"h5_mobile_1.0");
	} else {
		oAjax.open("GET", url, true);// 把要读取的参数的传过来。
	}
	oAjax.setRequestHeader("bfwajax", "v<?=VERSION?>"); // 可以定义请求头带给后端
	oAjax.setRequestHeader("jsonpretty", "1"); // 可以定义请求头带给后端
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
</script>
<style>
body {
	background: #1c1c1c;
}

p {
	margin: 0;
	padding: 0;
}

#apidebugpanel {
	box-shadow: 0 0 10px #feff93;
	border-radius: 5px;
	height: 500px;
	position: absolute;
	display: none;
	width: 60%;
	background: #fff5b3;
	padding: 9px;
	left: 50%;
	top: 50%;
	margin-left: -30%;
	z-index:222;
}

#apidebugpanel select {
	width: 20%;
	padding: 10px;
	height: 40px;
}

#apidebugpanel input {
	padding: 10px;
	height: 40px;
}

#closebtn {
	cursor: pointer;
	float: right;
	padding: 5px;
}

#apiurl {
	width: 60%;
}

#sendbtn {
	width: 20%;
	height: 30px;
}

#postpara {
	width: 100%;
	height: 40px;
}
#summary{
	width:85%;
	margin-left:15%;
}
#mulu {
	background: #000000;
	color: #8b8b8b;
	padding: 5px;
	position: fixed;
	top: 0;
	left: 0;
	width: 15%;
	height:100vh;
	overflow:scroll;
	font-size:16px;
}

#mulu ul {
	padding: 0;
	margin: 0;
	list-style: none;
	clear: both;
}

#mulu ul li {
	line-height: 30px;
	margin: 0 10px;
	display: block;
	overflow: hidden;
	background: black;
	padding: 10px;
}


#mulu ul li a .selected{
	color:blue;
	
}
#mulu ul li h4 {
	padding:0px;
	margin:0;
	font-weight:bold;
	font-size:18px;
}
#mulu ul li h4 a {
	color:#2596cc;
}
#mulu ul li a {
	text-decoration: none;
	color: #d1d1d1;
}
#mulu::-webkit-scrollbar {
        width: 10px;  
        height: 1px;
    }
#mulu::-webkit-scrollbar-thumb {
        border-radius: 10px;
         -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
        background: #535353;
    }
#mulu::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
        border-radius: 10px;
        background: #EDEDED;
    }
</style>


<body>
	<div id="apidebugpanel">
		<div
			style="padding: 3px; height: 40px; clear: both; font-weight: bold;">
			<span>接口调试</span> <span id="closebtn" onclick="closepanel();">×</span>
		</div>
		<select id="methodselect">
			<option value="post">POST</option>
			<option value="get">GET</option>
		</select><input placeholder="http://api.example.com/get/id/1"
			type="text" id="apiurl" /><input onclick="sendapiurl();" id="sendbtn"
			type="button" value="发送" />

		<div>
			<input type="text" id="postpara"
				placeholder="post参数类似于username=wangbo&passwd=111111这种形式" />
		</div>
		<h4>返回结果</h4>
		<textarea style="width: 100%; height: 200px;" id="responsepannel"></textarea>
		<textarea style="width: 100%; height: 100px;" id="responsedebugdata"></textarea>
	</div>

	<div id="mulu">
		<ul>
		<?php foreach($con_act_array as $key=>$val){ ?>
		<li><h4><a href="#an_<?=$key?>"><?=$val[0]['doccomment'][0]?></a></h4>
	   
		<?php   foreach ($val as $item) {?>
		<p>
					<a class="selected"  href="#ac_<?=$key?>_<?=$item[0]?>"><?=$item[1]?></a>
				</p>
		
		<?php }?>
		
		</li>
		<?php }?>
		</ul>
	</div>
	<div id="summary">
		<h1 style="color: #2596cc; padding-left: 20px;"><?=DOMIAN_VALUE?>接口文档</h1>
		<div style="padding: 5px 24px; color: #8b8b8b;"><?=$readmedata?></div>
				<?php foreach($con_act_array as $key=>$val){ ?>
				   <div
			style='color: #2596cc; background: #0c0c0c; padding: 10px; margin: 18px;'>
			<a name="an_<?=$key?>" />


			<p style="font-weight: bold;"><?=str_replace("*","<br/>",$val[0]['doccomment'][0])?></p>
			<p><?=str_replace("*","<br/>",$val[0]['doccomment'][1])?></p>
					<?php
        $i = 0;
        foreach ($val as $item) {
            if (! isset($item['doccomment'])) {
                ?>
            <a name="ac_<?=$key?>_<?=$item[0]?>" />

			<div style="margin: 20px 0px; background: #404040; padding: 20px;">
				<div style='color: #d1d1d1; line-height: 25px;'>
	<?=$item[3]?>
	</div>
				<p style='color: #8b8b8b;'>
					接口地址:<span id="url_<?=$key?><?=$i?>"><?=builderurl($key,$item[0],DOMIAN_VALUE)?></span><input
						type="button" onclick="apidebug('url_<?=$key?><?=$i?>',event);"
						value="调试" /> <input type="button"
						onClick="copyUrl('url_<?=$key?><?=$i?>')" value="复制" />
				</p>
			</div>
					<?php
                $i ++;
            }
        }
        
        ?>
		</div>
				<?php } ?>
		
				 <div style="margin: 0; text-align: center; color: white;">
			Power by BFW<sup><?=VERSION?></sup>[SOA framework]
		</div>
	</div>
</body>
</html>