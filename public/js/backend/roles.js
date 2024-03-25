(function () {

    FTX.Roles = {

        list: {

            selectors: {
                role_table: $('#roles-table'),
            },

            init: function () {

                this.selectors.role_table.dataTable({

                    processing: false,
                    serverSide: true,
                    ajax: {
                        url: this.selectors.role_table.data('ajax_url'),
                        type: 'post'
                    },
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'users', name: 'users', searchable: false, sortable: false },
                        { data: 'actions', name: 'actions', searchable: false, sortable: false }
                    ],
                    order: [[2, "asc"]],
                    searchDelay: 500,
                    "createdRow": function (row, data, dataIndex) {
                        FTX.Utils.dtAnchorToForm(row);
                    }
                });
            }
        },

       
    }
})();