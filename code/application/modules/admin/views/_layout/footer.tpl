<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        $('.numberfomat').inputmask( { alias: 'numeric', groupSeparator: ',', digits: 2, digitsOptional: false, placeholder: '0' } );
    } );
    
    function click_confirm(txt,url) {
        bootbox.confirm(txt, function(result) {
            if(result){
                window.location.replace(url);
            }
        }); 
    };
</script>
<style>
    a {
        cursor: pointer;
    }
    .modal {
      text-align: center;
      padding: 0!important;
    }

    .modal:before {
      content: '';
      display: inline-block;
      height: 100%;
      vertical-align: middle;
      margin-right: -4px;
    }

    .modal-dialog {
      display: inline-block;
      text-align: left;
      vertical-align: middle;
    }    
</style>
    
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
            </div>
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    {ci_language line="wpn_pagerendered" default='Page rendered in'}
                    <strong>{$elapsed_time}</strong> {ci_language line="wpn_seconds" default='Seconds'}.
                </div>
                <p>&copy; EWIT Co., Ltd. {$copyright_year}, <a href="#" target="_blank">
                        {ci_language line="wpn_licence" default='Terms and licence'}</a>. {ci_language line="wpn_developed" default='Developed by'}
                        <a href="http://www.ewitewit.com/" target="_blank">EWIT Co., Ltd.</a>.</p>
            </footer>
        </div>


    </body>
</html>