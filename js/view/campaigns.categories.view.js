function categoriesAddShow (idParentCategory) {
    var data= {'isAjax' : 1};
    if (idParentCategory) {
        data['idParentCategory'] = idParentCategory;
    }
    SMP.makeRequest(
        '/campaigns/categories/add', data,
        'json',
        function (data) {
            if (SMP.isset(data.result) && (data.result == 1)) {
                categoriesAdd.render(data.html);
            }
        },
        'categories.openEdit'
    );

    return false;
}

function categoryDelete(idCategory, hasChild)
{
    var sConfirm = 'Вы действительно хотите удалить данную категорию?';
    if (hasChild) {
        sConfirm = 'Внимание у данной категории есть дети. Они все удалятся при удалении категории. ' + sConfirm;
    }
    if (confirm(sConfirm)) {
        SMP.makeRequest(
            '/campaigns/categories/delete', {
                'idCategory' : idCategory
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    categoryTreeRefresh(data.idParentCategory);
                }
            },
            'categories.delete'
        );
    }
}

function categoriesEditShow (idCategory) {
    var data= {'isAjax' : 1};
    if (idCategory) {
        data['idCategory'] = idCategory;
    }
    SMP.makeRequest(
        '/campaigns/categories/edit', data,
        'json',
        function (data) {
            if (SMP.isset(data.result) && (data.result == 1)) {
                categoriesEdit.render(data.html);
            }
        },
        'categories.openEdit'
    );

    return false;
}

function categoriesTreeInit() {
    $("#categoriesContent").jstree({
        'core' : {
            'data' : {
                'url' : '/campaigns/categories/list',
                'data' : function (node) {
                    return { 'idParentCategory' : node.id };
                }
            },
            'check_callback' : true,
            'themes' : {
                'icons' : false,
                'responsive' : false
            }
        },
        'plugins' : ['state','dnd','contextmenu','wholerow'],
        'contextmenu' : {
            select_node: true,
            // items можно определить как функцию от ноды, и для каждой ноды таким образом определить свой набор
            // элементов меню. Другого способа сделать различные наборы (разные disabled/enabled к примеру) элементов,
            // судя по всему, нет
            items: function(node) {
                return {
                    // убираем элементы по умолчанию
                    create: {
                        label: 'Создать',
                        action: function() {
                            categoriesAddShow(node.id);
                        }
                    },
                    rename: {
                        label: 'Изменить',
                        action: function() {
                            categoriesEditShow(node.id);
                        }
                    },
                    remove: {
                        label: 'Удалить',
                        action: function() {
                            categoryDelete(node.id, node.children.length);
                        }
                    }
                }
            }
        }
    }).on('move_node.jstree', function (e, data) {
        $.get('/campaigns/categories/move', { 'idCategory' : data.node.id, 'idParentCategory' : data.parent, 'orderNum' : data.position })
            .fail(function () {
                data.instance.refresh();
            });
    });
}

function categoryTreeRefresh(idParentCategory) {
    var tree = $("#categoriesContent").jstree();
    if (!idParentCategory || idParentCategory == 1) {
        tree.refresh();
        return;
    }
    var parentNode = tree.get_node(idParentCategory, false);
    console.log(parentNode);
    tree.refresh_node(parentNode);
}

function categoriesViewStartup()
{
    $('#dialogEdit').dialog({
        autoOpen: false,
        width: 500
    });

    $('#dialogAdd').dialog({
        autoOpen: false,
        width: 700
    });

    categoriesTreeInit();
}