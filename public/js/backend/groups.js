(function () {

    FTX.Groups = {

        list: {
        
            selectors: {
                groups: $('#groups-table'),
            },
        
            init: function () {

                this.selectors.groups.dataTable({

                    processing: false,
                    serverSide: true,
                    ajax: {
                        url: this.selectors.groups.data('ajax_url'),
                        type: 'post',
                    },
                    columns: [

                        { data: 'name', name: 'name' },
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