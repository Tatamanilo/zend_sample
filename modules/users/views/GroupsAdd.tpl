<form accept-charset="utf-8" method="POST" class="groupAddForm" action="">
    <input type="hidden" name="ok" value="1">

    <div class="form-group input-group-sm">
        <label>Название группы</label>
        <input type="text" class="form-control" name="form[userGroupName]" value="" />
    </div>

    <div class="form-group input-group-sm">
        <label>Выберите пользователя для добавления в группу</label>
        <!--<span class="input-group-addon">@</span>-->
        <input class="usersForGroup form-control" size="50">
        <input class="usersIds" name="form[idUsers]" type="hidden" size="50">
    </div>

    <br />
    <div class="row list-group usersSelectedBox">
    </div>

    <div class="form-group">
        <input class="btnadd btn btn-primary" type="submit" value="Сохранить" />
    </div>
</form>

