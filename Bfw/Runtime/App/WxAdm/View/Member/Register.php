<?php
use Lib\Bfw;
use Lib\Util\HtmlUtil;
use Lib\Util\StringUtil;
?>
<?=Bfw::Widget("Header")?>
</header>
<div ng-app="loginApp" ng-controller="formloginCtrl">  
  <form  class="am-form"  name="regform" ng-submit="submitForm(regform.$valid)" novalidate>
    
    <fieldset>
    <legend  class="am-text-center" >注册</legend>
    手机号:<br>
    <input type="text" name="mobile" ng-pattern="/^[\d]{11,13}$/" ng-model="user.mobile" required >
    <span ng-show="regform.mobile.$error.required">必填</span>
	<span ng-show="regform.mobile.$error.pattern">手机格式不对</span>
	<br>
	<input class="am-btn am-btn-block" type="button" ng-disabled="regform.mobile.$error.required||regform.mobile.$error.pattern" ng-click="sendcode()" value="发送验证码"/>
	<br>
	 验证码:<br>
    <input type="text" name="verifycode" ng-minlength="6" ng-maxlength="6" ng-model="user.verifycode" required >
	<span ng-show="regform.verifycode.$error.minlength">密码必须为6位字符串</span>
		<span ng-show="regform.verifycode.$error.maxlength">密码必须为6位字符串</span>
		<span ng-show="regform.verifycode.$error.required">必填</span>
    <br>
    密码:<br>
    <input type="text" name="userpwd" ng-minlength="6" ng-maxlength="15" ng-model="user.userpwd" required >
	<span ng-show="regform.userpwd.$error.minlength">密码必须为6到15位字符串</span>
		<span ng-show="regform.userpwd.$error.maxlength">密码必须为6到15位字符串</span>
		<span ng-show="regform.userpwd.$error.required">必填</span>
    <br>
	用户类型<br>
	<select name="kindid"  ng-change="selectChange();"  ng-model="user.kindid" required> 
    <option ng-repeat="x in kinds" value="{{x.v}}">{{x.n}}</option>
    </select>
	<span ng-show="regform.kindid.$error.required">必填</span>
	<br>
	<div ng-show="user.kindid==2"  >
	公司名称<br>
	<input type="text" name="compname" ng-model="user.compname" >
	
    <br>
    </div>
    <input class="am-btn am-btn-danger am-btn-block"  ng-disabled="regform.$invalid" type="submit" value="提交" />
	<span>{{ notice }}</span>
	
	</fieldset>
  </form>
</div>
<script> 
var app = angular.module('loginApp', []);
app.config(function($httpProvider){
           $httpProvider.defaults.transformRequest=function(obj){
               var str=[];
               for(var p in obj){
                   str.push(encodeURIComponent(p)+"="+encodeURIComponent(obj[p]));
               }
               return str.join("&");
           };
          $httpProvider.defaults.headers.post={
              'Content-Type':'application/x-www-form-urlencoded'
          }
          
      });
app.controller('formloginCtrl', function($scope,$http,$location) {
	$scope.kinds = [
    {n : "竞买人", v : 1},
    {n : "拍卖公司", v : 2},
     ];
	// $scope.user.kindid=1;
	 $scope.selectChange = function () {  
	        console.info($scope.user.kindid);  
	    }  
    $scope.submitForm = function(isValid) {
                if (!isValid) {
                    alert('验证失败');
                }else{
					$scope.reg();
				}
            };
	$scope.sendcode=function(){
		$http.get("<?=Bfw::ACLINK("Helper","GetPhoneCode")?>mobile="+$scope.user.mobile)
  .then(function (response) {$scope.names = response.data.sites;});
	};
    $scope.reg = function() {
		$http.post($location.path(), $scope.user,{
        headers : {'bfwajax' : "1.0"}
    })
        .success(function(resjson) {
			
			if(resjson['err']){
		$scope.notice = resjson.data;
	}else{
		if (resjson['data'].substr(0, 9) == "redirect:") {
			location.href = resjson['data'].substr(9);
		}else if(resjson['data'].substr(0, 12) =="msgredirect:"){
			var spos=resjson['data'].indexOf("---");
			if(spos>12){
				alert(resjson['data'].substr(12, spos-12));
				var gotohref=resjson['data'].substr(spos+3);
				console.log(gotohref);
				if(gotohref=="back"){
					history.back();
				}else{
					location.href = gotohref;
				}
			}
		}else if(resjson['data'].substr(0, 4) =="back"){
			history.back();
		}
		else {
			$scope.notice = resjson.data;
		}
	}	
			  
        });
     };
    //$scope.reset();
});
</script>
<?=Bfw::Widget("Footer")?>
</html>