(function () {
    var CloudPrintTabView = OCA.Files.DetailTabView.extend({
        id: 'cloudprintTabView',
        className: 'tab cloudprintTabView',

        getLabel: function () {
            return t('cloudprint', 'CloudPrint');
        },

        getIcon: function () {
            return 'icon-category-tools';
        },

        render: function () {
            const fileInfo = this.getFileInfo();
            const printfileUrl = OC.generateUrl('/apps/cloudprint/printfile');
            const getPrintersUrl = OC.generateUrl('/apps/cloudprint/printers');

            let printers = [];
            $.ajax({
                type: 'GET',
                url: getPrintersUrl,
                dataType: 'json',
                async: false,
                success: function (data) {
                    printers = data.printers;
                }
            });

            const printerOptions = printers.map(printer => {
                return `<option value="${printer.id}">${printer.name}</option>`;
            });

            this.$el.html(
                '<form style="display: flex; flex-direction: column;"/>' +
                '<div>Printer</div>' +
                '<select id="printer">' + printerOptions.join('') + '</select>' +
                '<div>Copies</div>' +
                '<input type="number" id="copies" value="1"/>' +
                '<div>Orientation</div>' +
                '<select id="orientation">' +
                '<option value="3">Portrait</option> <option value="4">Landscape</option> <option value="5">Reverse Landscape</option> <option value="6">Reverse Portrait</option>' +
                '</select>' +
                '<div>Papersize</div>' +
                '<select id="media">' +
                '<option value="a4">A4</option> <option value="letter">Letter</option> <option value="Legal">Legal</option>' +
                '</select>' +
                '<input type="submit" name="submitButton" value="Print">' +
                '</form>'
            );

            this.$el.on('submit', 'form', function (event) {
                event.preventDefault();

                const data = {
                    printer: event.target.printer.value,
                    file: fileInfo.getFullPath(),
                    copies: event.target.copies.value,
                    orientation: event.target.orientation.value,
                    media: event.target.media.value,
                };

                $.ajax({
                    type: 'GET',
                    url: printfileUrl,
                    dataType: 'json',
                    data: data,
                    async: true,
                    success: function (data) {
                        console.debug('success', data);
                    }
                });
            });
        },

        canDisplay: function (fileInfo) {
            if (!fileInfo || fileInfo.isDirectory()) {
                return false;
            }
            var mimetype = fileInfo.get('mimetype') || '';

            return (['image/gif', 'image/heic', 'image/jpeg', 'image/png', 'image/tiff', 'image/x-dcraw',
                'application/pdf'].indexOf(mimetype) > -1);
        },
    });

    OCA.CloudPrint = OCA.CloudPrint || {};

    OCA.CloudPrint.CloudPrintTabView = CloudPrintTabView;

    console.debug('CloudPrint tabview loaded');
})();