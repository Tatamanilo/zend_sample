{*Шаблон для отображения списка пользователей с ролью affiliate*}
<script type="text/javascript">
    {literal}

    $(document).ready(function(){
        var usersView = new Users({el: $('#usersContent')});
        usersView.prepareView('affiliate');
    });
    {/literal}
</script>

<div class="col-lg-10">
    <div class="row">
        <div class="panel panel-primary">
            <!--
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-search"></i>
                    Фильтр
                </h3>
            </div>  -->
            <div class="panel-body">

                <form role="form" id="usersSearchForm" name="searchForm">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group input-group-sm">
                                <label>Login</label>
                                <input type="text" id="search_login" name="form[search_login]" class="form-control" value="" />
                            </div>

                            <div class="form-group input-group-sm">
                                <label>Регистрация за последний</label>
                                <input type="hidden" id="search_dateperiod" name="form[search_dateperiod]" class="form-control" value="1 DAY" />
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="btn-group btn-group-justified">
                                            <a href="javascript:void(0)" style="padding: 4px 12px;" class="btn btn-default active periodChangeBtn" period="1 DAY">1 день</a>
                                            <a href="javascript:void(0)" style="padding: 4px 12px;" class="btn btn-default periodChangeBtn"  period="3 DAY">3 дня</a>
                                            <a href="javascript:void(0)" style="padding: 4px 12px;" class="btn btn-default periodChangeBtn"  period="1 WEEK">1 нед.</a>
                                            <a href="javascript:void(0)" style="padding: 4px 12px;" class="btn btn-default periodChangeBtn"  period="all">все</a>
                                            <a href="javascript:void(0)" style="padding: 4px 12px;" class="btn btn-default periodChangeBtn" period="custom">...</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="periodCustomBox" style="display: none;">
                                    <p></p>
                                    <div class="col-lg-6">
                                        <input type="text" id="search_datefrom" name="form[search_datefrom]" class="input-sm form-control" disabled value="" />
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" id="search_dateto" name="form[search_dateto]" class="input-sm form-control" disabled value="" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group input-group-sm">
                                <label>ID</label>
                                <input type="text" id="search_userRef" name="form[search_userRef]" class="form-control" value="" />
                            </div>
                            <div class="form-group input-group-sm">
                                <label>WMR</label>
                                <input type="text" id="search_wmr" name="form[search_wmr]" class="form-control" value="" />

                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="col-lg-4">
                                <div class="form-group input-group-sm">
                                    <label>Статус</label>
                                    <div class="checkbox">
                                        <label class="">
                                            <input type="checkbox" name="form[search_status][]" value="active" checked>{$messages.activeStatus} <i class="fa fa-check-circle text-success font16" title="test"></i>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label class="">
                                            <input type="checkbox" name="form[search_status][]" value="wait" checked>{$messages.waitStatus} <i class="fa fa-question-circle font16"></i>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label class="">
                                            <input type="checkbox" name="form[search_status][]" value="ban">{$messages.banStatus} <i class="fa fa-minus-circle text-danger font16"></i>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label class="">
                                            <input type="checkbox" name="form[search_status][]" value="delete">{$messages.deleteStatus} <i class="fa fa-times-circle text-warning font16"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group input-group-sm">
                                    <label>Проверен</label>
                                    <div class="checkbox">
                                        <label class="">
                                            <input type="checkbox" name="form[search_checked][]" value="1" checked>Проверен <i class="fa fa-thumbs-up text-success font16"></i>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label class="">
                                            <input type="checkbox" name="form[search_checked][]" value="0" checked>Не проверен <i class="fa fa-thumbs-down text-danger font16"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group input-group-sm">
                                    <label>Выплаты</label>
                                    <div class="checkbox">
                                        <label class="">
                                            <input type="checkbox" name="form[search_freeze][]" value="1" checked>Заморожен <i class="fa fa-lock text-info font16"></i>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label class="">
                                            <input type="checkbox" name="form[search_freeze][]" value="0" checked>Не заморож. <i class="fa fa-unlock font16"></i>
                                        </label>
                                    </div>
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
</div>
<div class="col-lg-2 text-right">
    <!--<input class="btnadd btn btn-primary" id="addUser" rid="{$idRole}" type="button" value="{$messages.newButton}">-->
    <!--<button class="btnadd btn btn-default" id="addUser" rid="{$idRole}"><i class="fa fa-file"></i></button>-->
</div>
<div class="clearfix"></div>


<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="users">
    <thead>
        <tr>
            <th >ID</th>
            <th >Логин</th>
            <th >Дата регистр.</th>
            <th >Кол. входов</th>
            <th width="180px">Статус</th>
            <th width="200px">Действия</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div id="dialogEdit" title="Редактирование Аффилиейта"></div>
<div id="dialogAdd" title="Добавление Аффилиейта"></div>
<div id="dialogLoginHistory" title="История Логинов Аффилиейта">
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