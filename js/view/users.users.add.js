var UsersAdd = Backbone.View.extend({
    prefix: 'usersEdit',

    events: {
        "submit #userAddForm": "submit"
    },

    submit: function (event) {
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/users/users/add',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    $('#dialogAdd').dialog('close');
                    //$('#users').trigger('reloadGrid');
                    $('#users').dataTable().fnDraw();
                }
            },
            'users.openEdit'
        );
    },

    render: function (content) {
        this.setElement($('#dialogAdd').get(0));
        this.$el.html(content);
        this.$el.dialog('open');
        return this;
    }
});

usersAdd = new UsersAdd();
