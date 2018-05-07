<div id="reclsContent{$idCampaign}" class="reclsContent" cid="{$idCampaign}">
<div class="row">
    <div class="col-lg-12">
        <button class="btnadd btn btn-default addRecl" cid="{$idCampaign}"><i class="fa fa-plus"></i></button>
    </div>
</div>
<div class="clearfix"></div>

<table cellspacing="0" cellpadding="0" border="0" id="recls{$idCampaign}" class="table table-hover">
    <thead>
        <tr>
            <th>Название</th>
            <th width="280px">Количество заявок в день</th>
        </tr>
    </thead>
    {if $recls}
    {foreach from=$recls item=recl}
    <tr {if $recl.reclStatus=="D"}class="text-muted"{/if}>
        <td class="tableitem">{$recl.reclName}</td>
        <td class="tableitem editTransCountPerDay" rcid={$recl.idReclCampaign}>{$recl.transCountPerDay}</td>
    </tr>
    {/foreach}
    {/if}
</table>

</div>