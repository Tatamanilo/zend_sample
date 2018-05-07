<div id="landingsContent{$idCampaign}" class="landingsContent" cid="{$idCampaign}">
<div class="row">
    <div class="col-lg-12">
        <button class="btnadd btn btn-default addLanding" cid="{$idCampaign}" title="Добавить лендинг"><i class="fa fa-plus"></i></button>
    </div>
</div>
<div class="clearfix"></div>

<table cellspacing="0" cellpadding="0" border="0" id="landings{$idCampaign}" class="table table-hover">
    <thead>
        <tr>
            <th>Название</th>
            <th>EPC сегодня</th>
            <th>EPC вчера</th>
            <th>EPC неделя</th>
            <th>EPC общий</th>
            <th>Приватный</th>
            <th width="130px">Статус</th>
            <th width="140px">Операции</th>
        </tr>
    </thead>
    {if $landings}
    {foreach from=$landings item=item}
    <tr>
        <td class="tableitem">{$item.landingName}</td>
        <td class="tableitem">
            {$item.epcToday}
            {if $item.epcToday>$item.epcYesterday}<i class="fa green fa-sort-desc font17"{/if}
            {if $item.epcToday<$item.epcYesterday}<i class="fa red fa-sort-asc font17"{/if}
        </td>
        <td class="tableitem">{$item.epcYesterday}</td>
        <td class="tableitem">{$item.epcWeek}</td>
        <td class="tableitem">{$item.epcAll}</td>
        <td class="tableitem"><i class="fa {if $item.forPrivate}fa-users text-success{else}fa-globe{/if}" title="{if $item.forPrivate}Приватный{else}Публичный{/if}"></i></td>
        <td class="tableitem">
            {if $item.landingStatus=='E'}
            <div class="btn-group">
                <button title="Активный" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">
                    <i class="fa fa-check-circle text-success font17"></i>&nbsp;<span class="caret"></span>
                </button>
                <ul style="width: auto;" class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0)" lnid="{$item.idLanding}" class="changeStatus" change_to="D">
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
                        <a href="javascript:void(0)" lnid="{$item.idLanding}" class="changeStatus" change_to="E">
                            <i class="fa fa-check-circle text-success "></i> Активный
                        </a>
                    </li>
                </ul>
            </div>
            {/if}
            {if $item.forPrivate}
            <div class="btn-group">
                <button title="Приватный" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">
                    <i class="fa fa-users text-success font17"></i>&nbsp;<span class="caret"></span>
                </button>
                <ul style="width: auto;" class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0)" lnid="{$item.idLanding}" class="changeForPrivate" change_to="0">
                            <i class="fa fa-globe "></i> Публичный
                        </a>
                    </li>
                </ul>
            </div>
            {else}
            <div class="btn-group">
                <button title="Публичный" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">
                    <i class="fa fa-globe font17"></i>&nbsp;<span class="caret"></span>
                </button>
                <ul style="width: auto;" class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0)" lnid="{$item.idLanding}" class="changeForPrivate" change_to="1">
                            <i class="fa fa-users text-success"></i> Приватный
                        </a>
                    </li>
                </ul>
            </div>
            {/if}
        </td>
        <td class="tableitem" lnid="{$item.idLanding}">
            <button class="editLanding btn btn-default" cid="{$idCampaign}" lnid="{$item.idLanding}" title="Редакт."><i class="fa fa-pencil"></i></button>
            {if $item.forPrivate}<button class="viewUsers btn btn-default" cid="{$idCampaign}" lnid="{$item.idLanding}" title="Список пользователей"><i class="fa fa-users"></i></button>{/if}
        </td>
    </tr>
    {/foreach}
    {/if}
</table>

</div>