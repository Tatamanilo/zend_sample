
{if !$listOnly}
<form id="addPriceForm{$idCommission}" class="addPriceForm">
<div class="row nav-tabs">
    <div class="col-lg-2">
        <div class="form-group">
            <label>Цена рекла</label>
            <input class="form-control input-sm" name="form[priceRecl]" id="priceRecl" size="50">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label>Цена аффа</label>
            <input class="form-control input-sm" name="form[priceAdvert]" id="priceAdvert" size="50">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Дата от</label>
            <input type="text" id="validFrom" name="form[validFrom]" class="input-sm form-control" value="" />
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Дата до</label>
            <input type="text" id="validTo" name="form[validTo]" class="input-sm form-control" value="" />
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <br />
            <input type="hidden" name="idCommission" value="{$idCommission}" />
            <button class="btnadd btn btn-primary addPrice" type="submit" csid="{$idCommission}">Добавить цену</button>
        </div>
    </div>
</div>
</form>

<div class="clearfix"></div>
{/if}

<table cellspacing="0" cellpadding="0" border="0" id="csprices{$idCommission}" class="table table-hover">
    <thead>
        <tr>
            <th width="140px">Цена рекла</th>
            <th width="140px">Цена аффа</th>
            <th>Дата от</th>
            <th>Дата до</th>
        </tr>
    </thead>
    {if $prices}
    {foreach from=$prices item=price}
    <tr id="commissionPriceRow{$price.idCommissionPrice}">
        <td class="tableitem editPriceData" cpid="{$price.idCommissionPrice}" fname="priceRecl">{$price.priceRecl}</td>
        <td class="tableitem editPriceData" cpid="{$price.idCommissionPrice}" fname="priceAdvert">{$price.priceAdvert}</td>
        <td class="tableitem editPriceValidDate" cpid="{$price.idCommissionPrice}" fname="validFrom">{$price.validFrom}</td>
        <td class="tableitem editPriceValidDate" cpid="{$price.idCommissionPrice}" fname="validTo">{$price.validTo}</td>
    </tr>
    {/foreach}
    {/if}
</table>
