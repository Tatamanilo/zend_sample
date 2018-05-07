var url = window.location.host;

function changeInfo(val)
{
    $(".direct" + (1 - val)).hide();
    $(".direct" + val).show();
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
    $.showAjaxWait();

    $.post(
       "http://"+url+"/menu/menumanage/changeposition/",
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
               changeMessageBox("Перемещение выполнено успешно")
           }
           else
           {
               changeMessageBox("Ошибка! Перемещение не выполнено")
           }
       }
   );
}

function changeStatus(id)
{
    $.showAjaxWait();

    var status = 1 - $('#bull_'+id).attr("name");

    $.post(
        "http://"+url+"/menu/menumanage/changestatus/",
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
                $('#bull_'+id).attr("name", status);

                $.hideAjaxWait();
                //changeMessageBox("Смена статуса выполнено успешно");
            }
            else
            {
                $.hideAjaxWait();
                //changeMessageBox("Ошибка! Смена статуса не выполнена");
            }
        }
    );

    return false;
}

function toggleList(el)
{
    //+ "ul:first-child"
    $(el).parent().children("ul:first").toggle();
    return false;
}