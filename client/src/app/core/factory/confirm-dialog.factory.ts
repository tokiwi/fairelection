import { Injectable } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { ConfirmDialogComponent } from '../../shared/confirm-dialog/confirm-dialog.component';


@Injectable()
export class ConfirmDialogFactory {
    constructor(
        private dialog: MatDialog
    ) {
    }

    public create(
        title: string = null,
        content: string = null,
        confirm: string = null,
        cancel: string = null,
        cancelColor = 'default',
        confirmColor = 'warn'
    ): MatDialogRef<any, any> {
        return this.dialog.open(ConfirmDialogComponent, {
            maxWidth: '500px',
            data: {
                title: title || 'dialog.confirm_dialog.title',
                content: content || 'dialog.confirm_dialog.content',
                confirm: confirm || 'dialog.confirm_dialog.confirm',
                cancel: cancel || 'dialog.confirm_dialog.cancel',
                cancelColor: cancelColor || 'default',
                confirmColor: confirmColor || 'warn',
            }
        });
    }
}
