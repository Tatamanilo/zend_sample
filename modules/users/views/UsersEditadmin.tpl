<form class="edit" accept-charset="utf-8" method="POST" id="userEditForm" action="">
    <input type="hidden" name="ok" value="1">
    <input type="hidden" name="id" value="{$user.idUser}">

    <div class="form-group">
        <label>{$messages.login}</label>
        <div class="input-group-sm input-group">
            <span class="input-group-addon">@</span>
            <input type="text" class="form-control" name="form[login]" value="{$user.login|escape:html}" />
        </div>
    </div>

    <div class="form-group input-group-sm">
        <label>{$messages.pass}</label>
        <input type="password" class="form-control" name="form[pass]" value="" />
    </div>

    <div class="form-group input-group-sm">
        <label>{$messages.status}</label>
        <select class="form-control" name="form[status]">
            <option value="active" {if $user.status == 'active'}selected="selected"{/if}>{$messages.activeStatus}</option>
            <option value="delete" {if $user.status == 'delete'}selected="selected"{/if}>{$messages.deleteStatus}</option>
        </select>
    </div>

    <div class="form-group">
        <input class="btnadd btn btn-primary" type="submit" value="Сохранить" />
    </div>
</form>