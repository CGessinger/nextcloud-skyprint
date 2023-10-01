var cloudprintFileListPlugin = {
    attach: function (fileList) {
        if (fileList.id === 'trashbin' || fileList.id === 'files.public') {
            return;
        }

        fileList.registerTabView(new OCA.CloudPrint.CloudPrintTabView());
    }
};
OC.Plugins.register('OCA.Files.FileList', cloudprintFileListPlugin);


console.debug('CloudPrint plugin loaded');