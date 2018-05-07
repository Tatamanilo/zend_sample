<div id="campaignsContent{$idRecl}" class="campaignsContent" rid="{$idRecl}">

<table cellspacing="0" cellpadding="0" border="0" id="campaigns{$idRecl}" class="table table-hover">
    <thead>
        <tr>
            <th>Лого</th>
            <th>Название</th>
            <th>ID</th>
            <th>Цена на лендинге</th>
            <th>Оплаченных транзакций</th>
            <th>Неоплаченных транзакций</th>
            <th width="150px">Статус</th>
            <th width="80px">Действия</th>
        </tr>
    </thead>
    {if $campaigns}
    {foreach from=$campaigns item=campaign}
    <tr {if $campaign.campaignStatus=="D"}class="text-muted"{/if}>
        <td class="tableitem">{if $campaign.logo}<img src="{$offersUrl}{$campaign.logo}" height="50px" />{/if}</td>
        <td class="tableitem">{$campaign.campaignName}</td>
        <td class="tableitem">{$campaign.idCampaign}</td>
        <td class="tableitem">{$campaign.priceOnLanding}</td>
        <td class="tableitem">{$campaign.payedTransactionsInfo}</td>
        <td class="tableitem">{$campaign.unpayedTransactionsInfo}</td>
        <td class="tableitem">
            {if $campaign.campaignStatus=="E"}<i class="fa fa-check-circle text-success font17"></i>{/if}
            {if $campaign.campaignStatus=="D"}<i class="fa fa-minus-circle font17"></i>{/if}
            {if $campaign.campaignType=="P"}<i class="fa fa-eye text-success font17" title="Публичный"></i>{/if}
            {if $campaign.campaignType=="R"}<i class="fa fa-lock text-info font17" title="Приватный"></i>{/if}
            {if $campaign.campaignType=="I"}<i class="fa fa-eye-slash font17" title="Невидимый"></i>{/if}
        </td>
        <td class="tableitem">
            <button class="viewPayments btn btn-default" cid="{$campaign.idCampaign}" title="История оплат транзакций"><i class="fa fa-usd"></i></button>
        </td>
    </tr>
    {/foreach}
    {/if}
</table>

</div>