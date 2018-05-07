<form class="edit" accept-charset="utf-8" method="POST" id="userEditForm" action="">
    <input type="hidden" name="id" value="{$user.idUser}">
    <input type="hidden" name="ok" value="1">
    
    <div class="form-group">
        <label>{$messages.login}</label>
        <div class="input-group-sm input-group">
            <span class="input-group-addon">@</span>
            <input type="text" class="form-control" name="form[login]" value="{$user.login|escape:html}" />
        </div>
    </div>
    
    <div class="form-group input-group-sm">
        <label>{$messages.pass}</label>
        <input type="password" class="form-control" name="form[pass]" value="" />
    </div>
     
     <div class="form-group input-group-sm">
        <label>{$messages.name}</label>
        <input type="text" class="form-control" name="form[name]" value="{$user.name|escape:html}" />
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group input-group-sm">
                <label>{$messages.wmr}</label>
                <input type="text" class="form-control" id="wmr" {if $user.wmr}disabled{/if} name="form[wmr]" value="{$user.wmr|escape:html}" />
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group input-group-sm">
                <label>{$messages.wmid}</label>
                <input type="text" class="form-control" id="wmid" readonly name="form[wmid]" value="{$user.wmid|escape:html}" />
            </div>
        </div>
    </div>
        
    
    <div class="form-group input-group-sm">
        <label>{$messages.status}</label>
        <select class="form-control" name="form[status]">
                    <option value="active" {if $user.status == 'active'}selected="selected"{/if}>{$messages.activeStatus}</option>
                    <option value="wait" {if $user.status == 'wait'}selected="selected"{/if}>{$messages.waitStatus}</option>
                    <option value="ban" {if $user.status == 'ban'}selected="selected"{/if}>{$messages.banStatus}</option>
                    <option value="delete" {if $user.status == 'delete'}selected="selected"{/if}>{$messages.deleteStatus}</option>
        </select>
    </div>
    
    <div class="row">      
        <div class="col-lg-8">
            <div class="form-group">
                <label>Холд</label>
                <label class="radio-inline">
                    <input type="radio" {if $user.holdDisabled==1}checked="checked"{/if} value="1" name="form[holdDisabled]">
                    Отключен
                </label>
                
                <label class="radio-inline">
                    <input type="radio" {if $user.holdDisabled==0}checked="checked"{/if} value="0" name="form[holdDisabled]">
                    Включен
                </label>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group text-right">
                <input class="btnadd btn btn-default" id="resetHoldBtn" uid="{$user.idUser}" type="button" value="Сбросить холд" />
            </div>
        </div>
    </div>
    
    
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <input class="btnadd btn btn-primary" type="submit" value="Сохранить" />
                <!--<button class="btnadd btn btn-primary" id="addUser" rid="{$idRole}"><i class="fa fa-floppy-o font16"></i></button>-->
            </div>
        </div>
        <div class="col-lg-6 text-right">
                <button type="button" class="_loginHistoryInEdit btn btn-default" role="1" uid="{$user.idUser}" title="История логинов"><i class="fa fa-list-alt"></i></button>
        </div>
    </div>

</form>