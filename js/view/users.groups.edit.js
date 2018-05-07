var GroupsEdit = Backbone.View.extend({
    prefix: 'groupsEdit',
    useDialog: true,
    userIds: [],
    events: {
        "submit .groupEditForm": "submit"
    },

    submit: function (event) {
        self = this;
        event.preventDefault();
        $(event.currentTarget).find(".usersIds").val(this.userIds.join(";"));
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/users/groups/edit',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    $.showAjaxWait();
                    location.href = '/users/groups/index/';
                    if (self.useDialog)
                    {
                        self.$el.dialog('close');
                    }

                }
            },
            'users.openEdit'
        );
    },

    prepareView: function () {
        self = this;
        self.$el.find(".usersForGroup")
        // don't navigate away from the field on tab when selecting an item
        .bind( "keydown", function( event ) {
            if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
                event.preventDefault();
            }
        })
        .autocomplete({
            source: "/users/groups/affsearchlist",
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            select: function( event, ui ) {
                self.appendUserRow(ui.item);

                this.value = "";
                return false;
            }
        }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
            return $( "<li>" )
                .append( "<a>" + item.login + "(" + item.name + ") - " + item.userRef + "</a>" )
                .appendTo( ul );
        };

        this.removeUserRowActionPrepare();

    },

    appendUserRow: function(user) {

        if (this.isUserSelected(user.id))
        {
            $.showInfoBox('Пользователь уже в группе');
        }
        else
        {
            this.$el.find(".usersSelectedBox").prepend( "<div class='col-lg-6 user-row-box'><a uid='" + user.id + "' class='list-group-item' href='#'>" + user.login + "(" + user.name + ") - " + user.userRef + "<i uid='" + user.id + "' class='close fa fa-times _removeUser close-user-row-box'></i></a></div>" );
            this.userIds.push(user.id);
            this.removeUserRowActionPrepare();
        }
    },

    removeUserRowActionPrepare: function() {
        self = this;
        this.$el.find("._removeUser").off('click');
        this.$el.find("._removeUser").on('click', function (event) {
            $(this).parents("div.user-row-box").first().remove();
            self.userIds.splice( $.inArray($(this).attr("uid"), self.userIds), 1 );
        });
    },

    isUserSelected: function(uid) {
        if ($.inArray(uid, self.userIds) >= 0 )
        {
            return true;
        }
        return false;
    },

    render: function (gid) {
        self = this;
        SMP.makeRequest(
            '/users/groups/edit',
            {
                id: gid
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.html(data.html);
                    if (self.useDialog)
                    {
                        self.$el.dialog('open');
                    }
                    self.userIds = self.$el.find(".usersIds").val().split( /;\s*/ );
                    self.prepareView();
                    return self;
                }
            }
        );
    }
});

groupsEdit = new GroupsEdit();
