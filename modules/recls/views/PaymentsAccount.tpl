
<form id="accountPaymentForm{$idRecl}" class="accountPaymentForm">
<div class="row">
    <div class="col-lg-2">
        <div class="form-group">
            <label>{$messages.paySum}</label>
            <input class="form-control input-sm" name="form[paySum]" id="paySum" size="50">
        </div>
    </div>
    {*<div class="col-lg-3">
        <div class="form-group">
            <label>{$messages.payDate}</label>
            <input type="text" id="payDate" name="form[payDate]" class="input-sm form-control" value="{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}" />
        </div>
    </div>*}
    <div class="col-lg-2">
        <div class="form-group">
            <label>{$messages.paySystem}</label>
            <select class="form-control input-sm" name="form[paySystem]" id="paySystem" >
                <option value="wm">{$messages.wm}</option>
                <option value="bank">{$messages.bank}</option>
            </select>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label>Статус</label>
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="1" name="form[approve]" id="approve" checked>
                    Подтвержден
                </label>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>{$messages.descr}</label>
            <input class="form-control input-sm" name="form[descr]" id="descr">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <br />
            <input type="hidden" name="idRecl" value="{$idRecl}" />
            <button class="btnadd btn btn-primary addPrice" type="submit" rid="{$idRecl}">Пополнить</button>
        </div>
    </div>
</div>
</form>

<div class="clearfix"></div>

<table cellspacing="0" cellpadding="0" border="0" id="accountHistory" class="table table-hover">
    <thead>
        <tr>
            <th width="140px">Сумма</th>
            <th width="140px">Дата</th>
            <th>Тип</th>
            <th>Статус</th>
            <th>Описание</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
