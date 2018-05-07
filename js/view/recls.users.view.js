var UsersView = Backbone.View.extend({
    prefix: '',

    events: {
        "click .addUser": "addUser",
        "click .editUser": "editUser",
        "click .delUser": "deleteUser"
    },

    addUser: function () {
        usersAdd.setElement($("#dialogAddUser"));
        usersAdd.render(this.idRecl);
    },

    editUser: function (event) {
        usersEdit.setElement($("#dialogEditUser"));
        usersEdit.render(this.idRecl, $(event.currentTarget).attr('uid'));
    },

    deleteUser: function (event) {
        var self = this;
        SMP.makeRequest(
            '/recls/users/delete',
            {
                id: $(event.currentTarget).attr('uid')
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.render(self.idRecl);
                }
            }
        );
    },

    render: function (idRecl) {
        var self = this;
        self.idRecl = idRecl;

        SMP.makeRequest(
            '/recls/users/index',
            {
                id: idRecl
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.html(data.html);
                    return self;
                }
            }
        );

    }
});


