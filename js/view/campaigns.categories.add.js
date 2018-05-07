var CategoriesAdd = Backbone.View.extend({
    prefix: 'categoriesAdd',

    events: {
        "submit #categoryAddForm": "submit"
    },

    submit: function (event) {
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/campaigns/categories/add',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    $('#dialogAdd').dialog('close');
                    categoryTreeRefresh(data.idParentCategory);
                }
            },
            'categories.openAdd'
        );
    },

    render: function (content) {
        this.setElement($('#dialogAdd').get(0));
        this.$el.html(content);
        this.$el.dialog('open');
        return this;
    }
});

categoriesAdd = new CategoriesAdd();
