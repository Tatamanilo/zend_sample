var CampaignsAdd = Backbone.View.extend({
    prefix: 'usersEdit',

    events: {
        "submit #campaignAddForm": "submit"
    },

    submit: function (event) {
        var self = this;
        event.preventDefault();
        //reclsAssign.refreshIdsStr();
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/campaigns/campaigns/add',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.dialog('close');
                    //$('#users').trigger('reloadGrid');
                    $('#campaigns').dataTable().fnDraw();
                }
            },
            'users.openEdit'
        );
    },

    prepareView: function () {
        $('#logoFile').fileupload({
            url: '/campaigns/campaigns/logoupload/',
            dataType: 'json',
            done: function (e, data) {
                $.each(data.result.files, function (index, file) {
                    $('#logo').val(file.name);
                    $('#files').html("");
                    $('<img/>').attr("src", file.thumbnail_url).attr("height", "70px").appendTo('#files');
                });
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');

        $('#promoMaterialsFile').fileupload({
            url: '/campaigns/campaigns/promoupload/',
            dataType: 'json',
            done: function (e, data) {
                $.each(data.result.files, function (index, file) {
                    $('#promoMaterials').val(file.name);
                    $('#filesPromo').html("");
                    $('<p/>').text(file.name).appendTo('#filesPromo');
                });
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progressPromoMaterials .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');

        //reclsAssign.setElement($("#reclsBox"));
        //reclsAssign.render();

        $("#reclName").autocomplete({
            source: '/users/recls/reclslist',
            minLength: 1,
            change: function (event, ui) {
                if(!ui.item){
                    //http://api.jqueryui.com/autocomplete/#event-change -
                    // The item selected from the menu, if any. Otherwise the property is null
                    //so clear the item for force selection
                    $("#reclName").val("");
                    $("#idRecl").val("");
                }
                else
                {
                    $("#idRecl").val(ui.item.id);
                }

            }

        });
    },

    render: function () {
        self = this;
        SMP.makeRequest(
            '/campaigns/campaigns/add',
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

campaignsAdd = new CampaignsAdd();
