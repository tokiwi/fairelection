import { Directive, EventEmitter, HostListener, Input, Output } from '@angular/core';
import { Subscription } from 'rxjs';
import { ConfirmDialogFactory } from '../factory/confirm-dialog.factory';

@Directive({
    selector: '[appConfirmDialog]'
})
export class ConfirmDialogDirective {
    @Input()
    dialogTitle = 'dialog.confirm_dialog.title';

    @Input()
    dialogContent = 'dialog.confirm_dialog.content';

    @Input()
    dialogConfirm = 'dialog.confirm_dialog.confirm';

    @Input()
    dialogCancel = 'dialog.confirm_dialog.cancel';

    @Output()
    confirmClick = new EventEmitter();

    @Output()
    cancelClick = new EventEmitter();

    public subsc: Subscription = new Subscription();

    constructor(
        private confirmDialogFactory: ConfirmDialogFactory
    ) {

    }

    @HostListener('click', ['$event'])
    public clickEvent(): void {
        const dialogRef = this.confirmDialogFactory.create(this.dialogTitle, this.dialogContent, this.dialogConfirm, this.dialogCancel);

        dialogRef.afterClosed().subscribe((res) => {
            if (res) {
                this.confirmClick.emit();
            } else {
                this.cancelClick.emit();
            }
        });
    }
}
