<form class="edit" accept-charset="utf-8" method="POST" id="campaignAddForm" action="">
    <input type="hidden" name="ok" value="1">

    <div id="dialog_alert" class="fade"></div>

    <div class="form-group input-group-sm">
        <label>{$messages.campaignName}</label>
        <input type="text" class="form-control" name="form[campaignName]" value="{$campaign.campaignName|escape:html}" />
    </div>

    <div class="form-group input-group-sm">

        <div class="row">
            <div class="col-lg-6">
                <label>{$messages.logo}</label>
                <br />
                <span class="btn btn-primary fileinput-button">
                    <span>Загрузить лого</span>
                    <!-- The file input field used as target for the file upload widget -->
                    <input id="logoFile" type="file" name="logoFile" />
                    <input id="logo" type="hidden" name="form[logo]" />
                </span>
                <br /><br />
                <div id="progress" class="progress">
                    <div class="progress-bar progress-bar-info"></div>
                </div>
                <!-- The container for the uploaded files -->
                <div id="files" class="files">
                    {if $campaign.logo}
                    <img src="{$campaign.logo}" />
                    {/if}
                </div>
            </div>
            <div class="col-lg-6">
                <label>{$messages.promoMaterials}</label>
                <br />
                <span class="btn btn-primary fileinput-button">
                    <span>Загрузить промо</span>
                    <!-- The file input field used as target for the file upload widget -->
                    <input id="promoMaterialsFile" type="file" name="promoMaterialsFile" />
                    <input id="promoMaterials" type="hidden" name="form[promoMaterials]" />
                </span>
                <br />
                <br />
                <div id="progressPromoMaterials" class="progress">
                    <div class="progress-bar progress-bar-info"></div>
                </div>
                <!-- The container for the uploaded files -->
                <div id="filesPromo" class="files">
                    {if $campaign.promoMaterials}
                    <p>{$campaign.promoMaterials}</p>
                    {/if}
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group input-group-sm">
                <label>{$messages.priceOnLanding}</label>
                <input type="text" class="form-control" name="form[priceOnLanding]" value="{$campaign.priceOnLanding|escape:html}" />
            </div>


            <div class="form-group">
                <label>{$messages.campaignDescr}</label>
                <textarea class="form-control" cols="70" rows="5" name="form[campaignDescr]">{$campaign.campaignDescr|escape:html}</textarea>
            </div>

            <div class="form-group input-group-sm">
                <label>{$messages.campaignStatus}</label>
                <div class="radio">
                    <label class="">
                        <input type="radio" name="form[campaignStatus]" value="E" {if $campaign.campaignStatus == 'E'}checked{/if} checked>Активный <i class="fa fa-check-circle text-success font16" title="test"></i>
                    </label>
                </div>
                <div class="radio">
                    <label class="">
                        <input type="radio" name="form[campaignStatus]" value="D" {if $campaign.campaignStatus == 'D'}checked{/if}>Неактивный <i class="fa fa-minus-circle text-danger font16"></i>
                    </label>
                </div>
            </div>

            <div class="form-group input-group-sm">
                <label>{$messages.campaignType}</label>
                <div class="radio">
                    <label class="">
                        <input type="radio" name="form[campaignType]" value="P" {if $campaign.campaignType == 'P'}checked{/if} checked>Публичные <i class="fa fa-eye text-success font16"></i>
                    </label>
                </div>
                <div class="radio">
                    <label class="">
                        <input type="radio" name="form[campaignType]" value="R" {if $campaign.campaignType == 'R'}checked{/if}>Приватные <i class="fa fa-lock text-info font16"></i>
                    </label>
                </div>
                <div class="radio">
                    <label class="">
                        <input type="radio" name="form[campaignType]" value="I" {if $campaign.campaignType == 'I'}checked{/if}>Невидимые <i class="fa fa-eye-slash font16"></i>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group input-group-sm">
                <label>{$messages.sources}</label>
                {foreach item=source from=$sources}
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="form[sources][]"  {if $campaign.sources&$source.code}checked{/if} checked value="{$source.code}">
                        {$source.name}
                    </label>
                </div>
                {/foreach}
            </div>

            <div class="form-group input-group-sm">
                <label>{$messages.hold}</label>
                <input type="text" class="form-control" name="form[hold]" value="{$campaign.hold|escape:html|default:"0 "}" />
            </div>

            <div class="form-group input-group-sm">
                <label class="checkbox-inline">
                    <input type="checkbox" {if $campaign.holdWithNoDisable}checked{/if} value="1" name="form[holdWithNoDisable]" />
                    <label>{$messages.holdWithNoDisable}<label>
                </label>
            </div>

            <div class="form-group input-group-sm">
                <label class="checkbox-inline">
                    <input type="checkbox" {if $campaign.deepLink}checked{/if} name="form[deepLink]" value="1" />
                    <label>{$messages.deepLink}</label>
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <input class="btnadd btn btn-primary" type="submit" value="Сохранить" />
    </div>
</form>