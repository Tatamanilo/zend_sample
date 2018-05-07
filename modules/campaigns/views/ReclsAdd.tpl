<form accept-charset="utf-8" method="POST" id="reclsAddForm" action="">
    <input type="hidden" name="ok" value="1">
    <input type="hidden" name="id" value="{$idCampaign}">

    <div class="form-group input-group-sm">
        <label>Рекламодатель</label>
        <input type="text" id="reclNameAdd"  class="form-control" value="" />
        <input type="hidden" id="idReclAdd" name="form[idRecl]" class="form-control" value="" />
    </div>

    <div class="form-group input-group-sm">
        <label>Количество заявок</label>
        <input type="text" class="form-control" name="form[transCountPerDay]" value="" />
    </div>

    <div class="form-group">
        <input class="btnadd btn btn-primary" type="submit" value="Сохранить" />
    </div>
</form>

