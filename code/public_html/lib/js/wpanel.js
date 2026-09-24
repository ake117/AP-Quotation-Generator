/**
         * Esta função exibe as mensagens do WPanel.
         */
        function sysmsg(message){
            bootbox.alert({
                title: "WPanel",
                size: "small",
                className: "modal-default",
                message: message
            });
        }

        /**
         * Esta função faz a confirmação de exclusão no WPanel.
         */
        function confirm(link){
            bootbox.dialog({
                show: true,
                size: 'small',
                title: "Confirmation",
                message: "This option can not be undone, you are sure you want to continue?",
                locale: "br",
                buttons: {
                    "Cancel": {
                        className: "btn-danger"
                    },
                    "Confirm!": {
                        className: "btn-success",
                        callback: function(){
                            document.location = link;
                        }
                    }
                }
            });
        }

        /**
         * Esta função aplica o plugin dataTable nas listagens do WPanel.
         */
        $(function(){
            $("#grid").dataTable({
                language: {
                    search: "Search the table: ",
                    lengthMenu: "Display  _MENU_  record per page",
                    info: "Displaying records from _START_ a _END_ to _TOTAL_ records.",
                    paginate: {
                        first:      "Primeiro",
                        previous:   "Previous",
                        next:       "Next",
                        last:       "Último"
                    },
                    loadingRecords: "Carregando ...",
                    zeroRecords:    "No record found."
                }
            });

            $('.select_color').colorpicker();

            /* -------------------------------------------------------------------------
             * Este bloco faz o funcionamento do formulário de cadastro
             * de itens de menu.
             * ---------------------------------------------------------------------- */
            $("#tipo_link").click(function () {
                $("#form_link").show();
                $("#form_post").hide();
                $("#form_posts").hide();
                $("#form_funcional").hide();

            });
            $("#tipo_post").click(function () {
                $("#form_link").hide();
                $("#form_post").show();
                $("#form_posts").hide();
                $("#form_funcional").hide();
                $("#form_submenu").hide();
            });
            $("#tipo_posts").click(function () {
                $("#form_link").hide();
                $("#form_post").hide();
                $("#form_posts").show();
                $("#form_funcional").hide();
                $("#form_submenu").hide();
            });
            $("#tipo_funcional").click(function () {
                $("#form_link").hide();
                $("#form_post").hide();
                $("#form_posts").hide();
                $("#form_funcional").show();
                $("#form_submenu").hide();
            });
            $("#tipo_submenu").click(function () {
                $("#form_link").hide();
                $("#form_post").hide();
                $("#form_posts").hide();
                $("#form_funcional").hide();
                $("#form_submenu").show();
            });

        });