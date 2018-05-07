
<form id="transactionsPaymentForm{$idCampaign}" class="transactionsPaymentForm">
<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label>{$messages.paySum}</label>
            <input class="form-control input-sm" name="form[paySum]" id="paySum" size="50" value="{$onAccount}">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>{$messages.payDate}</label>
            <input type="text" id="payDate" name="form[payDate]" class="input-sm form-control" value="{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}" />
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <br />
            <input type="hidden" name="idRecl" value="{$idRecl}" />
            <input type="hidden" name="idCampaign" value="{$idCampaign}" />
            <button class="btnadd btn btn-primary addPrice" type="submit" rid="{$idRecl}" cid="{$idCampaign}">Пополнить</button>
        </div>
    </div>
</div>
</form>

<div class="clearfix"></div>

<table cellspacing="0" cellpadding="0" border="0" id="paymentsHistory" class="table table-hover">
    <thead>
        <tr>
            <th width="140px">Сумма</th>
            <th width="140px">Дата</th>
            <th>Транзакции</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
