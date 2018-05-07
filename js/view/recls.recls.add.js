var ReclsAdd = Backbone.View.extend({
    prefix: 'reclsAdd',

    events: {
        "submit #reclAddForm": "submit"
    },

    submit: function (event) {
        var self = this;
        event.preventDefault();
        //reclsAssign.refreshIdsStr();
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/recls/recls/add',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.dialog('close');
                    //$('#users').trigger('reloadGrid');
                    $('#recls').dataTable().fnDraw();
                }
            },
            'users.openEdit'
        );
    },

    prepareView: function () {

    },

    render: function () {
        self = this;
        SMP.makeRequest(
            '/recls/recls/add',
            {
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

reclsAdd = new ReclsAdd();
