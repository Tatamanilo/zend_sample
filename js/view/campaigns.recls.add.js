var ReclsAdd = Backbone.View.extend({
    prefix: 'reclsAdd',
    useDialog: false,
    idCampaign: null,

    events: {
        "submit #reclsAddForm": "submit"
    },

    submit: function (event) {
        var self = this;
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/campaigns/recls/add',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.dialog('close');
                    reclsView[self.idCampaign].render(self.idCampaign);
                }
            },
            ''
        );
    },


    prepareView: function () {
        var self = this;
        $("#reclNameAdd").autocomplete({
            source: '/campaigns/recls/reclslist',
            minLength: 1,
            change: function (event, ui) {
                if(!ui.item){
                    $("#reclNameAdd").val("");
                    $("#idReclAdd").val("");
                }
                else
                {
                    $("#idReclAdd").val(ui.item.id);
                }

            }

        });
    },


    render: function (idCampaign) {
        var self = this;
        self.idCampaign = idCampaign;

        SMP.makeRequest(
            '/campaigns/recls/add',
            {
                id: self.idCampaign
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.html(data.html);
                    self.$el.dialog('open');
                    self.prepareView();
                    return self;
                }
            }
        );
    }
});

reclsAdd = new ReclsAdd();
