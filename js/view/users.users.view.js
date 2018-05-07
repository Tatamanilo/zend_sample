var Users = Backbone.View.extend({
    prefix: 'users',

    table: null,
    users: [],

    events: {
        "submit #usersSearchForm": "searchSubmit",
        "click .periodChangeBtn": "periodChange",
        "click #addUser": "addUser"
    },

    // main func, prepare page
    prepareView: function (userType){
        var self = this;

        $('#dialogEdit').dialog({
            autoOpen: false,
            width: 500
        });

        $('#dialogAdd').dialog({
            autoOpen: false,
            width: 700
        });

        $('#dialogLoginHistory').dialog({
            autoOpen: false,
            width: 900
        });

         $("#search_datefrom").datepicker({
             defaultDate: "-2w",
             dateFormat: "yy-mm-dd",
             //changeMonth: true,
             onClose: function( selectedDate ) {
                $("#search_dateto").datepicker("option", "minDate", selectedDate);
             }
         });

         $("#search_dateto").datepicker({
             defaultDate: "+0",
             dateFormat: "yy-mm-dd",
             //changeMonth: true,
             onClose: function( selectedDate ) {
                $("#search_datefrom").datepicker("option", "maxDate", selectedDate);
             }
         });
         $("#search_datefrom").datepicker("setDate", "-2w");
         $("#search_dateto").datepicker("option", "minDate", "-2w");

         $("#search_dateto").datepicker("setDate", "+0");
         $("#search_datefrom").datepicker("option", "maxDate", "+0");

         this.renderTable(userType);
    },

    // filter form submit
    searchSubmit: function (event) {
        event.preventDefault();
        this.reloadTable();
    },

    // prepare view events
    periodChange: function (event) {
        $(".periodChangeBtn").removeClass("active");
        $(event.currentTarget).addClass("active");
        $("#search_dateperiod").val($(event.currentTarget).attr('period'));
        if ($(event.currentTarget).attr('period') == "custom")
        {
            $("#periodCustomBox").show();
            $("#search_datefrom").attr("disabled", false);
            $("#search_dateto").attr("disabled", false);
        }
        else
        {
            $("#periodCustomBox").hide();
            $("#search_datefrom").attr("disabled", true);
            $("#search_dateto").attr("disabled", true);
        }
    },

    // prepare view events
    addUser: function (event) {
        SMP.makeRequest(
            '/users/users/add',
            {
                rid: $(event.currentTarget).attr('rid')
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    usersAdd.render(data.html);
                }
            },
            'users.openEdit'
        );
    },

    // render table stucture and data
    renderTable: function (userType) {
        var self = this;
        var url = '';
        var colModel = [];
        var caption = '';
        switch (userType) {
            case 'admin':
                url = '/users/users/userlist/id/1';
                aoColumns = [
                    { "mData": 'login' },
                    { "mData": 'registrationDate' },
                    {
                        "bSortable": false,
                        "mData": null,
                        "mRender": this.actionsFormatter
                    }
                ];
                caption = 'Администраторы';
                break;
            case 'support':
                url = '/users/users/userlist/id/6';
                aoColumns = [
                    { "mData": 'login' },
                    { "mData": 'registrationDate' },
                    {
                        "bSortable": false,
                        "mData": null,
                        "mRender": this.actionsFormatter
                    }
                ];
                caption = 'Саппорт';
                break;
            case 'manager':
                url = '/users/users/userlist/id/7';
                aoColumns = [
                    { "mData": 'login' },
                    { "mData": 'registrationDate' },
                    {
                        "bSortable": false,
                        "mData": null,
                        "mRender": this.actionsFormatter
                    }
                ];
                caption = 'Менеджер';
                break;
            case 'affiliate':
                url = '/users/users/userlist/id/3';
                aoColumns = [
                    { "mData": 'userRef' },
                    { "mData": 'login' },
                    { "mData": 'registrationDate' },
                    { "mData": null },
                    {
                        "bSortable": false,
                        "mData": 'status' ,
                        "mRender": this.statusFormatter
                    },
                    {
                        "bSortable": false,
                        "mData": null,
                        "mRender": this.actionsFormatter
                    }
                ];
                caption = 'Аффилиейты';
                break;
            case 'merchant':
                url = '/users/users/userlist/id/4';
                aoColumns = [
                    { "mData": 'login' },
                    { "mData": 'name' },
                    { "mData": 'additionalInfo' },
                    {
                        "bSortable": false,
                        "mData": 'status' ,
                        "mRender": this.statusFormatter
                    },
                    {
                        "bSortable": false,
                        "mData": null,
                        "mRender": this.actionsFormatter
                    }
                ];
                caption = 'Реклы';
                break;
        }

        Users.table = $('#users').dataTable( {
            "fnDrawCallback": function(){self.actionsPrepare(self);},
            "aoColumns": aoColumns,
            "aaSorting": [[ 0, "asc" ]] ,
            "sPaginationType": "bootstrap",
            "bLengthChange": true,
            "aLengthMenu": [20, 50, 100, 200, 500],
            "iDisplayLength": 20,
            "bProcessing": true,
            "bDestroy": true,
            "bFilter": false,
            "bInfo": false,
            "sDom": "frt<Lip>",
            "bServerSide": true,
            "sAjaxSource": url,
            "sServerMethod": "POST",
            "fnServerData": SMP.makeRequestDT
        } );
    },


    // formatter for action column in the table
    actionsFormatter: function (data, type, row) {
        var disabled = '';
        if (row.status == 'delete')
        {
            disabled = 'disabled';
        }
        var hide = '';
        if (row.idRole == '1')
        {
            hide = 'hide';
        }
        var acts = '<button class="_editUser btn btn-default" role="1" uid="' + row.idUser + '" title="Редакт."><i class="fa fa-pencil"></i></button>' +
                '<button class="_delUser btn btn-default ' + disabled + '" role="1" uid="' + row.idUser + '" title="Удал."><i class="fa fa-trash-o"></i></button>' +
                '<button class="_loginAs btn btn-default disabled ' + hide + '" role="1" uid="' + row.idUser + '" title="Войти"><i class="fa fa-key"></i></button>' +
                '<button class="_loginHistory btn btn-default" role="1" uid="' + row.idUser + '" title="История логинов"><i class="fa fa-list-alt"></i></button>' +
                '<button class="viewUser btn btn-default ' + hide + '" role="1" id="viewUser' + row.idUser + '" uid="' + row.idUser + '" title="Инфо"><i class="fa fa-info"></i></button>' +
                '';
        return acts;
        //if (row.)
        //'<button class="_expand btn btn-default" role="1" uid="' + row.idUser + '" title="Раскрыть."><i class="fa fa-plus-circle"></i></button>'
    },

    // formatter for status column in the table
    statusFormatter: function (data, type, row) {
        var st = '';
        var icn = '';
        var li = '';
        var titl = '';
        var uid = 'uid="' + row.idUser + '"';
        if (row.status == "active")
        {
            titl = 'Активный';
            icn = '<i class="fa fa-check-circle text-success font17"></i>';
            li = '<li><a href="javascript:void(0)" ' + uid + ' class="_changeStatus" change_to="ban"><i class="fa fa-minus-circle text-danger "></i> Бан</a></li>';
        }

        if (row.status == "wait")
        {
            titl = 'В ожидании';
            icn = '<i class="fa fa-question-circle font17"></i>';
            li = '<li><a href="javascript:void(0)" ' + uid + ' class="_changeStatus" change_to="active"><i class="fa fa-check-circle text-success "></i> Активный</a></li>'+
                 '<li><a href="javascript:void(0)" ' + uid + ' class="_changeStatus" change_to="ban"><i class="fa fa-minus-circle text-danger "></i> Бан</a></li>';
        }
        if (row.status == "ban")
        {
            titl = 'Бан';
            icn = '<i class="fa fa-minus-circle text-danger font17" title="Бан"></i>';
            li = '<li><a href="javascript:void(0)" ' + uid + ' class="_changeStatus" change_to="active"><i class="fa fa-check-circle text-success "></i> Активный</a></li>';
        }
        if (row.status == "delete")
        {
            titl = 'Бан';
            icn = '<i class="fa fa-times-circle text-warning font17" title="Удален"></i>';
            li = '<li><a href="javascript:void(0)" ' + uid + ' class="_changeStatus" change_to="active"><i class="fa fa-check-circle text-success "></i> Активный</a></li>';
        }


        st = st + '<div class="btn-group">' +
                    '<button title="' + titl + '" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">' +
                        icn + '&nbsp;<span class="caret"></span>' +
                    '</button>' +
                    '<ul style="width: auto;" class="dropdown-menu">' +
                        li +
                    '</ul></div>';


        if (row.idRole == '3')
        {
            // ===== checked ===========
            var icn = '';
            var li = '';
            var titl = '';
            if (row.checked == 1)
            {
                titl = 'Проверен';
                icn = '<i class="fa fa-thumbs-up text-success font17" title="Проверен"></i>';
                li = '<li><a href="javascript:void(0)" ' + uid + ' class="_changeChecked" change_to="0"><i class="fa fa-thumbs-down text-danger "></i> Не проверен</a></li>';
            }

            if (row.checked == 0)
            {
                titl = 'Не проверен';
                icn = '<i class="fa fa-thumbs-down text-danger font17" title="Не проверен"></i>';
                li = '<li><a href="javascript:void(0)" ' + uid + ' class="_changeChecked" change_to="1"><i class="fa fa-thumbs-up text-success "></i> Проверен</a></li>';
            }

            st = st + '<div class="btn-group">' +
                        '<button title="' + titl + '" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">' +
                            icn + '&nbsp;<span class="caret"></span>' +
                        '</button>' +
                        '<ul style="width: auto;" class="dropdown-menu">' +
                            li +
                        '</ul></div>';

            // ===== checked ===========
            var icn = '';
            var li = '';
            var titl = '';
            if (row.freeze == 1)
            {
                icn = '<i class="fa fa-lock text-info font17" title="Заморожен"></i>';
                li = '<li><a href="javascript:void(0)" ' + uid + ' class="_changeFreeze" change_to="0"><i class="fa fa-unlock"></i> Разморозить </a></li>';
            }

            if (row.freeze == 0)
            {
                icn = '<i class="fa fa-unlock  font17" title="Разморожен"></i>';
                li = '<li><a href="javascript:void(0)" ' + uid + ' class="_changeFreeze" change_to="1"><i class="fa fa-lock text-info "></i> Заморозить</a></li>';
            }

            st = st + '<div class="btn-group">' +
                        '<button title="' + titl + '" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">' +
                            icn + '&nbsp;<span class="caret"></span>' +
                        '</button>' +
                        '<ul style="width: auto;" class="dropdown-menu">' +
                            li +
                        '</ul></div>';
        }

        return st;
    },

    // prepare actions of the table, calls after table loaded
    actionsPrepare: function (self) {
        $('._editUser').off('click');
        $('._editUser').on('click', function (event) {
            SMP.makeRequest(
                '/users/users/edit',
                {
                    id: $(this).attr('uid')
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        usersEdit.render(data.html);
                    }
                }
            );
        });

        $('._delUser').off('click');
        $('._delUser').on('click', function (event) {
            if (confirm("Вы действительно хотите удалить пользователя?")) {
                SMP.makeRequest(
                    '/users/users/delete',
                    {
                        id: $(this).attr('uid')
                    },
                    'json',
                    function (data) {
                        if (SMP.isset(data.result) && (data.result == 1)) {
                            $('#users').dataTable().fnDraw(false);
                        }
                    }
                );
            }
        });

        $('._changeStatus').off('click');
        $('._changeStatus').on('click', function (event) {
            SMP.makeRequest(
                '/users/users/changestatus',
                {
                    id: $(this).attr('uid'),
                    change_to: $(this).attr('change_to')
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        $('#users').dataTable().fnDraw(false);
                    }
                }
            );
        });

        $('._changeChecked').off('click');
        $('._changeChecked').on('click', function (event) {
            SMP.makeRequest(
                '/users/users/changechecked',
                {
                    id: $(this).attr('uid'),
                    change_to: $(this).attr('change_to')
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        $('#users').dataTable().fnDraw(false);
                    }
                }
            );
        });

        $('._changeFreeze').off('click');
        $('._changeFreeze').on('click', function (event) {
            SMP.makeRequest(
                '/users/users/changefreeze',
                {
                    id: $(this).attr('uid'),
                    change_to: $(this).attr('change_to')
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        $('#users').dataTable().fnDraw(false);
                    }
                }
            );
        });

        $('._expand').off('click');
        $('._expand').on('click', function (event) {
            var tr = $(this).parents('tr')[0];
            if ( $('#users').dataTable().fnIsOpen(tr) )
            {
                /* This row is already open - close it */
                $('#users').dataTable().fnClose( tr );
            }
            else
            {
                /* Open this row */
                // details - is class that will be on TD
                // some - is content
                $('#users').dataTable().fnOpen( tr, "some", 'details' );
            }
        });

        $('._loginAs').off('click');
        $('._loginAs').on('click', function (event) {
            alert("Login as user logic");
        });

        $('._loginHistory').off('click');
        $('._loginHistory').on('click', function (event) {
            loginHistory.prepareView($(this).attr('uid'));
        });

        $(".viewUser").tooltipX({
            open: function (event, ui) {
                ui.tooltip.css("max-width", "500px");
                self.prepareViewUserClose();
            },
            items: "button",
            content: function(callback) {
                var el = this;
                if (typeof(self.users[$(el).attr("uid")]) == "undefined")
                {
                    SMP.makeRequest(
                        '/users/users/affinfo',
                        {
                            id: $(el).attr('uid')
                        },
                        'json',
                        function (data) {
                            if (SMP.isset(data.result) && (data.result == 1)) {
                                self.users[$(el).attr("uid")] = data.html + '<span style="position:absolute;top:0;right:0;" uid="' + $(el).attr('uid') + '" class="tooltipClose ui-icon ui-icon-circle-close"></span>';
                                callback(self.users[$(el).attr("uid")]);

                            }
                        }
                    );
                }
                else
                {
                    callback(self.users[$(el).attr("uid")]);
                }


            },
            autoHide:false,
            autoShow:false
        });

        $('.viewUser').off('click');
        $('.viewUser').on('click', function (event) {
            $(this).tooltipX("open");
        });
    },

    prepareViewUserClose: function () {
        $('.tooltipClose').off('click');
        $('.tooltipClose').on('click', function (event) {
            $("#viewUser" + $(this).attr("uid")).tooltipX("close");
        });

    },

    // redraw table according to current filter
    reloadTable: function (){

        $('#users').dataTable().fnDraw();
    },

    login: function (event) {
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();
    }
});
