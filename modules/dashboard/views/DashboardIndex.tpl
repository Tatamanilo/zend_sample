<script src="{$jsPath}morris/raphael.min.js"></script>
<script src="{$jsPath}morris/morris.min.js"></script>
<link rel="stylesheet" href="{$jsPath}morris/css/morris.css">

<script type="text/javascript">
    {literal}
    var offersUrl = "{/literal}{$offersUrl}{literal}";
    $(document).ready(function(){
        //var dashboardView = new DashboardView({el: $('#reclsContent')});
        //dashboardView.prepareView();

        Morris.Donut({
          element: 'morris-chart-donut',
          colors: ['#5cb85c', '#d9534f', '#f0ad4e'],
          data: [
            {label: "Одобренных", value: {/literal}{$transactionsCount.A}{literal}},
            {label: "Отклоненных", value: {/literal}{$transactionsCount.D}{literal}},
            {label: "В ожидании", value: {/literal}{$transactionsCount.P}{literal}}
          ]
        }).select(0);

        Morris.Line({
          element: 'line-example',
          lineColors: ['#428bca', '#5cb85c', '#d9534f', '#f0ad4e'],
          data: [
            { y: '2014-07-29', v: 200, a: 100, b: 90, c: 90 },
            { y: '2014-07-28', v: 210, a: 75,  b: 45, c: 80 },
            { y: '2014-07-27', v: 170, a: 80,  b: 20, c: 50 },
            { y: '2014-07-26', v: 180, a: 85,  b: 15, c: 20 },
            { y: '2014-07-25', v: 190, a: 50,  b: 40, c: 40 },
            { y: '2014-07-24', v: 187, a: 75,  b: 95, c: 40 },
            { y: '2014-07-23', v: 230, a: 100, b: 90, c: 70 }
          ],
          xkey: 'y',
          ykeys: ['v', 'a', 'b', 'c'],
          labels: ['Всего', 'Одобренных', 'Отклоненных', 'В ожидании']
        });
        Morris.Area({
          element: 'area-example',
          lineColors: ['#428bca', '#5cb85c', ],
          data: [
            { y: '2014-07-29', a: 100, b: 90 },
            { y: '2014-07-28', a: 75,  b: 35 },
            { y: '2014-07-27', a: 50,  b: 40 },
            { y: '2014-07-26', a: 75,  b: 15 },
            { y: '2014-07-25', a: 50,  b: 40 },
            { y: '2014-07-24', a: 75,  b: 45 },
            { y: '2014-07-23', a: 100, b: 90 }
          ],
          behaveLikeLine: true,
          xkey: 'y',
          ykeys: ['a', 'b'],
          labels: ['Учитывая в ожидании', 'Не учитывая лиды в ожидании']
        });
    });
    {/literal}
</script>

