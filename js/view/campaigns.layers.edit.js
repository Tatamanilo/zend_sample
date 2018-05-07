var LayersEdit = Backbone.View.extend({
    prefix: 'layersEdit',
    useDialog: false,
    idCampaign: null,
    idLayer: null,

    events: {
        "submit #layersEditForm": "submit",
        "click .epcChangeBtn": "changeEpc"
    },

    changeEpc: function (event) {
        var self = this;

        if ($(event.currentTarget).attr("val") == "new")
        {
            $("#epcIsNew").val(1);
            $("#epcDiv").hide();

            $(".epcChangeBtn").removeClass("active");
            $(event.currentTarget).addClass("active");
        }

        if ($(event.currentTarget).attr("val") == "recalc")
        {
            SMP.makeRequest(
                '/campaigns/layers/recalcepc',
                {
                    idLayer: self.idLayer
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        $("#epcIsNew").val(0);
                        $("#epcToday").val(data.epcToday);
                        $("#epcYesterday").val(data.epcYesterday);
                        $("#epcWeek").val(data.epcWeek);
                        $("#epcAll").val(data.epcAll);
                        $("#epcDiv").show();

                        $(".epcChangeBtn").removeClass("active");
                        $(event.currentTarget).addClass("active");
                    }
                }
            );
        }
    },

    submit: function (event) {
        var self = this;
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/campaigns/layers/edit',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.dialog('close');
                    layersView[self.idCampaign].render(self.idCampaign);
                }
            },
            ''
        );
    },

    prepareView: function () {
        var self = this;

    },


    render: function (idCampaign, idLayer) {
        var self = this;
        self.idCampaign = idCampaign;
        self.idLayer = idLayer;

        SMP.makeRequest(
            '/campaigns/layers/edit',
            {
                id: idLayer
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

layersEdit = new LayersEdit();
