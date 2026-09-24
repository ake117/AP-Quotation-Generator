
var isClosed = false;
var trigger,overlay;

$(document).ready(function() {
    'use strict';
    
    /*-----------------------------------------------------------------------------------*/
    /*	MENU
    /*-----------------------------------------------------------------------------------*/
    trigger = $('.hamburger');
    overlay = $('.overlay');
   
    trigger.click(function () {
      hamburger_cross();      
    });            
    
    function loadScript(src, callback)
    {
        var s,
            r,
            t;
        r = false;
        s = document.createElement('script');
        s.type = 'text/javascript';
        s.src = src;
        s.onload = s.onreadystatechange = function() {
          //console.log( this.readyState ); //uncomment this line to see which ready states are called.
          if ( !r && (!this.readyState || this.readyState == 'complete') )
          {
            r = true;
            callback();
          }
        };
        t = document.getElementsByTagName('script')[0];
        t.parentNode.insertBefore(s, t);
    }        

});   

    function hamburger_cross() {
        
        $('#wrapper').toggleClass('toggled');
        
        if (isClosed == true) {          
          overlay.hide();
          trigger.removeClass('is-open');
          trigger.addClass('is-closed');
          isClosed = false;
        } else {   
          overlay.show();
          trigger.removeClass('is-closed');
          trigger.addClass('is-open');
          isClosed = true;
        }
    }
    function close_menu(){
       hamburger_cross(); 
    };

/*-----------------------------------------------------------------------------------*/
/*	LOADING
/*-----------------------------------------------------------------------------------*/
$(window).load(function() {
    $('#status').fadeOut();
    $('.modal_loading').delay(350).fadeOut('slow');
    $('.modal_loading .textload').delay(0).fadeOut('slow');
    $('body').delay(350).css({
        // 'overflow': 'visible'
    });
});
/*-----------------------------------------------------------------------------------*/
/*	FORM
/*-----------------------------------------------------------------------------------*/

/* jQuery Validate Emails with Regex */
    function validateEmail(Email) {
        var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return $.trim(Email).match(pattern) ? true : false;
    }

    /* jQuery Validate Emails with Regex */
    function popError(txt,header) {
        if(header === ''){
            header = 'Error';
        }
        $("#myModal .modal-title").html(header);
        $("#myModal .modal-body").html('<p>'+txt+'</p>');
        $("#myModal").modal();
    }
    
    if (!String.format) {
    String.format = function(format) {
        var args = Array.prototype.slice.call(arguments, 1);
        var i=-1;
        return format.replace(/%s/g, function(match, number) {
            i++;
            return typeof args[i] != 'undefined'
              ? args[i] 
              : match
            ;
        });
      };
    }
    
    function hilight_input(elm) {
        elm.addClass('require');
        elm.focus();
    }
    function hilight_clear(elm) {
        elm.removeClass('require');
        elm.focus();
    }
    function hilight_clearall(elm) {
        $(".grande-form ul li:nth-child("+elm+") input").removeClass('require');
    }                                                          
    function check_field($elm_type, $elm_name, $field_name, $checks){

        function check_empty() {
            //console.log('check_empty');
            $txt = $($elm_type+"[name='"+$elm_name+"']").val()||'';
            if ($txt === '') {                                                
                hilight_input($($elm_type+"[name='"+$elm_name+"']"));
                popError(String.format(resource.validate_require,$field_name),"Error"); 
                return false;
            } else {hilight_clear($($elm_type+"[name='"+$elm_name+"']"))}
            return true;
        }
        function check_length($option) {
            //console.log('check_length');
            $txt = $($elm_type+"[name='"+$elm_name+"']").val()||'';
            if ($txt.length < $option) {                                                
                hilight_input($($elm_type+"[name='"+$elm_name+"']"));
                popError(String.format(resource.validate_min_length,$field_name,$option),"Error");
                return false;
            } else {hilight_clear($($elm_type+"[name='"+$elm_name+"']"))}
            return true;
        }
        function check_email() {
            //console.log('check_email');
            $txt = $($elm_type+"[name='"+$elm_name+"']").val()||'';
            if (!validateEmail($txt)) {
                hilight_input( $("input[name="+$elm_name+"]"));
                popError(String.format(resource.validate_valid_email,$field_name),"Error");
                return false;
            } else {hilight_clear($($elm_type+"[name='"+$elm_name+"']"))}
            return true;
        }
        function check_ID() {
            //console.log('check_ID');
            $txt = $($elm_type+"[name='"+$elm_name+"']").val()||'';
            $txt = $txt.replace(/-/g, "");
            //console.log($txt);
            if($txt.length != 13) {
                hilight_input( $("input[name="+$elm_name+"]"));
                popError(String.format(resource.validate_valid_format,$field_name),"Error"); 
                return false;
            }
            for(i=0, sum=0; i < 12; i++){
                sum += parseFloat($txt.charAt(i))*(13-i); 
            }
            if((11-sum%11)%10!=parseFloat($txt.charAt(12))){
                hilight_input( $("input[name="+$elm_name+"]"));
                popError(String.format(resource.validate_valid_format,$field_name),"Error");
                return false;
            }
            return true;
        }
        function check_unique($option) {
            //console.log('check_unique');
            $txt = $($elm_type+"[name='"+$elm_name+"']").val()||'';
            $txt = $txt.replace(/-/g, "");                                                            
            $data = {};
            eval("$data."+$elm_name+"={}")
            eval("$data."+$elm_name+".value=$txt")
            if ($option != '') {
                //$data.member_id  = $option;
                eval("$data."+$elm_name+".id="+$option);
            }                       

            if ($txt !== '') {
                $.ajax({
                    type: "POST",
                    url: "/signup/check_unique",
                    data: $data,
                    //data: $(".grande-form input[name="+$elm_name+"]").serialize(),
                    dataType: "json",
                    async: false,
                    cache: false,
                    timeout: 30000,
                    success: function(msg){
                        if (msg.status == "success") {
                            hilight_clear($($elm_type+"[name='"+$elm_name+"']"));                                    
                            $res = true;
                        } else {                                                        
                            var errormsg = String.format(resource.validate_in_db,$field_name);                                    
                            hilight_input($("[name="+$elm_name+"]"));                                                                        
                            popError(errormsg,"Error");                                    
                            $res = false;
                        }
                    }
                });
                return $res
            }
        }

        $res = true; 
        for ($check in $checks) {                                                                
            //console.log($field_name);
            //console.log($checks[$check]);

            $method = $checks[$check];
            $option = '';
            if (typeof($checks[$check]) ==="object" ) {
                $method = Object.keys($checks[$check])[0];
                $option = Object.values($checks[$check])[0]
            }
            //console.log($method);

            switch($method) {
                case 'empty':
                    $res = check_empty();
                    break;
                case 'length':
                    $res = check_length($option);
                    break;
                case 'email':
                    $res = check_email();
                    break; 
                case 'check_id':
                    $res = check_ID();
                    break;     
                case 'is_unique':
                    $res = check_unique($option);
                    //console.log("error1");
                    break;  
            }
            //console.log($res);
            if (!$res) { break; }                    
        }
        //console.log("$res");
        //console.log($res);
        return $res;
    }
    
    

$(document).ready(function(){
    
    

});

