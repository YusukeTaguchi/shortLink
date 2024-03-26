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
                        { data: 'id', name: 'links.id' },
                        { data: 'title', name: 'links.title' },
                        { 
                            data: 'thumbnail_image', 
                            name: 'links.thumbnail_image',
                            render: function(data, type, full, meta) {
                                if(data){
                                    return '<div class="col-lg-1"><img src="/storage/img/link/'+data+'" height="80" width="80"></div>';
                                }
                                return '<div class="col-lg-1"></div>';
                                
                            }
                        },
                        {
                            data: 'fake',
                            name: 'links.fake',
                            render: function(data, type, full, meta) {
                                var button = '';
                                var debuger_url = 'https://developers.facebook.com/tools/debug/?q=' + full.url + '/' + full.slug;
                                var button_debuger = '<a target="_blank" href="'+ debuger_url +'" data-toggle="tooltip" data-placement="top" title="Facebook Debugger" class="btn btn-primary"><i class="fa fa-facebook-f"></i></a>';
                                if (data === 1) {
                                    button = '<a href="/admin/links/'+ full.id +'/fake/0" data-toggle="tooltip" data-placement="top" title="Sync" class="btn btn-primary btn-sm mr-1"><i class="fas fa-toggle-off"></i></a>';
                                    return '<div class="fake-value btn btn-danger rounded-pill btn-sm mr-1" style="color:blue; font-weight: bold">' + (data === 1 ? 'ON' : 'OFF') + '</div>' + '<div style="margin-top:2px">' + button + button_debuger + '</div>'
                                } else {
                                    button = '<a href="/admin/links/'+ full.id +'/fake/1" data-toggle="tooltip" data-placement="top" title="Sync" class="btn btn-primary btn-sm mr-1"><i class="fas fa-toggle-on"></i></a>';
                                    return '<div class="fake-value btn btn-light rounded-pill  btn-sm mr-1" style="color:blue; font-weight: bold">' + (data === 1 ? 'ON' : 'OFF') + '</div>' + '<div style="margin-top:2px">' + button + button_debuger + '</div>'
                                }
                                
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
                        { data: 'total_viewed', name: 'links.total_viewed', searchable: false , sortable: false  },
                        { data: 'created_by', name: 'links.created_by' },
                        { data: 'created_at', name: 'links.created_at' },
                        { data: 'actions', name: 'actions', searchable: false, sortable: false }

                    ],
                    order: [[0, "desc"]],
                    searchDelay: 500,
                    "createdRow": function (row, data, dataIndex) {
                        FTX.Utils.dtAnchorToForm(row);
                    }
                });

                this.selectors.links_table.on('click', '.copy-btn', function() {
                    var slugValue = $(this).closest('tr').find('.slug-value').text();
                    copyToClipboard(slugValue);
                });
            }
        },

        edit: {
            selectors: {
                status: jQuery(".status"),
            },

            init: function (locale) {
                this.addHandlers(locale);
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