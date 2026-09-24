var Agent = {
    init: function () {

        $("#sign_prefix").select2();
        $("#sign_subdistrict").select2();
        $("#sign_district").select2();
        $("#sign_province").select2();
        $("#sign_province").select2().select2("val",'');
        //$("#sign_project").select2();
        $("#sign_country").select2();

        Agent.initial();

    }
    , agentConfirm: function () {

        $name = $("#sign_prefix option:selected").text() + $("#sign_name").val() +' '+$("#sign_surname").val();
        $phone = $("#sign_phone").val();
        $email = $("#sign_email").val();
        $address = $("#sign_address_no").val();
        $subdistrict = $("#sign_subdistrict option:selected").text();
        $city = $("#sign_district option:selected").text();
        $province = $("#sign_province option:selected").text();;
        $zipcode = $("#sign_postcode").val();
        $country = $("#sign_country option:selected").text();
        $project = $("#sign_unit").val();
        $amounr = $("#sign_payment").val();

        $msg =  '<div class="panel panel-default" style="width: auto;font-size: 1.5rem;overflow: hidden;font-family: Kittithada, sans-serif;line-height: 1.1;font-weight: bold;"><div class="panel-heading text-center" style="font-size: 2rem"><span>สรุปข้อมูลการจอง</span></div><div class="panel-body" style="padding: 0rem 0 0rem 4rem;min-width: 40rem;">';
        $msg += '<div class="row"><div class="col-md-5">Name - Firstname :</div><div class="col-md-7">'+$name+'</div></div>';
        $msg += '<div class="row"><div class="col-md-5">Mobile :</div><div class="col-md-7">'+$phone+'</div></div>';
        $msg += '<div class="row"><div class="col-md-5">Email :</div><div class="col-md-7">'+$email+'</div></div>';
        $msg += '<div class="row"><div class="col-md-5">Address :</div><div class="col-md-7">'+$address+'</div></div>';
        if($subdistrict != 'กรุณาระบุตำบล/แขวง'){
            $msg += '<div class="row"><div class="col-md-5">SubDistrict :</div><div class="col-md-7">'+$subdistrict+'</div></div>';
        }
        if($city != 'กรุณาระบุอำเภอ/เขต'){
            $msg += '<div class="row"><div class="col-md-5">City :</div><div class="col-md-7">'+$city+'</div></div>';
        }
        if($province != 'กรุณาระบุจังหวัด'){
            $msg += '<div class="row"><div class="col-md-5">Province :</div><div class="col-md-7">'+$province+'</div></div>';
        }
        if($zipcode != ''){
            $msg += '<div class="row"><div class="col-md-5">ZipCode :</div><div class="col-md-7">'+$zipcode+'</div></div>';
        }
        $msg += '<div class="row"><div class="col-md-5">Country :</div><div class="col-md-7">'+$country+'</div></div>';
        $msg += '<div class="row"><div class="col-md-12"><hr style="margin:1rem 0;padding:0;width: calc(100% - 4rem);"></div></div>';
        $msg += '<div class="row"><div class="col-md-5">Project :</div><div class="col-md-7">'+$project+'</div></div>';
        $msg += '<div class="row"><div class="col-md-5">Amount :</div><div class="col-md-7">'+$amounr+'</div></div></div>';
        $msg += '<div class="row"><div class="col-md-12 text-center"><button onclick="Agent.agentSubmit();" style="cursor:pointer;font-weight: bold;background-color: #333;color: #FFF;border: 0 solid;padding: 0.5rem 3rem;border-radius: 6px;margin-top: 1rem;">Confirm</button></div></div>';
        $msg += '</div></div></div>';

        $.fancybox.open($msg);
    }
    , agentSubmitSuccess: function () {
        $msg =  '<div class="panel panel-default dialog"><div class="panel-heading text-center"><span>สรุปข้อมูลการจอง</span></div><div class="panel-body success">';
        $msg += '<div class="row"><div class="col-md-12 text-center"><i class="fa fa-check-circle"></i></div></div>';
        $msg += '<div class="row"><div class="col-md-12 text-center msg">การจองสำเร็จ</div></div>';
        $msg += '<div class="row"><div class="col-md-12 text-center"><button class="btn_submit" onclick="$.fancybox.close();">OK</button></div></div>';
        $msg += '</div></div></div>';

        $.fancybox.open(
            $msg,
            opts = {
		afterClose : function() {
                    var $agent_id_code = $("#agent_id_code").val();
                    window.location.href = "/agents/"+$agent_id_code+"/reservation";
		}
            }
        );
    }
    , agentSubmitFail: function () {
        $msg =  '<div class="panel panel-default dialog"><div class="panel-heading text-center"><span>สรุปข้อมูลการจอง</span></div><div class="panel-body fail">';
        $msg += '<div class="row"><div class="col-md-12 text-center"><i class="fa fa-times-circle"></i></div></div>';
        $msg += '<div class="row"><div class="col-md-12 text-center msg">ทำรายการจองไม่สำเร็จ</div></div>';
        $msg += '<div class="row"><div class="col-md-12 text-center"><button class="btn_submit" onclick="$.fancybox.close();">OK</button></div></div>';
        $msg += '</div></div></div>';

        $.fancybox.open(
            $msg,
            opts = {
		afterClose : function() {
                    var $agent_id_code = $("#agent_id_code").val();
                    window.location.href = "/agents/"+$agent_id_code+"/reservation";
		}
            }
        );
    }
    , agentSubmit: function () {

        $.fancybox.close();
        var url = "/agents/submit";
        Global.LoadingIcon($('body'));

        try {
            $data = $("#agentForm").serialize();
            $data += '&country='+$('#sign_country option:selected').text();

            $('body').waitMe();
            $.ajax({
                url: url,
                data: $data,
                type: 'POST',
                success: function (obj) {

                    obj = JSON.parse(obj);

                    //console.log(obj.status);
                    if (obj.status == "success") {

                        //var response = $.parseJSON(obj.data)

                        /*
                        $("#MERCHANT2").val(response["MERCHANT2"]);
                        $("#TERM2").val(response["TERM2"]);
                        $("#AMOUNT2").val(response["AMOUNT2"]);

                        $("#URL2").val(response["URL2"]);
                        $("#RESPURL").val(response["RESPURL"]);
                        $("#IPCUST2").val(response["IPCUST2"]);
                        $("#DETAIL2").val(response["DETAIL2"]);

                        $("#INVMERCHANT").val(response["INVMERCHANT"]);
                        $("#FILLSPACE").val(response["FILLSPACE"]);
                        //$("#SHOPID").val(response["SHOPID"]);
                        //$("#PAYTERM2").val(response["PAYTERM2"]);

                        $("#CHECKSUM").val(response["CHECKSUM"]);
                        */
                        // alert prepay

                        var form = document.createElement("form");
                        form.method = "POST";
                        form.action = obj.pay_url;

                        var input1 = document.createElement("input");
                        input1.setAttribute("type", "hidden");
                        input1.setAttribute("name", "merchantId");
                        input1.setAttribute("value", obj.pay_merchantid);
                        form.appendChild(input1);

                        var input2 = document.createElement("input");
                        input2.setAttribute("type", "hidden");
                        input2.setAttribute("name", "amount");
                        input2.setAttribute("value", obj.xyz.total_paid);
                        form.appendChild(input2);

                        var input3 = document.createElement("input");
                        input3.setAttribute("type", "hidden");
                        input3.setAttribute("name", "orderRef");
                        input3.setAttribute("value", obj.xyz.token);
                        form.appendChild(input3);

                        var input4 = document.createElement("input");
                        input4.setAttribute("type", "hidden");
                        input4.setAttribute("name", "currCode");
                        input4.setAttribute("value", "764");
                        form.appendChild(input4);

                        var input5 = document.createElement("input");
                        input5.setAttribute("type", "hidden");
                        input5.setAttribute("name", "successUrl");
                        input5.setAttribute("value", obj.url+"success");
                        form.appendChild(input5);

                        var input6 = document.createElement("input");
                        input6.setAttribute("type", "hidden");
                        input6.setAttribute("name", "failUrl");
                        input6.setAttribute("value", obj.url+"fail");
                        form.appendChild(input6);

                        var input7 = document.createElement("input");
                        input7.setAttribute("type", "hidden");
                        input7.setAttribute("name", "cancelUrl");
                        input7.setAttribute("value", obj.url+"cancel");
                        form.appendChild(input7);

                        var input8 = document.createElement("input");
                        input8.setAttribute("type", "hidden");
                        input8.setAttribute("name", "payType");
                        input8.setAttribute("value", "N");
                        form.appendChild(input8);

                        var input9 = document.createElement("input");
                        input9.setAttribute("type", "hidden");
                        input9.setAttribute("name", "lang");
                        input9.setAttribute("value", "E");
                        form.appendChild(input9);

                        var input10 = document.createElement("input");
                        input10.setAttribute("type", "hidden");
                        input10.setAttribute("name", "remark");
                        input10.setAttribute("value", "-");
                        form.appendChild(input10);

                        var input11 = document.createElement("input");
                        input11.setAttribute("type", "hidden");
                        input11.setAttribute("name", "redirect");
                        input11.setAttribute("value", "15");
                        form.appendChild(input11);

                        document.body.appendChild(form);

                        form.submit();

                        //$("#btnPayment").click();

                        //Agent.agentSubmitSuccess();

                        $('body').waitMe('hide');
                    }
                    else {

                        Agent.agentSubmitFail();

                        $('body').waitMe('hide');
                    }
                },
                error: function (obj) {

                    Agent.agentSubmitFail();

                    $('body').waitMe('hide');
                }
            });
        } catch (e) {
            alert(e);
        }
    }
    , agentSignout : function () {
        var url = "/agents/signout";

        Global.LoadingIcon($('body'));

        try {
            $.ajax({
                url: url,
                type: 'POST',
                success: function (obj) {
                    window.location.reload();
                },
                error: function (obj) {

                }
            });
        } catch (e) {
            alert(e);
        }
    }
    , initial: function () {

        //$("#sign_phone").inputmask({"mask": "999-999-9999"});

        $('.validation').tooltipster({
            trigger: 'custom',
            onlyOne: false,
            position: 'bottom',
            theme: "tooltipster-error-light",
            contentAsHTML: true,
        });

        $("#btnAgentSubmit").click(function (e) {
            if ($("#agentForm").valid()) {
                //Agent.agentSubmit();
                Agent.agentConfirm();
            }
        });
        $(".agentSignout").click(function (e) {
            e.stopPropagation();
            e.preventDefault();
            Agent.agentSignout();
        });
        $("select[name=sign_province]").change(function(){
            $province_id = $(this).val();
            $.ajax({
                type: "POST",
                url: "/signup/get_amphur",
                data: {province_id:$province_id},
                success: function(msg){
                    $("select[name=sign_district]").html(msg);
                    $("input[name=sign_postcode]").val('');
                    $("#sign_district").select2().select2("val",'');
                    $("#sign_subdistrict").select2().select2("val",'');
                }
            });
        });
        $("select[name=sign_district]").change(function(){
            $amphur_id = $(this).val();
            $.ajax({
                type: "POST",
                url: "/signup/get_tumbon",
                data: {amphur_id:$amphur_id},
                success: function(msg){
                    $("select[name=sign_subdistrict]").html(msg);
                    $("input[name=sign_postcode]").val('');
                    $("#sign_subdistrict").select2().select2("val",'');
                }
            });
        });
        $("select[name=sign_subdistrict]").change(function(){
            $tumbon_code = $(this).val();
            $.ajax({
                type: "POST",
                url: "/signup/get_postcode",
                data: {tumbon_code:$tumbon_code},
                success: function(msg){
                    $("input[name=sign_postcode]").val(msg);
                }
            });
        });

        $("#agentForm").validate({
            ignore: '*:not([name])',
            rules: {
                sign_name: {
                    required: true,
                    rangelength: [1, 250]
                },
                sign_surname: {
                    required: true,
                    rangelength: [1, 250]
                },
                sign_phone: {
                    required: true,
                    rangelength: [8, 15],
                    //digits: true
                },
                sign_email: {
                    required: true,
                    rangelength: [5, 100],
                    email: true
                },
                sign_address_no: {
                    required: true,
                    rangelength: [3, 250]
                },
                sign_unit: {
                    required: true,
                    rangelength: [3, 200],
                },
                sign_payment: {
                    required: true,
                    number: true,
                    min:1
                }
            },
            messages: {
                sign_name: {
                    required: "กรุณาระบุชื่อ.",
                    rangelength: "- Allow 1-250 char.",
                },
                sign_surname: {
                    required: "กรุณาระบุนามสกุล.",
                    rangelength: "- Allow 1-250 char.",
                },
                sign_phone: {
                    required: "- Etc. 088-123-456",
                    rangelength: "- Allow 9 char.",
                    digits: "- Etc. 088-123-456"
                },
                sign_email: {
                    required: "กรุณาระบุอีเมล",
                    rangelength: "- Allow 5-100 char.",
                    email: "- Etc. youremail@email.com."
                },
                // current add
                sign_address_no: {
                    required: "กรุณาระบุที่อยู่",
                    rangelength: "- Allow 3-250 char.",
                },
                sign_unit: {
                    required: "กรุณาระบุโครงการ",
                    rangelength: "- Allow 3-10 char.",
                },
                sign_payment: {
                    required: "กรุณาระบุจำนวนเงิน",
                    number: "- Etc. 1,234",
                    min: "จำนวนเงินต้องมากกว่า 100,000"
                }
            },
            debug: true,
            success: "valid",
            errorPlacement: function (error, element) {
                if ($(error).text().length > 1) {

                    console.log(element);

                    $(element).tooltipster('update', $(error).text());
                    $(element).tooltipster('show');
                }
            },
            success: function (label, element) {
                $(element).tooltipster('hide');
            }
        });
    }
}

