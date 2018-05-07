var LandingsAdd = Backbone.View.extend({
    prefix: 'landingsAdd',
    useDialog: false,
    idCampaign: null,

    events: {
        "submit #landingsAddForm": "submit",
        "click .epcChangeBtn": "changeEpc"
    },

    changeEpc: function (event) {
        $(".epcChangeBtn").removeClass("active");
        $(event.currentTarget).addClass("active");
        if ($(event.currentTarget).attr("val") == "new")
        {
            $("#epcIsNew").val(1);
            $("#epcDiv").hide();
        }
        if ($(event.currentTarget).attr("val") == "recalc")
        {
            $("#epcIsNew").val(0);
            $("#epcToday").val("0.00");
            $("#epcYesterday").val("0.00");
            $("#epcWeek").val("0.00");
            $("#epcAll").val("0.00");
            $("#epcDiv").show();
        }
    },

    submit: function (event) {
        var self = this;
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/campaigns/landings/add',
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


    render: function (idCampaign) {
        var self = this;
        self.idCampaign = idCampaign;

        SMP.makeRequest(
            '/campaigns/landings/add',
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

landingsAdd = new LandingsAdd();
