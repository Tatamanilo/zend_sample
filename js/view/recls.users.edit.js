var UsersEdit = Backbone.View.extend({
    prefix: 'usersEdit',
    useDialog: false,
    idRecl: null,

    events: {
        "submit #userEditForm": "submit"
    },

    submit: function (event) {
        var self = this;
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/users/users/edit',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.dialog('close');
                    usersView[self.idRecl].render(self.idRecl);
                }
            },
            ''
        );
    },

    prepareView: function () {
        var self = this;

    },


    render: function (idRecl, idUser) {
        var self = this;
        self.idRecl = idRecl;

        SMP.makeRequest(
            '/users/users/edit',
            {
                idRecl: self.idRecl,
                id: idUser
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

usersEdit = new UsersEdit();
