var CampaignsExpand = Backbone.View.extend({
    prefix: '',

    events: {
        "click .tabs": "changeTab",
        "click .hideCampaignDetailsBtn": "hideCampaignDetails"

    },

    hideCampaignDetails: function (event) {
        var cid = $(event.currentTarget).attr("cid");

        var trExp = ($(event.currentTarget).parents('tr')[0]);
        $('#campaigns').dataTable().fnClose( $(trExp).prev()[0] );
    },

    changeTab: function (event) {
        var tab = $(event.currentTarget).attr("tab");
        var cid = $(event.currentTarget).attr("cid");
        this.loadTabContent(tab, cid);
    },

    loadTabContent: function (tab, idCampaign) {
        if (tab == "landings")
        {
            if (typeof(landingsView[idCampaign]) == "undefined")
            {
                landingsView[idCampaign] = new LandingsView();
            }
            landingsView[idCampaign].setElement($('#landingsCid' + idCampaign).get(0));
            landingsView[idCampaign].render(idCampaign);
        }

        if (tab == "layers")
        {
            if (typeof(layersView[idCampaign]) == "undefined")
            {
                layersView[idCampaign] = new LayersView();
            }
            layersView[idCampaign].setElement($('#layersCid' + idCampaign).get(0));
            layersView[idCampaign].render(idCampaign);
        }

        if (tab == "commissions")
        {
            if (typeof(commissionsView[idCampaign]) == "undefined")
            {
                commissionsView[idCampaign] = new CommissionsView();
            }
            commissionsView[idCampaign].setElement($('#commissionsCid' + idCampaign).get(0));
            commissionsView[idCampaign].render(idCampaign);
        }

        if (tab == "recls")
        {
            if (typeof(reclsView[idCampaign]) == "undefined")
            {
                reclsView[idCampaign] = new ReclsView();
            }
            reclsView[idCampaign].setElement($('#reclsCid' + idCampaign).get(0));
            reclsView[idCampaign].render(idCampaign);
        }
    },
    prepareView: function (idCampaign) {

        var tab = $(".tabs" + idCampaign + ".active").attr("tab");

        this.loadTabContent(tab, idCampaign);
    }
});

