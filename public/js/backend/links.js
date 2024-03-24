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
                        { 
                            data: 'thumbnail_image', 
                            name: 'links.thumbnail_image',
                            render: function(data, type, full, meta) {
                                return '<div class="col-lg-1"><img src="/storage/img/link/'+data+'" height="80" width="80"></div>';
                            }
                        },
                        { 
                            data: 'slug', 
                            name: 'links.slug',
                            render: function(data, type, full, meta) {
                                return '<div class="slug-value">' + full.url + '/' + data + '</div> <button class="copy-btn"><i class="fas fa-copy"></i></button>';
                            }
                        },
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

                this.selectors.links_table.on('click', '.copy-btn', function() {
                    var slugValue = $(this).closest('tr').find('.slug-value').text();
                    copyToClipboard(slugValue);
                    alert('Slug copied: ' + slugValue);
                });
            }
        },

        edit: {
            selectors: {
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

    function copyToClipboard(text) {
        var input = document.createElement('textarea');
        input.innerHTML = text;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
    }
})();