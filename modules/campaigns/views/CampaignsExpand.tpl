<div id="expandCid{$idCampaign}" class="expand-box">
    <ul class="nav nav-tabs" style="margin-bottom: 15px;">
        <li class="active tabs{$idCampaign} tabs" tab="recls" cid="{$idCampaign}">
            <a data-toggle="tab" href="#reclsCid{$idCampaign}">Рекламодатели</a>
        </li>
        <li class="tabs{$idCampaign} tabs" tab="commissions" cid="{$idCampaign}">
            <a data-toggle="tab" href="#commissionsCid{$idCampaign}">Комиссии</a>
        </li>
        <li class="tabs{$idCampaign} tabs" tab="landings" cid="{$idCampaign}">
            <a data-toggle="tab" href="#landingsCid{$idCampaign}">Лендинги</a>
        </li>
        <li class="tabs{$idCampaign} tabs" tab="layers" cid="{$idCampaign}">
            <a data-toggle="tab" href="#layersCid{$idCampaign}">Прокладки</a>
        </li>
        <li class="pull-right">
            <a href="javascript:void(0)" class="hideCampaignDetailsBtn" cid="{$idCampaign}">
            Свернуть
            <i class="fa fa-caret-up"></i>
            </a>
        </li>
    </ul>
    <div cid="{$idCampaign}" class="tab-content">
        <div id="reclsCid{$idCampaign}" class="tab-pane fade active in"></div>
        <div id="commissionsCid{$idCampaign}" class="tab-pane fade"></div>
        <div id="landingsCid{$idCampaign}" class="tab-pane fade "></div>
        <div id="layersCid{$idCampaign}" class="tab-pane fade"></div>
    </div>
</div>