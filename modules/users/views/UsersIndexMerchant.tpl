{*Шаблон для отображения списка пользователей с ролью affiliate*}
<script type="text/javascript">
    {literal}
    $(document).ready(function(){
        var usersView = new Users({el: $('#usersContent')});
        usersView.prepareView('merchant');
    });
    {/literal}
</script>



<ul class="nav nav-tabs" style="margin-bottom: 15px;">
    <li class="active">
        <a data-toggle="tab" href="#merchants">Реклы</a>
    </li>
    <li class="">
        <a data-toggle="tab" href="#merchantAccounts">Аккаунты реклов</a>
    </li>
</ul>
<div id="" class="tab-content">
<div id="merchants" class="tab-pane fade active in">
    <div class="col-lg-10">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <form role="form" id="usersSearchForm" name="searchForm">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group input-group-small">
                                    <label>Login</label>
                                    <input type="text" id="search_login" name="form[search_login]" class="form-control" value="" />
                                </div>


                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                            <label>ID</label>
                                            <input type="text" id="search_idUser" name="form[search_idUser]" class="form-control" value="" />
                                            </div>
                                        </div>


                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group input-group-small">
                                    <label>Дата от</label>
                                    <input type="text" id="search_datefrom" name="form[search_datefrom]" class="form-control" value="" />

                                </div>
                                <div class="form-group input-group-small">
                                    <label>Дата до</label>
                                    <input type="text" id="search_dateto" name="form[search_dateto]" class="form-control" value="" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="col-lg-4">
                                    <div class="form-group input-group-small">
                                        <label>Статус</label>
                                        <div class="checkbox">
                                            <label class="">
                                                <input type="checkbox" name="form[search_status][]" value="active" checked>Активный <i class="fa fa-check-circle text-success font16"></i>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label class="">
                                                <input type="checkbox" name="form[search_status][]" value="ban">Бан <i class="fa fa-minus-circle text-danger font16"></i>
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

                        </div>
                        <button class="btn btn-primary" type="submit">Применить</button>
                        <button class="btn btn-default" type="reset">Сбросить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 text-right">
        <input class="btnadd btn btn-primary" id="addUser" rid="{$idRole}" type="button" value="{$messages.newButton}">
        <!--<button class="btnadd btn btn-default" id="addUser" rid="{$idRole}"><i class="fa fa-file"></i></button>-->
    </div>
    <div class="clearfix"></div>

    <div id="alert"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="users">
        <thead>
            <tr>
                <th >Логин</th>
                <th >ФИО</th>
                <th >Доп.инфа</th>
                <th width="80px">Статус</th>
                <th width="200px">Действия</th>
            </tr>
        </thead>
        <tbody>

        </tbody>

    </table>
</div>
<div id="merchantAccounts" class="tab-pane fade">a</div>
</div>


<div id="dialogEdit" title="Редактирование Рекла"></div>
<div id="dialogAdd" title="Добавление Рекла"></div>