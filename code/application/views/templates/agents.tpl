{include file="header_agents.tpl"}
<div id="page-content-wrapper">
{ci_config name="base_url"}

<link rel="stylesheet" type="text/css" href="{$base_url}/assets/css/agent.css?v=1">

<div class="agents-containter" >
            
{if (!$login)}
    
    <style>
        #page-content-wrapper{ background-color: #D89D57 }
        .agents-containter .panel-default { background-color: transparent;border-radius: 10px;padding: 0 0 1rem 0; }
        .agents-containter .panel-body { padding: 0; }
    </style>

    <div class="book-info agent signin">
                               
        <form id="signinForm" method="post" action="" onsubmit="return AgentSignin.agentSignin();">
            <input id="agent_id" name="agent_id" type="hidden" value="{$agent_id}" />
            <input id="agent_id_code" name="agent_id_code" type="hidden" value="{$agent_id_code}" />
            <div class="col-md-12 row">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">                    
                            <div class="col-md-12">
                                <a href="{$base_url}">                        
                                    <img src="{$logo|default:"{$base_url}/assets/images/grande/grande.png"}" class="logo-grande" alt="">
                                </a>
                            </div>
                        </div>                                                
                        <div class="row">                    
                            <div class="col-md-12 sign-name">{$name} {$surname}</div>
                        </div>
                        <div class="row">                    
                            <div class="col-md-12">
                                <input class="psfont form-control" id="agentPassword" name="agentPassword" type="password" value="" placeholder="Password"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center" >
                                <input class="btn btn-primary btn-lg" style="" type="button" id="btnAgentsSignin" name="btnAgentsSignin" value="LOGIN" />
                            </div>
                        </div>
                        <div class="">
                            <p style="font-size: 10pt;"></p>
                        </div>
                        <div class="">
                            <p style="font-size: 10pt; color: #4800ff; text-align: right;"></p>
                        </div>
                    </div>
                </div>
            </div>
        </form>

