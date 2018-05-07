<script type="text/javascript" src="{$jsPath}chosen/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="{$jsPath}chosen/chosen.min.css">

<script type="text/javascript" src="{$jsPath}jquery-ui/js/ui.combobox.js"></script>
<script type="text/javascript" src="{$jsPath}jquery-ui/js/ui.tooltipx.js"></script>
<script type="text/javascript" src="{$jsPath}jquery-ui/js/jquery-ui-timepicker-addon.js"></script>

<script type="text/javascript" src="{$jsPath}datatables/js/jquery.jeditable.js"></script>
<script type="text/javascript" src="{$jsPath}datatables/js/jquery.jeditable.custominput.js"></script>


<script src="{$jsPath}morris/raphael.min.js"></script>
<script src="{$jsPath}morris/morris.min.js"></script>
<link rel="stylesheet" href="{$jsPath}morris/css/morris.css">


<script type="text/javascript" src="{$jsPath}view/recls.recls.view.js"></script>
<script type="text/javascript" src="{$jsPath}view/recls.recls.add.js"></script>
<script type="text/javascript" src="{$jsPath}view/recls.recls.edit.js"></script>
<script type="text/javascript" src="{$jsPath}view/recls.recls.expand.js"></script>
<script type="text/javascript" src="{$jsPath}view/recls.campaigns.view.js"></script>
<script type="text/javascript" src="{$jsPath}view/recls.users.view.js"></script>
<script type="text/javascript" src="{$jsPath}view/recls.users.add.js"></script>
<script type="text/javascript" src="{$jsPath}view/recls.users.edit.js"></script>
<script type="text/javascript" src="{$jsPath}view/recls.payments.account.js"></script>
<script type="text/javascript" src="{$jsPath}view/recls.payments.payments.js"></script>

<link rel="stylesheet" href="{$jsPath}jquery-ui/css/redmond/jquery-ui-timepicker-addon.css">

<script type="text/javascript">
    {literal}
    var offersUrl = "{/literal}{$offersUrl}{literal}";
    $(document).ready(function(){
        var reclsView = new ReclsView({el: $('#reclsContent')});
        reclsView.prepareView();
    });
    {/literal}
</script>


<div id="reclsContent">

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

                <form role="form" id="reclsSearchForm" name="searchForm">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group input-group-sm">
                                <label>Рекламодатель</label>
                                <input type="text" id="search_reclName"  class="form-control" value="" />
                                <input type="hidden" id="search_idRecl" name="form[search_idRecl]" class="form-control" value="" />
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-group-sm">
                                <label>Статус</label>
                                <div class="checkbox">
                                    <label class="">
                                        <input type="checkbox" name="form[search_reclStatus][]" value="E" checked>Активный <i class="fa fa-check-circle text-success font16" title="test"></i>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label class="">
                                        <input type="checkbox" name="form[search_reclStatus][]" value="D">Неактивный <i class="fa fa-minus-circle text-danger font16"></i>
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
</div>
<div class="col-lg-2 text-right">
    <input class="btnadd btn btn-primary" id="addRecl" type="button" value="{$messages.newButton}">
</div>

<div class="clearfix"></div>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="recls">
    <thead>
        <tr>
            <th >{$messages.reclName}</th>
            <th >{$messages.onAccount}</th>
            <th >{$messages.unpayed}</th>
            <th >{$messages.balance}</th>
            <th width="130px">Статус</th>
            <th width="175px">Действия</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div class="clearfix"></div>

<div id="line-example"></div>
<div id="donut-example1"></div>

</div>

<div id="dialogEdit" title="Редактирование Рекламодателя" style="overflow-x: hidden;"></div>
<div id="dialogAdd" title="Добавление Рекламодателя" style="overflow-x: hidden;"></div>
<div id="dialogReclUsers" title="Пользователи Рекламодателя" style="overflow-x: hidden;"></div>
<div id="dialogAddUser" title="Пользователи Рекламодателя" style="overflow-x: hidden;"></div>
<div id="dialogEditUser" title="Пользователи Рекламодателя" style="overflow-x: hidden;"></div>
<div id="dialogAccount" title="История пополнения счета" style="overflow-x: hidden;"></div>
<div id="dialogPayments" title="История оплат транзакций" style="overflow-x: hidden;"></div>
