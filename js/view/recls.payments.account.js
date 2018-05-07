var ReclsAccount = Backbone.View.extend({
    prefix: 'reclsAccount',
    useDialog: true,
    table: null,
    idRecl: null,
    events: {
        "submit .accountPaymentForm": "addAccountPayment"
    },

    addAccountPayment: function (event) {
        var self = this;
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();

        SMP.makeRequest(
            '/recls/payments/addaccountpayment',
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
        $('#accountHistory').dataTable().fnDraw(false);
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
        url = '/recls/payments/accounthistory/id/' + this.idRecl;
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
                'mData': 'paySystem',
                'sName': 'paySystem',
            },
            {
                'bSortable': true,
                'mData': 'approve' ,
                'sName': 'approve',
                'mRender': this.statusFormatter
            },
            {
                'mData': 'descr',
                'sName': 'descr',
            }
        ];
        caption = 'История пополнения счета';

        ReclsAccount.table = $('#accountHistory').dataTable( {
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

    // formatter for status column in the table
    statusFormatter: function (data, type, row) {
        var st = '';
        var icn = '';
        var li = '';
        var titl = '';
        var cid = 'raid="' + row.idReclAccount + '"';
        if (data == "1")
        {
            titl = 'Проверен';
            icn = '<i class="fa fa-check-circle text-success font17"></i>';
            li = '<li><a href="javascript:void(0)" ' + cid + ' class="_changeAccountPaymentApprove" change_to="0"><i class="fa fa-minus-circle text-danger "></i> Не проверен</a></li>';
        }

        if (data == "0")
        {
            titl = 'Не проверен';
            icn = '<i class="fa fa-minus-circle text-danger font17"></i>';
            li = '<li><a href="javascript:void(0)" ' + cid + ' class="_changeAccountPaymentApprove" change_to="1"><i class="fa fa-check-circle text-success "></i> Проверен</a></li>';
        }

        st = st + '<div class="btn-group">' +
                    '<button title="' + titl + '" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button">' +
                        icn + '&nbsp;<span class="caret"></span>' +
                    '</button>' +
                    '<ul style="width: auto;" class="dropdown-menu">' +
                        li +
                    '</ul></div>';
        return st;
    },

    // prepare actions of the table, calls after table loaded
    actionsPrepare: function (self) {
        $('._changeAccountPaymentApprove').off('click');
        $('._changeAccountPaymentApprove').on('click', function (event) {
            SMP.makeRequest(
                '/recls/payments/changeaccountpaymentapprove',
                {
                    id: $(this).attr('raid'),
                    change_to: $(this).attr('change_to')
                },
                'json',
                function (data) {
                    if (SMP.isset(data.result) && (data.result == 1)) {
                        //$('#accountHistory').dataTable().fnDraw(false);
                        self.refreshTable();
                    }
                }
            );
        });
    },

    render: function (idRecl) {
        var self = this;
        self.idRecl = idRecl;

        SMP.makeRequest(
            '/recls/payments/account',
            {
                id: self.idRecl
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.html(data.html);
                    if (self.useDialog)
                    {
                        self.$el.off( "dialogclose" );
                        self.$el.on( "dialogclose", function( event, ui ) {
                            $('#recls').dataTable().fnDraw();
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

reclsAccount = new ReclsAccount();
