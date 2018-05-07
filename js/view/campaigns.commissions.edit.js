var CommissionsEdit = Backbone.View.extend({
    prefix: 'commissionsEdit',
    useDialog: false,
    idCampaign: null,

    events: {
        "submit #commissionsEditForm": "submit"
    },

    submit: function (event) {
        var self = this;
        event.preventDefault();
        var formData = $(event.currentTarget).serializeObject();
        SMP.makeRequest(
            '/campaigns/commissions/edit',
            formData,
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.dialog('close');
                    commissionsView[self.idCampaign].render(self.idCampaign);
                }
            },
            ''
        );
    },

    split: function ( val ) {
        return val.split( /,\s*/ );
    },

    extractLast: function( term ) {
        return this.split( term ).pop();
    },

    prepareView: function () {
        var self = this;
        //$("#campaignRecls").combobox();
        //$("#commissionTarget").combobox();

        $("#commissionCountries").chosen();

        /*$("#commissionCountries")
         .bind( "keydown", function( event ) {
            if ( event.keyCode === $.ui.keyCode.TAB &&
                $( this ).data( "ui-autocomplete" ).menu.active )
            {
                event.preventDefault();
            }
        })
        .autocomplete({
            source: function( request, response ) {
                $.getJSON( '/campaigns/campaigns/countrieslist', {
                    term: self.extractLast( request.term )
                }, response );
            },
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            select: function( event, ui ) {
                var terms = self.split( this.value );
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push( ui.item.value );
                // add placeholder to get the comma-and-space at the end
                terms.push( "" );
                this.value = terms.join( ", " );
                return false;
            },
            change: function (event, ui) {
                if(!ui.item){
                    // The item selected from the menu, if any. Otherwise the property is null
                    //so clear the item for force selection
                    var terms = self.split( this.value );
                    // remove the current input
                    terms.pop();
                    terms.push( "" );
                    this.value = terms.join( ", " );
                }
            }
        });
        */
    },


    render: function (idCampaign, idCommission) {
        var self = this;
        self.idCampaign = idCampaign;

        SMP.makeRequest(
            '/campaigns/commissions/edit',
            {
                id: idCommission
            },
            'json',
            function (data) {
                if (SMP.isset(data.result) && (data.result == 1)) {
                    self.$el.html(data.html);
                    self.$el.dialog('open');
                    self.prepareView();
                    return self;
                }
            }
        );
    }
});

commissionsEdit = new CommissionsEdit();
