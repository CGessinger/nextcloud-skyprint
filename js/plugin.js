var skyprintFileListPlugin = {
    attach: function (fileList) {
        if (fileList.id === 'trashbin' || fileList.id === 'files.public') {
            return;
        }

        fileList.registerTabView(new OCA.SkyPrint.SkyPrintTabView());
    }
};
OC.Plugins.register('OCA.Files.FileList', skyprintFileListPlugin);