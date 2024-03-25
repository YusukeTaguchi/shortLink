(function () {

    FTX.Settings = {

        list: {
        
            selectors: {
                settings_table: $('#settings-table'),
            },
        
            init: function () {

                this.selectors.settings_table.dataTable({

                    processing: false,
                    serverSide: true,
                    ajax: {
                        url: this.selectors.settings_table.data('ajax_url'),
                        type: 'post',
                    },
                    columns: [
                        { data: 'auto_redirect_type', name: 'auto_redirect_type' },
                        { data: 'auto_redirect_to', name: 'auto_redirect_to' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'actions', name: 'actions', searchable: false, sortable: false }

                    ],
                    order: [[0, "asc"]],
                    searchDelay: 500,
                    "createdRow": function (row, data, dataIndex) {
                        FTX.Utils.dtAnchorToForm(row);
                    }
                });
            }
        },

        edit: {
            init: function (locale) {                
            }
        },
    }
})();