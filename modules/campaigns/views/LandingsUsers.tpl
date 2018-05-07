{if !$listOnly}
<div class="form-group">
    <label>Добавить пользователя</label>
    <!--<span class="input-group-addon">@</span>-->
    <div class="row" >
        <div class="col-lg-8">
            <input class="form-control input-sm" name="" id="userToAdd" size="50">
            <input type="hidden" name="" id="userIdToAdd">
        </div>
        <div class="col-lg-4">
            <button class="btnadd btn btn-default addUser" lnid="{$idLanding}"><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>

<div class="clearfix"></div>


<form id="usersToDelete{$idLanding}" class="usersToDeleteForm">
{/if}
<table cellspacing="0" cellpadding="0" border="0" id="lnusers{$idLanding}" class="table table-hover">
    <thead>
        <tr>
            <th></th>
            <th>Email</th>
            <th>ID</th>
            <th width="120px">Действия</th>
        </tr>
    </thead>
    {if $users}
    {foreach from=$users item=user}
    <tr id="landingUserRow{$user.idUser}"  class="{if $user.status!='active'}text-muted{/if}">
        <td class="tableitem">
            <input type="checkbox" name="usersToDelete[{$user.idUser}]" value="{$user.idUser}" />
        </td>
        <td class="tableitem">{$user.login}</td>
        <td class="tableitem">{$user.userRef}</td>
        <td class="tableitem">
            <button type="button" class="viewUser btn btn-default" id="viewUser{$user.idUser}" uid="{$user.idUser}" title="Просмотреть"><i class="fa fa-info"></i></button>
            <button class="delUser btn btn-default" uid="{$user.idUser}" title="Удалить"><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    {/foreach}
    {/if}
</table>
{if !$listOnly}
<input type="hidden" name="idLanding" value="{$idLanding}" />
<button class="btn btn-default usersToDeleteSubmit" type="submit">Удалить выбранные</button>
</form>
{/if}
