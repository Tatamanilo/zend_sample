<div id="layersContent{$idCampaign}" class="layersContent" cid="{$idCampaign}">
<div class="row">
    <div class="col-lg-12">
        <button class="btnadd btn btn-default addLayer" cid="{$idCampaign}" title="Добавить прокладку"><i class="fa fa-plus"></i></button>
    </div>
</div>
<div class="clearfix"></div>

<table cellspacing="0" cellpadding="0" border="0" id="layers{$idCampaign}" class="table table-hover">
    <thead>
        <tr>
            <th>Название</th>
            <th>EPC сегодня</th>
            <th>EPC вчера</th>
            <th>EPC неделя</th>
            <th>EPC общий</th>
            <th>Процент</th>
            <th>Приватный</th>
            <th width="130px">Статус</th>
            <th width="140px">Операции</th>
        </tr>
    </thead>
    {if $layers}
    {foreach from=$layers item=item}
    <tr>
        <td class="tableitem">{$item.layerName}</td>
        <td class="tableitem">
            {$item.epcToday}
            {if $item.epcToday>$item.epcYesterday}<i class="fa green fa-sort-desc font17"{/if}
            {if $item.epcToday<$item.epcYesterday}<i class="fa red fa-sort-asc font17"{/if}
        </td>
        <td class="tableitem">{$item.epcYesterday}</td>
        <td class="tableitem">{$item.epcWeek}</td>
        <td class="tableitem">{$item.epcAll}</td>
        <td class="tableitem">{$item.percent}</td>
        <td class="tableitem">{if $item.forPrivate}<i class="fa fa-users text-success" title="Приватный"></i>{/if}</td>
        <td class="tableitem">
            {if $item.layerStatus=='E'}
            <div class="btn-group">
                <button title="Активный" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">
                    <i class="fa fa-check-circle text-success font17"></i>&nbsp;<span class="caret"></span>
                </button>
                <ul style="width: auto;" class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0)" lid="{$item.idLayer}" class="changeStatus" change_to="D">
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
                        <a href="javascript:void(0)" lid="{$item.idLayer}" class="changeStatus" change_to="E">
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
                        <a href="javascript:void(0)" lid="{$item.idLayer}" class="changeForPrivate" change_to="0">
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
                        <a href="javascript:void(0)" lid="{$item.idLayer}" class="changeForPrivate" change_to="1">
                            <i class="fa fa-users text-success"></i> Приватный
                        </a>
                    </li>
                </ul>
            </div>
            {/if}
        </td>
        <td class="tableitem" lid="{$item.idLayer}">
            <button class="editLayer btn btn-default" cid="{$idCampaign}" lid="{$item.idLayer}" title="Редакт."><i class="fa fa-pencil"></i></button>
            {if $item.forPrivate}<button class="viewUsers btn btn-default" cid="{$idCampaign}" lid="{$item.idLayer}" title="Список пользователей"><i class="fa fa-users"></i></button>{/if}
        </td>
    </tr>
    {/foreach}
    {/if}
</table>

</div>