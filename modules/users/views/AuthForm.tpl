<div class="row">
<div class="col-lg-4">
</div>
<div class="col-lg-4">
    <form action="{$base_url}{$module}/{$controller}/{$action}/" method="POST">
    <input type="hidden" name="ok_auth" value="ok" />
    <input type="hidden" name="user_type" value="user" />
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">
                <i class="fa fa-key"></i>
                Вход в систему
            </h3>    
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>{$messages.login}</label>
<!--                <div class="input-group-sm input-group">-->
                    <!--<span class="input-group-addon">@</span>-->
                    <input type="text" class="form-control" name="form[login]" value="{$userForm.login|escape:"html"}" />
<!--                </div>-->
            </div>
            <div class="form-group">
                <label>{$messages.pass}</label>
<!--                <div class="input-group-sm input-group">-->
                    <input type="password" class="form-control" name="form[pass]" value="" />
<!--                </div>-->
            </div>
            <div class="form-group"> 
                <input type="submit" class="btn btn-primary" name="submit" value="Войти" />
            </div>
        </div>
    </div>
    </form>
</div>
<div class="col-lg-4">
</div>



