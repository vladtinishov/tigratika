@extends('layouts.admin')

@section('content')
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="ml-2">
                    <button type="button" data-toggle="modal" data-target="#import-modal" class="btn btn-block btn-secondary">Импорт</button>
                </div>
            </div>
            <div id="data-table" class="mt-3"></div>
        </div>
        <!-- /.card-body -->
    </div>

    <div class="modal fade" id="import-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Импортировать файл по ссылке:</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>https://quarta-hunt.ru/bitrix/catalog_export/export_Ngq.xml</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-primary" type="button" id="import-modal_accept-button">
                        <span
                            class="spinner-border spinner-border-sm d-none mr-1"
                            id="import-modal_accept-button_spinner"
                            aria-hidden="true"
                        >
                        </span>
                        <span class="visually-hidden" role="status">Импорт</span>
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="import-modal_cancel-button_spinner">
                        Отмена
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/jsgrid/jsgrid.min.js') }}"></script>
    <script>
        const $importButton = $("#import-modal_accept-button");
        const $importButtonSpinner = $("#import-modal_accept-button_spinner");
        const $importCancelButton = $("#import-modal_cancel-button_spinner");

        $("#data-table").jsGrid({
            width: "100%",
            height: "600px",

            paging: true,

            data: [],
            autoload: true,

            pageSize: 10,

            controller: {
                loadData(filter) {
                    return $.ajax({
                        type: "GET",
                        url: "{{ route("products.indexJson") }}",
                        data: filter
                    });
                },
            },

            fields: [
                {
                    title: "#",
                    name: "id",
                    itemTemplate: function(value) {
                        return '#' + value;
                    }
                },
                {
                    title: "Name",
                    name: "name",
                    type: "text",
                    itemTemplate: function(value, item) {
                        const $image = $(`<img class="img-fluid rounded" src="${item.picture}" />`)
                        const $container = $(`<div></div>`);
                        $container.append($image);
                        $container.append(`<span>${value}</span>`);
                        return $(`<a href="${item.url}"></a>`).append($container);
                    }
                },
                { title: "Available", name: "available", type: "checkbox" },
                { title: "Price", name: "price", type: "number" },
                { title: "Old Price", name: "oldprice", type: "number" },
                { title: "Currency", name: "currency_id", type: "text" },
                { title: "Vendor", name: "vendor", type: "text" },
                { title: "Category", name: "category", type: "text" },
                { title: "Sub category", name: "sub_category", type: "text", width: 150 },
                { title: "Sub sub category", name: "sub_sub_category", type: "text", width: 200 },
            ]
        });

        $importButton.on("click", function () {
            makeImportButtonLoading();

            const token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('products.convert') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': token
                },
                responseType: 'arraybuffer',
                success(response, status, xhr) {
                    const blob = new Blob([response], { type: xhr.getResponseHeader("content-type") });
                    const url = window.URL.createObjectURL(blob);

                    const downloadLink = $("<a>")
                        .attr("href", url)
                        .attr("download", "output.xlsx")
                        .css("display", "none");

                    $("body").append(downloadLink);
                    downloadLink[0].click();

                    window.URL.revokeObjectURL(url);
                    downloadLink.remove();

                    window.location.reload();
                },
                error(error) {
                    makeImportButtonStatic()
                    console.error("AJAX request failed", error);
                }
            });
        });

        function makeImportButtonLoading() {
            $importButtonSpinner.removeClass("d-none");
            $importButton.addClass("disabled");
            $importCancelButton.addClass("disabled")
        }

        function makeImportButtonStatic() {
            $importButtonSpinner.addClass("d-none");
            $importButton.removeClass("disabled");
            $importCancelButton.removeClass("disabled")
        }

    </script>
@endsection
