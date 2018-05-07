var UsersEdit = Backbone.View.extend({
    prefix: 'usersEdit',

    events: {
        "submit #userEditForm": "submit"
    },

    submit: function (event) {
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/users/users/edit',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    $('#dialogEdit').dialog('close');
                    //$('#users').trigger('reloadGrid');
                    $('#users').dataTable().fnDraw();
                }
            },
            'users.openEdit'
        );
    },

    render: function (content) {
        this.setElement($('#dialogEdit').get(0));
        this.$el.html(content);
        this.$el.dialog('open');
        
        $('._loginHistoryInEdit').off('click');
        $('._loginHistoryInEdit').on('click', function (event) {
            loginHistory.prepareView($(this).attr('uid'));   
        });
        
        $('#resetHoldBtn').off('click');
        $('#resetHoldBtn').on('click', function (event) {
            alert("Reset hold logic");
            /*
            SMP.makeRequest(
                '/users/users/resethold',
                {
                    id: $(this).attr('uid')
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        // do smth
                    }
                },
                'users.openEdit'
            );
            */
        });
        
        $('#wmr').off('change');
        $('#wmr').on('change', function (event) {
            SMP.makeRequest(
                '/users/users/getwmid',
                {
                    wmr: $(this).val()
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        $("#wmid").val(data.wmid);
                        // do smth
                    }
                }
            );
        });
        
        return this;  
    }
});

usersEdit = new UsersEdit();
