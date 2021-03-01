import * as FS from 'file-saver';

export class DownloaderHelper {
    public static forceDownload(blob: Blob|Response, originalName: string): void {
        (FS as FileSaver).saveAs(blob, originalName);
    }
}

interface FileSaver {
    // Unused interface -> declares the saveAs function from FileSaver library
    saveAs(blob, name, opts?, popup?): any;
}
