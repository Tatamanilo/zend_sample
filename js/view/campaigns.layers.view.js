var LayersView = Backbone.View.extend({
    prefix: '',

    events: {
        "click .addLayer": "addLayer",
        "click .editLayer": "editLayer",
        "click .changeStatus": "changeStatus",
        "click .changeForPrivate": "changeForPrivate",
        "click .viewUsers": "viewUsers"
    },

    addLayer: function () {
        layersAdd.setElement($("#dialogAddLayer"));
        layersAdd.render(this.idCampaign);
    },

    editLayer: function (event) {
        layersEdit.setElement($("#dialogEditLayer"));
        layersEdit.render(this.idCampaign, $(event.currentTarget).attr('lid'));
    },

    viewUsers: function (event) {
        layersUsers.setElement($("#dialogLayerUsers"));
        layersUsers.render(this.idCampaign, $(event.currentTarget).attr('lid'));
    },

    changeStatus: function (event) {
        var self = this;
        SMP.makeRequest(
            '/campaigns/layers/changestatus',
            {
                id: $(event.currentTarget).attr('lid'),
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
            '/campaigns/layers/changeforprivate',
            {
                id: $(event.currentTarget).attr('lid'),
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
            '/campaigns/layers/index',
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


