var CommissionsPrices = Backbone.View.extend({
    prefix: 'groupsEdit',
    useDialog: true,
    idCampaign: null,
    events: {
        "submit .addPriceForm": "addPrice"
    },

    addPrice: function (event) {
        var self = this;
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();

        SMP.makeRequest(
            '/campaigns/commissions/addprice',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.refreshTable();
                }
            },
            'users.openEdit'
        );
    },

    refreshTable: function () {
        var self = this;

        SMP.makeRequest(
            '/campaigns/commissions/priceslist'                                                ,
            {
                id: self.idCommission
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    $('#csprices' + self.idCommission).replaceWith(data.html);
                    self.prepareEditCells();
                }
            },
            'users.openEdit'
        );
    },

    prepareView: function () {
        var self = this;

        var startDateTextBox = $('#validFrom');
        var endDateTextBox = $('#validTo');

        startDateTextBox.datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss',
            onClose: function(dateText, inst) {
                if (endDateTextBox.val() != '') {
                    var testStartDate = startDateTextBox.datetimepicker('getDate');
                    var testEndDate = endDateTextBox.datetimepicker('getDate');
                    if (testStartDate > testEndDate)
                        endDateTextBox.datetimepicker('setDate', testStartDate);
                }
                else {
                    endDateTextBox.val(dateText);
                }
            },
            onSelect: function (selectedDateTime){
                endDateTextBox.datetimepicker('option', 'minDate', startDateTextBox.datetimepicker('getDate') );
            }
        });
        endDateTextBox.datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss',
            onClose: function(dateText, inst) {
                if (startDateTextBox.val() != '') {
                    var testStartDate = startDateTextBox.datetimepicker('getDate');
                    var testEndDate = endDateTextBox.datetimepicker('getDate');
                    if (testStartDate > testEndDate)
                        startDateTextBox.datetimepicker('setDate', testEndDate);
                }
                else {
                    startDateTextBox.val(dateText);
                }
            },
            onSelect: function (selectedDateTime){
                startDateTextBox.datetimepicker('option', 'maxDate', endDateTextBox.datetimepicker('getDate') );
            }
        });

        self.prepareEditCells();
    },

    prepareEditCells: function (idCampaign, idCommission) {
        var self = this;

        self.$el.find(".editPriceData").editable(
            "/campaigns/commissions/editpricefield/",
            {
                "submitdata": function(value, settings) {
                    return {
                        "idCommissionPrice": $(this).attr("cpid"),
                        "fname": $(this).attr("fname")
                    };
                },
                "callback" : function(value, settings) {
                    var json = $.parseJSON(value);
                    if (json.result)
                    {
                        $(this).text(json.value);
                    }
                    else
                    {
                        $(this).text("");
                        $.showErrorBox(json.errors);
                    }
                },
                "height": "14px",
                "type": "custom_input",
                "onblur": "submit"
            }
        );

        self.$el.find(".editPriceValidDate").editable(
            "/campaigns/commissions/editpricefield/",
            {
                "submitdata": function(value, settings) {
                    return {
                        "idCommissionPrice": $(this).attr("cpid"),
                        "fname": $(this).attr("fname")
                    };
                },
                "callback" : function(value, settings) {
                    var json = $.parseJSON(value);
                    if (json.result)
                    {
                        $(this).text(json.value);
                    }
                    else
                    {
                        $(this).text("");
                        $.showErrorBox(json.errors);
                    }
                },
                "height": "14px",
                "type": "datetimepicker",
                "onblur": "submit"
            }
        );
    },

    render: function (idCampaign, idCommission) {
        var self = this;
        self.idCampaign = idCampaign;
        self.idCommission = idCommission;

        SMP.makeRequest(
            '/campaigns/commissions/prices',
            {
                id: self.idCommission
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.html(data.html);
                    if (self.useDialog)
                    {
                        self.$el.on( "dialogclose", function( event, ui ) {
                            commissionsView[self.idCampaign].render(self.idCampaign); 
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

commissionsPrices = new CommissionsPrices();
