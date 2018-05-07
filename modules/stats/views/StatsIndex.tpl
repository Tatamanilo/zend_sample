<script type="text/javascript" src="{$jsPath}chosen/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="{$jsPath}chosen/chosen.min.css">

<script type="text/javascript" src="{$jsPath}jquery-ui/js/ui.combobox.js"></script>
<script type="text/javascript" src="{$jsPath}jquery-ui/js/ui.tooltipx.js"></script>
<script type="text/javascript" src="{$jsPath}jquery-ui/js/jquery-ui-timepicker-addon.js"></script>

<script type="text/javascript" src="{$jsPath}datatables/js/jquery.jeditable.js"></script>
<script type="text/javascript" src="{$jsPath}datatables/js/jquery.jeditable.custominput.js"></script>
<script type="text/javascript" src="{$jsPath}datatables/js/jquery.dataTables.fnReloadAjax.js"></script>



<script src="{$jsPath}morris/raphael.min.js"></script>
<script src="{$jsPath}morris/morris.min.js"></script>
<link rel="stylesheet" href="{$jsPath}morris/css/morris.css">


<script type="text/javascript" src="{$jsPath}view/stats.stats.view.js"></script>

<link rel="stylesheet" href="{$jsPath}jquery-ui/css/redmond/jquery-ui-timepicker-addon.css">

