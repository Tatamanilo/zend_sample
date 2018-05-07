var LandingsEdit = Backbone.View.extend({
    prefix: 'landingsEdit',
    useDialog: false,
    idCampaign: null,
    idLanding: null,

    events: {
        "submit #landingsEditForm": "submit",
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
                '/campaigns/landings/recalcepc',
                {
                    idLanding: self.idLanding
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
            '/campaigns/landings/edit',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.dialog('close');
                    landingsView[self.idCampaign].render(self.idCampaign);
                }
            },
            ''
        );
    },

    prepareView: function () {
        var self = this;

    },


    render: function (idCampaign, idLanding) {
        var self = this;
        self.idCampaign = idCampaign;
        self.idLanding = idLanding;

        SMP.makeRequest(
            '/campaigns/landings/edit',
            {
                id: idLanding
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

landingsEdit = new LandingsEdit();
