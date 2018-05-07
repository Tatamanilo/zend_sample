<form class="edit"  method="POST" id="categoryEditForm" action="">
    <input type="hidden" name="ok" value="1">
    <input type="hidden" name="idCategory" value="{$idCategory}">

    <div class="form-group input-group-sm">
        <label>{$messages.categoryName}</label>
        <input type="text" class="form-control" name="form[categoryName]" value="{$categoryName|escape:html}" />
    </div>

    <div class="form-group input-group-sm">
        <label>{$messages.categoryDescr}</label>
        <input type="text" class="form-control" name="form[categoryDescr]" value="{$categoryDescr|escape:html}" />
    </div>

    <div class="form-group">
        <input class="btnadd btn btn-primary" type="submit" value="Сохранить" />
    </div>
</form>