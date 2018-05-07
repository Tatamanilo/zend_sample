var CampaignsView = Backbone.View.extend({
    prefix: '',
    idRecl: null,

    events: {
        "click .viewPayments": "viewPayments"
    },

    viewPayments: function (event) {
        reclsPayments.setElement($("#dialogPayments"));
        reclsPayments.render(this.idRecl, $(event.currentTarget).attr('cid'));
    },

    prepareView: function () {
    },

    render: function (idRecl) {
        var self = this;
        self.idRecl = idRecl;

        SMP.makeRequest(
            '/recls/campaigns/index',
            {
                id: self.idRecl
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