{else}
        
    <div class="row"><div class="col-md-12 agents-title">Agent</div></div>
    <div class="row agents-content">
        <div class="col-md-3 agents-menu">
            <div class="agents-menu-panel">
                <div class="agents-menu-panel-name">{$name} {$surname}</div>
                <div class="agents-menu-panel-email">{$email}</div>
                <div class="agents-menu-panel-phone">{$phone}</div>
                <div class="show-desktop">
                    <div class=""><hr></hr></div>                
                    <ul class="agents-menu-panel-menu text-left">
                        {if ($active_page=='history') }
                        <li class=""><a href="{$base_url}/agents/{$agent_id_code}/reservation" >Reservation</a></li>
                        <li class=""><a class="active">History</a></li> 
                        {else}
                        <li class=""><a class="active">Reservation</a></li>
                        <li class=""><a href="{$base_url}/agents/{$agent_id_code}/history">History</a></li> 
                        {/if}
                        <li class=""><a href='#' class='agentSignout'>LogOut</a></li> 
                    </ul>
                </div>
            </div>
        </div>
                        
        {if ($active_page=='history')}
        <div class="col-md-9 agents-form">
            <div class="col-md-12 agentForm-title">
                History
                <div class="search-container">
                    <form method="post" action="" onsubmit="return History.loadPagination(1);">
                      <input type="text" placeholder="Search.." name="search" class="txt-search">
                      <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>
            <div class="agents-form-box history">    
                <div class="row header">
                    <div class="col-md-1">Date</div>
                    <div class="col-md-1">FirstName</div>
                    <div class="col-md-1">LastName</div>
                    <div class="col-md-4">Address</div>
                    <div class="col-md-1">Tel</div>
                    <div class="col-md-1">Email</div>
                    <div class="col-md-2">Project</div>
                    <div class="col-md-1">Amount</div>
                </div>                
                {*foreach from=$historys item=$history}                    
                    <div class="row detail">
                        <div class="col-md-1">{$history.create_date|date_format:"%d-%m-%y"}</div>
                        <div class="col-md-1">{$history.name}</div>
                        <div class="col-md-1">{$history.surname}</div>
                        <div class="col-md-4">{$history.address_no}</div>
                        <div class="col-md-1">{$history.phone}</div>
                        <div class="col-md-1">{$history.email}</div>
                        <div class="col-md-2">{$history.project_unit}</div>
                        <div class="col-md-1">{$history.total_paid}</div>
                    </div>
                {/foreach*}
                
                <!-- Posts List -->
                <div id='postsList'></div>
                {*<table border='1' width='100%' style='border-collapse: collapse;' id='postsList'>
                  <thead>
                   <tr>
                     <th>S.no</th>
                     <th>Title</th>
                     <th>Content</th>
                   </tr>
                  </thead>
                  <tbody></tbody>
                </table>*}
                
                <!-- Paginate -->
                <div id='pagination'></div>
                
                {*<nav aria-label="navigation pg-amber">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                          <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                          </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active"><span class="page-link">2</span></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item">
                          <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                          </a>
                        </li>
                    </ul>
                </nav>  *}                              
            </div>
        </div>  
        <script type='text/javascript'>            
            $(document).ready(function(){              
                History.init('{$agent_id}');                
            });            
        </script>    
        {else}
        <div class="col-md-9 agents-form">
            <div class="col-md-12 agentForm-title">Reservation</div>
            <div class="agents-form-box">                
                <form id="agentForm" name="agentForm" method="post" action="">
                    <input id="agent_id" name="agent_id" type="hidden" value="{$agent_id}" />
                    <input id="agent_id_code" name="agent_id_code" type="hidden" value="{$agent_id_code}" />
                    <div class="col-md-12 agentForm-title-form">
                        <div class="panel panel-default">
                            <div class="panel-heading"><span><div class="panel-no">1</div>&nbsp;&nbsp;Customer</span></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row"><div class="col-md-12"><label class="psfont">First Name: </label> <span class="validaterequire">*</span></div></div>
                                        <div class="row">
                                            <div class="col-3 col-md-3" style="padding-right: 0;">
                                                <select data-form="select2" data-select-value="" id="sign_prefix" name="sign_prefix" class="psfont" value="">
                                                    <option value="Mr.">Mr.</option>
                                                    <option value="Mrs.">Mrs.</option>
                                                    <option value="Miss">Miss</option>
                                                </select>
                                            </div>
                                            <div class="col-9 col-md-9">
                                                <input class="psfont form-control validation" id="sign_name" name="sign_name" type="text" style="width: 100%;" />
                                            </div>
                                        </div>                                                                        
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="row"><div class="col-md-12"><label class="psfont">Last Name: </label> <span class="validaterequire">*</span></div></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input class="psfont form-control validation" id="sign_surname" name="sign_surname" type="text" style="width: 100%;" />
                                            </div>
                                        </div>                                                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row"><div class="col-md-12"><label class="psfont">Mobile: </label> <span class="validaterequire">*</span></div></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input class="psfont form-control validation" id="sign_phone" name="sign_phone" type="text" style="width: 100%;" />
                                            </div>
                                        </div>                                                                        
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="row"><div class="col-md-12"><label class="psfont">Email: </label> <span class="validaterequire">*</span></div></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input class="psfont form-control validation" id="sign_email" name="sign_email" type="text" style="width: 100%;" />
                                            </div>
                                        </div>                                                                        
                                    </div>
                                </div>                
                                <div class="row">
                                    <div class="col-md-12"><label class="psfont">Address: </label> <span class="validaterequire">*</span></div>
                                    <div class="col-md-12">
                                        <textarea class="psfont form-control validation" id="sign_address_no" name="sign_address_no" rows="2" style="width: 100%;"></textarea>
                                    </div>
                                </div>
                                <div class="row">                                                                    
                                    <div class="col-md-6">
                                        <div class="row"><div class="col-md-12"><label class="psfont">Province: </label></div></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <select id="sign_province" name="sign_province" autocomplete="off" data-form="select2" data-select-value="" class="validation psfont">
                                                    <option value='' disabled="disabled" selected='selected'>{$lang['sign_province_info']}</option>
                                                    {html_options options=$province}
                                                </select>
                                            </div>
                                        </div>                                                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row"><div class="col-md-12"><label class="psfont">City: </label></div></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <select id="sign_district" name="sign_district" autocomplete="off" data-form="select2" data-select-value="" class="validation psfont">
                                                    {html_options options=$district }
                                                </select>
                                            </div>
                                        </div>                                                                        
                                    </div>    
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row"><div class="col-md-12"><label class="psfont">SubDistrict: </label></div></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <select id="sign_subdistrict" name="sign_subdistrict" autocomplete="off" data-form="select2" data-select-value="" class="validation psfont">
                                                    {html_options options=$subdistrict }
                                                </select>
                                            </div>
                                        </div>                                                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row"><div class="col-md-12"><label class="psfont">ZipCode: </label></div></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input id="sign_postcode" name="sign_postcode" type="text" style="width: 100%;" class="psfont form-control validation" class="validation psfont"/>
                                            </div>
                                        </div>                                                                        
                                    </div>            
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row"><div class="col-md-12"><label class="psfont">Country: </label></div></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" id="hhDefaultCountry" value="CN" />
                                                <input type="hidden" id="hdCurrentCountry" value="CN" />

                                                <select id="sign_country" name="sign_country" autocomplete="off" data-form="select2" data-select-value="" class="psfont">
                <option value="AE">AE : United Arab Emirates</option>
                <option value="AF">AF : Afghanistan</option>
                <option value="AG">AG : Antigua and Barbuda</option>
                <option value="AI">AI : Anguilla</option>
                <option value="AL">AL : Albania</option>
                <option value="AM">AM : Armenia</option>
                <option value="AN">AN : Netherlands Antilles</option>
                <option value="AO">AO : Angola</option>
                <option value="AQ">AQ : Antarctica</option>
                <option value="AR">AR : Argentina</option>
                <option value="AS">AS : American Samoa</option>
                <option value="AT">AT : Austria</option>
                <option value="AU">AU : Australia</option>
                <option value="AW">AW : Aruba</option>
                <option value="AZ">AZ : Azerbaijan</option>
                <option value="BA">BA : Bosnia and Herzegovina</option>
                <option value="BB">BB : Barbados</option>
                <option value="BD">BD : Bangladesh</option>
                <option value="BE">BE : Belgium</option>
                <option value="BF">BF : Burkina Faso</option>
                <option value="BG">BG : Bulgaria</option>
                <option value="BH">BH : Bahrain</option>
                <option value="BI">BI : Burundi</option>
                <option value="BJ">BJ : Benin</option>
                <option value="BM">BM : Bermuda</option>
                <option value="BN">BN : Brunei Darussalam</option>
                <option value="BO">BO : Bolivia</option>
                <option value="BR">BR : Brazil</option>
                <option value="BS">BS : Bahamas</option>
                <option value="BT">BT : Bhutan</option>
                <option value="BV">BV : Bouvet Island</option>
                <option value="BW">BW : Botswana</option>
                <option value="BY">BY : Belarus</option>
                <option value="BZ">BZ : Belize</option>
                <option value="CA">CA : Canada</option>
                <option value="CC">CC : Cocos (Keeling) Islands</option>
                <option value="CF">CF : Central African Republic</option>
                <option value="CG">CG : Congo</option>
                <option value="CH">CH : Switzerland</option>
                <option value="CI">CI : Cote d&#39;Ivoire</option>
                <option value="CK">CK : Cook Islands</option>
                <option value="CL">CL : Chile</option>
                <option value="CM">CM : Cameroon</option>
                <option value="CN">CN : China</option>
                <option value="CO">CO : Colombia</option>
                <option value="CR">CR : Costa Rica</option>
                <option value="CU">CU : Cuba</option>
                <option value="CV">CV : Cape Verde</option>
                <option value="CX">CX : Christmas Island</option>
                <option value="CY">CY : Cyprus</option>
                <option value="CZ">CZ : Czech Republic</option>
                <option value="DE">DE : Germany</option>
                <option value="DJ">DJ : Djibouti</option>
                <option value="DK">DK : Denmark</option>
                <option value="DM">DM : Dominica</option>
                <option value="DO">DO : Dominican Republic</option>
                <option value="DZ">DZ : Algeria</option>
                <option value="EC">EC : Ecuador</option>
                <option value="EE">EE : Estonia</option>
                <option value="EG">EG : Egypt</option>
                <option value="EH">EH : Western Sahara</option>
                <option value="ER">ER : Eritrea</option>
                <option value="ES">ES : Spain</option>
                <option value="ET">ET : Ethiopia</option>
                <option value="FI">FI : Finland</option>
                <option value="FJ">FJ : Fiji</option>
                <option value="FK">FK : Falkland Islands</option>
                <option value="FM">FM : Micronesia</option>
                <option value="FO">FO : Faroe Islands</option>
                <option value="FR">FR : France</option>
                <option value="GA">GA : Gabon</option>
                <option value="GB">GB : United Kingdom</option>
                <option value="GD">GD : Grenada</option>
                <option value="GE">GE : Georgia</option>
                <option value="GF">GF : French Guiana</option>
                <option value="GH">GH : Ghana</option>
                <option value="GI">GI : Gibraltar (UK)</option>
                <option value="GL">GL : Greenland</option>
                <option value="GM">GM : Gambia</option>
                <option value="GN">GN : Guinea</option>
                <option value="GP">GP : Guadaloupe</option>
                <option value="GQ">GQ : Equatorial Guinea</option>
                <option value="GR">GR : Greece</option>
                <option value="GS">GS : South Georgia</option>
                <option value="GT">GT : Guatemala</option>
                <option value="GU">GU : Guam</option>
                <option value="GW">GW : Guinea-Bissau</option>
                <option value="GY">GY : Guyana</option>
                <option value="HK">HK : Hong Kong SAR PRC, China</option>
                <option value="HM">HM : Heard Island and McDonald Islands</option>
                <option value="HN">HN : Honduras</option>
                <option value="HR">HR : Croatia</option>
                <option value="HT">HT : Haiti</option>
                <option value="HU">HU : Hungary</option>
                <option value="ID">ID : Indonesia</option>
                <option value="IE">IE : Ireland</option>
                <option value="IL">IL : Israel</option>
                <option value="IN">IN : India</option>
                <option value="IO">IO : Bristish Ocean Territory</option>
                <option value="IQ">IQ : Iraq</option>
                <option value="IR">IR : Iran</option>
                <option value="IS">IS : Iceland</option>
                <option value="IT">IT : Italy</option>
                <option value="JM">JM : Jamaica</option>
                <option value="JO">JO : Jordan</option>
                <option value="JP">JP : Japan</option>
                <option value="KE">KE : Kenya</option>
                <option value="KG">KG : Kyrgyzstan</option>
                <option value="KH">KH : Cambodia</option>
                <option value="KI">KI : Kiribati</option>
                <option value="KM">KM : Comoros</option>
                <option value="KN">KN : Saint Kitts and Nevis</option>
                <option value="KP">KP : D,P,R, Korea</option>
                <option value="KR">KR : Korea, Republic of</option>
                <option value="KW">KW : Kuwait</option>
                <option value="KY">KY : Cayman Islands</option>
                <option value="KZ">KZ : Kazakhstan</option>
                <option value="LA">LA : Lao People&#39;s Replublic</option>
                <option value="LB">LB : Lebanon</option>
                <option value="LC">LC : Saint Lucia</option>
                <option value="LI">LI : Liechtenstein</option>
                <option value="LK">LK : Sri Lanka</option>
                <option value="LR">LR : Liberia</option>
                <option value="LS">LS : Lesotho</option>
                <option value="LT">LT : Lithuania</option>
                <option value="LU">LU : Luxembourg</option>
                <option value="LV">LV : Latvia</option>
                <option value="LY">LY : Libyan Arab Jamahiriya</option>
                <option value="MA">MA : Morocco</option>
                <option value="MC">MC : Monaco</option>
                <option value="MD">MD : Moldova</option>
                <option value="MG">MG : Madagascar</option>
                <option value="MH">MH : Marshall Islands</option>
                <option value="MK">MK : Macedonia</option>
                <option value="ML">ML : Mali</option>
                <option value="MM">MM : Myanmar</option>
                <option value="MN">MN : Mongolia</option>
                <option value="MO">MO : Macao</option>
                <option value="MP">MP : Northern Mariana Islands</option>
                <option value="MQ">MQ : Martinique</option>
                <option value="MR">MR : Mauritania</option>
                <option value="MS">MS : Montserrat</option>
                <option value="MT">MT : Malta</option>
                <option value="MU">MU : Mauritius</option>
                <option value="MV">MV : Maldives</option>
                <option value="MW">MW : Malawi</option>
                <option value="MX">MX : Mexico</option>
                <option value="MY">MY : Malaysia</option>
                <option value="MZ">MZ : Mozambique</option>
                <option value="NA">NA : Namibia</option>
                <option value="NC">NC : New Caledonia</option>
                <option value="NE">NE : Niger</option>
                <option value="NF">NF : Norfolk Island</option>
                <option value="NG">NG : Nigeria</option>
                <option value="NI">NI : Nicaragua</option>
                <option value="NL">NL : Netherlands</option>
                <option value="NO">NO : Norway</option>
                <option value="NP">NP : Nepal</option>
                <option value="NR">NR : Nauru</option>
                <option value="NU">NU : Niue</option>
                <option value="NZ">NZ : New Zealand</option>
                <option value="OM">OM : Oman</option>
                <option value="OTH">OTH : Other</option>
                <option value="PA">PA : Panama</option>
                <option value="PE">PE : Peru</option>
                <option value="PF">PF : French Polynesia</option>
                <option value="PG">PG : Papua New Guinea</option>
                <option value="PH">PH : Philippines</option>
                <option value="PK">PK : Pakistan</option>
                <option value="PL">PL : Poland</option>
                <option value="PM">PM : St. Pierre and Miquelon</option>
                <option value="PN">PN : Pitcairn Islands</option>
                <option value="PR">PR : Puerto Rico</option>
                <option value="PT">PT : Portugal</option>
                <option value="PW">PW : Palau</option>
                <option value="PY">PY : Paraguay</option>
                <option value="QA">QA : Qatar</option>
                <option value="RE">RE : Reunion</option>
                <option value="RO">RO : Romania</option>
                <option value="RU">RU : Russian Federation</option>
                <option value="RW">RW : Rwanda</option>
                <option value="SA">SA : Saudi Arabia</option>
                <option value="SB">SB : Solomon Islands</option>
                <option value="SC">SC : Seychelles</option>
                <option value="SD">SD : Sudan</option>
                <option value="SE">SE : Sweden</option>
                <option value="SG">SG : Singapore</option>
                <option value="SH">SH : St. Helena</option>
                <option value="SI">SI : Slovenia</option>
                <option value="SJ">SJ : Svalbard og Jan Mayen</option>
                <option value="SK">SK : Slovakia</option>
                <option value="SL">SL : Sierra Leone</option>
                <option value="SM">SM : San Marino</option>
                <option value="SN">SN : Senegal</option>
                <option value="SO">SO : Somalia</option>
                <option value="SR">SR : Suriname</option>
                <option value="ST">ST : Sao Tome and Principe</option>
                <option value="SV">SV : El Salvador</option>
                <option value="SY">SY : Syria</option>
                <option value="SZ">SZ : Swaziland</option>
                <option value="TC">TC : Turks and Caicos Islands</option>
                <option value="TD">TD : Chad</option>
                <option value="TF">TF : French Southern Territories</option>
                <option value="TG">TG : Togo</option>
                <option value="TH" selected="selected">TH : Thailand</option>
                <option value="TJ">TJ : Tajikistan</option>
                <option value="TM">TM : Turkmenistan</option>
                <option value="TN">TN : Tunisia</option>
                <option value="TO">TO : Tonga</option>
                <option value="TP">TP : East Timor</option>
                <option value="TR">TR : Turkey</option>
                <option value="TT">TT : Trinidad and Tobago</option>
                <option value="TV">TV : Tuvalu</option>
                <option value="TW">TW : Taiwan Region</option>
                <option value="TZ">TZ : Tanzania</option>
                <option value="UA">UA : Ukraine</option>
                <option value="UG">UG : Uganda</option>
                <option value="UM">UM : United States Minor Outlying Islands</option>
                <option value="US">US : United States</option>
                <option value="UY">UY : Uruguay</option>
                <option value="UZ">UZ : Uzbekistan</option>
                <option value="VA">VA : Vatican City State (Holy See)</option>
                <option value="VC">VC : Saint Vincent and the Grenadines</option>
                <option value="VE">VE : Venezuela</option>
                <option value="VG">VG : Virgin Islands (UK)</option>
                <option value="VI">VI : Virgin Islands (USA)</option>
                <option value="VN">VN : Vietnam</option>
                <option value="VU">VU : Vanuatu</option>
                <option value="WF">WF : Wallis et Futuna</option>
                <option value="WS">WS : Samoa</option>
                <option value="YE">YE : Yemen</option>
                <option value="YT">YT : Mayotte</option>
                <option value="ZA">ZA : South Africa</option>
                <option value="ZM">ZM : Zambia</option>
                <option value="ZR">ZR : Zaire</option>
                <option value="ZW">ZW : Zimbabwe</option>
                <option value="ZZ">ZZ : Other-Not Shown</option>
                                                </select>
                                            </div>
                                        </div>                                                                        
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading"><span><div class="panel-no">2</div>&nbsp;&nbsp;Payment</span></div>
                            <div class="panel-body">                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row"><div class="col-md-12"><label class="psfont">Project: </label> <span class="validaterequire">*</span></div></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                {*<select data-form="select2" data-select-value="" id="sign_project" name="sign_project" class="psfont" value="">
                                                {html_options options=$projects }
                                                </select>*}
                                                <input class="psfont form-control validation" id="sign_unit" name="sign_unit" type="text" value=""/>
                                            </div>
                                        </div>                                                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row"><div class="col-md-12"><label class="psfont">Amount: </label> <span class="validaterequire">*</span></div></div>
                                        <div class="row">
                                            <div class="col-md-12" style="position: relative;">
                                                <input class="psfont form-control validation decimal" id="sign_payment" name="sign_payment" type="text" value="0.00"/>
                                                <div style="position: absolute;right: 25px;top: 0;">
                                                    <span style="line-height: 40px;">THB</span>
                                                </div>
                                            </div>
                                        </div>                                                                        
                                    </div>            
                                </div>


                            </div>
                        </div>
                        <div class="col-md-12 text-center">                    
                            <button type="button" class="btn btn-primary btn-lg" id="btnAgentSubmit">Payment</button>                    
                        </div>

                    </div>
                </form>
            </div>
                                
                {*<form id="frmPayment" name="frmPayment" method="post" action="https://rt05.kasikornbank.com/pgpayment/payment.aspx">
                <div class="col-md-12">
                    <input type="hidden" id="MERCHANT2" name="MERCHANT2" value="401001234567001" />
                    <input type="hidden" id="TERM2" name="TERM2" value="70876434" />
                    <input type="hidden" id="AMOUNT2" name="AMOUNT2" value="000000005200" />
                    <input type="hidden" id="URL2" name="URL2" value="www.google.com" />
                    <input type="hidden" id="RESPURL" name="RESPURL" value="www.pruksa.com" />
                    <input type="hidden" id="IPCUST2" name="IPCUST2" value="111.111.111.111" />
                    <input type="hidden" id="DETAIL2" name="DETAIL2" value="Testing Pruksa" />
                    <input type="hidden" id="INVMERCHANT" name="INVMERCHANT" value="000000009111" />
                    <input type="hidden" id="FILLSPACE" name="FILLSPACE" value="Y" />

                    <input type="hidden" id="CHECKSUM" name="CHECKSUM" value="87984546578935624654654" />



                    <div class="col-md-4">
                        <input type="submit" class="btn btn-success btn-lg" id="btnPayment" style="width:100%;display:none;" value="Pay !" />
                    </div>
                </div>
                </form>*}

        </div>   

        {/if}        

