<script type="text/javascript" src="{$jsPath}jquery-ui/js/ui.tooltipx.js"></script>

<script type="text/javascript" src="{$jsPath}view/users.users.view.js"></script>
<script type="text/javascript" src="{$jsPath}view/users.users.edit.js"></script>
<script type="text/javascript" src="{$jsPath}view/users.users.add.js"></script>
<script type="text/javascript" src="{$jsPath}view/users.users.loginhistory.js"></script>


<div id="usersContent">
    {if $idRole == 1}
        {include file="file:`$templateDir`UsersIndexAdmin.tpl"}
    {elseif $idRole == 3}
        {include file="file:`$templateDir`UsersIndexAffiliate.tpl"}
    {elseif $idRole == 4}
        {include file="file:`$templateDir`UsersIndexMerchant.tpl"}
    {elseif $idRole == 6}
        {include file="file:`$templateDir`UsersIndexSupport.tpl"}
    {elseif $idRole == 7}
        {include file="file:`$templateDir`UsersIndexManager.tpl"}
    {/if}
</div>