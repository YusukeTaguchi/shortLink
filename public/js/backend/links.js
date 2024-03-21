(function () {

    FTX.Links = {

        list: {

            selectors: {
                links_table: $('#links-table'),
            },

            init: function () {

                this.selectors.links_table.dataTable({

                    processing: false,
                    serverSide: true,
                    ajax: {
                        url: this.selectors.links_table.data('ajax_url'),
                        type: 'post',
                    },
                    columns: [

                        { data: 'title', name: 'links.title' },
                        { data: 'display_status', name: 'links.status' },
                        { data: 'created_by', name: 'links.created_by' },
                        { data: 'created_at', name: 'links.created_at' },
                        { data: 'actions', name: 'actions', searchable: false, sortable: false }

                    ],
                    order: [[3, "asc"]],
                    searchDelay: 500,
                    "createdRow": function (row, data, dataIndex) {
                        FTX.Utils.dtAnchorToForm(row);
                    }
                });
            }
        },

        edit: {
            selectors: {,
                status: jQuery(".status"),
            },

            init: function (locale) {
                this.addHandlers(locale);
                FTX.tinyMCE.init(locale);
            },

            addHandlers: function (locale) {


                this.selectors.status.select2({
                    width: '100%'
                });
            },
        },
    }
})();