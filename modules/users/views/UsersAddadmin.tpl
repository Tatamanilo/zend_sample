<form class="edit" accept-charset="utf-8" method="POST" id="userAddForm" action="">
    <input type="hidden" name="rid" value="1">
    <input type="hidden" name="ok" value="1">
    <input type="hidden" name="form[status]" value="active">

    <div class="form-group input-group-sm">
        <label>{$messages.login}</label>
        <!--<span class="input-group-addon">@</span>-->
        <input type="text" class="form-control" name="form[login]" value="{$user.login|escape:html}" />
    </div>

    <div class="form-group input-group-sm">
        <label>{$messages.pass}</label>
        <input type="password" class="form-control" name="form[pass]" value="" />
    </div>

    <div class="form-group">
        <input class="btnadd btn btn-primary" type="submit" value="Сохранить" />
    </div>
</form>