<form class="edit" accept-charset="utf-8" method="POST" id="reclEditForm" action="">
    <input type="hidden" name="ok" value="1">
    <input type="hidden" name="id" value="{$idRecl}">

    <div class="form-group">
        <label>{$messages.reclName}</label>
        <div class="form-group input-group-sm">
            <input type="text" class="idform-control" name="form[reclName]" value="{$recl.reclName|escape:html}" />
        </div>
    </div>

    <div class="form-group">
        <label>{$messages.reclSalt}</label>
        <div class="form-group input-group-sm">
            <input type="text" class="form-control" name="form[reclSalt]" value="{$recl.reclSalt|escape:html}" />
        </div>
    </div>

    <div class="form-group">
        <label>{$messages.reclApiKey}</label>
        <div class="form-group input-group-sm">
            <input type="text" class="form-control" name="form[reclApiKey]" value="{$recl.reclApiKey|escape:html}" />
        </div>
    </div>

    <div class="form-group input-group-sm">
        <label>{$messages.reclStatus}</label>
        <div class="radio">
            <label class="">
                <input type="radio" name="form[reclStatus]" value="E" {if $recl.reclStatus == 'E'}checked{/if}>Активный <i class="fa fa-check-circle text-success font16" title="test"></i>
            </label>
        </div>
        <div class="radio">
            <label class="">
                <input type="radio" name="form[reclStatus]" value="D" {if $recl.reclStatus == 'D'}checked{/if}>Неактивный <i class="fa fa-minus-circle text-danger font16"></i>
            </label>
        </div>
    </div>

    <div class="form-group">
        <label>{$messages.descr}</label>
        <textarea class="form-control" rows="5" name="form[descr]">{$recl.descr|escape:html}</textarea>
    </div>

    <div class="form-group">
        <input class="btnadd btn btn-primary" type="submit" value="Сохранить" />
    </div>
</form>