var AgentSignin = {
    init: function () {
        AgentSignin.initial();
    }
    , agentSignin: function () {
        var url = "/agents/signin";

        if (!$("#signinForm").valid()) {
            return false;
        }

        Global.LoadingIcon($('body'));

        try {
            $.ajax({
                url: url,
                data: $("#signinForm").serialize(),
                type: 'POST',
                success: function (obj) {
                    obj = JSON.parse(obj);
                    if (obj.status == "success") {
                        $().toastmessage('showToast', {
                            text: obj.message,
                            position: 'top-center',
                            sticky: false,
                            type: 'success'
                        });
                        window.location.reload();
                        $('body').waitMe('hide');
                    } else {
                        $().toastmessage('showToast', {
                            text: obj.message,
                            position: 'top-center',
                            sticky: true,
                            type: 'error'
                        });
                        $('body').waitMe('hide');
                    }
                },
                error: function (obj) {
                    $().toastmessage('showToast', {
                        text: obj.message,
                        position: 'top-center',
                        sticky: true,
                        type: 'error'
                    });

                    $('body').waitMe('hide');
                }
            });
        } catch (e) {
            //alert(e);
        }
        return false;
    }
    , initial: function () {
        $("#btnAgentsSignin").click(function (e) {
            AgentSignin.agentSignin();
        });
    }
}

