<link type="text/css" href="{$jsPath}jquery-ui/css/redmond/ui.multiselect.css" rel="stylesheet" />
<script type="text/javascript" src="{$jsPath}jquery.scrollTo-min.js"></script>
<script type="text/javascript" src="{$jsPath}jquery-ui/js/ui.multiselect.js"></script>

<script type="text/javascript" src="{$jsPath}view/users.groups.view.js"></script>
<script type="text/javascript" src="{$jsPath}view/users.groups.edit.js"></script>
<script type="text/javascript" src="{$jsPath}view/users.groups.add.js"></script>

<script type="text/javascript">
    {literal}

    $(document).ready(function(){
        var groupsView = new Groups({el: $('#groupsContent')});
        groupsView.prepareView();
    });
    {/literal}
</script>

<div id="groupsContent">
<div class="row">
    <div class="col-lg-10">
    </div>
    <div class="col-lg-2 text-right">
        <input class="btnadd btn btn-primary" id="addGroup" type="button" value="Новый">
    </div>
</div>
<div class="clearfix"></div>
<br />


<table cellspacing="0" cellpadding="0" border="0" id="affiliateGroups" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Название</th>
            <th width="280px">Количество пользователей в группе</th>
            <th width="100px">Статус</th>
            <th width="120px">Действия</th>
        </tr>
    </thead>
    {if $groups}
    {foreach from=$groups item=group}
    <tr>
        <td class="tableitem">{$group.userGroupName}</td>
        <td class="tableitem">
            <span class="badge _showUsers text-info" style="cursor:pointer;" uids="{$group.idUsers}" gid="{$group.idUserGroup}">{$group.count}</span>
            <!--<i class="fa fa-search _showUsers text-info" style="cursor:pointer;" uids="{$group.idUsers}" gid="{$group.idUserGroup}"> </i>-->
        </td>
        <td class="tableitem">
            {if $group.userGroupStatus=='E'}
            <div class="btn-group">
                <button title="Активный" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">
                    <i class="fa fa-check-circle text-success font17"></i>&nbsp;<span class="caret"></span>
                </button>
                <ul style="width: auto;" class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0)" lnid="{$group.idUserGroup}" class="_changeStatus" change_to="D">
                            <i class="fa fa-minus-circle text-danger "></i> Не активный
                        </a>
                    </li>
                </ul>
            </div>
            {else}
            <div class="btn-group">
                <button title="Не активный" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">
                    <i class="fa fa-minus-circle text-danger font17"></i>&nbsp;<span class="caret"></span>
                </button>
                <ul style="width: auto;" class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0)" lnid="{$group.idUserGroup}" class="_changeStatus" change_to="E">
                            <i class="fa fa-check-circle text-success "></i> Активный
                        </a>
                    </li>
                </ul>
            </div>
            {/if}
        </td>
        <td class="tableitem">
            <button class="_editGroup btn btn-default" gid="{$group.idUserGroup}" title="Редакт."><i class="fa fa-pencil"></i></button>
            <!--<button class="_delGroup btn btn-default" gid="{$group.idUserGroup}" title="Удалить"><i class="fa fa-trash-o"></i></button>-->
        </td>
    </tr>
    {/foreach}
    {/if}
</table>

<div id="dialogEdit" title="Редактирование Группы"></div>
<div id="dialogAdd" title="Добавление Группы"></div>
</div>