(function () {
    var SkyPrintTabView = OCA.Files.DetailTabView.extend({
        id: 'skyprintTabView',
        className: 'tab skyprintTabView',

        getLabel: function () {
            return t('skyprint', 'SkyPrint');
        },

        getIcon: function () {
            return 'icon-category-tools';
        },

        render: function () {
            const getPrintersUrl = OC.generateUrl('/apps/skyprint/printers');

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

            const nupOptions = [
                '<option value="1">1</option>',
                '<option value="2">2</option>',
                '<option value="4">4</option>',
                '<option value="6">6</option>',
                '<option value="9">9</option>',
                '<option value="16">16</option>',
            ]

            this.$el.html(
                '<form style="display: flex; flex-direction: column;" id="printer-options"/>' +
                '<div>Printer</div>' +
                '<select id="printer">' + printerOptions.join('') + '</select>' +
                '<div>Copies</div>' +
                '<input type="number" id="copies" value="1"/>' +
                '<div>Orientation</div>' +
                '<select id="orientation">' +
                '<option value="3">Portrait</option> <option value="4">Landscape</option> <option value="5">Reverse Landscape</option> <option value="6">Reverse Portrait</option>' +
                '</select>' +
                '<div>Range (leave empty for all pages)</div>' +
                '<input type="number" id="range" value="" placeholder="e.g. 2-6,9,12-16"/>' +
                '<div>Papersize</div>' +
                '<select id="media">' +
                '<option value="a4">A4</option> <option value="letter">Letter</option> <option value="Legal">Legal</option>' +
                '</select>' +
                '<div>Documents per Page</div>' +
                '<select id="nup">' +
                nupOptions.join(' ') +
                '</select>' +
                '<input type="submit" name="submitButton" value="Print">' +
                '</form>'
            );

            this.delegateEvents({
                'submit #printer-options': 'submitPrint'
            });
        },

        submitPrint: function (event) {
            event.preventDefault();

            const fileInfo = this.getFileInfo();
            const printfileUrl = OC.generateUrl('/apps/skyprint/printfile');

            const data = {
                printer: event.target.printer.value,
                file: fileInfo.getFullPath(),
                copies: event.target.copies.value,
                orientation: event.target.orientation.value,
                media: event.target.media.value,
                range: event.target.range.value,
                nup: event.target.nup.value,
            };

            $.ajax({
                type: 'POST',
                url: printfileUrl,
                dataType: 'json',
                data: data,
                async: true,
                success: function (data) {
                    OC.Notification.show(data.message);
                }
            });
        },

        canDisplay: function (fileInfo) {
            if (!fileInfo || fileInfo.isDirectory()) {
                return false;
            }
            var mimetype = fileInfo.get('mimetype') || '';

            return (['image/gif', 'image/heic', 'image/jpeg', 'image/png', 'image/tiff', 'image/x-dcraw',
                'application/pdf', 'text/plain'].indexOf(mimetype) > -1);
        },
    });

    OCA.SkyPrint = OCA.SkyPrint || {};

    OCA.SkyPrint.SkyPrintTabView = SkyPrintTabView;
})();