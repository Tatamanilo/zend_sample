

    <div class="form-group input-group-sm">
        <label>Выберите рекламодателей</label>
        <!--<span class="input-group-addon">@</span>-->
        <input class="usersForGroup form-control" size="50">
        <input class="usersIds" name="form[idRecls]" type="hidden" size="50" value="{$reclsIds}">
    </div>
    <div class="row list-group usersSelectedBox">
        {foreach from=$recls item=recl}
        <div class='col-lg-6 user-row-box'><a rid='{$recl.idRecl}' class='list-group-item' href='#'>{$recl.reclName}<i rid='{$recl.idRecl}' class='close fa fa-times _removeUser close-user-row-box'></i></a></div>
        {/foreach}
    </div>

