var CommissionsView = Backbone.View.extend({
    prefix: '',
    idCampaign: null,

    events: {
        "click .cloneCommissionSection": "cloneCommissionSection",
        "click .addCommission": "addCommission",
        "click .editCommission": "editCommission",
        "click .changeStatus": "changeStatus",
        "click .changeApproveType": "changeApproveType",
        "click .viewUsers": "viewUsers",
        "click .viewPrices": "viewPrices"
    },

    cloneCommissionSection: function (event) {
        var self = this;
        SMP.makeRequest(
            '/campaigns/commissions/clonecommissionsection',
            {
                idCampaign: this.idCampaign,
                idCommissionSection: $(event.currentTarget).attr('csecid')
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.render(self.idCampaign, data.idCommissionSectionNew);
                }
            }
        );
    },

    addCommission: function (event) {
        commissionsAdd.setElement($("#dialogAddCommission"));
        commissionsAdd.render(this.idCampaign, $(event.currentTarget).attr('csecid'));
    },

    editCommission: function (event) {
        commissionsEdit.setElement($("#dialogEditCommission"));
        commissionsEdit.render(this.idCampaign, $(event.currentTarget).attr('csid'));
    },

    viewUsers: function (event) {
        commissionsUsers.setElement($("#dialogCommissionUsers"));
        commissionsUsers.render(this.idCampaign, $(event.currentTarget).attr('csecid'));
    },

    viewPrices: function (event) {
        commissionsPrices.setElement($("#dialogCommissionPrices"));
        commissionsPrices.render(this.idCampaign, $(event.currentTarget).attr('csid'));
    },

    changeStatus: function (event) {
        var self = this;
        SMP.makeRequest(
            '/campaigns/commissions/changestatus',
            {
                id: $(event.currentTarget).attr('csid'),
                change_to: $(event.currentTarget).attr('change_to')
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.render(self.idCampaign);
                }
            }
        );
    },

    changeApproveType: function (event) {
        var self = this;
        SMP.makeRequest(
            '/campaigns/commissions/changeapprovetype',
            {
                id: $(event.currentTarget).attr('csid'),
                change_to: $(event.currentTarget).attr('change_to')
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.render(self.idCampaign);
                }
            }
        );
    },

    prepareView: function (openSection) {
        var self = this;

        $("#commissions" + self.idCampaign).dataTable({
            "fnDrawCallback": function(){
                $("#csec" + openSection).parent("td").dblclick();
            },
            "sDom": "t",
            "aaSorting": [[ 3, "asc" ], [ 4, "asc" ], [ 5, "asc" ]]
        }).rowGrouping({
            iGroupingColumnIndex: 1,
            sGroupingColumnSortDirection: "asc",
            iGroupingOrderByColumnIndex: 0,
            bExpandableGrouping: true,
            fnOnGroupCreated: self.testGroup,
            asExpandedGroups: []

        });

        self.$el.find(".sectionName").editable(
            "/campaigns/commissions/editsectionname/",
            {
                "submitdata": function(value, settings) {
                    return {
                        "idCommissionSection": $(this).attr("csecid")
                    };
                },
                "callback" : function(value, settings) {
                    var json = $.parseJSON(value);
                    if (json.result)
                    {
                        $(this).text(json.value);
                    }
                    else
                    {
                        $(this).text("");
                        $.showErrorBox(json.errors);
                    }
                },
                "height": "14px",
                "type": "custom_input"
                //"onblur": "submit"
            }
        );

    },

    testGroup: function (oGroup, sGroup, iLevel) {
        var i=1;
    },

    render: function (idCampaign, openSection) {
        var self = this;
        self.idCampaign = idCampaign;

        SMP.makeRequest(
            '/campaigns/commissions/index',
            {
                id: self.idCampaign
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.html(data.html);
                    self.prepareView(openSection);
                    return self;
                }
            }
        );

    }
});


