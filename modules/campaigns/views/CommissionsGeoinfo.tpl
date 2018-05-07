{foreach item=commission from=$commissions}
    {$commission.countries} ({$messages[$commission.target]}) - {$commission.priceAdvert}<br />
{/foreach}