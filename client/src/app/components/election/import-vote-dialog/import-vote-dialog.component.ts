import { Component, ElementRef, Inject, OnInit, ViewChild } from '@angular/core';
import { IriUtil } from '../../../core/utils/iri.util';
import { finalize, take } from 'rxjs/operators';
import { DownloaderHelper } from '../../../core/utils/download.util';
import { HtmlInputEvent } from '../../../core/events/html-input-event';
import { HttpParamUtil } from '../../../core/utils/http-param.util';
import { Election } from '../../../core/models/election.model';
import { NotificationService } from '../../../core/services/notification.service';
import { ConfirmDialogFactory } from '../../../core/factory/confirm-dialog.factory';
import { ElectionService } from '../../../core/services/election.service';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';

@Component({
    selector: 'app-import-vote-dialog',
    templateUrl: './import-vote-dialog.component.html',
    styleUrls: ['./import-vote-dialog.component.scss']
})
export class ImportVoteDialogComponent implements OnInit {
    @ViewChild('fileInput', {static: false})
    public fileInput: ElementRef;

    public loading = false;
    public loadingModel = false;
    public loadingUploadModel = false;

    public election: Election;

    constructor(
        private notificationService: NotificationService,
        private confirmDialogFactory: ConfirmDialogFactory,
        private electionService: ElectionService,
        public dialogRef: MatDialogRef<ImportVoteDialogComponent>,
        @Inject(MAT_DIALOG_DATA) public data: {election: Election}
    ) {
        this.election = data.election;
    }

    ngOnInit(): void {
    }

    public downloadModel(): void {
        this.loadingModel = true;

        this.electionService.downloadVoteModel(IriUtil.extractId(this.election['@id'])).pipe(
            take(1),
            finalize(() => this.loadingModel = false)
        ).subscribe((response: Response) => {
            DownloaderHelper.forceDownload(response, 'model.xlsx');
        });
    }

    public uploadModel(): void {
        const nativeElement = this.fileInput.nativeElement as HTMLInputElement;
        nativeElement.click();
    }

    public onNewFileSelected(event: HtmlInputEvent): void {
        const httpParams = HttpParamUtil.create()
            .add('election', IriUtil.extractId(this.election['@id']));

        // stackoverflow.com/q/25333488/ (convert target.files to Array)
        for (const file of Array.prototype.slice.call(event.target.files)) {
            this.loadingUploadModel = true;

            this.electionService.uploadVoteModel(file, httpParams.getHttpParams()).pipe(
                take(1),
                finalize(() => this.loadingUploadModel = false)
            ).subscribe(() => {
                this.notificationService.success('toast.candidates_successfully_imported');
                this.dialogRef.close(true);
            });
        }

        this.clearFileInput();
    }

    private clearFileInput(): void {
        const nativeElement = this.fileInput.nativeElement as HTMLInputElement;
        nativeElement.value = '';
    }
}
