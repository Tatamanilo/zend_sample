{*Шаблон для отображения списка пользователей с ролью admin*}
<script type="text/javascript">
    {literal}
    $(document).ready(function(){
        var usersView = new Users({el: $('#usersContent')});
        usersView.prepareView('manager');
    });
    {/literal}
</script>

<div class="row">
    <div class="col-lg-10">
        <div class="panel panel-primary">
            <div class="panel-body">
                <form role="form" id="usersSearchForm" name="searchForm">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group input-group-sm">
                                <label>Login</label>
                                <input type="text" id="search_login" name="form[search_login]" class="form-control" value="" />
                            </div>


                        </div>


                        <div class="col-lg-6">
                                <div class="form-group input-group-sm">
                                    <label>Статус</label>
                                    <div class="checkbox">
                                        <label class="">
                                            <input type="checkbox" name="form[search_status][]" value="active" checked>Активный <i class="fa fa-check-circle text-success font16"></i>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label class="">
                                            <input type="checkbox" name="form[search_status][]" value="delete">Удален <i class="fa fa-times-circle text-warning font16"></i>
                                        </label>
                                    </div>
                                </div>
                        </div>

                    </div>
                    <button class="btn btn-primary" type="submit">Применить</button>
                    <button class="btn btn-default" type="reset">Сбросить</button>
                </form>
            </div>
        </div>

    </div>
    <div class="col-lg-2 text-right">
        <input class="btnadd btn btn-primary" id="addUser" rid="{$idRole}" type="button" value="{$messages.newButton}">
        <!--<button class="btnadd btn btn-default" id="addUser" rid="{$idRole}"><i class="fa fa-file"></i></button>-->
    </div>
</div>
<div class="clearfix"></div>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="users">
    <thead>
        <tr>
            <th >Логин</th>
            <th >Дата регистр.</th>
            <th width="200px">Действия</th>
        </tr>
    </thead>
    <tbody>

    </tbody>

</table>

<div id="dialogEdit" title="Редактирование Менеджера"></div>
<div id="dialogAdd" title="Добавление Менеджера"></div>
<div id="dialogLoginHistory" title="История Логинов Менеджера">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="loginHistory">
        <thead>
            <tr>
                <th >IP</th>
                <th >Дата</th>
                <th >Страна.</th>
                <th >Город</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>