<div id="expandRid{$idRecl}" class="expand-box">
    <ul class="nav nav-tabs" style="margin-bottom: 15px;">
        <li class="active tabs{$idRecl} tabs" tab="campaigns" rid="{$idRecl}">
            <a data-toggle="tab" href="#campaignsRid{$idRecl}">Кампании</a>
        </li>
        <li class="tabs{$idRecl} tabs" tab="users" rid="{$idRecl}">
            <a data-toggle="tab" href="#usersRid{$idRecl}">Пользователи офиса</a>
        </li>
        <li class="tabs{$idRecl} tabs" tab="settings" rid="{$idRecl}">
            <a data-toggle="tab" href="#settingsRid{$idRecl}">Настройки</a>
        </li>
        <li class="pull-right">
            <a href="javascript:void(0)" class="hideReclDetailsBtn" rid="{$idRecl}">
            Свернуть
            <i class="fa fa-caret-up"></i>
            </a>
        </li>
    </ul>
    <div rid="{$idRecl}" class="tab-content">
        <div id="campaignsRid{$idRecl}" class="tab-pane fade active in"></div>
        <div id="usersRid{$idRecl}" class="tab-pane fade"></div>
        <div id="settingsRid{$idRecl}" class="tab-pane fade "></div>
    </div>
</div>