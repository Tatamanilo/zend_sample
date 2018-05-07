var CommissionsUsers = Backbone.View.extend({
    prefix: 'groupsEdit',
    useDialog: true,
    idCampaign: null,
    idCommissionSection: null,
    users: [],
    events: {
        "click .addUser": "addUser",
        "click .addUsersGroup": "addUsersGroup",
        "click .viewUser": "viewUser",
        "click .delUser": "deleteUser",
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
            '/campaigns/commissions/deleteusers',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.refreshTable();
                }
            },
            'users.openEdit'
        );
    },

    deleteUser: function (event) {
        var self = this;
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();

        SMP.makeRequest(
            '/campaigns/commissions/deleteuser',
            {
                idUser: $(event.currentTarget).attr("uid"),
                idCommissionSection: self.idCommissionSection
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    //self.refreshTable();
                    $("#commissionUserRow" + $(event.currentTarget).attr("uid")).remove();
                }
            },
            'users.openEdit'
        );
    },

    addUser: function (event) {
        var self = this;
        event.preventDefault();

        SMP.makeRequest(
            '/campaigns/commissions/adduser',
            {
                idCommissionSection: self.idCommissionSection,
                idUser: $("#userIdToAdd").val()
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.refreshTable();
                }
                $("#userIdToAdd").val("");
                $("#userToAdd").val("");


            },
            'users.openEdit'
        );
    },

    addUsersGroup: function (event) {
        var self = this;
        event.preventDefault();

        SMP.makeRequest(
            '/campaigns/commissions/addusersgroup',
            {
                idCommissionSection: self.idCommissionSection,
                idUserGroup: $("#groupToAdd").val()
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.refreshTable();
                }
                $("#groupToAdd").val("");
            },
            'users.openEdit'
        );
    },

    refreshTable: function () {
        var self = this;

        SMP.makeRequest(
            '/campaigns/commissions/userslist'                                                ,
            {
                id: self.idCommissionSection
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    $('#listBoxCsec' + self.idCommissionSection).replaceWith(data.html); 
                    self.delegateEvents();
                    self.prepareTableView();
                    //$('.delUser').off('click');
                    //$('.delUser').on('click', 'deleteUser');
                }
            },
            'users.openEdit'
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
                //self.appendUserRow(ui.item);

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



    render: function (idCampaign, idCommissionSection) {
        var self = this;
        self.idCampaign = idCampaign;
        self.idCommissionSection = idCommissionSection;

        SMP.makeRequest(
            '/campaigns/commissions/users',
            {
                id: self.idCommissionSection
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

commissionsUsers = new CommissionsUsers();
