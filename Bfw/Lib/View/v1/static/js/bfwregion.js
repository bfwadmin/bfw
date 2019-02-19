jQuery.Bfw.region = function(cont,act,dom,provobj,cityobj,disobj,provval,cityval,disval) {

		$.Bfw.callact(cont,act,dom,{},'post',function(data){
			if(data['err']){
				alert("地址数据出错");
				return false;
			}
			var regiondata=data['data'];
			$(provobj).bind("change", function () {
               var selectval=$(this).val();
                var selhtml="<option value=''>请选择城市</option>";
				var pid="";
				$.each(regiondata, function (i) {
					if(regiondata[i]['code']==selectval){
						pid=regiondata[i]['id'];
					}
				});
      	        $.each(regiondata, function (i) {
                 if(regiondata[i]['pid']==pid){
            	   selhtml+="<option value='" + regiondata[i]['code'] + "'>" + regiondata[i]['name'] + "</option>";
                 }
              });
        	$(cityobj).html(selhtml);
        	selhtml=null;
			$(cityobj).val(cityval);
            });
            $(cityobj).bind("change", function () {
              var selectval=$(this).val();
              var selhtml="<option value=''>请选择地区</option>";
			  var pid="";
				$.each(regiondata, function (i) {
					if(regiondata[i]['code']==selectval){
						pid=regiondata[i]['id'];
					}
				});
      	      $.each(regiondata, function (i) {
                if(regiondata[i]['pid']==pid){
            	  selhtml+="<option value='" + regiondata[i]['code'] + "'>" + regiondata[i]['name'] + "</option>";
                   }

            }); 
        	$(disobj).html(selhtml);
        	selhtml=null;
			$(disobj).val(disval);
            });


           var selhtml="<option value=''>请选择省份</option>";
           $.each(regiondata, function (i) {
            if(regiondata[i]['pid']==0){
				selhtml+="<option value='" + regiondata[i]['code'] + "'>" + regiondata[i]['name'] + "</option>";
            }
           });
	       $(provobj).html(selhtml);
	       selhtml=null;
	       $(provobj).val(provval);
		   $(provobj).change();
		   $(cityobj).change();
		});
	
};
