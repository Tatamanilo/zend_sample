<script type="text/javascript" src="{$jsPath}chosen/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="{$jsPath}chosen/chosen.min.css">

<script type="text/javascript" src="{$jsPath}jquery-ui/js/ui.combobox.js"></script>
<script type="text/javascript" src="{$jsPath}jquery-ui/js/ui.tooltipx.js"></script>
<script type="text/javascript" src="{$jsPath}jquery-ui/js/jquery-ui-timepicker-addon.js"></script>

<script type="text/javascript" src="{$jsPath}datatables/js/jquery.jeditable.js"></script>
<script type="text/javascript" src="{$jsPath}datatables/js/jquery.jeditable.custominput.js"></script>
<script type="text/javascript" src="{$jsPath}datatables/js/jquery.jeditable.datetimepicker.js"></script>

<script type="text/javascript" src="{$jsPath}datatables/js/jquery.dataTables.rowGrouping.js"></script>


<script type="text/javascript" src="{$jsPath}view/campaigns.campaigns.view.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.campaigns.add.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.campaigns.edit.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.campaigns.expand.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.campaigns.users.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.landings.view.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.landings.add.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.landings.edit.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.landings.users.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.layers.view.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.layers.add.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.layers.edit.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.layers.users.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.commissions.view.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.commissions.add.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.commissions.edit.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.commissions.users.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.commissions.prices.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.recls.view.js"></script>
<script type="text/javascript" src="{$jsPath}view/campaigns.recls.add.js"></script>

<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="{$jsPath}fileupload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="{$jsPath}fileupload/js/jquery.fileupload.js"></script>

<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="{$jsPath}fileupload/css/jquery.fileupload.css">
<link rel="stylesheet" href="{$jsPath}jquery-ui/css/redmond/jquery-ui-timepicker-addon.css">





<script type="text/javascript">
    {literal}
    var offersUrl = "{/literal}{$offersUrl}{literal}";
    $(document).ready(function(){
        var campaignsView = new CampaignsView({el: $('#campaignsContent')});
        campaignsView.prepareView();
    });

    {/literal}
</script>


<div id="campaignsContent">

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

                <form role="form" id="campaignsSearchForm" name="searchForm">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group input-group-sm">
                                <label>Оффер</label>
                                <input type="text" id="search_campaignName"  class="form-control" value="" />
                                <input type="hidden" id="search_idCampaign" name="form[search_idCampaign]" class="form-control" value="" />
                            </div>

                            <div class="form-group input-group-sm">
                                <label>Категории</label>
                                <input type="text" id="search_categories" name="form[search_categories]" class="form-control" value="" />
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group input-group-sm">
                                <label>Рекламодатель</label>
                                <input type="text" id="search_reclName"  class="form-control" value="" />
                                <input type="hidden" id="search_idRecl" name="form[search_idRecl]" class="form-control" value="" />
                            </div>
                            <div class="form-group input-group-sm">
                                <label>Гео</label><br />
                                <select data-placeholder="Выберите страны..." type="text" id="search_countries" name="form[search_countries][]" class="form-control" multiple>
                                {foreach from=$countries item=country}
                                    <option value="{$country.countryCode}">{$country.countryName}</option>
                                {/foreach}
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group input-group-sm">
                                <label>Статус</label>
                                <div class="checkbox">
                                    <label class="">
                                        <input type="checkbox" name="form[search_campaignStatus][]" value="E" checked>Активный <i class="fa fa-check-circle text-success font16" title="test"></i>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label class="">
                                        <input type="checkbox" name="form[search_campaignStatus][]" value="D">Неактивный <i class="fa fa-minus-circle text-danger font16"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group input-group-sm">
                                <label>Тип</label>
                                <div class="checkbox">
                                    <label class="">
                                        <input type="checkbox" name="form[search_campaignType][]" value="P" checked>Публичные <i class="fa fa-eye text-success font16"></i>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label class="">
                                        <input type="checkbox" name="form[search_campaignType][]" value="R" checked>Приватные <i class="fa fa-lock text-info font16"></i>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label class="">
                                        <input type="checkbox" name="form[search_campaignType][]" value="I" >Невидимые <i class="fa fa-eye-slash font16"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group input-group-sm">
                                <label>Цель</label>
                                {foreach item=target from=$targets}
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="form[search_targets][]" value="{$target}" checked>{$messages.$target}
                                    </label>
                                {/foreach}
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
    <input class="btnadd btn btn-primary" id="addCampaign" type="button" value="{$messages.newButton}">
    <!--<button class="btnadd btn btn-default" id="addUser" rid="{$idRole}"><i class="fa fa-file"></i></button>-->
</div>

<div class="clearfix"></div>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="campaigns">
    <thead>
        <tr>
            <th >Лого</th>
            <th >ID</th>
            <th >Название</th>
            <th >Гео</th>
            <th >Цена на ленд.</th>
            <th >% подтверждения</th>
            <th width="130px">Статус</th>
            <th width="175px">Действия</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

</div>

<div id="dialogEdit" title="Редактирование Оффера" style="overflow-x: hidden;"></div>
<div id="dialogAdd" title="Добавление Оффера" style="overflow-x: hidden;"></div>
<div id="dialogCampaignUsers" title="Пользователи Оффера" style="overflow-x: hidden;"></div>
<div id="dialogAddRecl" title="Добавление Рекламодателя" ></div>
<div id="dialogAddCommission" title="Добавление Коммиссии" style="overflow-x: hidden;"></div>
<div id="dialogEditCommission" title="Редактирование Коммиссии" style="overflow-x: hidden;"></div>
<div id="dialogCommissionUsers" title="Пользователи Коммиссии" style="overflow-x: hidden;"></div>
<div id="dialogCommissionPrices" title="Цены Коммиссии"></div>
<div id="dialogAddLanding" title="Добавление Лендинга" style="overflow-x: hidden;"></div>
<div id="dialogEditLanding" title="Редактирование Лендинга" style="overflow-x: hidden;"></div>
<div id="dialogLandingUsers" title="Пользователи Лендинга" style="overflow-x: hidden;"></div>
<div id="dialogAddLayer" title="Добавление Прокладки" style="overflow-x: hidden;"></div>
<div id="dialogEditLayer" title="Редактирование Прокладки" style="overflow-x: hidden;"></div>
<div id="dialogLayerUsers" title="Пользователи Прокладки" style="overflow-x: hidden;"></div>
