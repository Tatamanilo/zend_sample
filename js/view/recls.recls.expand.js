var ReclsExpand = Backbone.View.extend({
    prefix: '',

    events: {
        "click .tabs": "changeTab",
        "click .hideReclDetailsBtn": "hideReclDetails"

    },

    hideReclDetails: function (event) {
        var rid = $(event.currentTarget).attr("rid");

        var trExp = ($(event.currentTarget).parents('tr')[0]);
        $('#recls').dataTable().fnClose( $(trExp).prev()[0] );
    },

    changeTab: function (event) {
        var tab = $(event.currentTarget).attr("tab");
        var rid = $(event.currentTarget).attr("rid");
        this.loadTabContent(tab, rid);
    },

    loadTabContent: function (tab, idRecl) {
        if (tab == "campaigns")
        {
            if (typeof(campaignsView[idRecl]) == "undefined")
            {
                campaignsView[idRecl] = new CampaignsView();
            }
            campaignsView[idRecl].setElement($('#campaignsRid' + idRecl).get(0));
            campaignsView[idRecl].render(idRecl);
        }

        if (tab == "users")
        {
            if (typeof(usersView[idRecl]) == "undefined")
            {
                usersView[idRecl] = new UsersView();
            }
            usersView[idRecl].setElement($('#usersRid' + idRecl).get(0));
            usersView[idRecl].render(idRecl);
        }

        if (tab == "settings")
        {
            if (typeof(settingsView[idRecl]) == "undefined")
            {
                settingsView[idRecl] = new SettingsView();
            }
            settingsView[idRecl].setElement($('#settingsRid' + idRecl).get(0));
            settingsView[idRecl].render(idRecl);
        }
    },
    prepareView: function (idRecl) {

        var tab = $(".tabs" + idRecl + ".active").attr("tab");

        this.loadTabContent(tab, idRecl);
    }
});

