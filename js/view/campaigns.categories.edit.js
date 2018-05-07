var CategoriesEdit = Backbone.View.extend({
    prefix: 'categoriesEdit',

    events: {
        "submit #categoryEditForm": "submit"
    },

    submit: function (event) {
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/campaigns/categories/edit',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    $('#dialogEdit').dialog('close');
                    categoryTreeRefresh(data.idParentCategory);
                }
            },
            'categories.openEdit'
        );
    },

    render: function (content) {
        this.setElement($('#dialogEdit').get(0));
        this.$el.html(content);
        this.$el.dialog('open');
        return this;
    }
});

categoriesEdit = new CategoriesEdit();
