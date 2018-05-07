{if $users}
    {foreach from=$users item=user}
    <div class="{if $user.status!='active'}text-muted{/if}">
        {$user.login} {if $user.name}({$user.name}){/if} {if $user.userRef}- {$user.userRef}{/if}<br />
    </div>
    {/foreach}
{/if}