var Global = {
    unAuthorized: function (redirect) {
        if (redirect) {
            var reurl = baseUrl + 'Utility/Login';
            setTimeout(function () { window.parent.location = reurl }, 3000);
        }
    },
    LoadingIcon: function (item) {
        if (item) {
            item.waitMe({
                //none, rotateplane, stretch, orbit, roundBounce, win8,
                //win8_linear, ios, facebook, rotation, timer, pulse,
                //progressBar, bouncePulse or img
                effect: 'bounce',
                //place text under the effect (string).
                text: '',
                //background for container (string).
                bg: 'rgba(255,255,255,0.7)',
                //color for background animation and text (string).
                color: '#000',
                //change width for elem animation (string).
                sizeW: '',
                //change height for elem animation (string).
                sizeH: '',
                // url to image
                source: '',
                // callback
                onClose: function () { }
            });
        }
    }
}

var History = {
    init: function (agent_id) {
        History.agent_id = agent_id;
        History.initial();
    }
    , initial: function () {
        $('#pagination').on('click','a',function(e){
            e.preventDefault();
            var pageno = $(this).attr('data-ci-pagination-page');
            History.loadPagination(pageno);
        });

        History.loadPagination(1);
    }
    , createTable: function (result) {

        $('#postsList').empty();
        for(index in result){
            var create_date = result[index].create_date_format;
            var name = result[index].name;
            var surname = result[index].surname;
            var address_no =
                     ((result[index].address_no==null)?'':result[index].address_no+' ')
                    +((result[index].subdistrict==null)?'':result[index].subdistrict+' ')
                    +((result[index].district==null)?'':result[index].district+' ')
                    +((result[index].province==null)?'':result[index].province+' ')
                    +((result[index].postcode==null)?'':result[index].postcode+' ')
                    +((result[index].country==null)?'':result[index].country+' ')
            var phone = result[index].phone;
            var email = result[index].email;
            var project_unit = result[index].project_unit;
            var total_paid = $.number(result[index].total_paid);

            var tr = '<div class="row detail">';
            tr += '<div class="col-md-1">'+create_date+'</div>';
            tr += '<div class="col-md-1">'+name+'</div>';
            tr += '<div class="col-md-1">'+surname+'</div>';
            tr += '<div class="col-md-4">'+address_no+'</div>';
            tr += '<div class="col-md-1">'+phone+'</div>';
            tr += '<div class="col-md-1">'+email+'</div>';
            tr += '<div class="col-md-2">'+project_unit+'</div>';
            tr += '<div class="col-md-1">'+total_paid+'</div>';
            tr += '</div>';

           $('#postsList').append(tr);

        }
    }
    , loadPagination: function (pagno) {
        $keyword = $('.txt-search').val();
        $.ajax({
            url: '/agents/loadRecord/'+History.agent_id+'/'+pagno+'/'+$keyword,
            type: 'get',
            dataType: 'json',
            success: function(response){
               $('#pagination').html(response.pagination);
               History.createTable(response.result);
            }
        });
        return false;
    }
}

