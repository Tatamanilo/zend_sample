var ReclsView = Backbone.View.extend({
    prefix: '',
    idCampaign: null,

    events: {
        "click .addRecl": "addRecl"

    },

    addRecl: function () {
        reclsAdd.setElement($("#dialogAddRecl"));
        reclsAdd.render(this.idCampaign);
    },

    prepareView: function () {
        var self = this;
        self.$el.find(".editTransCountPerDay").editable(
            "/campaigns/recls/edittranscount/",
            {
                "submitdata": function(value, settings) {
                    return {
                        "rcid": $(this).attr("rcid")
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
                "type": "custom_input",
                "onblur": "submit"
            }
        );
    },
    render: function (idCampaign) {
        var self = this;
        self.idCampaign = idCampaign;

        SMP.makeRequest(
            '/campaigns/recls/index',
            {
                id: self.idCampaign
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


