<div id="usersContent{$idRecl}" class="usersContent" rid="{$idRecl}">
<div class="row">
    <div class="col-lg-12">
        <button class="btnadd btn btn-default addUser" rid="{$idRecl}"><i class="fa fa-plus"></i></button>
    </div>
</div>
<div class="clearfix"></div>

<table cellspacing="0" cellpadding="0" border="0" id="users{$idRecl}" class="table table-hover">
    <thead>
        <tr>
            <th>Логин (email)</th>
            <th>Имя</th>
            <th width="280px">Количество заявок в день</th>
        </tr>
    </thead>
    {if $users}
    {foreach from=$users item=user}
    <tr>
        <td class="tableitem">{$user.login}</td>
        <td class="tableitem">{$user.name}</td>
        <td class="tableitem">
            <button class="editUser btn btn-default" uid="{$user.idUser}" title="Редакт."><i class="fa fa-pencil"></i></button>
            <button class="delUser btn btn-default" uid="{$user.idUser}" title="Удалить"><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    {/foreach}
    {/if}
</table>

</div>