var StatsView = Backbone.View.extend({
    prefix: 'stats',

    table: null,
    tableUsers: null,
    lineChart: null,
    //barChart: null,
    barChartMoney: null,
    barChartUsers: null,
    barChartMoneyUsers: null,
    chartKey: "clicksCount",
    currTableId: null,

    events: {
        "click #lineChartKey input": "lineChartKeyChange",
        "click #barChartKeyUsers input": "barChartKeyUsersChange",
        "click .groupbyChangeBtn": "groupbyChange",
        "click .periodChangeBtn": "periodChange",
        "submit #statsSearchForm": "searchSubmit"
    },

    // prepare view events
    lineChartKeyChange: function (event) {
        this.redrawLineChart();
    },

    // prepare view events
    barChartKeyUsersChange: function (event) {
        this.redrawBarChartUsers();
    },

    // prepare view events
    periodChange: function (event) {
        event.preventDefault();
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
    groupbyChange: function (event) {
        //event.preventDefault();
        //$(".groupbyChangeBtn").removeClass("active");
        //$(event.currentTarget).addClass("active");
        $("#search_groupby").val($(event.currentTarget).attr('groupby'));

        $("#groupByTh").html($(event.currentTarget).attr("thtitle"));

        if ($(event.currentTarget).attr('groupby') == "affs")
        {
            if (StatsView.tableUsers)
            {
                this.reloadTableUsers();
            }
            else
            {
                this.renderTableUsers();
            }
        }
        else
        {
            this.reloadTable();
        }

    },

    // filter form submit
    searchSubmit: function (event) {
        event.preventDefault();
        this.reloadTable();
    },

    // main func, prepare page
    prepareView: function (){
        self = this;

        $("#search_groupby").val("days");
        $("#search_affName")
        // don't navigate away from the field on tab when selecting an item
        .bind( "keydown", function( event ) {
            if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
                event.preventDefault();
            }
        })
        .autocomplete({
            source: "/users/users/affsearchlist/activeOnly/0/",
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            change: function (event, ui) {
                if(!ui.item){
                    $("#search_affName").val("");
                    $("#search_idAff").val("");
                }
                else
                {
                    $("#search_idAff").val(ui.item.id);
                }
            },
            select: function( event, ui ) {
                $("#search_idAff").val(ui.item.id);
                this.value = ui.item.login + "(" + ui.item.name + ") - " + ui.item.userRef;
                return false;
            }
        }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
            var cl = "";
            if (item.status != "active")
            {
                cl = "class='text-muted'";
            }
            return $( "<li " + cl + ">" )
                .append( "<a>" + item.login + "(" + item.name + ") - " + item.userRef + "</a>" )
                .appendTo( ul );
        };

        $("#search_reclName").autocomplete({
            source: '/recls/recls/reclslist/activeOnly/0/',
            change: function (event, ui) {
                if(!ui.item){
                    $("#search_reclName").val("");
                    $("#search_idRecl").val("");
                }
                else
                {
                    $("#search_idRecl").val(ui.item.id);
                }
                $("#search_campaignName").autocomplete( "option", "source", '/campaigns/campaigns/campaignslist/idRecl/' + $("#search_idRecl").val());
            }
        }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
            var cl = "";
            if (item.reclStatus != "E")
            {
                cl = "class='text-muted'";
            }
            return $( "<li " + cl + ">" )
                .append( "<a>" + item.value + "</a>" )
                .appendTo( ul );
        };


        $("#search_campaignName").autocomplete({
            source: '/campaigns/campaigns/campaignslist/idRecl/' + $("#search_idRecl").val(),
            select: function (event, ui) {
                if(!ui.item){
                    //http://api.jqueryui.com/autocomplete/#event-change -
                    // The item selected from the menu, if any. Otherwise the property is null
                    //so clear the item for force selection
                    $("#search_campaignName").val("");
                    $("#search_idCampaign").val("");

                    $("#search_idLanding").html("");
                    $("#search_idLayer").html("");
                }
                else
                {
                    $("#search_idCampaign").val(ui.item.id);
                    self.reloadLandingFilter();
                    self.reloadLayerFilter();
                }

            },
            change: function (event, ui) {
                if(!ui.item){
                    //http://api.jqueryui.com/autocomplete/#event-change -
                    // The item selected from the menu, if any. Otherwise the property is null
                    //so clear the item for force selection
                    $("#search_campaignName").val("");
                    $("#search_idCampaign").val("");

                    $("#search_idLanding").html("");
                    $("#search_idLayer").html("");
                }
            }
        }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
            var cl = "";
            if (item.campaignStatus != "E")
            {
                cl = "class='text-muted'";
            }
            return $( "<li " + cl + ">" )
                .append( "<a>" + item.value + "</a>" )
                .appendTo( ul );
        };

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

         $("#lineChartKey").buttonset();
         $("#barChartKeyUsers").buttonset();

         this.renderTable();
    },

    reloadLandingFilter: function () {
        $("#search_idLanding").html("");
        SMP.makeRequest(
            '/campaigns/landings/landingslist',
            {
                idCampaign: $("#search_idCampaign").val()
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    $("#search_idLanding").append('<option value=""></option>')
                    $.each(data.items, function (index, item) {
                        $("#search_idLanding").append('<option value="' + item.idLanding + '">' + item.landingName + '</option>')
                    });
                }
            }
        );
    },

    reloadLayerFilter: function () {
        $("#search_idLayer").html("");
        SMP.makeRequest(
            '/campaigns/layers/layerslist',
            {
                idCampaign: $("#search_idCampaign").val()
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    $("#search_idLayer").append('<option value=""></option>')
                    $.each(data.items, function (index, item) {
                        $("#search_idLayer").append('<option value="' + item.idLayer + '">' + item.layerName + '</option>')
                    });
                }
            }
        );
    },

    // render table stucture and data
    renderTable: function () {
        var self = this;
        var url = '';
        var colModel = [];
        var caption = '';
        url = '/stats/stats/stats';
        aoColumns = [
            {
                'mData': 'groupByCol',
                'sName': 'groupByCol'
            },
            {
                'mData': 'clicksCount',
                'sName': 'clicksCount'
            },
            {
                'mData': 'uniqClicksCount',
                'sName': 'uniqClicksCount'
            },
            {
                'mData': 'transCountAll',
                'sName': 'transCountAll'
            },
            {
                'mData': 'transCountA',
                'sName': 'transCountA'
            },
            {
                'mData': 'transCountP',
                'sName': 'transCountP'
            },
            {
                'mData': 'transCountD',
                'sName': 'transCountD'
            },
            {
                'mData': 'epc',
                'sName': 'epc'
            },
            {
                'mData': 'cr',
                'sName': 'cr'
            },
            {
                'mData': 'crP',
                'sName': 'crP'
            },
            {
                'mData': 'approvedPerc',
                'sName': 'approvedPerc'
            },
            {
                'mData': 'approvedWithPendPerc',
                'sName': 'approvedWithPendPerc'
            },
            {
                'mData': 'commissionSumA',
                'sName': 'commissionSumA'
            }
        ];
        caption = 'Статистика';

        self.currTableId = "stats";
        self.table = $('#stats').dataTable( {
            "fnInitComplete": function(oSettings){self.actionsPrepare(self, oSettings);},
            "aoColumns": aoColumns,
            "aaSorting": [[0, "asc"]],
            "bProcessing": false,
            "bPaginate": false,
            "bLengthChange": false,
            "bDestroy": true,
            "bFilter": false,
            "bInfo": false,
            "sDom": "frt<ip>",
            "bServerSide": false,
            "sAjaxSource": url,
            "sServerMethod": "POST",
            "fnServerData": SMP.makeRequestDT
        } );


    },

    // render table stucture and data
    renderTableUsers: function () {
        var self = this;
        var url = '';
        var colModel = [];
        var caption = '';
        url = '/stats/stats/statsaffs';
        aoColumns = [
            {
                'mData': 'groupByCol',
                'sName': 'groupByCol'
            },
            {
                'mData': 'clicksCount',
                'sName': 'clicksCount'
            },
            {
                'mData': 'uniqClicksCount',
                'sName': 'uniqClicksCount'
            },
            {
                'mData': 'transCountAll',
                'sName': 'transCountAll'
            },
            {
                'mData': 'transCountA',
                'sName': 'transCountA'
            },
            {
                'mData': 'transCountP',
                'sName': 'transCountP'
            },
            {
                'mData': 'transCountD',
                'sName': 'transCountD'
            },
            {
                'mData': 'epc',
                'sName': 'epc'
            },
            {
                'mData': 'cr',
                'sName': 'cr'
            },
            {
                'mData': 'crP',
                'sName': 'crP'
            },
            {
                'mData': 'approvedPerc',
                'sName': 'approvedPerc'
            },
            {
                'mData': 'approvedWithPendPerc',
                'sName': 'approvedWithPendPerc'
            },
            {
                'mData': 'commissionSumA',
                'sName': 'commissionSumA'
            }
        ];
        caption = 'Статистика';

        self.currTableId = "statsUsers";
        self.tableUsers = $('#statsUsers').dataTable( {
            "fnDrawCallback": function(oSettings){self.actionsPrepareUsers(self, oSettings);},
            "aoColumns": aoColumns,
            "aaSorting": [[2, "desc"]],
            "sPaginationType": "bootstrap",
            "bLengthChange": true,
            "aLengthMenu": [20, 50, 100, 200, 500],
            "iDisplayLength": 20,
            "bProcessing": true,
            "bPaginate": true,
            "bDestroy": true,
            "bFilter": false,
            "bInfo": false,
            "sDom": "frt<Li>",
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

    },

    // prepare actions of the table, calls after table loaded
    actionsPrepare: function (self, oSettings) {
        self.redrawLineChart();
        self.redrawBarChartMoney();
    },

    // prepare actions of the table, calls after table loaded
    actionsPrepareUsers: function (self, oSettings) {
        self.redrawBarChartUsers();
        self.redrawBarChartMoneyUsers();
    },

    // redraw line chart
    redrawLineChart: function (){
        if (self.lineChart)
        {
            self.lineChart.options.ykeys = [$("#lineChartKey :radio:checked").val()];
            self.lineChart.options.labels = [$("#lineChartKey :radio:checked + label").text()];

            self.lineChart.setData($('#' + self.currTableId).dataTable().fnGetData());
        }
        else
        {
            self.lineChart = Morris.Line({
                element: 'lineChart',
                //xLabelFormat: function (y) { return ''; },
                xLabelAngle: 90,
                data: $('#' + self.currTableId).dataTable().fnGetData(),
                xkey: 'groupByCol',
                ykeys: [$("#lineChartKey :radio:checked").val()],
                labels: [$("#lineChartKey :radio:checked + label").text()]
            });
        }


    },

    // redraw line chart
    redrawBarChartMoney: function (){
        if (self.barChartMoney)
        {
            self.barChartMoney.setData($('#' + self.currTableId).dataTable().fnGetData());
        }
        else
        {
            self.barChartMoney = Morris.Bar({
                element: 'barChartMoney',
                xLabelAngle: 90,
                hoverCallback: function (index, options, content, row) {
                    return "Должны аффам " + row.commissionSumA + "руб.";
                },
                data: $('#' + self.currTableId).dataTable().fnGetData(),
                xkey: 'groupByCol',
                ykeys: ["commissionSumA"],
                labels: ["Должны аффам"]
            });
        }
    },

    // redraw line chart
    redrawBarChartUsers: function (){
        if (self.barChartUsers)
        {
            self.barChartUsers.options.ykeys = [$("#barChartKeyUsers :radio:checked").val()];
            self.barChartUsers.options.labels = [$("#barChartKeyUsers :radio:checked + label").text()];

            self.barChartUsers.setData($('#' + self.currTableId).dataTable().fnGetData());
        }
        else
        {
            self.barChartUsers = Morris.Bar({
                element: 'barChartUsers',
                xLabelFormat: function (y) { return ''; },
                //xLabelAngle: 90,
                hoverCallback: function (index, options, content, row) {
                    return row.groupByCol + " - " + row[options.ykeys[0]];
                },
                data: $('#' + self.currTableId).dataTable().fnGetData(),
                xkey: 'groupByCol',
                ykeys: [$("#barChartKeyUsers :radio:checked").val()],
                labels: [$("#barChartKeyUsers :radio:checked + label").text()]
            });
        }


    },

    // redraw line chart
    redrawBarChartMoneyUsers: function (){
        if (self.barChartMoneyUsers)
        {
            self.barChartMoneyUsers.setData($('#' + self.currTableId).dataTable().fnGetData());
        }
        else
        {
            self.barChartMoneyUsers = Morris.Bar({
                element: 'barChartMoneyUsers',
                xLabelFormat: function (y) { return ''; },
                hoverCallback: function (index, options, content, row) {
                    return "Должны аффу " + row.groupByCol + " " + row.commissionSumA + "руб.";
                },
                data: $('#' + self.currTableId).dataTable().fnGetData(),
                xkey: 'groupByCol',
                ykeys: ["commissionSumA"],
                labels: ["Должны аффам"]
            });
        }
    },

    // redraw table according to current filter
    reloadTable: function (){
        $('#stats').dataTable().fnReloadAjax();
    },

    // redraw table according to current filter
    reloadTableUsers: function (){
        $('#statsUsers').dataTable().fnDraw();
    }

});
