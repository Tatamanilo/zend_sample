var UsersAdd = Backbone.View.extend({
    prefix: 'usersAdd',
    useDialog: false,
    idRecl: null,

    events: {
        "submit #userAddForm": "submit"
    },

    submit: function (event) {
        var self = this;
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/users/users/addmerchant',
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


    render: function (idRecl) {
        var self = this;
        self.idRecl = idRecl;

        SMP.makeRequest(
            '/users/users/addmerchant',
            {
                idRecl: self.idRecl
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

usersAdd = new UsersAdd();
