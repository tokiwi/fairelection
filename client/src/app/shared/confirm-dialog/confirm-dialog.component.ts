import { Component, Inject, OnInit } from '@angular/core';
import { MAT_DIALOG_DATA } from '@angular/material/dialog';
import { ConfirmDialogService } from '../../core/services/confirm-dialog.service';

@Component({
    selector: 'app-confirm-dialog',
    templateUrl: './confirm-dialog.component.html',
    styleUrls: ['./confirm-dialog.component.scss'],
})
export class ConfirmDialogComponent implements OnInit {
    constructor(
        private confirmDialogService: ConfirmDialogService,
        // eslint-disable-next-line @typescript-eslint/explicit-module-boundary-types
        @Inject(MAT_DIALOG_DATA) public data
    ) { }

    ngOnInit(): void {
    }

    public cancel(): void {
        this.confirmDialogService.emitCancelClick();
    }

    public confirm(): void {
        this.confirmDialogService.emitConfirmClick();
    }
}
