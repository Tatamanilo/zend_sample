
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
            <button class="btnadd btn btn-default addUser" csecid="{$idCommissionSection}"><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>

<div class="form-group">
    <label>Либо пользователей группы</label>
    <!--<span class="input-group-addon">@</span>-->
    <div class="row">
        <div class="col-lg-8">
            <select class="form-control input-sm" id="groupToAdd">
                <option value=""></option>
                {foreach from=$groups item=group}
                <option value="{$group.idUserGroup}">{$group.userGroupName}</option>
                {/foreach}
            </select>
        </div>
        <div class="col-lg-4">
            <button class="btnadd btn btn-default addUsersGroup" csecid="{$idCommissionSection}"><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>

<div class="clearfix"></div>


<form id="usersToDelete{$idCommissionSection}" class="usersToDeleteForm">
{/if}
<div id="listBoxCsec{$idCommissionSection}">
    <p class="text-primary">Прикрепленные Группы</p>
    <table cellspacing="0" cellpadding="0" border="0" id="cgroups{$idCampaign}" class="table table-hover">
        <thead>
            <tr>
                <th width="60px"><i class="fa fa-users text-primary"></i></th>
                <th>Группа</th>
                <th width="120px">Действия</th>
            </tr>
        </thead>
        {if $csecgroups}
        {foreach from=$csecgroups item=csecgroup}
        <tr id="commissionSectionGroupRow{$csecgroup.idUserGroup}" class="commissionSectionRow {if $csecgroup.userGroupStatus=='D'}text-muted{/if}" uids="{$csecgroup.idUsers}">
            <td class="tableitem"></td>
            <td class="tableitem">{$csecgroup.userGroupName}</td>
            <td class="tableitem">
                <button class="delGroup btn btn-default" gid="{$csecgroup.idUserGroup}" title="Удалить"><i class="fa fa-trash-o"></i></button>
            </td>
        </tr>
        {/foreach}
        {/if}
    </table>
    <br />

    <p class="text-primary">Прикрепленные Индивидуальные Пользователи</p>
    <table cellspacing="0" cellpadding="0" border="0" id="csecusers{$idCommissionSection}" class="table table-hover">
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
        <tr id="commissionUserRow{$user.idUser}" class="{if $user.status!='active'}text-muted{/if}">
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
</div>

{if !$listOnly}
<input type="hidden" name="idCommissionSection" value="{$idCommissionSection}" />
<button class="btn btn-default usersToDeleteSubmit" type="submit">Удалить выбранные</button>
</form>
{/if}
