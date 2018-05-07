var ReclsPayments = Backbone.View.extend({
    prefix: 'reclsPayments',
    useDialog: true,
    table: null,
    idRecl: null,
    idCampaign: null,
    events: {
        "submit .transactionsPaymentForm": "addTransactionsPayment"
    },

    addTransactionsPayment: function (event) {
        var self = this;
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();

        SMP.makeRequest(
            '/recls/payments/addtransactionspayment',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.refreshTable();
                }
            }
        );
    },

    refreshTable: function () {
        $('#paymentsHistory').dataTable().fnDraw(false);
    },

    prepareView: function () {
        var self = this;

        /*
        $('#payDate').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss'
        });
        */

        self.renderTable();
    },

    // render table stucture and data
    renderTable: function () {
        var self = this;

        var url = '';
        var colModel = [];
        var caption = '';
        url = '/recls/payments/paymentshistory/idRecl/' + this.idRecl + '/idCampaign/' + this.idCampaign;
        aoColumns = [
            {
                'mData': 'paySum',
                'sName': 'paySum',
            },
            {
                'mData': 'payDate',
                'sName': 'payDate',
            },
            {
                'mData': 'idTransactions',
                'sName': 'idTransactions',
            }
        ];
        caption = 'История оплат транзакций';

        ReclsPayments.table = $('#paymentsHistory').dataTable( {
            "fnDrawCallback": function(){self.actionsPrepare(self);},
            "aoColumns": aoColumns,
            "aaSorting": [[1, "desc"]],
            "sPaginationType": "bootstrap",
            "bLengthChange": true,
            "aLengthMenu": [20, 50, 100, 200, 500],
            "iDisplayLength": 20,
            "bProcessing": true,
            "bDestroy": true,
            "bFilter": false,
            "bInfo": false,
            "sDom": "frt<Lip>",
            "bServerSide": true,
            "sAjaxSource": url,
            "sServerMethod": "POST",
            "fnServerData": SMP.makeRequestDT
        } );
    },


    // prepare actions of the table, calls after table loaded
    actionsPrepare: function (self) {

    },

    render: function (idRecl, idCampaign) {
        var self = this;
        self.idRecl = idRecl;
        self.idCampaign = idCampaign;

        SMP.makeRequest(
            '/recls/payments/payments',
            {
                idRecl: self.idRecl,
                idCampaign: self.idCampaign
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.html(data.html);
                    if (self.useDialog)
                    {
                        self.$el.off( "dialogclose" );
                        self.$el.on( "dialogclose", function( event, ui ) {
                            campaignsView[self.idRecl].render(self.idRecl);
                        } );
                        self.$el.dialog('open');
                    }
                    self.prepareView();
                    return self;
                }
            }
        );
    }
});

reclsPayments = new ReclsPayments();
