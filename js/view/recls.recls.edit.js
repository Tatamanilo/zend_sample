var ReclsEdit = Backbone.View.extend({
    prefix: 'reclsEdit',

    events: {
        "submit #reclEditForm": "submit"
    },

    submit: function (event) {
        var self = this;
        event.preventDefault();
        //reclsAssign.refreshIdsStr();
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/recls/recls/edit',
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

    render: function (idRecl) {
        self = this;
        SMP.makeRequest(
            '/recls/recls/edit',
            {
                id: idRecl
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

reclsEdit = new ReclsEdit();
