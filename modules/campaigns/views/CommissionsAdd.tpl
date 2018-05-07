<form accept-charset="utf-8" method="POST" id="commissionsAddForm" action="">
    <input type="hidden" name="ok" value="1">
    <input type="hidden" name="id" value="{$idCampaign}">
    <input type="hidden" name="idCommissionSection" value="{$idCommissionSection}">


    <div class="form-group input-group-sm text-info">
        <label>{if $commissionSection.isGroupCommissionSection}
        Коммиссия для закрытых групп <i class="fa fa-users font16" title="test"></i>
        {else}
        Общая коммиссия
        {/if}
        </label>
        <input type="hidden" name="form[isGroupCommission]" value="{$commissionSection.isGroupCommissionSection}" />
    </div>

    <div class="row">
        <div class="form-group input-group-sm col-lg-6">
            <label>{$messages.commissionName}</label>
            <input type="text" class="form-control" name="form[commissionName]" value="" />
        </div>
        <div class="form-group input-group-sm col-lg-6">
            <label>{$messages.addNumber}</label>
            <input type="text" class="form-control" name="form[addNumber]" value="" />
        </div>
    </div>

    <div class="row">
        <div class="form-group input-group-sm col-lg-6">
            <label>{$messages.idRecl}</label>
            <br />
            <select id="campaignRecls" class="form-control input-group-sm" name="form[idRecl]">
                <option value=""></option>
                {foreach from=$recls item=recl}
                <option value="{$recl.idRecl}" {if $recl.reclStatus=="D"}class="text-muted"{/if}>{$recl.reclName}</option>
                {/foreach}
            </select>
        </div>


        <div class="form-group input-group-sm col-lg-6">
            <label>{$messages.target}</label>
            <br />
            <select id="commissionTarget" class="form-control input-group-sm" name="form[target]">
                {foreach from=$targets item=target}
                <option value="{$target}">{$messages.$target}</option>
                {/foreach}
            </select>
        </div>
    </div>


    <div class="form-group input-group-sm ">
        <label>{$messages.countries}</label>
        <select data-placeholder="Выберите страны..." type="text" id="commissionCountries" name="form[countries][]" class="form-control" multiple>
        {foreach from=$countries item=country}
            <option value="{$country.countryCode}">{$country.countryName}</option>
        {/foreach}
        </select>
    </div>

    <div class="row">
        <div class="form-group input-group-sm col-lg-6">
            <label>{$messages.approveType}</label>
            <div class="radio">
                <label class="">
                    <input type="radio" name="form[approveType]" value="M" {if $commission.approveType == 'M'}checked{/if} checked>Ручной <i class="fa fa-hand-o-up font16"></i>
                </label>
            </div>
            <div class="radio">
                <label class="">
                    <input type="radio" name="form[approveType]" value="A" {if $commission.approveType == 'A'}checked{/if}>Автоматический <i class="fa fa-cogs font16"></i>
                </label>
            </div>
        </div>
        <div class="form-group input-group-sm col-lg-6">
            <label>{$messages.commissionStatus}</label>
            <div class="radio">
                <label class="">
                    <input type="radio" name="form[commissionStatus]" value="E" {if $commission.commissionStatus == 'E'}checked{/if} checked>Активный <i class="fa fa-check-circle text-success font16" title="test"></i>
                </label>
            </div>
            <div class="radio">
                <label class="">
                    <input type="radio" name="form[commissionStatus]" value="D" {if $commission.commissionStatus == 'D'}checked{/if}>Неактивный <i class="fa fa-minus-circle text-danger font16"></i>
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group input-group-sm col-lg-6">
            <label>{$messages.commissionType}</label>
            <div class="radio">
                <label class="">
                    <input type="radio" name="form[commissionType]" value="P" {if $commission.commissionType == 'P'}checked{/if} checked>Проценты <i class="fa fa-percent text-info font16"></i>
                </label>
            </div>
            <div class="radio">
                <label class="">
                    <input type="radio" name="form[commissionType]" value="S" {if $commission.commissionType == 'S'}checked{/if}>Сумма <i class="fa fa-usd text-success font16"></i>
                </label>
            </div>
        </div>

    </div>

    <div class="form-group">
        <input class="btnadd btn btn-primary" type="submit" value="Сохранить" />
    </div>
</form>

