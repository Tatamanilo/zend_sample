var ReclsView = Backbone.View.extend({
    prefix: 'recls',

    table: null,
    chart: null,

    events: {
        "submit #reclsSearchForm": "searchSubmit",
        "click #addRecl": "addRecl",
    },

    // prepare view events
    addRecl: function (event) {
        reclsAdd.setElement($("#dialogAdd"));
        reclsAdd.render();

    },

    // filter form submit
    searchSubmit: function (event) {
        event.preventDefault();
        this.reloadTable();
    },

    // main func, prepare page
    prepareView: function (){
        self = this;



        $('#dialogEdit').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            width: 600
        });

        $('#dialogAdd').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            width: 900
        });

        $('#dialogAddUser').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            width: 500
        });

        $('#dialogEditUser').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            width: 500
        });

        $('#dialogAccount').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            width: 800
        });

        $('#dialogPayments').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            width: 800
        });

        $("#search_reclName").autocomplete({
            source: '/recls/recls/reclslist',
            change: function (event, ui) {
                if(!ui.item){
                    $("#search_reclName").val("");
                    $("#search_idRecl").val("");
                }
                else
                {
                    $("#search_idRecl").val(ui.item.id);
                }
            }
        });

        this.renderTable();
    },

    // render table stucture and data
    renderTable: function () {
        var self = this;
        var url = '';
        var colModel = [];
        var caption = '';
        url = '/recls/recls/recls';
        aoColumns = [
            {
                'mData': 'reclName',
                'sName': 'reclName',
            },
            {
                'mData': 'onAccount',
                'sName': 'onAccount',
            },
            {
                'mData': 'unpayedTransactionsInfo',
                'sName': 'unpayedTransactionsInfo',
            },
            {
                'mData': 'balance',
                'sName': 'balance',
            },
            {
                'bSortable': true,
                'mData': 'reclStatus' ,
                'sName': 'reclStatus',
                'mRender': this.statusFormatter
            },
            {
                'bSortable': false,
                'mData': null,
                'mRender': this.actionsFormatter
            }
        ];
        caption = 'Рекламодатели';

        ReclsView.table = $('#recls').dataTable( {
            "fnDrawCallback": function(oSettings){self.actionsPrepare(self, oSettings);},
            "aoColumns": aoColumns,
            "aaSorting": [[0, "asc"]],
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
        var acts = '<button class="_expand btn btn-default" rid="' + row.idRecl + '" title="Детали"><i class="fa fa-info"></i></button>' +
                '<button class="_editRecl btn btn-default" rid="' + row.idRecl + '" title="Редакт."><i class="fa fa-pencil"></i></button>' +
                '<button class="_loginAs btn btn-default disabled" rid="' + row.idRecl + '" title="Войти"><i class="fa fa-key"></i></button>' +
                '<button class="_balance btn btn-default " rid="' + row.idRecl + '" title="Балланс рекла"><i class="fa fa-usd"></i></button>' +
                '';
        return acts;
    },

    // formatter for status column in the table
    statusFormatter: function (data, type, row) {
        var st = '';
        var icn = '';
        var li = '';
        var titl = '';
        var cid = 'rid="' + row.idRecl + '"';
        if (data == "E")
        {
            titl = 'Активный';
            icn = '<i class="fa fa-check-circle text-success font17"></i>';
            li = '<li><a href="javascript:void(0)" ' + cid + ' class="_changeStatus" change_to="D"><i class="fa fa-minus-circle text-danger "></i> Не активный</a></li>';
        }

        if (data == "D")
        {
            titl = 'Не активный';
            icn = '<i class="fa fa-minus-circle text-danger font17"></i>';
            li = '<li><a href="javascript:void(0)" ' + cid + ' class="_changeStatus" change_to="E"><i class="fa fa-check-circle text-success "></i> Активный</a></li>';
        }

        st = st + '<div class="btn-group">' +
                    '<button title="' + titl + '" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">' +
                        icn + '&nbsp;<span class="caret"></span>' +
                    '</button>' +
                    '<ul style="width: auto;" class="dropdown-menu">' +
                        li +
                    '</ul></div>';
        return st;
    },

    // prepare actions of the table, calls after table loaded
    actionsPrepare: function (self, oSettings) {
        //var self = this;

        $('._editRecl').off('click');
        $('._editRecl').on('click', function (event) {
            reclsEdit.setElement($("#dialogEdit"));
            reclsEdit.render($(this).attr('rid'));
        });

        $('._balance').off('click');
        $('._balance').on('click', function (event) {
            reclsAccount.setElement($("#dialogAccount"));
            reclsAccount.render($(this).attr('rid'));
        });

        $('._changeStatus').off('click');
        $('._changeStatus').on('click', function (event) {
            SMP.makeRequest(
                '/recls/recls/changestatus',
                {
                    id: $(this).attr('rid'),
                    change_to: $(this).attr('change_to')
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        $('#recls').dataTable().fnDraw(false);
                    }
                }
            );
        });


        $('._expand').parents('tr').off('dblclick');
        $('._expand').parents('tr').on('dblclick', function (event) {
            var tr = this;
            var el = $(this).find('._expand');
            self.toggleDetails(tr, el);
        });

        $('._expand').off('click');
        $('._expand').on('click', function (event) {
            var tr = $(this).parents('tr')[0];
            self.toggleDetails(tr, this);

        });



        if (self.chart)
        {
            self.chart.setData($('#recls').dataTable().fnGetData());
        }
        else
        {
            self.chart = Morris.Bar({
                element: 'line-example',
                data: $('#recls').dataTable().fnGetData(),
                xkey: 'reclName',
                ykeys: ['onAccount', 'balance'],
                labels: ['On Account', 'Balance']
            });
        }


    },

    // hide or expand campaign details
    toggleDetails: function (tr, el){
        if ( $('#recls').dataTable().fnIsOpen(tr) )
        {
            /* This row is already open - close it */
            $('#recls').dataTable().fnClose( tr );
        }
        else
        {
            /* Open this row */
            // details - is class that will be on TD
            // some - is content
            SMP.makeRequest(
                '/recls/recls/expand',
                {
                    id: $(el).attr('rid')
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        $('#recls').dataTable().fnOpen( tr, data.html, 'details' );
                        if (typeof(reclsExpand[data.idRecl]) == "undefined")
                        {
                            reclsExpand[data.idRecl] = new ReclsExpand();
                        }
                        reclsExpand[data.idRecl].setElement($('#expandRid' + data.idRecl).get(0));
                        reclsExpand[data.idRecl].prepareView(data.idRecl);
                    }
                }
            );
        }
    },

    // redraw table according to current filter
    reloadTable: function (){
        $('#recls').dataTable().fnDraw();
    }

});

var usersView = [];
var campaignsView = [];
var settingsView = [];
var reclsExpand = [];

