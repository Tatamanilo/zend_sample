<form class="edit"  method="POST" id="categoryAddForm" action="/campaigns/categories/add">
    <input type="hidden" name="ok" value="1">
    <input type="hidden" name="idParentCategory" value="{$idParentCategory}">

    <div class="form-group input-group-sm">
        <label>{$messages.categoryName}</label>
        <input type="text" class="form-control" name="form[categoryName]" value="" />
    </div>

    <div class="form-group input-group-sm">
        <label>{$messages.categoryDescr}</label>
        <input type="text" class="form-control" name="form[categoryDescr]" value="" />
    </div>

    <div class="form-group">
        <input class="btnadd btn btn-primary" type="submit" value="Сохранить" />
    </div>
</form>