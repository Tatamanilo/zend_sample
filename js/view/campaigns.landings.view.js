var LandingsView = Backbone.View.extend({
    prefix: '',

    events: {
        "click .addLanding": "addLanding",
        "click .editLanding": "editLanding",
        "click .changeStatus": "changeStatus",
        "click .changeForPrivate": "changeForPrivate",
        "click .viewUsers": "viewUsers"
    },

    addLanding: function () {
        landingsAdd.setElement($("#dialogAddLanding"));
        landingsAdd.render(this.idCampaign);
    },

    editLanding: function (event) {
        landingsEdit.setElement($("#dialogEditLanding"));
        landingsEdit.render(this.idCampaign, $(event.currentTarget).attr('lnid'));
    },

    viewUsers: function (event) {
        landingsUsers.setElement($("#dialogLandingUsers"));
        landingsUsers.render(this.idCampaign, $(event.currentTarget).attr('lnid'));
    },

    changeStatus: function (event) {
        var self = this;
        SMP.makeRequest(
            '/campaigns/landings/changestatus',
            {
                id: $(event.currentTarget).attr('lnid'),
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

    changeForPrivate: function (event) {
        var self = this;
        SMP.makeRequest(
            '/campaigns/landings/changeforprivate',
            {
                id: $(event.currentTarget).attr('lnid'),
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

    prepareView: function () {
        var self = this;

    },

    render: function (idCampaign) {
        var self = this;
        self.idCampaign = idCampaign;

        SMP.makeRequest(
            '/campaigns/landings/index',
            {
                id: idCampaign
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.html(data.html);
                    self.prepareView();
                    return self;
                }
            }
        );

    }
});


