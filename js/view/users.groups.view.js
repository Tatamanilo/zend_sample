var Groups = Backbone.View.extend({
    prefix: 'users',

    users: [],

    events: {
        "click #addGroup": "addGroup",
        "click ._showUsers": "showUsers",
        "click ._changeStatus": "changeStatus",
        "click ._editGroup": "editGroup",
        "click ._delGroup": "delGroup"
    },

    changeStatus: function (event) {
        var self = this;
        SMP.makeRequest(
            '/users/groups/changestatus',
            {
                id: $(event.currentTarget).attr('lnid'),
                change_to: $(event.currentTarget).attr('change_to')
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    $.showAjaxWait();
                    location.href = '/users/groups/index/';
                }
            }
        );
    },

    // main func, prepare page
    prepareView: function (){
        self = this;

        $('#dialogEdit').dialog({
            autoOpen: false,
            width: 700
        });

        $('#dialogAdd').dialog({
            autoOpen: false,
            width: 700,
            height: 600
        });

        //this.renderTable(userType);
    },


    // add group event
    addGroup: function (event) {
        groupsAdd.setElement($("#dialogAdd"));
        groupsAdd.render();
    },

    // add group event
    showUsers: function (event) {
        self = this;
        el = $(event.currentTarget);
        el.tooltip({
            open: function (event, ui) {
                ui.tooltip.css("max-width", "500px");
            },
            items: "span",
            content: function(callback) {
                if (typeof(self.users[$(el).attr("gid")]) == "undefined")
                {
                    SMP.makeRequest(
                        '/users/groups/showgroupusers',
                        {
                            uids: $(el).attr('uids')
                        },
                        'json',
                        function (data) {
                            if (SMP.isset(data.result) && (data.result == 1)) {
                                self.users[$(el).attr("gid")] = data.html;
                                callback(data.html);
                            }
                        }
                    );
                }
                else
                {
                    callback(self.users[$(el).attr("gid")]);
                }
            }
        }).tooltip("open").mouseout(function(){
            $(this).tooltip("disable");
        });
    },


    // edit group event
    editGroup: function (event) {
        groupsEdit.setElement($("#dialogEdit"));
        groupsEdit.render($(event.currentTarget).attr("gid"));
    },

    // del group event
    delGroup: function (event) {
        if (confirm("Вы действительно хотите удалить группу?")) {
            SMP.makeRequest(
                '/users/groups/delete',
                {
                    id: $(event.currentTarget).attr('gid')
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        $.showAjaxWait();
                        location.href = '/users/groups/index/';
                    }
                }
            );
        }
    },



    // redraw table according to current filter
    reloadTable: function (){

        $('#users').dataTable().fnDraw();
    }

});
