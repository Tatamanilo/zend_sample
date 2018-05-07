var url = window.location.host;

function changeInfo(val)
{
    $(".isLink" + (1 - val)).hide();
    $(".isLink" + val).show();
}

function initTree()
{
    $("#basic_html").tree({
        ui: {
            dots: false,
            animation: 300
        },
        callback: {
            onmove: changePosition
        }
    });

    $("#basic_html a, #basic_html div a").each(
        function(){
            $(this).hover(
                function () {
                    $(this).parents("li").css("background-color","#D9EDF7");
                    $(this).parents("li").parents("li").css("background-color","transparent");
                },
                function () {
                    $(this).parents("li").css("background-color","transparent");
                }
            );
        }
    );
}

function changePosition(node, ref_node, type, tree_obj, rb)
{
    $.post(
       "http://"+url+"/menu/menuback/changeposition/",
       {
           "ajax": "ajax",
           "form[id]": node.id,
           "form[idRef]": ref_node.id,
           "form[type]": type
       },
       function (data)
       {
           if (data)
           {
               $.showSuccessBox("Перемещение выполнено успешно");
           }
           else
           {
               $.showErrorBox("Ошибка! Перемещение не выполнено");
           }
       }
   );
}

function changeStatus(id)
{
    $.showAjaxWait();

    var status = 1 - $('#bull_'+id).attr("name");

    $.post(
        "http://"+url+"/menu/menuback/changestatus/",
        {
           "ajax": "ajax",
            "id":     id,
            "status": status
        },
        function( data )
        {
            if( data == 1)
            {
                if (status == 1)
                {
                    $('#bull_'+id).html('<i class="fa fa-check-circle text-success font17"></i>');
                }
                else
                {
                    $('#bull_'+id).html('<i class="fa fa-minus-circle text-danger font17"></i>');
                }
                $('#bull_'+id).attr("name",status);

                $.showSuccessBox("Смена статуса выполнено успешно");
            }
            else
            {
                $.showErrorBox("Ошибка! Смена статуса не выполнена");
            }
            $.hideAjaxWait();
        }
    );

    return false;
}

function showAdditionalActions(val, id, actions_str)
{
    $.showAjaxWait();

    $.post(
        "http://"+url+"/menu/menuback/additionalactions/",
        {
           "ajax": "ajax",
            "mca": val,
            "actionsString": actions_str,
            "id": id
        },
        function( data )
        {
            if(data)
            {
                $("#td_additional_actions").html(data);
            }
            $.hideAjaxWait();
        }
    );
    return false;
}