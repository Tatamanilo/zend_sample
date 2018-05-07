var CampaignsView = Backbone.View.extend({
    prefix: 'users',

    table: null,

    events: {
        "submit #campaignsSearchForm": "searchSubmit",
        "click #addCampaign": "addCampaign",
    },

    // prepare view events
    addCampaign: function (event) {
        campaignsAdd.setElement($("#dialogAdd"));
        campaignsAdd.render();

    },

    // filter form submit
    searchSubmit: function (event) {
        event.preventDefault();
        this.reloadTable();
    },

    split: function ( val ) {
        return val.split( /,\s*/ );
    },

    extractLast: function( term ) {
        return this.split( term ).pop();
    },

    // main func, prepare page
    prepareView: function (){
        var self = this;

        $('#dialogEdit').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            maxHeight: $(window).height() - 80,
            width: 900
        });

        $('#dialogAdd').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            maxHeight: $(window).height() - 80,
            width: 900
        });

        $('#dialogCampaignUsers').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            maxHeight: $(window).height() - 80,
            width: 650
        });

        $('#dialogAddRecl').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            maxHeight: $(window).height() - 80,
            width: 600
        });

        $('#dialogAddCommission').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            maxHeight: $(window).height() - 80,
            width: 600
        });

        $('#dialogEditCommission').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            width: 600
        });

        $('#dialogCommissionUsers').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            maxHeight: $(window).height() - 80,
            width: 650
        });

        $('#dialogCommissionPrices').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            maxHeight: $(window).height() - 80,
            width: 850
        });

        $('#dialogAddLanding').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            maxHeight: $(window).height() - 80,
            width: 600
        });

        $('#dialogEditLanding').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            width: 600
        });

        $('#dialogLandingUsers').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            maxHeight: $(window).height() - 80,
            width: 650
        });

        $('#dialogAddLayer').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            maxHeight: $(window).height() - 80,
            width: 600
        });

        $('#dialogEditLayer').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            width: 600
        });

        $('#dialogLayerUsers').dialog({
            close: function( event, ui ) {
                $(this).html("");
            },
            autoOpen: false,
            maxHeight: $(window).height() - 80,
            width: 650
        });

        $("#search_campaignName").autocomplete({
            source: '/campaigns/campaigns/campaignslist',
            change: function (event, ui) {
                if(!ui.item){
                    //http://api.jqueryui.com/autocomplete/#event-change -
                    // The item selected from the menu, if any. Otherwise the property is null
                    //so clear the item for force selection
                    $("#search_campaignName").val("");
                    $("#search_idCampaign").val("");
                }
                else
                {
                    $("#search_idCampaign").val(ui.item.id);
                }

            }

        });

        $("#search_reclName").autocomplete({
            source: '/recls/recls/reclslist',
            minLength: 1,
            change: function (event, ui) {
                if(!ui.item){
                    //http://api.jqueryui.com/autocomplete/#event-change -
                    // The item selected from the menu, if any. Otherwise the property is null
                    //so clear the item for force selection
                    $("#search_reclName").val("");
                    $("#search_idRecl").val("");
                }
                else
                {
                    $("#search_idRecl").val(ui.item.id);
                }

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

        $("#search_countries").chosen();

        self.renderTable();
    },

    // render table stucture and data
    renderTable: function () {
        var self = this;
        var url = '';
        var colModel = [];
        var caption = '';
        url = '/campaigns/campaigns/campaigns';
        aoColumns = [
            {
                'bSortable': false,
                'mData': 'logo' ,
                'mRender': this.logoFormatter
            },
            { 'mData': 'idCampaign' },
            {
                'mData': 'campaignName',
                'sName': 'campaignName',
            },
            {
                'bSortable': true,
                'mData': 'countries' ,
                'sName': 'countries',
                'mRender': this.countriesFormatter
            },
            { 'mData': 'priceOnLanding' },
            { 'mData': null },
            {
                'bSortable': false,
                'mData': 'campaignStatus' ,
                'mRender': this.statusFormatter
            },
            {
                'bSortable': false,
                'mData': null,
                'mRender': this.actionsFormatter
            }
        ];
        caption = 'Офферы';

        CampaignsView.table = $('#campaigns').dataTable( {
            "fnDrawCallback": function(){self.actionsPrepare(self);},
            "aoColumns": aoColumns,
            "aaSorting": [] ,
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
        if (row.campaignType == 'R')
        {
            disabled = 'disabled';
        }
        var hide = '';
        if (row.campaignType != 'R')
        {
            hide = 'hide';
        }
        var acts = '<button class="_expand btn btn-default" role="1" cid="' + row.idCampaign + '" title="Детали"><i class="fa fa-info"></i></button>' +
                '<button class="_editCampaign btn btn-default" role="1" cid="' + row.idCampaign + '" title="Редакт."><i class="fa fa-pencil"></i></button>' +
                '<button class="_cloneCampaign btn btn-default" role="1" cid="' + row.idCampaign + '" title="Клонировать оффер"><i class="fa fa-files-o"></i></button>' +
                '<button class="_campaignUsers btn btn-default" role="1" cid="' + row.idCampaign + '" title="Пользователи (аффы) оффера"><i class="fa fa-users"></i></button>' +
                '';
        return acts;
        //if (row.)
        //'<button class="_expand btn btn-default" role="1" cid="' + row.idCampaign + '" title="Раскрыть."><i class="fa fa-plus-circle"></i></button>'
    },

    // formatter for status column in the table
    countriesFormatter: function (data, type, row) {
        if (data)
            return '<div style="cursor: pointer;" class="geo-tooltip" cid="' + row.idCampaign + '" title="">' + data +
            '&nbsp;<i class="fa fa-info-circle text-info font17"></i></div>';
        else
            return '';
    },

    // formatter for status column in the table
    logoFormatter: function (data, type, row) {
        if (data)
            return '<img src="' + offersUrl + data +'" height="50px" />';
        else
            return '';
    },

    // formatter for status column in the table
    statusFormatter: function (data, type, row) {
        var st = '';
        var icn = '';
        var li = '';
        var titl = '';
        var cid = 'cid="' + row.idCampaign + '"';
        if (row.campaignStatus == "E")
        {
            titl = 'Активный';
            icn = '<i class="fa fa-check-circle text-success font17"></i>';
            li = '<li><a href="javascript:void(0)" ' + cid + ' class="_changeStatus" change_to="D"><i class="fa fa-minus-circle text-danger "></i> Не активный</a></li>';
        }

        if (row.campaignStatus == "D")
        {
            titl = 'Не активный';
            icn = '<i class="fa fa-minus-circle font17"></i>';
            li = '<li><a href="javascript:void(0)" ' + cid + ' class="_changeStatus" change_to="E"><i class="fa fa-check-circle text-success "></i> Активный</a></li>';
        }

        st = st + '<div class="btn-group">' +
                    '<button title="' + titl + '" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">' +
                        icn + '&nbsp;<span class="caret"></span>' +
                    '</button>' +
                    '<ul style="width: auto;" class="dropdown-menu">' +
                        li +
                    '</ul></div>';


        // ===== type ===========
        var icn = '';
        var li = '';
        var titl = '';
        if (row.campaignType == "P")
        {
            titl = 'Публичный';
            icn = '<i class="fa fa-eye text-success font17" title="Публичный"></i>';
            li = '<li><a href="javascript:void(0)" ' + cid + ' class="_changeType" change_to="R"><i class="fa fa-lock text-info "></i> Приватный</a></li>' +
                '<li><a href="javascript:void(0)" ' + cid + ' class="_changeType" change_to="I"><i class="fa fa-eye-slash "></i> Невидимый</a></li>';
        }

        if (row.campaignType == "R")
        {
            titl = 'Приватный';
            icn = '<i class="fa fa-lock text-info font17" title="Приватный"></i>';
            li = '<li><a href="javascript:void(0)" ' + cid + ' class="_changeType" change_to="P"><i class="fa fa-eye text-success "></i> Публичный</a></li>' +
                '<li><a href="javascript:void(0)" ' + cid + ' class="_changeType" change_to="I"><i class="fa fa-eye-slash "></i> Невидимый</a></li>';
        }

        if (row.campaignType == "I")
        {
            titl = 'Невидимый';
            icn = '<i class="fa fa-eye-slash font17" title="Невидимый"></i>';
            li = '<li><a href="javascript:void(0)" ' + cid + ' class="_changeType" change_to="P"><i class="fa fa-eye text-success "></i> Публичный</a></li>' +
                '<li><a href="javascript:void(0)" ' + cid + ' class="_changeType" change_to="R"><i class="fa fa-lock text-info "></i> Приватный</a></li>';
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
    actionsPrepare: function (self) {
        $('._editCampaign').off('click');
        $('._editCampaign').on('click', function (event) {
            campaignsEdit.setElement($("#dialogEdit"));
            campaignsEdit.render($(this).attr('cid'));
        });

        $('._changeStatus').off('click');
        $('._changeStatus').on('click', function (event) {
            SMP.makeRequest(
                '/campaigns/campaigns/changestatus',
                {
                    id: $(this).attr('cid'),
                    change_to: $(this).attr('change_to')
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        $('#campaigns').dataTable().fnDraw(false);
                    }
                }
            );
        });

        $('._changeType').off('click');
        $('._changeType').on('click', function (event) {
            SMP.makeRequest(
                '/campaigns/campaigns/changetype',
                {
                    id: $(this).attr('cid'),
                    change_to: $(this).attr('change_to')
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        $('#campaigns').dataTable().fnDraw(false);
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

        $('._cloneCampaign').off('click');
        $('._cloneCampaign').on('click', function (event) {
            alert("Clone campaign logic");
        });


        $('._campaignUsers').off('click');
        $('._campaignUsers').on('click', function (event) {
            campaignsUsers.setElement($("#dialogCampaignUsers"));
            campaignsUsers.render($(event.currentTarget).attr('cid'));
        });

        $('.geo-tooltip').click(function() {
            self_geo = this;
            $(this).tooltip({
                open: function (event, ui) {
                    ui.tooltip.css("max-width", "500px");
                },
                items: "div",
                content: function(callback) {
                    if (typeof(geo[$(self_geo).attr("cid")]) == "undefined")
                    {
                        SMP.makeRequest(
                            '/campaigns/commissions/geoinfo',
                            {
                                id: $(self_geo).attr('cid')
                            },
                            'json',
                            function (data) {
                                if (SMP.isset(data.result) && (data.result == 1)) {
                                    geo[$(self_geo).attr("cid")] = data.html;
                                    callback(data.html);
                                }
                            }
                        );
                    }
                    else
                    {
                        callback(geo[$(self_geo).attr("cid")]);
                    }
                }
            }).tooltip("open").mouseout(function(){
                $(this).tooltip("disable");
            });
        });
    },

    // hide or expand campaign details
    toggleDetails: function (tr, el){
        if ( $('#campaigns').dataTable().fnIsOpen(tr) )
        {
            /* This row is already open - close it */
            $('#campaigns').dataTable().fnClose( tr );
        }
        else
        {
            /* Open this row */
            // details - is class that will be on TD
            // some - is content
            SMP.makeRequest(
                '/campaigns/campaigns/expand',
                {
                    id: $(el).attr('cid')
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        $('#campaigns').dataTable().fnOpen( tr, data.html, 'details' );
                        if (typeof(campaignsExpand[data.idCampaign]) == "undefined")
                        {
                            campaignsExpand[data.idCampaign] = new CampaignsExpand();
                        }
                        campaignsExpand[data.idCampaign].setElement($('#expandCid' + data.idCampaign).get(0));
                        campaignsExpand[data.idCampaign].prepareView(data.idCampaign);
                    }
                }
            );
        }
    },

    // redraw table according to current filter
    reloadTable: function (){
        $('#campaigns').dataTable().fnDraw();
    }

});

var geo = [];
var reclsView = [];
var landingsView = [];
var layersView = [];
var commissionsView = [];
var campaignsExpand = [];