<script type="text/javascript">
    {literal}
    var offersUrl = "{/literal}{$offersUrl}{literal}";
    $(document).ready(function(){
        var statsView = new StatsView({el: $('#reclsContent')});
        statsView.prepareView();
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

                <form role="form" id="statsSearchForm" name="searchForm">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group input-group-sm">
                                <label>Данные за последнюю</label>
                                <input type="hidden" id="search_dateperiod" name="form[search_dateperiod]" class="form-control" value="all" />
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="btn-group btn-group-justified">
                                            <a href="javascript:void(0)" style="padding: 4px 12px;" class="btn btn-default periodChangeBtn" period="1 WEEK">неделю</a>
                                            <a href="javascript:void(0)" style="padding: 4px 12px;" class="btn btn-default periodChangeBtn"  period="1 MONTH">месяц</a>
                                            <a href="javascript:void(0)" style="padding: 4px 12px;" class="btn btn-default periodChangeBtn active"  period="all">все</a>
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
                            <div class="form-group input-group-sm">
                                <label>Афф</label>
                                <input type="text" id="search_affName"  class="form-control" value="" />
                                <input type="hidden" id="search_idAff" name="form[search_idAff]" class="form-control" value="" />
                            </div>
                            <div class="form-group input-group-sm">
                                <label>Рекламодатель</label>
                                <input type="text" id="search_reclName"  class="form-control" value="" />
                                <input type="hidden" id="search_idRecl" name="form[search_idRecl]" class="form-control" value="" />
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-group-sm">
                                <label>Оффер</label>
                                <input type="text" id="search_campaignName"  class="form-control" value="" />
                                <input type="hidden" id="search_idCampaign" name="form[search_idCampaign]" class="form-control" value="" />
                            </div>

                            <div class="form-group input-group-sm">
                                <label>Прокладка</label>
                                <select id="search_idLayer" name="form[search_idLayer]" class="form-control">

                                </select>
                            </div>

                            <div class="form-group input-group-sm">
                                <label>Лендинг</label>
                                <select id="search_idLanding" name="form[search_idLanding]" class="form-control">

                                </select>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="search_groupby" name="form[search_groupby]" class="form-control" value="days" />
                    <button class="btn btn-primary" type="submit">Применить</button>
                    <button class="btn btn-default" type="reset">Сбросить</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-2 text-right">
</div>

<div class="clearfix"></div>

<ul class="nav nav-tabs" role="tablist">
  <li class="active groupbyChangeBtn tabs" groupby="days" thtitle="День"><a data-toggle="tab" href="#divStats">По дням</a></li>
  <li class="groupbyChangeBtn tabs" groupby="weeks" thtitle="Неделя"><a data-toggle="tab" href="#divStats">По неделям</a></li>
  <li class="groupbyChangeBtn tabs" groupby="monthes" thtitle="Месяц"><a data-toggle="tab" href="#divStats">По месяцам</a></li>
  <li class="groupbyChangeBtn tabs" groupby="affs" thtitle="Пользователь"><a data-toggle="tab" href="#divStatsUsers">По адвертам</a></li>
</ul>

<div class="tab-content">
    <div id="divStats" class="tab-pane fade active in">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="stats">
            <thead>
                <tr>
                    <th id="groupByTh" width="170px">День</th>
                    <th >Кликов</th>
                    <th >Уник. кликов</th>
                    <th >Всего продаж</th>
                    <th >Одобр.</th>
                    <th >В ожид.</th>
                    <th >Откл.</th>
                    <th >EPC</th>
                    <th >CR</th>
                    <th >CR %</th>
                    <th >% одобр.</th>
                    <th >% одобр. учит. в ож.</th>
                    <th >Должны аффам</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <div class="clearfix"></div>
        <br />

<!--        <div class="row">
            <div class="col-lg-6"> -->
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-bar-chart-o"></i>
                            Клики, продажи
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div id="lineChart"></div>
                        <div id="lineChartKey">
                            <input type="radio" id="radio1" name="radio" value="clicksCount" checked><label for="radio1">Клики</label>
                            <input type="radio" id="radio2" name="radio" value="uniqClicksCount"><label for="radio2">Уник.клики</label>
                            <input type="radio" id="radio3" name="radio" value="transCountAll"><label for="radio3">Продаж</label>
                            <input type="radio" id="radio4" name="radio" value="transCountA"><label for="radio4">Одобрен.</label>
                            <input type="radio" id="radio5" name="radio" value="transCountD"><label for="radio5">Отклон.</label>
                            <input type="radio" id="radio6" name="radio" value="transCountP"><label for="radio6">В ожид.</label>
                        </div>
                    </div>
                </div>

<!--            </div>
            <div class="col-lg-6">  -->
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-bar-chart-o"></i>
                            Обороты
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div id="barChart"></div>
                        <div id="barChartMoney"></div>
                    </div>
                </div>

<!--            </div>
        </div>   -->
    </div>
    <div id="divStatsUsers" class="tab-pane fade">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="statsUsers" useFilterForm="statsSearchForm">
            <thead>
                <tr>
                    <th id="groupByTh" width="170px">Афф</th>
                    <th >Кликов</th>
                    <th >Уник. кликов</th>
                    <th >Всего продаж</th>
                    <th >Одобр.</th>
                    <th >В ожид.</th>
                    <th >Откл.</th>
                    <th >EPC</th>
                    <th >CR</th>
                    <th >CR %</th>
                    <th >% одобр.</th>
                    <th >% одобр. учит. в ож.</th>
                    <th >Должны аффам</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <div class="clearfix"></div>
        <br />

<!--        <div class="row">
            <div class="col-lg-6">   -->
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-bar-chart-o"></i>
                            Клики, продажи
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div id="barChartUsers"></div>
                        <div id="barChartKeyUsers">
                            <input type="radio" id="radioUsers1" name="radioUsers" value="clicksCount" checked><label for="radioUsers1">Клики</label>
                            <input type="radio" id="radioUsers2" name="radioUsers" value="uniqClicksCount"><label for="radioUsers2">Уник.клики</label>
                            <input type="radio" id="radioUsers3" name="radioUsers" value="transCountAll"><label for="radioUsers3">Продаж</label>
                            <input type="radio" id="radioUsers4" name="radioUsers" value="transCountA"><label for="radioUsers4">Одобрен.</label>
                            <input type="radio" id="radioUsers5" name="radioUsers" value="transCountD"><label for="radioUsers5">Отклон.</label>
                            <input type="radio" id="radioUsers6" name="radioUsers" value="transCountP"><label for="radioUsers6">В ожид.</label>
                        </div>
                    </div>
                </div>
<!--
            </div>
            <div class="col-lg-6">   -->
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-bar-chart-o"></i>
                            Обороты
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div id="barChartUsers"></div>
                        <div id="barChartMoneyUsers"></div>
                    </div>
                </div>

            <!--</div>
        </div>  -->
    </div>
</div>









</div>

<div id="dialogEdit" title="Редактирование Рекламодателя" style="overflow-x: hidden;"></div>
<div id="dialogAdd" title="Добавление Рекламодателя" style="overflow-x: hidden;"></div>
<div id="dialogReclUsers" title="Пользователи Рекламодателя" style="overflow-x: hidden;"></div>
<div id="dialogAddUser" title="Пользователи Рекламодателя" style="overflow-x: hidden;"></div>
<div id="dialogEditUser" title="Пользователи Рекламодателя" style="overflow-x: hidden;"></div>
<div id="dialogAccount" title="История пополнения счета" style="overflow-x: hidden;"></div>
<div id="dialogPayments" title="История оплат транзакций" style="overflow-x: hidden;"></div>
