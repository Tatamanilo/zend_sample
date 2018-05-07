<div id="commissionsContent{$idCampaign}" class="commissionsContent" cid="{$idCampaign}">

<table cellspacing="0" cellpadding="0" border="0" id="commissions{$idCampaign}" class="table table-hover">
    <thead>
        <tr>
            <th>айди Связки</th>
            <th>Название Связки</th>
            <th width="250px">Название</th>
            <th width="100px">Рекл</th>
            <th>Цель</th>
            <th>Страны</th>
            <th>Тип</th>
            <th>Доп.номер</th>
            <th>Групповая</th>
            <th>Цена</th>
            <th width="130px">Статус</th>
            <th width="110px">Операции</th>
        </tr>
    </thead>
    {if $commissions}
    {foreach from=$commissions item=commission}
    <tr csecid="{$commission.idCommissionSection}">
        <td>{$commission.idCommissionSection}</td>
        <td class="tableitem" csecid="{$commission.idCommissionSection}">
            <div csecid="{$commission.idCommissionSection}" class="sectionName pull-left" style="width: 400px;">{$commission.commissionSectionName}</div>
            <div id="csec{$commission.idCommissionSection}" class="pull-right" style="width: 160px">
            <button class="btnadd btn btn-default addCommission " cid="{$idCampaign}" csecid="{$commission.idCommissionSection}" title="Создать коммиссию в этой связке"><i class="fa fa-plus text-primary"></i></button>
            <button class="btnadd btn btn-default cloneCommissionSection " cid="{$idCampaign}" csecid="{$commission.idCommissionSection}"><i class="fa fa-files-o text-primary"></i></button>
            {if $commission.isGroupCommissionSection}<button class="viewUsers btn btn-default " cid="{$idCampaign}" csecid="{$commission.idCommissionSection}" title="Список пользователей"><i class="fa fa-users text-primary"></i></button>{/if}
            </div>
        </td>
        {if empty($commission.idCommission)}
        <td class="tableitem"></td>
        <td class="tableitem"></td>
        <td class="tableitem"></td>
        <td class="tableitem"></td>
        <td class="tableitem"></td>
        <td class="tableitem"></td>
        <td class="tableitem"></td>
        <td class="tableitem"></td>
        <td class="tableitem"></td>
        <td class="tableitem" csecid="{$commission.idCommissionSection}">
            <!--<button class="btnadd btn btn-default addCommission" cid="{$idCampaign}" csecid="{$commission.idCommissionSection}"><i class="fa fa-plus"></i></button>-->
        </td>
        {else}
        <td class="tableitem" style="padding-left: 28px;">{$commission.commissionName}</td>
        <td class="tableitem">{$commission.reclName}</td>
        <td class="tableitem">{$messages[$commission.target]}</td>
        <td class="tableitem">{$commission.countries}</td>
        <td class="tableitem">
            {if $commission.commissionType=='P'}
            <i class="fa fa-percent text-info"></i>
            {/if}

            {if $commission.commissionType=='S'}
            <i class="fa fa-usd text-success"></i>
            {/if}
        </td>

        <td class="tableitem">{$commission.addNumber}</td>
        <td class="tableitem">{if $commission.isGroupCommission}<i class="fa fa-users text-success "></i>{/if}</td>
        <td class="tableitem">{$commission.priceRecl} / {$commission.priceAdvert}</td>
        <td class="tableitem">
            {if $commission.commissionStatus=='E'}
            <div class="btn-group">
                <button title="Активный" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">
                    <i class="fa fa-check-circle text-success font17"></i>&nbsp;<span class="caret"></span>
                </button>
                <ul style="width: auto;" class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0)" csid="{$commission.idCommission}" class="changeStatus" change_to="D">
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
                        <a href="javascript:void(0)" csid="{$commission.idCommission}" class="changeStatus" change_to="E">
                            <i class="fa fa-check-circle text-success "></i> Активный
                        </a>
                    </li>
                </ul>
            </div>
            {/if}
            {if $commission.approveType=='M'}
            <div class="btn-group">
                <button title="Ручной" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">
                    <i class="fa fa-hand-o-up font17"></i>&nbsp;<span class="caret"></span>
                </button>
                <ul style="width: auto;" class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0)" csid="{$commission.idCommission}" class="changeApproveType" change_to="A">
                            <i class="fa fa-cogs "></i> Автоматический
                        </a>
                    </li>
                </ul>
            </div>
            {else}
            <div class="btn-group">
                <button title="Автоматический" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">
                    <i class="fa fa-cogs font17"></i>&nbsp;<span class="caret"></span>
                </button>
                <ul style="width: auto;" class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0)" csid="{$commission.idCommission}" class="changeApproveType" change_to="M">
                            <i class="fa fa-hand-o-up "></i> Ручной
                        </a>
                    </li>
                </ul>
            </div>
            {/if}
        </td>
        <td class="" csid="{$commission.idCommission}">
            <button class="editCommission btn btn-default" cid="{$idCampaign}" csid="{$commission.idCommission}" title="Редакт."><i class="fa fa-pencil"></i></button>
            <button class="viewPrices btn btn-default" cid="{$idCampaign}" csid="{$commission.idCommission}" title="Список цен"><i class="fa fa-usd"></i></button>
        </td>
        {/if}
    </tr>
    {/foreach}
    {/if}
</table>

</div>