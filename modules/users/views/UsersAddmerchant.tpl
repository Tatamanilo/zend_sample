<form class="edit" accept-charset="utf-8" method="POST" id="userAddForm" action="">
    <input type="hidden" name="rid" value="4">
    <input type="hidden" name="ok" value="1">
    <input type="hidden" name="idRecl" value="{$idRecl}">
    <input type="hidden" name="form[status]" value="active">


    <div id="dialog_alert" class="fade"></div>

    <div class="form-group">
        <label>{$messages.login}</label>
        <div class="input-group input-group-sm">
            <span class="input-group-addon">@</span>
            <input type="text" class="form-control" name="form[login]" value="{$user.login|escape:html}" />
        </div>
    </div>

    <div class="form-group input-group-sm">
        <label>{$messages.pass}</label>
        <input type="password" class="form-control" name="form[pass]" value="" />
    </div>

    <div class="form-group input-group-sm">
        <label>{$messages.name}</label>
        <input type="text" class="form-control" name="form[name]" value="{$user.name|escape:html}" />
    </div>

    <div class="form-group">
        <label>{$messages.addInfo}</label>
        <textarea class="form-control" cols="70" rows="5" name="form[additionalInfo]">{$user.additionalInfo|escape:html}</textarea>
    </div>

    <div class="form-group">
        <input class="btnadd btn btn-primary" type="submit" value="Сохранить" />
    </div>
</form>