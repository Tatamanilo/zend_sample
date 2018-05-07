<form accept-charset="utf-8" method="POST" id="layersEditForm" action="">
    <input type="hidden" name="ok" value="1">
    <input type="hidden" name="id" value="{$idLayer}">



    <div class="form-group input-group-sm ">
        <label>{$messages.layerName}</label>
        <input type="text" class="form-control" name="form[layerName]" value="{$layer.layerName}" />
    </div>

    <div class="form-group input-group-sm">
        <label>{$messages.layerLink}</label>
        <input type="text" class="form-control" name="form[layerLink]" value="{$layer.layerLink}" />
    </div>

    <div class="form-group input-group-sm">
        <label>{$messages.defaultLink}</label>
        <input type="text" class="form-control" name="form[defaultLink]" value="{$layer.defaultLink}" />
    </div>

    <div class="form-group input-group-sm">
        <label>{$messages.epc}</label>

        <div class="row">
            <div class="col-lg-6">
                <div class="btn-group btn-group-vertical">
                    <input type="hidden" id="epcIsNew" name="form[epcIsNew]" value="{$layer.epcIsNew}">
                    <a href="javascript:void(0)" style="padding: 4px 12px;" class="btn btn-default  {if $layer.epcIsNew}active{/if} epcChangeBtn" val="new">Новый</a>
                    <a href="javascript:void(0)" style="padding: 4px 12px;" class="btn btn-default {if !$layer.epcIsNew}active{/if} epcChangeBtn" val="recalc">Перерасчет</a>
                </div>
            </div>

            <div class="col-lg-6" id="epcDiv" {if $layer.epcIsNew}style="display: none;"{/if}>
                <div class="input-group input-group-sm">
                    <span class="input-group-addon" style="width: 100px;">Сегодня</span>
                    <input type="text" id="epcToday" class="form-control input-sm" name="form[epcToday]" value="{$layer.epcToday}" readonly>
                </div>
                <div class="input-group input-group-sm">
                    <span class="input-group-addon" style="width: 100px;">Вчера</span>
                    <input type="text" id="epcYesterday" class="form-control input-sm" name="form[epcYesterday]" value="{$layer.epcYesterday}" readonly>
                </div>
                <div class="input-group input-group-sm">
                    <span class="input-group-addon" style="width: 100px;">Неделя</span>
                    <input type="text" id="epcWeek" class="form-control input-sm" name="form[epcWeek]" value="{$layer.epcWeek}" readonly>
                </div>
                <div class="input-group input-group-sm">
                    <span class="input-group-addon" style="width: 100px;">Общий</span>
                    <input type="text" id="epcAll" class="form-control input-sm" name="form[epcAll]" value="{$layer.epcAll}" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group input-group-sm col-lg-6">
            <label>{$messages.layerStatus}</label>
            <div class="radio">
                <label class="">
                    <input type="radio" name="form[layerStatus]" value="E" {if $layer.layerStatus == 'E'}checked{/if} >Активный <i class="fa fa-check-circle text-success font16" title="test"></i>
                </label>
            </div>
            <div class="radio">
                <label class="">
                    <input type="radio" name="form[layerStatus]" value="D" {if $layer.layerStatus == 'D'}checked{/if}>Неактивный <i class="fa fa-minus-circle text-danger font16"></i>
                </label>
            </div>
        </div>
        <div class="form-group input-group-sm col-lg-6">
            <label>{$messages.forPrivate}</label>
            <div class="checkbox">
                <label class="">
                    <input type="checkbox" name="form[forPrivate]" value="1" {if $layer.forPrivate}checked{/if} >Приватный <i class="fa fa-users text-success font16" title="test"></i>
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <input class="btnadd btn btn-primary" type="submit" value="Сохранить" />
    </div>
</form>