{/if} {* check login *} 
    
            </div>
        </div>
        
</div>
<!-- /#page-content-wrapper -->
<div id="div_hide_only" style="display:none;"></div>
<link href="/assets/js/select2-3.5.2/select2.css" rel="stylesheet" />
<script src="/assets/js/select2-3.5.2/select2.js"></script>
<script src="/assets/js/number/jquery.number.js"></script>
<script src="/assets/js/number/autoNumeric.js"></script>
<script src="/assets/js/jquery.validate.js"></script>
<link href="/assets/js/tooltipster-master/css/themes/tooltipster-light.css" rel="stylesheet" />
<link href="/assets/js/tooltipster-master/css/tooltipster.css" rel="stylesheet" />
<link href="/assets/js/tooltipster-master/css/themes/tooltipster-error-light.css" rel="stylesheet" />
<script src="/assets/js/tooltipster-master/js/jquery.tooltipster.min.js"></script>
<link href="/assets/js/waitMe/waitMe.css" rel="stylesheet" />
<script src="/assets/js/waitMe/waitMe.js"></script>
<link href="/assets/js/jquery-toastmessage/resource/css/jquery.toastmessage.css" rel="stylesheet" />
<script src="/assets/js/jquery-toastmessage/jquery.toastmessage.js"></script>

