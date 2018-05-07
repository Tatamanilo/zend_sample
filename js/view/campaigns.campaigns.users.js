var CampaignsUsers = Backbone.View.extend({
    prefix: 'groupsEdit',
    useDialog: true,
    idCampaign: null,
    users: [],
    events: {
        "click .addUser": "addUser",
        "click .addUsersGroup": "addUsersGroup",
        "click .viewUser": "viewUser",
        "click .delUser": "deleteUser",
        "click .delGroup": "deleteGroup",
        "submit .usersToDeleteForm": "deleteUsers"
    },

    // view user event
    viewUser: function (event) {
        el = $(event.currentTarget);
        el.tooltipX("open");
    },

    deleteUsers: function (event) {
        var self = this;
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();

        SMP.makeRequest(
            '/campaigns/affs/deleteusers',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.refreshTable();
                }
            },
            ''
        );
    },

    deleteUser: function (event) {
        var self = this;

        SMP.makeRequest(
            '/campaigns/affs/deleteuser',
            {
                idUser: $(event.currentTarget).attr("uid"),
                idCampaign: self.idCampaign
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    //self.refreshTable();
                    $("#campaignUserRow" + $(event.currentTarget).attr("uid")).remove();
                }
            },
            ''
        );
    },

    deleteGroup: function (event) {
        var self = this;

        SMP.makeRequest(
            '/campaigns/affs/deletegroup',
            {
                idUserGroup: $(event.currentTarget).attr("gid"),
                idCampaign: self.idCampaign
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.refreshTable();
                    //$("#campaignGroupRow" + $(event.currentTarget).attr("")).remove();
                }
            },
            ''
        );
    },

    addUser: function (event) {
        var self = this;
        event.preventDefault();

        SMP.makeRequest(
            '/campaigns/affs/adduser',
            {
                idCampaign: self.idCampaign,
                idUser: $("#userIdToAdd").val()
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    $("#userIdToAdd").val("");
                    $("#userToAdd").val("");
                    self.refreshTable();
                }
            },
            ''
        );
    },

    addUsersGroup: function (event) {
        var self = this;
        event.preventDefault();

        SMP.makeRequest(
            '/campaigns/affs/addusersgroup',
            {
                idCampaign: self.idCampaign,
                idUserGroup: $("#groupToAddC").val()
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    $("#groupToAddC").val("");
                    self.refreshTable();
                }
            },
            ''
        );
    },

    refreshTable: function () {
        var self = this;

        SMP.makeRequest(
            '/campaigns/affs/userslist'                                                ,
            {
                id: self.idCampaign
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    $('#listBox' + self.idCampaign).replaceWith(data.html);
                    self.delegateEvents();
                    self.prepareTableView();
                }
            },
            ''
        );
    },

    prepareViewUserClose: function () {
        $('.tooltipClose').off('click');
        $('.tooltipClose').on('click', function (event) {
            $("#viewUser" + $(this).attr("uid")).tooltipX("close");
        });
    },

    prepareView: function () {
        var self = this;

        self.prepareTableView();

        $("#userToAdd")
        // don't navigate away from the field on tab when selecting an item
        .bind( "keydown", function( event ) {
            if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
                event.preventDefault();
            }
        })
        .autocomplete({
            source: "/users/users/affsearchlist",
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            change: function (event, ui) {
                if(!ui.item){
                    $("#userIdToAdd").val("");
                }
                else
                {
                    $("#userIdToAdd").val(ui.item.id);
                }
            },
            select: function( event, ui ) {
                $("#userIdToAdd").val(ui.item.id);
                this.value = ui.item.login + "(" + ui.item.name + ") - " + ui.item.userRef;
                return false;
            }
        }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
            return $( "<li>" )
                .append( "<a>" + item.login + "(" + item.name + ") - " + item.userRef + "</a>" )
                .appendTo( ul );
        };


    },



    prepareTableView: function () {
        var self = this;

        $('.campaignGroupRow').hover(
            function () {
                $.each($(this).attr("uids").split( /;\s*/ ), function (index, uid) {
                    //$("#campaignUserRow" + uid).css("background-color","#EBEBEB");
                    $("#campaignUserRow" + uid).addClass("bg-info");
                });
            },
            function () {
                $.each($(this).attr("uids").split( /;\s*/ ), function (index, uid) {
                    //$("#campaignUserRow" + uid).css("background-color","transparent");
                    $("#campaignUserRow" + uid).removeClass("bg-info");
                });
            }
        );

        $(".viewUser").tooltipX({
            open: function (event, ui) {
                ui.tooltip.css("max-width", "500px");
                self.prepareViewUserClose();
            },
            items: "button",
            content: function(callback) {
                var el = this;
                if (typeof(self.users[$(el).attr("uid")]) == "undefined")
                {
                    SMP.makeRequest(
                        '/users/users/affinfo',
                        {
                            id: $(el).attr('uid')
                        },
                        'json',
                        function (data) {
                            if (SMP.isset(data.result) && (data.result == 1)) {
                                self.users[$(el).attr("uid")] = data.html + '<span style="position:absolute;top:0;right:0;" uid="' + $(el).attr('uid') + '" class="tooltipClose ui-icon ui-icon-circle-close"></span>';
                                callback(self.users[$(el).attr("uid")]);

                            }
                        }
                    );
                }
                else
                {
                    callback(self.users[$(el).attr("uid")]);
                }


            },
            autoHide:false,
            autoShow:false
        });
    },
    render: function (idCampaign) {
        var self = this;
        self.idCampaign = idCampaign;

        SMP.makeRequest(
            '/campaigns/affs/users',
            {
                id: self.idCampaign
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.html(data.html);
                    if (self.useDialog)
                    {
                        self.$el.dialog('open');
                    }
                    self.prepareView();
                    return self;
                }
            }
        );
    }
});

campaignsUsers = new CampaignsUsers();
