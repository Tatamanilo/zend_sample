<form accept-charset="utf-8" method="POST" class="groupEditForm" action="">
    <input type="hidden" name="ok" value="1">
    <input type="hidden" name="id" value="{$group.idUserGroup}">

    <div class="form-group input-group-sm">
        <label>Название группы</label>
        <input type="text" class="form-control" name="form[userGroupName]" value="{$group.userGroupName}" />
    </div>

    <div class="form-group input-group-sm">
        <label>Выберите пользователя для добавления в группу</label>
        <!--<span class="input-group-addon">@</span>-->
        <input class="usersForGroup form-control" size="50">
        <input class="usersIds" name="form[idUsers]" type="hidden" size="50" value="{$group.idUsers}">
    </div>

    <br />
    <div class="row list-group usersSelectedBox">
        {if $users}
        {foreach from=$users item=user}
        <div class="col-lg-6 user-row-box {if $user.status!='active'}text-muted{/if}"><a uid="{$user.idUser}" class='list-group-item' href='#'>{$user.login} ({$user.name}) - {$user.userRef}<i uid="{$user.idUser}" class="close fa fa-times _removeUser close-user-row-box"></i></a></div>
        {/foreach}
        {/if}
    </div>

    <div class="form-group">
        <input class="btnadd btn btn-primary" type="submit" value="Сохранить" />
    </div>
</form>