<script src="{$base_url}/assets/js/scripts_grande.js"></script>
<script src="{$base_url}/assets/js/scripts_agents.js"></script>

<script>

    $(document).ready(function () {

    {if (!$login)}
        AgentSignin.init();
    {else}
        Agent.init();
        
        if ('{$param}'=='success') {
            Agent.agentSubmitSuccess();
        } else {
            if ('{$param}'=='fail') {
                Agent.agentSubmitFail();
            }
            if ('{$param}'=='cancel') {
                {if !empty($Ref)}
                    var ifrm = document.createElement('iframe');
                    ifrm.setAttribute('id', 'ifrm');
                    var el = document.getElementById('div_hide_only');
                    el.parentNode.insertBefore(ifrm, el);
                    ifrm.setAttribute('src', "{$base_url}/booking/cancelUrl?Ref={$Ref}");
                {/if}
                Agent.agentSubmitFail();
            }
        }
        
        /*$("input[name=sign_name]").val("สมปอง");
        $("input[name=sign_surname]").val("น้องสมชาย");

        $("input[name=sign_phone]").val("0898888888");
        $("input[name=sign_email]").val("xx@xx.xx");

        $("input[name=sign_unit]").val("sdfgsfsadfasdfasdfsf");
        $("input[name=sign_payment]").val("100000");

        $("#sign_address_no").html("111222");
        $("select[name=sign_province]").val("1");
        $("select[name=sign_province]").change();
        setTimeout(function(){ $("select[name=sign_district]").val("15");$("select[name=sign_district]").change();},500);
        setTimeout(function(){ $("select[name=sign_subdistrict]").val("101502");$("select[name=sign_subdistrict]").change();},1000);*/
    
    {/if}

    });

   
</script>
{*include file="footer_grande.tpl"*}
