var ReclsAssign = Backbone.View.extend({
    prefix: 'groupsAdd',
    useDialog: false,
    userIds: [],
    events: {
    },

    refreshIdsStr: function () {
        return this.$el.find(".usersIds").val(this.userIds.join(";"));
    },

    prepareView: function () {
        var self = this;
        self.$el.find(".usersForGroup")
        // don't navigate away from the field on tab when selecting an item
        .bind( "keydown", function( event ) {
            if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
                event.preventDefault();
            }
        })
        .autocomplete({
            source: "/campaigns/recls/reclslist",
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
                .append( "<a>" + item.value + "</a>" )
                .appendTo( ul );
        };

        this.removeUserRowActionPrepare();
    },

    appendUserRow: function(recl) {
        if (this.isUserSelected(recl.id))
        {
            $.showInfoBox('Пользователь уже добавлен');
        }
        else
        {
            this.$el.find(".usersSelectedBox").prepend( "<div class='col-lg-6 user-row-box'><a rid='" + recl.id + "' class='list-group-item' href='#'>" + recl.value + "<i rid='" + recl.id + "' class='close fa fa-times _removeUser close-user-row-box'></i></a></div>" );
            this.userIds.push(recl.id);
            this.removeUserRowActionPrepare();
        }
    },

    removeUserRowActionPrepare: function() {
        var self = this;
        this.$el.find("._removeUser").off('click');
        this.$el.find("._removeUser").on('click', function (event) {
            $(this).parents("div.user-row-box").first().remove();
            self.userIds.splice( $.inArray($(this).attr("rid"), self.userIds), 1 );
        });
    },

    isUserSelected: function(rid) {
        if ($.inArray(rid, self.userIds) >= 0 )
        {
            return true;
        }
        return false;
    },

    render: function () {
        var self = this;
        this.userIds = [];
        SMP.makeRequest(
            '/campaigns/recls/assign',
            {
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.html(data.html);
                    if (self.useDialog)
                    {
                        self.$el.dialog('open');
                    }
                    userIdsStr = self.$el.find(".usersIds").val();
                    if (userIdsStr)
                    {
                        self.userIds = userIdsStr.split( /;\s*/ );
                    }
                    self.prepareView();
                    return self;
                }
            }
        );
    }
});

reclsAssign = new ReclsAssign();
