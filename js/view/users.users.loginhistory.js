var LoginHistory = Backbone.View.extend({
    prefix: 'users',
    
    table: null,

    events: {
        
    },
    
    // main func, prepare page
    prepareView: function (uid){
        self = this;
        
        $("#dialogLoginHistory").dialog('open');
        
        this.renderTable(uid);
        
    },
    
    // render table stucture and data
    renderTable: function (uid) {
        var url = '';
        var aoColumns = [];
        var caption = '';

        url = '/users/users/loginhistory/id/' + uid;
        aoColumns = [
            { "mData": 'ip' },
            { "mData": 'actionDate' },
            { "mData": 'country' },
            { "mData": 'city' }
        ];
        
        LoginHistory.table = $('#loginHistory').dataTable( {
            //"fnDrawCallback": this.actionsPrepare, 
            "aoColumns": aoColumns,
            "aaSorting": [[ 0, "desc" ]] ,
            "sPaginationType": "bootstrap",
            "bLengthChange": true,
            "aLengthMenu": [20, 50, 100, 200, 500],
            "iDisplayLength": 20,
            "bProcessing": true,
            "bDestroy": true,
            "bFilter": false,
            "bInfo": false,
            "sDom": "frt<Lip>",
            "oLanguage": {
                "sLengthMenu": "<div style='float: left; padding-right: 10px;padding-top: 10px;'>Показать</div><div style='float:left;'> _MENU_ </div><div style='float: left; padding-top: 10px; padding-left: 10px;'>записей</div><div style='clear: both;'></div>"
            },
            "bServerSide": true,
            "sAjaxSource": url,
            "sServerMethod": "POST",
            "fnServerData": SMP.makeRequestDT
        } );
    },
    
    // redraw table according to current filter
    reloadTable: function (){
        
        $('#users').dataTable().fnDraw();
    }
});

loginHistory = new LoginHistory();
