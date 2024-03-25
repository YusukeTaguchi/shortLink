(function () {

    FTX.RedirectLinks = {

        list: {
        
            selectors: {
                redirect_links_table: $('#redirect-links-table'),
            },
        
            init: function () {

                this.selectors.redirect_links_table.dataTable({

                    processing: false,
                    serverSide: true,
                    ajax: {
                        url: this.selectors.redirect_links_table.data('ajax_url'),
                        type: 'post',
                    },
                    columns: [

                        { data: 'domain', name: 'domain' },
                        { data: 'url', name: 'url' },
                        { data: 'status', name: 'status' },
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