<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i> Статистика продаж за последнюю неделю</h3>
            </div>
            <div class="panel-body">
                <div class="">

                    <ul class="list-group">
                        <li class="list-group-item" href="#">
                            <span class="label label-primary pull-right font16">{$transactionsCount.all}</span>
                            <i class="fa fa-shopping-cart text-primary font18 fa-fw"></i> Всего
                        </li>
                        <li class="list-group-item" href="#">
                            <span class="label label-success pull-right font16">{$transactionsCount.A}</span>
                            <i class="fa fa-check text-success font18 fa-fw"></i> Одобренных
                        </li>
                        <li class="list-group-item" href="#">
                            <span class="label label-danger pull-right font16">{$transactionsCount.D}</span>
                            <i class="fa fa-truck text-danger font18 fa-fw"></i> Отклоненных
                        </li>
                        <li class="list-group-item" href="#">
                            <span class="label label-warning pull-right font16">{$transactionsCount.P}</span>
                            <i class="fa fa-money text-warning font18 fa-fw"></i> В ожидании
                        </li>
                        <li class="list-group-item" href="#">
                            <span class="label label-success pull-right font16">{$approvedRate}%</span>
                            <i class="fa fa-percent text-success font18 fa-fw"></i> Процент одобрения
                        </li>
                        <li class="list-group-item" href="#">
                            <span class="label label-warning pull-right font16">{$approvedPendingRate}%</span>
                            <i class="fa fa-percent text-warning font18 fa-fw"></i> Процент одобрения учитывая лиды в ожидании
                        </li>
                        <li class="list-group-item" href="#">
                            <span class="label label-success pull-right font16">28</span>
                            <i class="fa fa-check text-success font18 fa-fw"></i> EPC
                        </li>
                        <li class="list-group-item" href="#">
                            <span class="label label-success pull-right font16">45</span>
                            <i class="fa fa-check text-success font18 fa-fw"></i> CR
                        </li>
                    </ul>
                </div>
                <div class="text-right">
                    <a href="#">Детали <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Количество продаж</h3>
            </div>
            <div class="panel-body">
                <div id="morris-chart-donut"></div>
                <div class="text-right">
                    <a href="#">Детали <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Изменение количества продаж</h3>
            </div>
            <div class="panel-body">
                <div id="line-example" style="height: 150px;"></div>
                <div class="text-right">
                    <a href="#">Детали <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Изменение процента одобрений</h3>
            </div>
            <div class="panel-body">
                <div id="area-example" style="height: 150px;"></div>
                <div class="text-right">
                    <a href="#">Детали <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="announcement-heading">26</div>
                        <div>Новых аффилиейтов!</div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <a href="widgets.html#">
                            <img src="http://placehold.it/50x50" alt="Lucas" class="avatar">
                        </a>
                        <strong>Name:</strong> <a href="widgets.html#">Łukasz Holeczek</a><br>
                        <strong>Since:</strong> Jul 25, 2012 11:09<br>
                        <strong>Status:</strong> <span class="label label-success">Approved</span>
                    </li>
                    <li class="list-group-item">
                        <a href="widgets.html#">
                            <img src="http://placehold.it/50x50" alt="Bill" class="avatar">
                        </a>
                        <strong>Name:</strong> <a href="widgets.html#">Bill Cole</a><br>
                        <strong>Since:</strong> Jul 25, 2012 11:09<br>
                        <strong>Status:</strong> <span class="label label-warning">Pending</span>
                    </li>
                    <li class="list-group-item">
                        <a href="widgets.html#">
                            <img src="http://placehold.it/50x50" alt="Jane" class="avatar">
                        </a>
                        <strong>Name:</strong> <a href="widgets.html#">Jane Sanchez</a><br>
                        <strong>Since:</strong> Jul 25, 2012 11:09<br>
                        <strong>Status:</strong> <span class="label label-important">Banned</span>
                    </li>
                    <li class="list-group-item">
                        <a href="widgets.html#">
                            <img src="http://placehold.it/50x50" alt="Kate" class="avatar">
                        </a>
                        <strong>Name:</strong> <a href="widgets.html#">Kate Presley</a><br>
                        <strong>Since:</strong> Jul 25, 2012 11:09<br>
                        <strong>Status:</strong> <span class="label label-info">Updates</span>
                    </li>
                </ul>
            </div>
            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">Детали</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-files-o fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="announcement-heading">12</div>
                        <div>Новых кампаний!</div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <ul class="list-group">
                        <li class="list-group-item message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img alt="" src="http://placehold.it/50x50" class="media-object">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>Кампания1</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img alt="" src="http://placehold.it/50x50" class="media-object">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>Кампания 222</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img alt="" src="http://placehold.it/50x50" class="media-object">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>Кампания 333</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
            </div>
            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">Детали</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="announcement-heading">124</div>
                        <div>Новых одобренных продаж!</div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <strong>
                        <i class="fa fa-arrow-down text-primary"></i>
                        <span class="text-primary">10450 руб.</span>
                        </strong>
                        реклы должны нам
                    </li>
                    <li class="list-group-item">
                        <strong>
                        <i class="fa fa-arrow-up red"></i>
                        <span class="red">4450 руб.</span>
                        </strong>
                        мы должны аффам
                    </li>
                    <li class="list-group-item">
                        <strong>
                        <i class="fa fa-arrow-down green"></i>
                        <span class="green">6000 руб.</span>
                        </strong>
                        должны получить мы
                    </li>
                </ul>
            </div>
            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">Детали</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i> Недавняя активность</h3>
            </div>
            <div class="panel-body">
                <div class="">
                    <ul class="list-group">
                        <li class="list-group-item" href="#">
                            <!--<span class="label label-primary pull-right font18">1200</span>-->
                            <span class="pull-right text-muted small">
                                <em>4 минуты назад</em>
                            </span>
                            <i class="fa fa-money text-danger font20 fa-fw"></i>
                            Рекл Потапенко внес на счет 1000 руб.
                        </li>
                        <li class="list-group-item" href="#">
                            <span class="pull-right text-muted small">
                                <em>14 минуты назад</em>
                            </span>
                            <i class="fa fa-shopping-cart yellow font20 fa-fw"></i>
                            25 новых продаж
                        </li>
                        <li class="list-group-item" href="#">
                            <span class="pull-right text-muted small">
                                <em>35 минут назад</em>
                            </span>
                            <i class="fa fa-user text-primary font20 fa-fw"></i>
                            Зарегистрировался новый аффилиейт Руденко
                        </li>
                        <li class="list-group-item" href="#">
                            <span class="pull-right text-muted small">
                                <em>4 часа назад</em>
                            </span>
                            <i class="fa fa-money text-danger font20 fa-fw"></i>
                            Рекл Потапенко оплатил 5 транзакций на сумму 350 руб.
                        </li>
                        <li class="list-group-item" href="#">
                            <span class="pull-right text-muted small">
                                <em>2 дня назад</em>
                            </span>
                            <i class="fa fa-tasks text-success font20 fa-fw"></i>
                            Создана новая кампания "Чай для похудения"
                        </li>
                    </ul>
                </div>
                <div class="text-right">
                    <a href="#">Детали <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
