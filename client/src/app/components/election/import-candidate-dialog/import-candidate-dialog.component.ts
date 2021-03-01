import { Component, ElementRef, Inject, OnInit, ViewChild } from '@angular/core';
import { finalize, take } from 'rxjs/operators';
import { HtmlInputEvent } from '../../../core/events/html-input-event';
import { HttpParamUtil } from '../../../core/utils/http-param.util';
import { IriUtil } from '../../../core/utils/iri.util';
import { NotificationService } from '../../../core/services/notification.service';
import { ConfirmDialogFactory } from '../../../core/factory/confirm-dialog.factory';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { Election } from '../../../core/models/election.model';
import { ElectionService } from '../../../core/services/election.service';
import { DownloaderHelper } from '../../../core/utils/download.util';

@Component({
    selector: 'app-import-candidate-dialog',
    templateUrl: './import-candidate-dialog.component.html',
    styleUrls: ['./import-candidate-dialog.component.scss']
})
export class ImportCandidateDialogComponent implements OnInit {
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
        public dialogRef: MatDialogRef<ImportCandidateDialogComponent>,
        @Inject(MAT_DIALOG_DATA) public data: {election: Election}
    ) {
        this.election = data.election;
    }

    ngOnInit(): void {
    }

    public downloadModel(): void {
        this.loadingModel = true;

        this.electionService.downloadCandidateModel(IriUtil.extractId(this.election['@id'])).pipe(
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

            this.electionService.uploadCandidateModel(file, httpParams.getHttpParams()).pipe(
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
